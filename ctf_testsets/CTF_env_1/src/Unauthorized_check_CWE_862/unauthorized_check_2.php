<?php
// 💡 CTF提示：尝试访问 ?action=dump 并使用伪造 Referer 头（例如 admin_panel.html）绕过限制
session_start();

// 模拟管理员接口
function dumpSecretAuditLog()
{
    echo "<h3>内部审计日志：</h3>";
    echo "<pre>";
    echo "Access granted to user root from 10.0.0.1\n";
    echo "Access denied to guest from 192.168.0.12\n";
    echo "Admin override activated\n";
    echo "FLAG: flag{referer_bypass_secret_log}\n";
    echo "</pre>";
}

// Referer 验证函数（可绕过）
function checkReferer()
{
    if (!isset($_SERVER['HTTP_REFERER'])) {
        return false;
    }

    // 只允许从 admin_panel.html 页面跳转过来
    $referer = $_SERVER['HTTP_REFERER'];
    return strpos($referer, 'admin_panel.html') !== false;
}

// 简易提示
function showHelp()
{
    echo "<p>仅允许从后台跳转访问该接口</p>";
    echo "<p>提示：Referer 头可能会影响访问控制，你可以尝试构造 Referer 绕过。</p>";
    echo "<p><a href='admin_panel.html' target='_blank'>点此打开后台面板</a></p>";
}

// 主逻辑
if (isset($_GET['action']) && $_GET['action'] === 'dump') {
    if (checkReferer()) {
        dumpSecretAuditLog();
    } else {
        showHelp();
    }
} else {
    echo "<p>请输入 ?action=dump</p>";
}