<?php
// SSRF题目（进阶版）带有简单的Host验证绕过点

$url = $_GET['url'] ?? '';
if (!$url) {
    die("请提供 ?url 参数");
}

// 防御机制：仅允许 example.com
$parsed = parse_url($url);
$host = $parsed['host'] ?? '';

if (stripos($host, 'example.com') === false) {
    die("仅允许访问 example.com");
}

// 执行请求（SSRF点）
$content = @file_get_contents($url);
if (preg_match('/<title>(.*?)<\/title>/i', $content, $matches)) {
    echo "抓取到的标题：<b>" . htmlspecialchars($matches[1]) . "</b>";
} else {
    echo "无法抓取标题";
}
?>