<?php
// classes.php
// Modul 5: Class Product
class Product {
    public $name;
    public $price;
    public $stock;
    public $age;
    public $img;

    public function __construct($name, $price, $stock, $age, $img) {
        $this->name = $name;
        $this->price = $price;
        $this->stock = $stock;
        $this->age = $age;
        $this->img = $img;
    }

    // Function (Modul 4) untuk memformat Rupiah
    public function formatRupiah() {
        return "Rp " . number_format($this->price, 0, ',', '.');
    }

    // Modul 6: Method dasar untuk menampilkan info (akan di-override)
    public function displayInfo() {
        $priceFormatted = $this->formatRupiah();
        return "Umur: " . $this->age . "<br>" . 
               "Harga: " . $priceFormatted . "<br>" .
               "Stok: " . $this->stock;
    }
}

// Modul 6: Class Orchid (untuk kasus ini, Orchid sama dengan Product, 
// tapi bisa dikembangkan nanti, jadi kita pertahankan struktur kelasnya)
class Orchid extends Product {
    // Tidak perlu override __construct jika properti sama
    // Tapi kita bisa override displayInfo() jika perlu info spesifik Anggrek
}

// Function (Modul 4) untuk menghitung total
function calculateTotal($price, $qty, $ship) {
    return ($price * $qty) + $ship;
}

// Function (Modul 4) untuk format rupiah (dipakai di buy.php untuk total)
function formatRupiah($number) {
    return "Rp " . number_format($number, 0, ',', '.');
}