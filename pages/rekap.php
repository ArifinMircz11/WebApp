<?php
include('../db/config.php');
$kelas = $_GET['kelas'] ?? 'x';
$table = "absen_kelas_" . $kelas;
$result = $conn->query("SELECT * FROM $table ORDER BY tanggal DESC, waktu DESC");
echo "<h2>Rekap Kelas $kelas</h2><table border='1'><tr><th>ID</th><th>Tanggal</th><th>Waktu</th></tr>";
while($r = $result->fetch_assoc()) {
    echo "<tr><td>{$r['siswa_id']}</td><td>{$r['tanggal']}</td><td>{$r['waktu']}</td></tr>";
}
echo "</table>";
?>