<?php 

session_start();

// membatasi halaman sebelum login
if (!isset($_SESSION['login'])) {
    echo "<script>
            alert('login dulu');
            document.location.href = 'login.php';
          </script>";
    exit;
}

// membatasi halaman sesuai user login
if ($_SESSION['level'] != 1 and $_SESSION['level'] != 2) {
    echo "<script>
            alert('Perhatian anda tidak punya hak akses');
            document.location.href = 'siswa.php';
          </script>";
    exit;
}

include 'layout/header.php';

// menampilkan data siswa
$data_siswa = select("SELECT * FROM siswa ORDER BY id_siswa ASC");

?>

<div class="container mt-5">
    <h1><i class="fas fa-users"></i> Data Siswa</h1>
    <hr>
    <a href="tambah-siswa.php" class="btn btn-primary mb-1"><i class="fas fa-plus-circle"></i> Tambah</a>
    <a href="download-excel-siswa.php" class="btn btn-success mb-1"><i class="fas fa-file-excel"></i> Download Excel</a>
    <a href="download-pdf-siswa.php" class="btn btn-danger mb-1"><i class="fas fa-file-pdf"></i> Download PDF</a>
    
    <table id="table" class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nama</th>
                <th scope="col">Prodi</th>
                <th scope="col">Jenis Kelamin</th>
                <th scope="col">Telepon</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>      
            <?php foreach ($data_siswa as $siswa) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $siswa['nama']; ?></td>
                    <td><?= $siswa['prodi']; ?></td>
                    <td><?= $siswa['jk']; ?></td>
                    <td><?= $siswa['telepon']; ?></td>
                    <td class="text-center" width="20%">
                        <div class="d-flex justify-content-center gap-1">
                            <a href="detail-siswa.php?id_siswa=<?= $siswa['id_siswa']; ?>" class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i> Detail</a>
                            <a href="ubah-siswa.php?id_siswa=<?= $siswa['id_siswa']; ?>" class="btn btn-success btn-sm"><i class="fas fa-edit"></i> Ubah</a>
                            <a href="hapus-siswa.php?id_siswa=<?= $siswa['id_siswa']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data siswa ini?');"><i class="fas fa-trash-alt"></i> Hapus</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'layout/footer.php'; ?>