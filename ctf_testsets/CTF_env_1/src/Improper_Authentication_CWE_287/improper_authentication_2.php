<?php
// 更复杂的逻辑：使用 base64 cookie + PATH 推导判断身份
function isAdmin() {
    if (!isset($_COOKIE['auth'])) return false;

    $rawCookie = $_COOKIE['auth'];
    $decoded = base64_decode($rawCookie, true);
    if ($decoded === false) {
        // base64 解码失败时抛出提示
        echo "<h2>cookie错误: 传入的 auth 不是合法的 base64 格式</h2>";
        return false;
    }

    parse_str($decoded, $data);
    return isset($data['user']) && $data['user'] === 'admin';
}

// 模拟管理员路径限制，仅路径为 /admin 才会显示管理内容
if (strpos($_SERVER['REQUEST_URI'], '/admin') === false) {
    echo "<h2>欢迎访问用户中心</h2>";
    echo "<p>请通过?a=/admin 路径查看系统后台，此外，只有admin才能访问后台</p>";
    exit;
}

if (isAdmin()) {
    echo "<h2>欢迎管理员</h2>";
    echo "<p>FLAG: flag{b64_cookie_privilege_escalation}</p>";
} else {
    echo "<h2>权限不足:cookie中未检测到值auth变量以鉴定权限</h2>";
}
?>
