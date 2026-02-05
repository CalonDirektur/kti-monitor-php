-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 05 Feb 2026 pada 01.49
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kti_monitor`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bendungan`
--

CREATE TABLE `bendungan` (
  `id` int(11) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `bendungan_status`
--

CREATE TABLE `bendungan_status` (
  `id` int(11) NOT NULL,
  `bendungan_id` int(11) NOT NULL,
  `tinggi_air` decimal(6,2) DEFAULT NULL,
  `level` enum('Normal','Waspada','Siaga','Awas') NOT NULL,
  `waktu` datetime NOT NULL,
  `sumber` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cuaca_realtime`
--

CREATE TABLE `cuaca_realtime` (
  `id` int(11) NOT NULL,
  `wilayah` varchar(150) NOT NULL,
  `kondisi` varchar(100) NOT NULL,
  `suhu` decimal(4,1) DEFAULT NULL,
  `kelembaban` int(11) DEFAULT NULL,
  `kecepatan_angin` decimal(4,1) DEFAULT NULL,
  `waktu` datetime NOT NULL,
  `sumber` varchar(50) DEFAULT 'BMKG',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `gempa_events`
--

CREATE TABLE `gempa_events` (
  `id` int(11) NOT NULL,
  `waktu` datetime NOT NULL,
  `magnitude` decimal(3,1) NOT NULL,
  `lintang` varchar(20) NOT NULL,
  `bujur` varchar(20) NOT NULL,
  `kedalaman` varchar(50) DEFAULT NULL,
  `wilayah` varchar(255) DEFAULT NULL,
  `potensi` varchar(255) DEFAULT NULL,
  `sumber` varchar(50) DEFAULT 'BMKG',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `gempa_events`
--

INSERT INTO `gempa_events` (`id`, `waktu`, `magnitude`, `lintang`, `bujur`, `kedalaman`, `wilayah`, `potensi`, `sumber`, `created_at`) VALUES
(1, '2026-01-27 09:18:31', 4.2, '8.27 LS', '109.09 BT', '34 km', 'Pusat gempa berada di laut 61 km selatan Cilacap', 'Gempa ini dirasakan untuk diteruskan pada masyarakat', 'BMKG', '2026-01-27 12:03:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hujan_harian`
--

CREATE TABLE `hujan_harian` (
  `id` int(11) NOT NULL,
  `wilayah` varchar(150) NOT NULL,
  `tanggal` date NOT NULL,
  `total_mm` decimal(6,2) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `sumber` varchar(50) DEFAULT 'BMKG',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kualitas_udara`
--

CREATE TABLE `kualitas_udara` (
  `id` int(11) NOT NULL,
  `lokasi` varchar(150) NOT NULL,
  `pm25` decimal(6,2) DEFAULT NULL,
  `pm10` decimal(6,2) DEFAULT NULL,
  `so2` decimal(6,2) DEFAULT NULL,
  `no2` decimal(6,2) DEFAULT NULL,
  `o3` decimal(6,2) DEFAULT NULL,
  `co` decimal(6,2) DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `waktu` datetime NOT NULL,
  `sumber` varchar(50) DEFAULT 'BMKG',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `nowcast_alerts`
--

CREATE TABLE `nowcast_alerts` (
  `id` int(11) NOT NULL,
  `alert_type` varchar(20) DEFAULT NULL,
  `title` text DEFAULT NULL,
  `wilayah` text DEFAULT NULL,
  `link` text DEFAULT NULL,
  `pub_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `nowcast_alerts`
--

INSERT INTO `nowcast_alerts` (`id`, `alert_type`, `title`, `wilayah`, `link`, `pub_date`, `created_at`) VALUES
(1, 'PETIR', 'Hujan Lebat disertai Petir di Sulawesi Tengah', 'KTI', 'https://www.bmkg.go.id/alerts/nowcast/id/CSG20260128003_alert.xml', '2026-01-28 06:43:00', '2026-01-28 07:12:53'),
(2, 'PETIR', 'Hujan Lebat disertai Petir di Sulawesi Barat', 'KTI', 'https://www.bmkg.go.id/alerts/nowcast/id/CSK20260128003_alert.xml', '2026-01-28 06:27:00', '2026-01-28 07:12:53'),
(3, 'PETIR', 'Hujan Lebat disertai Petir di Sulawesi Selatan', 'KTI', 'https://www.bmkg.go.id/alerts/nowcast/id/CSL20260128004_alert.xml', '2026-01-28 06:10:00', '2026-01-28 07:12:53'),
(4, 'PETIR', 'Hujan Lebat disertai Petir di Sulawesi Tenggara', 'KTI', 'https://www.bmkg.go.id/alerts/nowcast/id/CST20260128002_alert.xml', '2026-01-28 04:50:00', '2026-01-28 07:12:53'),
(5, 'PETIR', 'Hujan Lebat disertai Petir di Sulawesi Barat', 'KTI', 'https://www.bmkg.go.id/alerts/nowcast/id/CSK20260128005_alert.xml', '2026-01-28 12:50:00', '2026-01-28 13:03:53'),
(6, 'PETIR', 'Hujan Lebat disertai Petir di Sulawesi Tenggara', 'KTI', 'https://www.bmkg.go.id/alerts/nowcast/id/CST20260128007_alert.xml', '2026-01-28 12:10:00', '2026-01-28 13:03:53'),
(7, 'PETIR', 'Hujan Lebat disertai Petir di Sulawesi Tengah', 'KTI', 'https://www.bmkg.go.id/alerts/nowcast/id/CSG20260128005_alert.xml', '2026-01-28 11:58:00', '2026-01-28 13:03:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `system_setting`
--

CREATE TABLE `system_setting` (
  `id` int(11) NOT NULL,
  `nama_setting` varchar(100) DEFAULT NULL,
  `nilai_setting` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `system_setting`
--

INSERT INTO `system_setting` (`id`, `nama_setting`, `nilai_setting`, `updated_at`) VALUES
(1, 'gempa_threshold', '6', '2026-01-27 02:23:59'),
(2, 'udara_threshold', 'Tidak Sehat', '2026-01-27 02:23:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `telegram_log`
--

CREATE TABLE `telegram_log` (
  `id` int(11) NOT NULL,
  `jenis` varchar(50) DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `waktu` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bendungan`
--
ALTER TABLE `bendungan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `bendungan_status`
--
ALTER TABLE `bendungan_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bendungan_id` (`bendungan_id`),
  ADD KEY `idx_level` (`level`),
  ADD KEY `idx_waktu` (`waktu`);

--
-- Indeks untuk tabel `cuaca_realtime`
--
ALTER TABLE `cuaca_realtime`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_wilayah` (`wilayah`),
  ADD KEY `idx_waktu` (`waktu`);

--
-- Indeks untuk tabel `gempa_events`
--
ALTER TABLE `gempa_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_waktu` (`waktu`),
  ADD KEY `idx_magnitude` (`magnitude`);

--
-- Indeks untuk tabel `hujan_harian`
--
ALTER TABLE `hujan_harian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_hujan` (`wilayah`,`tanggal`),
  ADD KEY `idx_tanggal` (`tanggal`);

--
-- Indeks untuk tabel `kualitas_udara`
--
ALTER TABLE `kualitas_udara`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lokasi` (`lokasi`),
  ADD KEY `idx_kategori` (`kategori`),
  ADD KEY `idx_waktu` (`waktu`);

--
-- Indeks untuk tabel `nowcast_alerts`
--
ALTER TABLE `nowcast_alerts`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `system_setting`
--
ALTER TABLE `system_setting`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_setting` (`nama_setting`);

--
-- Indeks untuk tabel `telegram_log`
--
ALTER TABLE `telegram_log`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bendungan`
--
ALTER TABLE `bendungan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `bendungan_status`
--
ALTER TABLE `bendungan_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `cuaca_realtime`
--
ALTER TABLE `cuaca_realtime`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `gempa_events`
--
ALTER TABLE `gempa_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `hujan_harian`
--
ALTER TABLE `hujan_harian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kualitas_udara`
--
ALTER TABLE `kualitas_udara`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `nowcast_alerts`
--
ALTER TABLE `nowcast_alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `system_setting`
--
ALTER TABLE `system_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `telegram_log`
--
ALTER TABLE `telegram_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `bendungan_status`
--
ALTER TABLE `bendungan_status`
  ADD CONSTRAINT `bendungan_status_ibfk_1` FOREIGN KEY (`bendungan_id`) REFERENCES `bendungan` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
