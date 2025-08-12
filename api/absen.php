<?php
include('../db/config.php');
$id = $_POST['id'];
$tanggal = date('Y-m-d');
$kelas = substr($id, 0, 1); // Asumsi ID berisi info kelas

$table = "absen_kelas_" . $kelas;
$conn->query("CREATE TABLE IF NOT EXISTS $table (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siswa_id VARCHAR(50),
    tanggal DATE,
    waktu TIME,
    UNIQUE(siswa_id, tanggal)
)");

$stmt = $conn->prepare("INSERT IGNORE INTO $table (siswa_id, tanggal, waktu) VALUES (?, ?, NOW())");
$stmt->bind_param("ss", $id, $tanggal);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Absen berhasil!";
} else {
    echo "Sudah absen hari ini.";
}
?>