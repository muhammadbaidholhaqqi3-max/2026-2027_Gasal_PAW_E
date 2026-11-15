<?php
require "koneksi.php";

function valid_tanggal($t) {
    $d = DateTime::createFromFormat('Y-m-d', $t);
    return $d && $d->format('Y-m-d') === $t;
}

$tanggal_mulai   = $_GET['tanggal_mulai']   ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';

if (!valid_tanggal($tanggal_mulai) || !valid_tanggal($tanggal_selesai) || $tanggal_mulai > $tanggal_selesai) {
    die("Rentang tanggal tidak valid.");
}

// Header HTTP: beri tahu browser bahwa respon adalah file Excel yang diunduh
$namafile = "Rekap_Penjualan_{$tanggal_mulai}_sd_{$tanggal_selesai}.xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$namafile\"");
header("Pragma: no-cache");
header("Expires: 0");

// Data rekap harian
$sql = "SELECT DATE(waktu_transaksi) AS tgl, SUM(total) AS total_harian
        FROM transaksi
        WHERE DATE(waktu_transaksi) BETWEEN ? AND ?
        GROUP BY DATE(waktu_transaksi)
        ORDER BY tgl ASC";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "ss", $tanggal_mulai, $tanggal_selesai);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

// Ringkasan
$sql2 = "SELECT COUNT(DISTINCT pelanggan_id) AS jml, COALESCE(SUM(total),0) AS tot
         FROM transaksi
         WHERE DATE(waktu_transaksi) BETWEEN ? AND ?";
$stmt2 = mysqli_prepare($koneksi, $sql2);
mysqli_stmt_bind_param($stmt2, "ss", $tanggal_mulai, $tanggal_selesai);
mysqli_stmt_execute($stmt2);
$sum = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt2));
?>
<meta charset="UTF-8">
<h3>Rekap Laporan Penjualan <?= htmlspecialchars($tanggal_mulai) ?> sampai <?= htmlspecialchars($tanggal_selesai) ?></h3>
<table border="1" cellpadding="5" cellspacing="0">
    <tr style="background:#d6eaf5;">
        <th>No</th><th>Total Pendapatan</th><th>Tanggal Transaksi</th>
    </tr>
    <?php $no = 1; while ($r = mysqli_fetch_assoc($res)): ?>
    <tr>
        <td><?= $no++ ?></td>
        <td>Rp. <?= number_format($r['total_harian'], 0, ',', '.') ?></td>
        <td><?= date('d M Y', strtotime($r['tgl'])) ?></td>
    </tr>
    <?php endwhile; ?>
</table>
<br>
<table border="1" cellpadding="5" cellspacing="0">
    <tr style="background:#d6eaf5;">
        <th>Jumlah Pelanggan Unik</th><th>Total Pendapatan Keseluruhan</th>
    </tr>
    <tr>
        <td><?= (int)$sum['jml'] ?> Orang</td>
        <td>Rp. <?= number_format($sum['tot'], 0, ',', '.') ?></td>
    </tr>
</table>
