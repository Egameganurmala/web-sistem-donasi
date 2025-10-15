<?php
include "config/koneksi.php";
session_start();

if (isset($_POST['donasi_id'])) {
    $id = intval($_POST['donasi_id']);

    $folder = "uploads/bukti_tf/";
    if (!file_exists($folder)) mkdir($folder, 0777, true);

    $namaFile = $_FILES['bukti']['name'];
    $tmp = $_FILES['bukti']['tmp_name'];
    $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
    $namaBaru = "bukti_" . time() . ".$ext";
    $path = $folder . $namaBaru;

    if (move_uploaded_file($tmp, $path)) {
        mysqli_query($koneksi, "UPDATE donasi SET bukti_transfer='$namaBaru' WHERE id='$id'");
        $_SESSION['pesan'] = "Bukti transfer berhasil diunggah! Tunggu verifikasi admin.";
    } else {
        $_SESSION['pesan'] = "Gagal mengunggah bukti transfer.";
    }

    header("Location: bukti_donasi.php?id=$id");
    exit;
}
?>
