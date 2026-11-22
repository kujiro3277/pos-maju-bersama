<?php
require_once 'config/database.php';

// Cek apakah user sudah login (jalur redirect disesuaikan karena index.php ada di root folder)
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

// Ambil statistik ringkas untuk ditampilkan di dashboard
$totalProduk    = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalTransaksi = $pdo->query("SELECT COUNT(*) FROM transactions")->fetchColumn();
$pendapatanHariIni = $pdo->query("SELECT COALESCE(SUM(total_bayar), 0) FROM transactions WHERE DATE(tanggal) = CURDATE()")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - POS Toko Maju Bersama</title>
    <link href="https://jsdelivr.net" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">POS Toko Maju Bersama</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="produk/index.php">Kelola Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="transaksi/index.php">Transaksi Penjualan</a>
                    </li>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="auth/register.php">Registrasi User</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <div class="d-flex align-items-center text-white">
                    <span class="me-3 small">
                        Halo, <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong> 
                        <span class="badge bg-light text-primary text-uppercase"><?= htmlspecialchars($_SESSION['role']) ?></span>
                    </span>
                    <a href="auth/logout.php" class="btn btn-outline-light btn-sm" onclick="return confirm('Yakin ingin keluar?')">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="container mt-4">
        <div class="alert alert-info shadow-sm">
            Selamat datang di sistem kasir <strong>Toko Maju Bersama Samarinda</strong>. Silakan pilih menu di bawah atau melalui navigasi di atas.
        </div>

        <!-- Kartu Statistik -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Total Jenis Produk</h6>
                        <h2 class="fw-bold mb-0"><?= number_format($totalProduk) ?> <small class="fs-6 fw-normal text-muted">item</small></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Total Transaksi</h6>
                        <h2 class="fw-bold mb-0"><?= number_format($totalTransaksi) ?> <small class="fs-6 fw-normal text-muted">struk</small></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Omzet Hari Ini</h6>
                        <h2 class="fw-bold text-success mb-0">Rp <?= number_format($pendapatanHariIni, 0, ',', '.') ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Akses Cepat -->
        <h5 class="mb-3">Menu Cepat</h5>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title">Kasir / Transaksi Baru</h5>
                            <p class="card-text text-muted small">Catat penjualan sembako & ATK, hitung kembalian otomatis, dan cetak detail transaksi.</p>
                        </div>
                        <a href="transaksi/buat.php" class="btn btn-success w-100 mt-2">+ Buat Transaksi</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title">Data Produk & Stok</h5>
                            <p class="card-text text-muted small">Tambah, ubah, hapus, dan cari barang berdasarkan nama atau kode barang.</p>
                        </div>
                        <a href="produk/index.php" class="btn btn-primary w-100 mt-2">Kelola Produk</a>
                    </div>
                </div>
            </div>
            <?php if ($_SESSION['role'] === 'admin'): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-warning">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title">Manajemen Pengguna</h5>
                            <p class="card-text text-muted small">Khusus Administrator: Daftarkan akun baru untuk petugas kasir atau admin.</p>
                        </div>
                        <a href="auth/register.php" class="btn btn-warning w-100 mt-2">Registrasi User Baru</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://jsdelivr.net"></script>
</body>
</html>
