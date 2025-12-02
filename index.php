<?php
// index.php

session_start();
include 'classes.php'; 

// Modul 1 & 5: Ganti array data menjadi array objek Orchid
$orchids_data = [
    new Orchid("Anggrek Bulan (Putih)", 120000, 5, "6 bulan", "gambar/bulanp.jpg"),
    new Orchid("Anggrek Bulan (Merah)", 95000, 10, "4 bulan", "gambar/bulanm.jpg"),
    new Orchid("Anggrek Bulan (Ungu)", 250000, 3, "8 bulan", "gambar/bulanu.jpg"),
    new Orchid("Anggrek Bulan (Pink)", 180000, 4, "5 bulan", "gambar/bulanpink.jpg"),
    new Orchid("Anggrek Dendrobium (Putih)", 200000, 2, "6 bulan", "gambar/dendrop.jpg"),
    new Orchid("Anggrek Dendrobium (Merah)", 150000, 6, "7 bulan", "gambar/dendrom.jpg"),
    new Orchid("Anggrek Dendrobium (Ungu)", 200000, 2, "6 bulan", "gambar/dendrou.jpg"),
    new Orchid("Anggrek Dendrobium (Kuning)", 150000, 6, "7 bulan", "gambar/dendrok.jpg"),
];


// Simpan stok di session jika belum ada (menggunakan data OOP)
// Supaya stok tidak ter-reset jika stok sudah pernah diubah di buy.php
if(!isset($_SESSION['orchids'])){
    // Tempat simpan objek sebagai array sederhana di session agar mudah diakses di buy.php
    $_SESSION['orchids'] = array_map(function($o) {
        // Menggunakan ReflectionProperty atau get_object_vars() untuk mengambil semua properti
        return get_object_vars($o); 
    }, $orchids_data);
}


// Ubah array session kembali ke array objek untuk tampilan di index
$orchids_display = array_map(function($o_array) {
    return new Orchid(
        $o_array['name'], 
        $o_array['price'], 
        $o_array['stock'], 
        $o_array['age'], 
        $o_array['img']
    );
}, $_SESSION['orchids']);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mozaik Kebun Anggrek - Katalog & Pembelian</title>
    <link rel="stylesheet" href="style.css">
    </head>

<body>
   <nav class="navbar">
         <div class="logo">
            <a href="#home">
                <img src="gambar/logo_mozaik.png" alt="Mozaik Kebun Anggrek Logo">
            </a>
        </div>
        <div class="nav-links">
            <a href="#home">Home</a>
            <a href="#products">Products</a>
            <a href="#trivia">Trivia</a>
        </div>
    </nav>

    <section id="home" class="section">
        <div class="home-image-container">
            <img src="gambar/show.jpg" alt="Anggrek Ungu Cantik">
            </div>
        <div class="home-info">
            <h1>Selamat Datang di Mozaik Kebun Anggrek!</h1>
            <p>Mozaik Kebun Anggrek adalah rumah bagi koleksi anggrek-anggrek terbaik, dari Anggrek Bulan yang elegan hingga Dendrobium yang cerah. Kami berdedikasi untuk menyediakan bibit dan tanaman anggrek dengan kualitas premium.</p>
            <p>Telusuri katalog produk kami untuk menemukan anggrek yang sempurna untuk koleksi atau hadiah Anda. Setiap anggrek dirawat dengan penuh kasih dan siap untuk memperindah ruangan Anda!</p>
            <a href="#products"><button style="width: auto;">Lihat Produk Kami ➡️</button></a>
        </div>
    </section>

    <hr> 

    <section id="products" class="section">
        <h1>Products</h1>
        <div class="grid">
            <?php foreach ($orchids_display as $o): // Modul 3: Foreach ?>
            <div class="card">
                <img src="<?= htmlspecialchars($o->img) ?>" alt="<?= htmlspecialchars($o->name) ?>">
                <h2><?= htmlspecialchars($o->name) ?></h2>
                <p><?= $o->displayInfo() ?></p>    
                
                <form action="buy.php" method="GET">
                    <input type="hidden" name="name" value="<?= htmlspecialchars($o->name) ?>">
                    <input type="hidden" name="price" value="<?= htmlspecialchars($o->price) ?>">
                    <input type="hidden" name="stock" value="<?= htmlspecialchars($o->stock) ?>">
                    <button type="submit" <?= $o->stock == 0 ? 'disabled' : '' ?>>
                        <?= $o->stock == 0 ? 'Stok Habis' : 'Beli Sekarang' ?>
                    </button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <hr>

    <section id="trivia" class="section">
        <h1>Trivia Anggrek 💡</h1>
        <div class="trivia-item">
            <h3>Tips Merawat Anggrek Bulan (Phalaenopsis)</h3>
            <p><strong>Pencahayaan:</strong> Letakkan di tempat yang terang namun tidak terkena sinar matahari langsung. Sinar matahari pagi sangat baik.</p>
            <p><strong>Penyiraman:</strong> Siram saat media tanam mulai kering (sekitar 1-2 kali seminggu). Jangan biarkan air menggenang di dasar pot.</p>
            <p><strong>Suhu:</strong> Anggrek Bulan menyukai suhu hangat, idealnya antara 18°C hingga 29°C.</p>
        </div>
        <div class="trivia-item">
            <h3>Fakta Unik Anggrek Dendrobium</h3>
            <p>Anggrek Dendrobium adalah salah satu genus anggrek terbesar, dengan ribuan spesies. Mereka dikenal karena daya tahannya dan kemampuannya untuk mekar dalam waktu yang lama. Banyak varietas Dendrobium yang berbunga lebih dari sekali dalam setahun!</p>
        </div>
    </section>

    <footer style="text-align: center; padding: 20px; background: #4d3e53; color: white;">
        <p>&copy; <?= date('Y') ?> Mozaik Kebun Anggrek. All Rights Reserved.</p>
    </footer>

</body>
</html>