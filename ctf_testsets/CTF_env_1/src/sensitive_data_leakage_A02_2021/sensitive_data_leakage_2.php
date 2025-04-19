<?php
// 敏感信息泄露示例：后台路径被 robots.txt 暴露

session_start();
//error_reporting(0);
//highlight_file(__FILE__);

$request = $_SERVER['REQUEST_URI'];

// 漏洞：首次访问即赋管理员权限
if (!isset($_SESSION['admin'])) {
    $_SESSION['admin'] = true;
    echo "<h2>欢迎首次访问管理后台</h2>";
    echo "<p>系统尚未完成，请参阅系统日志或测试路径配置（详见 /leak/robots.txt）</p>";
} else {
    if (strpos($request, 'admin') !== false) {
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            echo "<h2>管理员面板</h2>";
            echo "<p>FLAG: flag{real_admin_route_accessed}</p>";
            echo "<p>欢迎回来，超级管理员！</p>";
        } else {
            echo "<h2>权限不足</h2>";
            echo "<p>你没有访问管理员面板的权限。</p>";
        }
        exit;
    }
    echo "<h2>欢迎回来，管理员！</h2>";
    echo "<p><!-- Dev Note: robots.txt contains restricted routes --></p>";
}
?>
