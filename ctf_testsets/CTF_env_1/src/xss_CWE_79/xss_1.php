<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>XSS 示例</title>
</head>
<body>
    <h2>XSS 漏洞演示页面</h2>
    <form method="GET">
        <label for="name">请输入你的名字：</label>
        <input type="text" name="name" id="name" required>
        <button type="submit">提交</button>
    </form>

    <?php
    if (isset($_GET['name'])) {
        $name = $_GET['name'];
        echo "<p>你好，" . $name . "！</p>";
    }
    ?>
</body>
</html>
