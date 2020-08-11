-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 09, 2019 at 11:57 AM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 5.6.39

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `total_forecast`
--

-- --------------------------------------------------------

--
-- Table structure for table `client_catagory`
--

CREATE TABLE `client_catagory` (
  `id` int(16) NOT NULL,
  `client_type` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `client_catagory`
--

INSERT INTO `client_catagory` (`id`, `client_type`) VALUES
(1, 'Distributor'),
(2, 'Dealer'),
(3, 'Wholesaler'),
(4, 'Retailer');

-- --------------------------------------------------------

--
-- Table structure for table `client_info`
--

CREATE TABLE `client_info` (
  `id` int(32) NOT NULL,
  `catagory_id` int(32) NOT NULL,
  `client_code` varchar(32) DEFAULT NULL,
  `name` varchar(16) NOT NULL,
  `contact_number` varchar(16) DEFAULT NULL,
  `email` varchar(16) DEFAULT NULL,
  `address` varchar(32) DEFAULT NULL,
  `office_id` int(16) DEFAULT NULL,
  `handler_id` int(16) NOT NULL,
  `client_parent_id` int(16) DEFAULT NULL,
  `client_pairID` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `client_info`
--

INSERT INTO `client_info` (`id`, `catagory_id`, `client_code`, `name`, `contact_number`, `email`, `address`, `office_id`, `handler_id`, `client_parent_id`, `client_pairID`) VALUES
(1, 1, '104763', 'Alhaj Ano Meah S', '', '', '', 0, 0, 10, '16.1.7.10'),
(2, 1, '104782', 'B. M. Trading', '', '', '', 0, 0, 11, '16.1.7.11'),
(3, 1, '104803', 'Hoque & Brothers', '', '', '', 0, 0, 12, '16.1.7.12'),
(4, 1, '104817', 'Isahaq Traders', '', '', '', 0, 0, 10, '16.1.7.10'),
(5, 1, '104823', 'Jananye Enterpri', '', '', '', 0, 0, 11, '16.1.7.11'),
(6, 1, '104825', 'Jamuna Enterpris', '', '', '', 0, 0, 13, '16.1.7.13'),
(7, 1, '104845', 'Motaleb Store', '', '', '', 0, 0, 14, '16.1.7.14'),
(8, 1, '104862', 'Arabian Enterpri', '', '', '', 0, 0, 14, '16.1.7.14'),
(9, 1, '104872', 'N F Enterprise', '', '', '', 0, 0, 10, '16.1.7.10'),
(10, 1, '104877', 'N. N. Enterprise', '', '', '', 0, 0, 15, '16.1.7.15'),
(11, 1, '104901', 'Sara Enterprise', '', '', '', 0, 0, 14, '16.1.7.14'),
(12, 1, '104903', 'Solar Associates', '', '', '', 0, 0, 15, '16.1.7.15'),
(13, 1, '104906', 'Sunrise Gas Trad', '', '', '', 0, 0, 15, '16.1.7.15'),
(14, 1, '104910', 'Sunmoon Gas Trad', '', '', '', 0, 0, 13, '16.1.7.13'),
(15, 1, '104919', 'S Alam Enterpris', '', '', '', 0, 0, 12, '16.1.7.12'),
(16, 1, '105030', 'Tareq Enterprise', '', '', '', 0, 0, 10, '16.1.7.10'),
(17, 1, '105320', 'Jahangir Store', '', '', '', 0, 0, 13, '16.1.7.13'),
(18, 1, '106183', 'Prayas Trading', '', '', '', 0, 0, 13, '16.1.7.13'),
(19, 1, '106461', 'Monzur Alam Trad', '', '', '', 0, 0, 14, '16.1.7.14'),
(20, 1, '106511', 'Mahadi Traders', '', '', '', 0, 0, 15, '16.1.7.15'),
(21, 1, '106512', 'Saiful Traders', '', '', '', 0, 0, 11, '16.1.7.11'),
(22, 1, '106513', 'Ali & Sons', '', '', '', 0, 0, 12, '16.1.7.12'),
(23, 1, '106531', 'JS Trading/New', '', '', '', 0, 0, 15, '16.1.7.15'),
(24, 1, '106532', 'T.S. Enterprise', '', '', '', 0, 0, 15, '16.1.7.15'),
(25, 1, '108321', 'HK Enterprise', '', '', '', 0, 0, 10, '16.1.7.10'),
(26, 1, '108406', 'Nurjahan Traders', '', '', '', 0, 0, 11, '16.1.7.11'),
(27, 1, '108664', 'NK Corporation', '', '', '', 0, 0, 11, '16.1.7.11'),
(28, 1, '108693', 'Aneek Enterprise', '', '', '', 0, 0, 15, '16.1.7.15'),
(29, 1, '109885', 'Raj Enterprise', '', '', '', 0, 0, 11, '16.1.7.11'),
(30, 1, '110291', 'Bhai Bhai Gas Ce', '', '', '', 0, 0, 10, '16.1.7.10'),
(31, 1, '110306', 'K.H Enterprise', '', '', '', 0, 0, 14, '16.1.7.14'),
(32, 1, '110458', 'Momin Corporatio', '', '', '', 0, 0, 15, '16.1.7.15'),
(33, 1, '110475', 'Abdul Nabi & Tra', '', '', '', 0, 0, 15, '16.1.7.15'),
(34, 1, '110480', 'Mir Mojaffar Aha', '', '', '', 0, 0, 15, '16.1.7.15'),
(35, 1, '110616', 'Apu Enterprise', '', '', '', 0, 0, 10, '16.1.7.10'),
(36, 1, '110812', 'Maina Enterprise', '', '', '', 0, 0, 14, '16.1.7.14'),
(37, 1, '111451', 'Farjana Enterpri', '', '', '', 0, 0, 11, '16.1.7.11'),
(38, 1, '111515', 'Protiva Library', '', '', '', 0, 0, 13, '16.1.7.13'),
(39, 1, '111571', 'Asia Link Gas Su', '', '', '', 0, 0, 10, '16.1.7.10'),
(40, 1, '111906', 'Abdur Rahman Sha', '', '', '', 0, 0, 12, '16.1.7.12'),
(41, 1, '112360', 'M/S Jashim Enter', '', '', '', 0, 0, 13, '16.1.7.13'),
(42, 1, '112560', 'M Y Traders', '', '', '', 0, 0, 14, '16.1.7.14'),
(43, 1, '113382', 'Jashim & Sons', '', '', '', 0, 0, 13, '16.1.7.13'),
(44, 1, '113383', 'A.R Trading', '', '', '', 0, 0, 12, '16.1.7.12'),
(45, 1, '113465', 'NA Gas Fair', '', '', '', 0, 0, 10, '16.1.7.10'),
(46, 1, '113713', 'Makka Traders', '', '', '', 0, 0, 11, '16.1.7.11'),
(47, 1, '113716', 'Bismillah Enterp', '', '', '', 0, 0, 11, '16.1.7.11'),
(48, 1, '114656', 'Rashid & Sons', '', '', '', 0, 0, 10, '16.1.7.10'),
(49, 1, '116141', 'Mobinul Haque', '', '', '', 0, 0, 10, '16.1.7.10'),
(50, 1, '117489', 'Haruna Enterpris', '', '', '', 0, 0, 11, '16.1.7.11'),
(51, 1, '117611', 'Ibra foods & bre', '', '', '', 0, 0, 14, '16.1.7.14'),
(52, 1, '118072', 'AL NOOR ENTERPRI', '', '', '', 0, 0, 13, '16.1.7.13'),
(53, 1, '118073', 'MOSTAFA MOTORS', '', '', '', 0, 0, 13, '16.1.7.13'),
(54, 1, '118370', 'MADINA TRADERS, ', '', '', '', 0, 0, 13, '16.1.7.13'),
(55, 1, '118610', 'F.K. Enterprise', '', '', '', 0, 0, 11, '16.1.7.11'),
(56, 1, '500052', 'HASINA TRADING C', '', '', '', 0, 0, 13, '16.1.7.13'),
(57, 1, '500072', 'JAHANGIR & BROTH', '', '', '', 0, 0, 11, '16.1.7.11'),
(58, 1, '500366', 'Rokeya Enterpris', '', '', '', 0, 0, 15, '16.1.7.15'),
(59, 1, '500650', 'Mohshen Awlia', '', '', '', 0, 0, 11, '16.1.7.11'),
(60, 1, '500772', 'Shah Mohshen Awl', '', '', '', 0, 0, 15, '16.1.7.15'),
(61, 1, '500843', 'N H Enterprise', '', '', '', 0, 0, 12, '16.1.7.12'),
(62, 1, '500894', 'Bijoy Enterprise', '', '', '', 0, 0, 12, '16.1.7.12'),
(63, 1, '500913', 'S M Gas Trading,', '', '', '', 0, 0, 10, '16.1.7.10'),
(64, 1, '500974', 'MAA ENTERPRISE, ', '', '', '', 0, 0, 10, '16.1.7.10'),
(65, 1, '501048', 'SHITAKUNDO LPG T', '', '', '', 0, 0, 14, '16.1.7.14'),
(66, 1, '501167', 'Mirsarai Enterpr', '', '', '', 0, 0, 14, '16.1.7.14'),
(67, 1, '501200', 'Mawa Enterprise', '', '', '', 0, 0, 13, '16.1.7.13'),
(68, 1, '501423', 'Bhai Bhai Trader', '', '', '', 0, 0, 10, '16.1.7.10'),
(69, 1, '501745', 'Ria Enterprise', '', '', '', 0, 0, 14, '16.1.7.14'),
(70, 1, '501858', 'Hossen Traders', '', '', '', 0, 0, 11, '16.1.7.11'),
(71, 1, '501929', 'Lutfor gas house', '', '', '', 0, 0, 10, '16.1.7.10'),
(72, 1, '502160', 'Alim & Sons', '', '', '', 0, 0, 11, '16.1.7.11'),
(73, 1, '502235', 'Padma Trading,Ct', '', '', '', 0, 0, 12, '16.1.7.12');

-- --------------------------------------------------------

--
-- Table structure for table `company_user`
--

CREATE TABLE `company_user` (
  `id` int(64) NOT NULL,
  `username` varchar(16) NOT NULL,
  `password` varchar(32) NOT NULL,
  `created_time` date NOT NULL,
  `employee_id` int(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `company_user`
--

INSERT INTO `company_user` (`id`, `username`, `password`, `created_time`, `employee_id`) VALUES
(1, 'admin@total.com', 'abcdtotal', '2019-07-02', 16);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(64) NOT NULL,
  `designation` int(16) NOT NULL,
  `team_id` int(16) DEFAULT NULL,
  `transfer_team_id` int(16) DEFAULT NULL,
  `info_id` int(16) NOT NULL,
  `created_date` date DEFAULT NULL,
  `parent_id` int(16) DEFAULT NULL,
  `employeeId` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `designation`, `team_id`, `transfer_team_id`, `info_id`, `created_date`, `parent_id`, `employeeId`) VALUES
(1, 1, 0, 0, 1, '2019-06-30', 16, '16.1'),
(2, 1, 0, 0, 2, '2019-07-01', 16, '16.2'),
(3, 1, 0, 0, 3, '2019-07-02', 16, '16.3'),
(4, 1, 0, 0, 4, '2019-07-03', 16, '16.4'),
(5, 1, 0, 0, 5, '2019-07-04', 16, '16.5'),
(6, 1, 0, 0, 6, '2019-07-05', 16, '16.6'),
(7, 4, 0, 0, 7, '2019-07-06', 1, '16.1.7'),
(8, 4, 0, 0, 8, '2019-07-07', 1, '16.1.8'),
(9, 4, 0, 0, 9, '2019-07-08', 1, '16.1.9'),
(10, 5, 0, 0, 10, '2019-07-09', 7, '16.1.7.10'),
(11, 5, 0, 0, 11, '2019-07-10', 7, '16.1.7.11'),
(12, 5, 0, 0, 12, '2019-07-11', 8, '16.1.7.12'),
(13, 5, 0, 0, 13, '2019-07-12', 8, '16.1.7.13'),
(14, 5, 0, 0, 14, '2019-07-13', 9, '16.1.7.14'),
(15, 5, 0, 0, 15, '2019-07-14', 9, '16.1.7.15'),
(16, 6, 0, 0, 16, '2019-07-15', 0, '16');

-- --------------------------------------------------------

--
-- Table structure for table `employee_designation`
--

CREATE TABLE `employee_designation` (
  `id` int(64) NOT NULL,
  `designation` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee_designation`
--

INSERT INTO `employee_designation` (`id`, `designation`) VALUES
(1, 'Manager'),
(2, 'DSM'),
(3, 'ASM'),
(4, 'Officer'),
(5, 'DSR'),
(6, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `employee_info`
--

CREATE TABLE `employee_info` (
  `id` int(64) NOT NULL,
  `name` varchar(32) DEFAULT NULL,
  `contact_number` varchar(16) DEFAULT NULL,
  `email` varchar(32) DEFAULT NULL,
  `office_email` varchar(32) DEFAULT NULL,
  `home_address` varchar(32) DEFAULT NULL,
  `emergency_contact` varchar(32) DEFAULT NULL,
  `is_client_handler` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee_info`
--

INSERT INTO `employee_info` (`id`, `name`, `contact_number`, `email`, `office_email`, `home_address`, `emergency_contact`, `is_client_handler`) VALUES
(1, 'Saiful', '', '', '', '', '', 0),
(2, 'Jashim', '', '', '', '', '', 0),
(3, 'Osman', '', '', '', '', '', 0),
(4, 'Rashid', '', '', '', '', '', 0),
(5, 'South', '', '', '', '', '', 0),
(6, 'Ruhul', '', '', '', '', '', 0),
(7, 'Arman', '', '', '', '', '', 0),
(8, 'Murad', '', '', '', '', '', 0),
(9, 'Jim', '', '', '', '', '', 0),
(10, 'Arifur', '', '', '', '', '', 1),
(11, 'Minhaj', '', '', '', '', '', 1),
(12, 'Sohel', '', '', '', '', '', 1),
(13, 'Fsruk', '', '', '', '', '', 1),
(14, 'Arman-2', '', '', '', '', '', 1),
(15, 'Hasan-2', '', '', '', '', '', 1),
(16, 'Admin', '', '', '', '', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(64) NOT NULL,
  `txid` varchar(64) NOT NULL,
  `client_id` int(64) NOT NULL,
  `taker_id` int(16) NOT NULL,
  `product_id` int(16) NOT NULL,
  `quantityes` varchar(16) DEFAULT NULL,
  `delevary_date` date NOT NULL,
  `plant` int(16) NOT NULL,
  `taking_date` date NOT NULL,
  `order_type` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `txid`, `client_id`, `taker_id`, `product_id`, `quantityes`, `delevary_date`, `plant`, `taking_date`, `order_type`) VALUES
(1, '20190702', 73, 12, 1, '10', '2019-07-04', 1, '2019-07-02', 1),
(2, '20190703', 65, 14, 2, '15', '2019-07-05', 1, '2019-07-02', 1),
(3, '20190704', 1, 10, 3, '18', '2019-07-06', 1, '2019-07-04', 2),
(4, '20190705', 5, 11, 4, '6', '2019-07-07', 1, '2019-07-04', 2),
(5, '20190706', 50, 11, 9, '2', '2019-07-08', 1, '2019-07-06', 1),
(6, '20190707', 33, 15, 12, '9', '2019-07-09', 1, '2019-07-06', 2),
(7, '1354682', 63, 10, 1, '10', '2019-07-06', 3, '2019-07-04', 2);

-- --------------------------------------------------------

--
-- Table structure for table `plant_detail`
--

CREATE TABLE `plant_detail` (
  `id` int(16) NOT NULL,
  `plant` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `plant_detail`
--

INSERT INTO `plant_detail` (`id`, `plant`) VALUES
(1, 'CTG'),
(2, 'TALTOLA'),
(3, 'BOGRA'),
(4, 'MONGLA');

-- --------------------------------------------------------

--
-- Table structure for table `product_details`
--

CREATE TABLE `product_details` (
  `id` int(128) NOT NULL,
  `p_name` varchar(16) NOT NULL,
  `p_type` int(16) NOT NULL,
  `p_retailPrice` varchar(16) DEFAULT NULL,
  `p_wholesalePrice` varchar(16) DEFAULT NULL,
  `p_specialPrice` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_details`
--

INSERT INTO `product_details` (`id`, `p_name`, `p_type`, `p_retailPrice`, `p_wholesalePrice`, `p_specialPrice`) VALUES
(1, '12Kg', 1, '', '', '1500'),
(2, '12Kg', 2, '', '', '1500'),
(3, '15Kg', 1, '', '', '1500'),
(4, '15Kg', 2, '', '', '1500'),
(5, '33Kg', 1, '', '', '1500'),
(6, '33Kg', 2, '', '', '1500'),
(7, '45Kg', 1, '', '', '1500'),
(8, '45Kg', 2, '', '', '1500'),
(9, '12Kg Boc', 1, '', '', '1500'),
(10, '12Kg Boc', 2, '', '', '1500'),
(11, 'Quantas', 1, '', '', '1500'),
(12, 'Quantas', 2, '', '', '1500');

-- --------------------------------------------------------

--
-- Table structure for table `product_type`
--

CREATE TABLE `product_type` (
  `id` int(16) NOT NULL,
  `type` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_type`
--

INSERT INTO `product_type` (`id`, `type`) VALUES
(1, 'New'),
(2, 'Refill');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client_catagory`
--
ALTER TABLE `client_catagory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_info`
--
ALTER TABLE `client_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_user`
--
ALTER TABLE `company_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_designation`
--
ALTER TABLE `employee_designation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_info`
--
ALTER TABLE `employee_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plant_detail`
--
ALTER TABLE `plant_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_details`
--
ALTER TABLE `product_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_type`
--
ALTER TABLE `product_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client_catagory`
--
ALTER TABLE `client_catagory`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `client_info`
--
ALTER TABLE `client_info`
  MODIFY `id` int(32) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `company_user`
--
ALTER TABLE `company_user`
  MODIFY `id` int(64) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(64) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `employee_designation`
--
ALTER TABLE `employee_designation`
  MODIFY `id` int(64) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employee_info`
--
ALTER TABLE `employee_info`
  MODIFY `id` int(64) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(64) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `plant_detail`
--
ALTER TABLE `plant_detail`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_details`
--
ALTER TABLE `product_details`
  MODIFY `id` int(128) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_type`
--
ALTER TABLE `product_type`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
