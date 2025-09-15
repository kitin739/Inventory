<?php
session_start();
if($_SESSION['password']=='') {
    header("location:login.php");
    exit();
}

include 'koneksi.php';

// Header Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Data_Barang.xls");
header("Cache-Control: max-age=0");

// Ambil semua data barang
$data = mysqli_query($conn, "SELECT * FROM barang");

echo "<table border='1'>";
echo "<tr>
<th>No</th>
<th>Kode Barang</th>
<th>Nama</th>
<th>Kategori</th>
<th>Stok</th>
<th>Lokasi</th>
<th>Kondisi</th>
</tr>";

$no = 1;
while($d = mysqli_fetch_array($data)) {
    echo "<tr>
        <td>{$no}</td>
        <td>{$d['kode_barang']}</td>
        <td>{$d['nama_barang']}</td>
        <td>{$d['kategori']}</td>
        <td>{$d['stok']}</td>
        <td>{$d['lokasi']}</td>
        <td>{$d['kondisi']}</td>
    </tr>";
    $no++;
}
echo "</table>";
?>
