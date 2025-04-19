<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>调试计算器</title>
</head>
<body>
    <h2>请输入一个表达式进行调试计算</h2>
    <form method="GET">
        <input type="text" name="expr" placeholder="例如 1+2*3" required>
        <button type="submit">计算</button>
    </form>

<?php
if (isset($_GET['expr'])) {
    $input = $_GET['expr'];
    echo "<h3>计算结果：</h3><pre>";
    // 存在代码注入漏洞，攻击者可执行任意 PHP 代码
    eval("\$result = $input;");
    echo isset($result) ? $result : "无结果";
    echo "</pre>";
}
?>
</body>
</html>
