<?php
include 'config.php';

require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

if (isset($_POST['submit'])) {
    $nama_file = $_FILES['excelFile']['name'];
    $nama_sementara = $_FILES['excelFile']['tmp_name'];
    $directory = 'tmp/';
    $upload = move_uploaded_file($nama_sementara, $directory . $nama_file);
    $path = $directory . $nama_file;
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $spreadsheet = $reader->load($path); // Load file yang tadi diupload ke folder tmp
    $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    $numrow = 1;
    foreach ($sheet as $row) {
        $no_kwitansi = $row['A'];
        $tanggal = $row['B'];
        $tanggal_alokasi = $row['C'];
        $customer = $row['D'];
        $metode = $row['E'];
        $total = $row['F'];
        $biaya = $row['G'];

        if ($no_kwitansi == "" && $tanggal == "" && $tanggal_alokasi == "" && $customer == "" && $metode == "" && $total == "" && $biaya == "")
            continue;

        if ($numrow > 1) {
            $query = "INSERT INTO tb_pembayaran VALUES('" . $no_kwitansi . "','" . $tanggal . "','" . $tanggal_alokasi . "','" . $customer . "','" . $metode . "','" . $total . "','" . $biaya . "')";
            mysqli_query($mysqli, $query);
        }

        $numrow++;
    }

    //unlink($path);
}
header('location: index.php');
