<?php
include "koneksi.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Barang</title>
</head>
<body>
    <h1>Data Barang</h1>
    <?php include "menu.php"; ?>

    <?php
    // tampilkan notifikasi dari hapus_barang.php
    if (isset($_GET['pesan'])) {
        if ($_GET['pesan'] == "dipakai") {
            echo "<p style='color:red'>Barang tidak bisa dihapus karena masih digunakan pada transaksi!</p>";
        } elseif ($_GET['pesan'] == "terhapus") {
            echo "<p style='color:green'>Data barang berhasil dihapus.</p>";
        }
    }
    ?>

    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Supplier</th>
            <th>Aksi</th>
        </tr>
        <?php
        $no = 1;
        $query = mysqli_query($koneksi, "SELECT barang.*, supplier.nama AS nama_supplier
                                         FROM barang
                                         LEFT JOIN supplier ON barang.supplier_id = supplier.id");
        while ($row = mysqli_fetch_assoc($query)) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $row['kode_barang'] . "</td>";
            echo "<td>" . $row['nama_barang'] . "</td>";
            echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
            echo "<td>" . $row['stok'] . "</td>";
            echo "<td>" . $row['nama_supplier'] . "</td>";
            echo "<td><a href='hapus_barang.php?id=" . $row['id'] . "' onclick=\"return confirm('Yakin hapus barang ini?')\">Hapus</a></td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>
