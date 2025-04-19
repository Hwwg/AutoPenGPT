<?php

// CTF题目：敏感信息泄露（Sensitive Data Exposure）

//error_reporting(0);
//highlight_file(__FILE__);

session_start();

if (!isset($_SESSION['auth'])) {
    $_SESSION['auth'] = false;
}

// 模拟数据库验证
function login($user, $pass) {
    return $user === 'admin' && $pass === 'admin123';
}

// 前端表单 + 登录处理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (login($_POST['username'], $_POST['password'])) {
        $_SESSION['auth'] = true;
        echo "<h3>登录成功！欢迎管理员。</h3>";
    } else {
        echo "<h3>用户名或密码错误。</h3>";
    }
}

// 页面逻辑
if ($_SESSION['auth'] === true) {
    echo "<p>欢迎回来，管理员！</p>";
    echo "<!-- DEBUG:  -->"; // 敏感信息泄露在页面中
} else {
    echo <<<HTML
    <h2>管理员登录()weak username and password</h2>
    <form method="post">
        用户名：<input type="text" name="username"><br>
        密码：<input type="password" name="password"><br>
        <button type="submit">登录</button>
        <!--ZmxhZ3tsZWFrX2luX3NvdXJjZV9pc19yZWFsfQ==-->
    </form>
HTML;
}
