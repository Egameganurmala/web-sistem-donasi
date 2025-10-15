<?php
include "config/koneksi.php";
session_start();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$donasi = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT d.*, p.nama_program
    FROM donasi d
    JOIN donasi_program p ON d.program_id = p.id
    WHERE d.id = '$id'
"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Upload Bukti Transfer - DonasiYuk</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="card shadow-sm p-4 mx-auto" style="max-width: 500px;">
    <h4 class="text-success mb-3">Upload Bukti Transfer</h4>
    <p><strong>Program:</strong> <?= htmlspecialchars($donasi['nama_program']) ?></p>
    <p><strong>Nominal:</strong> Rp <?= number_format($donasi['jumlah'],0,',','.') ?></p>
    <p><strong>Metode:</strong> <?= strtoupper($donasi['metode']) ?></p>

    <form action="proses_upload_bukti.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="donasi_id" value="<?= $id ?>">
      <div class="mb-3">
        <label class="form-label">Unggah Bukti Transfer (JPG/PNG)</label>
        <input type="file" name="bukti" class="form-control" accept="image/*" required>
      </div>
      <button type="submit" class="btn btn-success w-100">Kirim Bukti</button>
    </form>
  </div>
</div>

</body>
</html>
