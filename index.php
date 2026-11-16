<?php

session_start();

// Membatasi halaman sebelum login
if (!isset($_SESSION['login'])) {
    echo "<script>
        alert('Login dulu');
        document.location.href = 'login.php';
    </script>";
    exit;
}

$title = 'Data Barang';

// Memanggil fungsi CRUD
require_once 'config/controller.php';

// Menangkap parameter halaman (default ke 'barang')
$page = $_GET['page'] ?? 'barang';

// Memanggil header AdminLTE
include 'adminlte/layout/header.php';

// ==================================
// PAGINATION
// ==================================

$jumlahDataPerhalaman = 1;

// ==================================
// FILTER DATA BERDASARKAN TANGGAL
// ==================================

$filter_error = '';
$tanggal_awal = '';
$tanggal_akhir = '';
$where = '';

if (isset($_GET['filter'])) {

    $tanggal_awal = $_GET['tanggal_awal'] ?? '';
    $tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

    // Validasi tanggal awal
    $awal_valid = preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_awal)
        && checkdate(
            (int) substr($tanggal_awal, 5, 2),
            (int) substr($tanggal_awal, 8, 2), 
            (int) substr($tanggal_awal, 0, 4)
        );

    // Validasi tanggal akhir
    $akhir_valid = preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_akhir)
        && checkdate(
            (int) substr($tanggal_akhir, 5, 2),
            (int) substr($tanggal_akhir, 8, 2), 
            (int) substr($tanggal_akhir, 0, 4)
        );

    if (!$awal_valid || !$akhir_valid) {

        $filter_error = 'Tanggal yang dimasukkan tidak valid.';

    } elseif ($tanggal_awal > $tanggal_akhir) {

        $filter_error = 'Tanggal awal tidak boleh melebihi tanggal akhir.';

    } else {

        $where = " WHERE tanggal >= '$tanggal_awal 00:00:00'
                    AND tanggal <= '$tanggal_akhir 23:59:59' ";
    }
}

// ==================================
// MENGHITUNG INFORMASI BARANG
// ==================================

if ($filter_error == '') {

    $semua_barang = select("
        SELECT * FROM barang
        $where
        ORDER BY id_barang DESC
    ");

} else {

    $semua_barang = [];

}

$total_barang = count($semua_barang);
$total_stok = 0;
$total_nilai = 0;
$total_barcode = 0;

foreach ($semua_barang as $barang) {

    $total_stok += $barang['jumlah'];

    $total_nilai += $barang['harga'] * $barang['jumlah'];

    if (!empty($barang['barcode'])) {
        $total_barcode++;
    }
}

// ==================================
// PAGINATION DATA BARANG
// ==================================

$jumlahData = $total_barang;

$jumlahHalaman = ceil(
    $jumlahData / $jumlahDataPerhalaman
);

$halamanAktif = isset($_GET['halaman'])
    ? (int) $_GET['halaman']
    : 1;

if ($halamanAktif < 1) {
    $halamanAktif = 1;
}

if ($jumlahHalaman > 0 && $halamanAktif > $jumlahHalaman) {
    $halamanAktif = $jumlahHalaman;
}

$awalData = ($jumlahDataPerhalaman * $halamanAktif) - $jumlahDataPerhalaman;

// Mengambil data dengan pagination
if ($jumlahData > 0 && $filter_error == '') {

    $data_barang = select("
        SELECT * FROM barang
        $where
        ORDER BY id_barang DESC
        LIMIT $awalData, $jumlahDataPerhalaman
    ");

} else {

    $data_barang = [];

}

?>

<!-- CSS TAMBAHAN -->
<style>
    .content-header h1 {
        font-size: 28px;
        font-weight: 600;
    }

    .small-box {
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.12);
    }

    .small-box .inner h3 {
        font-size: 30px;
        font-weight: bold;
    }

    .small-box .icon {
        top: 10px;
        right: 15px;
    }

    .small-box .icon i {
        font-size: 65px;
    }

    .card {
        border-radius: 5px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.10);
    }

    .card-header {
        background: #fff;
        padding: 15px 20px;
    }

    .card-title {
        font-size: 17px;
        font-weight: 500;
    }

    .card-body {
        padding: 20px;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: bold;
        vertical-align: middle;
        white-space: nowrap;
    }

    .table td {
        vertical-align: middle;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .barcode-img {
        max-width: 100px;
        height: auto;
    }
</style>

<?php if ($page == 'siswa') : ?>

    <?php
    $data_siswa = select("SELECT * FROM siswa ORDER BY id_siswa ASC");
    ?>

    <div class="content-wrapper">

        <!-- JUDUL HALAMAN -->
        <div class="content-header">
            <div class="container-fluid">
                <h1>
                    <i class="fas fa-users mr-2"></i>
                    Data Siswa
                </h1>
            </div>
        </div>

        <!-- TABEL DATA SISWA -->
        <section class="content">
            <div class="container-fluid">

                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">
                            Tabel Data Siswa
                        </h3>
                    </div>

                    <div class="card-body">

                        <!-- TOMBOL -->
                        <a href="tambah-siswa.php"
                           class="btn btn-primary mb-3">
                            <i class="fas fa-plus-circle"></i>
                            Tambah
                        </a>

                        <a href="download-excel-siswa.php"
                           class="btn btn-success mb-3">
                            <i class="fas fa-file-excel"></i>
                            Download Excel
                        </a>

                        <a href="download-pdf-siswa.php"
                           class="btn btn-danger mb-3">
                            <i class="fas fa-file-pdf"></i>
                            Download PDF
                        </a>

                        <!-- TABLE -->
                        <div class="table-responsive">

                            <table class="table table-bordered table-striped">

                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Prodi</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Telepon</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php $no = 1; ?>

                                    <?php if (count($data_siswa) > 0) : ?>

                                        <?php foreach ($data_siswa as $siswa) : ?>

                                            <tr>

                                                <td><?= $no++; ?></td>

                                                <td>
                                                    <?= htmlspecialchars($siswa['nama']); ?>
                                                </td>

                                                <td>
                                                    <?= htmlspecialchars($siswa['prodi']); ?>
                                                </td>

                                                <td>
                                                    <?= htmlspecialchars($siswa['jk']); ?>
                                                </td>

                                                <td>
                                                    <?= htmlspecialchars($siswa['telepon']); ?>
                                                </td>

                                                <td class="text-center">

                                                    <div class="d-flex justify-content-center">

                                                        <a href="detail-siswa.php?id_siswa=<?= $siswa['id_siswa']; ?>"
                                                           class="btn btn-secondary btn-sm mr-1">
                                                            <i class="fas fa-eye"></i>
                                                            Detail
                                                        </a>

                                                        <a href="ubah-siswa.php?id_siswa=<?= $siswa['id_siswa']; ?>"
                                                           class="btn btn-success btn-sm mr-1">
                                                            <i class="fas fa-edit"></i>
                                                            Ubah
                                                        </a>

                                                        <a href="hapus-siswa.php?id_siswa=<?= $siswa['id_siswa']; ?>"
                                                           class="btn btn-danger btn-sm"
                                                           onclick="return confirm('Yakin ingin menghapus data siswa ini?');">
                                                            <i class="fas fa-trash"></i>
                                                            Hapus
                                                        </a>

                                                    </div>

                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                    <?php else : ?>

                                        <tr>
                                            <td colspan="6" class="text-center">
                                                Data siswa tidak ditemukan.
                                            </td>
                                        </tr>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>
        </section>

    </div>

<?php elseif ($page == 'akun') : ?>

    <?php
    // Mengambil seluruh data akun
    $data_akun = select("SELECT * FROM akun ORDER BY id_akun ASC");
    ?>

    <div class="content-wrapper">

        <!-- JUDUL HALAMAN -->
        <div class="content-header">
            <div class="container-fluid">
                <h1>
                    <i class="fas fa-user-cog mr-2"></i>
                    Data Akun
                </h1>
            </div>
        </div>

        <!-- TABEL DATA AKUN -->
        <section class="content">
            <div class="container-fluid">

                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">
                            Tabel Data Akun
                        </h3>
                    </div>

                    <div class="card-body">

                        <!-- TOMBOL KELOLA AKUN -->
                        <a href="akun.php" class="btn btn-primary mb-3">
                            <i class="fas fa-user-plus"></i>
                            Kelola Akun
                        </a>

                        <div class="table-responsive">

                            <table class="table table-bordered table-striped">

                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Password</th>
                                        <th>Level</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php $no = 1; ?>

                                    <?php if (count($data_akun) > 0) : ?>

                                        <?php foreach ($data_akun as $akun) : ?>

                                            <tr>

                                                <td><?= $no++; ?></td>

                                                <td>
                                                    <?= htmlspecialchars($akun['nama']); ?>
                                                </td>

                                                <td>
                                                    <?= htmlspecialchars($akun['username']); ?>
                                                </td>

                                                <td>
                                                    <?= htmlspecialchars($akun['email']); ?>
                                                </td>

                                                <td>
                                                    Password Ter-enkripsi
                                                </td>

                                                <td>
                                                    <?php
                                                    if ($akun['level'] == 1) {
                                                        echo 'Admin';
                                                    } elseif ($akun['level'] == 2) {
                                                        echo 'Operator Barang';
                                                    } elseif ($akun['level'] == 3) {
                                                        echo 'Operator Mahasiswa';
                                                    } else {
                                                        echo 'Tidak Diketahui';
                                                    }
                                                    ?>
                                                </td>

                                                <td class="text-center">

                                                    <a href="akun.php"
                                                       class="btn btn-success btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                        Ubah
                                                    </a>

                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                    <?php else : ?>

                                        <tr>
                                            <td colspan="7" class="text-center">
                                                Data akun tidak ditemukan.
                                            </td>
                                        </tr>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>
        </section>

    </div>

<?php else : ?>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h1>
                    <i class="fas fa-list mr-2"></i>
                    Data Barang
                </h1>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <!-- KOTAK INFORMASI -->
                <div class="row">

                    <!-- TOTAL BARANG -->
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?= $total_barang; ?></h3>
                                <p>Total Jenis Barang</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                    </div>

                    <!-- TOTAL STOK -->
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?= $total_stok; ?></h3>
                                <p>Total Stok Barang</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                        </div>
                    </div>

                    <!-- TOTAL BARCODE -->
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?= $total_barcode; ?></h3>
                                <p>Total Barcode</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-barcode"></i>
                            </div>
                        </div>
                    </div>

                    <!-- TOTAL NILAI -->
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3 style="font-size: 22px;">
                                    Rp <?= number_format($total_nilai, 0, ',', '.'); ?>
                                </h3>
                                <p>Total Nilai Stok</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- TABEL DATA BARANG -->
                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">
                            Tabel Data Barang
                        </h3>
                    </div>

                    <div class="card-body">

                        <!-- TOMBOL TAMBAH -->
                        <a href="tambah-barang.php" class="btn btn-primary mb-3">
                            <i class="fas fa-plus-circle"></i>
                            Tambah
                        </a>

                        <!-- TOMBOL FILTER -->
                        <button type="button"
                                class="btn btn-success mb-3"
                                data-toggle="modal"
                                data-target="#modalFilter">
                            <i class="fas fa-search"></i>
                            Filter Data
                        </button>

                        <!-- TOMBOL RESET -->
                        <?php if (isset($_GET['filter'])) : ?>
                            <a href="index.php" class="btn btn-secondary mb-3">
                                <i class="fas fa-sync"></i>
                                Reset Filter
                            </a>
                        <?php endif; ?>

                        <!-- PESAN ERROR -->
                        <?php if ($filter_error != '') : ?>
                            <div class="alert alert-danger">
                                <?= htmlspecialchars($filter_error); ?>
                            </div>
                        <?php endif; ?>

                        <div class="table-responsive">

                            <table class="table table-bordered table-striped">

                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                        <th>Barcode</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php $no = $awalData + 1; ?>

                                    <?php if (count($data_barang) > 0) : ?>

                                        <?php foreach ($data_barang as $barang) : ?>

                                            <tr>

                                                <td><?= $no++; ?></td>

                                                <td>
                                                    <?= htmlspecialchars($barang['nama']); ?>
                                                </td>

                                                <td>
                                                    <?= $barang['jumlah']; ?>
                                                </td>

                                                <td>
                                                    Rp. <?= number_format($barang['harga'], 0, ',', '.'); ?>
                                                </td>

                                                <!-- BARCODE -->
                                                <td class="text-center">
                                                    <?php if (!empty($barang['barcode'])) : ?>

                                                        <img
                                                            class="barcode-img"
                                                            alt="barcode"
                                                            src="barcode.php?codetype=Code128&size=15&text=<?= urlencode($barang['barcode']); ?>&print=true"
                                                        >

                                                    <?php else : ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>

                                                <!-- TANGGAL -->
                                                <td>
                                                    <?= date(
                                                        "d/m/Y | H:i:s",
                                                        strtotime($barang['tanggal'])
                                                    ); ?>
                                                </td>

                                                <!-- AKSI -->
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center">

                                                        <a href="ubah-barang.php?id_barang=<?= $barang['id_barang']; ?>"
                                                           class="btn btn-success btn-sm mr-1">
                                                            <i class="fas fa-edit"></i>
                                                            Ubah
                                                        </a>

                                                        <a href="hapus-barang.php?id_barang=<?= $barang['id_barang']; ?>"
                                                           class="btn btn-danger btn-sm"
                                                           onclick="return confirm('Yakin Data Barang Akan Dihapus?');">
                                                            <i class="fas fa-trash"></i>
                                                            Hapus
                                                        </a>

                                                    </div>
                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                    <?php else : ?>

                                        <tr>
                                            <td colspan="7" class="text-center">
                                                Data barang tidak ditemukan.
                                            </td>
                                        </tr>

                                    <?php endif; ?>

                                </tbody>
                            </table>
                        </div>

                        <!-- PAGINATION -->
                        <div class="mt-2 d-flex justify-content-end">

                            <nav aria-label="Page navigation">
                                <ul class="pagination">

                                    <?php
                                    $parameterPagination = [
                                        'page' => $page
                                    ];

                                    if (isset($_GET['filter'])) {
                                        $parameterPagination['filter'] = 1;
                                        $parameterPagination['tanggal_awal'] = $tanggal_awal;
                                        $parameterPagination['tanggal_akhir'] = $tanggal_akhir;
                                    }
                                    ?>

                                    <!-- PREVIOUS -->
                                    <?php if ($halamanAktif > 1) : ?>

                                        <li class="page-item">
                                            <a class="page-link"
                                               href="index.php?<?= http_build_query(array_merge($parameterPagination, ['halaman' => $halamanAktif - 1])); ?>"
                                               aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>

                                    <?php endif; ?>

                                    <!-- NOMOR HALAMAN -->
                                    <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : ?>

                                        <?php if ($i == $halamanAktif) : ?>

                                            <li class="page-item active">
                                                <a class="page-link" href="#">
                                                    <?= $i; ?>
                                                </a>
                                            </li>

                                        <?php else : ?>

                                            <li class="page-item">
                                                <a class="page-link"
                                                   href="index.php?<?= http_build_query(array_merge($parameterPagination, ['halaman' => $i])); ?>">
                                                    <?= $i; ?>
                                                </a>
                                            </li>

                                        <?php endif; ?>

                                    <?php endfor; ?>

                                    <!-- NEXT -->
                                    <?php if ($halamanAktif < $jumlahHalaman) : ?>

                                        <li class="page-item">
                                            <a class="page-link"
                                               href="index.php?<?= http_build_query(array_merge($parameterPagination, ['halaman' => $halamanAktif + 1])); ?>"
                                               aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>

                                    <?php endif; ?>

                                </ul>
                            </nav>

                        </div>

                    </div>
                </div>

            </div>
        </section>

    </div>

<?php endif; ?>

<!-- Modal Filter -->
<div class="modal fade" id="modalFilter" tabindex="-1"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Header Modal -->
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fas fa-search"></i> Filter Data
                </h5>

                <button type="button" class="close"
                        data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Body Modal -->
            <div class="modal-body">
                <form action="index.php" method="get">

                    <div class="form-group">
                        <label for="tanggal_awal">Tanggal Awal</label>
                        <input type="date" name="tanggal_awal"
                               id="tanggal_awal" class="form-control"
                               value="<?= htmlspecialchars($tanggal_awal); ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_akhir">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir"
                               id="tanggal_akhir" class="form-control"
                               value="<?= htmlspecialchars($tanggal_akhir); ?>"
                               required>
                    </div>

                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" name="filter" value="1"
                                class="btn btn-success">
                            Submit
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<?php include 'adminlte/layout/footer.php'; ?>