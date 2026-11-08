-- Database penjualan (sesuai diagram)
CREATE DATABASE IF NOT EXISTS penjualan;
USE penjualan;

DROP TABLE IF EXISTS pembayaran;
DROP TABLE IF EXISTS transaksi_detail;
DROP TABLE IF EXISTS transaksi;
DROP TABLE IF EXISTS barang;
DROP TABLE IF EXISTS pelanggan;
DROP TABLE IF EXISTS supplier;
DROP TABLE IF EXISTS user;

CREATE TABLE supplier (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  telp VARCHAR(12),
  alamat TEXT
) ENGINE=InnoDB;

CREATE TABLE barang (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode_barang VARCHAR(10) NOT NULL UNIQUE,
  nama_barang VARCHAR(100) NOT NULL,
  harga INT NOT NULL,
  stok INT NOT NULL,
  supplier_id INT NOT NULL,
  FOREIGN KEY (supplier_id) REFERENCES supplier(id)
) ENGINE=InnoDB;

CREATE TABLE pelanggan (
  id VARCHAR(20) PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  jenis_kelamin ENUM('L','P') NOT NULL,
  telp VARCHAR(12),
  alamat TEXT
) ENGINE=InnoDB;

CREATE TABLE transaksi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  waktu_transaksi DATE NOT NULL,
  keterangan TEXT,
  total INT NOT NULL DEFAULT 0,
  pelanggan_id VARCHAR(20) NOT NULL,
  FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id)
) ENGINE=InnoDB;

-- PK gabungan (transaksi_id, barang_id) -> barang yang sama tidak bisa 2x dalam 1 transaksi
CREATE TABLE transaksi_detail (
  transaksi_id INT NOT NULL,
  barang_id INT NOT NULL,
  harga INT NOT NULL,
  qty INT NOT NULL,
  PRIMARY KEY (transaksi_id, barang_id),
  FOREIGN KEY (transaksi_id) REFERENCES transaksi(id) ON DELETE CASCADE,
  FOREIGN KEY (barang_id) REFERENCES barang(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE pembayaran (
  id INT AUTO_INCREMENT PRIMARY KEY,
  waktu_bayar DATETIME NOT NULL,
  total INT NOT NULL,
  metode ENUM('TUNAI','TRANSFER','EDC') NOT NULL,
  transaksi_id INT NOT NULL,
  FOREIGN KEY (transaksi_id) REFERENCES transaksi(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE user (
  id_user TINYINT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(30) NOT NULL UNIQUE,
  password VARCHAR(35) NOT NULL,
  nama VARCHAR(50),
  alamat VARCHAR(150),
  hp VARCHAR(20),
  level TINYINT(1) NOT NULL
) ENGINE=InnoDB;

-- ===== DATA =====
INSERT INTO supplier (nama, telp, alamat) VALUES
('PT Sumber Pangan', '031555001', 'Surabaya'),
('CV Maju Jaya', '031555002', 'Sidoarjo'),
('UD Tani Makmur', '031555003', 'Malang'),
('PT Indo Sembako', '031555004', 'Gresik'),
('CV Berkah Abadi', '031555005', 'Bangkalan');

INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES
('BRG001', 'Beras 5kg', 65000, 50, 1),
('BRG002', 'Minyak Goreng 2L', 34000, 40, 2),
('BRG003', 'Gula Pasir 1kg', 16000, 60, 1),
('BRG004', 'Telur 1kg', 28000, 30, 3),
('BRG005', 'Mie Instan', 3500, 200, 4),
('BRG006', 'Kopi Sachet', 12000, 80, 5);

INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES
('PLG001', 'Budi Santoso', 'L', '081234567801', 'Bangkalan'),
('PLG002', 'Siti Aminah', 'P', '081234567802', 'Surabaya'),
('PLG003', 'Andi Pratama', 'L', '081234567803', 'Sampang'),
('PLG004', 'Dewi Lestari', 'P', '081234567804', 'Pamekasan'),
('PLG005', 'Rina Wulandari', 'P', '081234567805', 'Sumenep');

INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES
('2026-10-01', 'Belanja bulanan', 0, 'PLG001'),
('2026-10-02', 'Belanja harian', 0, 'PLG002'),
('2026-10-03', 'Beli sembako', 0, 'PLG003'),
('2026-10-05', 'Belanja warung', 0, 'PLG004'),
('2026-10-07', 'Belanja bulanan', 0, 'PLG005'),
('2026-10-09', 'Beli kebutuhan dapur', 0, 'PLG001'),
('2026-10-12', 'Belanja harian', 0, 'PLG002'),
('2026-10-15', 'Beli sembako', 0, 'PLG003'),
('2026-10-18', 'Belanja warung', 0, 'PLG004'),
('2026-10-20', 'Belanja bulanan', 0, 'PLG005');

INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES
(1, 1, 65000, 2), (1, 2, 34000, 1),
(2, 3, 16000, 3), (2, 5, 3500, 10),
(3, 1, 65000, 1), (3, 4, 28000, 2),
(4, 5, 3500, 20), (4, 6, 12000, 5),
(5, 2, 34000, 2), (5, 3, 16000, 2),
(6, 4, 28000, 1), (6, 6, 12000, 3),
(7, 1, 65000, 1), (7, 5, 3500, 5),
(8, 2, 34000, 1), (8, 3, 16000, 1),
(9, 4, 28000, 3), (9, 5, 3500, 15),
(10, 1, 65000, 3), (10, 6, 12000, 2);

-- hitung total tiap transaksi dari detailnya
UPDATE transaksi t SET total =
  (SELECT SUM(d.harga * d.qty) FROM transaksi_detail d WHERE d.transaksi_id = t.id);

INSERT INTO pembayaran (waktu_bayar, total, metode, transaksi_id)
SELECT CONCAT(waktu_transaksi, ' 10:00:00'), total, 'TUNAI', id FROM transaksi;

INSERT INTO user (username, password, nama, alamat, hp, level) VALUES
('admin', MD5('admin123'), 'Administrator', 'Bangkalan', '081200000001', 1),
('kasir', MD5('kasir123'), 'Kasir Toko', 'Bangkalan', '081200000002', 2);
