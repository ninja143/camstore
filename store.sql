-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2021 at 09:30 AM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `store`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2021_04_24_183121_create_product_categories_table', 1),
(3, '2021_04_24_184720_create_products_table', 1),
(4, '2021_04_24_185452_create_user_carts_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL COMMENT 'id of the product_categories table',
  `description` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double(8,2) NOT NULL COMMENT 'Price of the Product',
  `make` mediumint(8) UNSIGNED NOT NULL COMMENT 'product built in year',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category_id`, `description`, `price`, `make`, `created_at`, `updated_at`) VALUES
(1, 'FlyCam 500 DC', 5, 'ktDRQUt7scVzWtsHGd8n2Lwa473VBdzw9bzOBdbbmVjqRCUwxEXdKf7hLGQwLLbvD7vzfTqGm9ptwUCPkxDjCIRXjI3h0mmGhzsXQsky0WStdWqr2uNgrm5wsFrfl1h5', 14.00, 2017, '2021-04-24 14:32:33', '2021-04-24 14:32:33'),
(2, 'Blue Star Ronaldo C', 4, 'n9Z9OkcyCBcbO2XVu3wk76GVlNWSGcAVWEgtcrtqYbxI9hyH8lKolKY8aUXqRBABiN31iYErY4DnkNDLWADWV5GAtrudh2i4wJf23PKdNsjx3p1jbKeUSBLl6mKa0ApF', 67.00, 2013, '2021-04-24 14:32:33', '2021-04-24 14:32:33'),
(3, 'Blue Star Jackson', 4, 'uwE9HCkjyHyDEHcDVRk58NzFieWDJuZkJaKmIxb24w4fqu3vYxSLiVTHeqCXODTQFHwCZwKec45w8RqGt1bnm0gKVbiK2LVg78Aw6eE87UqJ4KuUquTNLypZT2SI8bXq', 56.00, 2021, '2021-04-24 14:32:33', '2021-04-24 14:32:33'),
(4, 'Cannon 100 RC', 2, 'euhrUAZEKyieVrMbi5ZIfmLatoYiwIY0yhHhkOWHytMl23q4mF7LSf2TGiNZHmtb7FMO9TgXYeExSETMvEpiLKpR2e6ukRNouSQkqtq36dt2L1EwDqurSi58UQmwfDGT', 36.00, 2017, '2021-04-24 14:32:33', '2021-04-24 14:32:33'),
(5, 'Blue Star 15 Rock', 4, 'WWQbi2HLrWFE0RrHabFNCHv18qv2KWIzwFqGULqohC2HBowrjeOWT9zkKDydQr2YbW1r63GGn9X49rw5SGgpkOniavaGaJL1StNxV3eLqfePehR1INPlzQDMRqpsObPI', 6.00, 2017, '2021-04-24 14:32:33', '2021-04-24 14:32:33'),
(6, 'Cannon Bru C', 2, 'rMCM842GRX6n1lbEuueWfQxaIgv2pCX6jnYyCRPlnrQPsvPTFo2OjA73XfIONcNVLUS5ZcVgU2ugRbYzDVeiV2EUfONcnaT0BS35lJrwilH1iLQh6vWmwMrmJckdeAn2', 35.00, 2014, '2021-04-24 14:32:33', '2021-04-24 14:32:33'),
(7, 'Sony Pro 5800', 3, 'QBivqkKpy5tITljiUASjCXp3XQ37ili4rmYttrOUBnk9IUkUUYDqvZzQ0siyRm6GPoQgY7kbNwF5eCllvjojX72AMPCYdAHD6AGlgKEM3RBYYEO4Q6lj8MgG674RKdQA', 34.00, 2016, '2021-04-24 14:32:33', '2021-04-24 14:32:33'),
(8, 'FlyCam 17C Jeffer', 5, 'DbmaesZMlRISqHsAVnX0IY68fnfgpE94SWrpORUNKaKVo3lfTYrJ1gCFU0hH79u24TbtiyRYD8BSTmUM6AQCF5jBzsMQM4WDZJzh2EIHffeQoENmhrsisUZyQM7OlwwL', 13.00, 2014, '2021-04-24 14:32:33', '2021-04-24 14:32:33'),
(9, 'Nikkon Bees', 1, 'Hdz8SFKHNw5rrNyMEEl8pHQwX1XizOoireN8wy1a6ffgRU0FRpQKLeiAIHlW63pU0XrNxCLIowDQZXEqgBjVfUCOL8m1s1E23UNAzG9EjpGQA6k4EPyVC4rGrHhUAqxY', 80.00, 2017, '2021-04-24 14:32:33', '2021-04-24 14:32:33'),
(10, 'Nikkon 5500', 1, '0ZNUUOeXNdFMD6S4TF2cJUwlXsm7WvBZgcffn141u81EgEt3PKS0nLMVcbv0XenjvqagbALxIWFazr1x7qt7ND4ndfr0ZvGZvrDL8odhwWREdZanI7ZVtlCEI8BKRkg6', 59.00, 2019, '2021-04-24 14:32:34', '2021-04-24 14:32:34');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('Mirrorless','Full frame','Point And Shoot') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Category Type',
  `model` mediumint(8) UNSIGNED NOT NULL COMMENT 'It store data like year 2018',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `name`, `type`, `model`, `created_at`, `updated_at`) VALUES
(1, 'Nikkon', 'Full frame', 2014, '2021-04-24 14:32:32', '2021-04-24 14:32:32'),
(2, 'Cannon', 'Point And Shoot', 2012, '2021-04-24 14:32:33', '2021-04-24 14:32:33'),
(3, 'Sony', 'Full frame', 2013, '2021-04-24 14:32:33', '2021-04-24 14:32:33'),
(4, 'Blue Star', 'Mirrorless', 2018, '2021-04-24 14:32:33', '2021-04-24 14:32:33'),
(5, 'FlyCam', 'Full frame', 2016, '2021-04-24 14:32:33', '2021-04-24 14:32:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Kameron Jaskolski', 'sqs1HxZUyP@yopmail.com', NULL, '$2y$10$rwMV7P7dPBdcOrVphg0ofenkEoTikiLWkMMSRS95zF.fSrL4Bwgoy', NULL, '2021-04-24 14:32:32', '2021-04-24 14:32:32'),
(2, 'Julia Lang', 'ZGeJUV65On@yopmail.com', NULL, '$2y$10$PUErdRkrfx7kIhUGUK/iq.fc.gV7ikF7RLxpD3M3fC.0R.XpS2klW', NULL, '2021-04-24 14:32:32', '2021-04-24 14:32:32'),
(3, 'Prof. Alvah Kerluke', 'qq9eysuLW8@yopmail.com', NULL, '$2y$10$2uLlild4LTOV.wZN2AUXtuGV9g26dCrJSnTtgpeDurFTpsigptvpq', NULL, '2021-04-24 14:32:32', '2021-04-24 14:32:32'),
(4, 'Mr. Layne Parisian', 'DQvMAAW9uy@yopmail.com', NULL, '$2y$10$NuTmdJ6owVqWf75P3G2sguVwCpReNBeghH57nEDVtkjLz19luw352', NULL, '2021-04-24 14:32:32', '2021-04-24 14:32:32'),
(5, 'Ava Hyatt V', '9waFThHGep@yopmail.com', NULL, '$2y$10$XnT547CWviZVs25U0VC4feAvLs1SFHqeVKQlLjMyhNMZ9bYaOne1C', NULL, '2021-04-24 14:32:32', '2021-04-24 14:32:32');

-- --------------------------------------------------------

--
-- Table structure for table `user_carts`
--

CREATE TABLE `user_carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL COMMENT 'id of the products table',
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT 'id of the users table',
  `quantity` mediumint(8) UNSIGNED NOT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Active is understood as Active in Cart And Inactive is as in Wishlist',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_carts`
--

INSERT INTO `user_carts` (`id`, `product_id`, `user_id`, `quantity`, `status`, `created_at`, `updated_at`) VALUES
(3, 2, 5, 100, 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 3, 5, 100, 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_carts`
--
ALTER TABLE `user_carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_carts_product_id_foreign` (`product_id`),
  ADD KEY `user_carts_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_carts`
--
ALTER TABLE `user_carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_carts`
--
ALTER TABLE `user_carts`
  ADD CONSTRAINT `user_carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
