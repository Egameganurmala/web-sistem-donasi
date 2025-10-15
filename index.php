<?php
session_start();
include "config/koneksi.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DonasiYuk | Bersama Kita Berbagi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body { background-color: #f8f9fa; }
  .navbar-brand { font-weight: 700; color: #2b9348 !important; font-size: 1.4rem; }
  .hero {
    background: url('assets/background.jpg') center/cover no-repeat;
    color: white;
    text-align: center;
    padding: 100px 0;
  }
  .hero h1 { font-weight: bold; text-shadow: 1px 1px 5px #000; }
  .hero p { text-shadow: 1px 1px 3px #000; }
  .card { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
  .card:hover { transform: translateY(-5px); transition: 0.3s; }
  .btn-success { background-color: #2b9348; border: none; }
  .btn-success:hover { background-color: #208b3a; }
</style>
</head>

<body>

<!-- 🔹 NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">💚 DonasiYuk</a>
    <div class="d-flex align-items-center">
      <?php if (!empty($_SESSION['id'])): ?>
        <div class="dropdown">
          <a class="btn btn-outline-success dropdown-toggle" href="#" data-bs-toggle="dropdown">
            👤 <?= htmlspecialchars($_SESSION['username'] ?? 'User'); ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="dashboard.php">Dashboard</a></li>
            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
          </ul>
        </div>
      <?php else: ?>
        <a href="login.php" class="btn btn-success">Login / Daftar</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<!-- 🔹 HERO SECTION -->
<section class="hero">
  <div class="container">
    <h1>Bersama Kita Bisa Berbagi</h1>
    <p>Donasi mudah, cepat, dan transparan — tanpa perlu login 💚</p>
    <a href="#program" class="btn btn-light mt-3 px-4 py-2 fw-semibold">Donasi Sekarang</a>
  </div>
</section>

<!-- 🔹 PROGRAM DONASI -->
<section id="program" class="container py-5">
  <h2 class="text-center mb-4 text-success fw-bold">Program Donasi</h2>
  <div class="row justify-content-center">
    <?php
    $programs = mysqli_query($koneksi, "SELECT * FROM donasi_program ORDER BY id DESC");
    if (mysqli_num_rows($programs) == 0) {
        echo "<p class='text-center text-muted'>Belum ada program donasi yang tersedia.</p>";
    }

    while ($p = mysqli_fetch_assoc($programs)) :
      // hitung total donasi yang sudah selesai
      $total = mysqli_fetch_assoc(mysqli_query($koneksi, 
        "SELECT SUM(jumlah) AS total 
         FROM donasi 
         WHERE program_id = '$p[id]' AND status='completed'"));
      $total_donasi = $total['total'] ?? 0;

      // gambar dari database, kosong jika tidak ada
      $gambar = !empty($p['gambar']) && file_exists("uploads/program/" . $p['gambar'])
                ? "uploads/program/" . $p['gambar']
                : "";

      // persentase progres
      $persen = $p['target'] > 0 ? round(($total_donasi / $p['target']) * 100, 1) : 0;
      if ($persen > 100) $persen = 100;
    ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <?php if($gambar): ?>
            <img src="<?= $gambar ?>" class="card-img-top" style="height:220px; object-fit:cover;">
          <?php endif; ?>
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($p['nama_program']) ?></h5>
            <p class="text-muted small"><?= substr($p['deskripsi'], 0, 100) ?>...</p>
            <p><strong>Target:</strong> Rp <?= number_format($p['target'], 0, ',', '.') ?></p>
            <p><strong>Terkumpul:</strong> Rp <?= number_format($total_donasi, 0, ',', '.') ?></p>

            <!-- Progress bar -->
            <div class="progress mb-3" style="height: 10px;">
              <div class="progress-bar bg-success" role="progressbar" 
                   style="width: <?= $persen ?>%;" 
                   aria-valuenow="<?= $persen ?>" 
                   aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <small class="text-muted d-block mb-2">Progress: <?= $persen ?>%</small>

            <button class="btn btn-success w-100" 
                    data-bs-toggle="modal" 
                    data-bs-target="#donasiModal"
                    data-id="<?= $p['id'] ?>"
                    data-nama="<?= htmlspecialchars($p['nama_program']) ?>">
              Donasi Sekarang
            </button>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<!-- 🔹 FOOTER -->
<footer class="text-center py-3 bg-white border-top">
  <small>© <?= date('Y') ?> DonasiYuk — Bersama Kita Berbagi 💚</small>
</footer>

<!-- 🔹 MODAL DONASI -->
<div class="modal fade" id="donasiModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="proses_donasi.php" method="POST">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Form Donasi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="program_id" id="program_id">

          <div class="mb-3">
            <label>Program Donasi</label>
            <input type="text" id="program_nama" class="form-control" readonly>
          </div>

          <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" placeholder="Kosongkan jika ingin anonim">
          </div>

          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" placeholder="Email aktif (opsional)">
          </div>

          <div class="mb-3">
            <label>Nominal Donasi (Rp)</label>
            <input type="number" name="jumlah" class="form-control" required min="1000">
          </div>

          <div class="mb-3">
            <label>Metode Pembayaran</label>
            <select name="metode" class="form-select" required>
              <option value="">-- Pilih E-Wallet --</option>
              <option value="ovo">OVO</option>
              <option value="dana">DANA</option>
              <option value="gopay">GoPay</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success w-100">Donasi Sekarang</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// isi otomatis data program ke modal
const modal = document.getElementById('donasiModal');
modal.addEventListener('show.bs.modal', event => {
  const button = event.relatedTarget;
  document.getElementById('program_id').value = button.getAttribute('data-id');
  document.getElementById('program_nama').value = button.getAttribute('data-nama');
});
</script>

</body>
</html>
