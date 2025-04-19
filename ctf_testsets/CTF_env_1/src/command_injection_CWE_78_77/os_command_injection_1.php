<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>高级 OS Command Injection 示例</title>
</head>
<body>
    <h2>DNS 查询工具</h2>
    <form method="GET">
        <label for="domain">请输入要查询的域名：</label>
        <input type="text" name="domain" id="domain" required>
        <button type="submit">查询</button>
    </form>

    <?php
    if (isset($_GET['domain'])) {
        $domain = $_GET['domain'];

        // 简单的字符黑名单过滤，易被绕过
        if (preg_match('/[&|`]/', $domain)) {
            echo "<p style='color:red;'>非法字符被禁止！</p>";
        } else {
            $cmd = "nslookup " . $domain;
            echo "<pre>";
            system($cmd);
            echo "</pre>";
        }
    }
    ?>
</body>
</html>
?>
