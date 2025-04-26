-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th4 26, 2025 lúc 07:04 AM
-- Phiên bản máy phục vụ: 8.0.37
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `websitetest`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payment_method`
--

CREATE TABLE `payment_method` (
  `id` int NOT NULL,
  `method_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `payment_method`
--

INSERT INTO `payment_method` (`id`, `method_name`) VALUES
(1, 'Theo tháng'),
(2, 'Theo quý'),
(3, 'Theo năm');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `room`
--

CREATE TABLE `room` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(15) NOT NULL,
  `start_date` date NOT NULL,
  `payment_method_id` int NOT NULL,
  `note` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `room`
--

INSERT INTO `room` (`id`, `name`, `phone_number`, `start_date`, `payment_method_id`, `note`) VALUES
(5, 'Lê Thị Kim Oanh', '0332199694', '2025-04-27', 2, 'Phòng có điều hòa'),
(6, 'Nguyễn Văn A', '0123456789', '2025-04-28', 1, ''),
(7, 'Nguyễn Văn B', '0123456789', '2025-04-27', 3, 'Phòng có máy lạnh');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `payment_method`
--
ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_method_id` (`payment_method_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `payment_method`
--
ALTER TABLE `payment_method`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `room`
--
ALTER TABLE `room`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `room_ibfk_1` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_method` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
