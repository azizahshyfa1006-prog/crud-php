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

$title = 'Tambah Siswa';

include 'layout/header.php';

if (isset($_POST['tambah'])) {
    if (create_siswa($_POST) > 0) {
        echo "<script>
                alert('Data siswa Berhasil Ditambahkan');
                window.location.href = 'siswa.php';
              </script>";
    } else {
        echo "<script>
                alert('Data siswa Gagal Ditambahkan');
                window.location.href = 'siswa.php';
              </script>";
    }
}

?>

<div class="container mt-5">
    <h1>Tambah siswa</h1>
    <hr>

    <form action="" method="post" enctype="multipart/form-data">

        <!-- Input Nama Siswa -->
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Siswa</label>
            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama siswa..." required>
        </div>

        <div class="row">
            <div class="mb-3 col-6">
                <label for="prodi" class="form-label">Program Studi</label>
                <select name="prodi" id="prodi" class="form-control" required>
                    <option value="">-- pilih program studi --</option>
                    <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                    <option value="Teknik Kendaraan Ringan Otomotif">Teknik Kendaraan Ringan Otomotif</option>
                    <option value="Teknik Bisnis Sepeda Motor">Teknik Bisnis Sepeda Motor</option>
                    <option value="Teknik Alat Berat">Teknik Alat Berat</option>
                    <option value="Teknik Logistik">Teknik Logistik</option>
                    <option value="Agribisnis Tanaman Pangan">Agribisnis Tanaman Pangan</option>
                    <option value="Agribisnis Pengolahan Hasil Pertanian">Agribisnis Pengolahan Hasil Pertanian</option>
                </select>
            </div>

            <div class="mb-3 col-6">
                <label for="jk" class="form-label">Jenis Kelamin</label>
                <select name="jk" id="jk" class="form-control" required>
                    <option value="">-- pilih jenis kelamin --</option>
                    <option value="laki-laki">laki-laki</option>
                    <option value="perempuan">perempuan</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label for="telepon" class="form-label">Telepon</label>
            <input type="number" class="form-control" id="telepon" name="telepon" placeholder="Telepon siswa..." required>
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Alamat siswa..."></textarea>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Email siswa..." required>
        </div>

        <div class="mb-3">
            <label for="foto" class="form-label">Foto</label>
            <input type="file" class="form-control" id="foto" name="foto" placeholder="Foto..." onchange="previewImg()">

            <img src="" alt="" class="img-thumbnail img-preview mt-2" width="100px">
        </div>

        <button type="submit" name="tambah" class="btn btn-primary" style="float: right;">+ Tambah</button>
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

<!-- CKEditor dan CKFinder -->
<script>
    CKEDITOR.replace('alamat', {
        filebrowserBrowseUrl: 'assets/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl: 'assets/ckfinder/ckfinder.html?type=Images'
    });
</script>

<?php include_once 'layout/footer.php'; ?>