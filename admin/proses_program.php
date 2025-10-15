<?php
include "../config/koneksi.php";

// Tambah Program
if(isset($_POST['tambah_program'])){
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_program']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $gambar = null;

    if(!empty($_FILES['gambar']['name'])){
        $file_tmp = $_FILES['gambar']['tmp_name'];
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $new_name = uniqid('prog_').'.'.$ext;
        move_uploaded_file($file_tmp, "../uploads/program/".$new_name);
        $gambar = $new_name;
    }

    mysqli_query($koneksi, "INSERT INTO donasi_program (nama_program, deskripsi, gambar) 
                            VALUES ('$nama', '$deskripsi', '$gambar')");
    header("Location: program_admin.php");
    exit;
}

// Edit Program
if(isset($_POST['edit_program'])){
    $id = intval($_POST['id']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_program']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    $sql = "UPDATE donasi_program SET nama_program='$nama', deskripsi='$deskripsi'";

    if(!empty($_FILES['gambar']['name'])){
        $file_tmp = $_FILES['gambar']['tmp_name'];
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $new_name = uniqid('prog_').'.'.$ext;
        move_uploaded_file($file_tmp, "../uploads/program/".$new_name);
        $sql .= ", gambar='$new_name'";
    }

    $sql .= " WHERE id='$id'";
    mysqli_query($koneksi, $sql);
    header("Location: program_admin.php");
    exit;
}
?>
