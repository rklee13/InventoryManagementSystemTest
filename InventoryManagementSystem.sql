-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 26, 2026 at 06:37 PM
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
-- Database: `InventoryManagementSystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `image` varchar(150) DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `description`, `image`, `stock`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'TestProduct1', 'Test adding the first product', 'product-1745710435-product-drop.jpg', 59, 1, '2025-04-06 01:32:55', '2025-04-27 01:33:55'),
(11, 'Test product Update', 'TESTING ADDING PRODUCT TESTING ADDING PRODUCT', NULL, 0, 1, '2025-04-14 01:15:13', '2025-05-05 01:30:27'),
(12, 'TestProductMeh', 'Test Supplier', 'product-1745192451-product-cartoon.jpeg', 102, 3, '2025-04-20 23:51:03', '2025-04-21 01:40:51'),
(13, 'New test product', 'New testing of showing supplier list', 'product-1745187355-product-cartoon.jpeg', 141, 1, '2025-04-21 00:15:55', '2025-04-21 00:15:55');

-- --------------------------------------------------------

--
-- Table structure for table `productSupplier`
--

CREATE TABLE `productSupplier` (
  `id` int(11) NOT NULL,
  `supplier` int(11) NOT NULL,
  `product` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Links between product and supplier table';

--
-- Dumping data for table `productSupplier`
--

INSERT INTO `productSupplier` (`id`, `supplier`, `product`, `created_at`, `updated_at`) VALUES
(3, 3, 13, '2025-04-21 00:15:55', '2025-04-21 00:15:55'),
(4, 4, 13, '2025-04-21 00:15:55', '2025-04-21 00:15:55'),
(6, 2, 13, '2025-04-21 00:15:55', '2025-04-21 00:15:55'),
(9, 2, 12, '2025-04-21 01:40:51', '2025-04-21 01:40:51'),
(10, 3, 12, '2025-04-21 01:40:51', '2025-04-21 01:40:51'),
(24, 2, 1, '2025-04-27 01:33:55', '2025-04-27 01:33:55'),
(25, 3, 1, '2025-04-27 01:33:55', '2025-04-27 01:33:55'),
(26, 4, 1, '2025-04-27 01:33:55', '2025-04-27 01:33:55'),
(28, 1, 12, '2025-04-27 22:04:54', '2025-04-27 22:04:54'),
(29, 3, 11, '2025-05-05 01:30:27', '2025-05-05 01:30:27');

-- --------------------------------------------------------

--
-- Table structure for table `product_order`
--

CREATE TABLE `product_order` (
  `id` int(11) NOT NULL,
  `supplier` int(11) DEFAULT NULL,
  `product` int(11) DEFAULT NULL,
  `quantity_ordered` int(11) DEFAULT NULL,
  `quantity_received` int(11) DEFAULT NULL,
  `quantity_remaining` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `batch` int(20) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_order`
--

INSERT INTO `product_order` (`id`, `supplier`, `product`, `quantity_ordered`, `quantity_received`, `quantity_remaining`, `status`, `batch`, `created_by`, `created_at`, `updated_at`) VALUES
(7, 3, 13, 434, NULL, NULL, 'PENDING', 1746997728, 1, '2025-05-11 23:08:48', '2025-05-11 23:08:48'),
(8, 4, 13, 78, NULL, NULL, 'PENDING', 1746997728, 1, '2025-05-11 23:08:48', '2025-05-11 23:08:48'),
(9, 2, 13, 35, NULL, NULL, 'PENDING', 1746997728, 1, '2025-05-11 23:08:48', '2025-05-11 23:08:48'),
(10, 2, 12, 487, NULL, NULL, 'PENDING', 1746997728, 1, '2025-05-11 23:08:48', '2025-05-11 23:08:48'),
(11, 3, 12, 112, NULL, NULL, 'PENDING', 1746997728, 1, '2025-05-11 23:08:48', '2025-05-11 23:08:48'),
(12, 1, 12, 547, NULL, NULL, 'PENDING', 1746997728, 1, '2025-05-11 23:08:48', '2025-05-11 23:08:48'),
(13, 3, 11, 68, 120, 0, 'INCOMPLETE', 1746998383, 1, '2025-05-11 23:19:43', '2025-05-19 01:04:07'),
(14, 2, 1, 78, 0, 0, 'PENDING', 1746998383, 1, '2025-05-11 23:19:43', '2025-05-19 01:04:07'),
(15, 3, 1, 102, 4, 98, 'INCOMPLETE', 1746998383, 1, '2025-05-11 23:19:43', '2025-05-25 23:29:09'),
(16, 4, 1, 20, 0, 0, 'COMPLETE', 1746998383, 1, '2025-05-11 23:19:43', '2025-05-19 01:04:07'),
(17, 3, 13, 30, NULL, NULL, 'PENDING', 1747596838, 1, '2025-05-18 21:33:58', '2025-05-18 21:33:58'),
(18, 4, 13, 54, NULL, NULL, 'PENDING', 1747596838, 1, '2025-05-18 21:33:58', '2025-05-18 21:33:58'),
(19, 2, 13, 77, 71, 6, 'PENDING', 1747596838, 1, '2025-05-18 21:33:58', '2025-05-26 01:54:31'),
(20, 2, 12, 12, 2, 10, 'PENDING', 1747596838, 1, '2025-05-18 21:33:58', '2025-05-26 01:54:31'),
(21, 3, 12, 45, NULL, NULL, 'PENDING', 1747596838, 1, '2025-05-18 21:33:58', '2025-05-18 21:33:58'),
(22, 1, 12, 788, 107, 688, 'PENDING', 1747596838, 1, '2025-05-18 21:33:58', '2025-05-26 01:54:31'),
(23, 3, 13, 30, 30, 5, 'COMPLETE', 1748204088, 1, '2025-05-25 22:14:48', '2025-05-25 23:02:47'),
(24, 4, 13, 40, 40, 10, 'COMPLETE', 1748204088, 1, '2025-05-25 22:14:48', '2025-05-26 01:54:14'),
(25, 2, 13, 50, 50, 10, 'COMPLETE', 1748204088, 1, '2025-05-25 22:14:48', '2025-05-26 02:00:43'),
(26, 2, 1, 7, 8, 2, 'COMPLETE', 1748210122, 1, '2025-05-25 23:55:22', '2025-05-26 00:15:31'),
(27, 3, 1, 30, 31, 0, 'COMPLETE', 1748210122, 1, '2025-05-25 23:55:22', '2025-05-26 00:17:01'),
(28, 4, 1, 20, 20, 1, 'COMPLETE', 1748210122, 1, '2025-05-25 23:55:22', '2025-05-26 00:19:33');

-- --------------------------------------------------------

--
-- Table structure for table `product_order_history`
--

CREATE TABLE `product_order_history` (
  `id` int(11) NOT NULL,
  `product_order_id` int(11) NOT NULL,
  `quatity_received` int(11) NOT NULL,
  `date_received` datetime NOT NULL,
  `date_updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_order_history`
--

INSERT INTO `product_order_history` (`id`, `product_order_id`, `quatity_received`, `date_received`, `date_updated`) VALUES
(7, 26, 3, '2025-05-26 00:14:28', '2025-05-26 00:14:28'),
(8, 27, 5, '2025-05-26 00:14:59', '2025-05-26 00:14:59'),
(9, 26, 5, '2025-05-26 00:15:31', '2025-05-26 00:15:31'),
(10, 27, 25, '2025-05-26 00:16:35', '2025-05-26 00:16:35'),
(11, 27, 1, '2025-05-02 00:17:01', '2025-05-26 00:17:01'),
(12, 28, 1, '2025-05-26 00:17:01', '2025-05-26 00:17:01'),
(13, 28, 19, '2025-05-14 00:19:33', '2025-05-26 00:19:33'),
(14, 24, 30, '2025-05-26 01:54:14', '2025-05-26 01:54:14'),
(15, 19, 71, '2025-05-25 01:54:31', '2025-05-26 01:54:31'),
(16, 22, 100, '2025-05-25 01:54:31', '2025-05-26 01:54:31'),
(17, 20, 2, '2025-05-20 01:54:31', '2025-05-26 01:54:31'),
(18, 25, 40, '2025-05-26 02:00:43', '2025-05-26 02:00:43');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `supplier_name` varchar(100) NOT NULL,
  `supplier_location` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `supplier_name`, `supplier_location`, `email`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'NestleUpdate', 'NigeriaUpdate', 'nestleUpdate@test.com', 1, '2025-04-16 14:03:16', '2025-04-16 14:03:16'),
(2, 'Apple', 'California', 'apple@macTest.com', 1, '2025-04-16 14:03:16', '2025-04-16 14:03:16'),
(3, 'Microsoft', 'Sri Lanka', 'microsoft@test.com', 2, '2025-04-17 14:05:18', '2025-04-17 14:05:18'),
(4, 'Facebook', 'Mexico', 'facebook@test.com', 1, '2025-04-17 14:05:18', '2025-04-17 14:05:18');

-- --------------------------------------------------------

--
-- Table structure for table `UserLoginInformation`
--

CREATE TABLE `UserLoginInformation` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `password` varchar(500) NOT NULL,
  `email` varchar(50) NOT NULL,
  `permissions` varchar(5000) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `UserLoginInformation`
--

INSERT INTO `UserLoginInformation` (`id`, `first_name`, `last_name`, `password`, `email`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 'John', 'Doe', 'password', 'jdoe@ims.com', 'dashboard_view,report_view,purchaseOrder_view,purchaseOrder_create,purchaseOrder_edit,product_view,product_create,product_edit,product_delete,supplier_view,supplier_create,supplier_edit,supplier_delete,users_view,users_create,users_edit,users_delete,pointOfSale_access', '2025-03-23 23:20:55', '2025-03-23 23:20:55'),
(2, 'test1', 'test1', '$2y$10$Hq6ijeluDavFGeuU7rcgzOVG3K6kyNt85nE2nBUuYDBiZT/WLrjOe', 'test1@ims.com', 'dashboard_view,report_view,purchaseOrder_view,product_view,supplier_view,pointOfSale_access,purchaseOrder_create,product_create,purchaseOrder_edit,product_edit,product_delete,supplier_delete,supplier_edit,supplier_create,users_view,users_create,users_edit,users_delete', '2025-03-26 12:43:18', '2025-06-02 03:18:59'),
(3, 'test2', 'test2', '$2y$10$2nbPD.7gqBZAh/OZczzSQuELQt8DI1xBXF4HeIaD9UaoqJSxxgU9.', 'test2@ims.com', 'product_view,purchaseOrder_create,purchaseOrder_edit', '2025-03-26 12:57:55', '2025-06-02 01:19:40'),
(4, 'test3', 'test3', '$2y$10$h18q0kwTrp61iCklZibMHeUtnb9ROMMspyc7OAq4WUDBVI.U5OqAa', 'test3@ims.com', 'product_view,dashboard_view,report_view,purchaseOrder_view,supplier_view,product_delete,supplier_delete,users_delete,users_view,supplier_view,product_view,product_view,supplier_view,users_view', '2025-03-26 12:58:54', '2025-06-02 01:23:18'),
(5, 'test4', 'test4', '$2y$10$50vNfu6QmzUpok7Ecmv1TOYtEO6yCB7/Y7q9kykUkpLa6Z3mdaXki', 'test4@ims.com', 'supplier_create', '2025-03-26 13:01:06', '2025-06-02 01:23:23'),
(15, 'test5', 'test5LastNameUpdate', '$2y$10$XJ37PHoTjY2s9XaFlZd5Q.I0UdRft4ZYIPnHqLqBWB7Vhzpmt5mB2', 'test5Update@ims.com', 'purchaseOrder_view,supplier_view,product_view,dashboard_view,report_view,users_view,pointOfSale_access', '2025-03-30 16:19:24', '2025-06-02 01:20:13'),
(21, 'Permission', 'Test', '$2y$10$xjVTAnFlU4JrZ5V80d.BSuIlB3DFxLzRlqDfelvsZQuFVAG2zvTWW', 'pTest@IMS.com', 'pointOfSale_access,users_view,supplier_create,supplier_view,product_create,purchaseOrder_create,purchaseOrder_view,product_view,report_view,dashboard_view,purchaseOrder_edit,product_edit,supplier_edit,product_delete,supplier_delete', '2025-06-01 21:34:56', '2025-06-02 02:50:57'),
(22, 'First', 'Last', '$2y$10$P/3vLuE29RKEc8OGG6qcq.lFzqnLr5dUuKkzXUFj0ld1gsR2XqYK2', '1@ims.com', 'dashboard_view,report_view,purchaseOrder_view,product_view,purchaseOrder_create,product_create,purchaseOrder_edit,product_edit,product_delete,supplier_delete,users_delete,supplier_edit,users_edit,users_create,supplier_create,supplier_view,users_view,pointOfSale_access', '2025-06-02 02:51:35', '2025-06-02 02:51:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_UserLoginInformation_id` (`created_by`);

--
-- Indexes for table `productSupplier`
--
ALTER TABLE `productSupplier`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier` (`supplier`),
  ADD KEY `product` (`product`);

--
-- Indexes for table `product_order`
--
ALTER TABLE `product_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier` (`supplier`),
  ADD KEY `product` (`product`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `product_order_history`
--
ALTER TABLE `product_order_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_order_id` (`product_order_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_created_by` (`created_by`);

--
-- Indexes for table `UserLoginInformation`
--
ALTER TABLE `UserLoginInformation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `productSupplier`
--
ALTER TABLE `productSupplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `product_order`
--
ALTER TABLE `product_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `product_order_history`
--
ALTER TABLE `product_order_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `UserLoginInformation`
--
ALTER TABLE `UserLoginInformation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_UserLoginInformation_id` FOREIGN KEY (`created_by`) REFERENCES `UserLoginInformation` (`id`);

--
-- Constraints for table `productSupplier`
--
ALTER TABLE `productSupplier`
  ADD CONSTRAINT `productSupplier_ibfk_1` FOREIGN KEY (`supplier`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `productSupplier_ibfk_2` FOREIGN KEY (`product`) REFERENCES `products` (`id`);

--
-- Constraints for table `product_order`
--
ALTER TABLE `product_order`
  ADD CONSTRAINT `product_order_ibfk_1` FOREIGN KEY (`supplier`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `product_order_ibfk_2` FOREIGN KEY (`product`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `product_order_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `UserLoginInformation` (`id`);

--
-- Constraints for table `product_order_history`
--
ALTER TABLE `product_order_history`
  ADD CONSTRAINT `product_order_history_ibfk_1` FOREIGN KEY (`product_order_id`) REFERENCES `product_order` (`id`);

--
-- Constraints for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD CONSTRAINT `fk_created_by` FOREIGN KEY (`created_by`) REFERENCES `UserLoginInformation` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
