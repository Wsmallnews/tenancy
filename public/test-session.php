<?php
/**
 * Session 测试页面
 * 访问: http://resourcedb.me/test-session.php
 */

// 启动 session
session_start();

// 生成测试数据
$testKey = 'oauth_state';
if (! isset($_SESSION[$testKey])) {
    $_SESSION[$testKey] = bin2hex(random_bytes(20));
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Session Test - ResourceDB</title>
    <style>
        body { font-family: monospace; padding: 20px; }
        h2 { color: #333; }
        table { border-collapse: collapse; margin: 10px 0; }
        td, th { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .success { color: green; }
        .error { color: red; }
        pre { background: #f5f5f5; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>ResourceDB Session Test</h1>

    <h2>Session Information</h2>
    <table>
        <tr><th>Property</th><th>Value</th></tr>
        <tr><td>Session ID</td><td><?= session_id() ?></td></tr>
        <tr><td>Session Name</td><td><?= session_name() ?></td></tr>
        <tr><td>Session Save Path</td><td><?= session_save_path() ?></td></tr>
        <tr><td>Session Status</td><td class="success">Active</td></tr>
    </table>

    <h2>Cookie Parameters</h2>
    <table>
        <tr><th>Parameter</th><th>Value</th></tr>
        <?php
        $params = session_get_cookie_params();
foreach ($params as $key => $value) {
    echo "<tr><td>$key</td><td>".var_export($value, true).'</td></tr>';
}
?>
    </table>

    <h2>PHP Session Settings</h2>
    <table>
        <tr><th>Setting</th><th>Value</th></tr>
        <tr><td>session.cookie_domain</td><td><?= ini_get('session.cookie_domain') ?: '(empty)' ?></td></tr>
        <tr><td>session.cookie_samesite</td><td><?= ini_get('session.cookie_samesite') ?: '(empty)' ?></td></tr>
        <tr><td>session.cookie_secure</td><td><?= ini_get('session.cookie_secure') ?></td></tr>
        <tr><td>session.cookie_httponly</td><td><?= ini_get('session.cookie_httponly') ?></td></tr>
        <tr><td>session.cookie_lifetime</td><td><?= ini_get('session.cookie_lifetime') ?></td></tr>
    </table>

    <h2>Session Data</h2>
    <pre><?php print_r($_SESSION); ?></pre>

    <h2>Request Cookies</h2>
    <pre><?php print_r($_COOKIE); ?></pre>

    <h2>Server Variables</h2>
    <table>
        <tr><th>Variable</th><th>Value</th></tr>
        <tr><td>HTTP_HOST</td><td><?= $_SERVER['HTTP_HOST'] ?? 'N/A' ?></td></tr>
        <tr><td>SERVER_NAME</td><td><?= $_SERVER['SERVER_NAME'] ?? 'N/A' ?></td></tr>
        <tr><td>REQUEST_URI</td><td><?= $_SERVER['REQUEST_URI'] ?? 'N/A' ?></td></tr>
        <tr><td>HTTP_REFERER</td><td><?= $_SERVER['HTTP_REFERER'] ?? 'N/A' ?></td></tr>
        <tr><td>HTTP_USER_AGENT</td><td><?= $_SERVER['HTTP_USER_AGENT'] ?? 'N/A' ?></td></tr>
    </table>

    <h2>Test Actions</h2>
    <p>
        <a href="?action=regenerate">Regenerate Session ID</a> |
        <a href="?action=destroy">Destroy Session</a> |
        <a href="?action=refresh">Refresh Page</a>
    </p>

    <?php
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'regenerate':
                session_regenerate_id(true);
                echo '<p class="success">Session ID regenerated!</p>';
                break;
            case 'destroy':
                session_destroy();
                echo '<p class="success">Session destroyed!</p>';
                break;
        }
    }
?>

    <h2>OAuth Flow Simulation</h2>
    <p>Current OAuth State: <strong><?= $_SESSION[$testKey] ?></strong></p>
    <p>
        <a href="http://10.0.0.40:8091/oauth/authorize?client_id=resourcedb&redirect_uri=http://resourcedb.me/test-callback.php&response_type=code&state=<?= $_SESSION[$testKey] ?>&account_type=horticultural" target="_blank">
            Test OAuth Redirect (opens SSO in new tab)
        </a>
    </p>
    <p style="color: #666; font-size: 12px;">
        Note: This simulates the OAuth flow. After clicking, check if the session persists when you return.
    </p>
</body>
</html>
