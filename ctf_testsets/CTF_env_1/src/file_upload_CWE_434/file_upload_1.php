<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>上传你的图片</title>
</head>
<body>
    <h2>请选择图片上传（仅限 .jpg 或 .png）</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="upload" accept=".jpg,.png" required>
        <button type="submit">上传</button>
    </form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['upload']) && $_FILES['upload']['error'] === UPLOAD_ERR_OK) {
        $filename = $_FILES['upload']['name'];
        $tmpPath = $_FILES['upload']['tmp_name'];
        $uploadDir = 'uploads/';
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        // 只允许.jpg或.png结尾，忽略了双扩展绕过
        if (strtolower($ext) === 'jpg' || strtolower($ext) === 'png') {
            $target = $uploadDir . basename($filename);
            if (move_uploaded_file($tmpPath, $target)) {
                echo "<p>上传成功：<a href=upload/'$target'>uplpad/$target</a></p>";
            } else {
                echo "<p style='color:red;'>上传失败</p>";
            }
        } else {
            echo "<p style='color:red;'>仅允许上传 JPG/PNG 文件！</p>";
        }
    }
}
?>
</body>
</html>
