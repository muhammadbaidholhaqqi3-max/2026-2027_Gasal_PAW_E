<?php
include "koneksi.php";

// ambil id transaksi (master) dari URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}
$id = intval($_GET['id']);

$pesan = "";

// ===== SIMPAN DETAIL BARU =====
if (isset($_POST['simpan_detail'])) {
    $barang_id = intval($_POST['barang_id']);
    $qty       = intval($_POST['qty']);

    // cek dulu barang ini sudah ada di transaksi ini atau belum
    $cek = mysqli_query($koneksi, "SELECT * FROM transaksi_detail
                                   WHERE transaksi_id = $id AND barang_id = $barang_id");

    if ($qty <= 0) {
        $pesan = "Qty harus lebih dari 0!";
    } elseif (mysqli_num_rows($cek) > 0) {
        $pesan = "Barang tersebut sudah ada di transaksi ini!";
    } else {
        // harga diambil dari tabel barang
        $qb = mysqli_query($koneksi, "SELECT harga FROM barang WHERE id = $barang_id");
        $b  = mysqli_fetch_assoc($qb);
        $harga = $b['harga'];

        // 1. simpan ke transaksi_detail
        mysqli_query($koneksi, "INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty)
                                VALUES ($id, $barang_id, $harga, $qty)");

        // 2. hitung ulang total transaksi (harga x qty semua detail)
        $qt = mysqli_query($koneksi, "SELECT SUM(harga * qty) AS total
                                      FROM transaksi_detail
                                      WHERE transaksi_id = $id");
        $t = mysqli_fetch_assoc($qt);
        $total = $t['total'];

        // 3. update kolom total di tabel transaksi
        mysqli_query($koneksi, "UPDATE transaksi SET total = $total WHERE id = $id");

        header("Location: detail.php?id=$id");
        exit;
    }
}

// ===== DATA MASTER =====
$qm = mysqli_query($koneksi, "SELECT transaksi.*, pelanggan.nama
                              FROM transaksi
                              INNER JOIN pelanggan ON transaksi.pelanggan_id = pelanggan.id
                              WHERE transaksi.id = $id");
$master = mysqli_fetch_assoc($qm);

if (!$master) {
    die("Transaksi tidak ditemukan. <a href='index.php'>Kembali</a>");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detail Transaksi</title>
</head>
<body>
    <h1>Master Detail Transaksi</h1>
    <?php include "menu.php"; ?>

    <!-- PILIH MASTER + TOMBOL LOAD (data detail muncul sesuai master yang dipilih) -->
    <form method="get">
        Pilih Transaksi:
        <select name="id">
            <?php
            $qlist = mysqli_query($koneksi, "SELECT transaksi.id, transaksi.waktu_transaksi, pelanggan.nama
                                             FROM transaksi
                                             INNER JOIN pelanggan ON transaksi.pelanggan_id = pelanggan.id
                                             ORDER BY transaksi.id");
            while ($l = mysqli_fetch_assoc($qlist)) {
                $pilih = ($l['id'] == $id) ? "selected" : "";
                echo "<option value='" . $l['id'] . "' $pilih>#" . $l['id'] . " - "
                     . $l['waktu_transaksi'] . " - " . $l['nama'] . "</option>";
            }
            ?>
        </select>
        <button type="submit">Load</button>
    </form>

    <!-- MASTER -->
    <h3>Data Transaksi (Master)</h3>
    <table>
        <tr><td>ID Transaksi</td><td>: <?php echo $master['id']; ?></td></tr>
        <tr><td>Tanggal</td><td>: <?php echo $master['waktu_transaksi']; ?></td></tr>
        <tr><td>Pelanggan</td><td>: <?php echo $master['nama']; ?></td></tr>
        <tr><td>Keterangan</td><td>: <?php echo $master['keterangan']; ?></td></tr>
        <tr><td>Total</td><td>: <b>Rp <?php echo number_format($master['total'], 0, ',', '.'); ?></b></td></tr>
    </table>

    <!-- FORM INPUT DETAIL -->
    <h3>Tambah Barang</h3>
    <?php
    if ($pesan != "") {
        echo "<p style='color:red'>$pesan</p>";
    }

    // barang yang sudah masuk detail transaksi ini TIDAK ditampilkan di dropdown
    $qbarang = mysqli_query($koneksi, "SELECT * FROM barang
                                       WHERE id NOT IN (SELECT barang_id FROM transaksi_detail
                                                        WHERE transaksi_id = $id)");

    if (mysqli_num_rows($qbarang) == 0) {
        echo "<p>Semua barang sudah dimasukkan ke transaksi ini.</p>";
    } else {
    ?>
    <form method="post">
        <select name="barang_id" required>
            <option value="">-- Pilih Barang --</option>
            <?php
            while ($b = mysqli_fetch_assoc($qbarang)) {
                echo "<option value='" . $b['id'] . "'>" . $b['kode_barang'] . " - " . $b['nama_barang']
                     . " (Rp " . number_format($b['harga'], 0, ',', '.') . ")</option>";
            }
            ?>
        </select>
        Qty: <input type="number" name="qty" min="1" value="1" required>
        <button type="submit" name="simpan_detail">Simpan</button>
    </form>
    <?php } ?>

    <!-- DETAIL -->
    <h3>Detail Barang</h3>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>
        <?php
        $no = 1;
        $qd = mysqli_query($koneksi, "SELECT transaksi_detail.*, barang.kode_barang, barang.nama_barang
                                      FROM transaksi_detail
                                      INNER JOIN barang ON transaksi_detail.barang_id = barang.id
                                      WHERE transaksi_detail.transaksi_id = $id");
        while ($d = mysqli_fetch_assoc($qd)) {
            $subtotal = $d['harga'] * $d['qty'];
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $d['kode_barang'] . "</td>";
            echo "<td>" . $d['nama_barang'] . "</td>";
            echo "<td>Rp " . number_format($d['harga'], 0, ',', '.') . "</td>";
            echo "<td>" . $d['qty'] . "</td>";
            echo "<td>Rp " . number_format($subtotal, 0, ',', '.') . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <p><a href="index.php">&laquo; Kembali ke daftar transaksi</a></p>
</body>
</html>
