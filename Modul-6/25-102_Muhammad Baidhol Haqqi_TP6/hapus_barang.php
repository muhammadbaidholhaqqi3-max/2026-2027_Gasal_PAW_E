<?php
include "koneksi.php";

$id = intval($_GET['id']);

// cek dulu apakah barang sudah dipakai di transaksi_detail
$cek = mysqli_query($koneksi, "SELECT * FROM transaksi_detail WHERE barang_id = $id");

if (mysqli_num_rows($cek) > 0) {
    // sudah dipakai -> tidak boleh dihapus
    header("Location: barang.php?pesan=dipakai");
} else {
    // belum dipakai -> boleh dihapus
    mysqli_query($koneksi, "DELETE FROM barang WHERE id = $id");
    header("Location: barang.php?pesan=terhapus");
}
exit;
?>
