<?php
/**
 * Session 测试脚本
 * 用于验证 session 配置是否正确
 */

// 启动 session
session_start();

// 生成测试数据
$testKey = 'test_state_' . time();
$testValue = bin2hex(random_bytes(16));

// 存储到 session
$_SESSION[$testKey] = $testValue;

echo "Session Test\n";
echo "============\n\n";
echo "Session ID: " . session_id() . "\n";
echo "Session Name: " . session_name() . "\n";
echo "Session Save Path: " . session_save_path() . "\n\n";

echo "Test Data:\n";
echo "Key: $testKey\n";
echo "Value: $testValue\n\n";

echo "Session Data:\n";
print_r($_SESSION);

echo "\nCookie Parameters:\n";
$params = session_get_cookie_params();
print_r($params);

echo "\nPHP Session Settings:\n";
echo "session.cookie_domain: " . ini_get('session.cookie_domain') . "\n";
echo "session.cookie_samesite: " . ini_get('session.cookie_samesite') . "\n";
echo "session.cookie_secure: " . ini_get('session.cookie_secure') . "\n";
echo "session.cookie_httponly: " . ini_get('session.cookie_httponly') . "\n";
