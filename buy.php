<?php
session_start();
include 'classes.php';

$name = $_GET['name'] ?? $_SESSION['pending']['name'] ?? '';
$price = (int)($_GET['price'] ?? $_SESSION['pending']['price'] ?? 0);
$stock = (int)($_GET['stock'] ?? $_SESSION['pending']['stock'] ?? 0);

if(!isset($_SESSION['history'])) $_SESSION['history'] = [];

$step = $_POST['step'] ?? 1;

// STEP 2: pending
if($step==2 && isset($_POST['qty'], $_POST['ship'])){
    $qty = (int)$_POST['qty'];
    $ship = (int)$_POST['ship'];
    if($qty <=0 || $qty > $stock){
        $error = "Jumlah pembelian tidak valid! Stok maksimum: $stock";
        $step = 1;
    } else {
        $_SESSION['pending'] = [
            'name'=>$name,'qty'=>$qty,'ship'=>$ship,'price'=>$price,
            'total'=>calculateTotal($price,$qty,$ship),'stock'=>$stock
        ];
    }
}

// STEP 3: pembayaran
if($step==3 && isset($_POST['method'])){
    if(!isset($_SESSION['pending'])) { $error="Data tidak tersedia"; $step=1; }
    else{
        $method = $_POST['method'];
        $transaction = $_SESSION['pending'];

        if($method=='Kartu Kredit' && !rand(0,1)){
            $error="Pembayaran Kartu Kredit gagal"; $step=2;
        }

        if(!isset($error)){
            $transaction['method']=$method;
            $transaction['time']=date('H:i:s');
            $_SESSION['history'][]=$transaction;

            foreach($_SESSION['orchids'] as &$o){
                if($o['name']==$transaction['name']){
                    $o['stock'] -= $transaction['qty'];
                    if($o['stock']<0) $o['stock']=0;
                    break;
                }
            } unset($o);
            unset($_SESSION['pending']);
            $success=true;
        }
    }
}

$pending=$_SESSION['pending']??null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Beli Anggrek</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="buy-page">
    <nav class="buy-navbar">
    <div class="logo">
        <img src="gambar/logo_mozaik.png" alt="Logo" height="40">
    </div>
    <div class="nav-links">
        <a href="index.php">Home</a>
    </div>
</nav>
<h1>Pembelian: <?= htmlspecialchars($name) ?></h1>

<?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
<?php if(isset($success) && $success) echo "<p class='success'>✅ Pembayaran Berhasil!</p>"; ?>

<?php if($step==1): ?>
<div class="buy-card">
<form method="POST">
<label>Jumlah (stok max <?= $stock ?>):</label>
<input type="number" name="qty" min="1" max="<?= $stock ?>" required>
<label>Pengiriman:</label>
<select name="ship">
<option value="10000">Reguler (10.000)</option>
<option value="20000">Kilat (20.000)</option>
</select>
<input type="hidden" name="step" value="2">
<button type="submit">Hitung Total</button>
</form>
</div>

<?php elseif($step==2 && $pending): ?>
<div class="buy-card">
<p>Total: <strong><?= formatRupiah($pending['total']) ?></strong></p>
<form method="POST">
<label>Pilih Metode Pembayaran:</label>
<select name="method" required>
<option value="Kartu Kredit">Kartu Kredit</option>
<option value="Transfer">Transfer</option>
<option value="COD">COD</option>
</select>
<input type="hidden" name="step" value="3">
<button type="submit">Bayar</button>
</form>
</div>

<?php elseif($step==3 && isset($success) && $success): ?>
<div class="buy-card">
<p>Total Dibayar: <?= formatRupiah($transaction['total']) ?></p>
<p>Metode: <?= htmlspecialchars($transaction['method']) ?></p>
</div>
<?php endif; ?>

<hr>
<h2>Riwayat Pembelian (Queue)</h2>
<?php if(empty($_SESSION['history'])): ?>
<p>Belum ada transaksi.</p>
<?php else: ?>
<ul>
<?php foreach($_SESSION['history'] as $t): ?>
<li>
[<?= $t['time'] ?>] Beli <strong><?= $t['qty'] ?></strong>x <?= htmlspecialchars($t['name']) ?>.
Total: <?= formatRupiah($t['total']) ?> (Ongkir: <?= formatRupiah($t['ship']) ?>), Metode: <?= htmlspecialchars($t['method']??'-') ?>
</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<p><a href="index.php">⬅️ Kembali ke Katalog</a></p>
</body>
</html>
