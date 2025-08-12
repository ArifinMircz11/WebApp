<?php
require 'config.php';

// Ambil data kelas (untuk select di form)
$dataKelas = $pdo->query("SELECT * FROM kelas ORDER BY tingkat, nama_kelas")->fetchAll(PDO::FETCH_ASSOC);

// Tambah siswa
if (isset($_POST['tambah'])) {
    $nisn = $_POST['nisn'];
    $nama = $_POST['nama'];
    $no_hp = $_POST['no_hp'];
    $kelas_id = $_POST['kelas_id'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $stmt = $pdo->prepare("INSERT INTO siswa (nisn, nama, no_hp, kelas_id, tanggal_lahir, alamat) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nisn, $nama, $no_hp, $kelas_id, $tanggal_lahir, $alamat]);
    header("Location: siswa.php");
    exit;
}

// Hapus siswa
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $pdo->prepare("DELETE FROM siswa WHERE id=?")->execute([$id]);
    header("Location: siswa.php");
    exit;
}

// Edit siswa
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nisn = $_POST['nisn'];
    $nama = $_POST['nama'];
    $no_hp = $_POST['no_hp'];
    $kelas_id = $_POST['kelas_id'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $stmt = $pdo->prepare("UPDATE siswa SET nisn=?, nama=?, no_hp=?, kelas_id=?, tanggal_lahir=?, alamat=? WHERE id=?");
    $stmt->execute([$nisn, $nama, $no_hp, $kelas_id, $tanggal_lahir, $alamat, $id]);
    header("Location: siswa.php");
    exit;
}

// Ambil data siswa (join kelas)
$dataSiswa = $pdo->query("SELECT siswa.*, kelas.nama_kelas FROM siswa LEFT JOIN kelas ON siswa.kelas_id=kelas.id ORDER BY nama")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h2 class="mb-4">Data Siswa</h2>
    <!-- Form Tambah Siswa -->
    <form class="row g-2 mb-4" method="POST">
        <div class="col-md-2"><input type="text" name="nisn" class="form-control" placeholder="NISN" required></div>
        <div class="col-md-3"><input type="text" name="nama" class="form-control" placeholder="Nama" required></div>
        <div class="col-md-2"><input type="text" name="no_hp" class="form-control" placeholder="No HP"></div>
        <div class="col-md-2">
            <select name="kelas_id" class="form-select" required>
                <option value="">Pilih Kelas</option>
                <?php foreach($dataKelas as $kls): ?>
                <option value="<?= $kls['id'] ?>"><?= htmlspecialchars($kls['nama_kelas']) ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="col-md-2"><input type="date" name="tanggal_lahir" class="form-control" placeholder="Tanggal Lahir"></div>
        <div class="col-md-1"><button type="submit" name="tambah" class="btn btn-primary w-100">Tambah</button></div>
        <div class="col-md-12 mt-2"><input type="text" name="alamat" class="form-control" placeholder="Alamat"></div>
    </form>
    <!-- Tabel Data Siswa -->
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>NISN</th>
                <th>Nama</th>
                <th>No HP</th>
                <th>Kelas</th>
                <th>Tgl Lahir</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach($dataSiswa as $siswa): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($siswa['nisn']) ?></td>
                <td><?= htmlspecialchars($siswa['nama']) ?></td>
                <td><?= htmlspecialchars($siswa['no_hp']) ?></td>
                <td><?= htmlspecialchars($siswa['nama_kelas']) ?></td>
                <td><?= htmlspecialchars($siswa['tanggal_lahir']) ?></td>
                <td><?= htmlspecialchars($siswa['alamat']) ?></td>
                <td>
                    <!-- Tombol Edit -->
                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $siswa['id'] ?>">Edit</button>
                    <!-- Tombol Hapus -->
                    <a href="siswa.php?hapus=<?= $siswa['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus siswa ini?')">Hapus</a>
                </td>
            </tr>
            <!-- Modal Edit -->
            <div class="modal fade" id="modalEdit<?= $siswa['id'] ?>" tabindex="-1">
              <div class="modal-dialog">
                <form method="POST" class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $siswa['id'] ?>">
                    <div class="mb-2">
                      <label>NISN</label>
                      <input type="text" name="nisn" class="form-control" value="<?= htmlspecialchars($siswa['nisn']) ?>" required>
                    </div>
                    <div class="mb-2">
                      <label>Nama</label>
                      <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($siswa['nama']) ?>" required>
                    </div>
                    <div class="mb-2">
                      <label>No HP</label>
                      <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($siswa['no_hp']) ?>">
                    </div>
                    <div class="mb-2">
                      <label>Kelas</label>
                      <select name="kelas_id" class="form-select" required>
                        <option value="">Pilih Kelas</option>
                        <?php foreach($dataKelas as $kls): ?>
                        <option value="<?= $kls['id'] ?>" <?= ($siswa['kelas_id']==$kls['id']?'selected':'') ?>>
                          <?= htmlspecialchars($kls['nama_kelas']) ?>
                        </option>
                        <?php endforeach ?>
                      </select>
                    </div>
                    <div class="mb-2">
                      <label>Tanggal Lahir</label>
                      <input type="date" name="tanggal_lahir" class="form-control" value="<?= htmlspecialchars($siswa['tanggal_lahir']) ?>">
                    </div>
                    <div class="mb-2">
                      <label>Alamat</label>
                      <input type="text" name="alamat" class="form-control" value="<?= htmlspecialchars($siswa['alamat']) ?>">
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" name="edit" class="btn btn-success">Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                  </div>
                </form>
              </div>
            </div>
            <?php endforeach ?>
        </tbody>
    </table>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>