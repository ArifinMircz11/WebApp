<?php
require_once __DIR__ . '/../config.php';

$origin = $_ENV['ALLOWED_ORIGIN'] ?? '*';
header('Access-Control-Allow-Origin: ' . $origin);
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$id = $_POST['id'] ?? null;
if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'ID wajib diisi.']);
    exit;
}

$tanggal = date('Y-m-d');
$kelas   = substr($id, 0, 1);
$table   = 'absen_kelas_' . $kelas;

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `$table` (
        id INT AUTO_INCREMENT PRIMARY KEY,
        siswa_id VARCHAR(50),
        tanggal DATE,
        waktu TIME,
        UNIQUE(siswa_id, tanggal)
    )");

    $stmt = $pdo->prepare("INSERT IGNORE INTO `$table` (siswa_id, tanggal, waktu) VALUES (?, ?, NOW())");
    $stmt->execute([$id, $tanggal]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['message' => 'Absen berhasil!']);
    } else {
        echo json_encode(['message' => 'Sudah absen hari ini.']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    error_log($e->getMessage());
    echo json_encode(['error' => 'Terjadi kesalahan pada server.']);
}
