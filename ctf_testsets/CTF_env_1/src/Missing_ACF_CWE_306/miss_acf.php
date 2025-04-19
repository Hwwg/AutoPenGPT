<?php
// 💡 CTF提示：本题模拟缺失访问控制的账户接口，请尝试绕过权限直接触发管理员操作。
session_start();
error_reporting(0);
//highlight_file(__FILE__);
include('flag.php');

// 模拟登录接口
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if ($user === 'admin' && $pass === 'supersecure') {
        $_SESSION['auth'] = true;
        echo "<p>登录成功！</p>";
    } else {
        echo "<p>登录失败</p>";
    }
}

// 模拟创建账户接口（存在漏洞）
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $name = $_POST['name'];
    $ssn = $_POST['ssn'];

    // ❗ 未验证当前用户权限，任何人都可以创建管理员账户，存在认证绕过风险
    if ($name === 'admin' && $ssn === '000-00-0000') {
        echo "<p>账户创建成功！</p>";
        echo "<p>FLAG: $flag</p>";
    } else {
        echo "<p>已为 $name 创建账户。</p>";
    }
}
?>

<!-- 前端界面 -->
<h2>欢迎来到账户系统</h2>

<h3>登录面板</h3>
<form method="post">
    <input type="hidden" name="action" value="login">
    用户名: <input type="text" name="username"><br>
    密码: <input type="password" name="password"><br>
    <button type="submit">登录</button>
</form>

<h3>创建账户</h3>
<p style="color:gray;">提示：尝试使用特定的用户名和社保号组合，如 admin / 000-00-0000</p>
<form method="post">
    <input type="hidden" name="action" value="create">
    姓名: <input type="text" name="name"><br>
    社保号: <input type="text" name="ssn"><br>
    <button type="submit">创建账户</button>
</form>