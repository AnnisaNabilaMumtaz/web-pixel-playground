-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 03 Jan 2025 pada 08.11
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pixel_playground`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `game`
--

CREATE TABLE `game` (
  `id_game` int(10) NOT NULL,
  `nama_game` varchar(100) NOT NULL,
  `developer` varchar(100) NOT NULL,
  `batas_usia` varchar(10) NOT NULL,
  `link_game` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `foto_game` varchar(255) NOT NULL,
  `link_mabar` varchar(100) NOT NULL,
  `link_komunitas` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `clicks` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `game`
--

INSERT INTO `game` (`id_game`, `nama_game`, `developer`, `batas_usia`, `link_game`, `deskripsi`, `foto_game`, `link_mabar`, `link_komunitas`, `created_at`, `clicks`) VALUES
(1, 'Minecraft', 'Mojang Studio', '7+', 'https://discord.com', 'Minecraft adalah sebuah permainan video sandbox yang dikembangkan oleh Mojang Studios. Pemain dapat menjelajahi dunia blok yang luas, membangun berbagai struktur, serta bertahan hidup melawan monster.', 'uploads/1733394931_minecraft3.jpg', 'https://discord.com', 'https://discord.com', '2024-12-02 10:21:37', 0),
(2, 'Mobile Legends', 'developer', '7+', 'https://discord.com', 'Mobile Legends: Bang Bang adalah game MOBA mobile 5v5 yang mengadu dua tim dalam pertarungan strategis real-time. Pemain memilih hero dengan peran unik untuk bekerja sama menghancurkan base musuh dalam permainan cepat dan kompetitif.', 'uploads/1733400113_mobilelegend02.jpg', 'https://discord.com', 'https://discord.com', '2024-12-02 10:21:37', 0),
(3, 'Free Fire', 'developer', '7+', 'https://discord.com', 'Free Fire adalah game battle royale mobile yang menempatkan hingga 50 pemain di medan pertempuran untuk saling bertarung hingga tersisa satu pemenang. Pemain harus bertahan hidup dengan mencari senjata, peralatan, dan strategi di peta yang terus menyusut, menawarkan aksi cepat dan intens dalam durasi pertandingan sekitar 10 menit.', 'uploads/1733400326_freefire.jpg', 'https://discord.com', 'https://discord.com', '2024-12-02 10:21:37', 0),
(4, 'Among Us', 'developer', '12', 'https://discord.com', 'Among Us adalah game multiplayer sosial yang menempatkan pemain dalam kru pesawat luar angkasa dengan tugas memperbaiki kapal sambil mengungkap siapa di antara mereka yang menjadi Impostor. Pemain harus bekerja sama atau berstrategi licik untuk menang, menciptakan ketegangan dan keseruan dalam setiap sesi permainan.', 'uploads/1733400434_amongus02.jpg', 'https://discord.com', 'https://discord.com', '2024-12-02 10:21:37', 0),
(5, 'Stumble Guys', 'developer', '7+', 'https://discord.com', 'Stumble Guys adalah game multiplayer battle royale yang mengadu hingga 32 pemain dalam serangkaian tantangan dan rintangan lucu. Pemain harus berlari, melompat, dan bertahan di arena untuk menjadi yang terakhir bertahan, menawarkan pengalaman kompetitif yang seru dan penuh tawa.', 'uploads/1733400365_stumbleguys02.jpg', 'https://discord.com', 'https://discord.com', '2024-12-02 10:21:37', 0),
(14, 'zzzzzzzzzzzzzzzzzzzzzzzz', 'zzzzzzzzzzzzzzzzzzzzz', 'zzzzzzzzzz', 'https://discord.com', 'zzzzzzzzzzzz', 'uploads/1733400390_callofduty.jpg', 'https://discord.com', 'https://discord.com', '2024-12-05 10:17:57', 0),
(15, 'a', 'a', 'a', 'https://discord.com', 'aaaaaaaaaaaaaaa', 'uploads/3a1463b5-b892-4665-bac2-7960a5e0944f.jpeg', 'https://discord.com', 'https://discordzz.com', '2024-12-05 10:18:26', 0),
(16, 'b', 'b', 'b', 'https://discord.com', 'bbbbbbbbbbbbbbbbbb', 'uploads/‧₊˚✩彡.jpeg', 'https://discord.com', 'https://discord.com', '2024-12-05 10:18:59', 0),
(17, 'zzzzzzzzzzzzzzzz', 'mmmmmmmm', 'mmmmmmmmmm', 'https://discord.com', 'nnnnnnnnn', 'uploads/✿ woonhak.jpeg', 'https://discord.com', 'https://discordaa.com', '2024-12-05 10:36:26', 0),
(19, 'game11', 'game1', '7', 'https://discord.com', 'game', 'uploads/𝐋♡𝐕𝐄 ୨୧ ˚₊.jpeg', 'https://discord.com', 'https://discordzz.com', '2024-12-24 14:58:52', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `komunitas`
--

CREATE TABLE `komunitas` (
  `id_komunitas` int(10) NOT NULL,
  `nama_komunitas` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `foto_komunitas` varchar(255) DEFAULT NULL,
  `link_komunitas` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `id_game` int(10) NOT NULL,
  `clicks` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `komunitas`
--

INSERT INTO `komunitas` (`id_komunitas`, `nama_komunitas`, `deskripsi`, `foto_komunitas`, `link_komunitas`, `created_at`, `id_game`, `clicks`) VALUES
(1, 'Minecraft Community', 'Komunitas penggemar Minecraft, mulai dari membangun, bertahan hidup, hingga bermain bersama.', 'uploads/freefire.jpg', 'https://discord.com', '2024-12-02 11:55:39', 1, 0),
(2, 'Mobile Legends Squad', 'Ayo bergabung dengan tim Mobile Legends dan mainkan mode Ranked bersama teman-teman.', 'uploads/mobilelegend.png', 'https://discord.com', '2024-12-02 11:55:39', 2, 0),
(3, 'Free Fire Fighters', 'Bergabunglah dengan komunitas pemain Free Fire untuk berbagi strategi dan bermain bersama.', 'uploads/freefire.png', 'https://discord.com', '2024-12-02 11:55:39', 3, 0),
(4, 'Whos Among Us', 'Bergabunglah dan buktikan kecerdikanmu! Temukan siapa yang bisa dipercaya, berdebat, dan nikmati keseruan bermain bersama. Siap jadi Crewmate atau Impostor?', 'uploads/amongus02.jpg', 'https://discord.com', '2024-12-02 11:55:39', 4, 0),
(5, 'Stumble Guys Community', 'Lompat, luncur, dan nikmati keseruan di komunitas Stumble Guys! Bersaing dengan teman-teman, berbagi trik, dan hadapi rintangan bersama.', 'uploads/stumbleguys02.jpg', 'https://discord.com', '2024-12-02 11:55:39', 5, 0),
(14, 'vvv', 'nnn', 'uploads/✿ woonhak.jpeg', 'https://discordzz.com', '2024-12-09 15:11:21', 15, 0),
(18, 'hellow', 'helllllllooooooow', 'uploads/♡⃝🫂  ♪.jpeg', 'https://discordzz.com', '2024-12-24 14:52:52', 17, 0),
(19, 'testinggggg', 'tes', 'uploads/‧₊˚✩彡.jpeg', 'https://discordaa.com', '2024-12-25 07:58:54', 17, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `profile`
--

CREATE TABLE `profile` (
  `id_profile` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_user` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `profile`
--

INSERT INTO `profile` (`id_profile`, `username`, `name`, `foto`, `email`, `created_at`, `id_user`) VALUES
(1, 'kobskuy_zzzz', 'Kobo Kanaeru', 'uploads/𝐋♡𝐕𝐄 ୨୧ ˚₊.jpeg', 'kobokanaeru@gmail.com', '2024-12-25 08:46:48', 1),
(2, 'aldennay', 'Alden Nay', 'pp03.png', 'alden@gmail.com', '2024-12-23 04:07:59', 6),
(3, 'user123', 'Park Wonbin', 'pp06.png', 'wonbin@gmail.com', '2024-12-23 04:07:59', 2),
(4, 'lord.crime', 'Moriarty', 'pp07.png', 'moriarty@gmail.com', '2024-12-23 04:07:59', 7),
(5, 'user_new123', 'Taesan', 'pp06.png', 'taesan@gmail.com', '2024-12-23 04:07:59', 8),
(7, 'unaa', '', 'uploads/677784431f791.jpg', 'una@gmail.com', '2025-01-03 06:31:31', 54),
(8, 'athifah', '', 'uploads/67774c658eb9f.jpeg', 'athif@gmail.com', '2025-01-03 02:33:57', 11),
(9, 'athifah', '', '', 'athi@gmail.com', '2025-01-03 02:25:19', 56);

-- --------------------------------------------------------

--
-- Struktur dari tabel `review`
--

CREATE TABLE `review` (
  `id_review` int(10) NOT NULL,
  `id_user` int(10) NOT NULL,
  `id_game` int(10) NOT NULL,
  `rating` int(10) NOT NULL,
  `komentar` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `review`
--

INSERT INTO `review` (`id_review`, `id_user`, `id_game`, `rating`, `komentar`, `created_at`) VALUES
(1, 1, 1, 5, 'Gamenya sangat seru', '2024-12-23 04:08:47'),
(2, 2, 2, 5, 'Bagus banget gamenya', '2024-12-23 04:08:47'),
(3, 1, 3, 5, 'Asik banget buat mabar', '2024-12-23 04:08:47'),
(4, 1, 4, 5, 'Bagus', '2024-12-23 04:08:47'),
(5, 2, 5, 5, 'Asik banget gamenya', '2024-12-23 04:08:47'),
(6, 2, 1, 5, 'seru banget gamenya', '2024-12-25 08:44:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `jenis_user` enum('user','admin','','') NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `username`, `email`, `jenis_user`, `password`, `created_at`) VALUES
(1, 'kobskuy_zzzz', '', 'user', '22222222', '2024-11-23 04:09:07'),
(2, 'user1234', '', 'user', '11223344', '2024-11-23 04:09:07'),
(3, 'athi_admin', '', 'admin', '12345678', '2024-11-23 04:09:07'),
(4, 'annis_admin', '', 'admin', '12345678', '2024-11-23 04:09:07'),
(5, 'william_admin', '', 'admin', '12345678', '2024-10-23 04:09:07'),
(6, 'aldennay', '', 'user', '11111111', '2024-10-23 04:09:07'),
(7, 'lord.crime', '', 'user', '22222222', '2024-10-23 04:09:07'),
(8, 'user_new123', '', 'user', '33333333', '2024-12-23 04:09:07'),
(9, '', '', '', '$2y$10$ML2HKmR9N', '2024-12-23 04:09:07'),
(10, '', '', '', '$2y$10$HfEstm2eu', '2024-12-23 04:09:07'),
(11, 'athifah', '', 'user', '$2y$10$UlFsHLtJz', '2024-12-23 04:09:07'),
(12, 'annis', '', 'user', '$2y$10$yGG7xkkZ/', '2024-12-23 04:09:07'),
(13, 'willi', '', 'admin', '$2y$10$i455phfB3', '2024-12-23 04:09:07'),
(14, 'andi', '', 'admin', '$2y$10$E.WF75zri', '2024-12-23 04:09:07'),
(15, 'hantaesan', '', 'admin', '$2y$10$RWdSjc3qq', '2024-12-23 04:09:07'),
(16, 'wonbinpark', '', 'admin', '$2y$10$4BRRXv7eq', '2024-12-23 04:09:07'),
(17, 'sohee', '', 'admin', '$2y$10$llvdhI15A', '2024-12-23 04:09:07'),
(18, 'athi.fah', '', '', '12345678', '2024-12-23 04:09:07'),
(23, 'abc_def', '', '', '11111111', '2024-12-23 04:09:07'),
(26, 'kobskuy', '', '', '12345678', '2024-12-23 04:09:07'),
(27, 'athiiiiiii', '', '', '12345678', '2024-12-23 04:09:07'),
(28, 'athiiiiiii', '', '', '12345678', '2024-11-23 04:09:07'),
(29, 'abddd', '', '', '12345678', '2024-12-23 04:19:58'),
(30, 'abddd', '', '', '12345678', '2024-12-23 04:20:12'),
(33, 'kobskuy', '', '', '12345678', '2024-12-23 04:37:19'),
(34, 'kobskuy', '', '', '12345678', '2024-12-23 04:38:12'),
(35, 'kobskuy', '', '', '12345678', '2024-12-23 04:38:19'),
(36, 'aisyah123', '', '', '12345678', '2024-12-23 06:04:30'),
(37, 'aisyah123', '', '', '12345678', '2024-12-23 06:04:48'),
(38, 'aisyah123', '', '', '12345678', '2024-12-23 06:05:47'),
(39, 'aisyah123', '', '', '12345678', '2024-12-23 06:06:11'),
(40, 'aisyah', '', '', '11111111', '2024-12-23 06:06:59'),
(41, 'aisyah', '', '', '11111111', '2024-12-23 06:07:32'),
(42, 'kobskuy', '', '', '12345678', '2024-12-23 06:08:05'),
(43, 'kobskuy', '', '', '12345678', '2024-12-23 06:08:42'),
(44, 'aisyah', '', '', '12345678', '2024-12-23 06:13:11'),
(45, 'aisyah123', '', '', '12345678', '2024-12-23 06:13:28'),
(46, 'aaaa', '', '', '22222222', '2024-12-23 06:14:49'),
(47, 'aisyah123', '', '', '12345678', '2024-12-23 06:17:11'),
(48, 'aisyah123', '', '', '12345678', '2024-12-23 06:17:22'),
(49, 'aisyah', '', '', '12345678', '2024-12-23 06:26:18'),
(50, 'aisyah', '', '', '12345678', '2024-12-23 06:27:25'),
(51, 'karina', 'a@gmail.com', 'user', '$2y$10$r2Zkqj6fp', '2024-12-24 13:57:50'),
(52, 'athi', 'aisyaathifah@gmail.com', 'user', '$2y$10$hQum9mdKC', '2024-12-24 13:58:59'),
(53, 'user_12345678', 'user@gmail.com', 'user', '$2y$10$tGZ6hFOC4', '2024-12-24 14:08:57'),
(54, 'unaa', 'una@gmail.com', 'admin', '$2y$10$QpIi7/Eh5Tw7POXOmp4Auekl02UM6v0nERd2R7onJa4D9znaUJYfG', '2024-12-31 03:34:33'),
(55, 'william_admin', '', 'admin', '12345678', '2024-10-23 04:09:07'),
(56, 'athifah', 'athi@gmail.com', 'user', '$2y$10$qA9o56LUtTQU7Ek04Smj1ujPd/Z3MWC2cTA.crRVUcKFawSEqDMN.', '2025-01-02 15:40:30');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`id_game`);

--
-- Indeks untuk tabel `komunitas`
--
ALTER TABLE `komunitas`
  ADD PRIMARY KEY (`id_komunitas`),
  ADD KEY `id_game` (`id_game`);

--
-- Indeks untuk tabel `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id_profile`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id_review`),
  ADD KEY `id_user` (`id_user`,`id_game`),
  ADD KEY `id_game` (`id_game`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `game`
--
ALTER TABLE `game`
  MODIFY `id_game` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `komunitas`
--
ALTER TABLE `komunitas`
  MODIFY `id_komunitas` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `profile`
--
ALTER TABLE `profile`
  MODIFY `id_profile` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `review`
--
ALTER TABLE `review`
  MODIFY `id_review` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `komunitas`
--
ALTER TABLE `komunitas`
  ADD CONSTRAINT `fk_game` FOREIGN KEY (`id_game`) REFERENCES `game` (`id_game`) ON UPDATE CASCADE,
  ADD CONSTRAINT `komunitas_ibfk_1` FOREIGN KEY (`id_game`) REFERENCES `game` (`id_game`);

--
-- Ketidakleluasaan untuk tabel `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `fk_profile_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`id_game`) REFERENCES `game` (`id_game`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
