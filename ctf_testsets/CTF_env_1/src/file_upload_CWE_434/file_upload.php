<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>上传头像</title>
</head>
<body>
<h2>请选择你的头像上传（仅限 PNG/JPG）</h2>
<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="avatar" accept=".png,.jpg,.jpeg" required>
    <button type="submit">上传</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $filename = basename($_FILES['avatar']['name']);
        $targetPath = $uploadDir . $filename;

        // 仅前端限制文件类型，后端未验证 MIME 或扩展名
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
            echo "<p>文件已上传至: <a href='upload/$targetPath'>upload/$targetPath</a></p>";
        } else {
            echo "<p style='color:red;'>上传失败。</p>";
        }
    }
}
?>
</body>
</html>
