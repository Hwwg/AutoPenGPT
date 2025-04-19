<?php

function raisePrivileges() {
    echo "[DEBUG] 权限已提升<br>";
    // 模拟提权：例如将用户设置为 root（此处仅打印）
}

function lowerPrivileges() {
    echo "[DEBUG] 权限已降级<br>";
    // 模拟降权
}

function isValidUsername($username) {
    return !preg_match('/[\/\\\\]/', $username); // 禁止目录穿越
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
        // ⚠️ 如果 mkdir 抛出异常，则不会执行降权
        lowerPrivileges();
    } catch (Exception $e) {
        echo "发生错误：" . $e->getMessage() . "<br>";
        // 漏洞点：未调用 lowerPrivileges，程序仍处于高权限态
        return false;
    }

    return true;
}

// 模拟环境初始化
if (!is_dir(__DIR__ . "/users")) {
    mkdir(__DIR__ . "/users");
}

// 接收用户名参数
if (isset($_GET['username'])) {
    $name = $_GET['username'];
    makeNewUserDir($name);

    // 模拟检测当前是否仍在“高权限”状态
    if ($name === "admin" && file_exists(__DIR__ . "/users/admin")) {
        echo "欢迎管理员，FLAG: flag{php_privilege_not_restored}";
    } elseif ($name === "admin") {
        echo "管理员目录未创建，访问受限。";
    }
} else {
    echo <<<HTML
<h2>用户目录创建系统</h2>
<p>请输入一个合法用户名（不能包含 / 或 \）。如果该用户不存在，将尝试创建一个用户目录。</p>
<p><strong>注意：</strong>当用户名为 admin 且目录创建失败时，程序可能会误认为您仍处于管理员权限状态。</p>
<form method="get">
    用户名：<input type="text" name="username">
    <button type="submit">创建用户目录</button>
</form>
HTML;
}
