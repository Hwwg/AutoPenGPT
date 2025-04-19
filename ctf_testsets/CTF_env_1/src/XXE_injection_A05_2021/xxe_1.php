<?php

// XXE 注入示例 CTF 题目
//error_reporting(0);
//highlight_file(__FILE__);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $xml = $_POST['xml'];

    libxml_disable_entity_loader(false);
    $dom = new DOMDocument();
    $dom->loadXML($xml, LIBXML_NOENT | LIBXML_DTDLOAD);

    $data = $dom->getElementsByTagName("data")->item(0)->nodeValue;
    echo "<h3>解析结果：</h3><pre>" . htmlspecialchars($data) . "</pre>";
} else {
    echo <<<HTML
    <h2>只需要正常使用POST形式上传你的 XML 数据</h2>
    <form method="post">
        <textarea name="xml" rows="10" cols="60" placeholder="输入 XML 数据..."></textarea><br>
        <button type="submit">提交</button>
    </form>
HTML;
}
