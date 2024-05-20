-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2024 at 03:40 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Database: `db_diagnotani`

-- Table structure for table `tb_account`
CREATE TABLE `tb_account` (
  `id_user` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(55) NOT NULL,
  `job_desk` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `tb_account`
INSERT INTO `tb_account` (`id_user`, `username`, `email`, `job_desk`, `password`, `role`) VALUES
('USR001', 'gavra', 'gavra@gmail.com', 'backend', 'halohalobandung', 'user');

-- Table structure for table `tb_gejala`
CREATE TABLE `tb_gejala` (
  `id_gejala` varchar(50) NOT NULL,
  `gejala` varchar(255) NOT NULL,
  `mb` float NOT NULL,
  `md` float NOT NULL,
  PRIMARY KEY (`id_gejala`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO tb_gejala (id_gejala, gejala, mb, md) VALUES
('G001', 'Terdapat bercak kecil berbentuk oval pada daun', 0.6, 0.2),
('G002', 'Terdapat bercak memanjang berbentuk ellips pada daun', 0.8, 0.1),
('G003', 'Daun berwarna hijau keabu-abuan', 0.9, 0.1),
('G004', 'Daun berwarna coklat', 0.8, 0.2),
('G005', 'Terdapat bercak berwarna kemerahan pada palepah daun', 0.9, 0.1),
('G006', 'Terdapat bercak berwarna keabu-abuan pada palepah daun', 0.8, 0.4),
('G007', 'Terdapat sklerotium berwarna putih atau coklat', 0.8, 0.1),
('G008', 'Ada warna khorofil memanjang sejajar tulang daun', 0.7, 0.2),
('G009', 'Terdapat bercak berwarna putih', 0.9, 0.2),
('G010', 'Pertumbuhan jagung terhambat', 1.0, 0.1),
('G011', 'Daun menggulung', 0.7, 0.3),
('G012', 'Kelobot saling menempel erat pada tongkol', 0.8, 0.1),
('G013', 'Buah berwarna biru hitam di permukaan kelobot maupun tongkol', 0.9, 0.1),
('G014', 'Pangkal batang berwarna hijau atau coklat', 0.6, 0.2),
('G015', 'Bagian dalam batang busuk', 0.8, 0.4),
('G016', 'Batang mudah rebah', 0.8, 0.3),
('G017', 'Kulit luar batang tipis', 0.8, 0.2),
('G018', 'Batang berwarna merah jambu', 0.7, 0.3),
('G019', 'Batang berwarna merah kecoklatan', 0.5, 0.2);

-- Table structure for table `tb_history`
-- CREATE TABLE `tb_history` (
--   `id_history` int(11) NOT NULL AUTO_INCREMENT,
--   `id_user` varchar(50) NOT NULL,
--   `id_tb_penyakit_gejala` varchar(50) NOT NULL,
--   PRIMARY KEY (`id_history`),
--   FOREIGN KEY (`id_user`) REFERENCES `tb_account`(`id_user`),
--   FOREIGN KEY (`id_tb_penyakit_gejala`) REFERENCES `tb_penyakit_gejala`(`id_gejala_penyakit`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `tb_penyakit`
CREATE TABLE `tb_penyakit` (
  `id_penyakit` varchar(50) NOT NULL,
  `nama_penyakit` varchar(255) NOT NULL,
  PRIMARY KEY (`id_penyakit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO tb_penyakit (id_penyakit, nama_penyakit) VALUES
('P001','Hawar Daun'),
('P002','Busuk Palepah'),
('P003','Bulai'),
('P004','Busuk Tongkol'),
('P005','Busuk Batang');  

-- Table structure for table `tb_penyakit_gejala`
CREATE TABLE `tb_penyakit_gejala` (
  `id_gejala_penyakit` varchar(50) NOT NULL,
  `id_penyakit` varchar(50) NOT NULL,
  `id_gejala` varchar(50) NOT NULL,
  PRIMARY KEY (`id_gejala_penyakit`),
  FOREIGN KEY (`id_penyakit`) REFERENCES `tb_penyakit`(`id_penyakit`),
  FOREIGN KEY (`id_gejala`) REFERENCES `tb_gejala`(`id_gejala`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Hawar Daun
INSERT INTO tb_penyakit_gejala (id_gejala_penyakit, id_penyakit, id_gejala) VALUES 
('PG001', 'P001', 'G001'),   
('PG002', 'P001', 'G002'), 
('PG003', 'P001', 'G003'), 
('PG004', 'P001', 'G004'); 

-- Busuk Palepah
INSERT INTO tb_penyakit_gejala (id_gejala_penyakit, id_penyakit, id_gejala) VALUES 
('PG005', 'P002', 'G005'), 
('PG006', 'P002', 'G006'), 
('PG007', 'P002', 'G007');

-- Bulai
INSERT INTO tb_penyakit_gejala (id_gejala_penyakit, id_penyakit, id_gejala) VALUES 
('PG008', 'P003', 'G008'), 
('PG009', 'P003', 'G009'), 
('PG010', 'P003', 'G010'), 
('PG011', 'P003', 'G011');

-- Busuk Tongkol
INSERT INTO tb_penyakit_gejala (id_gejala_penyakit, id_penyakit, id_gejala) VALUES 
('PG012', 'P004', 'G012'), 
('PG013', 'P004', 'G013');

-- Busuk Batang
INSERT INTO tb_penyakit_gejala (id_gejala_penyakit, id_penyakit, id_gejala) VALUES 
('PG014', 'P005', 'G014'), 
('PG015', 'P005', 'G015'), 
('PG016', 'P005', 'G016'), 
('PG017', 'P005', 'G017'), 
('PG018', 'P005', 'G018'), 
('PG019', 'P005', 'G019');

COMMIT;
