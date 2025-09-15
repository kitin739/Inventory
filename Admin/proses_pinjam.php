<?php
session_start();
if($_SESSION['password']==''){
    header("location:login.php");
    exit;
}

include 'koneksi.php';
include 'send_wa.php'; // <-- fungsi sendWa ada di sini

if(isset($_POST['id_barang'])){
    $id_barang        = $_POST['id_barang'];
    $jumlah           = $_POST['jumlah'];
    $tanggal_pinjam   = $_POST['tanggal_pinjam'];
    $tanggal_kembali  = $_POST['tanggal_kembali'];
    $status           = 'dipinjam';
    $keterangan_pinjam= $_POST['keterangan_pinjam'] ?? NULL;
    $no_wa            = $_POST['no_wa'];

    // cek stok barang
    $cek = mysqli_query($conn, "SELECT * FROM barang WHERE id_barang='$id_barang'");
    $barang = mysqli_fetch_assoc($cek);

    if(!$barang){
        header("location:pinjam.php?pesan=barang_tidak_ada");
        exit;
    }

    if($barang['stok'] < $jumlah){
        header("location:pinjam.php?pesan=stok_kurang");
        exit;
    }

    // simpan ke peminjaman
    $insert = mysqli_query($conn, "INSERT INTO peminjaman 
        (id_barang, no_wa, jumlah_pinjam, tgl_pinjam, tgl_kembali, status, keterangan_pinjam) 
        VALUES ('$id_barang','$no_wa','$jumlah','$tanggal_pinjam','$tanggal_kembali','$status','$keterangan_pinjam')");

    // kurangi stok
    $update = mysqli_query($conn, "UPDATE barang SET stok=stok - $jumlah WHERE id_barang='$id_barang'");

    if($insert && $update){
        // kirim WA
        $pesan = "Halo! Peminjaman berhasil ✅\n\n"
                ."Barang : ".$barang['nama_barang']."\n"
                ."Jumlah : $jumlah\n"
                ."Tanggal Pinjam : $tanggal_pinjam\n"
                ."Tanggal Kembali : $tanggal_kembali\n\n"
                ."Harap dikembalikan tepat waktu 🙏";

        sendWa($no_wa, $pesan);

        header("location:pinjam.php?pesan=berhasil");
    } else {
        header("location:pinjam.php?pesan=gagal");
    }

} else {
    header("location:pinjam.php");
    exit;
}
?>
