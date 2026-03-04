# ResourceDB SSO 登录问题诊断报告

## 问题描述

用户从 `http://resourcedb.me/admin/login` 点击"使用园艺库账号登录"后，跳转到 SSO 服务 (`http://10.0.0.40:8091`)，登录成功后返回 resourcedb 时出现错误：

```
State验证失败，可能存在CSRF攻击
```

## 根本原因

这是一个 **session cookie 跨域持久化问题**。OAuth 2.0 流程需要在以下步骤中保持 session：

1. **Redirect**: resourcedb.me → 生成 state，存入 session → 重定向到 10.0.0.40:8091
2. **Callback**: 10.0.0.40:8091 → 重定向回 resourcedb.me/sso/callback?state=xxx
3. **Verify**: resourcedb.me → 从 session 读取 state → 验证是否匹配

问题发生在步骤 3：当从 `10.0.0.40:8091` 重定向回 `resourcedb.me` 时，浏览器没有发送 session cookie，导致 Laravel 无法读取之前存储的 state。

## 为什么会发生这个问题？

### 域名差异

- **resourcedb.me**: 通过 `/etc/hosts` 映射到 `127.0.0.1`
- **10.0.0.40:8091**: SSO 服务的实际 IP 地址

这两个是**不同的域**，浏览器的 SameSite cookie 策略会阻止 cookie 在跨域重定向时发送。

### SameSite Cookie 策略

- `SameSite=Strict`: Cookie 只在同站请求时发送（最严格）
- `SameSite=Lax`: Cookie 在顶级导航（如链接点击、表单 GET）时发送，但不在跨站 POST 或 iframe 中发送（默认值）
- `SameSite=None`: Cookie 在所有跨站请求时发送（需要 Secure=true，即 HTTPS）

当前配置是 `SameSite=Lax`，这在从 `10.0.0.40:8091` 重定向回 `resourcedb.me` 时**可能**会阻止 cookie。

## 已实施的修复

### 1. 统一使用域名 (resourcedb.me)

**文件**: `.env`

```env
APP_URL=http://resourcedb.me
SESSION_DOMAIN=null
SESSION_SAME_SITE=lax
SSO_REDIRECT_URI=http://resourcedb.me/sso/callback
```

- `SESSION_DOMAIN=null`: 让 Laravel 自动使用当前域名
- 确保 SSO callback URI 使用相同的域名

### 2. SSO 服务添加 CORS 支持

**文件**: `sso/src/main/resources/application.yml`

```yaml
sso:
  cors:
    allowed-origins:
      - http://resourcedb.me
```

### 3. SSO 客户端配置

已验证 `sso_client` 表中包含正确的 redirect_uri：

```json
["http://10.0.0.40:8000/sso/callback","http://resourcedb.me/sso/callback","https://resourcedb.nhgrc.cn/sso/callback"]
```

### 4. 添加调试日志

**文件**: `app/Http/Controllers/SsoCallbackController.php`

在 `redirect()` 和 `callback()` 方法中添加了详细的日志记录：
- Session ID
- Session 数据
- Cookies
- State 参数

## 测试步骤

### 方法 1: 使用测试页面（推荐）

1. 访问 `http://resourcedb.me/test-session.php`
2. 查看 session 配置和 cookie 参数
3. 点击 "Test OAuth Redirect" 链接
4. 在新标签页完成 SSO 登录（如果需要）
5. 查看 `test-callback.php` 页面的验证结果

**预期结果**: State verification PASSED

### 方法 2: 使用实际登录流程

1. 清除浏览器 cookies（针对 resourcedb.me）
2. 访问 `http://resourcedb.me/admin/login`
3. 点击"使用园艺库账号登录"
4. 在 SSO 页面输入凭据
5. 观察是否成功登录

**预期结果**: 成功登录并重定向到 `/admin` 面板

### 方法 3: 检查日志

```bash
tail -f storage/logs/laravel-2026-03-01.log
```

查找：
- "SSO Redirect" 日志（记录 session_id 和 state）
- "SSO Callback" 日志（应该有相同的 session_id）
- 如果 session_id 不同 → session 丢失
- 如果有 "SSO callback failed" → 查看错误详情

## 可能仍然存在的问题

### 问题 1: 浏览器仍然阻止 SameSite=Lax cookies

**症状**: 测试页面显示 "State verification FAILED"

**原因**: 某些浏览器可能将 `resourcedb.me` 和 `10.0.0.40` 视为不同的站点

**解决方案 A**: 统一使用 IP 地址

修改 `/etc/hosts`，移除 `resourcedb.me` 映射，改用 `10.0.0.40:8000`：

```env
APP_URL=http://10.0.0.40:8000
SSO_REDIRECT_URI=http://10.0.0.40:8000/sso/callback
SESSION_DOMAIN=10.0.0.40
```

**解决方案 B**: 使用 HTTPS + SameSite=None

设置 SSL 证书，然后：

```env
APP_URL=https://resourcedb.me
SESSION_SAME_SITE=none
SESSION_SECURE_COOKIE=true
SSO_BASE_URL=https://sso.nhgrc.cn
```

**解决方案 C**: 禁用 state 验证（不推荐，仅用于调试）

在 `SsoProvider.php` 中覆盖 `hasInvalidState()` 方法：

```php
protected function hasInvalidState()
{
    return false; // WARNING: 禁用 CSRF 保护
}
```

### 问题 2: Session 驱动配置错误

**症状**: Session 数据根本没有存储

**检查**:

```bash
php artisan tinker --execute="
echo 'Sessions in DB: ' . DB::table('sessions')->count() . PHP_EOL;
"
```

**解决方案**: 确保 `sessions` 表存在且可写

```bash
php artisan migrate
php artisan session:table
```

## 下一步行动

1. **立即测试**: 访问 `http://resourcedb.me/test-session.php` 并执行测试
2. **查看结果**: 如果 state 验证通过，问题已解决
3. **如果失败**: 根据上述"可能仍然存在的问题"选择解决方案
4. **生产部署**: 使用 HTTPS + SameSite=None 配置

## 技术细节

### Laravel Socialite State 验证流程

1. `redirect()`: 调用 `$this->getState()` 生成随机 state
2. State 存储在 session: `session()->put('state', $state)`
3. 重定向到 SSO: `?state=$state`
4. `callback()`: 调用 `$this->hasInvalidState()` 验证
5. 验证逻辑: `$state = session()->pull('state')` 然后比较

如果 `session()->pull('state')` 返回 null，说明 session 丢失。

### Session Cookie 属性

当前配置生成的 cookie 属性：

```
Set-Cookie: laravel_session=xxx;
  Path=/;
  HttpOnly;
  SameSite=Lax
```

对于跨域 OAuth，理想配置应该是：

```
Set-Cookie: laravel_session=xxx;
  Path=/;
  Domain=resourcedb.me;
  HttpOnly;
  SameSite=None;
  Secure
```

但这需要 HTTPS。

## 参考资料

- [MDN: SameSite cookies](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie/SameSite)
- [Laravel Session Configuration](https://laravel.com/docs/11.x/session)
- [OAuth 2.0 Security Best Practices](https://datatracker.ietf.org/doc/html/draft-ietf-oauth-security-topics)
