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

$title = 'Ubah Siswa';

include 'layout/header.php';

// check apakah tombol ubah ditekan
if (isset($_POST['ubah'])) {
    if (update_siswa($_POST) > 0) {
        echo "<script>
                alert('Data Siswa Berhasil Diubah');
                document.location.href = 'siswa.php';
              </script>";
    } else {
        echo "<script>
                alert('Data Siswa Gagal Diubah');
                document.location.href = 'siswa.php';
              </script>";
    }
}

// ambil id siswa dari url
$id_siswa = (int)$_GET['id_siswa'];

// query ambil data siswa
$siswa = select("SELECT * FROM siswa WHERE id_siswa = $id_siswa")[0];

?>

<div class="container mt-5">
    <h1>Ubah Siswa</h1>
    <hr>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id_siswa" value="<?= $siswa['id_siswa']; ?>">
        <input type="hidden" name="fotoLama" value="<?= $siswa['foto']; ?>">

        <!-- Input Nama Siswa -->
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Siswa</label>
            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama siswa..." required value="<?= $siswa['nama']; ?>">
        </div>

        <div class="row">
            <div class="mb-3 col-6">
                <label for="prodi" class="form-label">Program Studi</label>
                <select name="prodi" id="prodi" class="form-control" required>
                    <?php $prodi = $siswa['prodi']; ?>
                    <option value="Program Jurusan" <?= $prodi == 'Program Jurusan' ? 'selected' : null ?>>Program Jurusan</option>
                    <option value="Rekayasa Perangkat Lunak" <?= $prodi == 'Rekayasa Perangkat Lunak' ? 'selected' : null ?>>Rekayasa Perangkat Lunak</option>
                    <option value="Teknik Kendaraan Ringan Otomotif" <?= $prodi == 'Teknik Kendaraan Ringan Otomotif' ? 'selected' : null ?>>Teknik Kendaraan Ringan Otomotif</option>
                    <option value="Teknik Bisnis Sepeda Motor" <?= $prodi == 'Teknik Bisnis Sepeda Motor' ? 'selected' : null ?>>Teknik Bisnis Sepeda Motor</option>
                    <option value="Teknik Alat Berat" <?= $prodi == 'Teknik Alat Berat' ? 'selected' : null ?>>Teknik Alat Berat</option>
                    <option value="Teknik Logistik" <?= $prodi == 'Teknik Logistik' ? 'selected' : null ?>>Teknik Logistik</option>
                    <option value="Agribisnis Tanaman Pangan" <?= $prodi == 'Agribisnis Tanaman Pangan' ? 'selected' : null ?>>Agribisnis Tanaman Pangan</option>
                    <option value="Agribisnis Pengolahan Hasil Pertanian" <?= $prodi == 'Agribisnis Pengolahan Hasil Pertanian' ? 'selected' : null ?>>Agribisnis Pengolahan Hasil Pertanian</option>
                </select>
            </div>

            <div class="mb-3 col-6">
                <label for="jk" class="form-label">Jenis Kelamin</label>
                <select name="jk" id="jk" class="form-control" required>
                    <?php $jk = $siswa['jk']; ?>
                    <option value="laki-laki" <?= $jk == 'laki-laki' ? 'selected' : null ?>>laki-laki</option>
                    <option value="perempuan" <?= $jk == 'perempuan' ? 'selected' : null ?>>perempuan</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label for="telepon" class="form-label">Telepon</label>
            <input type="number" class="form-control" id="telepon" name="telepon" placeholder="Telepon siswa..." required value="<?= $siswa['telepon']; ?>">
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Alamat siswa..."><?= $siswa['alamat']; ?></textarea>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Email siswa..." required value="<?= $siswa['email']; ?>">
        </div>

        <div class="mb-3">
            <label for="foto" class="form-label">Foto</label>
            <input type="file" class="form-control" id="foto" name="foto" placeholder="Foto..." onchange="previewImg()">
            
            <img src="assets/img/<?= $siswa['foto']; ?>" alt="" class="img-thumbnail img-preview mt-2" width="100px">
        </div>

        <button type="submit" name="ubah" class="btn btn-primary" style="float: right;">Ubah</button>
    </form>
</div>

<!-- preview image -->
<script>
    function previewImg() {
        const foto = document.querySelector('#foto');
        const imgPreview = document.querySelector('.img-preview');

        const fileFoto = new FileReader();
        fileFoto.readAsDataURL(foto.files[0]);

        fileFoto.onload = function(e) {
            imgPreview.src = e.target.result;
        }
    }
</script>

<?php include 'layout/footer.php'; ?>