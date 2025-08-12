<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Dashboard</title></head>
<body>
<h2>Selamat datang, <?php echo $_SESSION['user']; ?>!</h2>
<ul>
    <li><a href="scan.php">Scan QR</a></li>
    <li><a href="pages/rekap.php">Rekap Absen</a></li>
    <li><a href="pages/history.php">Riwayat Absen</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>
</body>
</html>