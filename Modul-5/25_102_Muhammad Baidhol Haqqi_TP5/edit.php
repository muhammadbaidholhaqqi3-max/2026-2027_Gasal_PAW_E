<?php include 'koneksi.php';
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $st = mysqli_prepare($conn, "UPDATE supplier SET nama=?, telp=?, alamat=? WHERE id=?");
  mysqli_stmt_bind_param($st, "sssi", $_POST['nama'], $_POST['telp'], $_POST['alamat'], $id);
  mysqli_stmt_execute($st);
  header("Location: index.php?pesan=" . urlencode("Data supplier berhasil diupdate")); exit;
}
$r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM supplier WHERE id=$id"));
if (!$r) { header("Location: index.php?pesan=" . urlencode("Data tidak ditemukan")); exit; }
include 'header.php'; ?>
<h2>Edit Data Master Supplier</h2><hr>
<form method="post" class="form-horizontal">
  <input type="hidden" name="id" value="<?= $r['id'] ?>">
  <div class="form-group"><label class="col-sm-3 control-label">Nama</label>
    <div class="col-sm-6"><input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($r['nama']) ?>" maxlength="100" required></div></div>
  <div class="form-group"><label class="col-sm-3 control-label">Telp</label>
    <div class="col-sm-4"><input type="text" name="telp" class="form-control" value="<?= htmlspecialchars($r['telp']) ?>" maxlength="12" required></div></div>
  <div class="form-group"><label class="col-sm-3 control-label">Alamat</label>
    <div class="col-sm-4"><input type="text" name="alamat" class="form-control" value="<?= htmlspecialchars($r['alamat']) ?>" required></div></div>
  <div class="form-group"><div class="col-sm-offset-3 col-sm-6">
    <button type="submit" class="btn btn-success">Update</button>
    <a href="index.php" class="btn btn-danger">Batal</a></div></div>
</form></div></body></html>
