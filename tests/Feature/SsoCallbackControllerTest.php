<?php

use App\Http\Controllers\SsoCallbackController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;

uses(RefreshDatabase::class);

/**
 * T056: SsoCallbackController Pest测试
 * 验证OAuth流程、用户创建(user_type='user')、映射记录
 */

beforeEach(function () {
    // Mock SSO database connection to use default connection for testing
    config(['database.connections.sso' => config('database.connections.testing')]);

    // Create sso_cross_platform_user table for testing
    if (!Schema::hasTable('sso_cross_platform_user')) {
        Schema::create('sso_cross_platform_user', function ($table) {
            $table->id();
            $table->string('source_platform', 32);
            $table->string('source_user_id', 64);
            $table->string('source_username', 128)->nullable();
            $table->string('target_platform', 32);
            $table->string('target_user_id', 64);
            $table->timestamps();
        });
    }
});

test('redirect sends user to SSO with account_type=horticultural', function () {
    Socialite::shouldReceive('driver')
        ->with('sso')
        ->andReturnSelf();
    Socialite::shouldReceive('with')
        ->with(['account_type' => 'horticultural'])
        ->andReturnSelf();
    Socialite::shouldReceive('redirect')
        ->andReturn(redirect('http://sso.test/oauth/authorize'));

    $response = $this->get('/sso/redirect');

    $response->assertRedirect();
});

test('callback creates new user with user_type=user on first login', function () {
    $ssoUser = new SocialiteUser();
    $ssoUser->map([
        'id' => 'sso-user-001',
        'name' => 'TestHortUser',
        'email' => 'hort@example.com',
    ]);

    Socialite::shouldReceive('driver')
        ->with('sso')
        ->andReturnSelf();
    Socialite::shouldReceive('user')
        ->andReturn($ssoUser);

    $response = $this->get('/sso/callback?code=test-auth-code');

    $response->assertRedirect('/admin');

    $this->assertDatabaseHas('users', [
        'name' => 'TestHortUser',
        'user_type' => 'user',
        'source_platform' => 'horticultural',
        'source_user_id' => 'sso-user-001',
    ]);

    $this->assertDatabaseHas('sso_cross_platform_user', [
        'source_platform' => 'horticultural',
        'source_user_id' => 'sso-user-001',
        'target_platform' => 'resourcedb',
    ]);
});

test('callback reuses existing user on second login', function () {
    // Pre-create user and mapping
    $user = User::create([
        'name' => 'ExistingHortUser',
        'email' => 'existing@example.com',
        'password' => bcrypt('random'),
        'user_type' => 'user',
        'source_platform' => 'horticultural',
        'source_user_id' => 'sso-user-002',
    ]);

    DB::connection('sso')->table('sso_cross_platform_user')->insert([
        'source_platform' => 'horticultural',
        'source_user_id' => 'sso-user-002',
        'source_username' => 'ExistingHortUser',
        'target_platform' => 'resourcedb',
        'target_user_id' => $user->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $ssoUser = new SocialiteUser();
    $ssoUser->map([
        'id' => 'sso-user-002',
        'name' => 'ExistingHortUser',
        'email' => 'existing@example.com',
    ]);

    Socialite::shouldReceive('driver')->with('sso')->andReturnSelf();
    Socialite::shouldReceive('user')->andReturn($ssoUser);

    $response = $this->get('/sso/callback?code=test-auth-code');

    $response->assertRedirect('/admin');

    // Should NOT create a duplicate user
    expect(User::where('source_user_id', 'sso-user-002')->count())->toBe(1);
});

test('callback handles SSO failure gracefully', function () {
    Socialite::shouldReceive('driver')->with('sso')->andReturnSelf();
    Socialite::shouldReceive('user')->andThrow(new \Exception('SSO unavailable'));

    $response = $this->get('/sso/callback?code=bad-code');

    $response->assertRedirect('/admin/login');
});

test('callback handles email conflict by generating unique email', function () {
    // Pre-create a user with the same email
    User::create([
        'name' => 'LocalUser',
        'email' => 'conflict@example.com',
        'password' => bcrypt('random'),
        'user_type' => 'admin',
    ]);

    $ssoUser = new SocialiteUser();
    $ssoUser->map([
        'id' => 'sso-user-003',
        'name' => 'ConflictUser',
        'email' => 'conflict@example.com',
    ]);

    Socialite::shouldReceive('driver')->with('sso')->andReturnSelf();
    Socialite::shouldReceive('user')->andReturn($ssoUser);

    $response = $this->get('/sso/callback?code=test-auth-code');

    $response->assertRedirect('/admin');

    // New user should have a different email to avoid conflict
    $newUser = User::where('source_user_id', 'sso-user-003')->first();
    expect($newUser)->not->toBeNull();
    expect($newUser->email)->not->toBe('conflict@example.com');
    expect($newUser->user_type)->toBe('user');
});
