<?php
session_start();

$host = 'localhost';
$db   = 'pos_maju_bersama';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

function cekLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../auth/login.php");
        exit;
    }
}

function cekAdmin() {
    cekLogin();
    if ($_SESSION['role'] !== 'admin') {
        die("Akses ditolak! Halaman ini hanya untuk Administrator.");
    }
}
?>
