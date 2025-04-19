<?php
session_start();

$users = [
    'admin' => '123456',
    'guest' => 'guest123'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!isset($users[$username])) {
        echo "Login Failed - unknown username";
    } else if ($users[$username] !== $password) {
        echo "Login Failed - incorrect password";
    } else {
        $_SESSION['auth'] = $username;
        echo "Login Successful";

        if ($username === 'admin') {
            echo "<br>FLAG: flag{username_enum_success}";
        }
    }
} else {
    echo <<<HTML
    <h2>登录系统</h2>
    <form method="post">
        用户名: <input type="text" name="username"><br>
        密码: <input type="password" name="password"><br>
        <button type="submit">登录</button>
    </form>
HTML;
}
?>