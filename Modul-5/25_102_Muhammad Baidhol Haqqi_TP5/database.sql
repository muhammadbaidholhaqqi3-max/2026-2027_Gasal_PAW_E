CREATE DATABASE IF NOT EXISTS penjualan;
USE penjualan;

CREATE TABLE supplier (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100),
  telp VARCHAR(12),
  alamat TEXT
) ENGINE=InnoDB;

CREATE TABLE barang (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  kode_barang VARCHAR(10),
  nama_barang VARCHAR(100),
  harga INT(11),
  stok INT(11),
  supplier_id INT(11),
  FOREIGN KEY (supplier_id) REFERENCES supplier(id)
) ENGINE=InnoDB;

-- Diagram memakai varchar(20) untuk id pelanggan, jadi tidak bisa AUTO_INCREMENT (isi manual, mis. PLG001)
CREATE TABLE pelanggan (
  id VARCHAR(20) NOT NULL PRIMARY KEY,
  nama VARCHAR(100),
  jenis_kelamin ENUM('L','P'),
  telp VARCHAR(12),
  alamat TEXT
) ENGINE=InnoDB;

CREATE TABLE transaksi (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  waktu_transaksi DATE,
  keterangan TEXT,
  total INT(11),
  pelanggan_id VARCHAR(20),
  FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id)
) ENGINE=InnoDB;

CREATE TABLE transaksi_detail (
  transaksi_id INT(11) NOT NULL,
  barang_id INT(11) NOT NULL,
  harga INT(11),
  qty INT(11),
  PRIMARY KEY (transaksi_id, barang_id),
  FOREIGN KEY (transaksi_id) REFERENCES transaksi(id),
  FOREIGN KEY (barang_id) REFERENCES barang(id)
) ENGINE=InnoDB;

CREATE TABLE pembayaran (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  waktu_bayar DATETIME,
  total INT(11),
  metode ENUM('TUNAI','TRANSFER','EDC'),
  transaksi_id INT(11) NOT NULL,
  FOREIGN KEY (transaksi_id) REFERENCES transaksi(id)
) ENGINE=InnoDB;

CREATE TABLE user (
  id_user TINYINT(2) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(30),
  password VARCHAR(35),
  nama VARCHAR(50),
  alamat VARCHAR(150),
  hp VARCHAR(20),
  level TINYINT(1)
) ENGINE=InnoDB;

-- ===== DATA (minimal 10 per tabel) =====
INSERT INTO supplier (nama, telp, alamat) VALUES
('PT. Maju Bersama','08123456','Surabaya'),
('PT. Senang Sekali','081515563','Bangkalan'),
('PT. Segar Segar','0845454663','Surabaya'),
('CV. Sumber Rejeki','081234567890','Sidoarjo'),
('PT. Jaya Abadi','085711223344','Gresik'),
('CV. Makmur Sentosa','087855667788','Malang'),
('PT. Berkah Niaga','081322334455','Mojokerto'),
('CV. Sejahtera','082144556677','Pasuruan'),
('PT. Indah Pangan','081998877665','Surabaya'),
('PT. Harapan Baru','085233445566','Lamongan');;

INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES
('BRG001','Beras 5 Kg',65000,50,4),
('BRG002','Minyak Goreng 2 L',34000,40,5),
('BRG003','Gula Pasir 1 Kg',16000,60,6),
('BRG004','Telur Ayam 1 Kg',28000,30,7),
('BRG005','Kopi Bubuk 200 g',22000,45,8),
('BRG006','Teh Celup 25 pcs',8000,80,9),
('BRG007','Susu UHT 1 L',18000,35,10),
('BRG008','Mie Instan',3500,200,4),
('BRG009','Sabun Mandi',4500,100,5),
('BRG010','Sikat Gigi',12000,70,6);

INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES
('PLG001','Budi Santoso','L','081211110001','Surabaya'),
('PLG002','Siti Aminah','P','081211110002','Sidoarjo'),
('PLG003','Agus Prasetyo','L','081211110003','Gresik'),
('PLG004','Dewi Lestari','P','081211110004','Malang'),
('PLG005','Rizky Ramadhan','L','081211110005','Surabaya'),
('PLG006','Nurul Hidayah','P','081211110006','Bangkalan'),
('PLG007','Eko Wahyudi','L','081211110007','Mojokerto'),
('PLG008','Fitriani','P','081211110008','Pasuruan'),
('PLG009','Hendra Wijaya','L','081211110009','Lamongan'),
('PLG010','Maya Sari','P','081211110010','Surabaya');

INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES
('2026-10-01','Pembelian beras',65000,'PLG001'),
('2026-10-02','Pembelian minyak',68000,'PLG002'),
('2026-10-03','Pembelian gula',48000,'PLG003'),
('2026-10-04','Pembelian telur',28000,'PLG004'),
('2026-10-05','Pembelian kopi',44000,'PLG005'),
('2026-10-06','Pembelian teh',40000,'PLG006'),
('2026-10-07','Pembelian susu',36000,'PLG007'),
('2026-10-08','Pembelian mie instan',35000,'PLG008'),
('2026-10-09','Pembelian sabun',18000,'PLG009'),
('2026-10-10','Pembelian sikat gigi',36000,'PLG010');

INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES
(1,1,65000,1),(2,2,34000,2),(3,3,16000,3),(4,4,28000,1),(5,5,22000,2),
(6,6,8000,5),(7,7,18000,2),(8,8,3500,10),(9,9,4500,4),(10,10,12000,3);

INSERT INTO pembayaran (waktu_bayar, total, metode, transaksi_id) VALUES
('2026-10-01 09:10:00',65000,'TUNAI',1),
('2026-10-02 10:20:00',68000,'TRANSFER',2),
('2026-10-03 11:05:00',48000,'EDC',3),
('2026-10-04 13:40:00',28000,'TUNAI',4),
('2026-10-05 14:15:00',44000,'TRANSFER',5),
('2026-10-06 15:30:00',40000,'EDC',6),
('2026-10-07 16:00:00',36000,'TUNAI',7),
('2026-10-08 08:50:00',35000,'TUNAI',8),
('2026-10-09 12:25:00',18000,'TRANSFER',9),
('2026-10-10 17:45:00',36000,'EDC',10);

INSERT INTO user (username, password, nama, alamat, hp, level) VALUES
('admin',MD5('admin123'),'Administrator','Surabaya','081300000001',1),
('kasir1',MD5('kasir123'),'Andi Kasir','Surabaya','081300000002',2),
('kasir2',MD5('kasir123'),'Rina Kasir','Sidoarjo','081300000003',2),
('gudang1',MD5('gudang123'),'Joko Gudang','Gresik','081300000004',3),
('gudang2',MD5('gudang123'),'Lina Gudang','Malang','081300000005',3),
('owner',MD5('owner123'),'Pak Owner','Surabaya','081300000006',1),
('staff1',MD5('staff123'),'Tono Staff','Mojokerto','081300000007',2),
('staff2',MD5('staff123'),'Sari Staff','Pasuruan','081300000008',2),
('staff3',MD5('staff123'),'Doni Staff','Lamongan','081300000009',2),
('staff4',MD5('staff123'),'Wati Staff','Bangkalan','081300000010',2);
