<?php
// CTF题目：Cookie授权绕过（CWE-565）
//highlight_file(__FILE__);
// 💡 CTF提示：系统通过名为 role 的 Cookie 进行权限判断，尝试伪造 Cookie 实现越权访问。
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
        <p style='color:gray;'>当前角色：" . htmlspecialchars($role) . "</p>
        <form method="post">
            <label for="patient_ID">患者ID：</label>
            <input type="text" name="patient_ID" id="patient_ID" placeholder="例如 admin"><br>
            <button type="submit">查看病历</button>
        </form>
HTML;
    } else {
        die("You are not docker authorized to view this record\n");
    }
}
?>

<?php
// CTF题目：伪造身份标识绕过（基于隐藏字段）
highlight_file(__FILE__);
// 💡 CTF提示：系统通过隐藏表单字段 role 判断权限，可尝试修改 POST 数据伪造医生身份。
include("flag.php");

// 模拟角色检查：仅允许 role=doctor 访问敏感信息
function checkAccess() {
    if (isset($_POST['role']) && $_POST['role'] === 'doctor') {
        return true;
    }
    return false;
}

// 模拟信息输出
function showRecord($id) {
    global $flag;
    if ($id === 'admin') {
        echo "Admin Medical Record: [TOP SECRET]<br>";
        echo "FLAG: $flag";
    } else {
        echo "Record for patient $id: [REDACTED]";
    }
}

// 主逻辑
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['patient_ID'] ?? 'unknown';
    if (checkAccess()) {
        showRecord($id);
    } else {
        echo "Unauthorized access. You must be a doctor to view records.";
    }
} else {
    echo <<<HTML
    <h2>患者信息查询</h2>
    <p>仅具有医生 (role=doctor) 身份的用户可以查看患者记录,里面可能会有flag哦。</p>
    <p style='color:gray;'>你当前身份是：nurse</p>
    <form method="post">
        <label for="patient_ID">患者ID：</label>
        <input type="text" name="patient_ID" placeholder="例如 admin"><br>
        <input type="hidden" name="role" value="nurse">
        <button type="submit">提交查询</button>
    </form>
HTML;
}
?>
