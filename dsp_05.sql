-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : lun. 24 août 2020 à 15:50
-- Version du serveur :  10.1.45-MariaDB-1~bionic
-- Version de PHP : 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `dsp`
--

-- --------------------------------------------------------

--
-- Structure de la table `address`
--

CREATE TABLE `address` (
  `id_address` smallint(5) UNSIGNED NOT NULL,
  `id_user` smallint(5) UNSIGNED NOT NULL,
  `addr_label` varchar(150) NOT NULL DEFAULT '',
  `addr_name` varchar(250) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `address2` varchar(255) NOT NULL DEFAULT '',
  `zip_code` varchar(10) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `id_country` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `address`
--

INSERT INTO `address` (`id_address`, `id_user`, `addr_label`, `addr_name`, `address`, `address2`, `zip_code`, `city`, `id_country`) VALUES
(1, 34, 'Domicile', 'Damien Thoorens', '5 rue dufyont', '', '14000', 'caen', 1);

-- --------------------------------------------------------

--
-- Structure de la table `country`
--

CREATE TABLE `country` (
  `id_country` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `code` char(2) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `country`
--

INSERT INTO `country` (`id_country`, `name`, `code`) VALUES
(1, 'France', 'fr'),
(2, 'Belgique', 'be');

-- --------------------------------------------------------

--
-- Structure de la table `dossier_color`
--

CREATE TABLE `dossier_color` (
  `id_dossier_color` smallint(5) UNSIGNED NOT NULL,
  `text` varchar(32) NOT NULL DEFAULT '',
  `hex` varchar(6) NOT NULL DEFAULT '',
  `printable` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `unprintable` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `position` smallint(5) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `dossier_color`
--

INSERT INTO `dossier_color` (`id_dossier_color`, `text`, `hex`, `printable`, `unprintable`, `position`) VALUES
(1, 'Ivoire', 'eceadf', 1, 1, 14),
(2, 'Blanc', 'ffffff', 0, 1, 18),
(3, 'Noir', '000000', 0, 1, 15),
(4, 'Gris foncé', '525252', 0, 1, 16),
(5, 'Gris clair', 'acacac', 1, 1, 17),
(6, 'Marron', '7a672c', 0, 1, 12),
(7, 'Bleu clair', '6e99e8', 1, 1, 5),
(8, 'Bleu foncé', '2b4ba6', 1, 1, 4),
(9, 'Rouge', 'df1d1d', 1, 1, 11),
(10, 'Jaune', 'ffff00', 1, 1, 8),
(11, 'Bordeau', '6d071a', 0, 1, 1),
(12, 'Vert foncé', '076d0e', 0, 1, 6),
(13, 'Vert clair', '33bc3d', 0, 1, 7),
(14, 'Orange foncé', 'e1a400', 1, 0, 10),
(15, 'Orange clair', 'f4c953', 1, 0, 9),
(16, 'Violet', 'a346e4', 1, 0, 2),
(17, 'Rose', 'e446de', 1, 0, 3),
(18, 'Cuir 250g', '74452b', 0, 1, 13);

-- --------------------------------------------------------

--
-- Structure de la table `key_value`
--

CREATE TABLE `key_value` (
  `sKey` varchar(150) NOT NULL DEFAULT '',
  `sValue` varchar(250) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `key_value`
--

INSERT INTO `key_value` (`sKey`, `sValue`) VALUES
('maxFeuillesMetal', '100'),
('maxFeuillesPlast', '500'),
('maxFeuillesThermo', '350'),
('prixFC', '0.35');

-- --------------------------------------------------------

--
-- Structure de la table `paliers_couleur`
--

CREATE TABLE `paliers_couleur` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `palier` smallint(5) UNSIGNED NOT NULL,
  `prix` float NOT NULL,
  `position` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `paliers_couleur`
--

INSERT INTO `paliers_couleur` (`id`, `palier`, `prix`, `position`) VALUES
(1, 15, 0.7, 7),
(2, 49, 0.55, 6),
(3, 99, 0.5, 5),
(4, 199, 0.45, 4),
(5, 499, 0.4, 3),
(6, 1000, 0.25, 2),
(8, 3000, 0.15, 8);

-- --------------------------------------------------------

--
-- Structure de la table `paliers_nb`
--

CREATE TABLE `paliers_nb` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `palier` smallint(5) UNSIGNED NOT NULL,
  `prix` float NOT NULL,
  `position` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `paliers_nb`
--

INSERT INTO `paliers_nb` (`id`, `palier`, `prix`, `position`) VALUES
(1, 10, 0.1, 8),
(2, 49, 0.08, 7),
(3, 199, 0.07, 6),
(4, 499, 0.06, 5),
(5, 999, 0.05, 4),
(6, 3000, 0.04, 3),
(7, 5000, 0.035, 1);

-- --------------------------------------------------------

--
-- Structure de la table `paliers_spimetal`
--

CREATE TABLE `paliers_spimetal` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `palier` smallint(5) UNSIGNED NOT NULL,
  `prix` float NOT NULL,
  `position` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `paliers_spimetal`
--

INSERT INTO `paliers_spimetal` (`id`, `palier`, `prix`, `position`) VALUES
(1, 40, 3.9, 4),
(2, 60, 4.4, 3),
(3, 80, 4.9, 2),
(4, 100, 5.4, 1);

-- --------------------------------------------------------

--
-- Structure de la table `paliers_spiplast`
--

CREATE TABLE `paliers_spiplast` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `palier` smallint(5) UNSIGNED NOT NULL,
  `prix` float NOT NULL,
  `position` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `paliers_spiplast`
--

INSERT INTO `paliers_spiplast` (`id`, `palier`, `prix`, `position`) VALUES
(1, 45, 2.1, 6),
(2, 95, 2.6, 5),
(3, 145, 3.1, 4),
(4, 240, 3.6, 3),
(5, 375, 4.6, 2),
(6, 500, 5.1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `paliers_thermo`
--

CREATE TABLE `paliers_thermo` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `palier` smallint(5) UNSIGNED NOT NULL,
  `prix` float NOT NULL,
  `position` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `paliers_thermo`
--

INSERT INTO `paliers_thermo` (`id`, `palier`, `prix`, `position`) VALUES
(1, 350, 4, 1);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id_user` smallint(5) UNSIGNED NOT NULL,
  `first_name` varchar(30) NOT NULL DEFAULT '',
  `last_name` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(190) NOT NULL DEFAULT '',
  `phone` varchar(20) NOT NULL DEFAULT '',
  `pseudo` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `user_type` varchar(20) NOT NULL DEFAULT 'user',
  `secure_key` varchar(190) NOT NULL DEFAULT '',
  `subscr_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `date_add` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `gender` enum('m','f') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id_user`, `first_name`, `last_name`, `email`, `phone`, `pseudo`, `password`, `user_type`, `secure_key`, `subscr_confirmed`, `date_add`, `gender`) VALUES
(34, 'admin', 'admin', 'damien@thoorens.fr', '0606060606', 'admin', '$2y$10$BEBEX5yYJFafr.ew6DF2HOH5ubPJ53Ho21QaqEV6XqxqSJ0IkEOly', 'admin', 'be18973e68b14b51d2b3598a947c981ddfe9d16ac1038af1e5303f9bdb17a31e238884df4ea675ac1b556731622e63d45d58', 1, '2020-08-04 09:56:22', NULL),
(35, 'user', 'user', 'user@user.fr', '0123456789', 'user', '$2y$10$Y/SW6mlSpnBuhiCXk8JkWuc8lUduoxfi0sv6c9kmL/HcyWRO6AmSW', 'user', 'd3d0510e15e9c1e48d9daac23468535b4ad99f5cb5d1d9321db128c471a5e7c0f9f548aa647d0f3ad907fd12bedb8a18e457', 0, '2020-08-04 12:03:37', NULL),
(37, 'gre', 'gre', 'gegreg@rgeerg.gre', '0123456666', 'gre', '$2y$10$BCaOMsp9PXkUsSToUnnMLu6OTeqrO6JFW6cz5pG7c3qpMYokmonki', 'user', '00f04b4fe15a349049a7a623d7e2bff73c52fc968ed7eced973fd933e3069aa243d5c87d1b8d591b2e8a6cc1d4f0e3875ad7', 0, '2020-08-04 12:16:25', NULL),
(38, 'printer', 'printer', 'printer@example.com', '0606060606', 'printer', '$2y$10$54VUAm/Tl.BfTxzHhr1n4.QVEWX4.oss.ooYfM5s7MPrnOO6yWYkq', 'admprinter', 'c3413acb9d77ffe00934c608bcbc79fe057ff1e522b3b67736555f135107c9ee968647bf97b50542094e757e3a2752f39852', 1, '2020-08-04 12:34:04', NULL),
(39, 'kgg', 'rrg', 'jgfg@ggfg.gg', '1111111111', 'kll', '$2y$10$5CfUoAVs/ASBSAJcb6pOiePHdwXw3VhA5oBgTR4NJwSOFHuo6t2RG', 'user', 'b5c02863275abd839236959e0e8abefda9b86517e9f16e93c5b80950bbc321caccb7d2cc43930085611a7968110ff19f763b', 0, '2020-08-05 09:47:35', NULL),
(40, 'iugiuh', 'ouguiyg', 'ououh@iygi.khg', '0606060606', '&amp;aqw2ZSX', '$2y$10$z7Vjigp0zlxO3dJHYD6X6u91QD1fohJxKnd8rMuIJoGbJrhR4yaTC', 'user', '177b8f70f78002cc2ac0045e066de08e0723f4e8366c1e0fca352db5478b5734540453c7c0ee1a478c75439e30a27d537473', 0, '2020-08-06 12:15:46', NULL),
(55, 'Chris', 'Tophe', 'enmoijecrois@gmail.com', '0123456789', 'christophe', '$2y$10$RLUmoCsN1Hb4Qs0JZZ1tsOYbESPePV8N1JN/9I1dlI4x/QEL8wX1O', 'user', '83ba0ffdaca6f939828c5c63018a9ef74a68fc6e5d6ac270d46c1e523a7bce778c41a94c6c494d71c47305ddc05cc0f2dd1a', 1, '2020-08-18 19:38:58', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id_address`),
  ADD KEY `address_country` (`id_country`),
  ADD KEY `address_user` (`id_user`);

--
-- Index pour la table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id_country`);

--
-- Index pour la table `dossier_color`
--
ALTER TABLE `dossier_color`
  ADD PRIMARY KEY (`id_dossier_color`);

--
-- Index pour la table `key_value`
--
ALTER TABLE `key_value`
  ADD PRIMARY KEY (`sKey`);

--
-- Index pour la table `paliers_couleur`
--
ALTER TABLE `paliers_couleur`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `paliers_nb`
--
ALTER TABLE `paliers_nb`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `paliers_spimetal`
--
ALTER TABLE `paliers_spimetal`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `paliers_spiplast`
--
ALTER TABLE `paliers_spiplast`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `paliers_thermo`
--
ALTER TABLE `paliers_thermo`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `secure_key` (`secure_key`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `pseudo` (`pseudo`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `address`
--
ALTER TABLE `address`
  MODIFY `id_address` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `country`
--
ALTER TABLE `country`
  MODIFY `id_country` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `dossier_color`
--
ALTER TABLE `dossier_color`
  MODIFY `id_dossier_color` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `paliers_couleur`
--
ALTER TABLE `paliers_couleur`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `paliers_nb`
--
ALTER TABLE `paliers_nb`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `paliers_spimetal`
--
ALTER TABLE `paliers_spimetal`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `paliers_spiplast`
--
ALTER TABLE `paliers_spiplast`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `paliers_thermo`
--
ALTER TABLE `paliers_thermo`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_country` FOREIGN KEY (`id_country`) REFERENCES `country` (`id_country`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `address_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
