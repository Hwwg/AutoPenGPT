<?php

if ($_SERVER['REMOTE_ADDR'] !== '127.0.0.1') {
    die("禁止访问此接口！");
}
echo "<title>FLAG: flag{ssrf_127_hunter}</title>";