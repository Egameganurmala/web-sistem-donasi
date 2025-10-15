<?php
include "config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'donatur';

    $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
    if (mysqli_query($koneksi, $query)) {
        header("Location: login.php");
        exit;
    } else {
        $error = "Gagal mendaftar: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - DonasiYuk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(135deg, #d9fdd3, #f8fff7);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: "Poppins", sans-serif;
    }

    .register-card {
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
      padding: 2rem;
      width: 100%;
      max-width: 420px;
      transition: transform 0.3s ease;
    }

    .register-card:hover {
      transform: translateY(-3px);
    }

    .register-card h3 {
      font-weight: 600;
      color: #2b9348;
    }

    .btn-success {
      background-color: #2b9348;
      border: none;
    }

    .btn-success:hover {
      background-color: #208b3a;
    }

    a {
      color: #2b9348;
      text-decoration: none;
      font-weight: 500;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="register-card text-center">
    <h3 class="mb-4">Daftar Akun DonasiYuk 💚</h3>

    <?php if(isset($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="mb-3 text-start">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control form-control-lg" required>
      </div>

     

      <div class="mb-3 text-start">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control form-control-lg" required>
      </div>

      <button type="submit" class="btn btn-success w-100 btn-lg">Daftar Sekarang</button>
    </form>

    <p class="mt-4">
      Sudah punya akun? <a href="login.php">Login di sini</a>
    </p>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
