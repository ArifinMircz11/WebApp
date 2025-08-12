<?php
include('../db/config.php');
echo "<h2>Riwayat Semua Absen</h2>";
for ($k = 1; $k <= 3; $k++) {
    $table = "absen_kelas_" . $k;
    if ($conn->query("SHOW TABLES LIKE '$table'")->num_rows > 0) {
        echo "<h3>Kelas $k</h3><table border='1'><tr><th>ID</th><th>Tanggal</th><th>Waktu</th></tr>";
        $result = $conn->query("SELECT * FROM $table ORDER BY tanggal DESC, waktu DESC");
        while($r = $result->fetch_assoc()) {
            echo "<tr><td>{$r['siswa_id']}</td><td>{$r['tanggal']}</td><td>{$r['waktu']}</td></tr>";
        }
        echo "</table>";
    }
}
?>