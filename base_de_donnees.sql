-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Mer 26 Juin 2019 à 18:53
-- Version du serveur :  5.7.14
-- Version de PHP :  7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `autoscan`
--

-- --------------------------------------------------------

--
-- Structure de la table `affectation`
--

CREATE TABLE `affectation` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `caisse_id` int(11) DEFAULT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `actif` tinyint(1) NOT NULL,
  `date` datetime NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `affectation`
--

INSERT INTO `affectation` (`id`, `utilisateur_id`, `caisse_id`, `version`, `actif`, `date`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`) VALUES
(1, 2, 1, 1, 1, '2019-06-15 10:01:30', 'adminuser', 'adminuser', NULL, '2019-06-15 10:01:30', '2019-06-15 10:01:30'),
(2, 1, 2, 1, 1, '2019-06-16 17:26:33', 'adminuser', 'adminuser', NULL, '2019-06-16 17:26:34', '2019-06-16 17:26:34');

-- --------------------------------------------------------

--
-- Structure de la table `affectation_piste`
--

CREATE TABLE `affectation_piste` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `caisse_id` int(11) DEFAULT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `actif` tinyint(1) NOT NULL,
  `date` datetime NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `affectation_piste`
--

INSERT INTO `affectation_piste` (`id`, `utilisateur_id`, `caisse_id`, `version`, `actif`, `date`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`) VALUES
(1, 3, 1, 1, 1, '2019-06-23 10:26:22', 'adminuser', 'adminuser', NULL, '2019-06-23 10:26:23', '2019-06-23 10:26:23'),
(2, 4, 2, 1, 1, '2019-06-23 10:30:28', 'adminuser', 'adminuser', NULL, '2019-06-23 10:30:28', '2019-06-23 10:30:28');

-- --------------------------------------------------------

--
-- Structure de la table `caisse`
--

CREATE TABLE `caisse` (
  `id` int(11) NOT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `numero` int(11) NOT NULL,
  `actif` tinyint(1) NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL,
  `solde` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `caisse`
--

INSERT INTO `caisse` (`id`, `version`, `numero`, `actif`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`, `solde`) VALUES
(1, 7, 1, 1, 'adminuser', 'adminuser', NULL, '2019-06-09 15:05:20', '2019-06-26 18:23:36', 28270),
(2, 3, 2, 1, 'adminuser', 'adminuser', NULL, '2019-06-09 15:05:44', '2019-06-23 10:54:11', 10000);

-- --------------------------------------------------------

--
-- Structure de la table `carrosserie`
--

CREATE TABLE `carrosserie` (
  `id` int(11) NOT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `carrosserie`
--

INSERT INTO `carrosserie` (`id`, `version`, `libelle`, `code`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`) VALUES
(1, 1, 'Berline', 'Berline', 'adminuser', 'adminuser', NULL, '2019-05-07 15:34:54', '2019-05-07 15:34:54'),
(2, 1, 'Cabriolet', 'Cabriolet', 'adminuser', 'adminuser', NULL, '2019-06-08 09:04:48', '2019-06-08 09:04:48');

-- --------------------------------------------------------

--
-- Structure de la table `categoriecontrole`
--

CREATE TABLE `categoriecontrole` (
  `id` int(11) NOT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `categoriecontrole`
--

INSERT INTO `categoriecontrole` (`id`, `version`, `libelle`, `code`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`) VALUES
(1, 2, 'Pneus', 'PN', 'adminuser', 'adminuser', NULL, '2019-06-09 13:44:38', '2019-06-09 13:45:56'),
(2, 1, 'Eclairages', 'ES', 'adminuser', 'adminuser', NULL, '2019-06-09 13:45:31', '2019-06-09 13:45:31'),
(3, 1, 'Géométrie', 'GE', 'adminuser', 'adminuser', NULL, '2019-06-23 17:39:16', '2019-06-23 17:39:16'),
(4, 1, 'Frein', 'FR', 'adminuser', 'adminuser', NULL, '2019-06-23 17:39:35', '2019-06-23 17:39:35'),
(5, 1, 'Direction', 'DI', 'adminuser', 'adminuser', NULL, '2019-06-23 17:39:55', '2019-06-23 17:39:55'),
(6, 2, 'Identification', 'ID', 'adminuser', 'adminuser', NULL, '2019-06-23 17:40:16', '2019-06-23 17:41:14'),
(7, 1, 'Nuisance et accessoires', 'NA', 'adminuser', 'adminuser', NULL, '2019-06-23 17:40:52', '2019-06-23 17:40:52'),
(8, 1, 'Signalisation', 'SI', 'adminuser', 'adminuser', NULL, '2019-06-23 17:41:34', '2019-06-23 17:41:34');

-- --------------------------------------------------------

--
-- Structure de la table `chaine`
--

CREATE TABLE `chaine` (
  `id` int(11) NOT NULL,
  `piste_id` int(11) DEFAULT NULL,
  `caisse_id` int(11) DEFAULT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `actif` tinyint(1) NOT NULL,
  `surrendezvous` tinyint(1) NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `chaine`
--

INSERT INTO `chaine` (`id`, `piste_id`, `caisse_id`, `version`, `actif`, `surrendezvous`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`) VALUES
(1, 1, 1, 1, 1, 1, 'adminuser', 'adminuser', NULL, '2019-06-11 10:02:50', '2019-06-11 10:02:50'),
(2, 2, 2, 1, 1, 0, 'adminuser', 'adminuser', NULL, '2019-06-11 10:03:12', '2019-06-11 10:03:12');

-- --------------------------------------------------------

--
-- Structure de la table `codemaharesultat`
--

CREATE TABLE `codemaharesultat` (
  `id` int(11) NOT NULL,
  `controle_id` int(11) DEFAULT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `detail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `actif` tinyint(1) NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL,
  `reussite` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `codemaharesultat`
--

INSERT INTO `codemaharesultat` (`id`, `controle_id`, `version`, `libelle`, `code`, `detail`, `actif`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`, `reussite`) VALUES
(1, 2, 1, 'Trop Haut', '0', NULL, 1, 'adminuser', 'adminuser', NULL, '2019-06-23 14:42:22', '2019-06-23 14:42:22', 0),
(2, 2, 2, 'Correct', '1', NULL, 1, 'adminuser', 'adminuser', NULL, '2019-06-23 14:43:42', '2019-06-23 16:39:31', 1),
(3, 2, 2, 'Trop bas', '2', NULL, 1, 'adminuser', 'adminuser', NULL, '2019-06-23 14:44:07', '2019-06-23 14:45:07', 0),
(4, 2, 2, 'Non mesuré', '9', NULL, 1, 'adminuser', 'adminuser', NULL, '2019-06-23 14:44:50', '2019-06-23 14:45:22', 0),
(5, 1, 2, 'OK', '1', 'Valeur de test', 1, 'adminuser', 'adminuser', NULL, '2019-06-23 14:47:42', '2019-06-23 16:38:53', 1),
(6, 1, 2, 'Mauvais', '0', NULL, 1, 'adminuser', 'adminuser', NULL, '2019-06-23 14:48:10', '2019-06-26 00:59:49', 0),
(7, 3, 3, 'Correct', '1', NULL, 1, 'adminuser', 'adminuser', NULL, '2019-06-23 17:45:30', '2019-06-23 17:48:59', 1),
(8, 3, 2, 'Mauvais', '0', NULL, 1, 'adminuser', 'adminuser', NULL, '2019-06-23 17:48:12', '2019-06-23 17:48:39', 0);

-- --------------------------------------------------------

--
-- Structure de la table `controle`
--

CREATE TABLE `controle` (
  `id` int(11) NOT NULL,
  `categorie_id` int(11) DEFAULT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `detail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `actif` tinyint(1) NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `controle`
--

INSERT INTO `controle` (`id`, `categorie_id`, `version`, `libelle`, `code`, `detail`, `actif`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`) VALUES
(1, 1, 3, 'Banc de Freins', 'BDF', 'Controle des freins', 1, 'adminuser', 'adminuser', NULL, '2019-06-09 14:25:17', '2019-06-23 17:35:43'),
(2, 2, 2, 'Feux de croisement gauche', '0490', NULL, 1, 'adminuser', 'adminuser', NULL, '2019-06-23 14:09:55', '2019-06-23 17:29:48'),
(3, 7, 1, 'Echappement', 'EP', NULL, 1, 'adminuser', 'adminuser', NULL, '2019-06-23 17:42:08', '2019-06-23 17:42:08');

-- --------------------------------------------------------

--
-- Structure de la table `droitvisite`
--

CREATE TABLE `droitvisite` (
  `id` int(11) NOT NULL,
  `genre_id` int(11) DEFAULT NULL,
  `usage_id` int(11) DEFAULT NULL,
  `carrosserie_id` int(11) DEFAULT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `ptacmin` double NOT NULL,
  `ptacmax` double NOT NULL,
  `montant` double NOT NULL,
  `timbre` double NOT NULL,
  `anasser` double NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `droitvisite`
--

INSERT INTO `droitvisite` (`id`, `genre_id`, `usage_id`, `carrosserie_id`, `version`, `ptacmin`, `ptacmax`, `montant`, `timbre`, `anasser`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`) VALUES
(1, 1, 1, 1, 1, 0, 10, 10000, 5000, 1000, 'adminuser', 'adminuser', NULL, '2019-05-13 01:03:10', '2019-05-13 01:03:10');

-- --------------------------------------------------------

--
-- Structure de la table `genre`
--

CREATE TABLE `genre` (
  `id` int(11) NOT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL,
  `ptacmin` double NOT NULL,
  `ptacmax` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `genre`
--

INSERT INTO `genre` (`id`, `version`, `libelle`, `code`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`, `ptacmin`, `ptacmax`) VALUES
(1, 3, 'Véhicule léger', 'VL', 'adminuser', 'adminuser', NULL, '2019-05-05 18:46:01', '2019-06-08 08:57:12', 0, 3.5),
(2, 3, 'Poids lourd', 'PL', 'adminuser', 'adminuser', NULL, '2019-05-05 18:55:15', '2019-06-08 08:56:23', 3.50000000001, 1000000),
(3, 2, 'Transport public', 'TL', 'adminuser', 'adminuser', '2019-06-08 08:56:54', '2019-05-13 13:16:11', '2019-05-13 13:16:11', 2100, 0);

-- --------------------------------------------------------

--
-- Structure de la table `immatriculation`
--

CREATE TABLE `immatriculation` (
  `id` int(11) NOT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL,
  `typeImmatriculation_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `immatriculation`
--

INSERT INTO `immatriculation` (`id`, `version`, `code`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`, `typeImmatriculation_id`) VALUES
(1, 1, 'AB2156MD', 'adminuser', 'adminuser', NULL, '2019-05-12 23:44:20', '2019-05-12 23:44:20', 2),
(2, 1, 'AV5555MD', 'adminuser', 'adminuser', NULL, '2019-05-12 23:45:35', '2019-05-12 23:45:35', 2);

-- --------------------------------------------------------

--
-- Structure de la table `marque`
--

CREATE TABLE `marque` (
  `id` int(11) NOT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `marque`
--

INSERT INTO `marque` (`id`, `version`, `libelle`, `code`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`) VALUES
(1, 2, 'TOYOTA', 'TOYOTA', 'adminuser', 'adminuser', '2019-05-05 13:41:10', '2019-05-05 13:25:24', '2019-05-05 13:25:24'),
(2, 2, 'TOYOTA', 'TOYOTA', 'adminuser', 'adminuser', '2019-05-05 13:45:23', '2019-05-05 13:41:25', '2019-05-05 13:41:25'),
(3, 1, 'TOYOTA', 'TOYOTA', 'adminuser', 'adminuser', NULL, '2019-05-05 13:45:41', '2019-05-05 13:45:41'),
(4, 1, 'PEUGEOT', 'PEUGEOT', 'adminuser', 'adminuser', NULL, '2019-05-05 15:34:56', '2019-05-05 15:34:56'),
(5, 1, 'RENAULT', 'RENAULT', 'adminuser', 'adminuser', NULL, '2019-05-05 16:08:45', '2019-05-05 16:08:45');

-- --------------------------------------------------------

--
-- Structure de la table `modele`
--

CREATE TABLE `modele` (
  `id` int(11) NOT NULL,
  `marque_id` int(11) DEFAULT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `modele`
--

INSERT INTO `modele` (`id`, `marque_id`, `version`, `libelle`, `code`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`) VALUES
(1, 3, 2, 'COROLLA', 'COROLLA', 'adminuser', 'adminuser', '2019-05-05 17:19:16', '2019-05-05 17:12:27', '2019-05-05 17:12:27'),
(2, 3, 1, 'COROLLA', 'COROLLA', 'adminuser', 'adminuser', NULL, '2019-05-05 17:19:32', '2019-05-05 17:19:32'),
(3, 3, 1, 'AVENSIS', 'AVENSIS', 'adminuser', 'adminuser', NULL, '2019-06-15 20:44:09', '2019-06-15 20:44:09'),
(4, 4, 1, '405', '405', 'adminuser', 'adminuser', NULL, '2019-06-15 20:44:32', '2019-06-15 20:44:32');

-- --------------------------------------------------------

--
-- Structure de la table `penalite`
--

CREATE TABLE `penalite` (
  `id` int(11) NOT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `dureemin` int(11) NOT NULL,
  `dureemax` int(11) NOT NULL,
  `pourcentage` int(11) NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `penalite`
--

INSERT INTO `penalite` (`id`, `version`, `dureemin`, `dureemax`, `pourcentage`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`) VALUES
(1, 2, 1, 15, 25, 'adminuser', 'adminuser', NULL, '2019-05-15 10:37:37', '2019-06-18 14:45:15'),
(2, 1, 16, 31, 50, 'adminuser', 'adminuser', NULL, '2019-05-15 10:38:03', '2019-05-15 10:38:03'),
(3, 1, 40, 9999999, 75, 'adminuser', 'adminuser', NULL, '2019-05-15 10:39:30', '2019-05-15 10:39:30');

-- --------------------------------------------------------

--
-- Structure de la table `piste`
--

CREATE TABLE `piste` (
  `id` int(11) NOT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `numero` int(11) NOT NULL,
  `actif` tinyint(1) NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `piste`
--

INSERT INTO `piste` (`id`, `version`, `numero`, `actif`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`) VALUES
(1, 1, 1, 1, 'adminuser', 'adminuser', NULL, '2019-06-09 15:42:51', '2019-06-09 15:42:51'),
(2, 1, 2, 1, 'adminuser', 'adminuser', NULL, '2019-06-09 15:43:15', '2019-06-09 15:43:15');

-- --------------------------------------------------------

--
-- Structure de la table `proprietaire`
--

CREATE TABLE `proprietaire` (
  `id` char(36) COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  `version` int(11) NOT NULL DEFAULT '1',
  `numpiece` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `autreTelephone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `adresse` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `personneMorale` tinyint(1) NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `typePiece_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `proprietaire`
--

INSERT INTO `proprietaire` (`id`, `version`, `numpiece`, `nom`, `prenom`, `telephone`, `autreTelephone`, `adresse`, `email`, `personneMorale`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`, `path`, `typePiece_id`) VALUES
('3967ceb5-7589-11e9-bc8d-1065305557be', 1, '1045/BTK', 'Diarra', 'Ousmane', '78965432', '88976543', 'Lafiabougou', 'ousmane.diarra@yahho.fr', 0, 'adminuser', 'adminuser', NULL, '2019-05-13 14:11:50', '2019-05-13 14:11:50', '754339a16c09c199941cbff8ff6154d346eefa63.jpeg', 1),
('d929ca64-91e5-11e9-b872-1065305557be', 1, '234556788', 'Tounkara', 'Oumou', '76890765', NULL, 'Djélibougou', NULL, 0, 'adminuser', 'adminuser', NULL, '2019-06-18 16:26:38', '2019-06-18 16:26:38', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `quittance`
--

CREATE TABLE `quittance` (
  `id` int(11) NOT NULL,
  `visite_id` int(11) DEFAULT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `numero` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `penalite` double NOT NULL,
  `montantvisite` double NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL,
  `retard` int(11) NOT NULL,
  `paye` tinyint(1) NOT NULL,
  `tva` double NOT NULL,
  `timbre` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `quittance`
--

INSERT INTO `quittance` (`id`, `visite_id`, `version`, `numero`, `penalite`, `montantvisite`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`, `retard`, `paye`, `tva`, `timbre`) VALUES
(16, 21, 2, 'BKO1561573346', 3101.25, 4135, 'adminuser', 'adminuser', NULL, '2019-06-26 18:22:26', '2019-06-26 18:23:36', 2002, 1, 744.3, 120);

-- --------------------------------------------------------

--
-- Structure de la table `resultat`
--

CREATE TABLE `resultat` (
  `id` int(11) NOT NULL,
  `controle_id` int(11) DEFAULT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `commentaire` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `succes` tinyint(1) NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL,
  `visite_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `resultat`
--

INSERT INTO `resultat` (`id`, `controle_id`, `version`, `commentaire`, `succes`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`, `visite_id`) VALUES
(25, 1, 1, 'OK', 1, 'adminuser', 'adminuser', NULL, '2019-06-26 18:24:09', '2019-06-26 18:24:09', 21),
(26, 2, 1, 'Trop bas', 0, 'adminuser', 'adminuser', NULL, '2019-06-26 18:24:09', '2019-06-26 18:24:09', 21),
(27, 3, 1, 'Correct', 1, 'adminuser', 'adminuser', NULL, '2019-06-26 18:24:09', '2019-06-26 18:24:09', 21);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `version` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `role`
--

INSERT INTO `role` (`id`, `name`, `roles`, `version`) VALUES
(1, 'DEFAUT', 'a:1:{i:0;s:9:"ROLE_USER";}', 1),
(2, 'SUPERVISEUR', 'a:1:{i:0;s:16:"ROLE_SUPERVISEUR";}', 1),
(3, 'CAISSIER', 'a:1:{i:0;s:13:"ROLE_CAISSIER";}', 1),
(4, 'ENREGISTREMENT', 'a:1:{i:0;s:19:"ROLE_ENREGISTREMENT";}', 1),
(5, 'CAISSIER PRINCIPAL', 'a:1:{i:0;s:23:"ROLE_CAISSIER_PRINCIPAL";}', 1),
(6, 'DELIVRANCE', 'a:1:{i:0;s:15:"ROLE_DELIVRANCE";}', 1),
(7, 'CONTROLLEUR', 'a:1:{i:0;s:16:"ROLE_CONTROLLEUR";}', 1);

-- --------------------------------------------------------

--
-- Structure de la table `typeimmatriculation`
--

CREATE TABLE `typeimmatriculation` (
  `id` int(11) NOT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `typeimmatriculation`
--

INSERT INTO `typeimmatriculation` (`id`, `version`, `description`, `code`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`) VALUES
(1, 1, 'ETAT', 'ETAT', 'adminuser', 'adminuser', NULL, '2019-05-12 23:43:35', '2019-05-12 23:43:35'),
(2, 1, 'PERSONNEL', 'PERSONNEL', 'adminuser', 'adminuser', NULL, '2019-05-12 23:43:57', '2019-05-12 23:43:57');

-- --------------------------------------------------------

--
-- Structure de la table `typepiece`
--

CREATE TABLE `typepiece` (
  `id` int(11) NOT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `typepiece`
--

INSERT INTO `typepiece` (`id`, `version`, `code`, `libelle`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`) VALUES
(1, 1, 'CIN', 'Carte d\'identité nationale', 'adminuser', 'adminuser', NULL, '2019-05-13 13:46:19', '2019-05-13 13:46:19'),
(2, 1, 'NINA', 'Carte NINA', 'adminuser', 'adminuser', NULL, '2019-05-13 14:02:07', '2019-05-13 14:02:07'),
(3, 2, 'RCCM', 'Registre de commerce', 'adminuser', 'adminuser', NULL, '2019-05-13 14:07:26', '2019-05-13 14:07:50'),
(4, 1, 'NINAE', 'NINA Entreprise', 'adminuser', 'adminuser', NULL, '2019-05-13 14:09:19', '2019-05-13 14:09:19'),
(5, 1, 'ACTECREATION', 'Acte de création', 'adminuser', 'adminuser', NULL, '2019-05-13 14:09:53', '2019-05-13 14:09:53');

-- --------------------------------------------------------

--
-- Structure de la table `typeusage`
--

CREATE TABLE `typeusage` (
  `id` int(11) NOT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `typeusage`
--

INSERT INTO `typeusage` (`id`, `version`, `libelle`, `code`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`) VALUES
(1, 2, 'Véhicule Personnel', 'VP', 'adminuser', 'adminuser', NULL, '2019-05-07 18:03:00', '2019-06-08 09:05:47'),
(2, 1, 'Véhicule Utilitaire', 'VU', 'adminuser', 'adminuser', NULL, '2019-06-08 09:06:33', '2019-06-08 09:06:33'),
(3, 1, 'AUTO ECOLE', 'AE', 'adminuser', 'adminuser', NULL, '2019-06-08 09:07:35', '2019-06-08 09:07:35');

-- --------------------------------------------------------

--
-- Structure de la table `typevehicule`
--

CREATE TABLE `typevehicule` (
  `id` int(11) NOT NULL,
  `genre_id` int(11) DEFAULT NULL,
  `carrosserie_id` int(11) DEFAULT NULL,
  `usage_id` int(11) DEFAULT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `montantrevisite` double NOT NULL,
  `montantvisite` double NOT NULL,
  `delai` int(11) NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL,
  `timbre` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `typevehicule`
--

INSERT INTO `typevehicule` (`id`, `genre_id`, `carrosserie_id`, `usage_id`, `version`, `libelle`, `montantrevisite`, `montantvisite`, `delai`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`, `timbre`) VALUES
(1, 1, 1, 1, 5, 'VL_Berline_Véhicule Personnel', 1381, 4135, 30, 'adminuser', 'adminuser', NULL, '2019-06-08 09:46:41', '2019-06-26 02:09:00', 120);

-- --------------------------------------------------------

--
-- Structure de la table `usage`
--

CREATE TABLE `usage` (
  `id` int(11) NOT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_role`
--

CREATE TABLE `user_role` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `user_role`
--

INSERT INTO `user_role` (`user_id`, `group_id`) VALUES
(2, 3),
(3, 7),
(4, 7);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`, `version`, `nom`, `prenom`, `telephone`) VALUES
(1, 'adminuser', 'adminuser', 'djibrilland@yahoo.fr', 'djibrilland@yahoo.fr', 1, 't7ctuj0z2zkk8gsg8cs448kg4okw8ck', 'vprgGpn9BBZRSSfPYnr1AlScwYigRnyremzNvSVCnyzezoyhX3u3C+UuQMNWrVVfNKGD48Y5aBdNRbv87rh+rQ==', '2019-06-26 11:23:02', 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:16:"ROLE_SUPER_ADMIN";}', 0, NULL, 24, 'Admin', 'Admin', NULL),
(2, 'toto', 'toto', 'toto@toto.test', 'toto@toto.test', 1, '1l7hv37agm68wwww0cgkgk08cwkc8gc', 'K/dfj/FqzX/SlwqHx0vm70xFYVBarn87q+mPnFkiQHo/nH1R4Tk4QG3nnEs/MYIl+eXQIq/Ncfusc9MNgpzWcw==', '2019-06-23 17:23:59', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 9, 'Doe', 'Toto', NULL),
(3, 'controlleur 1', 'controlleur 1', 'controlleur@autoscan.ml', 'controlleur@autoscan.ml', 1, 'i9gg7h1ymjkg4gskk8oso8044scg884', '9LvvnYYgl2+f8UYeGE27mFtLO9ukyBh5CCoCgIMXeYQ02SpHQgMI7ClVL51L6F6cSeGaaLZDbwJlWjh54zHGEQ==', NULL, 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 1, 'Controlleur', '1', NULL),
(4, 'controlleur2', 'controlleur2', 'controlleur2@autoscan.ml', 'controlleur2@autoscan.ml', 1, 'hfdqjlaix14wwsk8osc8kogg0s48os0', 'bUZvC+3yMnku603IgayYKe8B1oHWwUagSnfFaG6Ak5QdgFP4cZj7vzwGJ+wD4c8cKDfOXt/uVjMDXOrZTxgTvQ==', '2019-06-23 11:49:49', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 5, 'Controlleu', '2', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `vehicule`
--

CREATE TABLE `vehicule` (
  `id` int(11) NOT NULL,
  `modele_id` int(11) DEFAULT NULL,
  `proprietaire_id` char(36) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(DC2Type:guid)',
  `version` int(11) NOT NULL DEFAULT '1',
  `chassis` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `carteGrise` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dateCarteGrise` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dateMiseCirculation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ptac` double NOT NULL,
  `place` int(11) NOT NULL,
  `puissance` int(11) NOT NULL,
  `kilometrage` int(11) NOT NULL,
  `couleur` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL,
  `type_vehicule_id` int(11) DEFAULT NULL,
  `type_immatriculation_id` int(11) DEFAULT NULL,
  `immatriculation` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `vehicule`
--

INSERT INTO `vehicule` (`id`, `modele_id`, `proprietaire_id`, `version`, `chassis`, `carteGrise`, `dateCarteGrise`, `dateMiseCirculation`, `ptac`, `place`, `puissance`, `kilometrage`, `couleur`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`, `type_vehicule_id`, `type_immatriculation_id`, `immatriculation`) VALUES
(1, 2, '3967ceb5-7589-11e9-bc8d-1065305557be', 3, '7654321', '1234567', '2014-01-01', '2014-01-01', 2, 5, 12, 15000, 'Bleu', 'adminuser', 'adminuser', NULL, '2019-05-15 10:05:33', '2019-06-08 09:59:52', 1, 2, 'AD4178MD'),
(2, 2, '3967ceb5-7589-11e9-bc8d-1065305557be', 2, 'qsdfghjk', 'sdfghj45678', '2014-01-01', '2014-01-01', 3, 6, 11, 34000, 'BLEU', 'adminuser', 'adminuser', NULL, '2019-05-18 11:33:58', '2019-06-08 09:58:35', 1, 2, 'AE6789MD'),
(3, 3, 'd929ca64-91e5-11e9-b872-1065305557be', 1, 'azolfjfkfl', '12238900', '2016-10-01', '2010-01-01', 4, 5, 10, 150000, 'Vert', 'adminuser', 'adminuser', NULL, '2019-06-18 00:00:00', '2019-06-18 00:00:00', 1, 1, 'VG-4567-MD');

-- --------------------------------------------------------

--
-- Structure de la table `visite`
--

CREATE TABLE `visite` (
  `id` int(11) NOT NULL,
  `visite_id` int(11) DEFAULT NULL,
  `vehicule_id` int(11) DEFAULT NULL,
  `penalite_id` int(11) DEFAULT NULL,
  `chaine_id` int(11) DEFAULT NULL,
  `quittance_id` int(11) DEFAULT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `observations` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `statut` int(11) NOT NULL,
  `numeroCertificat` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `dateValidite` datetime DEFAULT NULL,
  `revisite` tinyint(1) NOT NULL,
  `cree_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modifier_par` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `visite`
--

INSERT INTO `visite` (`id`, `visite_id`, `vehicule_id`, `penalite_id`, `chaine_id`, `quittance_id`, `version`, `observations`, `statut`, `numeroCertificat`, `date`, `dateValidite`, `revisite`, `cree_par`, `modifier_par`, `deletedAt`, `date_creation`, `date_modification`) VALUES
(21, NULL, 1, NULL, 1, NULL, 3, NULL, 3, NULL, '2019-06-26 11:45:17', NULL, 0, 'adminuser', 'adminuser', NULL, '2019-06-26 11:45:17', '2019-06-26 18:24:09');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `affectation`
--
ALTER TABLE `affectation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F4DD61D3FB88E14F` (`utilisateur_id`),
  ADD KEY `IDX_F4DD61D327B4FEBF` (`caisse_id`);

--
-- Index pour la table `affectation_piste`
--
ALTER TABLE `affectation_piste`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_85D136DFFB88E14F` (`utilisateur_id`),
  ADD KEY `IDX_85D136DF27B4FEBF` (`caisse_id`);

--
-- Index pour la table `caisse`
--
ALTER TABLE `caisse`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `carrosserie`
--
ALTER TABLE `carrosserie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `categoriecontrole`
--
ALTER TABLE `categoriecontrole`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `chaine`
--
ALTER TABLE `chaine`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_94DA53ECC34065BC` (`piste_id`),
  ADD KEY `IDX_94DA53EC27B4FEBF` (`caisse_id`);

--
-- Index pour la table `codemaharesultat`
--
ALTER TABLE `codemaharesultat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_405FEAD7758926A6` (`controle_id`);

--
-- Index pour la table `controle`
--
ALTER TABLE `controle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E39396EBCF5E72D` (`categorie_id`);

--
-- Index pour la table `droitvisite`
--
ALTER TABLE `droitvisite`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_2C312D364296D31F` (`genre_id`),
  ADD KEY `IDX_2C312D362150E69A` (`usage_id`),
  ADD KEY `IDX_2C312D36C9FED38E` (`carrosserie_id`);

--
-- Index pour la table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `immatriculation`
--
ALTER TABLE `immatriculation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_BE73422E7203B12E` (`typeImmatriculation_id`);

--
-- Index pour la table `marque`
--
ALTER TABLE `marque`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `modele`
--
ALTER TABLE `modele`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_100285584827B9B2` (`marque_id`);

--
-- Index pour la table `penalite`
--
ALTER TABLE `penalite`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `piste`
--
ALTER TABLE `piste`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `proprietaire`
--
ALTER TABLE `proprietaire`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_69E399D661102376` (`typePiece_id`);

--
-- Index pour la table `quittance`
--
ALTER TABLE `quittance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_D57587DDC1C5DC59` (`visite_id`);

--
-- Index pour la table `resultat`
--
ALTER TABLE `resultat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E7DB5DE2758926A6` (`controle_id`),
  ADD KEY `IDX_E7DB5DE2C1C5DC59` (`visite_id`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_57698A6A5E237E06` (`name`);

--
-- Index pour la table `typeimmatriculation`
--
ALTER TABLE `typeimmatriculation`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `typepiece`
--
ALTER TABLE `typepiece`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `typeusage`
--
ALTER TABLE `typeusage`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `typevehicule`
--
ALTER TABLE `typevehicule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8C3013C34296D31F` (`genre_id`),
  ADD KEY `IDX_8C3013C3C9FED38E` (`carrosserie_id`),
  ADD KEY `IDX_8C3013C32150E69A` (`usage_id`);

--
-- Index pour la table `usage`
--
ALTER TABLE `usage`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`user_id`,`group_id`),
  ADD KEY `IDX_2DE8C6A3A76ED395` (`user_id`),
  ADD KEY `IDX_2DE8C6A3FE54D947` (`group_id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_1D1C63B392FC23A8` (`username_canonical`),
  ADD UNIQUE KEY `UNIQ_1D1C63B3A0D96FBF` (`email_canonical`);

--
-- Index pour la table `vehicule`
--
ALTER TABLE `vehicule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_292FFF1DAC14B70A` (`modele_id`),
  ADD KEY `IDX_292FFF1D76C50E4A` (`proprietaire_id`),
  ADD KEY `IDX_292FFF1D153E280` (`type_vehicule_id`),
  ADD KEY `IDX_292FFF1D38B0D8B5` (`type_immatriculation_id`);

--
-- Index pour la table `visite`
--
ALTER TABLE `visite`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_B09C8CBBB7BDE8B9` (`quittance_id`),
  ADD KEY `IDX_B09C8CBBC1C5DC59` (`visite_id`),
  ADD KEY `IDX_B09C8CBB4A4A3511` (`vehicule_id`),
  ADD KEY `IDX_B09C8CBBD0CCF327` (`penalite_id`),
  ADD KEY `IDX_B09C8CBB3129D93D` (`chaine_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `affectation`
--
ALTER TABLE `affectation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `affectation_piste`
--
ALTER TABLE `affectation_piste`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `caisse`
--
ALTER TABLE `caisse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `carrosserie`
--
ALTER TABLE `carrosserie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `categoriecontrole`
--
ALTER TABLE `categoriecontrole`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `chaine`
--
ALTER TABLE `chaine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `codemaharesultat`
--
ALTER TABLE `codemaharesultat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `controle`
--
ALTER TABLE `controle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `droitvisite`
--
ALTER TABLE `droitvisite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `genre`
--
ALTER TABLE `genre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `immatriculation`
--
ALTER TABLE `immatriculation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `marque`
--
ALTER TABLE `marque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `modele`
--
ALTER TABLE `modele`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `penalite`
--
ALTER TABLE `penalite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `piste`
--
ALTER TABLE `piste`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `quittance`
--
ALTER TABLE `quittance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT pour la table `resultat`
--
ALTER TABLE `resultat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `typeimmatriculation`
--
ALTER TABLE `typeimmatriculation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `typepiece`
--
ALTER TABLE `typepiece`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `typeusage`
--
ALTER TABLE `typeusage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `typevehicule`
--
ALTER TABLE `typevehicule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `usage`
--
ALTER TABLE `usage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `vehicule`
--
ALTER TABLE `vehicule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `visite`
--
ALTER TABLE `visite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `affectation`
--
ALTER TABLE `affectation`
  ADD CONSTRAINT `FK_F4DD61D327B4FEBF` FOREIGN KEY (`caisse_id`) REFERENCES `caisse` (`id`),
  ADD CONSTRAINT `FK_F4DD61D3FB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `affectation_piste`
--
ALTER TABLE `affectation_piste`
  ADD CONSTRAINT `FK_85D136DF27B4FEBF` FOREIGN KEY (`caisse_id`) REFERENCES `piste` (`id`),
  ADD CONSTRAINT `FK_85D136DFFB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `chaine`
--
ALTER TABLE `chaine`
  ADD CONSTRAINT `FK_94DA53EC27B4FEBF` FOREIGN KEY (`caisse_id`) REFERENCES `caisse` (`id`),
  ADD CONSTRAINT `FK_94DA53ECC34065BC` FOREIGN KEY (`piste_id`) REFERENCES `piste` (`id`);

--
-- Contraintes pour la table `codemaharesultat`
--
ALTER TABLE `codemaharesultat`
  ADD CONSTRAINT `FK_405FEAD7758926A6` FOREIGN KEY (`controle_id`) REFERENCES `controle` (`id`);

--
-- Contraintes pour la table `controle`
--
ALTER TABLE `controle`
  ADD CONSTRAINT `FK_E39396EBCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categoriecontrole` (`id`);

--
-- Contraintes pour la table `droitvisite`
--
ALTER TABLE `droitvisite`
  ADD CONSTRAINT `FK_2C312D362150E69A` FOREIGN KEY (`usage_id`) REFERENCES `typeusage` (`id`),
  ADD CONSTRAINT `FK_2C312D364296D31F` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`id`),
  ADD CONSTRAINT `FK_2C312D36C9FED38E` FOREIGN KEY (`carrosserie_id`) REFERENCES `carrosserie` (`id`);

--
-- Contraintes pour la table `immatriculation`
--
ALTER TABLE `immatriculation`
  ADD CONSTRAINT `FK_BE73422E7203B12E` FOREIGN KEY (`typeImmatriculation_id`) REFERENCES `typeimmatriculation` (`id`);

--
-- Contraintes pour la table `modele`
--
ALTER TABLE `modele`
  ADD CONSTRAINT `FK_100285584827B9B2` FOREIGN KEY (`marque_id`) REFERENCES `marque` (`id`);

--
-- Contraintes pour la table `proprietaire`
--
ALTER TABLE `proprietaire`
  ADD CONSTRAINT `FK_69E399D661102376` FOREIGN KEY (`typePiece_id`) REFERENCES `typepiece` (`id`);

--
-- Contraintes pour la table `quittance`
--
ALTER TABLE `quittance`
  ADD CONSTRAINT `FK_D57587DDC1C5DC59` FOREIGN KEY (`visite_id`) REFERENCES `visite` (`id`);

--
-- Contraintes pour la table `resultat`
--
ALTER TABLE `resultat`
  ADD CONSTRAINT `FK_E7DB5DE2758926A6` FOREIGN KEY (`controle_id`) REFERENCES `controle` (`id`),
  ADD CONSTRAINT `FK_E7DB5DE2C1C5DC59` FOREIGN KEY (`visite_id`) REFERENCES `visite` (`id`);

--
-- Contraintes pour la table `typevehicule`
--
ALTER TABLE `typevehicule`
  ADD CONSTRAINT `FK_8C3013C32150E69A` FOREIGN KEY (`usage_id`) REFERENCES `typeusage` (`id`),
  ADD CONSTRAINT `FK_8C3013C34296D31F` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`id`),
  ADD CONSTRAINT `FK_8C3013C3C9FED38E` FOREIGN KEY (`carrosserie_id`) REFERENCES `carrosserie` (`id`);

--
-- Contraintes pour la table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `FK_2DE8C6A3A76ED395` FOREIGN KEY (`user_id`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `FK_2DE8C6A3FE54D947` FOREIGN KEY (`group_id`) REFERENCES `role` (`id`);

--
-- Contraintes pour la table `vehicule`
--
ALTER TABLE `vehicule`
  ADD CONSTRAINT `FK_292FFF1D153E280` FOREIGN KEY (`type_vehicule_id`) REFERENCES `typevehicule` (`id`),
  ADD CONSTRAINT `FK_292FFF1D38B0D8B5` FOREIGN KEY (`type_immatriculation_id`) REFERENCES `typeimmatriculation` (`id`),
  ADD CONSTRAINT `FK_292FFF1D76C50E4A` FOREIGN KEY (`proprietaire_id`) REFERENCES `proprietaire` (`id`),
  ADD CONSTRAINT `FK_292FFF1DAC14B70A` FOREIGN KEY (`modele_id`) REFERENCES `modele` (`id`);

--
-- Contraintes pour la table `visite`
--
ALTER TABLE `visite`
  ADD CONSTRAINT `FK_B09C8CBB3129D93D` FOREIGN KEY (`chaine_id`) REFERENCES `chaine` (`id`),
  ADD CONSTRAINT `FK_B09C8CBB4A4A3511` FOREIGN KEY (`vehicule_id`) REFERENCES `vehicule` (`id`),
  ADD CONSTRAINT `FK_B09C8CBBB7BDE8B9` FOREIGN KEY (`quittance_id`) REFERENCES `quittance` (`id`),
  ADD CONSTRAINT `FK_B09C8CBBC1C5DC59` FOREIGN KEY (`visite_id`) REFERENCES `visite` (`id`),
  ADD CONSTRAINT `FK_B09C8CBBD0CCF327` FOREIGN KEY (`penalite_id`) REFERENCES `penalite` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
