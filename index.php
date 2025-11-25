<?php
// Array berisi data anggrek
$orchids = [
    ["name" => "Anggrek Bulan", "price" => 120000, "age" => "6 bulan", "img" => "bulan.jpg", "stock" => 5],
    ["name" => "Anggrek Dendrobium", "price" => 95000, "age" => "4 bulan", "img" => "dendrobium.jpg", "stock" => 10],
    ["name" => "Anggrek Bulan tapi Ungu", "price" => 250000, "age" => "8 bulan", "img" => "bulaan.jpg", "stock" => 3],
];
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mozaik Kebun Anggrek</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Mozaik Kebun Anggrek</h1>
    </header>
    
    <div class="grid">
        <?php foreach ($orchids as $o): ?>
        <div class="card">
            <!-- Gambar anggrek -->
            <img src="<?= $o['img'] ?>" alt="<?= $o['name'] ?>">

            <!-- Nama anggrek -->
            <h2><?= $o['name'] ?></h2>
        
            <!-- Umur tanaman -->
            <p>Umur: <?= $o['age'] ?></p>

            <!-- Harga -->
            <p>Harga: Rp <?= number_format($o['price'], 0, ',', '.') ?></p>

            <!-- Stok tersedia -->
            <p>Stok: <?= $o['stock'] ?></p>

            <!-- Form beli anggrek -->
            <form action="buy.php" method="GET">
            <input type="hidden" name="name" value="<?= $o['name'] ?>">
            <input type="hidden" name="price" value="<?= $o['price'] ?>">
            <input type="hidden" name="stock" value="<?= $o['stock'] ?>">
            <button type="submit">Beli</button>
            </form>
        </div>
         <?php endforeach; ?>
    </div>
</body>
</html>