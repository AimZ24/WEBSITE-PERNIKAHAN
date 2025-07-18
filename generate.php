<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Akses ditolak.");
}

// ----------------------------------------------------
// PENGATURAN & VARIABEL - SESUAIKAN DI SINI
// ----------------------------------------------------

// Ambil semua data dari form
$namaPria = htmlspecialchars($_POST['nama_pria'] ?? '');
$namaWanita = htmlspecialchars($_POST['nama_wanita'] ?? '');
$hariTanggal = htmlspecialchars($_POST['hari_tanggal'] ?? '');
$lokasi = htmlspecialchars($_POST['lokasi'] ?? '');

// Path ke file template baru dan font
$jalurTemplate = 'nikah_tamplate.png'; // GANTI DENGAN NAMA FILE TEMPLATE UNDANGAN ANDA
$jalurFont_nama = 'font_nama.ttf';       // Font untuk nama (misal: font latin/elegan)
$jalurfont_biasa = 'font_biasa.ttf'; // Font untuk detail (misal: font biasa/jelas)

// Pastikan semua file ada
if (!file_exists($jalurTemplate) || !file_exists($jalurFont_nama) || !file_exists($jalurfont_biasa)) {
    die("Error: File template atau font tidak ditemukan.");
}

// --- PENGATURAN TEKS ---
// Anda bisa mengatur setiap teks secara terpisah
$warnaNama = [199, 97, 69]; // Warna Hitam untuk semua teks (bisa diubah)
$warnaTanggal = [104, 107, 64]; // warna Tanggalnya
$warnaTempat = [104, 107, 64]; // Warna tempatnya

// Pengaturan untuk Nama Pria & Wanita
$ukuranfontDAN = 30;
$ukuranFont_nama = 90;
$posisiY_pria = 510;
$posisiY_wanita = 690;

// Pengaturan untuk Hari & Tanggal
$ukuranfont_biasa = 25;
$posisiY_tanggal = 920;

// Pengaturan untuk Lokasi
$ukuranfont_lokasi = 35;
$posisiY_lokasi = 1000;


// ----------------------------------------------------
// PROSES PEMBUATAN GAMBAR
// ----------------------------------------------------

// Muat gambar template
$gambar = imagecreatefrompng($jalurTemplate);
$lebarGambar = imagesx($gambar);

// Alokasikan setiap warna yang sudah Anda definisikan di atas
// Variabel ini yang akan kita gunakan (misal: $warnaUntukNama)
$warnaUntukNama = imagecolorallocate($gambar, $warnaNama[0], $warnaNama[1], $warnaNama[2]);
$warnaUntukTanggal = imagecolorallocate($gambar, $warnaTanggal[0], $warnaTanggal[1], $warnaTanggal[2]);
$warnaUntukTempat = imagecolorallocate($gambar, $warnaTempat[0], $warnaTempat[1], $warnaTempat[2]);

// Fungsi untuk menghitung posisi X tengah dan menulis teks
function tulisTengah($gambar, $ukuran, $font, $teks, $posisiY, $warna, $lebarGambar) {
    $kotakTeks = imagettfbbox($ukuran, 0, $font, $teks);
    $lebarTeks = $kotakTeks[2] - $kotakTeks[0];
    $posisiX = ($lebarGambar - $lebarTeks) / 2;
    // Baris ini yang sebelumnya error
    imagettftext($gambar, $ukuran, 0, $posisiX, $posisiY, $warna, $font, $teks);
}

// Tulis semua teks ke gambar DENGAN VARIABEL WARNA YANG SUDAH DIALOKASIKAN
// Perhatikan perbedaannya: kita pakai $warnaUntukNama, bukan $warnaNama
tulisTengah($gambar, $ukuranFont_nama, $jalurFont_nama, $namaPria, $posisiY_pria, $warnaUntukNama, $lebarGambar);
tulisTengah($gambar, $ukuranfontDAN, $jalurfont_biasa, "&", $posisiY_pria + 60, $warnaUntukNama, $lebarGambar);
tulisTengah($gambar, $ukuranFont_nama, $jalurFont_nama, $namaWanita, $posisiY_wanita, $warnaUntukNama, $lebarGambar);

tulisTengah($gambar, $ukuranfont_biasa, $jalurfont_biasa, $hariTanggal, $posisiY_tanggal, $warnaUntukTanggal, $lebarGambar);
tulisTengah($gambar, $ukuranfont_lokasi, $jalurfont_biasa, $lokasi, $posisiY_lokasi, $warnaUntukTempat, $lebarGambar);

// Tampilkan gambar ke browser
header('Content-Type: image/png');
header('Content-Disposition: inline; filename="undangan.png"');
imagepng($gambar);
imagedestroy($gambar);