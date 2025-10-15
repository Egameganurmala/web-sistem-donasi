<?php
session_start();
include "../config/koneksi.php";

if (!isset($_GET['id'])) {
    header("Location: ../index.php");
    exit;
}

$id = intval($_GET['id']);
$donasi = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT d.*, p.nama_program
    FROM donasi d
    JOIN donasi_program p ON d.program_id = p.id
    WHERE d.id = '$id'
"));

if (!$donasi) {
    echo "<script>alert('Data donasi tidak ditemukan');window.location='index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bukti Donasi | DonasiYuk</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="card shadow-sm p-4 mx-auto" style="max-width: 550px;">
    <h4 class="text-success mb-3 text-center">Bukti Donasi</h4>
    <hr>
    <p><strong>Program:</strong> <?= htmlspecialchars($donasi['nama_program']) ?></p>
    <p><strong>Nama Donatur:</strong> <?= htmlspecialchars($donasi['nama_lengkap']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($donasi['email']) ?></p>
    <p><strong>Jumlah Donasi:</strong> Rp <?= number_format($donasi['jumlah'], 0, ',', '.') ?></p>
    <p><strong>Metode Pembayaran:</strong> <?= strtoupper($donasi['metode']) ?></p>
    <p><strong>Nomor VA:</strong> <span class="text-danger fw-bold"><?= htmlspecialchars($donasi['no_va']) ?></span></p>
    <p><strong>Status:</strong> 
      <?php if($donasi['status'] == 'pending'): ?>
        <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
      <?php else: ?>
        <span class="badge bg-success">Selesai</span>
      <?php endif; ?>
    </p>

    <hr>

    <?php if ($donasi['status'] == 'pending'): ?>
      <h5 class="text-center mb-3">Upload Bukti Transfer</h5>

      <form action="proses_upload_bukti.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="donasi_id" value="<?= $donasi['id'] ?>">
        <div class="mb-3">
          <input type="file" name="bukti" class="form-control" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Kirim Bukti Transfer</button>
      </form>
    <?php else: ?>
      <div class="alert alert-success text-center">Donasi telah dikonfirmasi. Terima kasih!</div>
    <?php endif; ?>

    <?php if (!empty($donasi['bukti_transfer'])): ?>
      <div class="text-center mt-4">
        <h6>Bukti Transfer:</h6>
        <img src="../uploads/bukti_tf/<?= htmlspecialchars($donasi['bukti_transfer']) ?>" class="img-fluid rounded shadow-sm" alt="Bukti Transfer">
      </div>
    <?php endif; ?>

    <div class="mt-4 text-center">
      <a href="dashboard.php" class="btn btn-outline-success">Kembali ke Beranda</a>
    </div>
  </div>
</div>

</body>
</html>
