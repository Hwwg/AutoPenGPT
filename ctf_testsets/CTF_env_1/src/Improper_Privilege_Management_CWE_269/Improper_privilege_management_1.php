<?php
$isPrivileged = false;

function raisePrivileges() {
    global $isPrivileged;
    $isPrivileged = true;
    echo "[DEBUG] 权限已提升<br>";
}

function lowerPrivileges() {
    global $isPrivileged;
    $isPrivileged = false;
    echo "[DEBUG] 权限已降级<br>";
}

function isValidUsername($username) {
    return !preg_match('/[\\/\\\\]/', $username);
}

function makeNewUserDir($username) {
    if (!isValidUsername($username)) {
        echo "非法用户名！<br>";
        return false;
    }

    try {
        raisePrivileges();
        $target = __DIR__ . "/users/" . $username;
        if (!mkdir($target)) {
            throw new Exception("mkdir failed");
        }
        lowerPrivileges();
    } catch (Exception $e) {
        echo "发生错误：" . $e->getMessage() . "<br>";
        echo "[提示] 可能存在未降权风险。<br>";
        // ⚠️ 未调用 lowerPrivileges()，残留高权限
        return false;
    }

    return true;
}

// 初始化用户目录
if (!is_dir(__DIR__ . "/users")) {
    mkdir(__DIR__ . "/users");
}

if (isset($_GET['username'])) {
    $name = $_GET['username'];
    $ok = makeNewUserDir($name);

    if ($name === "admin" && file_exists(__DIR__ . "/users/admin")) {
        echo "欢迎管理员，FLAG: flag{php_not_restored_privilege_1}<br>";
    }
} else {
    echo <<<HTML
<h2>用户目录创建系统</h2>
<form method="get">
    用户名：<input type="text" name="username" />
    <button type="submit">创建用户目录</button>
</form>
HTML;
}