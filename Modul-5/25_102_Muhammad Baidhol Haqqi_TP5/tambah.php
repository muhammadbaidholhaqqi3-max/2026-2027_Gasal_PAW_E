<?php include 'koneksi.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $st = mysqli_prepare($conn, "INSERT INTO supplier (nama, telp, alamat) VALUES (?,?,?)");
  mysqli_stmt_bind_param($st, "sss", $_POST['nama'], $_POST['telp'], $_POST['alamat']);
  mysqli_stmt_execute($st);
  header("Location: index.php?pesan=" . urlencode("Data supplier berhasil ditambahkan")); exit;
}
include 'header.php'; ?>
<h2>Tambah Data Master Supplier Baru</h2><hr>
<form method="post" class="form-horizontal">
  <div class="form-group"><label class="col-sm-3 control-label">Nama</label>
    <div class="col-sm-6"><input type="text" name="nama" class="form-control" placeholder="Nama" maxlength="100" required></div></div>
  <div class="form-group"><label class="col-sm-3 control-label">Telp</label>
    <div class="col-sm-4"><input type="text" name="telp" class="form-control" placeholder="telp" maxlength="12" required></div></div>
  <div class="form-group"><label class="col-sm-3 control-label">Alamat</label>
    <div class="col-sm-4"><input type="text" name="alamat" class="form-control" placeholder="alamat" required></div></div>
  <div class="form-group"><div class="col-sm-offset-3 col-sm-6">
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="index.php" class="btn btn-danger">Batal</a></div></div>
</form></div></body></html>
