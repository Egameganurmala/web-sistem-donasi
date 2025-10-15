<?php
session_start();
include "../config/koneksi.php";

date_default_timezone_set('Asia/Jakarta');

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../index.php");
    exit;
}

$nama = trim($_POST['nama_lengkap']);
$email = trim($_POST['email']);
$program_id = intval($_POST['program_id']);
$jumlah = intval($_POST['jumlah']);
$metode = strtolower(trim($_POST['metode']));
$tgl = date("Y-m-d H:i:s");

// Validasi metode
$metode_valid = ['ovo', 'dana', 'gopay'];
if (!in_array($metode, $metode_valid)) {
    echo "<script>alert('Metode pembayaran tidak valid!');window.location='index.php';</script>";
    exit;
}

// Generate nomor VA unik
switch ($metode) {
    case 'ovo': $prefix = '001'; break;
    case 'dana': $prefix = '002'; break;
    case 'gopay': $prefix = '003'; break;
}
$no_va = $prefix . rand(1000000000, 9999999999);

// Jika login, ambil user_id
$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 'NULL';

// Simpan donasi
$query = "INSERT INTO donasi (user_id, program_id, nama_lengkap, email, jumlah, metode, tgl_donasi, status, no_va)
          VALUES ($user_id, '$program_id', '$nama', '$email', '$jumlah', '$metode', '$tgl', 'pending', '$no_va')";

if (mysqli_query($koneksi, $query)) {
    $id = mysqli_insert_id($koneksi);
    header("Location: bukti_donasi.php?id=$id");
    exit;
} else {
    die("Terjadi kesalahan server: " . mysqli_error($koneksi));
}

?>
