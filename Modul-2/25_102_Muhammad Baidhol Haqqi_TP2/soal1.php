<?php
// Deklarasi array sesuai ketentuan modul
$matkul = ["PTI", "ALPRO", "DPW", "STRUKDAT", "JARKOM", "PAW", "PSBF", "RPL"];
$praktikum = ["JARKOM", "PAW"];

// Menggunakan perulangan for
for ($i = 0; $i < count($matkul); $i++) {
    // Mengecek apakah matkul ada di dalam array praktikum
    if (in_array($matkul[$i], $praktikum)) {
        echo "Saya sedang mengambil matkul " . $matkul[$i] . " termasuk praktikum nya<br>";
    } 
    // Mengecek apakah indeks matkul adalah 6 atau 7
    elseif ($i == 6 || $i == 7) {
        echo "Saya belum mengambil matkul " . $matkul[$i] . "<br>";
    } 
    // Kondisi lainnya
    else {
        echo "Saya sudah mengambil matkul " . $matkul[$i] . " semester lalu<br>";
    }
}
?>