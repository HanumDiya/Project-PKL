-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2024 at 03:00 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `isp`
--

-- --------------------------------------------------------

--
-- Table structure for table `aksi`
--

CREATE TABLE `aksi` (
  `id` int(11) NOT NULL,
  `layanan_id` int(11) NOT NULL,
  `jenis_aksi_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `jenis_barang_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `brand_name`, `jenis_barang_id`) VALUES
(3, 'bearbrandok', 7);

-- --------------------------------------------------------

--
-- Table structure for table `dapel_satria`
--

CREATE TABLE `dapel_satria` (
  `id` int(11) NOT NULL,
  `inv` varchar(255) DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `Akta` varchar(255) DEFAULT NULL,
  `NPWP` varchar(100) DEFAULT NULL,
  `KTP` varchar(100) DEFAULT NULL,
  `DOMISILI` varchar(255) DEFAULT NULL,
  `NIB` varchar(100) DEFAULT NULL,
  `KEMEN_KAMHAM` varchar(255) DEFAULT NULL,
  `BIAYA_TOTAL` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_pelanggan`
--

CREATE TABLE `data_pelanggan` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `Akta` varchar(100) DEFAULT NULL,
  `NPWP` varchar(100) DEFAULT NULL,
  `KTP` varchar(100) DEFAULT NULL,
  `DOMISILI` varchar(255) DEFAULT NULL,
  `NIB` varchar(100) DEFAULT NULL,
  `KEMEN_KAMHAM` varchar(255) DEFAULT NULL,
  `BIAYA_TOTAL` varchar(255) DEFAULT NULL,
  `nama` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_pelanggan`
--

INSERT INTO `data_pelanggan` (`id`, `invoice_id`, `Akta`, `NPWP`, `KTP`, `DOMISILI`, `NIB`, `KEMEN_KAMHAM`, `BIAYA_TOTAL`, `nama`) VALUES
(24, NULL, '66ffeeed86e4a-Blue Purple Holographic Coming Soon Banner (2).png', '66ffeeed8748c-IMG-20230629-WA0275.jpeg.jpg', '66ffeeed87a15-Google Classroom Tutorial - Create a Student Self-Assessment using Google Forms.jpeg', 'nbv ', '98765467896', 'bcv', '56765', 'kbcsc'),
(25, NULL, '66fff55fcfcf9-Blue And Yellow Creative Playful New Student Admission Brochure (10 × 11 inci) (5 × 11', '66fff55fd51e0-Blue Purple Holographic Coming Soon Banner (2).png', '66fff55fd5898-Google Classroom Tutorial - Create a Student Self-Assessment using Google Forms.jpeg', 'jawa', '98765467896', 'nbv', '12399999', 'unyil'),
(26, NULL, '66fff57d919e6-Google Classroom Tutorial - Create a Student Self-Assessment using Google Forms.jpeg', '66fff57d927ea-Blue And Yellow Creative Playful New Student Admission Brochure (10 × 11 inci) (5 × 11', '66fff57d93031-IMG-20230108-WA0003.jpg', 'nbv ', '12345567891', 'hgfcxu', '12399999', 'iccigo'),
(27, NULL, '66fff5a96f898-Blue And Yellow Creative Playful New Student Admission Brochure (10 × 11 inci) (5 × 11', '66fff5a96ff6b-IMG-20230826-WA0007.jpeg.jpg', '66fff5a97139f-Blue Purple Holographic Coming Soon Banner (2).png', 'nbv ', '98765467896', 'nbv', '567659', 'iccigo'),
(28, NULL, '66fff60557499-Google Classroom Tutorial - Create a Student Self-Assessment using Google Forms.jpeg', '66fff60557cfe-Google Classroom Tutorial - Create a Student Self-Assessment using Google Forms.jpeg', '66fff6055845a-Blue And Yellow Creative Playful New Student Admission Brochure (10 × 11 inci) (5 × 11', 'nbv ', '98765467896', 'bcv', '12399999', 'Cintia'),
(29, NULL, '67063295e11f0-Cv.pdf', '67063295e795a-IMG-20230727-WA0130.jpg', '67063295eb70b-IMG-20230727-WA0127.jpg', 'jawa', '98765467896', 'vcx', '976900', 'Cintia'),
(30, NULL, '672055218a4c9-2.png', '672055218b1ec-2.png', '672055218bca8-1.png', 'jawa', '12345567891', 'nmax', '12399999', 'Cintia');

-- --------------------------------------------------------

--
-- Table structure for table `data_teknis`
--

CREATE TABLE `data_teknis` (
  `id` int(11) NOT NULL,
  `Nomor_ID` varchar(255) NOT NULL,
  `Nama_badan` varchar(255) DEFAULT NULL,
  `Alamat` text DEFAULT NULL,
  `mitro_E` varchar(255) DEFAULT NULL,
  `Kapasitas` varchar(255) DEFAULT NULL,
  `vlan` varchar(50) DEFAULT NULL,
  `IP_1_dst` varchar(50) DEFAULT NULL,
  `perangkat_1_dst` varchar(255) DEFAULT NULL,
  `akses` varchar(255) DEFAULT NULL,
  `RFS_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `datta_teknis`
--

CREATE TABLE `datta_teknis` (
  `id` int(11) NOT NULL,
  `metro_e` varchar(255) NOT NULL,
  `tipe` enum('trunk','access') NOT NULL,
  `kapasitas_mbps` decimal(10,2) NOT NULL,
  `history` text DEFAULT NULL,
  `ip1` varchar(15) NOT NULL,
  `ip2` varchar(15) NOT NULL,
  `vlan` int(11) NOT NULL,
  `sfp` enum('bidi','single_mood','multi_mood') NOT NULL,
  `kapasitas_gbps` decimal(10,2) NOT NULL,
  `jarak` decimal(10,2) NOT NULL,
  `perangkat` varchar(255) NOT NULL,
  `port1` varchar(255) NOT NULL,
  `port2` varchar(255) NOT NULL,
  `port3` varchar(255) NOT NULL,
  `status` enum('aset_satria','aset_klien') NOT NULL,
  `tanggal_aktivasi` date NOT NULL,
  `dokumen` varchar(255) NOT NULL,
  `nama` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `datta_teknis`
--

INSERT INTO `datta_teknis` (`id`, `metro_e`, `tipe`, `kapasitas_mbps`, `history`, `ip1`, `ip2`, `vlan`, `sfp`, `kapasitas_gbps`, `jarak`, `perangkat`, `port1`, `port2`, `port3`, `status`, `tanggal_aktivasi`, `dokumen`, `nama`) VALUES
(17, 'jeeessssokk', 'trunk', 1.00, NULL, '1234664', '765432', 8, 'bidi', 3.00, 0.00, 'laptop', '12342231210', '886', '', '', '2024-11-27', '1726536691_SuperProf_mk2 (2) (1) (1).pdf', '');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(10) UNSIGNED NOT NULL,
  `item_name` varchar(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `serial_number` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `condition_status` enum('good','damaged','in-use') DEFAULT 'good',
  `current_location` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` text NOT NULL,
  `jenis_barang` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `item_name`, `type`, `version`, `brand`, `serial_number`, `quantity`, `condition_status`, `current_location`, `created_at`, `description`, `jenis_barang`) VALUES
(7, 'mj', '9', '8', 'vf', '75', 3, '', NULL, '2024-10-25 09:06:04', '', 5),
(8, 'hanum', 'gf', '4', 'by', '54', 4, '', NULL, '2024-10-25 09:28:56', '', 5),
(17, 'm', 'n', '98', '3', '7', 8, 'in-use', 'hh', '2024-11-16 12:09:43', 'ngy', 7),
(18, 'b', '0', '6', '3', '76', 4, 'damaged', 'jb', '2024-11-16 12:14:03', 'nbu', 7),
(19, 'm', '4', 'o', 'not_available', '09765', 5, 'good', 'b', '2024-11-16 12:14:33', 'jg', 8),
(20, 's', 'i', '7', '3', '987', 6, 'in-use', ' gj', '2024-11-16 12:15:01', 'by', 7);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_history`
--

CREATE TABLE `inventory_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `inventory_id` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('in','out') NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `status_detail` enum('on delivery','on user','arrived') NOT NULL DEFAULT 'on delivery',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_history`
--

INSERT INTO `inventory_history` (`id`, `inventory_id`, `status`, `location`, `status_detail`, `created_at`, `description`) VALUES
(1, NULL, 'in', 'HUEUD', 'on user', '2024-11-06 01:35:16', NULL),
(2, NULL, 'in', 'fcv', 'on user', '2024-11-06 02:17:35', NULL),
(3, NULL, 'in', 'fcv', 'on user', '2024-11-06 02:17:41', NULL),
(4, NULL, 'in', 'g', 'arrived', '2024-11-06 02:17:49', NULL),
(5, NULL, 'in', 'tuh', 'on user', '2024-11-06 02:24:07', NULL),
(6, NULL, 'in', 'ytv', 'on user', '2024-11-06 03:11:53', NULL),
(7, NULL, 'in', 'tuhh', 'on user', '2024-11-06 11:44:59', NULL),
(8, NULL, 'in', 'ih', 'arrived', '2024-11-06 13:02:09', NULL),
(9, NULL, 'in', 'iwj', 'on delivery', '2024-11-06 13:07:45', NULL),
(10, NULL, 'in', 'er', 'on user', '2024-11-08 02:20:50', NULL),
(11, NULL, 'in', 'go', 'on delivery', '2024-11-09 05:11:35', NULL),
(12, NULL, 'in', 'HUEUDl', 'on user', '2024-11-09 05:18:07', NULL),
(13, NULL, 'in', 'ok', 'on delivery', '2024-11-09 05:18:14', NULL),
(14, NULL, 'out', 'iuk', 'arrived', '2024-11-15 15:11:36', 'ydh'),
(15, NULL, 'out', 'h', 'arrived', '2024-11-15 15:35:00', 'hu'),
(16, NULL, 'in', '', '', '2024-11-15 15:37:27', ''),
(17, NULL, 'in', '', '', '2024-11-15 15:40:26', ''),
(18, NULL, 'out', 'hhhhhb', 'on delivery', '2024-11-15 15:40:44', 'nb'),
(19, NULL, 'in', '', '', '2024-11-15 15:41:30', ''),
(20, NULL, 'out', 'dd', 'on delivery', '2024-11-15 15:41:46', 'be'),
(21, NULL, 'in', '', '', '2024-11-15 15:41:57', ''),
(22, NULL, 'out', 'b', 'on user', '2024-11-15 15:42:35', 'jv'),
(23, NULL, 'in', '', '', '2024-11-15 15:42:50', '');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id_invoice` int(11) NOT NULL,
  `nama_invoice` varchar(255) NOT NULL,
  `jenis_inv` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_aksi`
--

CREATE TABLE `jenis_aksi` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_barang`
--

CREATE TABLE `jenis_barang` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `kode_barang` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis_barang`
--

INSERT INTO `jenis_barang` (`id`, `nama_kategori`, `kode_barang`) VALUES
(7, 'jamiunm', 'M'),
(8, 'kabel', 'K');

-- --------------------------------------------------------

--
-- Table structure for table `layanan`
--

CREATE TABLE `layanan` (
  `id` int(11) NOT NULL,
  `layanan` varchar(255) NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logistik`
--

CREATE TABLE `logistik` (
  `id` int(11) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `tipe` varchar(100) DEFAULT NULL,
  `seri` varchar(100) DEFAULT NULL,
  `jumlah` int(11) NOT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `dapel_satria_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) UNSIGNED NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `url_menu` varchar(255) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nomor_telepon`
--

CREATE TABLE `nomor_telepon` (
  `id` int(11) NOT NULL,
  `pelanggan_id` int(11) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nomor_telepon`
--

INSERT INTO `nomor_telepon` (`id`, `pelanggan_id`, `phone_number`) VALUES
(28, 24, '86458522146'),
(29, 25, '86458522146'),
(30, 26, '976799'),
(31, 26, '86458522146'),
(32, 27, '83527582'),
(33, 28, '8765676987'),
(34, 29, '86488789899989'),
(35, 30, '86488789899989'),
(36, 30, '9853258987');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `note` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ticket_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `aksi_id` int(11) NOT NULL,
  `metode` enum('WA BOT','Email') NOT NULL,
  `penerima` enum('Pelanggan','Teknisi') NOT NULL,
  `status_kirim` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int(11) NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `bukti_bayar` varchar(100) DEFAULT NULL,
  `layanan` varchar(100) DEFAULT NULL,
  `harga_dasar` decimal(10,2) DEFAULT NULL,
  `ppn` decimal(10,2) DEFAULT 0.11,
  `total` decimal(10,2) DEFAULT NULL,
  `email_sent` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `policies`
--

CREATE TABLE `policies` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) UNSIGNED DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resolusi`
--

CREATE TABLE `resolusi` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `note` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_role` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `nama_role`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrator role with full access', '2024-11-16 13:33:40', NULL),
(7, 'admin', 'Administrator with full access', '2024-11-16 13:14:06', '2024-11-16 13:14:06'),
(8, 'users', 'Regular user with limited access', '2024-11-16 13:14:06', '2024-11-16 13:14:06'),
(9, 'editor', 'Editor with content modification privileges', '2024-11-16 13:14:06', '2024-11-16 13:14:06'),
(10, 'viewer', 'Viewer with read-only access', '2024-11-16 13:14:06', '2024-11-16 13:14:06');

-- --------------------------------------------------------

--
-- Table structure for table `ticketing`
--

CREATE TABLE `ticketing` (
  `id` int(11) NOT NULL,
  `dapel_satria_id` int(11) DEFAULT NULL,
  `data_teknis_id` int(11) DEFAULT NULL,
  `nomor_telepon_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `new_password` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `profile_photo` varchar(255) DEFAULT NULL,
  `update_pp` varchar(255) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `new_password`, `status`, `created_at`, `updated_at`, `profile_photo`, `update_pp`, `last_login`, `role_id`) VALUES
(65, 'Admin', '0192023a7bbd73250516f069df18b500', '', 1, '2024-11-16 20:33:58', '2024-11-16 20:33:58', NULL, NULL, NULL, 1),
(78, 'Ade Triviadi', '$2y$10$XJfCMyavxCRqQXgMKhIPEOgPK0/tBP5s6hBPfxukhCevrecMBziS.', '', 1, '2024-11-17 19:43:29', '2024-11-17 20:09:56', NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aksi`
--
ALTER TABLE `aksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `layanan_id` (`layanan_id`),
  ADD KEY `fk_jenis_aksi` (`jenis_aksi_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brands_ibfk_1` (`jenis_barang_id`);

--
-- Indexes for table `dapel_satria`
--
ALTER TABLE `dapel_satria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `data_pelanggan`
--
ALTER TABLE `data_pelanggan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_invoice_id` (`invoice_id`);

--
-- Indexes for table `data_teknis`
--
ALTER TABLE `data_teknis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `datta_teknis`
--
ALTER TABLE `datta_teknis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_inventory_jenis_barang` (`jenis_barang`);

--
-- Indexes for table `inventory_history`
--
ALTER TABLE `inventory_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_id` (`inventory_id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id_invoice`);

--
-- Indexes for table `jenis_aksi`
--
ALTER TABLE `jenis_aksi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jenis_barang`
--
ALTER TABLE `jenis_barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logistik`
--
ALTER TABLE `logistik`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dapel_satria_id` (`dapel_satria_id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nomor_telepon`
--
ALTER TABLE `nomor_telepon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pelanggan` (`pelanggan_id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ticket_notes` (`ticket_id`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aksi_id` (`aksi_id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `policies`
--
ALTER TABLE `policies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resolusi`
--
ALTER TABLE `resolusi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_id` (`ticket_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticketing`
--
ALTER TABLE `ticketing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dapel_satria_ticketing` (`dapel_satria_id`),
  ADD KEY `fk_data_teknis_ticketing` (`data_teknis_id`),
  ADD KEY `id_nomor` (`nomor_telepon_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_users_role` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aksi`
--
ALTER TABLE `aksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dapel_satria`
--
ALTER TABLE `dapel_satria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_pelanggan`
--
ALTER TABLE `data_pelanggan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `data_teknis`
--
ALTER TABLE `data_teknis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `datta_teknis`
--
ALTER TABLE `datta_teknis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `inventory_history`
--
ALTER TABLE `inventory_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id_invoice` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_aksi`
--
ALTER TABLE `jenis_aksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_barang`
--
ALTER TABLE `jenis_barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logistik`
--
ALTER TABLE `logistik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nomor_telepon`
--
ALTER TABLE `nomor_telepon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `policies`
--
ALTER TABLE `policies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resolusi`
--
ALTER TABLE `resolusi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ticketing`
--
ALTER TABLE `ticketing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aksi`
--
ALTER TABLE `aksi`
  ADD CONSTRAINT `aksi_ibfk_1` FOREIGN KEY (`layanan_id`) REFERENCES `layanan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_jenis_aksi` FOREIGN KEY (`jenis_aksi_id`) REFERENCES `jenis_aksi` (`id`);

--
-- Constraints for table `brands`
--
ALTER TABLE `brands`
  ADD CONSTRAINT `brands_ibfk_1` FOREIGN KEY (`jenis_barang_id`) REFERENCES `jenis_barang` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `dapel_satria`
--
ALTER TABLE `dapel_satria`
  ADD CONSTRAINT `dapel_satria_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id_invoice`);

--
-- Constraints for table `data_pelanggan`
--
ALTER TABLE `data_pelanggan`
  ADD CONSTRAINT `fk_invoice_id` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id_invoice`);

--
-- Constraints for table `inventory_history`
--
ALTER TABLE `inventory_history`
  ADD CONSTRAINT `fk_inventory_history_inventory` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `logistik`
--
ALTER TABLE `logistik`
  ADD CONSTRAINT `fk_dapel_satria_id` FOREIGN KEY (`dapel_satria_id`) REFERENCES `dapel_satria` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
