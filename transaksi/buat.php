<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->beginTransaction();
    try {
        $no_faktur = 'INV-' . date('YmdHis');
        $user_id   = $_SESSION['user_id'];
        $total     = $_POST['total_bayar'];
        $bayar     = $_POST['uang_diterima'];
        $kembali   = $bayar - $total;

        $stmt = $pdo->prepare("INSERT INTO transactions (no_faktur, user_id, total_bayar, uang_diterima, kembalian) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$no_faktur, $user_id, $total, $bayar, $kembali]);
        $transaction_id = $pdo->lastInsertId();

        foreach ($_POST['items'] as $item) {
            $stmtDetail = $pdo->prepare("INSERT INTO transaction_details (transaction_id, product_id, qty, harga_satuan, subtotal) VALUES (?, ?, ?, ?, ?)");
            $stmtDetail->execute([$transaction_id, $item['product_id'], $item['qty'], $item['harga'], $item['subtotal']]);

            $stmtStok = $pdo->prepare("UPDATE products SET stok = stok - ? WHERE id = ?");
            $stmtStok->execute([$item['qty'], $item['product_id']]);
        }

        $pdo->commit();
        header("Location: detail.php?id=" . $transaction_id);
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Transaksi gagal: " . $e->getMessage();
    }
}
?>
