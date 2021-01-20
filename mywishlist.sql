-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 06 jan. 2021 à 16:14
-- Version du serveur :  10.4.17-MariaDB
-- Version de PHP : 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mywishlist`
--

-- --------------------------------------------------------

--
-- Structure de la table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `liste_id` int(11) NOT NULL,
  `nom` text NOT NULL,
  `descr` text DEFAULT NULL,
  `img` varchar(250) DEFAULT 'default.png',
  `url` text DEFAULT NULL,
  `tarif` decimal(5,2) DEFAULT NULL,
  `cagnotteActive` varchar(250) NOT NULL DEFAULT 'false',
  `cagnotte` decimal(5,2) NOT NULL DEFAULT 0,
  `id_utilisateur` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `reserve` varchar(30) default 'false',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `item`
--

INSERT INTO `item` (`id`, `liste_id`, `nom`, `descr`, `img`, `url`, `tarif`, `cagnotteActive`, `cagnotte`, `id_utilisateur`, `message`, `reserve`) VALUES
(1, 2, 'Champagne', 'Bouteille de champagne + flutes + jeux à gratter', 'champagne.jpg', '', '20.00', 'true', '0.00', NULL, NULL, 'false'),
(2, 2, 'Musique', 'Partitions de piano à 4 mains', 'musique.jpg', '', '25.00', 'false', '0.00', NULL, NULL, 'Alessi'),
(3, 2, 'Exposition', 'Visite guidée de l’exposition ‘REGARDER’ à la galerie Poirel', 'poirelregarder.jpg', '', '14.00', 'false', '0.00', NULL, NULL, 'false'),
(4, 3, 'Goûter', 'Goûter au FIFNL', 'gouter.jpg', '', '20.00', 'false', '0.00', NULL, NULL, 'false'),
(5, 3, 'Projection', 'Projection courts-métrages au FIFNL', 'film.jpg', '', '10.00', 'false', '0.00', NULL, NULL, 'false'),
(6, 2, 'Bouquet', 'Bouquet de roses et Mots de Marion Renaud', 'rose.jpg', '', '16.00', 'false', '0.00', NULL, NULL, 'false'),
(7, 2, 'Diner Stanislas', 'Diner à La Table du Bon Roi Stanislas (Apéritif /Entrée / Plat / Vin / Dessert / Café / Digestif)', 'bonroi.jpg', '', '60.00', 'false', '0.00', NULL, NULL, 'false'),
(8, 3, 'Origami', 'Baguettes magiques en Origami en buvant un thé', 'origami.jpg', '', '12.00', 'false', '0.00', NULL, NULL, 'false'),
(9, 3, 'Livres', 'Livre bricolage avec petits-enfants + Roman', 'bricolage.jpg', '', '24.00', 'false', '0.00', NULL, NULL, 'Natasha '),
(10, 2, 'Diner  Grand Rue ', 'Diner au Grand’Ru(e) (Apéritif / Entrée / Plat / Vin / Dessert / Café)', 'grandrue.jpg', '', '59.00', 'false', '0.00', NULL, NULL, 'false'),
(11, 0, 'Visite guidée', 'Visite guidée personnalisée de Saint-Epvre jusqu’à Stanislas', 'place.jpg', '', '11.00', 'false', '0.00', NULL, NULL, 'false'),
(12, 2, 'Bijoux', 'Bijoux de manteau + Sous-verre pochette de disque + Lait après-soleil', 'bijoux.jpg', '', '29.00', 'false', '0.00', NULL, NULL, 'false'),
(19, 0, 'Jeu contacts', 'Jeu pour échange de contacts', 'contact.png', '', '5.00', 'false', '0.00', NULL, NULL, 'false'),
(22, 0, 'Concert', 'Un concert à Nancy', 'concert.jpg', '', '17.00', 'false', '0.00', NULL, NULL, 'false'),
(23, 1, 'Appart Hotel', 'Appart’hôtel Coeur de Ville, en plein centre-ville', 'apparthotel.jpg', '', '56.00', 'false', '0.00', NULL, NULL, 'Hubert'),
(24, 2, 'Hôtel d\'Haussonville', 'Hôtel d\'Haussonville, au coeur de la Vieille ville à deux pas de la place Stanislas', 'hotel_haussonville_logo.png', '', '169.00', 'false', '0.00', NULL, NULL, 'false'),
(25, 1, 'Boite de nuit', 'Discothèque, Boîte tendance avec des soirées à thème & DJ invités', 'boitedenuit.jpg', '', '32.00', 'false', '0.00', NULL, NULL, 'false'),
(26, 1, 'Planètes Laser', 'Laser game : Gilet électronique et pistolet laser comme matériel, vous voilà équipé.', 'laser.jpg', '', '15.00', 'false', '0.00', NULL, NULL, 'Arthur'),
(27, 1, 'Fort Aventure', 'Découvrez Fort Aventure à Bainville-sur-Madon, un site Accropierre unique en Lorraine ! Des Parcours Acrobatiques pour petits et grands, Jeu Mission Aventure, Crypte de Crapahute, Tyrolienne, Saut à l\'élastique inversé, Toboggan géant... et bien plus encore.', 'fort.jpg', '', '25.00', 'false', '0.00', NULL, NULL, 'false'),
(28, 4, 'Champagne', 'Bouteille de champagne + flutes + jeux à gratter', 'champagne.jpg', '', '20.00', 'false', '0.00', NULL, NULL, 'false'),
(29, 4, 'Fort Aventure', 'Découvrez Fort Aventure à Bainville-sur-Madon, un site Accropierre unique en Lorraine ! Des Parcours Acrobatiques pour petits et grands, Jeu Mission Aventure, Crypte de Crapahute, Tyrolienne, Saut à l\'élastique inversé, Toboggan géant... et bien plus encore.', 'fort.jpg', '', '25.00', 'false', '0.00', NULL, NULL, 'false');

-- --------------------------------------------------------

--
-- Structure de la table `liste`
--
DROP TABLE IF EXISTS `liste`;
CREATE TABLE `liste` (
  `no` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `titre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `expiration` date DEFAULT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `public` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `liste`
--

INSERT INTO `liste` (`no`, `user_id`, `titre`, `description`, `expiration`, `token`, `public`) VALUES
(1, 1, 'Pour fêter le bac !', 'Pour un week-end à Nancy qui nous fera oublier les épreuves. ', '2021-06-27', 'nosecure1', 'true'),
(2, 2, 'Liste de mariage d\'Alice et Bob', 'Nous souhaitons passer un week-end royal à Nancy pour notre lune de miel :)', '2021-06-30', 'nosecure2', 'true'),
(3, 3, 'C\'est l\'anniversaire de Charlie', 'Pour lui préparer une fête dont il se souviendra :)', '2021-12-12', 'nosecure3', 'true'),
(4, 4, 'Pour le déconfinement', 'Afin de s\'aérer l\'esprit !', '2021-05-05', 'nosecure4', 'true'),
(5, 1, 'Ma liste pour plus tard...', 'Cette liste...', '2021-12-25', '00b1cf4bc5867426b39e', 'false'),
(6, 2, 'Noël 2017', 'Super noël', '2017-01-20', '7d132965578185dd2f63', 'false');

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

DROP TABLE IF EXISTS message;
CREATE TABLE `message` (
  `id_message` int(4) NOT NULL AUTO_INCREMENT,
  `id_parent` int(4) NOT NULL,
  `type_parent` varchar(50) NOT NULL,
  `message` varchar(500) NOT NULL,
  `auteur` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_message`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`id_message`, `id_parent`, `type_parent`, `message`, `auteur`) VALUES
(1, 23, 'item', 'J&#39;adore ça ', 'Hubert'),
(2, 26, 'item', 'Bravo pour les projets', 'Arthur'),
(3, 2, 'item', 'J&#39;adore la musique', 'Alessi'),
(4, 9, 'item', 'Super les livre !', 'Natasha '),
(5, 1, 'liste', 'Super ta liste !', 'Jean');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `nom` varchar (60) NOT NULL,
  `prenom` varchar(60) NOT NULL,
  `login` varchar(60) NOT NULL,
  `pass` varchar(60) NOT NULL,
  `email` varchar(60)DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `nom` ,`prenom` ,`login`, `pass`, `email`) VALUES
(1, 'Demange','Alessi','alessi','$2y$10$LvtaU0UQTKxC49/0Ter99efEboYQVoM5S/oOol1Jt91MJOaipXuZi', null),
(2, 'Slimani','Theo','theo','$2y$10$YkfC182KMsMGndDJOKbobeoHUOyschln8302plXqgYfmDQFjrTiHi', null),
(3, 'Carrier','Lisa','lisa','$2y$10$Jo/zMM/TB6CwjyVnRjZoWOdj9p1fTK.NKGFJSbEf.9na.sMqa6pAO', null),
(4, 'Rzepka','Thomas','thomas','$2y$10$BvAhFsJxdFWa7yFR2VP7W.6AU35c3/nHCjMP4XHoXB/PdupJrpJsO', 'thomas@rzepka.php');

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `liste`
--
ALTER TABLE `liste`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
