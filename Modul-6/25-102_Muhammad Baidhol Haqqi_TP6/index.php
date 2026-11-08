<?php
include "koneksi.php";

// simpan transaksi baru (master)
if (isset($_POST['simpan'])) {
    $tanggal    = $_POST['waktu_transaksi'];
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    $pelanggan  = $_POST['pelanggan_id'];

    mysqli_query($koneksi, "INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id)
                            VALUES ('$tanggal', '$keterangan', 0, '$pelanggan')");
    $id_baru = mysqli_insert_id($koneksi);

    // langsung ke halaman detail transaksi yang baru dibuat
    header("Location: detail.php?id=" . $id_baru);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Transaksi</title>
</head>
<body>
    <h1>Data Transaksi (Master)</h1>
    <?php include "menu.php"; ?>

    <h3>Tambah Transaksi</h3>
    <form method="post">
        <table>
            <tr>
                <td>Tanggal</td>
                <td><input type="date" name="waktu_transaksi" value="<?php echo date('Y-m-d'); ?>" required></td>
            </tr>
            <tr>
                <td>Pelanggan</td>
                <td>
                    <select name="pelanggan_id" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        <?php
                        $q = mysqli_query($koneksi, "SELECT * FROM pelanggan");
                        while ($p = mysqli_fetch_assoc($q)) {
                            echo "<option value='" . $p['id'] . "'>" . $p['nama'] . "</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td><input type="text" name="keterangan"></td>
            </tr>
            <tr>
                <td></td>
                <td><button type="submit" name="simpan">Simpan &amp; Isi Detail</button></td>
            </tr>
        </table>
    </form>

    <h3>Daftar Transaksi</h3>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>No</th>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Keterangan</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
        <?php
        $no = 1;
        $query = mysqli_query($koneksi, "SELECT transaksi.*, pelanggan.nama
                                         FROM transaksi
                                         INNER JOIN pelanggan ON transaksi.pelanggan_id = pelanggan.id
                                         ORDER BY transaksi.id DESC");
        while ($row = mysqli_fetch_assoc($query)) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['waktu_transaksi'] . "</td>";
            echo "<td>" . $row['nama'] . "</td>";
            echo "<td>" . $row['keterangan'] . "</td>";
            echo "<td>Rp " . number_format($row['total'], 0, ',', '.') . "</td>";
            echo "<td><a href='detail.php?id=" . $row['id'] . "'>Lihat Detail</a></td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>
