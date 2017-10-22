-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 17-Out-2017 às 13:05
-- Versão do servidor: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projetox`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `lot`
--

CREATE TABLE `lot` (
  `id` int(10) UNSIGNED NOT NULL,
  `amount` int(10) UNSIGNED NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `valuation` decimal(10,0) NOT NULL,
  `creator` int(10) UNSIGNED NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_activated` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `password`
--

CREATE TABLE `password` (
  `id` int(10) UNSIGNED NOT NULL,
  `iterations` int(10) UNSIGNED NOT NULL,
  `password` varchar(64) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `password`
--

INSERT INTO `password` (`id`, `iterations`, `password`, `salt`, `created`) VALUES
(1, 5693, '5c728c35681d804269d510dff612abd40469ca2c0f23f8c0ca0cc01018687dc4', '150796902', '2017-10-03 15:04:45'),
(2, 6828, 'ecab77504f5edf8315539cf632bbc1484abcadd27b090861cee72c21bcdedb28', '464423514', '2017-10-16 17:40:41'),
(3, 1041, '104a26875fd957e398827002286bccb3b21d790b664bf331144406fe9e58af79', '854813139', '2017-10-16 17:42:33'),
(4, 2966, '7b8fd509e052711eb21afd3f709dd3cb2e271394aa32a6323d1d7786aa6c7556', '1471191171', '2017-10-16 17:57:15'),
(5, 4648, '1fd63372283b3de14667c934c3dd4841d53e030185231d928bf16e0e396e6ebf', '1599452230', '2017-10-16 17:59:17'),
(6, 8301, '817a3b692eef87d7550e354f7dbbda3250781337f395e0ec4b4b7712e830ec9a', '1191831597', '2017-10-16 18:02:55'),
(7, 3221, '29bf519ca2768a2dc12f775a301d5bd2373152c9996f18767db3867e3f5c1681', '1759265529', '2017-10-16 18:05:17'),
(8, 3715, '4e57284d1e9e2c9e9a9011e8fe5cebffd52bf5e5054be3bf55214a2baf6d734c', '909528570', '2017-10-16 18:06:22'),
(9, 7003, '5b811c96d2990f0468137c23c57ea16d4dc1da00ad350ada000d72b8c8c3651f', '1311017842', '2017-10-16 18:08:17'),
(10, 1614, '5272982d43251c0e82a04a398950297a658b82f8981d8af789a4fab16b186dab', '1160951685', '2017-10-16 18:18:02'),
(11, 7490, 'e7199d972126f8d59b36ee249bb30785b8080242e3623b817243502119a34b02', '498454450', '2017-10-16 18:29:07'),
(12, 9264, '8b4fb15ea89d8a43de9496fb00ef0d4183cc42eb02eb6d1f8dd6697d64be333a', '266072293', '2017-10-17 09:29:16'),
(13, 4724, 'a94e0121aa0e0546afed00d01800d699944dfe92f8ca1679d1881cd6eac83474', '1129820957', '2017-10-17 11:44:03'),
(14, 5617, '36d46a19a547c64181eb46163d152f7a2149a564043e5411ae86d81f9ba72fe6', '1986686697', '2017-10-17 12:30:47'),
(15, 1429, '0fcb4bbd0b730823c9a48f88e0ce63e794d576386651dcf4c3755679a4c08781', '1449216850', '2017-10-17 13:04:48');

-- --------------------------------------------------------

--
-- Estrutura da tabela `reset`
--

CREATE TABLE `reset` (
  `id` int(10) UNSIGNED NOT NULL,
  `iterations` int(10) UNSIGNED NOT NULL,
  `password` varchar(64) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `user` int(11) UNSIGNED NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_activated` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `reset`
--

INSERT INTO `reset` (`id`, `iterations`, `password`, `salt`, `user`, `created`, `is_activated`) VALUES
(58, 5850, '550e536162005b9479ce1d12ea8b9deb6b3e3d765b4f89293d156a8ebbf5e93d', '416272005', 1, '2017-10-17 11:43:37', 0),
(59, 9426, 'be83a2a937f47d6a307869045be5620ab61f32368658294bfa51d71a64175db4', '1944200034', 1, '2017-10-17 12:30:33', 0),
(60, 1321, 'a8bfb41166a2095d455d1d5585aa56deb6a066772315245400ae239382e33828', '1731436590', 1, '2017-10-17 13:04:34', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `ticket`
--

CREATE TABLE `ticket` (
  `id` int(10) UNSIGNED NOT NULL,
  `lot` int(10) UNSIGNED NOT NULL,
  `seller` int(11) UNSIGNED NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `owner_name` varchar(255) NOT NULL,
  `owner_email` varchar(255) NOT NULL,
  `owner_cpf` varchar(12) NOT NULL,
  `iterations` int(10) UNSIGNED NOT NULL,
  `password` varchar(64) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `cpf` varchar(12) DEFAULT NULL,
  `password` int(10) UNSIGNED DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `is_activated` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `cpf`, `password`, `is_admin`, `is_activated`) VALUES
(1, 'Administrador', 'admin@projetox.com', '12345678912', 15, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lot`
--
ALTER TABLE `lot`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creator` (`creator`);

--
-- Indexes for table `password`
--
ALTER TABLE `password`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reset`
--
ALTER TABLE `reset`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user`),
  ADD KEY `user` (`user`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD KEY `seller_2` (`seller`),
  ADD KEY `lot` (`lot`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD KEY `password` (`password`),
  ADD KEY `PASSWORD_ID` (`password`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lot`
--
ALTER TABLE `lot`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `password`
--
ALTER TABLE `password`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `reset`
--
ALTER TABLE `reset`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `lot`
--
ALTER TABLE `lot`
  ADD CONSTRAINT `lot_user` FOREIGN KEY (`creator`) REFERENCES `user` (`id`);

--
-- Limitadores para a tabela `reset`
--
ALTER TABLE `reset`
  ADD CONSTRAINT `reset_user` FOREIGN KEY (`user`) REFERENCES `user` (`id`);

--
-- Limitadores para a tabela `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `lot_ticket` FOREIGN KEY (`lot`) REFERENCES `lot` (`id`),
  ADD CONSTRAINT `sellet_ticket` FOREIGN KEY (`seller`) REFERENCES `user` (`id`);

--
-- Limitadores para a tabela `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_password` FOREIGN KEY (`password`) REFERENCES `password` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
