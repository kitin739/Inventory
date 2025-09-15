<?php
include 'koneksi.php';

if(!isset($_GET['id'])) {
    echo "ID barang tidak ditemukan!";
    exit;
}

$id_barang = $_GET['id'];

// Hapus data peminjaman terkait
mysqli_query($conn, "DELETE FROM peminjaman WHERE id_barang='$id_barang'");

// Hapus data barang keluar terkait
mysqli_query($conn, "DELETE FROM barang_keluar WHERE id_barang='$id_barang'");

// Hapus data barang
$result = mysqli_query($conn, "DELETE FROM barang WHERE id_barang='$id_barang'");

if($result){
    header("Location: Data.php?pesan=hapus_berhasil");
} else {
    echo "Gagal menghapus barang: " . mysqli_error($conn);
}
?>
