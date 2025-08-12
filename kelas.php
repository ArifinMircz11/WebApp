<?php
require 'config.php';

// Proses tambah kelas
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama_kelas'];
    $tingkat = $_POST['tingkat'];
    $wali = $_POST['wali_kelas'];
    $stmt = $pdo->prepare("INSERT INTO kelas (nama_kelas, tingkat, wali_kelas) VALUES (?, ?, ?)");
    $stmt->execute([$nama, $tingkat, $wali]);
    header("Location: kelas.php");
    exit;
}

// Proses hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $pdo->prepare("DELETE FROM kelas WHERE id=?")->execute([$id]);
    header("Location: kelas.php");
    exit;
}

// Proses edit
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama_kelas'];
    $tingkat = $_POST['tingkat'];
    $wali = $_POST['wali_kelas'];
    $stmt = $pdo->prepare("UPDATE kelas SET nama_kelas=?, tingkat=?, wali_kelas=? WHERE id=?");
    $stmt->execute([$nama, $tingkat, $wali, $id]);
    header("Location: kelas.php");
    exit;
}

// Ambil data kelas
$dataKelas = $pdo->query("SELECT * FROM kelas ORDER BY tingkat, nama_kelas")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h2 class="mb-4">Data Kelas</h2>
    <!-- Form Tambah Kelas -->
    <form class="row g-2 mb-4" method="POST">
        <div class="col-md-4">
            <input type="text" name="nama_kelas" class="form-control" placeholder="Nama Kelas" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="tingkat" class="form-control" placeholder="Tingkat" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="wali_kelas" class="form-control" placeholder="Wali Kelas">
        </div>
        <div class="col-md-2">
            <button type="submit" name="tambah" class="btn btn-primary w-100">Tambah</button>
        </div>
    </form>
    <!-- Tabel Data Kelas -->
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Kelas</th>
                <th>Tingkat</th>
                <th>Wali Kelas</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach($dataKelas as $kls): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($kls['nama_kelas']) ?></td>
                <td><?= htmlspecialchars($kls['tingkat']) ?></td>
                <td><?= htmlspecialchars($kls['wali_kelas']) ?></td>
                <td>
                    <!-- Tombol Edit (buka modal) -->
                    <button type="button" class="btn btn-warning btn-sm"
                      data-bs-toggle="modal"
                      data-bs-target="#modalEdit<?= $kls['id'] ?>">Edit</button>
                    <!-- Tombol Hapus -->
                    <a href="kelas.php?hapus=<?= $kls['id'] ?>" class="btn btn-danger btn-sm"
                        onclick="return confirm('Yakin hapus?')">Hapus</a>
                </td>
            </tr>
            <!-- Modal Edit -->
            <div class="modal fade" id="modalEdit<?= $kls['id'] ?>" tabindex="-1">
              <div class="modal-dialog">
                <form method="POST" class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Data Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $kls['id'] ?>">
                    <div class="mb-2">
                      <label>Nama Kelas</label>
                      <input type="text" name="nama_kelas" class="form-control" value="<?= htmlspecialchars($kls['nama_kelas']) ?>" required>
                    </div>
                    <div class="mb-2">
                      <label>Tingkat</label>
                      <input type="text" name="tingkat" class="form-control" value="<?= htmlspecialchars($kls['tingkat']) ?>" required>
                    </div>
                    <div class="mb-2">
                      <label>Wali Kelas</label>
                      <input type="text" name="wali_kelas" class="form-control" value="<?= htmlspecialchars($kls['wali_kelas']) ?>">
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