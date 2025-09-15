<?php
session_start();
if($_SESSION['password']==''){
    header("location:login.php");
}

include 'koneksi.php';

if(isset($_POST['id_peminjaman'])){
    $id_peminjaman = $_POST['id_peminjaman'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $keterangan = $_POST['keterangan_kembali'];

    // ambil data peminjaman
    $peminjaman = mysqli_query($conn, "SELECT * FROM peminjaman WHERE id_peminjaman='$id_peminjaman'");
    $data = mysqli_fetch_assoc($peminjaman);
    $id_barang = $data['id_barang'];
    $jumlah_pinjam = $data['jumlah_pinjam'];

    // update status peminjaman jadi dikembalikan
    $updatePeminjaman = mysqli_query($conn, "UPDATE peminjaman SET status='dikembalikan', tgl_kembali='$tanggal_kembali', keterangan_kembali='$keterangan' WHERE id_peminjaman='$id_peminjaman'");

    // tambahkan stok barang
    $updateBarang = mysqli_query($conn, "UPDATE barang SET stok=stok + $jumlah_pinjam WHERE id_barang='$id_barang'");

    if($updatePeminjaman && $updateBarang){
        header("location:pengembalian.php?pesan=berhasil");
    } else {
        header("location:pengembalian.php?pesan=gagal");
    }
} else {
    header("location:pengembalian.php");
}
?>
