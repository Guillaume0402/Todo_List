-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 16 oct. 2025 à 05:55
-- Version du serveur : 8.0.43-0ubuntu0.24.04.2
-- Version de PHP : 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `tickylist`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `icon`) VALUES
(1, 'Courses', 'bi-basket'),
(2, 'Travail', 'bi-briefcase'),
(3, 'Maison', 'bi-house'),
(4, 'Divers', 'bi-card-checklist');

-- --------------------------------------------------------

--
-- Structure de la table `items`
--

CREATE TABLE `items` (
  `id` int NOT NULL,
  `list_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_done` tinyint(1) NOT NULL DEFAULT '0',
  `position` int DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `items`
--

INSERT INTO `items` (`id`, `list_id`, `name`, `is_done`, `position`, `due_date`, `created_at`) VALUES
(1, 1, 'Acheter du lait', 0, 1, NULL, '2025-10-11 07:21:50'),
(2, 1, 'Payer la facture', 0, 2, NULL, '2025-10-11 07:21:50'),
(3, 2, 'pain', 0, NULL, NULL, '2025-10-13 07:01:34'),
(4, 2, 'gruyère', 1, NULL, NULL, '2025-10-13 07:01:37'),
(5, 3, 'CNI', 1, NULL, NULL, '2025-10-14 05:57:36'),
(6, 3, 'Passeport', 1, NULL, NULL, '2025-10-14 05:57:43'),
(7, 3, 'E-sim', 1, NULL, NULL, '2025-10-14 05:57:49'),
(8, 4, 'crampons', 0, NULL, NULL, '2025-10-14 07:04:11'),
(9, 4, 'Chaussettes', 0, NULL, NULL, '2025-10-14 07:04:23'),
(10, 4, 'Gourde', 0, NULL, NULL, '2025-10-14 07:04:28'),
(11, 4, 'Protège-tibias', 0, NULL, NULL, '2025-10-14 07:04:39'),
(12, 5, 'npm install', 0, NULL, NULL, '2025-10-15 07:45:09'),
(13, 5, 'Docker', 0, NULL, NULL, '2025-10-15 07:45:12'),
(14, 5, 'node', 0, NULL, NULL, '2025-10-15 07:45:16');

-- --------------------------------------------------------

--
-- Structure de la table `lists`
--

CREATE TABLE `lists` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int NOT NULL,
  `category_id` int DEFAULT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `lists`
--

INSERT INTO `lists` (`id`, `title`, `user_id`, `category_id`, `is_archived`, `created_at`) VALUES
(1, 'Ma première liste', 1, 4, 0, '2025-10-11 07:21:50'),
(2, 'Repas anniversaire', 3, 1, 0, '2025-10-13 07:01:28'),
(3, 'Voyage Japon', 3, 4, 0, '2025-10-14 05:57:31'),
(4, 'Foot', 3, 3, 0, '2025-10-14 07:04:02'),
(5, 'Todo coding', 3, 2, 0, '2025-10-15 07:45:02');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=user,1=admin',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `chk_users_is_admin_bool` CHECK (`is_admin` IN (0,1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `display_name`, `is_admin`, `created_at`) VALUES
(1, 'test@example.com', '$2y$10$Do/SIenKrsFBNKQAJB6FIuO1TEYrWJybP3rLBSxyP3xJH1RQYCRvq', 'Demo User', 0, '2025-10-11 07:21:46'),
(3, 'g.maignaut@gmail.com', '$2y$10$AnYMoNoqIykc1rkLNNoZjugklZD9eO.LbXkWqfIiJCdoVDsUfW/8G', 'Yom', 1, '2025-10-13 06:56:15'),
(4, 'yanismaignaut@gmail.com', '$2y$10$8lyIGidWuDGgWqiAp8K.HucGLZfkpK7Vv9xKh1oq/pmG6QBVUfY8.', 'yanis', 0, '2025-10-13 08:16:49'),
(5, 'g.maignautt@gmail.com', '$2y$10$E67U5BHU4K2qv4/dW5tnDepwIkLVMIS4zTNSIikqJEK7OWsmmO9sO', 'Maignaut', 0, '2025-10-15 07:21:59');


--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_categories_name` (`name`);

--
-- Index pour la table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_items_list` (`list_id`),
  ADD KEY `idx_items_done` (`is_done`);

--
-- Index pour la table `lists`
--
ALTER TABLE `lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lists_user` (`user_id`),
  ADD KEY `idx_lists_category` (`category_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `items`
--
ALTER TABLE `items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `lists`
--
ALTER TABLE `lists`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `fk_items_list` FOREIGN KEY (`list_id`) REFERENCES `lists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `lists`
--
ALTER TABLE `lists`
  ADD CONSTRAINT `fk_lists_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_lists_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
