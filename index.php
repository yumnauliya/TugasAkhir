<?php
session_start(); // Untuk nantinya bisa track stok & riwayat

$orchids = [
    ["name" => "Anggrek Bulan (Putih)", "price" => 120000, "age" => "6 bulan", "img" => "gambar/bulan.jpg", "stock" => 5],
    ["name" => "Anggrek Dendrobium (Merah)", "price" => 95000, "age" => "4 bulan", "img" => "gambar/dendrobium.jpg", "stock" => 10],
    ["name" => "Anggrek Bulan (Ungu)", "price" => 250000, "age" => "8 bulan", "img" => "gambar/bulaan.jpg", "stock" => 3],
    ["name" => "Anggrek Bulan (Merah)", "price" => 180000, "age" => "5 bulan", "img" => "gambar/merah.jpg", "stock" => 4],
    ["name" => "Anggrek Dendrobium (Kuning)", "price" => 200000, "age" => "6 bulan", "img" => "gambar/kuning.jpg", "stock" => 2],
    ["name" => "Anggrek Dendrobium (Putih)", "price" => 150000, "age" => "7 bulan", "img" => "gambar/putih.jpg", "stock" => 6],
];

$_SESSION['orchids'] = $orchids;


// Simpan stok di session supaya bisa update saat beli
if(!isset($_SESSION['orchids'])){
    $_SESSION['orchids'] = $orchids;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Mozaik Kebun Anggrek - Katalog & Pembelian</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        
    </header>

    <div class="grid">
        <?php foreach ($_SESSION['orchids'] as $o): ?>
        <div class="card">
            <img src="<?= htmlspecialchars($o['img']) ?>" alt="<?= htmlspecialchars($o['name']) ?>">
            <h2><?= htmlspecialchars($o['name']) ?></h2>
            <p>Umur: <?= htmlspecialchars($o['age']) ?></p>
            <p>Harga: <?= number_format($o['price'], 0, ',', '.') ?></p>
            <p>Stok: <?= htmlspecialchars($o['stock']) ?></p>

            <form action="buy.php" method="GET">
                <input type="hidden" name="name" value="<?= htmlspecialchars($o['name']) ?>">
                <input type="hidden" name="price" value="<?= htmlspecialchars($o['price']) ?>">
                <input type="hidden" name="stock" value="<?= htmlspecialchars($o['stock']) ?>">
                <button type="submit">Beli</button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
