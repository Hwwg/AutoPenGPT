<?php
if ($_COOKIE['loggedin'] !== 'true') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($_POST['username'] !== 'admin' || $_POST['password'] !== 'admin123') {
            die("请先登录！");
        } else {
            setcookie('loggedin', 'true');
            setcookie('user', $_POST['username']);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }

    // 显示登录表单
    echo <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>管理员登录</title>
</head>
<body>
    <h2>登录后台</h2>
    <form method="post">
        用户名：<input type="text" name="username"><br>
        密码：<input type="password" name="password"><br>
        <button type="submit">登录</button>
    </form>
    <p>请提供有效的用户名和密码以访问后台管理系统。</p>
    <p>登陆成功将cookie中的user设置为admin。</p>
</body>
</html>
HTML;
    exit;
}

if ($_COOKIE['user'] === 'admin') {
    echo "欢迎管理员！FLAG: flag{cookie_auth_is_not_auth}";
} else {
    echo "欢迎普通用户";
}
?>
