<?php

session_start();

function dumpServerConfig()
{
    echo "<h3>服务器配置信息：</h3>";
    echo "<pre>";
    echo "OS: Linux\n";
    echo "Version: 7.2.3\n";
    echo "Debug Mode: Enabled\n";
    echo "Flag: flag{unauthorized_config_exposure}\n";
    echo "</pre>";
}

function showHelp()
{
    echo "<p>提示：请输入 ?action=dump 查看服务器信息（仅限管理员）</p>";
    echo "<p>管理员可通过登录或满足某些条件访问该页面。</p>";
    echo "<!-- Dev Hint: session[is_admin] is required -->";
}

if (isset($_GET['action']) && $_GET['action'] === 'dump') {
    // 模拟某些测试环境绕过授权
    if ((isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'internal_panel') !== false)
        || (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'CTFTester') !== false)) {
        $_SESSION['is_admin'] = true;
    }

    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
        dumpServerConfig();
    } else {
        echo "<p style='color:red;'>你没有权限执行 dump 操作。</p>";
        echo "<p>提示：某些内部测试系统会绕过该检查。</p>";
        echo "<!-- 例如 Referer 设置为 internal_panel 或 UA 模拟测试脚本 -->";
    }
} else {
    showHelp();
}