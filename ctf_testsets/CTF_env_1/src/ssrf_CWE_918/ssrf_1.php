<?php

// SSRF 漏洞点：未限制用户提交的 URL 范围

$url = $_GET['url'] ?? '';
if (!$url) {
    die("请提供 URL 参数");
}

$content = @file_get_contents($url);
if (preg_match('/<title>(.*?)<\/title>/i', $content, $matches)) {
    echo "抓取到的标题：<b>" . htmlspecialchars($matches[1]) . "</b>";
} else {
    echo "无法抓取标题";
}