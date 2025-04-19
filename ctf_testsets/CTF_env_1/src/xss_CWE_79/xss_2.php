<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>高级 XSS 示例</title>
</head>
<body>
    <h2>欢迎留言</h2>
    <form method="GET">
        <label for="msg">你的留言：</label>
        <input type="text" name="msg" id="msg" required>
        <button type="submit">提交</button>
    </form>

    <?php
    if (isset($_GET['msg'])) {
        $msg = $_GET['msg'];
        echo "<div>用户留言：<span title=\"$msg\">查看提示</span></div>";
    }
    ?>
</body>
</html>
