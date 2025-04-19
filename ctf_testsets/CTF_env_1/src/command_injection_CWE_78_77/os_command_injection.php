<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OS Command Injection 示例</title>
</head>
<body>
    <h2>Ping 工具</h2>
    <form method="GET">
        <label for="host">请输入要 ping 的地址：</label>
        <input type="text" name="host" id="host" required>
        <button type="submit">Ping</button>
    </form>

    <?php
    if (isset($_GET['host'])) {
        $host = $_GET['host'];
        $cmd = "ping -c 2 " . $host;
        echo "<pre>";
        system($cmd);
        echo "</pre>";
    }
    ?>
</body>
</html>
?>
