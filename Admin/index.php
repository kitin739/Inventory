  <?php
  session_start();

  if($_SESSION['password']=='')
  {
      header("location:login.php");
  }

  include 'koneksi.php';


  // Total stock barang
  $totalBarangQuery = mysqli_query($conn, "SELECT SUM(stok) AS total FROM barang");
  $totalBarang = mysqli_fetch_assoc($totalBarangQuery);

  // Barang sedang dipinjam
  // Barang sedang dipinjam
  $pinjamQuery = mysqli_query($conn, "SELECT SUM(jumlah_pinjam) AS total_dipinjam FROM peminjaman WHERE status='dipinjam'");
  $barangDipinjam = mysqli_fetch_assoc($pinjamQuery);
  $total_dipinjam = $barangDipinjam['total_dipinjam'] ?? 0;


  $barangDipinjam = mysqli_fetch_assoc($pinjamQuery);

  // Jumlah jenis barang
  $jenisQuery = mysqli_query($conn, "SELECT COUNT(*) AS jenis FROM barang");
  $jenisBarang = mysqli_fetch_assoc($jenisQuery);

  // Barang berdasarkan kondisi
  $rusakQuery = mysqli_query($conn, "SELECT COUNT(*) AS rusak FROM barang WHERE kondisi='Rusak'");
  $rusak = mysqli_fetch_assoc($rusakQuery);

  $baikQuery = mysqli_query($conn, "SELECT COUNT(*) AS baik FROM barang WHERE kondisi='Baik'");
  $baik = mysqli_fetch_assoc($baikQuery);

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
      <div id="content-wrapper" class="d-flex flex-column">
      <div id="content" class="container mt-4">

          <!-- Page Content -->
          <div class="container-fluid">


        
          <!-- Begin Page Content -->
          <div class="container-fluid">


            <!-- Content Row -->
            <div class="row">

          <!-- Welcome Card -->
  <div class="col-12 mb-4">
    <div class="card shadow-lg h-100 py-4" style="background: linear-gradient(135deg, #4e73df, #4d1cc8ff); color: white; border-radius: 0.75rem; box-shadow: 0 8px 20px rgba(0,0,0,0.3);">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <h3 class="font-weight-bold">Selamat Datang, <?= $profile['nama'] ?>!</h3>
          <p class="mb-0">Aplikasi berbasis web E-Inventory SMKN 1 Bintan Utara untuk memudahkan manajemen barang.</p>
        </div>
        <div>
          <img src="penampung/<?= $profile['foto'] ?>" alt="Profile" class="rounded-circle" style="width:4rem; height:4rem; object-fit:cover; border: 2z vb SDFGHJKL;/px solid white;">
        </div>
      </div>
    </div>
  </div>
  

      <!-- Total Barang -->
<div class="col-xl-6 col-lg-6 col-md-6 mb-4">
  <div class="card border-left-info shadow h-100 bg-info text-white" style="min-height: 150px;">
    <div class="card-body d-flex align-items-center justify-content-between px-4">
      <div class="text-center">
        <div class="text-xs font-weight-bold text-uppercase mb-2">Total Barang</div>
        <div class="h2 font-weight-bold"><?= number_format($totalBarang['total']) ?> Barang</div>
      </div>
      <div>
        <svg width="6em" height="6em" viewBox="0 0 16 16" class="bi bi-box-seam" fill="white" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
        </svg>
      </div>
    </div>
  </div>
</div>


    <!-- Barang Dipinjam -->
<div class="col-xl-6 col-lg-6 col-md-6 mb-4">
  <div class="card shadow h-100 bg-warning text-white" style="min-height: 150px;">
    <div class="card-body d-flex align-items-center justify-content-between px-4">
      <div class="text-center">
        <div class="text-xs font-weight-bold text-uppercase mb-2">Barang Dipinjam</div>
        <div class="h2 font-weight-bold"><?= number_format($total_dipinjam) ?> Barang</div>
      </div>
      <div>
        <svg width="6em" height="6em" viewBox="0 0 16 16" class="bi bi-handbag" fill="white" xmlns="http://www.w3.org/2000/svg">
          <path d="M8 1a3 3 0 0 0-3 3v1H3a1 1 0 0 0-1 1v1h12V6a1 1 0 0 0-1-1h-2V4a3 3 0 0 0-3-3zM5 4a3 3 0 0 1 6 0v1H5V4z"/>
          <path d="M2 8v5a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V8H2z"/>
        </svg>
      </div>
    </div>
  </div>
</div>

<!-- Jenis Barang -->
<div class="col-xl-6 col-lg-6 col-md-6 mb-4">
  <div class="card shadow h-100 bg-success text-white" style="min-height: 150px;">
    <div class="card-body d-flex align-items-center justify-content-between px-4">
      <div class="text-center">
        <div class="text-xs font-weight-bold text-uppercase mb-2">Jenis Barang</div>
        <div class="h2 font-weight-bold"><?= number_format($jenisBarang['jenis']) ?> Jenis</div>
      </div>
      <div>
        <svg width="6em" height="6em" viewBox="0 0 16 16" class="bi bi-grid" fill="white" xmlns="http://www.w3.org/2000/svg">
          <path d="M2 2h3v3H2V2zm4 0h3v3H6V2zm4 0h3v3h-3V2zM2 6h3v3H2V6zm4 0h3v3H6V6zm4 0h3v3h-3V6zM2 10h3v3H2v-3zm4 0h3v3H6v-3zm4 0h3v3h-3v-3z"/>
        </svg>
      </div>
    </div>
  </div>
</div>

<!-- Barang Rusak -->
<div class="col-xl-6 col-lg-6 col-md-6 mb-4">
  <div class="card shadow h-100 bg-danger text-white" style="min-height: 150px;">
    <div class="card-body d-flex align-items-center justify-content-between px-4">
      <div class="text-center">
        <div class="text-xs font-weight-bold text-uppercase mb-2">Barang Rusak</div>
        <div class="h2 font-weight-bold"><?= number_format($rusak['rusak']) ?> Barang</div>
      </div>
      <div>
        <svg width="6em" height="6em" viewBox="0 0 16 16" class="bi bi-exclamation-triangle" fill="white" xmlns="http://www.w3.org/2000/svg">
          <path d="M7.938 2.016a.13.13 0 0 1 .125 0l6.857 11.857c.05.086.002.196-.093.196H1.193a.125.125 0 0 1-.093-.196L7.938 2.016zM8 4.5a.5.5 0 0 0-.5.5v3.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5zm0 7a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5z"/>
        </svg>
      </div>
    </div>
  </div>
</div>



</div>


    <!-- Content Row -->




        <!-- Footer -->
        
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
