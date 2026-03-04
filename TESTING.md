# ResourceDB SSO Login Testing Guide

## Quick Diagnosis

### Step 1: Test Session Persistence

访问测试页面来验证 session 是否能在跨域重定向后保持：

```
http://resourcedb.me/test-session.php
```

这个页面会显示：
- Session ID 和配置
- Cookie 参数
- PHP session 设置
- 当前 session 数据

### Step 2: Test OAuth Flow

在测试页面上点击 "Test OAuth Redirect" 链接，这会：
1. 在新标签页打开 SSO 授权页面
2. 携带 state 参数（从 session 中读取）
3. 重定向回 `test-callback.php`
4. 验证 state 是否匹配

**如果 state 验证失败**，说明 session cookie 在跨域重定向时丢失了。

### Step 3: Check Browser DevTools

打开浏览器开发者工具：
1. Application/Storage → Cookies
2. 查看 `resourcedb.me` 的 cookies
3. 检查 `laravel_session` 或 `PHPSESSID` cookie 的属性：
   - Domain
   - Path
   - SameSite
   - Secure
   - HttpOnly

## Changes Made

### 1. Session Configuration (.env)
- `APP_URL`: Changed from `http://resourcedb.me/` to `http://10.0.0.40:8000`
- `SESSION_DOMAIN`: Changed from `null` to `10.0.0.40`
- `SESSION_SAME_SITE`: Added with value `none` (allows cross-site cookies)
- `SESSION_SECURE_COOKIE`: Added with value `false` (required for SameSite=none over HTTP)

### 2. SSO Redirect URI (config/services.php)
- Changed from hardcoded `http://10.0.0.40:8000/sso/callback`
- Now uses `env('APP_URL') . '/sso/callback'` for consistency

### 3. Debug Logging (SsoCallbackController.php)
Added comprehensive logging in both `redirect()` and `callback()` methods:
- Session ID tracking
- Session data inspection
- Cookie inspection
- Request state parameter logging
- Detailed error messages with stack traces

## Testing Steps

### 1. Clear Browser Data
Before testing, clear all cookies and cache for `10.0.0.40`:
- Chrome: DevTools → Application → Clear storage
- Firefox: DevTools → Storage → Clear all

### 2. Test SSO Flow with DevTools

1. Open browser DevTools → Network tab
2. Navigate to `http://10.0.0.40:8000/admin/login`
3. Click "使用园艺库账号登录" button

**Expected behavior:**
- Should see `laravel_session` cookie set in response
- Cookie should have `SameSite=None` attribute
- Should redirect to `http://10.0.0.40:8091/oauth/authorize`

4. Complete login on SSO page (enter credentials)
5. After SSO authentication, should redirect back to callback

**Expected behavior:**
- `laravel_session` cookie should be sent in callback request
- Should NOT see "State验证失败" error
- Should be redirected to `/admin` dashboard
- User should be logged in

### 3. Check Logs

Monitor the Laravel log file:

```bash
tail -f /home/my-project/nhgrc/nhgrc-website/resourcedb/storage/logs/laravel.log
```

**Look for:**
- "SSO Redirect" log entry with session_id
- "SSO Callback" log entry with **same** session_id
- If session_id differs → session not persisting (cookie issue)
- If "SSO callback failed" → check error message and trace

### 4. Verify User Creation

After successful login:

```bash
cd /home/my-project/nhgrc/nhgrc-website/resourcedb
php artisan tinker --execute="
\$user = DB::table('users')->where('source_platform', 'horticultural')->latest()->first();
print_r(\$user);
"
```

**Expected output:**
- User with `source_platform = 'horticultural'`
- `source_user_id` matching SSO user ID
- `user_type = 'user'`

### 5. Check Cross-Platform Mapping

```bash
php artisan tinker --execute="
\$mapping = DB::connection('sso')->table('sso_cross_platform_user')
    ->where('target_platform', 'resourcedb')
    ->latest()
    ->first();
print_r(\$mapping);
"
```

**Expected output:**
- Mapping record linking horticultural user to resourcedb user
- `source_platform = 'horticultural'`
- `target_platform = 'resourcedb'`
- Valid `source_user_id` and `target_user_id`

## Troubleshooting

### Issue: "State验证失败" Error Still Occurs

**Possible causes:**
1. Browser blocking `SameSite=None` cookies over HTTP
2. Session cookie not being sent in callback request
3. Session data not persisting in database

**Solutions:**

#### Option A: Check Browser Console
Look for cookie warnings in browser console. Some browsers may block `SameSite=None` cookies over HTTP.

#### Option B: Verify Cookie in DevTools
In Network tab, check callback request:
- Headers → Request Headers → Cookie
- Should contain `laravel_session=...`
- If missing, cookie is being blocked

#### Option C: Check Session Database
```bash
php artisan tinker --execute="
DB::table('sessions')->orderBy('last_activity', 'desc')->limit(5)->get()->each(function(\$s) {
    echo 'ID: ' . \$s->id . ', Last Activity: ' . date('Y-m-d H:i:s', \$s->last_activity) . PHP_EOL;
});
"
```

### Issue: Session ID Changes Between Redirect and Callback

This indicates cookies are not being sent. Check:
1. Browser cookie settings (allow third-party cookies)
2. Browser version (older browsers may not support `SameSite=None`)
3. Network configuration (proxy/firewall blocking cookies)

### Issue: User Not Redirected to Dashboard

Check:
1. User was created successfully (see "Verify User Creation" above)
2. Filament authentication is working
3. User has correct `user_type` and permissions

## Alternative Solution: Use HTTPS

If `SameSite=None` over HTTP doesn't work, set up SSL certificates:

1. Install SSL certificates for both resourcedb and SSO service
2. Update `.env`:
   ```env
   APP_URL=https://resourcedb.me
   SESSION_SAME_SITE=none
   SESSION_SECURE_COOKIE=true
   SSO_BASE_URL=https://sso.example.com
   SSO_REDIRECT_URI=https://resourcedb.me/sso/callback
   ```
3. Clear caches and test again

## Security Notes

- `SameSite=None` with `Secure=false` is only for development over HTTP
- **Production deployment MUST use HTTPS** with `SESSION_SECURE_COOKIE=true`
- Never disable state verification in production (CSRF protection)
- Monitor SSO audit logs for suspicious activity

## Success Criteria

✅ User clicks SSO login button → redirects to SSO service
✅ User enters credentials on SSO page
✅ SSO redirects back with authorization code
✅ State verification succeeds (no error)
✅ User info retrieved from SSO
✅ Local user created/found
✅ Cross-platform mapping created
✅ User logged in and redirected to `/admin` dashboard
✅ Session persists across requests
