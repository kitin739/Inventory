<?php
session_start();

if($_SESSION['password']=='')
{
    header("location:login.php");
}

include 'koneksi.php';
// Ambil id barang dari URL
$id_barang = $_GET['id'] ?? null;
if(!$id_barang){
    echo "Data tidak tersedia!";
    exit;
}


// Ambil id barang dari URL
$id_barang = $_GET['id'] ?? null;
if (!$id_barang) {
    echo "Data tidak tersedia!";
    exit;
}

// Ambil data barang
$barangQuery = mysqli_query($conn, "SELECT * FROM barang WHERE id_barang='$id_barang'");
$barang = mysqli_fetch_assoc($barangQuery);
if (!$barang) {
    echo "Barang tidak ditemukan!";
    exit;
}

// Ambil data peminjaman barang ini
$pinjamQuery = mysqli_query($conn, "
    SELECT p.id_peminjaman, p.jumlah_pinjam, p.tgl_pinjam, p.tgl_kembali, p.status
    FROM peminjaman p
    WHERE p.id_barang='$id_barang'
    ORDER BY p.tgl_pinjam DESC
");

// Ambil data barang keluar barang ini
$keluarQuery = mysqli_query($conn, "
    SELECT id_keluar, jumlah, tanggal_keluar, keterangan
    FROM barang_keluar
    WHERE id_barang='$id_barang'
    ORDER BY tanggal_keluar DESC
");

// Handle hapus peminjaman
if (isset($_GET['hapus_peminjaman'])) {
    $id_peminjaman = $_GET['hapus_peminjaman'];

    // Ambil jumlah pinjam untuk menambah stok
    $cek = mysqli_query($conn, "SELECT id_barang, jumlah_pinjam FROM peminjaman WHERE id_peminjaman='$id_peminjaman'");
    $row = mysqli_fetch_assoc($cek);

    if ($row) {
        // Tambahkan stok kembali
        mysqli_query($conn, "UPDATE barang SET stok = stok + " . $row['jumlah_pinjam'] . " WHERE id_barang='" . $row['id_barang'] . "'");
        // Hapus peminjaman
        mysqli_query($conn, "DELETE FROM peminjaman WHERE id_peminjaman='$id_peminjaman'");
    }

    header("Location: detail_barang.php?id=$id_barang");
    exit;
}

// Sidebar active
$currentPage = basename($_SERVER['PHP_SELF']);
    $nama = mysqli_query($conn, "select * from about");
    $profile = mysqli_fetch_array($nama);


?>


<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Admin Dashboard</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar Brand / Logo -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon rotate-n-0"></div>
        <!-- Logo -->
        <img src="penampung/logo418.png" alt="Logo" height="30">
        <!-- Nama Sekolah -->
        <div class="sidebar-brand-text mx-2" style="font-size:14px;"> SMKN 1 BINTAN </div>
      </a>

      <hr class="sidebar-divider my-0">

      <!-- Dashboard Menu Item -->
      <li class="nav-item <?= ($currentPage == 'index.php') ? 'active' : '' ?>">
        <a class="nav-link" href="index.php">
          <i class="bi bi-house-door"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <hr class="sidebar-divider my-0">

      <!-- Inventory Menu Item -->
      <li class="nav-item <?= ($currentPage == 'Data.php') ? 'active' : '' ?>">
        <a class="nav-link" href="Data.php">
          <i class="fas fa-fw fa-table"></i>
          <span>Inventory</span>
        </a>
      </li>

      <!-- Add Item Menu Item -->
      <li class="nav-item <?= ($currentPage == 'barang.php') ? 'active' : '' ?>">
        <a class="nav-link" href="barang.php">
          <i class="bi bi-bag-plus"></i>
          <span>Add Item</span>
        </a>
      </li>

      <!-- Out Item Menu Item -->
      <li class="nav-item <?= ($currentPage == 'bkeluar.php') ? 'active' : '' ?>">
        <a class="nav-link" href="bkeluar.php">
          <i class="bi bi-bag-dash"></i>
          <span>Out Item</span>
        </a>
      </li>

      <hr class="sidebar-divider my-0">

      <!-- Borrow Item Menu Item -->
      <li class="nav-item <?= ($currentPage == 'pinjam.php') ? 'active' : '' ?>">
        <a class="nav-link" href="pinjam.php">
          <i class="bi bi-dash-square"></i>
          <span>Borrow Item</span>
        </a>
      </li>

      <!-- Return Item Menu Item -->
      <li class="nav-item <?= ($currentPage == 'pengembalian.php') ? 'active' : '' ?>">
        <a class="nav-link" href="pengembalian.php">
          <i class="bi bi-plus-square"></i>
          <span>Return Item</span>
        </a>
      </li>

      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar / Navbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle Button for Mobile -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Navbar Items -->
          <ul class="navbar-nav ml-auto">

            <!-- Divider -->
            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- User Info -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <!-- Nama User -->
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $profile['nama'] ?></span>
                <!-- Foto Profil -->
                <img class="img-profile rounded-circle" src="penampung/<?php echo$profile['foto'] ?>" alt="Profile" width="100px" height="100px">
              </a>

              <!-- Dropdown User Menu -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="profile.php">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <a class="dropdown-item" href="setting.php?id=<?php echo $profile['id']; ?>">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings
                </a>
                <a class="dropdown-item" href="change.php?id=<?php echo $rrr['id']; ?>">
                  <i class="fas fa-ruler-horizontal fa-sm fa-fw mr-2 text-gray-400"></i>
                  Ganti Password
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
      
        <!-- End of Topbar -->

     <!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content" class="container mt-4">
       <!-- Isi konten di sini -->

  <h2>Detail Barang</h2>
<table class="table table-bordered">
    <tr>
        <td>Nama Barang</td>
        <td><?= $barang['nama_barang'] ?></td>
    </tr>
    <tr>
        <td>Kode Barang</td>
        <td><?= $barang['kode_barang'] ?></td>
    </tr>
    <tr>
        <td>Kategori</td>
        <td><?= $barang['kategori'] ?></td>
    </tr>
    <tr>
        <td>Stok</td>
        <td><?= $barang['stok'] ?></td>
    </tr>
    <tr>
        <td>Lokasi</td>
        <td><?= $barang['lokasi'] ?></td>
    </tr>
    <tr>
        <td>Kondisi</td>
        <td><?= $barang['kondisi'] ?></td>
    </tr>
</table>

<!-- ================== PEMINJAMAN ================== -->
<h3>Daftar Peminjaman Barang</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Jumlah Dipinjam</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $no = 1;
    while($p = mysqli_fetch_assoc($pinjamQuery)) { ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $p['jumlah_pinjam'] ?></td>
            <td><?= $p['tgl_pinjam'] ?></td>
            <td><?= $p['tgl_kembali'] ?></td>
            <td><?= ucfirst($p['status']) ?></td>
            <td>
                <a href="detail_barang.php?id=<?= $id_barang ?>&hapus_peminjaman=<?= $p['id_peminjaman'] ?>" 
                   class="btn btn-danger btn-sm" 
                   onclick="return confirm('Hapus peminjaman ini? Stok akan kembali.')">Hapus</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<!-- ================== BARANG KELUAR ================== -->
<h3>Daftar Barang Keluar</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Jumlah</th>
            <th>Tanggal Keluar</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $no = 1;
    while ($k = mysqli_fetch_assoc($keluarQuery)) { ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= $k['jumlah'] ?></td>
            <td><?= $k['tanggal_keluar'] ?></td>
            <td><?= $k['keterangan'] ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>


    <a href="Data.php" class="btn btn-info">Kembali</a>
</div>




  <!-- Logout Model-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Yakin Mau Keluar?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Jika Keluar Anda Harus Login Terlebih Dahulu !</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
          <a class="btn btn-primary" href="logout.php">Keluar</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>

</body>
</html>
