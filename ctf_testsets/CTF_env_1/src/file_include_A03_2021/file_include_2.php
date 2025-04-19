<?php
// CTF题目：文件包含漏洞（LFI/RFI）

//error_reporting(0);
highlight_file(__FILE__);

if(isset($_GET['file'])){
    $file = $_GET['file'];
    $file = str_replace("php", "???", $file);
    include($file);
} else {
    echo <<<HTML
    <h2>🗂️ 文件加载工具</h2>
    <p>你可以使用 ?file= 参数尝试加载一个文件</p>
    <form method="get">
        <label for="file">文件路径：</label>
        <input type="text" name="file" id="file" placeholder="例如 test.php 或 ../../etc/passwd"><br>
        <button type="submit">加载文件</button>
    </form>
HTML;
}
