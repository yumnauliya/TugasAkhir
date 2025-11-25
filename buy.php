<?php
session_start(); 

// Ambil data GET
$name = $_GET['name'] ?? '';
$price = (int)($_GET['price'] ?? 0);
$stock = (int)($_GET['stock'] ?? 0);

// Inisialisasi stok di session jika belum ada (sama dengan index.php)
if(!isset($_SESSION['orchids'])){
    $_SESSION['orchids'] = [
        ["name" => "Anggrek Bulan", "price" => 120000, "age" => "6 bulan", "img" => "gambar/bulan.jpg", "stock" => 5],
        ["name" => "Anggrek Dendrobium", "price" => 95000, "age" => "4 bulan", "img" => "gambar/dendrobium.jpg", "stock" => 10],
        ["name" => "Anggrek Bulan tapi Ungu", "price" => 250000, "age" => "8 bulan", "img" => "gambar/bulaan.jpg", "stock" => 3],
        ["name" => "Anggrek Merah", "price" => 180000, "age" => "5 bulan", "img" => "gambar/dendro.jpg", "stock" => 4],
    ["name" => "Anggrek Kuning", "price" => 200000, "age" => "6 bulan", "img" => "gambar/kuning.jpg", "stock" => 2],
    ["name" => "Anggrek Putih", "price" => 150000, "age" => "7 bulan", "img" => "gambar/putih.jpg", "stock" => 6],
    ];
}

// Fungsi hitung total & format rupiah
function calculateTotal($price, $qty, $ship) {
    return ($price * $qty) + $ship;
}
function formatRupiah($number) {
    return "Rp " . number_format($number, 0, ',', '.');
}

// Queue riwayat
if (!isset($_SESSION['history'])) {
    $_SESSION['history'] = [];
}

// Step default
$step = $_POST['step'] ?? 1;

// Step 1 → simpan pending di session
if($step == 2 && isset($_POST['qty'], $_POST['ship'])){
    $_SESSION['pending'] = [
        'name' => $name,
        'qty' => (int)$_POST['qty'],
        'ship' => (int)$_POST['ship'],
        'total' => calculateTotal($price,(int)$_POST['qty'],(int)$_POST['ship'])
    ];
}

// Step 3 → proses pembayaran
if($step == 3 && isset($_POST['method'])){
    if(!isset($_SESSION['pending'])){
        $error = "Data pembelian tidak tersedia. Kembali ke katalog.";
        $step = 1;
    } else {
        $method = $_POST['method'];
        $transaction = $_SESSION['pending'];
        $transaction['method'] = $method;
        $transaction['time'] = date('H:i:s');

        // Dummy validasi Kartu Kredit
        if($method == 'Kartu Kredit'){
            $valid = rand(0,1);
            if(!$valid){
                $error = "Pembayaran Kartu Kredit gagal, coba lagi.";
                $step = 2;
            }
        }

        if(!isset($error)){
            // Tambahkan transaksi ke history
            array_push($_SESSION['history'],$transaction);

            // 🔹 Update stok di session agar berkurang
            if(isset($_SESSION['orchids'])){
                foreach($_SESSION['orchids'] as &$o){
                    if($o['name'] === $transaction['name']){
                        $o['stock'] -= $transaction['qty'];
                        if($o['stock'] < 0) $o['stock'] = 0;
                    }
                }
                unset($o);
            }

            unset($_SESSION['pending']);
            $success = true;
        }
    }
}

$pending = $_SESSION['pending'] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
<meta charset="UTF-8">
<title>Beli Anggrek</title>
</head>
<body>
<h1>Pembelian: <?= htmlspecialchars($name) ?></h1>

<?php if($step==1): ?>
<!-- STEP 1: Hitung Total -->
<form method="POST">
<label>Jumlah: </label>
<input type="number" name="qty" min="1" max="<?= $stock ?>" required>
<br><br>
<label>Pengiriman: </label>
<select name="ship">
<option value="10000">Reguler (10.000)</option>
<option value="20000">Kilat (20.000)</option>
</select>
<br><br>
<input type="hidden" name="step" value="2">
<button type="submit">Hitung Total</button>
</form>

<?php elseif($step==2 && $pending): ?>
<!-- STEP 2: Pilih metode pembayaran -->
<p>Total: <strong><?= formatRupiah($pending['total']) ?></strong></p>
<?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
<form method="POST">
<label>Pilih Metode Pembayaran: </label>
<select name="method" required>
<option value="Kartu Kredit">Kartu Kredit</option>
<option value="Transfer">Transfer</option>
<option value="COD">COD</option>
</select>
<br><br>
<input type="hidden" name="step" value="3">
<button type="submit">Bayar</button>
</form>

<?php elseif($step==3 && isset($success) && $success): ?>
<!-- STEP 3: Sukses -->
<h2>✅ Pembayaran Berhasil!</h2>
<p>Total Dibayar: <?= formatRupiah($transaction['total']) ?></p>
<p>Metode: <?= $transaction['method'] ?></p>

<?php else: ?>
<p style="color:red"><?= $error ?? 'Terjadi kesalahan, kembali ke katalog.' ?></p>
<p><a href="index.php">⬅️ Kembali ke Katalog</a></p>
<?php endif; ?>

<br><br>
<hr>
<h2>Riwayat Pembelian (Queue)</h2>
<?php if(empty($_SESSION['history'])): ?>
<p>Belum ada transaksi.</p>
<?php else: ?>
<ul>
<?php foreach($_SESSION['history'] as $t): ?>
<li>
[<?= $t['time'] ?>] Beli <strong><?= $t['qty'] ?></strong>x <?= $t['name'] ?>. 
Total: <?= formatRupiah($t['total']) ?> (Ongkir: <?= formatRupiah($t['ship']) ?>), Metode: <?= $t['method'] ?? '-' ?>
</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<br>
<p><a href="index.php">⬅️ Kembali ke Katalog</a></p>
</body>
</html>
