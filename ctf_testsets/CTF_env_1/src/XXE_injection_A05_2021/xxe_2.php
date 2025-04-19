<?php

// XXE 注入示例（进阶）CTF 题目
//error_reporting(0);
//highlight_file(__FILE__);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $xml = $_POST['xml'];

    libxml_disable_entity_loader(false);
    $dom = new DOMDocument();
    $dom->loadXML($xml, LIBXML_NOENT | LIBXML_DTDLOAD);

    $title = $dom->getElementsByTagName("title")->item(0)->nodeValue;
    $author = $dom->getElementsByTagName("author")->item(0)->nodeValue;
    $content = $dom->getElementsByTagName("content")->item(0)->nodeValue;

    echo "<h3>解析结果：</h3><pre>";
    echo "标题: " . htmlspecialchars($title) . "\n";
    echo "作者: " . htmlspecialchars($author) . "\n";
    echo "正文: " . htmlspecialchars($content) . "\n";
    echo "</pre>";
} else {
    echo <<<HTML
    <h2>XML 博客解析器</h2>
    <p>将你的 XML 博文提交给我们解析并展示</p>
    <p><strong>提示：</strong>请确保包含 &lt;title&gt;、&lt;author&gt; 和 &lt;content&gt; 标签</p>
    <form method="post">
        <textarea name="xml" rows="12" cols="80" placeholder="示例：&lt;?xml version='1.0'?&gt;&#10;&lt;post&gt;&#10;&nbsp;&nbsp;&lt;title&gt;Example&lt;/title&gt;&#10;&nbsp;&nbsp;&lt;author&gt;Anonymous&lt;/author&gt;&#10;&nbsp;&nbsp;&lt;content&gt;Hello&lt;/content&gt;&#10;&lt;/post&gt;"></textarea><br>
        <button type="submit">提交 XML</button>
    </form>
HTML;
}
