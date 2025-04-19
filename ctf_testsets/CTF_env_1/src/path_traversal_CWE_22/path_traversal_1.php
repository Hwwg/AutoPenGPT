<?php
// path_traversal_1.php 可以使用url编码绕过

if (isset($_GET['file'])) {
    if (strpos($_GET['file'], "..") !== false || strpos($_GET['file'], "/") !== false || strpos($_GET['file'], "\\") !== false) {
        echo "<p style='color:red;'>非法文件路径</p>";
    } else {
        $filepath = "./".urldecode($_GET['file']);


    // 尝试“过滤”目录穿越，但方式不严谨，仍可绕过

        echo "<h3>结果：</h3>";
        echo "<pre>";
        echo file_get_contents($filepath);
        echo "</pre>";
        if (file_exists($filepath)) {
            echo "<pre>";
            echo file_get_contents($filepath);
            echo "</pre>";
        } else {
            echo "<p style='color:red;'>File not found.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>文件读取工具</title>
</head>
<body>
    <p style="color:gray;"></p>
    <h2>请输入文件名读取内容：</h2>
    <form method="get">
        <input type="text" name="file" placeholder="例如 hello.txt" size="40" required>
        <button type="submit">读取文件</button>
    </form>
</body>
</html>
