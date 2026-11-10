<?php
session_start();

// membatasi halaman login
if (!isset($_SESSION['login'])) {
    echo "<script>
            alert('Harap login terlebih dahulu');
            document.location.href = 'login.php';
          </script>";
    exit;
}

// membatasi halaman sesuai user login (disamakan jadi level 1 dan 2)
if ($_SESSION['level'] != 1 && $_SESSION['level'] != 2) {
    echo "<script>
            alert('Anda tidak punya hak akses');
            document.location.href = 'siswa.php';
          </script>";
    exit;
}

require __DIR__ . '/vendor/autoload.php';
require 'config/app.php';

use Spipu\Html2Pdf\Html2Pdf;

$data_siswa = select("SELECT * FROM siswa");

$content = '<style type="text/css">
    .gambar {
        width: 50px;
    }
</style>';

$content .= '
<page>
    <table border="1" align="center">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Program Studi</th>
            <th>Jenis Kelamin</th>
            <th>Alamat</th>
            <th>Telepon</th>
            <th>Email</th>
            <th>Foto</th>
        </tr>';

$no = 1;
foreach ($data_siswa as $siswa) {
    // Cek apakah data foto ada isinya atau tidak untuk mencegah error gambar kosong
    if (!empty($siswa['foto'])) {
        $foto = '<img src="assets/img/' . $siswa['foto'] . '" class="gambar">';
    } else {
        $foto = 'Tidak ada foto';
    }

    $content .= '
        <tr>
            <td>' . $no++ . '</td>
            <td>' . $siswa['nama'] . '</td>
            <td>' . $siswa['prodi'] . '</td>
            <td>' . $siswa['jk'] . '</td>
            <td>' . strip_tags($siswa['alamat']) . '</td>
            <td>' . $siswa['telepon'] . '</td>
            <td>' . $siswa['email'] . '</td>
            <td>' . $foto . '</td>
        </tr>';
}

$content .= '
    </table>
</page>';

$html2pdf = new Html2Pdf();
$html2pdf->writeHTML($content);
ob_start();
$html2pdf->output('Laporan-siswa.pdf');