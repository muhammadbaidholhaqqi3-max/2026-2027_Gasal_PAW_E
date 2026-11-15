<?php
// =====================================================
// Laporan Penjualan - Modul 7 Reporting (PAW)
// =====================================================
require "koneksi.php";

// Cek format tanggal harus YYYY-MM-DD
function valid_tanggal($t) {
    $d = DateTime::createFromFormat('Y-m-d', $t);
    return $d && $d->format('Y-m-d') === $t;
}
// Format angka menjadi Rp. 1.000
function rupiah($n) { return "Rp. " . number_format($n, 0, ',', '.'); }

// 1. Ambil input dari form filter
$tanggal_mulai   = $_GET['tanggal_mulai']   ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';
$tampil = false;
$error  = '';

$labels = [];            // sumbu X grafik (tanggal)
$data_total = [];        // sumbu Y grafik (total per hari)
$rekap = [];             // data tabel rekapitulasi
$total_pendapatan = 0;   // ringkasan: total pendapatan
$total_pelanggan  = 0;   // ringkasan: pelanggan unik

// 2. Proses data hanya jika kedua tanggal sudah diisi
if ($tanggal_mulai !== '' && $tanggal_selesai !== '') {
    if (!valid_tanggal($tanggal_mulai) || !valid_tanggal($tanggal_selesai)) {
        $error = "Format tanggal tidak valid.";
    } elseif ($tanggal_mulai > $tanggal_selesai) {
        $error = "Tanggal mulai tidak boleh lebih besar dari tanggal selesai.";
    } else {
        $tampil = true;

        // Query 1: total pendapatan per hari (SUM + GROUP BY DATE)
        $sql = "SELECT DATE(waktu_transaksi) AS tgl, SUM(total) AS total_harian
                FROM transaksi
                WHERE DATE(waktu_transaksi) BETWEEN ? AND ?
                GROUP BY DATE(waktu_transaksi)
                ORDER BY tgl ASC";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $tanggal_mulai, $tanggal_selesai);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        while ($r = mysqli_fetch_assoc($res)) {
            $labels[]     = $r['tgl'];
            $data_total[] = (int)$r['total_harian'];
            $rekap[]      = $r;
            $total_pendapatan += (int)$r['total_harian'];
        }
        mysqli_stmt_close($stmt);

        // Query 2: jumlah pelanggan unik (COUNT DISTINCT)
        $sql2 = "SELECT COUNT(DISTINCT pelanggan_id) AS jml
                 FROM transaksi
                 WHERE DATE(waktu_transaksi) BETWEEN ? AND ?";
        $stmt2 = mysqli_prepare($koneksi, $sql2);
        mysqli_stmt_bind_param($stmt2, "ss", $tanggal_mulai, $tanggal_selesai);
        mysqli_stmt_execute($stmt2);
        $rp = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt2));
        $total_pelanggan = (int)($rp['jml'] ?? 0);
        mysqli_stmt_close($stmt2);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan dan Grafik</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }

        /* Saat dicetak, elemen ber-class "no-print" (form & tombol) disembunyikan */
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body>

<!-- Form filter rentang tanggal (tidak ikut dicetak) -->
<div class="no-print">
    <h1>Filter Laporan Penjualan</h1>
    <form method="GET">
        Dari: <input type="date" name="tanggal_mulai" value="<?= htmlspecialchars($tanggal_mulai) ?>" required>
        Sampai: <input type="date" name="tanggal_selesai" value="<?= htmlspecialchars($tanggal_selesai) ?>" required>
        <button type="submit">Tampilkan</button>
    </form>
    <?php if ($error): ?><p style="color:red;"><?= htmlspecialchars($error) ?></p><?php endif; ?>
</div>

<?php if ($tampil): ?>

    <h2>Rekap Laporan Penjualan <?= htmlspecialchars($tanggal_mulai) ?> sampai <?= htmlspecialchars($tanggal_selesai) ?></h2>

    <!-- Tombol Kembali, Cetak, dan Excel (tidak ikut dicetak) -->
    <div class="no-print">
        <a href="laporan.php"><button type="button">Kembali</button></a>
        <?php if (!empty($rekap)): ?>
            <button type="button" onclick="window.print()">Cetak</button>
            <a href="export_excel.php?tanggal_mulai=<?= urlencode($tanggal_mulai) ?>&tanggal_selesai=<?= urlencode($tanggal_selesai) ?>"><button type="button">Excel</button></a>
        <?php endif; ?>
    </div>

    <?php if (empty($rekap)): ?>
        <p>Tidak ada transaksi pada rentang tanggal tersebut.</p>
    <?php else: ?>

        <!-- Grafik batang -->
        <div style="width: 700px; height: 320px;"><canvas id="myChart"></canvas></div>
        <script>
            // Data dari PHP diubah ke JavaScript dengan json_encode
            new Chart(document.getElementById('myChart'), {
                type: 'bar',                                   // jenis grafik: batang
                data: {
                    labels: <?= json_encode($labels) ?>,       // sumbu X: tanggal
                    datasets: [{
                        label: 'Total',
                        data: <?= json_encode($data_total) ?>  // sumbu Y: total pendapatan
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false,                          // agar langsung tampil saat dicetak
                    scales: {
                        x: { title: { display: true, text: 'Tanggal Transaksi' } },
                        y: { beginAtZero: true, title: { display: true, text: 'Total Pendapatan (Rp)' } }
                    }
                }
            });
        </script>

        <!-- Tabel rekapitulasi -->
        <table>
            <tr><th>No</th><th>Total Pendapatan</th><th>Tanggal Transaksi</th></tr>
            <?php $no = 1; foreach ($rekap as $row): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= rupiah($row['total_harian']) ?></td>
                <td><?= date('d M Y', strtotime($row['tgl'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <!-- Ringkasan total kumulatif (di bawah tabel) -->
        <table style="width:auto;">
            <tr><th>Jumlah Pelanggan Unik</th><th>Total Pendapatan Keseluruhan</th></tr>
            <tr><td><?= $total_pelanggan ?> Orang</td><td><?= rupiah($total_pendapatan) ?></td></tr>
        </table>

    <?php endif; ?>
<?php endif; ?>

</body>
</html>
