<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

/**
 * SSO回调控制器
 * 处理园艺库账号登录资源库的OAuth流程
 * 支持两个入口：admin（后台管理）和 cms（前端用户）
 */
class SsoCallbackController extends Controller
{
    /**
     * T039: 重定向到SSO登录页（指定account_type=horticultural）
     * 接收 from 参数（admin/cms）和 tenant 参数（cms来源时的租户slug）
     */
    public function redirect(Request $request)
    {
        $from = $request->query('from', 'cms');
        $tenant = $request->query('tenant');

        session(['sso_from' => $from]);
        if ($tenant) {
            session(['sso_tenant' => $tenant]);
        }

        Log::info('SSO Redirect', [
            'from' => $from,
            'tenant' => $tenant,
            'session_id' => session()->getId(),
        ]);

        return Socialite::driver('sso')
            ->with(['account_type' => 'horticultural'])
            ->redirect();
    }

    /**
     * T040/T041: SSO回调处理
     * 交换授权码获取token，获取用户信息，创建/查找本地用户
     */
    public function callback(Request $request)
    {
        $from = session('sso_from', 'cms');
        $tenant = session('sso_tenant');

        Log::info('SSO Callback', [
            'from' => $from,
            'tenant' => $tenant,
            'session_id' => session()->getId(),
            'request_state' => $request->get('state'),
        ]);

        // 清理session中的SSO临时数据
        session()->forget(['sso_from', 'sso_tenant']);

        $errorRedirect = $from === 'admin'
            ? '/admin/login'
            : ($tenant ? "/cms/{$tenant}/login" : '/admin/login');

        try {
            $ssoUser = Socialite::driver('sso')->user();
        } catch (\Exception $e) {
            Log::error('SSO callback failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect($errorRedirect)->with('error', 'SSO登录失败: ' . $e->getMessage());
        }

        $ssoUserId = $ssoUser->getId();
        $ssoUsername = $ssoUser->getName();

        // 查找已有本地用户（通过 source_platform + source_user_id 映射）
        $user = User::where('source_platform', 'horticultural')
            ->where('source_user_id', $ssoUserId)
            ->first();

        if ($user) {
            return $this->loginAndRedirect($user, $from, $tenant);
        }

        // 创建新的本地用户
        $user = $this->createLocalUser($ssoUserId, $ssoUsername, $ssoUser->getEmail(), $from);

        return $this->loginAndRedirect($user, $from, $tenant);
    }

    /**
     * 创建本地用户，根据来源决定 user_type
     */
    private function createLocalUser(string $ssoUserId, ?string $ssoUsername, ?string $email, string $from): User
    {
        $name = $ssoUsername ?: 'hort_user_' . $ssoUserId;
        $emailAddr = $email ?: $ssoUsername . '@horticultural.local';

        // 检查email冲突
        if (User::where('email', $emailAddr)->exists()) {
            $emailAddr = 'hort_' . $ssoUserId . '@horticultural.local';
        }

        $userType = $from === 'admin' ? 'admin' : 'user';

        return User::create([
            'name' => $name,
            'email' => $emailAddr,
            'password' => bcrypt(bin2hex(random_bytes(16))),
            'user_type' => $userType,
            'source_platform' => 'horticultural',
            'source_user_id' => $ssoUserId,
        ]);
    }

    /**
     * 登录用户并根据来源重定向
     * admin → guard('admin') + 关联Team → /admin
     * cms → guard('web') → /cms/{tenant}
     */
    private function loginAndRedirect(User $user, string $from, ?string $tenant)
    {
        if ($from === 'admin') {
            // 已有用户从admin入口登录，但user_type不是admin，升级
            if ($user->user_type !== 'admin') {
                $user->update(['user_type' => 'admin']);
            }

            // 确保用户关联了至少一个Team（admin panel需要tenant）
            $this->ensureTeamAssociation($user);

            Auth::guard('admin')->login($user);
            session()->regenerate();

            // 手动存储 password_hash，与 AuthenticateSession 中间件保持一致
            $defaultGuard = config('auth.defaults.guard', 'web');
            session()->put("password_hash_{$defaultGuard}", $user->getAuthPassword());
            session()->save();

            Log::info('SSO login success (admin)', ['user_id' => $user->id, 'name' => $user->name]);

            return redirect('/admin');
        }

        // CMS来源：使用web guard
        Auth::guard('web')->login($user);
        session()->regenerate();

        // 手动存储 password_hash，与 AuthenticateSession 中间件保持一致
        $defaultGuard = config('auth.defaults.guard', 'web');
        session()->put("password_hash_{$defaultGuard}", $user->getAuthPassword());
        session()->save();

        Log::info('SSO login success (cms)', ['user_id' => $user->id, 'name' => $user->name]);

        if ($tenant) {
            return redirect("/cms/{$tenant}");
        }

        return redirect('/');
    }

    /**
     * 确保用户关联了至少一个Team
     * 如果没有关联，关联到第一个可用的Team
     */
    private function ensureTeamAssociation(User $user): void
    {
        if ($user->teams()->exists()) {
            // 已有Team关联，确保latest_team_id有值
            if (!$user->latest_team_id) {
                $user->update(['latest_team_id' => $user->teams()->first()->id]);
            }

            return;
        }

        // 关联到第一个可用的Team
        $team = Team::first();
        if ($team) {
            $user->teams()->attach($team->id);
            $user->update(['latest_team_id' => $team->id]);
        }
    }
}
