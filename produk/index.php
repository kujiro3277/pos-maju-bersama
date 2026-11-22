<?php
require_once '../config/database.php';
cekLogin();

$keyword = $_GET['cari'] ?? '';
$sql = "SELECT p.*, c.nama_kategori 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        WHERE p.nama_barang LIKE :kw OR p.kode_barang LIKE :kw
        ORDER BY p.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['kw' => "%$keyword%"]);
$produk = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
