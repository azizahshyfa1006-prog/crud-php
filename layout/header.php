<?php
// Pastikan session sudah aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once 'config/app.php';
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? $title : 'CRUD PHP'; ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  </head>
  <body>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand d-inline-flex align-items-center" href="#">
        <img src="assets/img/logo-rpl.jpg" alt="Logo" width="30" height="30" class="d-inline-block align-text-top me-2">
        CRUD PHP
      </a>
      
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          
          <?php if ($_SESSION['level'] == 1 || $_SESSION['level'] == 2) : ?>
            <li class="nav-item">
              <a class="nav-link active" href="index.php">Barang</a>
            </li>
          <?php endif; ?>

          <?php if ($_SESSION['level'] == 3) : ?>
            <li class="nav-item">
              <a class="nav-link" href="mahasiswa.php">Mahasiswa</a>
            </li>
          <?php endif; ?>

          <li class="nav-item">
            <a class="nav-link" href="crud-modal.php">Akun</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php" onclick="return confirm('Yakin ingin keluar.?')">keluar</a>
          </li>
          
          <?php if (isset($_SESSION['level']) && ($_SESSION['level'] == 1 || $_SESSION['level'] == 2)): ?>
            <!-- Tempat menu tambahan khusus level 1 atau 2 jika ada -->
          <?php endif; ?>
        </ul>

        <div class="d-flex">
          <span class="navbar-text text-light">
            <?= isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Guest'; ?>
          </span>
        </div>
      </div>
    </div>
  </nav>