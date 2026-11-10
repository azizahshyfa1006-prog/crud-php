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

// membatasi halaman sesuai user login
if ($_SESSION['level'] != 1 and $_SESSION['level'] != 2) {
    echo "<script>
            alert('Anda tidak punya hak akses');
            document.location.href = 'siswa.php';
          </script>";
    exit;
}

require 'config/app.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();

// Header tabel Excel
$activeWorksheet->setCellValue('A2', 'No');
$activeWorksheet->setCellValue('B2', 'Nama');
$activeWorksheet->setCellValue('C2', 'Program Studi');
$activeWorksheet->setCellValue('D2', 'Jenis Kelamin');
$activeWorksheet->setCellValue('E2', 'Alamat');
$activeWorksheet->setCellValue('F2', 'Telepon');
$activeWorksheet->setCellValue('G2', 'Email');
$activeWorksheet->setCellValue('H2', 'Foto');

$data_siswa = select("SELECT * FROM siswa");

$no = 1;
$start = 3;

foreach ($data_siswa as $siswa) {
    $activeWorksheet->setCellValue('A' . $start, $no++)->getColumnDimension('A')->setAutoSize(true);
    $activeWorksheet->setCellValue('B' . $start, $siswa['nama'])->getColumnDimension('B')->setAutoSize(true);
    $activeWorksheet->setCellValue('C' . $start, $siswa['prodi'])->getColumnDimension('C')->setAutoSize(true);
    $activeWorksheet->setCellValue('D' . $start, $siswa['jk'])->getColumnDimension('D')->setAutoSize(true);
    $activeWorksheet->setCellValue('E' . $start, strip_tags($siswa['alamat']))->getColumnDimension('E')->setAutoSize(true);
    $activeWorksheet->setCellValue('F' . $start, $siswa['telepon'])->getColumnDimension('F')->setAutoSize(true);
    $activeWorksheet->setCellValue('G' . $start, $siswa['email'])->getColumnDimension('G')->setAutoSize(true);
    $activeWorksheet->setCellValue('H' . $start, 'http://localhost/crud-php/assets/img/' . $siswa['foto'])->getColumnDimension('H')->setAutoSize(true);

    $start++;
}

// Border excel
$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
];

$border = $start - 1;

$activeWorksheet->getStyle('A2:H' . $border)->applyFromArray($styleArray);

$writer = new Xlsx($spreadsheet);
$writer->save('Laporan Data siswa.xlsx');

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan Data siswa.xlsx"');
readfile('Laporan Data siswa.xlsx');
unlink('Laporan Data siswa.xlsx');
exit;