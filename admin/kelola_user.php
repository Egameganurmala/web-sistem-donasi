<?php
session_start();
include "../config/koneksi.php";

// Cek apakah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

// Ambil semua user
$users = mysqli_query($koneksi, "SELECT * FROM users ORDER BY role DESC, username ASC");

// Ambil data admin login
$admin_id = $_SESSION['id'];
$admin = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id='$admin_id'"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola User - DonasiYuk</title>
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
</style>
</head>
<body>

<div class="sidebar">
  <h4><i class="bi bi-heart-fill me-2"></i>DonasiYuk</h4>
  <a href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
  <a href="program_admin.php" class="active"><i class="bi bi-journal-text me-2"></i>Kelola Program</a>
  <a href="kelola_user.php"><i class="bi bi-people-fill me-2"></i>Kelola User</a>
  <a href="#" data-bs-toggle="modal" data-bs-target="#editProfil"><i class="bi bi-person-circle me-2"></i>Edit Profil</a>
  <a href="logout.php" onclick="return confirm('Yakin ingin logout?')"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
</div>

<div class="main-content">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-semibold">Kelola User</h4>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahAdmin">
      <i class="bi bi-person-plus me-1"></i>Tambah Admin
    </button>
  </div>

  <div class="card p-4">
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-success text-center">
          <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php while($u = mysqli_fetch_assoc($users)): ?>
          <tr class="text-center">
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td><?= isset($u['email']) ? htmlspecialchars($u['email']) : '<i class="text-muted">-</i>' ?></td>
            <td>
              <?= $u['role'] == 'admin' ? '<span class="badge bg-primary">Admin</span>' : '<span class="badge bg-success">Donatur</span>' ?>
            </td>
            <td>
              <?php if($u['id'] != $admin_id): ?>
                <a href="proses_user.php?hapus=<?= $u['id'] ?>" 
                   onclick="return confirm('Yakin ingin menghapus user ini?')" 
                   class="btn btn-danger btn-sm">
                  <i class="bi bi-trash"></i>
                </a>
              <?php else: ?>
                <span class="text-muted">-</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Tambah Admin -->
<div class="modal fade" id="tambahAdmin" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="proses_user.php" method="POST" class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Tambah Admin Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label>Username</label>
          <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="tambah_admin" class="btn btn-success">Tambah</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Profil Admin -->
<div class="modal fade" id="editProfil" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="proses_user.php" method="POST" class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Edit Profil Saya</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" value="<?= $admin['id'] ?>">
        <div class="mb-3">
          <label>Username</label>
          <input type="text" name="username" value="<?= htmlspecialchars($admin['username']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Email</label>
          <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Password Baru (kosongkan jika tidak ingin ubah)</label>
          <input type="password" name="password" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="edit_profil" class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
