<?php
session_start();
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $role = "user";

    // ตรวจสอบว่า username ซ้ำหรือไม่
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // ถ้ามีชื่อผู้ใช้นี้ในฐานข้อมูลแล้ว
        $_SESSION["error"] = "ชื่อผู้ใช้นี้ถูกใช้แล้ว";
    } else {
        // ถ้าไม่มีชื่อผู้ใช้นี้ในฐานข้อมูล
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $role);

        if ($stmt->execute()) {
            $_SESSION["message"] = "สมัครสมาชิกสำเร็จ กรุณาเข้าสู่ระบบ";
            header("Location: login.php");
            exit();  // หยุดการทำงานหลังจาก redirect
        } else {
            $_SESSION["error"] = "เกิดข้อผิดพลาดในการสมัครสมาชิก";
        }
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">

<div class="card shadow p-4" style="width: 400px;">
    <h3 class="text-center">สมัครสมาชิก</h3>

    <?php if (isset($_SESSION["error"])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION["error"]; unset($_SESSION["error"]); ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">สมัครสมาชิก</button>
    </form>

    <div class="text-center mt-3">
        <small>มีบัญชีอยู่แล้ว? <a href="login.php">เข้าสู่ระบบ</a></small>
    </div>
</div>

</body>
</html>
