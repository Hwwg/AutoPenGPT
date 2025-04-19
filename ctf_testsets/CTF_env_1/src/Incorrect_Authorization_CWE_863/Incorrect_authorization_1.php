<?php
// CTF题目：Cookie授权绕过（CWE-565）

error_reporting(0);
//highlight_file(__FILE__);
// 💡 CTF提示：请注意角色是通过 Cookie 进行判断的，可能存在未授权访问的绕过方式
include("flag.php");

function getRole($user) {
    if ($user === 'admin') {
        return 'Reader';
    }
    return null;
}

function DisplayMedicalHistory($id) {
    global $flag;
    if ($id === 'admin') {
        echo "Admin Medical Record: [REDACTED]<br>";
        echo "FLAG: $flag";
    } else {
        echo "Patient Medical Record of $id: [REDACTED]";
    }
}

$role = $_COOKIE['role'];

if (!$role) {
    $role = getRole('user');
    if ($role) {
        setcookie("role", $role, time() + 60 * 60 * 2);
    } else {
        echo "Please login to continue.";
        die();
    }
}

if ($role === 'Reader') {
    $patient = $_POST['patient_ID'] ?? 'guest';
    DisplayMedicalHistory($patient);
} else {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo <<<HTML
        <h2>查询患者信息系统</h2>
        <p style='color:gray;'>提示：系统通过名为 role 的 Cookie 来判断权限，尝试修改 Cookie 值以模拟不同身份。</p>
        <form method="post">
            <p>当前角色：" . htmlspecialchars($role) . "</p>
            <label for="patient_ID">患者ID：</label>
            <input type="text" name="patient_ID" id="patient_ID" placeholder="例如 admin"><br>
            <button type="submit">查看病历</button>
        </form>
HTML;
    } else {
        die("You are not Authorized to view this record\n");
    }
}
?>
