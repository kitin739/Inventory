<?php
session_start();

if($_SESSION['password']==''){
    header("location:login.php");
}

include 'koneksi.php';

// Sidebar active
$currentPage = basename($_SERVER['PHP_SELF']);
$nama = mysqli_query($conn, "SELECT * FROM about");
$profile = mysqli_fetch_array($nama);

// Function buat kode barang urut (BRG001, BRG002, dst)
function generateKodeBarang($conn) {
    $query = mysqli_query($conn, "SELECT MAX(kode_barang) as kodeTerbesar FROM barang");
    $data = mysqli_fetch_array($query);
    $kodeBarang = $data['kodeTerbesar'];

    if($kodeBarang) {
        // ambil angka dari kode terakhir (misalnya BRG005 -> 5)
        $urutan = (int) substr($kodeBarang, 3, 3);
        $urutan++;
    } else {
        $urutan = 1; // kalau belum ada data mulai dari 1
    }

    // format jadi 3 digit, misalnya 001, 002
    $kodeBaru = "BRG" . sprintf("%03s", $urutan);
    return $kodeBaru;
}

// Buat kode baru saat halaman dibuka
$kodeBarangBaru = generateKodeBarang($conn);
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
    
    <!-- Link ke halaman Profile, pakai ID user dari $profile -->
    <a class="dropdown-item" href="profile.php?id=<?php echo $profile['id']; ?>">
        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
        Profile
    </a>
    
    <!-- Link ke halaman Settings, pakai ID user yang sama -->
    <a class="dropdown-item" href="setting.php?id=<?php echo $profile['id']; ?>">
        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
        Settings
    </a>
    
    <!-- Link ke halaman Ganti Password, pakai ID user yang sama -->
    <a class="dropdown-item" href="change.php?id=<?php echo $profile['id']; ?>">
        <i class="fas fa-ruler-horizontal fa-sm fa-fw mr-2 text-gray-400"></i>
        Ganti Password
    </a>
    
    <!-- Garis pemisah antar menu -->
    <div class="dropdown-divider"></div>
    
    <!-- Link Logout, memunculkan modal konfirmasi -->
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

<h3>Form Tambah Barang</h3>
          
       <div class="card shadow">
  <div class="card-body">
    <form method="post" action="">
      <div class="form-group">
        <label>Kode Barang</label>
        <input type="text" class="form-control" name="kode_barang" 
               value="<?= $kodeBarangBaru; ?>" readonly>
      </div>

      <div class="form-group">
        <label>Nama Barang</label>
        <input type="text" class="form-control" name="nama_barang" required>
      </div>

      <div class="form-group">
        <label>Lokasi</label>
        <input type="text" class="form-control" name="lokasi" required>
      </div>

      <div class="form-group">
        <label>Stok</label>
        <input type="number" class="form-control" name="stok" required>
      </div>

      <div class="form-group">
        <label>Kategori</label>
        <select class="form-control" name="kategori" required>
          <option value="">-- Pilih Kategori --</option>
          <option value="Hardware">Perangkat Keras (Hardware)</option>
          <option value="Jaringan">Perangkat Jaringan</option>
          <option value="Lainnya">Perangkat Lainnya</option>
        </select>
      </div>

      <div class="form-group">
        <label>Kondisi</label>
        <select class="form-control" name="kondisi" required>
          <option value="">-- Pilih Kondisi --</option>
          <option value="Baik">Baik</option>
          <option value="Rusak">Rusak</option>
        </select>
      </div>

      <button class="btn btn-primary" type="submit" name="simpan">Simpan</button>
    </form>
  </div>
</div>


          <?php
          if(isset($_POST['simpan'])){
    $kode   = htmlspecialchars($_POST['kode_barang']);
    $nama   = htmlspecialchars($_POST['nama_barang']);
    $kat    = htmlspecialchars($_POST['kategori']);
    $stok   = htmlspecialchars($_POST['stok']);
    $lokasi = htmlspecialchars($_POST['lokasi']);
    $kondisi= htmlspecialchars($_POST['kondisi']);

    $insert = mysqli_query($conn, "INSERT INTO barang 
        (kode_barang, nama_barang, kategori, stok, lokasi, kondisi) 
        VALUES ('$kode','$nama','$kat','$stok','$lokasi','$kondisi')");

    if($insert){
        echo "<div class='alert alert-success mt-3'>Data berhasil disimpan!</div>";
    }else{
        echo "<div class='alert alert-danger mt-3'>Gagal simpan data!</div>";
    }
}
?>
        </form>
        
      </div>
    </div>
  </div>

    

          <!-- End of Content Wrapper -->


  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
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
