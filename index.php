<?php
include 'config.php';
$result = mysqli_query($mysqli, "SELECT * FROM tb_pembayaran ORDER BY tanggal DESC");
$dataBayar = mysqli_query($mysqli, "SELECT metode_pembayaran, SUM(total) AS total_bayar FROM `tb_pembayaran` GROUP BY metode_pembayaran ORDER BY metode_pembayaran ASC");
$dataBiaya = mysqli_query($mysqli, "SELECT metode_pembayaran, SUM(biaya_admin) AS biaya FROM `tb_pembayaran` GROUP BY metode_pembayaran ORDER BY metode_pembayaran ASC");
//array total bayar
$bayarTemp = array();
while ($arrBayar = mysqli_fetch_array($dataBayar)) {
    $bayarTemp[] = $arrBayar;
}
$bayar = array();
foreach ($bayarTemp as $tb) {
    $bayar[] = ['y' => $tb['total_bayar'], 'label' => $tb['metode_pembayaran']];
}
//array biaya admin
$biayaTemp = array();
while ($arrBiaya = mysqli_fetch_array($dataBiaya)) {
    $biayaTemp[] = $arrBiaya;
}
$biaya = array();
foreach ($biayaTemp as $ba) {
    $biaya[] = ['y' => $ba['biaya'], 'label' => $ba['metode_pembayaran']];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT. Auto Sport</title>
    <!-- CSS only -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <!-- Load CSS file for DataTables -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css" integrity="sha512-1k7mWiTNoyx2XtmI96o+hdjP8nn0f3Z2N4oF/9ZZRgijyV4omsKOXEnqL1gKQNPy2MTSP9rIEWGcH/CInulptA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Load jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!-- Load DataTables -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js" integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Load Chart -->
    <script>
        window.onload = function() {
            var chartBayar = new CanvasJS.Chart("grafik-bayar", {
                title: {
                    text: "Total Bayar"
                },
                axisY: {
                    title: "Jumlah dalam Rupiah"
                },
                data: [{
                    type: "line",
                    dataPoints: <?php echo json_encode($bayar, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chartBayar.render();

            var chartBiaya = new CanvasJS.Chart("grafik-biaya", {
                title: {
                    text: "Total Biaya Admin"
                },
                axisY: {
                    title: "Jumlah dalam Rupiah"
                },
                data: [{
                    type: "line",
                    dataPoints: <?php echo json_encode($biaya, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chartBiaya.render();
        }
    </script>
</head>

<body>
    <nav class="navbar navbar-dark navbar-expand-lg bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">PT. Auto Sport</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h2>Dashboard</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <center>
                            <h3>Grafik Total Bayar per Metode Bayar</h3>
                        </center>
                    </div>
                    <div class="card-body">
                        <div id="grafik-bayar" style="height: 370px; width: 100%;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <center>
                            <h3>Grafik Biaya Admin per Metode Bayar</h3>
                        </center>
                    </div>
                    <div class="card-body">
                        <div id="grafik-biaya" style="height: 370px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="import.php" class="btn btn-primary">Import from Excel </a>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Data Pembayaran</h5>
                        <table id="tabel-pembayaran" class="display" style="width: 100%;">
                            <thead>
                                <tr>
                                    <td>No Kwitansi</td>
                                    <td>Tanggal</td>
                                    <td>Tanggal Alokasi</td>
                                    <td>Nama Customer</td>
                                    <td>Metode Pembayaran</td>
                                    <td>Total</td>
                                    <td>Biaya Admin</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($data = mysqli_fetch_array($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $data['no_kwitansi'] . "</td>";
                                    echo "<td>" . $data['tanggal'] . "</td>";
                                    echo "<td>" . $data['tanggal_alokasi'] . "</td>";
                                    echo "<td>" . $data['customer'] . "</td>";
                                    echo "<td>" . $data['metode_pembayaran'] . "</td>";
                                    echo "<td>" . $data['total'] . "</td>";
                                    echo "<td>" . $data['biaya_admin'] . "</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <footer class="main-footer">
            <strong>Copyright © 2022 Vivi Agustina Ratnasari.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>
    </div>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabel-pembayaran').DataTable();
        });
    </script>
</body>

</html>