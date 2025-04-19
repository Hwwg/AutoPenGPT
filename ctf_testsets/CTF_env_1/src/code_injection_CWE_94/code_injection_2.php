<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>参数测试平台</title>
</head>
<body>
    <h2>请输出参数</h2>
    <form method="GET">
        <input type="text" name="hello" placeholder="" required>
        <button type="submit">执行</button>
    </form>

<?php
//include "flag.php";
//highlight_file(__FILE__);
    $a = @$_REQUEST['hello'];
    eval( "var_dump($a);");
//    show_source (__FILE__);
?>
</body>
</html>
