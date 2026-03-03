<?php
/**
 * OAuth Callback 测试页面
 * 模拟 SSO callback，检查 session 是否保持
 */

session_start();

$receivedState = $_GET['state'] ?? null;
$storedState = $_SESSION['oauth_state'] ?? null;

?>
<!DOCTYPE html>
<html>
<head>
    <title>OAuth Callback Test - ResourceDB</title>
    <style>
        body { font-family: monospace; padding: 20px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        table { border-collapse: collapse; margin: 10px 0; }
        td, th { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        pre { background: #f5f5f5; padding: 10px; }
    </style>
</head>
<body>
    <h1>OAuth Callback Test</h1>

    <h2>State Verification</h2>
    <?php if ($receivedState && $storedState && $receivedState === $storedState): ?>
        <p class="success">✓ State verification PASSED!</p>
        <p>Session persisted correctly across the OAuth redirect.</p>
    <?php else: ?>
        <p class="error">✗ State verification FAILED!</p>
        <p>Session was lost during the OAuth redirect.</p>
    <?php endif; ?>

    <table>
        <tr><th>Property</th><th>Value</th></tr>
        <tr><td>Received State (from URL)</td><td><?= htmlspecialchars($receivedState ?? 'NULL') ?></td></tr>
        <tr><td>Stored State (from Session)</td><td><?= htmlspecialchars($storedState ?? 'NULL') ?></td></tr>
        <tr><td>Match</td><td><?= ($receivedState === $storedState) ? '<span class="success">YES</span>' : '<span class="error">NO</span>' ?></td></tr>
    </table>

    <h2>Session Information</h2>
    <table>
        <tr><th>Property</th><th>Value</th></tr>
        <tr><td>Session ID</td><td><?= session_id() ?></td></tr>
        <tr><td>Session Name</td><td><?= session_name() ?></td></tr>
    </table>

    <h2>Session Data</h2>
    <pre><?php print_r($_SESSION); ?></pre>

    <h2>Request Cookies</h2>
    <pre><?php print_r($_COOKIE); ?></pre>

    <h2>GET Parameters</h2>
    <pre><?php print_r($_GET); ?></pre>

    <p><a href="test-session.php">← Back to Session Test</a></p>
</body>
</html>
