<?php
require 'config.php';

// Ambil daftar kelas
$dataKelas = $pdo->query("SELECT * FROM kelas ORDER BY tingkat, nama_kelas")->fetchAll(PDO::FETCH_ASSOC);

// Proses tambah presensi (manual)
if (isset($_POST['absen'])) {
    $siswa_id = $_POST['siswa_id'];
    $tanggal = $_POST['tanggal'];
    $status = $_POST['status'];
    $keterangan = $_POST['keterangan'];
    $jam_masuk = date('H:i:s');
    $pdo->prepare("INSERT INTO presensi (siswa_id, tanggal, jam_masuk, status, keterangan) VALUES (?, ?, ?, ?, ?)")
        ->execute([$siswa_id, $tanggal, $jam_masuk, $status, $keterangan]);
    header("Location: presensi.php");
    exit;
}

// Filter kelas dan tanggal
$kelas_id = $_GET['kelas_id'] ?? '';
$tanggal = $_GET['tanggal'] ?? date('Y-m-d');

// Ambil siswa di kelas yang dipilih
$siswa = [];
if ($kelas_id) {
    $stmt = $pdo->prepare("SELECT * FROM siswa WHERE kelas_id=? ORDER BY nama");
    $stmt->execute([$kelas_id]);
    $siswa = $stmt->fetchAll();
}

// Ambil rekap presensi hari ini
$rekap = [];
if ($kelas_id) {
    $stmt = $pdo->prepare("SELECT s.nisn, s.nama, p.status, p.jam_masuk, p.keterangan 
        FROM siswa s 
        LEFT JOIN presensi p ON s.id=p.siswa_id AND p.tanggal=? 
        WHERE s.kelas_id=?
        ORDER BY s.nama");
    $stmt->execute([$tanggal, $kelas_id]);
    $rekap = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Presensi Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<h2>Presensi Siswa</h2>
<!-- Filter kelas dan tanggal -->
<form class="row mb-3" method="GET">
    <div class="col-md-3">
        <select name="kelas_id" class="form-select" onchange="this.form.submit()">
            <option value="">Pilih Kelas</option>
            <?php foreach($dataKelas as $kls): ?>
            <option value="<?= $kls['id'] ?>" <?= ($kelas_id==$kls['id']?'selected':'') ?>>
                <?= htmlspecialchars($kls['nama_kelas']) ?>
            </option>
            <?php endforeach ?>
        </select>
    </div>
    <div class="col-md-3">
        <input type="date" name="tanggal" class="form-control" value="<?= htmlspecialchars($tanggal) ?>" onchange="this.form.submit()">
    </div>
</form>

<?php if($kelas_id): ?>
<!-- Form presensi manual (per siswa) -->
<form class="mb-4" method="POST">
    <input type="hidden" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>">
    <div class="row">
        <div class="col-md-3">
            <select name="siswa_id" class="form-select" required>
                <option value="">Pilih Siswa</option>
                <?php foreach($siswa as $s): ?>
                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nama']) ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select" required>
                <option value="hadir">Hadir</option>
                <option value="izin">Izin</option>
                <option value="sakit">Sakit</option>
                <option value="alpa">Alpa</option>
            </select>
        </div>
        <div class="col-md-4">
            <input type="text" name="keterangan" class="form-control" placeholder="Keterangan">
        </div>
        <div class="col-md-2">
            <button type="submit" name="absen" class="btn btn-primary w-100">Absen</button>
        </div>
    </div>
</form>

<!-- Tabel rekap presensi -->
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>NISN</th>
            <th>Nama</th>
            <th>Status</th>
            <th>Jam Masuk</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=1; foreach($rekap as $row): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nisn']) ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= htmlspecialchars($row['status'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['jam_masuk'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['keterangan'] ?? '-') ?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>
<?php endif ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>