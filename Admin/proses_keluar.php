<?php
include 'koneksi.php';

if(isset($_POST['id_barang'], $_POST['jumlah'], $_POST['tanggal_keluar'])){
    $id_barang = $_POST['id_barang'];
    $jumlah = (int) $_POST['jumlah'];
    $tanggal = $_POST['tanggal_keluar'];
    $keterangan = $_POST['keterangan'];

    // Cek apakah barang ada
    $cek = mysqli_query($conn, "SELECT * FROM barang WHERE id_barang='$id_barang'");
    if(mysqli_num_rows($cek) > 0){
        $data = mysqli_fetch_assoc($cek);
        $stok = (int) $data['stok'];

        if($stok >= $jumlah){
            // Kurangi stok
            $sisa = $stok - $jumlah;
            mysqli_query($conn, "UPDATE barang SET stok='$sisa' WHERE id_barang='$id_barang'");

            // Simpan ke tabel barang_keluar
            mysqli_query($conn, "INSERT INTO barang_keluar (id_barang, jumlah, tanggal_keluar, keterangan) 
                                 VALUES ('$id_barang','$jumlah','$tanggal','$keterangan')");

            header("Location: bkeluar.php?pesan=berhasil");
        } else {
            header("Location: bkeluar.php?pesan=stok_kurang");
        }
    } else {
        header("Location: bkeluar.php?pesan=barang_tidak_ada");
    }
} else {
    header("Location: bkeluar.php?pesan=gagal");
}
?>
