<?php include 'koneksi.php';
mysqli_report(MYSQLI_REPORT_OFF);
$id = (int)($_GET['id'] ?? 0);
$st = mysqli_prepare($conn, "DELETE FROM supplier WHERE id=?");
mysqli_stmt_bind_param($st, "i", $id);
if (mysqli_stmt_execute($st)) {
  $pesan = "Data supplier berhasil dihapus";
} else {
  // supplier yang masih dipakai tabel barang tidak boleh dihapus (foreign key)
  $pesan = "Supplier tidak dapat dihapus karena masih dipakai oleh data barang";
}
header("Location: index.php?pesan=" . urlencode($pesan));
