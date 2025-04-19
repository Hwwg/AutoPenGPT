<?php
session_start();
error_reporting(0);
//highlight_file(__FILE__);
include('flag.php');

// 💡 CTF提示：本题模拟缺失访问控制的账户接口，未登录也可尝试创建高权限账户。

$users = [
    'admin' => ['pass' => 'adminpass', 'role' => 'admin'],
    'user1' => ['pass' => 'userpass', 'role' => 'user']
];

// 登录逻辑（模拟用户角色）
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'login') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if (isset($users[$username]) && $users[$username]['pass'] === $password) {
        $_SESSION['auth'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $users[$username]['role'];
        echo "<p>欢迎，$username</p>";
    } else {
        echo "<p>登录失败。</p>";
    }
}

// 创建账户接口（仍未验证身份！）
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'create_account') {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $token = $_POST['token']; // 伪验证字段

    // ❗ 漏洞：未验证用户是否已登录，仅靠 token 控制权限
    if ($role === 'admin' && $token === 'internal-token') {
        echo "<p>管理员账户创建成功。</p>";
        echo "<p style='color:blue;'>你已获得管理员权限。</p>";
        echo "<p>FLAG: $flag</p>";
    } else {
        echo "<p>账户 $name 创建成功，角色为 $role</p>";
    }
}
?>

<h2>账户管理系统</h2>

<h3>登录</h3>
<form method="post">
    <input type="hidden" name="action" value="login">
    用户名：<input type="text" name="username"><br>
    密码：<input type="password" name="password"><br>
    <button type="submit">登录</button>
</form>

<h3>创建账户</h3>
<form method="post">
    <input type="hidden" name="action" value="create_account">
    姓名：<input type="text" name="name"><br>
    角色：<input type="text" name="role" value="user"><br>
    Token：<input type="text" name="token"><br>
    <button type="submit">创建</button>
</form>
<p style="color:gray;">提示：尝试使用特殊 token 值 'internal-token' 创建管理员账户</p>