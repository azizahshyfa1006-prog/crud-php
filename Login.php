<?php 

session_start();

include 'config/app.php';

// check apakah tombol login ditekan
if (isset($_POST['login'])) {
    // ambil input username dan password
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    // check username
    $result = mysqli_query($db, "SELECT * FROM akun WHERE username = '$username'");

    // Cek 1: Apakah query SQL berhasil & username ditemukan?
    if (mysqli_num_rows($result) == 1) {
        $hasil = mysqli_fetch_assoc($result);

        // Cek 2: Terima password hash ATAU password teks biasa
        if (password_verify($password, $hasil['password']) || $password == $hasil['password']) {
            // set session
            $_SESSION['login']         = true;
            $_SESSION['id_akun']      = $hasil['id_akun'];
            $_SESSION['nama']         = $hasil['nama'];
            $_SESSION['username']     = $hasil['username'];
            $_SESSION['email']        = $hasil['email'];
            $_SESSION['level']        = $hasil['level'];

            // jika login benar arahkan ke file index2.php
            header("Location: index2.php");
            exit;
        } else {
            // Pesan jika password tidak cocok
            echo "<script>alert('Password yang dimasukkan tidak cocok!');</script>";
        }
    } else {
        // Pesan jika username tidak ditemukan
        echo "<script>alert('Username " . $username . " tidak ditemukan di database!');</script>";
    }
    
    $error = true;
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/">

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Favicons -->
    <link rel="icon" href="assets/img/logo-rpl.jpg">
    <meta name="theme-color" content="#7952b3">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      html,
      body {
        height: 100%;
      }

      body {
        display: flex;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        width: 100%;
        max-width: 330px;
        padding: 15px;
        margin: auto;
      }

      .form-signin .checkbox {
        font-weight: 400;
      }

      .form-signin .form-floating:focus-within {
        z-index: 2;
      }

      .form-signin input[type="text"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
      }

      .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
      }
    </style>
    
    <!-- Custom styles for this template -->
    <link href="assets/css/signin.css" rel="stylesheet">
  </head>
  <body class="text-center">
    
<main class="form-signin">
  <form action="" method="POST">
    <!-- Penambahan Logo di Atas Halaman Login -->
    <div class="mb-3">
        <img src="assets/img/logo-boostrap.png" alt="Logo" width="80" height="80" class="mb-2">
    </div>
    
    <h1 class="h3 mb-3 fw-normal">Login Admin</h1>

    <?php if (isset($error)) : ?>
        <div class="alert alert-danger text-center">
            <b>Username/Password SALAH</b>
        </div>
    <?php endif; ?>

    <div class="form-floating">
      <input type="text" name="username" class="form-control" id="floatingInput" placeholder="Username..." required>
      <label for="floatingInput">Username</label>
    </div>
    <div class="form-floating">
      <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password..." required>
      <label for="floatingPassword">Password</label>
    </div>

    <button class="w-100 btn btn-lg btn-primary" type="submit" name="login">Login</button>
    <p class="mt-5 mb-3 text-muted">&copy; 2017–2021</p>
  </form>
</main>

  </body>
</html>