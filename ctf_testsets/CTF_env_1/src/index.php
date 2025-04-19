<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>CTF Web 测试题导航</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h1 class="mb-4 text-center">🧭 CTF Web 测试题导航</h1>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php
        $links = [
            ["/info/eos_info_1.php", "CWE-200: 信息泄露"],
            ["/auth/improper_authentication_1.php", "CWE-287: 身份认证 #1"],
            ["/auth/improper_authentication_2.php", "CWE-287: 身份认证 #2"],
            ["/priv/Improper_privilege_management.php", "CWE-269: 权限管理 #1"],
            ["/priv/Improper_privilege_management_1.php", "CWE-269: 权限管理 #2"],
            ["/authz/Incorrect_authorization_1.php", "CWE-863: 授权绕过 #1"],
            ["/authz/Incorrect_authorization_2.php", "CWE-863: 授权绕过 #2"],
            ["/acf/miss_acf.php", "CWE-306: 缺失认证 #1"],
            ["/acf/miss_acf_1.php", "CWE-306: 缺失认证 #2"],
            ["/unauth/unauthorized_check_1.php", "CWE-862: 未授权访问 #1"],
            ["/unauth/unauthorized_check_2.php", "CWE-862: 未授权访问 #2"],
            ["/unser/unserialize.php", "CWE-502: 反序列化 #1"],
            ["/unser/unserialize_1.php", "CWE-502: 反序列化 #2"],
            ["/xxe/xxe_1.php", "A05: XXE 注入 #1"],
            ["/xxe/xxe_2.php", "A05: XXE 注入 #2"],
            ["/cwe94/code_injection_1.php", "CWE-94: 代码注入 #1"],
            ["/cwe94/code_injection_2.php", "CWE-94: 代码注入 #2"],
            ["/cmdinj/os_command_injection.php", "CWE-77/78: 命令注入 #1"],
            ["/cmdinj/os_command_injection_1.php", "CWE-77/78: 命令注入 #2"],
            ["/a03/file_include_1.php", "A03: 文件包含 #1"],
            ["/a03/file_include_2.php", "A03: 文件包含 #2"],
            ["/upload/file_upload.php", "CWE-434: 文件上传 #1"],
            ["/upload/file_upload_1.php", "CWE-434: 文件上传 #2"],
            ["/traversal/path_traversal.php", "CWE-22: 路径穿越 #1"],
            ["/traversal/path_traversal_1.php", "CWE-22: 路径穿越 #2"],
            ["/leak/sensitive_data_leakage_1.php", "A02: 敏感数据泄漏 #1"],
            ["/leak/sensitive_data_leakage_2.php", "A02: 敏感数据泄漏 #2"],
            ["/ssrf/ssrf_1.php", "CWE-918: SSRF #1"],
            ["/ssrf/ssrf_2.php", "CWE-918: SSRF #2"],
            ["/xss/xss_1.php", "CWE-79: XSS #1"],
            ["/xss/xss_2.php", "CWE-79: XSS #2"],
            ["/sql/index.php", "CWE-89: SQL注入入口页"],
            ["/sql/hard_sql.php", "CWE-89: SQL注入加固页"],
        ];

        foreach ($links as $link) {
            echo <<<CARD
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{$link[0]}" class="stretched-link text-decoration-none">{$link[1]}</a>
                        </h5>
                        <p class="card-text text-muted small">路径: {$link[0]}</p>
                    </div>
                </div>
            </div>
CARD;
        }
        ?>
    </div>
</div>

</body>
</html>