<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['selesai'])) {
    $id = intval($_GET['selesai']);
    
    // Update status donasi jadi completed
    $update = mysqli_query($koneksi, "UPDATE donasi SET status='completed' WHERE id='$id'");
    
    if ($update) {
        $_SESSION['pesan'] = "Donasi berhasil diselesaikan! Statistik telah diperbarui.";
    } else {
        $_SESSION['pesan'] = "Gagal menyelesaikan donasi: " . mysqli_error($koneksi);
    }
    
    header("Location: dashboard.php");
    exit;
}
?>
