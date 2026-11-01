<?php include 'koneksi.php'; include 'header.php';
$pesan = $_GET['pesan'] ?? '';
$q = mysqli_query($conn, "SELECT * FROM supplier ORDER BY id");
?>
<a href="tambah.php" class="btn btn-success pull-right">Tambah Data</a>
<h2>Data Master Supplier</h2><hr>
<?php if ($pesan) echo '<div class="alert alert-info">'.htmlspecialchars($pesan).'</div>'; ?>
<table class="table table-bordered table-striped">
<thead><tr><th>No</th><th>Nama</th><th>Telp</th><th>Alamat</th><th>Tindakan</th></tr></thead>
<tbody>
<?php $no = 1; while ($r = mysqli_fetch_assoc($q)) { ?>
<tr>
  <td><?= $no++ ?></td>
  <td><?= htmlspecialchars($r['nama']) ?></td>
  <td><?= htmlspecialchars($r['telp']) ?></td>
  <td><?= htmlspecialchars($r['alamat']) ?></td>
  <td>
    <a href="edit.php?id=<?= $r['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
    <a href="hapus.php?id=<?= $r['id'] ?>" class="btn btn-danger btn-sm"
       onclick="return confirm('Anda yakin akan menghapus supplier ini?')">Hapus</a>
  </td>
</tr>
<?php } ?>
</tbody></table></div></body></html>
