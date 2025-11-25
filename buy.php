<?php
// Ambil data anggrek dari GET parameter
$name = $_GET['name'];
$price = $_GET['price'];
$stock = $_GET['stock'];
?>


<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
<title>Beli Anggrek</title>
</head>
<body>
<h1>Pembelian: <?= $name ?></h1>
<form method="POST">
<!-- Input jumlah pembelian -->
<label>Jumlah: </label>
<input type="number" name="qty" min="1" max="<?= $stock ?>" required>
<br><br>
<!-- Pilih metode pengiriman -->
<label>Pengiriman: </label>
<select name="ship">
<option value="10000">Reguler (10.000)</option>
<option value="20000">Kilat (20.000)</option>
</select>
<br><br>
<button type="submit">Hitung Total</button>
</form>


<?php
// Logic hitung total harga dan cek stok
if ($_POST) {
$qty = $_POST['qty'];
$ship = $_POST['ship'];


if ($qty > $stock) {
echo "<h2>Stok tidak cukup! Stok tersedia: $stock</h2>";
} else {
$total = ($price * $qty) + $ship;
echo "<h2>Total: Rp " . number_format($total, 0, ',', '.') . "</h2>";
}
}
?>
</body>
</html>