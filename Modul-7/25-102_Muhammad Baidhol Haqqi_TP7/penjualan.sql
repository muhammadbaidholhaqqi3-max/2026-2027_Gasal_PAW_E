-- Database penjualan (sesuai skema pada Modul 7 PAW)
-- Cara import: phpMyAdmin -> tab Import -> pilih file ini -> Go

CREATE DATABASE IF NOT EXISTS penjualan CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE penjualan;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS pembayaran, transaksi_detail, transaksi, pelanggan, barang, supplier, user;
SET FOREIGN_KEY_CHECKS = 1;

-- ---------- STRUKTUR ----------
CREATE TABLE supplier (
  id     INT NOT NULL AUTO_INCREMENT,
  nama   VARCHAR(100) NOT NULL,
  telp   VARCHAR(12),
  alamat TEXT,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE barang (
  id          INT NOT NULL AUTO_INCREMENT,
  kode_barang VARCHAR(10) NOT NULL,
  nama_barang VARCHAR(100) NOT NULL,
  harga       INT NOT NULL,
  stok        INT NOT NULL,
  supplier_id INT NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (kode_barang),
  FOREIGN KEY (supplier_id) REFERENCES supplier(id)
) ENGINE=InnoDB;

CREATE TABLE pelanggan (
  id            VARCHAR(20) NOT NULL,
  nama          VARCHAR(100) NOT NULL,
  jenis_kelamin ENUM('L','P') NOT NULL,
  telp          VARCHAR(12),
  alamat        TEXT,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE transaksi (
  id               INT NOT NULL AUTO_INCREMENT,
  waktu_transaksi  DATE NOT NULL,
  keterangan       TEXT,
  total            INT NOT NULL,
  pelanggan_id     VARCHAR(20) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id)
) ENGINE=InnoDB;

CREATE TABLE transaksi_detail (
  transaksi_id INT NOT NULL,
  barang_id    INT NOT NULL,
  harga        INT NOT NULL,
  qty          INT NOT NULL,
  PRIMARY KEY (transaksi_id, barang_id),
  FOREIGN KEY (transaksi_id) REFERENCES transaksi(id),
  FOREIGN KEY (barang_id) REFERENCES barang(id)
) ENGINE=InnoDB;

CREATE TABLE pembayaran (
  id           INT NOT NULL AUTO_INCREMENT,
  waktu_bayar  DATETIME NOT NULL,
  total        INT NOT NULL,
  metode       ENUM('TUNAI','TRANSFER','EDC') NOT NULL,
  transaksi_id INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (transaksi_id) REFERENCES transaksi(id)
) ENGINE=InnoDB;

CREATE TABLE user (
  id_user  TINYINT NOT NULL AUTO_INCREMENT,
  username VARCHAR(30) NOT NULL,
  password VARCHAR(35) NOT NULL,
  nama     VARCHAR(50),
  alamat   VARCHAR(150),
  hp       VARCHAR(20),
  level    TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id_user),
  UNIQUE KEY (username)
) ENGINE=InnoDB;

-- ---------- DATA CONTOH ----------
INSERT INTO supplier (id, nama, telp, alamat) VALUES
(1, 'PT Sumber Pangan', '031555101', 'Jl. Rungkut Industri No. 5, Surabaya'),
(2, 'CV Maju Jaya', '031555202', 'Jl. Darmo Permai No. 12, Surabaya');

INSERT INTO barang (id, kode_barang, nama_barang, harga, stok, supplier_id) VALUES
(1, 'BRG001', 'Minyak Goreng 1L', 13000, 100, 1),
(2, 'BRG002', 'Telur Ayam 1kg',   26000, 80,  2),
(3, 'BRG003', 'Kopi Sachet',       6500, 200, 1);

INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES
('P001', 'Andi Pratama',   'L', '081200000001', 'Surabaya'),
('P002', 'Siti Aminah',    'P', '081200000002', 'Sidoarjo'),
('P003', 'Budi Santoso',   'L', '081200000003', 'Surabaya'),
('P004', 'Dewi Lestari',   'P', '081200000004', 'Gresik'),
('P005', 'Rina Wulandari', 'P', '081200000005', 'Surabaya'),
('P006', 'Joko Susilo',    'L', '081200000006', 'Sidoarjo'),
('P007', 'Maya Sari',      'P', '081200000007', 'Surabaya'),
('P008', 'Agus Wijaya',    'L', '081200000008', 'Gresik'),
('P009', 'Fitri Handayani','P', '081200000009', 'Surabaya');

INSERT INTO transaksi (id, waktu_transaksi, keterangan, total, pelanggan_id) VALUES
(1,  '2026-11-01', 'Pembelian telur',            26000, 'P001'),
(2,  '2026-11-01', 'Pembelian minyak goreng',    13000, 'P002'),
(3,  '2026-11-02', 'Pembelian telur 2kg',        52000, 'P003'),
(4,  '2026-11-03', 'Pembelian minyak goreng',    13000, 'P004'),
(5,  '2026-11-04', 'Pembelian minyak goreng',    13000, 'P009'),
(6,  '2026-11-05', 'Pembelian kopi sachet 3',    19500, 'P005'),
(7,  '2026-11-05', 'Pembelian kopi sachet 1',     6500, 'P006'),
(8,  '2026-11-06', 'Pembelian minyak goreng',    13000, 'P007'),
(9,  '2026-11-09', 'Pembelian minyak goreng',    13000, 'P008'),
(10, '2026-11-10', 'Pembelian telur',            26000, 'P001'),
(11, '2026-11-07', 'Pembelian kopi sachet 2',    13000, 'P002'),
(12, '2026-11-08', 'Pembelian telur',            26000, 'P003'),
(13, '2026-11-11', 'Pembelian minyak goreng',    13000, 'P004'),
(14, '2026-11-11', 'Pembelian kopi sachet 2',    13000, 'P005'),
(15, '2026-11-12', 'Pembelian telur',            26000, 'P006'),
(16, '2026-11-13', 'Pembelian minyak goreng 2',  26000, 'P007'),
(17, '2026-11-14', 'Pembelian kopi sachet 4',    26000, 'P008'),
(18, '2026-11-15', 'Pembelian telur dan minyak', 39000, 'P009');

INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES
(1, 2, 26000, 1),
(2, 1, 13000, 1),
(3, 2, 26000, 2),
(4, 1, 13000, 1),
(5, 1, 13000, 1),
(6, 3,  6500, 3),
(7, 3,  6500, 1),
(8, 1, 13000, 1),
(9, 1, 13000, 1),
(10, 2, 26000, 1),
(11, 3,  6500, 2),
(12, 2, 26000, 1),
(13, 1, 13000, 1),
(14, 3,  6500, 2),
(15, 2, 26000, 1),
(16, 1, 13000, 2),
(17, 3,  6500, 4),
(18, 2, 26000, 1),
(18, 1, 13000, 1);

INSERT INTO pembayaran (waktu_bayar, total, metode, transaksi_id) VALUES
('2026-11-01 09:10:00', 26000, 'TUNAI',    1),
('2026-11-01 11:20:00', 13000, 'TRANSFER', 2),
('2026-11-02 10:05:00', 52000, 'EDC',      3),
('2026-11-03 13:40:00', 13000, 'TUNAI',    4),
('2026-11-04 15:15:00', 13000, 'TUNAI',    5),
('2026-11-05 08:55:00', 19500, 'TRANSFER', 6),
('2026-11-05 14:30:00',  6500, 'TUNAI',    7),
('2026-11-06 16:00:00', 13000, 'EDC',      8),
('2026-11-09 12:25:00', 13000, 'TUNAI',    9),
('2026-11-10 10:45:00', 26000, 'TRANSFER', 10),
('2026-11-07 09:30:00', 13000, 'TUNAI',    11),
('2026-11-08 13:10:00', 26000, 'TRANSFER', 12),
('2026-11-11 10:00:00', 13000, 'TUNAI',    13),
('2026-11-11 15:20:00', 13000, 'EDC',      14),
('2026-11-12 11:45:00', 26000, 'TUNAI',    15),
('2026-11-13 14:05:00', 26000, 'TRANSFER', 16),
('2026-11-14 09:50:00', 26000, 'TUNAI',    17),
('2026-11-15 10:30:00', 39000, 'EDC',      18);

-- password 'admin' (MD5)
INSERT INTO user (username, password, nama, alamat, hp, level) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrator', 'Surabaya', '081200000000', 1);
