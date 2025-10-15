<?php
session_start();
include "../config/koneksi.php";

// 🔒 Hanya admin yang boleh akses
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: ../index.php");
    exit;
}

// 🔹 Ambil data admin
$admin_id = $_SESSION['id'];
$admin = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id='$admin_id'"));

// 🔹 Ambil semua program donasi
$programs = mysqli_query($koneksi, "SELECT * FROM donasi_program ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Program - DonasiYuk</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; background-color: #f4f6f8; margin: 0; padding: 0; }
.sidebar {
  width: 240px;
  height: 100vh;
  position: fixed;
  background: linear-gradient(180deg, #2b9348, #208b3a);
  color: #fff;
  padding: 20px 0;
  top: 0;
  left: 0;
}
.sidebar h4 { font-weight: 700; text-align: center; margin-bottom: 30px; }
.sidebar a { display: block; padding: 12px 25px; color: #fff; text-decoration: none; transition: 0.3s; }
.sidebar a:hover, .sidebar a.active { background-color: rgba(255,255,255,0.15); border-left: 4px solid #fff; }

.main-content { margin-left: 240px; padding: 30px; }
.card { border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
.table thead { background: #2b9348; color: #fff; }
img.preview { max-width: 100px; border-radius: 10px; }
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h4><i class="bi bi-heart-fill me-2"></i>DonasiYuk</h4>
  <a href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
  <a href="program_admin.php" class="active"><i class="bi bi-journal-text me-2"></i>Kelola Program</a>
  <a href="kelola_user.php"><i class="bi bi-people-fill me-2"></i>Kelola User</a>
  <a href="#" data-bs-toggle="modal" data-bs-target="#editProfil"><i class="bi bi-person-circle me-2"></i>Edit Profil</a>
  <a href="logout.php" onclick="return confirm('Yakin ingin logout?')"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-semibold">Kelola Program</h4>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahProgram">
      <i class="bi bi-plus-circle me-1"></i>Tambah Program
    </button>
  </div>

  <!-- Tabel Program -->
  <div class="card p-4">
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center">
        <thead>
          <tr>
            <th>Nama Program</th>
            <th>Deskripsi</th>
            <th>Gambar</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if(mysqli_num_rows($programs) == 0): ?>
            <tr><td colspan="4" class="text-muted">Belum ada program donasi.</td></tr>
          <?php else: ?>
            <?php while($p = mysqli_fetch_assoc($programs)): ?>
            <tr>
              <td><?= htmlspecialchars($p['nama_program']) ?></td>
              <td><?= htmlspecialchars($p['deskripsi']) ?></td>
              <td>
                <?php if(!empty($p['gambar'])): ?>
                  <img src="../uploads/program/<?= $p['gambar'] ?>" class="preview">
                <?php else: ?>
                  <span class="text-muted">Tidak ada</span>
                <?php endif; ?>
              </td>
              <td>
                <!-- Tombol Edit -->
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                        data-bs-target="#editProgram<?= $p['id'] ?>">
                  <i class="bi bi-pencil-square"></i>
                </button>
              </td>
            </tr>
            <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Tambah Program -->
<div class="modal fade" id="tambahProgram" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="proses_program.php" method="POST" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Tambah Program Donasi</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label>Nama Program</label>
          <input type="text" name="nama_program" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Deskripsi</label>
          <textarea name="deskripsi" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
          <label>Gambar Program</label>
          <input type="file" name="gambar" class="form-control" accept="image/*">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="tambah_program" class="btn btn-success w-100">Simpan Program</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Program -->
<?php
// Modal Edit dipisah di luar table supaya tidak bentrok
$programs_modal = mysqli_query($koneksi, "SELECT * FROM donasi_program ORDER BY id DESC");
while($p = mysqli_fetch_assoc($programs_modal)): ?>
<div class="modal fade" id="editProgram<?= $p['id'] ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="proses_program.php" method="POST" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Edit Program</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" value="<?= $p['id'] ?>">
        <div class="mb-3">
          <label>Nama Program</label>
          <input type="text" name="nama_program" value="<?= htmlspecialchars($p['nama_program']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Deskripsi</label>
          <textarea name="deskripsi" class="form-control" required><?= htmlspecialchars($p['deskripsi']) ?></textarea>
        </div>
        <div class="mb-3">
          <label>Gambar Saat Ini</label><br>
          <?php if(!empty($p['gambar'])): ?>
            <img src="../uploads/program/<?= $p['gambar'] ?>" class="preview mb-2">
          <?php else: ?>
            <span class="text-muted small">Belum ada gambar</span>
          <?php endif; ?>
        </div>
        <div class="mb-3">
          <label>Ganti Gambar (Opsional)</label>
          <input type="file" name="gambar" class="form-control" accept="image/*">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="edit_program" class="btn btn-success w-100">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>
<?php endwhile; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
