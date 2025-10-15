<?php
session_start();
include "../config/koneksi.php";

// Pastikan hanya admin yang bisa akses file ini
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

// ===============================
// 🔹 1. PROSES EDIT PROFIL ADMIN
// ===============================
if (isset($_POST['edit_profil'])) {
    $id       = intval($_POST['id']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $update = mysqli_query($koneksi, "UPDATE users SET username='$username', email='$email', password='$hashed' WHERE id='$id'");
    } else {
        $update = mysqli_query($koneksi, "UPDATE users SET username='$username', email='$email' WHERE id='$id'");
    }

    if ($update) {
        $_SESSION['pesan'] = "Profil berhasil diperbarui!";
    } else {
        $_SESSION['pesan'] = "Terjadi kesalahan: " . mysqli_error($koneksi);
    }

    header("Location: dashboard.php");
    exit;
}

// ===============================
// 🔹 2. TAMBAH USER BARU
// ===============================
if (isset($_POST['tambah_user'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = mysqli_real_escape_string($koneksi, $_POST['role']);

    $query = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $_SESSION['pesan'] = "User baru berhasil ditambahkan!";
    } else {
        $_SESSION['pesan'] = "Gagal menambah user: " . mysqli_error($koneksi);
    }

    header("Location: kelola_user.php");
    exit;
}

// ===============================
// 🔹 3. UBAH DATA USER
// ===============================
if (isset($_POST['edit_user'])) {
    $id       = intval($_POST['id']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $role     = mysqli_real_escape_string($koneksi, $_POST['role']);
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET username='$username', email='$email', role='$role', password='$hashed' WHERE id='$id'";
    } else {
        $query = "UPDATE users SET username='$username', email='$email', role='$role' WHERE id='$id'";
    }

    $update = mysqli_query($koneksi, $query);

    if ($update) {
        $_SESSION['pesan'] = "Data user berhasil diperbarui!";
    } else {
        $_SESSION['pesan'] = "Gagal memperbarui user: " . mysqli_error($koneksi);
    }

    header("Location: kelola_user.php");
    exit;
}

// ===============================
// 🔹 4. HAPUS USER
// ===============================
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $hapus = mysqli_query($koneksi, "DELETE FROM users WHERE id='$id'");

    if ($hapus) {
        $_SESSION['pesan'] = "User berhasil dihapus!";
    } else {
        $_SESSION['pesan'] = "Gagal menghapus user: " . mysqli_error($koneksi);
    }

    header("Location: kelola_user.php");
    exit;
}

// ===============================
// 🔹 5. DEFAULT
// ===============================
header("Location: dashboard.php");
exit;
?>
