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

$title = 'Detail Siswa';

include 'layout/header.php';

// mengambil id siswa dari url
$id_siswa = (int)$_GET['id_siswa'];

// menampilkan data siswa
$siswa = select("SELECT * FROM siswa WHERE id_siswa = $id_siswa")[0];

?>

<div class="container mt-5">
  <h1>Data <?= $siswa['nama']; ?></h1>
  <hr>

  <table class="table table-striped table-bordered mt-3">
    <tr>
      <td>Nama</td>
      <td><?= $siswa['nama']; ?></td>
    </tr>
    <tr>
      <td>Program Studi</td>
      <td><?= $siswa['prodi']; ?></td>
    </tr>
    <tr>
      <td>Jenis Kelamin</td>
      <td><?= $siswa['jk']; ?></td>
    </tr>
    <tr>
      <td>Telepon</td>
      <td><?= $siswa['telepon']; ?></td>
    </tr>
    <tr>
      <td>Alamat</td>
      <td>
        <div class="content-alamat">
            <?= $siswa['alamat']; ?>
        </div>
      </td>
    </tr>
    <tr>
      <td>Email</td>
      <td><?= $siswa['email']; ?></td>
    </tr>
    <tr>
      <td width="30%">Foto</td>
      <td>
        <img src="assets/img/<?= $siswa['foto']; ?>" alt="<?= $siswa['nama']; ?>" width="30%">
      </td>
    </tr>
  </table>

  <a href="siswa.php" class="btn btn-secondary btn-sm" style="float: right;">Kembali</a>
</div>

<?php include 'layout/footer.php'; ?>