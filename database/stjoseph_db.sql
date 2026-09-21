-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2026 at 03:31 AM
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
-- Database: `stjoseph_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `alumni_details`
--

CREATE TABLE `alumni_details` (
  `AlumniID` int(11) NOT NULL,
  `InstitutionName` varchar(255) NOT NULL,
  `Degree` varchar(100) NOT NULL,
  `FieldOfStudy` varchar(100) DEFAULT NULL,
  `StartDate` date DEFAULT NULL,
  `EndDate` date DEFAULT NULL,
  `logo` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `S_ID` int(11) DEFAULT NULL,
  `AddedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alumni_details`
--

INSERT INTO `alumni_details` (`AlumniID`, `InstitutionName`, `Degree`, `FieldOfStudy`, `StartDate`, `EndDate`, `logo`, `description`, `S_ID`, `AddedBy`) VALUES
(10, 'Tanon State', 'MIT', 'Church System', '2025-01-30', '2025-01-16', 'image/Teacher-male512_44209.png', 'pataka', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbladmission_payments`
--

CREATE TABLE `tbladmission_payments` (
  `PAYMENT_ID` int(11) NOT NULL,
  `APPLICANT_ID` int(11) DEFAULT NULL,
  `S_ID` int(11) DEFAULT NULL,
  `SY_ID` int(11) DEFAULT NULL,
  `AMOUNT` decimal(10,2) NOT NULL DEFAULT 0.00,
  `METHOD` varchar(20) NOT NULL DEFAULT 'Cash' COMMENT 'Cash, GCash, Other',
  `REFERENCE_NO` varchar(100) DEFAULT NULL,
  `STATUS` varchar(20) NOT NULL DEFAULT 'Pending' COMMENT 'Pending, Verified, Rejected, Cancelled, Refunded',
  `DATE_PAID` datetime NOT NULL DEFAULT current_timestamp(),
  `VERIFIED_BY` int(11) DEFAULT NULL,
  `DATE_VERIFIED` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbladmission_payment_items`
--

CREATE TABLE `tbladmission_payment_items` (
  `ITEM_ID` int(11) NOT NULL,
  `PAYMENT_ID` int(11) NOT NULL,
  `FEE_TYPE_ID` int(11) DEFAULT NULL,
  `DESCRIPTION` varchar(150) DEFAULT NULL,
  `UNITS` decimal(5,2) DEFAULT NULL COMMENT 'set only for per-unit tuition items',
  `AMOUNT` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbladmission_receipts`
--

CREATE TABLE `tbladmission_receipts` (
  `RECEIPT_ID` int(11) NOT NULL,
  `PAYMENT_ID` int(11) NOT NULL,
  `RECEIPT_NO` varchar(30) NOT NULL,
  `ISSUED_BY` int(11) DEFAULT NULL,
  `DATE_ISSUED` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblannouncements`
--

CREATE TABLE `tblannouncements` (
  `ANNOUNCEMENT_ID` int(11) NOT NULL,
  `TITLE` varchar(200) NOT NULL,
  `BODY` text DEFAULT NULL,
  `AUDIENCE` varchar(20) NOT NULL DEFAULT 'All' COMMENT 'All, Applicant, Student, Teacher, Registrar, Cashier, Admin',
  `STATUS` varchar(20) NOT NULL DEFAULT 'Published',
  `DATE_POSTED` datetime NOT NULL DEFAULT current_timestamp(),
  `POSTED_BY` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblapplicants`
--

CREATE TABLE `tblapplicants` (
  `APPLICANT_ID` int(11) NOT NULL,
  `UID` int(11) DEFAULT NULL COMMENT 'FK tblusers, the applicant login account',
  `APPLICANT_TYPE` varchar(30) NOT NULL DEFAULT 'Incoming First Year' COMMENT 'Incoming First Year, Transferee, Returning',
  `COURSE_ID` int(11) DEFAULT NULL,
  `MAJOR_ID` int(11) DEFAULT NULL,
  `SY_ID` int(11) DEFAULT NULL,
  `LNAME` varchar(40) NOT NULL,
  `FNAME` varchar(40) NOT NULL,
  `MNAME` varchar(40) DEFAULT NULL,
  `SUFFIX` varchar(10) DEFAULT NULL,
  `LRNNO` varchar(15) DEFAULT NULL,
  `CIVIL_STATUS` varchar(20) DEFAULT NULL,
  `SEX` varchar(10) DEFAULT NULL,
  `BDAY` date DEFAULT NULL,
  `BPLACE` text DEFAULT NULL,
  `NATIONALITY` varchar(40) DEFAULT NULL,
  `RELIGION` varchar(255) DEFAULT NULL,
  `EMAIL` varchar(150) DEFAULT NULL,
  `MOBILE_NO` varchar(40) DEFAULT NULL,
  `ADDRESS` text DEFAULT NULL,
  `PROVINCE` varchar(80) DEFAULT NULL,
  `CITY_MUN` varchar(80) DEFAULT NULL,
  `BRGY` varchar(80) DEFAULT NULL,
  `STATUS` varchar(40) NOT NULL DEFAULT 'Application Started' COMMENT 'Application Started, Form Incomplete, Form Completed, Payment Pending, Payment Verified, For Entrance Examination, Examination Completed, For Registrar Review, Approved, Rejected, For Enrollment, Enrolled',
  `APPROVED_S_ID` int(11) DEFAULT NULL COMMENT 'FK tblstudent once enrolled',
  `DATE_APPLIED` datetime NOT NULL DEFAULT current_timestamp(),
  `DATE_UPDATED` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblapplicants`
--

INSERT INTO `tblapplicants` (`APPLICANT_ID`, `UID`, `APPLICANT_TYPE`, `COURSE_ID`, `MAJOR_ID`, `SY_ID`, `LNAME`, `FNAME`, `MNAME`, `SUFFIX`, `LRNNO`, `CIVIL_STATUS`, `SEX`, `BDAY`, `BPLACE`, `NATIONALITY`, `RELIGION`, `EMAIL`, `MOBILE_NO`, `ADDRESS`, `PROVINCE`, `CITY_MUN`, `BRGY`, `STATUS`, `APPROVED_S_ID`, `DATE_APPLIED`, `DATE_UPDATED`) VALUES
(500, NULL, 'New Student', 13, NULL, 5, 'Trinidad', 'Liam Gabriel', 'Bautista', NULL, NULL, NULL, 'Male', '2018-05-19', 'Sagay City, Negros Occidental', 'Filipino', NULL, 'liam.trinidad@gmail.com', '09638102360', 'Brgy. Taba-ao, Sagay City, Negros Occidental', NULL, NULL, NULL, 'For Registrar Review', NULL, '2026-06-21 11:29:00', NULL),
(501, NULL, 'New Student', 10, NULL, 5, 'Beltran', 'Thea Louise', 'Bautista', NULL, NULL, NULL, 'Female', '2021-10-05', 'Sagay City, Negros Occidental', 'Filipino', NULL, 'thea.beltran@gmail.com', '09810639810', 'Brgy. Himoga-an Baybay, Sagay City, Negros Occidental', NULL, NULL, NULL, 'For Registrar Review', NULL, '2026-08-03 16:44:00', NULL),
(502, NULL, 'New Student', 12, NULL, 5, 'Sarmiento', 'Amara Joy', 'Rivera', NULL, NULL, NULL, 'Female', '2019-03-01', 'Sagay City, Negros Occidental', 'Filipino', NULL, 'amara.sarmiento@gmail.com', '09504422868', 'Brgy. Fabrica, Sagay City, Negros Occidental', NULL, NULL, NULL, 'Approved', NULL, '2026-07-07 14:15:00', NULL),
(503, NULL, 'New Student', 10, NULL, 5, 'Sarmiento', 'Amara Joy', 'Aguilar', NULL, NULL, NULL, 'Female', '2021-09-11', 'Sagay City, Negros Occidental', 'Filipino', NULL, 'amara.sarmiento@gmail.com', '09695379693', 'Brgy. Taba-ao, Sagay City, Negros Occidental', NULL, NULL, NULL, 'Payment Pending', NULL, '2026-06-04 10:17:00', NULL),
(504, NULL, 'New Student', 17, NULL, 5, 'Quirino', 'Liam Gabriel', 'Gomez', NULL, NULL, NULL, 'Male', '2014-05-21', 'Sagay City, Negros Occidental', 'Filipino', NULL, 'liam.quirino@gmail.com', '09634169825', 'Brgy. Taba-ao, Sagay City, Negros Occidental', NULL, NULL, NULL, 'Approved', NULL, '2026-06-06 09:51:00', NULL),
(505, NULL, 'New Student', 14, NULL, 5, 'Nicolas', 'Aldrin Paul', 'Aguilar', NULL, NULL, NULL, 'Male', '2017-07-15', 'Sagay City, Negros Occidental', 'Filipino', NULL, 'aldrin.nicolas@gmail.com', '09825897133', 'Brgy. Molocaboc, Sagay City, Negros Occidental', NULL, NULL, NULL, 'For Registrar Review', NULL, '2026-08-22 16:32:00', NULL),
(506, NULL, 'New Student', 16, NULL, 5, 'Beltran', 'Vince Andrei', 'Bautista', NULL, NULL, NULL, 'Male', '2015-05-19', 'Sagay City, Negros Occidental', 'Filipino', NULL, 'vince.beltran@gmail.com', '09408624026', 'Brgy. Malubon, Sagay City, Negros Occidental', NULL, NULL, NULL, 'For Registrar Review', NULL, '2026-08-14 10:53:00', '2026-09-11 05:59:36'),
(507, NULL, 'New Student', 19, NULL, 5, 'Abella', 'Yvonne', 'Tan', NULL, NULL, NULL, 'Female', '2012-08-12', 'Sagay City, Negros Occidental', 'Filipino', NULL, 'yvonne.abella@gmail.com', '09448592490', 'Brgy. Malubon, Sagay City, Negros Occidental', NULL, NULL, NULL, 'For Registrar Review', NULL, '2026-08-16 10:18:00', '2026-09-11 05:59:36'),
(508, NULL, 'New Student', 9, NULL, 5, 'Jocson', 'Isabel Marie', 'Aguilar', NULL, NULL, NULL, 'Female', '2022-03-18', 'Sagay City, Negros Occidental', 'Filipino', NULL, 'isabel.jocson@gmail.com', '09595549323', 'Brgy. Himoga-an Baybay, Sagay City, Negros Occidental', NULL, NULL, NULL, 'Form Completed', NULL, '2026-07-19 10:15:00', NULL),
(509, NULL, 'New Student', 20, NULL, 5, 'Dizon', 'Elijah Mark', 'Aguilar', NULL, NULL, NULL, 'Male', '2011-11-20', 'Sagay City, Negros Occidental', 'Filipino', NULL, 'elijah.dizon@gmail.com', '09418743935', 'Brgy. Rizal, Sagay City, Negros Occidental', NULL, NULL, NULL, 'Payment Pending', NULL, '2026-08-05 09:48:00', NULL),
(510, NULL, 'New Student', 15, NULL, 5, 'Nicolas', 'Nadine Claire', 'Reyes', NULL, NULL, NULL, 'Female', '2016-01-07', 'Sagay City, Negros Occidental', 'Filipino', NULL, 'nadine.nicolas@gmail.com', '09899969163', 'Brgy. Molocaboc, Sagay City, Negros Occidental', NULL, NULL, NULL, 'Rejected', NULL, '2026-06-06 14:41:00', NULL),
(511, NULL, 'New Student', 13, NULL, 5, 'Obispo', 'Sofia Elise', 'Rivera', NULL, NULL, NULL, 'Female', '2018-05-03', 'Sagay City, Negros Occidental', 'Filipino', NULL, 'sofia.obispo@gmail.com', '09655461980', 'Brgy. Molocaboc, Sagay City, Negros Occidental', NULL, NULL, NULL, 'For Registrar Review', NULL, '2026-06-21 12:53:00', NULL),
(512, NULL, 'New Student', 8, NULL, 5, 'Canlas', 'Vince Andrei', 'Cruz', NULL, NULL, NULL, 'Male', '2023-10-06', 'Sagay City, Negros Occidental', 'Filipino', NULL, 'vince.canlas@gmail.com', '09634325344', 'Brgy. Himoga-an Baybay, Sagay City, Negros Occidental', NULL, NULL, NULL, 'Application Started', NULL, '2026-07-10 11:28:00', NULL),
(513, NULL, 'Transferee', 8, NULL, 5, 'Pineda', 'Liana Faith', 'Lim', NULL, NULL, NULL, 'Female', '2023-10-13', 'Sagay City, Negros Occidental', 'Filipino', NULL, 'liana.pineda@gmail.com', '09773823022', 'Brgy. Rizal, Sagay City, Negros Occidental', NULL, NULL, NULL, 'For Registrar Review', NULL, '2026-06-28 12:34:00', '2026-09-11 05:59:36'),
(514, NULL, 'Transferee', 12, NULL, 5, 'Jocson', 'Yvonne', 'Aguilar', NULL, NULL, NULL, 'Female', '2019-04-05', 'Sagay City, Negros Occidental', 'Filipino', NULL, 'yvonne.jocson@gmail.com', '09618971557', 'Brgy. Poblacion II, Sagay City, Negros Occidental', NULL, NULL, NULL, 'Form Completed', NULL, '2026-06-08 16:20:00', NULL),
(515, NULL, 'Transferee', 14, NULL, 5, 'Pineda', 'Amara Joy', 'Rivera', NULL, NULL, NULL, 'Female', '2017-06-01', 'Sagay City, Negros Occidental', 'Filipino', NULL, 'amara.pineda@gmail.com', '09994328931', 'Brgy. Taba-ao, Sagay City, Negros Occidental', NULL, NULL, NULL, 'For Registrar Review', NULL, '2026-06-18 14:41:00', '2026-09-11 05:59:36'),
(516, NULL, 'Transferee', 15, NULL, 5, 'Beltran', 'Athena Mae', 'Salas', NULL, NULL, NULL, 'Female', '2016-10-06', 'Sagay City, Negros Occidental', 'Filipino', NULL, 'athena.beltran@gmail.com', '09476661422', 'Brgy. Taba-ao, Sagay City, Negros Occidental', NULL, NULL, NULL, 'Payment Pending', NULL, '2026-07-24 14:31:00', NULL),
(517, NULL, 'Returning', 20, NULL, 5, 'Fajardo', 'Zoe Marianne', 'Cruz', NULL, NULL, NULL, 'Female', '2011-06-19', 'Sagay City, Negros Occidental', 'Filipino', NULL, 'zoe.fajardo@gmail.com', '09579708150', 'Brgy. Molocaboc, Sagay City, Negros Occidental', NULL, NULL, NULL, 'Approved', NULL, '2026-07-05 10:37:00', NULL),
(518, NULL, 'Returning', 13, NULL, 5, 'Quirino', 'Amara Joy', 'Reyes', NULL, NULL, NULL, 'Female', '2018-10-17', 'Sagay City, Negros Occidental', 'Filipino', NULL, 'amara.quirino@gmail.com', '09775992862', 'Brgy. Fabrica, Sagay City, Negros Occidental', NULL, NULL, NULL, 'Application Started', NULL, '2026-06-11 15:40:00', NULL),
(519, NULL, 'Returning', 13, NULL, 5, 'Rosales', 'Liana Faith', 'Rivera', NULL, NULL, NULL, 'Female', '2018-01-22', 'Sagay City, Negros Occidental', 'Filipino', NULL, 'liana.rosales@gmail.com', '09346831659', 'Brgy. Bulanon, Sagay City, Negros Occidental', NULL, NULL, NULL, 'For Registrar Review', NULL, '2026-08-24 15:19:00', '2026-09-11 05:59:36'),
(520, 85, 'Incoming First Year', NULL, NULL, NULL, 'ghdfhdf', 'hfghdfh', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'rhammerton078@gmail.com', NULL, NULL, NULL, NULL, NULL, 'Application Started', NULL, '2026-09-11 09:52:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblattendance`
--

CREATE TABLE `tblattendance` (
  `ATTENDANCE_ID` int(11) NOT NULL,
  `UID` int(11) NOT NULL,
  `ATTENDANCE_DATE` date NOT NULL,
  `AM_TIME_IN` time DEFAULT NULL,
  `AM_TIME_OUT` time DEFAULT NULL,
  `PM_TIME_IN` time DEFAULT NULL,
  `PM_TIME_OUT` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblaudit_logs`
--

CREATE TABLE `tblaudit_logs` (
  `LOG_ID` int(11) NOT NULL,
  `UID` int(11) DEFAULT NULL,
  `ACTION` varchar(50) NOT NULL,
  `TABLE_NAME` varchar(50) DEFAULT NULL,
  `RECORD_ID` int(11) DEFAULT NULL,
  `DETAILS` text DEFAULT NULL,
  `IP_ADDRESS` varchar(45) DEFAULT NULL,
  `DATE_CREATED` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblclass_attendance`
--

CREATE TABLE `tblclass_attendance` (
  `ATTENDANCE_ID` int(11) NOT NULL,
  `S_ID` int(11) NOT NULL,
  `TS_ID` int(11) NOT NULL COMMENT 'FK tblteacher_subjects, ties attendance to a specific class offering',
  `ATTENDANCE_DATE` date NOT NULL,
  `STATUS` varchar(20) NOT NULL DEFAULT 'Present' COMMENT 'Present, Absent, Late, Excused',
  `ENCODED_BY` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblclass_schedules`
--

CREATE TABLE `tblclass_schedules` (
  `SCHEDULE_ID` int(11) NOT NULL,
  `TS_ID` int(11) NOT NULL,
  `DAY_OF_WEEK` varchar(20) DEFAULT NULL,
  `TIME_START` time DEFAULT NULL,
  `TIME_END` time DEFAULT NULL,
  `ROOM` varchar(50) DEFAULT NULL,
  `MODE` varchar(20) NOT NULL DEFAULT 'Face-to-face'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblcourses`
--

CREATE TABLE `tblcourses` (
  `COURSE_ID` int(11) NOT NULL,
  `COURSE_CODE` varchar(20) NOT NULL,
  `COURSE_NAME` varchar(150) NOT NULL,
  `COURSE_DESC` text DEFAULT NULL,
  `LEVEL_ORDER` int(11) NOT NULL DEFAULT 0,
  `DEPARTMENT` varchar(40) DEFAULT NULL,
  `STATUS` varchar(20) NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tblcourses`
--

INSERT INTO `tblcourses` (`COURSE_ID`, `COURSE_CODE`, `COURSE_NAME`, `COURSE_DESC`, `LEVEL_ORDER`, `DEPARTMENT`, `STATUS`) VALUES
(8, 'NUR1', 'Nursery 1', 'Early Childhood - 3 years old', 1, 'Early Childhood', 'Active'),
(9, 'NUR2', 'Nursery 2', 'Early Childhood - 4 years old', 2, 'Early Childhood', 'Active'),
(10, 'KINDER', 'Kindergarten', 'Early Childhood - 5 years old', 3, 'Early Childhood', 'Active'),
(11, 'G1', 'Grade 1', 'Elementary', 4, 'Elementary', 'Active'),
(12, 'G2', 'Grade 2', 'Elementary', 5, 'Elementary', 'Active'),
(13, 'G3', 'Grade 3', 'Elementary', 6, 'Elementary', 'Active'),
(14, 'G4', 'Grade 4', 'Elementary', 7, 'Elementary', 'Active'),
(15, 'G5', 'Grade 5', 'Elementary', 8, 'Elementary', 'Active'),
(16, 'G6', 'Grade 6', 'Elementary', 9, 'Elementary', 'Active'),
(17, 'G7', 'Grade 7', 'Junior High School', 10, 'Junior High School', 'Active'),
(18, 'G8', 'Grade 8', 'Junior High School', 11, 'Junior High School', 'Active'),
(19, 'G9', 'Grade 9', 'Junior High School', 12, 'Junior High School', 'Active'),
(20, 'G10', 'Grade 10', 'Junior High School', 13, 'Junior High School', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `tbldocuments`
--

CREATE TABLE `tbldocuments` (
  `DOCUMENT_ID` int(11) NOT NULL,
  `APPLICANT_ID` int(11) DEFAULT NULL,
  `S_ID` int(11) DEFAULT NULL,
  `DOC_TYPE` varchar(100) NOT NULL,
  `FILE_PATH` varchar(255) DEFAULT NULL,
  `STATUS` varchar(20) NOT NULL DEFAULT 'Pending' COMMENT 'Pending, Verified, Rejected',
  `UPLOADED_BY` int(11) DEFAULT NULL,
  `DATE_UPLOADED` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblenrollment`
--

CREATE TABLE `tblenrollment` (
  `ENROLLMENT_ID` int(11) NOT NULL,
  `S_ID` int(11) NOT NULL,
  `COURSE_ID` int(11) NOT NULL,
  `SECTION_ID` int(11) DEFAULT NULL,
  `SY_ID` int(11) NOT NULL,
  `YEAR_LEVEL` varchar(20) NOT NULL,
  `SEMESTER` varchar(20) NOT NULL,
  `CATEGORY` varchar(30) NOT NULL DEFAULT 'New',
  `CURRICULUM_YR` varchar(20) DEFAULT NULL,
  `DATE_RESERVED` date DEFAULT NULL,
  `DATE_ENROLLED` date DEFAULT NULL,
  `STATUS` varchar(30) NOT NULL DEFAULT 'Reserved',
  `ENCODED_BY` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tblenrollment`
--

INSERT INTO `tblenrollment` (`ENROLLMENT_ID`, `S_ID`, `COURSE_ID`, `SECTION_ID`, `SY_ID`, `YEAR_LEVEL`, `SEMESTER`, `CATEGORY`, `CURRICULUM_YR`, `DATE_RESERVED`, `DATE_ENROLLED`, `STATUS`, `ENCODED_BY`) VALUES
(4, 7, 10, 13, 5, 'Kindergarten', 'Whole Year', 'New', NULL, '2026-09-10', '2026-09-10', 'Enrolled', NULL),
(5, 8, 11, 14, 5, 'Grade 1', 'Whole Year', 'New', NULL, '2026-09-10', '2026-09-10', 'Enrolled', NULL),
(6, 9, 12, 15, 5, 'Grade 2', 'Whole Year', 'New', NULL, '2026-09-10', '2026-09-10', 'Enrolled', NULL),
(7, 10, 13, 16, 5, 'Grade 3', 'Whole Year', 'New', NULL, '2026-09-10', '2026-09-10', 'Enrolled', NULL),
(8, 11, 14, 17, 5, 'Grade 4', 'Whole Year', 'New', NULL, '2026-09-10', NULL, 'Paid', NULL),
(9, 12, 15, 18, 5, 'Grade 5', 'Whole Year', 'New', NULL, '2026-09-10', NULL, 'Paid', NULL),
(10, 13, 16, 19, 5, 'Grade 6', 'Whole Year', 'New', NULL, '2026-09-10', NULL, 'Sectioned', NULL),
(11, 14, 17, 20, 5, 'Grade 7', 'Whole Year', 'New', NULL, '2026-09-10', NULL, 'Sectioned', NULL),
(12, 15, 18, NULL, 5, 'Grade 8', 'Whole Year', 'New', NULL, '2026-09-10', NULL, 'Assigned', NULL),
(13, 16, 19, NULL, 5, 'Grade 9', 'Whole Year', 'New', NULL, '2026-09-10', NULL, 'Assigned', NULL),
(14, 17, 20, NULL, 5, 'Grade 10', 'Whole Year', 'New', NULL, '2026-09-10', NULL, 'Registered', NULL),
(15, 18, 17, NULL, 5, 'Grade 7', 'Whole Year', 'New', NULL, '2026-09-10', NULL, 'Registered', NULL),
(16, 19, 14, 17, 5, 'Grade 4', 'Whole Year', 'New', NULL, '2026-09-10', '2026-09-10', 'Enrolled', NULL),
(17, 20, 12, NULL, 5, 'Grade 2', 'Whole Year', 'New', NULL, '2026-09-10', NULL, 'Registered', NULL),
(1000, 1000, 8, 200, 4, 'Nursery 1', 'Whole Year', 'New', NULL, '2024-06-06', '2024-06-04', 'Enrolled', NULL),
(1001, 1001, 8, 200, 4, 'Nursery 1', 'Whole Year', 'New', NULL, '2024-06-08', '2024-06-26', 'Enrolled', NULL),
(1002, 1002, 8, 200, 4, 'Nursery 1', 'Whole Year', 'New', NULL, '2024-06-05', '2024-06-11', 'Enrolled', NULL),
(1003, 1003, 8, 200, 4, 'Nursery 1', 'Whole Year', 'New', NULL, '2024-06-11', '2024-06-25', 'Enrolled', NULL),
(1004, 1004, 8, 200, 4, 'Nursery 1', 'Whole Year', 'New', NULL, '2024-06-08', '2024-06-07', 'Enrolled', NULL),
(1005, 1005, 8, 200, 4, 'Nursery 1', 'Whole Year', 'New', NULL, '2024-06-08', '2024-06-13', 'Enrolled', NULL),
(1006, 1006, 8, 200, 4, 'Nursery 1', 'Whole Year', 'New', NULL, '2024-06-25', '2024-06-23', 'Enrolled', NULL),
(1007, 1007, 8, 200, 4, 'Nursery 1', 'Whole Year', 'New', NULL, '2024-06-05', '2024-06-19', 'Enrolled', NULL),
(1008, 1008, 9, 201, 4, 'Nursery 2', 'Whole Year', 'New', NULL, '2024-06-25', '2024-06-08', 'Enrolled', NULL),
(1009, 1009, 9, 201, 4, 'Nursery 2', 'Whole Year', 'New', NULL, '2024-06-03', '2024-06-03', 'Enrolled', NULL),
(1010, 1010, 9, 201, 4, 'Nursery 2', 'Whole Year', 'New', NULL, '2024-06-12', '2024-06-26', 'Enrolled', NULL),
(1011, 1011, 9, 201, 4, 'Nursery 2', 'Whole Year', 'New', NULL, '2024-06-18', '2024-06-07', 'Enrolled', NULL),
(1012, 1012, 9, 201, 4, 'Nursery 2', 'Whole Year', 'New', NULL, '2024-06-19', '2024-06-13', 'Enrolled', NULL),
(1013, 1013, 9, 201, 4, 'Nursery 2', 'Whole Year', 'New', NULL, '2024-06-27', '2024-06-13', 'Enrolled', NULL),
(1014, 1014, 9, 201, 4, 'Nursery 2', 'Whole Year', 'New', NULL, '2024-06-05', '2024-06-16', 'Enrolled', NULL),
(1015, 1015, 9, 201, 4, 'Nursery 2', 'Whole Year', 'New', NULL, '2024-06-18', '2024-06-04', 'Enrolled', NULL),
(1016, 1016, 9, 201, 4, 'Nursery 2', 'Whole Year', 'New', NULL, '2024-06-23', '2024-06-08', 'Enrolled', NULL),
(1017, 1017, 9, 201, 4, 'Nursery 2', 'Whole Year', 'New', NULL, '2024-06-10', '2024-06-17', 'Enrolled', NULL),
(1018, 1018, 10, 202, 4, 'Kindergarten', 'Whole Year', 'New', NULL, '2024-06-04', '2024-06-24', 'Enrolled', NULL),
(1019, 1019, 10, 202, 4, 'Kindergarten', 'Whole Year', 'New', NULL, '2024-06-19', '2024-06-07', 'Enrolled', NULL),
(1020, 1020, 10, 202, 4, 'Kindergarten', 'Whole Year', 'New', NULL, '2024-06-08', '2024-06-05', 'Enrolled', NULL),
(1021, 1021, 10, 202, 4, 'Kindergarten', 'Whole Year', 'New', NULL, '2024-06-15', '2024-06-22', 'Enrolled', NULL),
(1022, 1022, 10, 202, 4, 'Kindergarten', 'Whole Year', 'New', NULL, '2024-06-15', '2024-06-21', 'Enrolled', NULL),
(1023, 1023, 10, 202, 4, 'Kindergarten', 'Whole Year', 'New', NULL, '2024-06-28', '2024-06-12', 'Enrolled', NULL),
(1024, 1024, 10, 202, 4, 'Kindergarten', 'Whole Year', 'New', NULL, '2024-06-16', '2024-06-09', 'Enrolled', NULL),
(1025, 1025, 10, 202, 4, 'Kindergarten', 'Whole Year', 'New', NULL, '2024-06-09', '2024-06-22', 'Enrolled', NULL),
(1026, 1026, 10, 202, 4, 'Kindergarten', 'Whole Year', 'New', NULL, '2024-06-05', '2024-06-13', 'Enrolled', NULL),
(1027, 1027, 10, 202, 4, 'Kindergarten', 'Whole Year', 'New', NULL, '2024-06-05', '2024-06-24', 'Enrolled', NULL),
(1028, 1028, 10, 202, 4, 'Kindergarten', 'Whole Year', 'New', NULL, '2024-06-21', '2024-06-11', 'Enrolled', NULL),
(1029, 1029, 10, 202, 4, 'Kindergarten', 'Whole Year', 'New', NULL, '2024-06-21', '2024-06-28', 'Enrolled', NULL),
(1030, 1030, 11, 203, 4, 'Grade 1', 'Whole Year', 'New', NULL, '2024-06-10', '2024-06-05', 'Enrolled', NULL),
(1031, 1031, 11, 203, 4, 'Grade 1', 'Whole Year', 'New', NULL, '2024-06-03', '2024-06-17', 'Enrolled', NULL),
(1032, 1032, 11, 203, 4, 'Grade 1', 'Whole Year', 'New', NULL, '2024-06-13', '2024-06-27', 'Enrolled', NULL),
(1033, 1033, 11, 203, 4, 'Grade 1', 'Whole Year', 'New', NULL, '2024-06-04', '2024-06-19', 'Enrolled', NULL),
(1034, 1034, 11, 203, 4, 'Grade 1', 'Whole Year', 'New', NULL, '2024-06-12', '2024-06-22', 'Enrolled', NULL),
(1035, 1035, 11, 203, 4, 'Grade 1', 'Whole Year', 'New', NULL, '2024-06-13', '2024-06-17', 'Enrolled', NULL),
(1036, 1036, 11, 203, 4, 'Grade 1', 'Whole Year', 'New', NULL, '2024-06-14', '2024-06-16', 'Enrolled', NULL),
(1037, 1037, 12, 204, 4, 'Grade 2', 'Whole Year', 'New', NULL, '2024-06-10', '2024-06-27', 'Enrolled', NULL),
(1038, 1038, 12, 204, 4, 'Grade 2', 'Whole Year', 'New', NULL, '2024-06-23', '2024-06-14', 'Enrolled', NULL),
(1039, 1039, 12, 204, 4, 'Grade 2', 'Whole Year', 'New', NULL, '2024-06-09', '2024-06-28', 'Enrolled', NULL),
(1136, 1000, 9, 214, 1, 'Nursery 2', 'Whole Year', 'Old', NULL, '2025-06-20', '2025-06-17', 'Enrolled', NULL),
(1137, 1001, 9, 214, 1, 'Nursery 2', 'Whole Year', 'Old', NULL, '2025-06-14', '2025-06-19', 'Enrolled', NULL),
(1138, 1002, 9, 214, 1, 'Nursery 2', 'Whole Year', 'Old', NULL, '2025-06-15', '2025-06-05', 'Enrolled', NULL),
(1139, 1003, 9, 214, 1, 'Nursery 2', 'Whole Year', 'Old', NULL, '2025-06-04', '2025-06-19', 'Enrolled', NULL),
(1140, 1004, 9, 214, 1, 'Nursery 2', 'Whole Year', 'Old', NULL, '2025-06-26', '2025-06-19', 'Enrolled', NULL),
(1141, 1005, 9, 214, 1, 'Nursery 2', 'Whole Year', 'Old', NULL, '2025-06-11', '2025-06-14', 'Enrolled', NULL),
(1142, 1006, 9, 214, 1, 'Nursery 2', 'Whole Year', 'Old', NULL, '2025-06-04', '2025-06-09', 'Enrolled', NULL),
(1143, 1007, 9, 214, 1, 'Nursery 2', 'Whole Year', 'Old', NULL, '2025-06-23', '2025-06-10', 'Enrolled', NULL),
(1144, 1008, 10, 215, 1, 'Kindergarten', 'Whole Year', 'Old', NULL, '2025-06-15', '2025-06-28', 'Enrolled', NULL),
(1145, 1009, 10, 215, 1, 'Kindergarten', 'Whole Year', 'Old', NULL, '2025-06-03', '2025-06-12', 'Enrolled', NULL),
(1146, 1010, 10, 215, 1, 'Kindergarten', 'Whole Year', 'Old', NULL, '2025-06-28', '2025-06-19', 'Enrolled', NULL),
(1147, 1011, 10, 215, 1, 'Kindergarten', 'Whole Year', 'Old', NULL, '2025-06-20', '2025-06-15', 'Enrolled', NULL),
(1148, 1012, 10, 215, 1, 'Kindergarten', 'Whole Year', 'Old', NULL, '2025-06-15', '2025-06-23', 'Enrolled', NULL),
(1149, 1013, 10, 215, 1, 'Kindergarten', 'Whole Year', 'Old', NULL, '2025-06-12', '2025-06-11', 'Enrolled', NULL),
(1150, 1014, 10, 215, 1, 'Kindergarten', 'Whole Year', 'Old', NULL, '2025-06-26', '2025-06-09', 'Enrolled', NULL),
(1151, 1016, 10, 215, 1, 'Kindergarten', 'Whole Year', 'Old', NULL, '2025-06-13', '2025-06-17', 'Enrolled', NULL),
(1152, 1017, 10, 215, 1, 'Kindergarten', 'Whole Year', 'Old', NULL, '2025-06-11', '2025-06-03', 'Enrolled', NULL),
(1153, 1018, 11, 216, 1, 'Grade 1', 'Whole Year', 'Old', NULL, '2025-06-27', '2025-06-17', 'Enrolled', NULL),
(1154, 1019, 11, 216, 1, 'Grade 1', 'Whole Year', 'Old', NULL, '2025-06-20', '2025-06-11', 'Enrolled', NULL),
(1155, 1020, 11, 216, 1, 'Grade 1', 'Whole Year', 'Old', NULL, '2025-06-07', '2025-06-12', 'Enrolled', NULL),
(1156, 1021, 11, 216, 1, 'Grade 1', 'Whole Year', 'Old', NULL, '2025-06-24', '2025-06-17', 'Enrolled', NULL),
(1157, 1022, 11, 216, 1, 'Grade 1', 'Whole Year', 'Old', NULL, '2025-06-27', '2025-06-28', 'Enrolled', NULL),
(1158, 1023, 11, 216, 1, 'Grade 1', 'Whole Year', 'Old', NULL, '2025-06-13', '2025-06-12', 'Enrolled', NULL),
(1159, 1024, 11, 216, 1, 'Grade 1', 'Whole Year', 'Old', NULL, '2025-06-20', '2025-06-09', 'Enrolled', NULL),
(1160, 1025, 11, 216, 1, 'Grade 1', 'Whole Year', 'Old', NULL, '2025-06-19', '2025-06-13', 'Enrolled', NULL),
(1161, 1026, 11, 216, 1, 'Grade 1', 'Whole Year', 'Old', NULL, '2025-06-06', '2025-06-13', 'Enrolled', NULL),
(1162, 1027, 11, 216, 1, 'Grade 1', 'Whole Year', 'Old', NULL, '2025-06-25', '2025-06-04', 'Enrolled', NULL),
(1163, 1028, 11, 216, 1, 'Grade 1', 'Whole Year', 'Old', NULL, '2025-06-03', '2025-06-21', 'Enrolled', NULL),
(1164, 1029, 11, 216, 1, 'Grade 1', 'Whole Year', 'Old', NULL, '2025-06-15', '2025-06-06', 'Enrolled', NULL),
(1165, 1030, 12, 217, 1, 'Grade 2', 'Whole Year', 'Old', NULL, '2025-06-19', '2025-06-05', 'Enrolled', NULL),
(1166, 1031, 12, 217, 1, 'Grade 2', 'Whole Year', 'Old', NULL, '2025-06-05', '2025-06-22', 'Enrolled', NULL),
(1167, 1032, 12, 217, 1, 'Grade 2', 'Whole Year', 'Old', NULL, '2025-06-20', '2025-06-08', 'Enrolled', NULL),
(1168, 1033, 12, 217, 1, 'Grade 2', 'Whole Year', 'Old', NULL, '2025-06-04', '2025-06-08', 'Enrolled', NULL),
(1169, 1034, 12, 217, 1, 'Grade 2', 'Whole Year', 'Old', NULL, '2025-06-22', '2025-06-03', 'Enrolled', NULL),
(1170, 1035, 12, 217, 1, 'Grade 2', 'Whole Year', 'Old', NULL, '2025-06-24', '2025-06-11', 'Enrolled', NULL),
(1171, 1036, 12, 217, 1, 'Grade 2', 'Whole Year', 'Old', NULL, '2025-06-25', '2025-06-17', 'Enrolled', NULL),
(1172, 1037, 13, 218, 1, 'Grade 3', 'Whole Year', 'Old', NULL, '2025-06-14', '2025-06-17', 'Enrolled', NULL),
(1173, 1038, 13, 218, 1, 'Grade 3', 'Whole Year', 'Old', NULL, '2025-06-10', '2025-06-15', 'Enrolled', NULL),
(1174, 1039, 13, 218, 1, 'Grade 3', 'Whole Year', 'Old', NULL, '2025-06-16', '2025-06-16', 'Enrolled', NULL),
(1279, 1000, 10, 13, 5, 'Kindergarten', 'Whole Year', 'Old', NULL, '2026-06-21', '2026-06-20', 'Enrolled', NULL),
(1280, 1002, 10, 13, 5, 'Kindergarten', 'Whole Year', 'Old', NULL, '2026-06-09', '2026-06-16', 'Enrolled', NULL),
(1281, 1004, 10, 13, 5, 'Kindergarten', 'Whole Year', 'Old', NULL, '2026-06-26', NULL, 'Sectioned', NULL),
(1282, 1005, 10, 13, 5, 'Kindergarten', 'Whole Year', 'Old', NULL, '2026-06-15', NULL, 'Sectioned', NULL),
(1283, 1006, 10, 13, 5, 'Kindergarten', 'Whole Year', 'Old', NULL, '2026-06-19', '2026-06-27', 'Enrolled', NULL),
(1284, 1007, 10, 13, 5, 'Kindergarten', 'Whole Year', 'Old', NULL, '2026-06-04', '2026-06-14', 'Enrolled', NULL),
(1285, 1008, 11, 14, 5, 'Grade 1', 'Whole Year', 'Old', NULL, '2026-06-20', NULL, 'Sectioned', NULL),
(1286, 1009, 11, 14, 5, 'Grade 1', 'Whole Year', 'Old', NULL, '2026-06-12', '2026-06-11', 'Enrolled', NULL),
(1287, 1010, 11, 14, 5, 'Grade 1', 'Whole Year', 'Old', NULL, '2026-06-28', '2026-06-17', 'Enrolled', NULL),
(1288, 1011, 11, 14, 5, 'Grade 1', 'Whole Year', 'Old', NULL, '2026-06-08', '2026-06-18', 'Enrolled', NULL),
(1289, 1012, 11, 14, 5, 'Grade 1', 'Whole Year', 'Old', NULL, '2026-06-17', '2026-06-24', 'Enrolled', NULL),
(1290, 1013, 11, 14, 5, 'Grade 1', 'Whole Year', 'Old', NULL, '2026-06-11', '2026-06-05', 'Enrolled', NULL),
(1291, 1014, 11, 14, 5, 'Grade 1', 'Whole Year', 'Old', NULL, '2026-06-18', NULL, 'Sectioned', NULL),
(1292, 1015, 11, NULL, 5, 'Grade 1', 'Whole Year', 'Old', NULL, '2026-06-22', NULL, 'Registered', NULL),
(1293, 1016, 11, NULL, 5, 'Grade 1', 'Whole Year', 'Old', NULL, '2026-06-27', NULL, 'Assigned', NULL),
(1294, 1017, 11, 14, 5, 'Grade 1', 'Whole Year', 'Old', NULL, '2026-06-05', '2026-06-07', 'Enrolled', NULL),
(1295, 1018, 12, 15, 5, 'Grade 2', 'Whole Year', 'Old', NULL, '2026-06-07', NULL, 'Sectioned', NULL),
(1296, 1019, 12, NULL, 5, 'Grade 2', 'Whole Year', 'Old', NULL, '2026-06-09', NULL, 'Assigned', NULL),
(1297, 1020, 12, 15, 5, 'Grade 2', 'Whole Year', 'Old', NULL, '2026-06-23', '2026-06-22', 'Enrolled', NULL),
(1298, 1021, 12, 15, 5, 'Grade 2', 'Whole Year', 'Old', NULL, '2026-06-09', '2026-06-09', 'Enrolled', NULL),
(1299, 1022, 12, 15, 5, 'Grade 2', 'Whole Year', 'Old', NULL, '2026-06-24', '2026-06-03', 'Enrolled', NULL),
(1300, 1023, 12, 15, 5, 'Grade 2', 'Whole Year', 'Old', NULL, '2026-06-05', NULL, 'Paid', NULL),
(1301, 1025, 12, NULL, 5, 'Grade 2', 'Whole Year', 'Old', NULL, '2026-06-05', NULL, 'Assigned', NULL),
(1302, 1026, 12, 15, 5, 'Grade 2', 'Whole Year', 'Old', NULL, '2026-06-04', '2026-06-24', 'Enrolled', NULL),
(1303, 1027, 12, 15, 5, 'Grade 2', 'Whole Year', 'Old', NULL, '2026-06-17', '2026-06-03', 'Enrolled', NULL),
(1304, 1030, 13, 16, 5, 'Grade 3', 'Whole Year', 'Old', NULL, '2026-06-06', '2026-06-23', 'Enrolled', NULL),
(1305, 1031, 13, 16, 5, 'Grade 3', 'Whole Year', 'Old', NULL, '2026-06-14', '2026-06-10', 'Enrolled', NULL),
(1306, 1032, 13, 16, 5, 'Grade 3', 'Whole Year', 'Old', NULL, '2026-06-20', '2026-06-10', 'Enrolled', NULL),
(1307, 1033, 13, 16, 5, 'Grade 3', 'Whole Year', 'Old', NULL, '2026-06-05', '2026-06-21', 'Enrolled', NULL),
(1308, 1034, 13, 16, 5, 'Grade 3', 'Whole Year', 'Old', NULL, '2026-06-16', NULL, 'Sectioned', NULL),
(1309, 1035, 13, 16, 5, 'Grade 3', 'Whole Year', 'Old', NULL, '2026-06-10', '2026-06-07', 'Enrolled', NULL),
(1310, 1036, 13, 16, 5, 'Grade 3', 'Whole Year', 'Old', NULL, '2026-06-07', '2026-06-15', 'Enrolled', NULL),
(1311, 1037, 14, 17, 5, 'Grade 4', 'Whole Year', 'Old', NULL, '2026-06-06', NULL, 'Sectioned', NULL),
(1312, 1038, 14, 17, 5, 'Grade 4', 'Whole Year', 'Old', NULL, '2026-06-11', '2026-06-09', 'Enrolled', NULL),
(1313, 1039, 14, 17, 5, 'Grade 4', 'Whole Year', 'Old', NULL, '2026-06-13', NULL, 'Paid', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblenrollment_details`
--

CREATE TABLE `tblenrollment_details` (
  `DETAIL_ID` int(11) NOT NULL,
  `ENROLLMENT_ID` int(11) NOT NULL,
  `SUBJECT_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblentrance_exams`
--

CREATE TABLE `tblentrance_exams` (
  `EXAM_ID` int(11) NOT NULL,
  `SY_ID` int(11) DEFAULT NULL,
  `EXAM_NAME` varchar(150) NOT NULL,
  `EXAM_DATE` date DEFAULT NULL,
  `EXAM_VENUE` varchar(150) DEFAULT NULL,
  `STATUS` varchar(20) NOT NULL DEFAULT 'Scheduled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblexam_results`
--

CREATE TABLE `tblexam_results` (
  `RESULT_ID` int(11) NOT NULL,
  `APPLICANT_ID` int(11) NOT NULL,
  `EXAM_ID` int(11) NOT NULL,
  `SCORE` decimal(6,2) DEFAULT NULL,
  `PASSING_SCORE` decimal(6,2) DEFAULT NULL,
  `RESULT` varchar(20) NOT NULL DEFAULT 'Pending' COMMENT 'Pending, Passed, Failed',
  `DATE_TAKEN` date DEFAULT NULL,
  `ENCODED_BY` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblfeeschedule`
--

CREATE TABLE `tblfeeschedule` (
  `FEESCHEDULE_ID` int(11) NOT NULL,
  `COURSE_ID` int(11) NOT NULL,
  `YEAR_LEVEL` varchar(20) NOT NULL,
  `SY_ID` int(11) DEFAULT NULL,
  `TUITION_FEE` decimal(10,2) NOT NULL DEFAULT 0.00,
  `MISC_FEE` decimal(10,2) NOT NULL DEFAULT 0.00,
  `BOOKS_FEE` decimal(10,2) NOT NULL DEFAULT 0.00,
  `OTHER_FEE` decimal(10,2) NOT NULL DEFAULT 0.00,
  `REG_FEE` decimal(10,2) NOT NULL DEFAULT 1000.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblfeeschedule`
--

INSERT INTO `tblfeeschedule` (`FEESCHEDULE_ID`, `COURSE_ID`, `YEAR_LEVEL`, `SY_ID`, `TUITION_FEE`, `MISC_FEE`, `BOOKS_FEE`, `OTHER_FEE`, `REG_FEE`) VALUES
(1, 8, 'Nursery 1', NULL, 9000.00, 2500.00, 1200.00, 500.00, 1000.00),
(2, 9, 'Nursery 2', NULL, 9000.00, 2500.00, 1200.00, 500.00, 1000.00),
(3, 10, 'Kindergarten', NULL, 10500.00, 3000.00, 1500.00, 500.00, 1000.00),
(4, 11, 'Grade 1', NULL, 12000.00, 3200.00, 1800.00, 500.00, 1000.00),
(5, 12, 'Grade 2', NULL, 12000.00, 3200.00, 1800.00, 500.00, 1000.00),
(6, 13, 'Grade 3', NULL, 12500.00, 3200.00, 1800.00, 500.00, 1000.00),
(7, 14, 'Grade 4', NULL, 12500.00, 3400.00, 2000.00, 500.00, 1000.00),
(8, 15, 'Grade 5', NULL, 13000.00, 3400.00, 2000.00, 500.00, 1000.00),
(9, 16, 'Grade 6', NULL, 13000.00, 3400.00, 2000.00, 500.00, 1000.00),
(10, 17, 'Grade 7', NULL, 15000.00, 4200.00, 2600.00, 500.00, 1000.00),
(11, 18, 'Grade 8', NULL, 15000.00, 4200.00, 2600.00, 500.00, 1000.00),
(12, 19, 'Grade 9', NULL, 15500.00, 4200.00, 2600.00, 500.00, 1000.00),
(13, 20, 'Grade 10', NULL, 16000.00, 4500.00, 2800.00, 500.00, 1000.00);

-- --------------------------------------------------------

--
-- Table structure for table `tblfee_types`
--

CREATE TABLE `tblfee_types` (
  `FEE_TYPE_ID` int(11) NOT NULL,
  `FEE_CODE` varchar(30) NOT NULL,
  `FEE_NAME` varchar(100) NOT NULL,
  `DEFAULT_AMOUNT` decimal(10,2) NOT NULL DEFAULT 0.00,
  `IS_PER_UNIT` tinyint(1) NOT NULL DEFAULT 0,
  `APPLIES_TO` varchar(30) NOT NULL DEFAULT 'All' COMMENT 'All, Incoming First Year, Transferee, Returning',
  `STATUS` varchar(20) NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblfee_types`
--

INSERT INTO `tblfee_types` (`FEE_TYPE_ID`, `FEE_CODE`, `FEE_NAME`, `DEFAULT_AMOUNT`, `IS_PER_UNIT`, `APPLIES_TO`, `STATUS`) VALUES
(1, 'ENROLLMENT_FEE', 'Enrollment Fee', 1000.00, 0, 'All', 'Active'),
(2, 'ENTRANCE_EXAM', 'Entrance Examination', 150.00, 0, 'Incoming First Year', 'Active'),
(3, 'TUITION_PER_UNIT', 'Tuition (per unit)', 500.00, 1, 'All', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `tblgrades`
--

CREATE TABLE `tblgrades` (
  `GRADE_ID` int(11) NOT NULL,
  `S_ID` int(11) NOT NULL,
  `SUBJECT_ID` int(11) NOT NULL,
  `ENROLLMENT_ID` int(11) NOT NULL,
  `SY_ID` int(11) NOT NULL,
  `SEMESTER` varchar(20) DEFAULT NULL,
  `GRADE` decimal(5,2) DEFAULT NULL,
  `REMARKS` varchar(20) DEFAULT NULL,
  `DATE_ENCODED` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblhomepage_content_blocks`
--

CREATE TABLE `tblhomepage_content_blocks` (
  `BLOCK_ID` int(11) NOT NULL,
  `SECTION_ID` int(11) NOT NULL,
  `BLOCK_TYPE` varchar(20) NOT NULL DEFAULT 'Text' COMMENT 'Text, Image, Video, Button',
  `CONTENT` text DEFAULT NULL,
  `MEDIA_PATH` varchar(255) DEFAULT NULL,
  `VIDEO_URL` varchar(255) DEFAULT NULL,
  `LINK_URL` varchar(255) DEFAULT NULL,
  `SORT_ORDER` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblhomepage_sections`
--

CREATE TABLE `tblhomepage_sections` (
  `SECTION_ID` int(11) NOT NULL,
  `SECTION_KEY` varchar(50) NOT NULL,
  `TITLE` varchar(150) DEFAULT NULL,
  `IS_ENABLED` tinyint(1) NOT NULL DEFAULT 1,
  `SORT_ORDER` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblhomepage_sections`
--

INSERT INTO `tblhomepage_sections` (`SECTION_ID`, `SECTION_KEY`, `TITLE`, `IS_ENABLED`, `SORT_ORDER`) VALUES
(1, 'hero', 'Hero', 1, 1),
(2, 'news', 'News & Announcements', 1, 2),
(3, 'programs', 'Programs', 1, 3),
(4, 'portals', 'The School Portals', 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tblmajors`
--

CREATE TABLE `tblmajors` (
  `MAJOR_ID` int(11) NOT NULL,
  `COURSE_ID` int(11) NOT NULL,
  `MAJOR_CODE` varchar(20) NOT NULL,
  `MAJOR_NAME` varchar(150) NOT NULL,
  `STATUS` varchar(20) NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblnews`
--

CREATE TABLE `tblnews` (
  `NEWS_ID` int(11) NOT NULL,
  `TITLE` varchar(200) NOT NULL,
  `SUBTITLE` varchar(255) DEFAULT NULL,
  `AUTHOR` varchar(100) DEFAULT NULL,
  `CATEGORY` varchar(50) DEFAULT NULL,
  `FEATURED_IMAGE` varchar(255) DEFAULT NULL,
  `STATUS` varchar(20) NOT NULL DEFAULT 'Draft' COMMENT 'Draft, Published',
  `IS_FEATURED` tinyint(1) NOT NULL DEFAULT 0,
  `SORT_ORDER` int(11) NOT NULL DEFAULT 0,
  `DATE_PUBLISHED` datetime DEFAULT NULL,
  `CREATED_BY` int(11) DEFAULT NULL,
  `DATE_CREATED` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblnews_media`
--

CREATE TABLE `tblnews_media` (
  `MEDIA_ID` int(11) NOT NULL,
  `NEWS_ID` int(11) NOT NULL,
  `BLOCK_TYPE` varchar(20) NOT NULL DEFAULT 'Text' COMMENT 'Text, Image, Video, Caption',
  `TEXT_CONTENT` text DEFAULT NULL,
  `FILE_PATH` varchar(255) DEFAULT NULL,
  `VIDEO_URL` varchar(255) DEFAULT NULL,
  `SORT_ORDER` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblnotifications`
--

CREATE TABLE `tblnotifications` (
  `NOTIF_ID` int(11) NOT NULL,
  `UID` int(11) NOT NULL,
  `MESSAGE` varchar(255) NOT NULL,
  `LINK` varchar(255) DEFAULT NULL,
  `IS_READ` tinyint(1) NOT NULL DEFAULT 0,
  `DATE_CREATED` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblparents`
--

CREATE TABLE `tblparents` (
  `PARENT_ID` int(11) NOT NULL,
  `APPLICANT_ID` int(11) DEFAULT NULL,
  `S_ID` int(11) DEFAULT NULL,
  `ROLE` varchar(20) NOT NULL COMMENT 'Father, Mother, Guardian',
  `FULL_NAME` varchar(150) DEFAULT NULL,
  `CONTACT_NO` varchar(40) DEFAULT NULL,
  `EMAIL` varchar(150) DEFAULT NULL,
  `OCCUPATION` varchar(100) DEFAULT NULL,
  `DECEASED` varchar(3) DEFAULT 'No',
  `RELATIONSHIP` varchar(50) DEFAULT NULL COMMENT 'used for Guardian role only',
  `ADDRESS` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblpayments`
--

CREATE TABLE `tblpayments` (
  `PAYMENT_ID` int(11) NOT NULL,
  `ENROLLMENT_ID` int(11) NOT NULL,
  `FEE_TYPE` varchar(30) NOT NULL COMMENT 'Enrollment Fee, Entrance Exam, Admission Fee',
  `AMOUNT` decimal(10,2) NOT NULL DEFAULT 0.00,
  `METHOD` varchar(30) NOT NULL DEFAULT 'Cash',
  `STATUS` varchar(20) NOT NULL DEFAULT 'Approved',
  `OR_NUMBER` varchar(50) NOT NULL,
  `REF_NO` varchar(80) DEFAULT NULL,
  `PROOF` varchar(255) DEFAULT NULL,
  `DATE_PAID` date NOT NULL,
  `RECEIVED_BY` int(11) DEFAULT NULL,
  `CASHIER` varchar(120) DEFAULT NULL,
  `REMARKS` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblpayments`
--

INSERT INTO `tblpayments` (`PAYMENT_ID`, `ENROLLMENT_ID`, `FEE_TYPE`, `AMOUNT`, `METHOD`, `STATUS`, `OR_NUMBER`, `REF_NO`, `PROOF`, `DATE_PAID`, `RECEIVED_BY`, `CASHIER`, `REMARKS`) VALUES
(3, 4, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-00004', NULL, NULL, '2026-09-10', NULL, NULL, NULL),
(4, 5, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-00005', NULL, NULL, '2026-09-10', NULL, NULL, NULL),
(5, 6, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-00006', NULL, NULL, '2026-09-10', NULL, NULL, NULL),
(6, 7, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-00007', NULL, NULL, '2026-09-10', NULL, NULL, NULL),
(7, 8, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-00008', NULL, NULL, '2026-09-10', NULL, NULL, NULL),
(8, 9, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-00009', NULL, NULL, '2026-09-10', NULL, NULL, NULL),
(9, 16, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-00016', NULL, NULL, '2026-09-10', NULL, NULL, NULL),
(16, 4, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-000004', NULL, NULL, '2026-09-10', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(17, 5, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-000005', NULL, NULL, '2026-09-10', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(18, 6, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-000006', NULL, NULL, '2026-09-10', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(19, 7, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-000007', NULL, NULL, '2026-09-10', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(20, 8, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-000008', NULL, NULL, '2026-09-10', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(21, 9, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-000009', NULL, NULL, '2026-09-10', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(22, 16, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-000016', NULL, NULL, '2026-09-10', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(23, 1000, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001000', NULL, NULL, '2024-06-04', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(24, 1001, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001001', NULL, NULL, '2024-06-26', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(25, 1002, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001002', NULL, NULL, '2024-06-11', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(26, 1003, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001003', NULL, NULL, '2024-06-25', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(27, 1004, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001004', NULL, NULL, '2024-06-07', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(28, 1005, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001005', NULL, NULL, '2024-06-13', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(29, 1006, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001006', NULL, NULL, '2024-06-23', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(30, 1007, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001007', NULL, NULL, '2024-06-19', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(31, 1008, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001008', NULL, NULL, '2024-06-08', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(32, 1009, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001009', NULL, NULL, '2024-06-03', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(33, 1010, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001010', NULL, NULL, '2024-06-26', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(34, 1011, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001011', NULL, NULL, '2024-06-07', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(35, 1012, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001012', NULL, NULL, '2024-06-13', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(36, 1013, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001013', NULL, NULL, '2024-06-13', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(37, 1014, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001014', NULL, NULL, '2024-06-16', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(38, 1015, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001015', NULL, NULL, '2024-06-04', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(39, 1016, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001016', NULL, NULL, '2024-06-08', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(40, 1017, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001017', NULL, NULL, '2024-06-17', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(41, 1018, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001018', NULL, NULL, '2024-06-24', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(42, 1019, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001019', NULL, NULL, '2024-06-07', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(43, 1020, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001020', NULL, NULL, '2024-06-05', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(44, 1021, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001021', NULL, NULL, '2024-06-22', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(45, 1022, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001022', NULL, NULL, '2024-06-21', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(46, 1023, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001023', NULL, NULL, '2024-06-12', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(47, 1024, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001024', NULL, NULL, '2024-06-09', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(48, 1025, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001025', NULL, NULL, '2024-06-22', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(49, 1026, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001026', NULL, NULL, '2024-06-13', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(50, 1027, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001027', NULL, NULL, '2024-06-24', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(51, 1028, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001028', NULL, NULL, '2024-06-11', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(52, 1029, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001029', NULL, NULL, '2024-06-28', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(53, 1030, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001030', NULL, NULL, '2024-06-05', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(54, 1031, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001031', NULL, NULL, '2024-06-17', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(55, 1032, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001032', NULL, NULL, '2024-06-27', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(56, 1033, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001033', NULL, NULL, '2024-06-19', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(57, 1034, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001034', NULL, NULL, '2024-06-22', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(58, 1035, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001035', NULL, NULL, '2024-06-17', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(59, 1036, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001036', NULL, NULL, '2024-06-16', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(60, 1037, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001037', NULL, NULL, '2024-06-27', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(61, 1038, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001038', NULL, NULL, '2024-06-14', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(62, 1039, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001039', NULL, NULL, '2024-06-28', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(159, 1136, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001136', NULL, NULL, '2025-06-17', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(160, 1137, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001137', NULL, NULL, '2025-06-19', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(161, 1138, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001138', NULL, NULL, '2025-06-05', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(162, 1139, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001139', NULL, NULL, '2025-06-19', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(163, 1140, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001140', NULL, NULL, '2025-06-19', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(164, 1141, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001141', NULL, NULL, '2025-06-14', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(165, 1142, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001142', NULL, NULL, '2025-06-09', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(166, 1143, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001143', NULL, NULL, '2025-06-10', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(167, 1144, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001144', NULL, NULL, '2025-06-28', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(168, 1145, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001145', NULL, NULL, '2025-06-12', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(169, 1146, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001146', NULL, NULL, '2025-06-19', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(170, 1147, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001147', NULL, NULL, '2025-06-15', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(171, 1148, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001148', NULL, NULL, '2025-06-23', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(172, 1149, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001149', NULL, NULL, '2025-06-11', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(173, 1150, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001150', NULL, NULL, '2025-06-09', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(174, 1151, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001151', NULL, NULL, '2025-06-17', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(175, 1152, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001152', NULL, NULL, '2025-06-03', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(176, 1153, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001153', NULL, NULL, '2025-06-17', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(177, 1154, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001154', NULL, NULL, '2025-06-11', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(178, 1155, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001155', NULL, NULL, '2025-06-12', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(179, 1156, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001156', NULL, NULL, '2025-06-17', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(180, 1157, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001157', NULL, NULL, '2025-06-28', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(181, 1158, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001158', NULL, NULL, '2025-06-12', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(182, 1159, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001159', NULL, NULL, '2025-06-09', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(183, 1160, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001160', NULL, NULL, '2025-06-13', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(184, 1161, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001161', NULL, NULL, '2025-06-13', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(185, 1162, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001162', NULL, NULL, '2025-06-04', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(186, 1163, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001163', NULL, NULL, '2025-06-21', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(187, 1164, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001164', NULL, NULL, '2025-06-06', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(188, 1165, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001165', NULL, NULL, '2025-06-05', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(189, 1166, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001166', NULL, NULL, '2025-06-22', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(190, 1167, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001167', NULL, NULL, '2025-06-08', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(191, 1168, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001168', NULL, NULL, '2025-06-08', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(192, 1169, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001169', NULL, NULL, '2025-06-03', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(193, 1170, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001170', NULL, NULL, '2025-06-11', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(194, 1171, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001171', NULL, NULL, '2025-06-17', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(195, 1172, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001172', NULL, NULL, '2025-06-17', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(196, 1173, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001173', NULL, NULL, '2025-06-15', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(197, 1174, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001174', NULL, NULL, '2025-06-16', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(302, 1279, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001279', NULL, NULL, '2026-06-20', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(303, 1280, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001280', NULL, NULL, '2026-06-16', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(304, 1283, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001283', NULL, NULL, '2026-06-27', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(305, 1284, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001284', NULL, NULL, '2026-06-14', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(306, 1286, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001286', NULL, NULL, '2026-06-11', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(307, 1287, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001287', NULL, NULL, '2026-06-17', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(308, 1288, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001288', NULL, NULL, '2026-06-18', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(309, 1289, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001289', NULL, NULL, '2026-06-24', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(310, 1290, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001290', NULL, NULL, '2026-06-05', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(311, 1294, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001294', NULL, NULL, '2026-06-07', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(312, 1297, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001297', NULL, NULL, '2026-06-22', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(313, 1298, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001298', NULL, NULL, '2026-06-09', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(314, 1299, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001299', NULL, NULL, '2026-06-03', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(315, 1300, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001300', NULL, NULL, '2026-06-05', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(316, 1302, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001302', NULL, NULL, '2026-06-24', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(317, 1303, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001303', NULL, NULL, '2026-06-03', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(318, 1304, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001304', NULL, NULL, '2026-06-23', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(319, 1305, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001305', NULL, NULL, '2026-06-10', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(320, 1306, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001306', NULL, NULL, '2026-06-10', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(321, 1307, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001307', NULL, NULL, '2026-06-21', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(322, 1309, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001309', NULL, NULL, '2026-06-07', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(323, 1310, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001310', NULL, NULL, '2026-06-15', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(324, 1312, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001312', NULL, NULL, '2026-06-09', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(325, 1313, 'Registration', 1000.00, 'Cash', 'Approved', 'OR-2026-001313', NULL, NULL, '2026-06-13', NULL, 'School Cashier', 'Registration fee - unlocks enrollment'),
(527, 4, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-500004', NULL, NULL, '2026-09-10', NULL, 'School Cashier', 'First tuition instalment'),
(528, 5, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-500005', NULL, NULL, '2026-09-10', NULL, 'School Cashier', 'First tuition instalment'),
(529, 6, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-500006', NULL, NULL, '2026-09-10', NULL, 'School Cashier', 'First tuition instalment'),
(530, 7, 'Tuition', 3125.00, 'Cash', 'Approved', 'OR-2026-500007', NULL, NULL, '2026-09-10', NULL, 'School Cashier', 'First tuition instalment'),
(531, 16, 'Tuition', 3125.00, 'Cash', 'Approved', 'OR-2026-500016', NULL, NULL, '2026-09-10', NULL, 'School Cashier', 'First tuition instalment'),
(532, 1000, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501000', NULL, NULL, '2024-06-04', NULL, 'School Cashier', 'First tuition instalment'),
(533, 1001, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501001', NULL, NULL, '2024-06-26', NULL, 'School Cashier', 'First tuition instalment'),
(534, 1002, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501002', NULL, NULL, '2024-06-11', NULL, 'School Cashier', 'First tuition instalment'),
(535, 1003, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501003', NULL, NULL, '2024-06-25', NULL, 'School Cashier', 'First tuition instalment'),
(536, 1004, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501004', NULL, NULL, '2024-06-07', NULL, 'School Cashier', 'First tuition instalment'),
(537, 1005, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501005', NULL, NULL, '2024-06-13', NULL, 'School Cashier', 'First tuition instalment'),
(538, 1006, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501006', NULL, NULL, '2024-06-23', NULL, 'School Cashier', 'First tuition instalment'),
(539, 1007, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501007', NULL, NULL, '2024-06-19', NULL, 'School Cashier', 'First tuition instalment'),
(540, 1008, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501008', NULL, NULL, '2024-06-08', NULL, 'School Cashier', 'First tuition instalment'),
(541, 1009, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501009', NULL, NULL, '2024-06-03', NULL, 'School Cashier', 'First tuition instalment'),
(542, 1010, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501010', NULL, NULL, '2024-06-26', NULL, 'School Cashier', 'First tuition instalment'),
(543, 1011, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501011', NULL, NULL, '2024-06-07', NULL, 'School Cashier', 'First tuition instalment'),
(544, 1012, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501012', NULL, NULL, '2024-06-13', NULL, 'School Cashier', 'First tuition instalment'),
(545, 1013, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501013', NULL, NULL, '2024-06-13', NULL, 'School Cashier', 'First tuition instalment'),
(546, 1014, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501014', NULL, NULL, '2024-06-16', NULL, 'School Cashier', 'First tuition instalment'),
(547, 1015, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501015', NULL, NULL, '2024-06-04', NULL, 'School Cashier', 'First tuition instalment'),
(548, 1016, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501016', NULL, NULL, '2024-06-08', NULL, 'School Cashier', 'First tuition instalment'),
(549, 1017, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501017', NULL, NULL, '2024-06-17', NULL, 'School Cashier', 'First tuition instalment'),
(550, 1018, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501018', NULL, NULL, '2024-06-24', NULL, 'School Cashier', 'First tuition instalment'),
(551, 1019, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501019', NULL, NULL, '2024-06-07', NULL, 'School Cashier', 'First tuition instalment'),
(552, 1020, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501020', NULL, NULL, '2024-06-05', NULL, 'School Cashier', 'First tuition instalment'),
(553, 1021, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501021', NULL, NULL, '2024-06-22', NULL, 'School Cashier', 'First tuition instalment'),
(554, 1022, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501022', NULL, NULL, '2024-06-21', NULL, 'School Cashier', 'First tuition instalment'),
(555, 1023, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501023', NULL, NULL, '2024-06-12', NULL, 'School Cashier', 'First tuition instalment'),
(556, 1024, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501024', NULL, NULL, '2024-06-09', NULL, 'School Cashier', 'First tuition instalment'),
(557, 1025, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501025', NULL, NULL, '2024-06-22', NULL, 'School Cashier', 'First tuition instalment'),
(558, 1026, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501026', NULL, NULL, '2024-06-13', NULL, 'School Cashier', 'First tuition instalment'),
(559, 1027, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501027', NULL, NULL, '2024-06-24', NULL, 'School Cashier', 'First tuition instalment'),
(560, 1028, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501028', NULL, NULL, '2024-06-11', NULL, 'School Cashier', 'First tuition instalment'),
(561, 1029, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501029', NULL, NULL, '2024-06-28', NULL, 'School Cashier', 'First tuition instalment'),
(562, 1030, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501030', NULL, NULL, '2024-06-05', NULL, 'School Cashier', 'First tuition instalment'),
(563, 1031, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501031', NULL, NULL, '2024-06-17', NULL, 'School Cashier', 'First tuition instalment'),
(564, 1032, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501032', NULL, NULL, '2024-06-27', NULL, 'School Cashier', 'First tuition instalment'),
(565, 1033, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501033', NULL, NULL, '2024-06-19', NULL, 'School Cashier', 'First tuition instalment'),
(566, 1034, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501034', NULL, NULL, '2024-06-22', NULL, 'School Cashier', 'First tuition instalment'),
(567, 1035, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501035', NULL, NULL, '2024-06-17', NULL, 'School Cashier', 'First tuition instalment'),
(568, 1036, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501036', NULL, NULL, '2024-06-16', NULL, 'School Cashier', 'First tuition instalment'),
(569, 1037, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501037', NULL, NULL, '2024-06-27', NULL, 'School Cashier', 'First tuition instalment'),
(570, 1038, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501038', NULL, NULL, '2024-06-14', NULL, 'School Cashier', 'First tuition instalment'),
(571, 1039, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501039', NULL, NULL, '2024-06-28', NULL, 'School Cashier', 'First tuition instalment'),
(668, 1136, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501136', NULL, NULL, '2025-06-17', NULL, 'School Cashier', 'First tuition instalment'),
(669, 1137, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501137', NULL, NULL, '2025-06-19', NULL, 'School Cashier', 'First tuition instalment'),
(670, 1138, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501138', NULL, NULL, '2025-06-05', NULL, 'School Cashier', 'First tuition instalment'),
(671, 1139, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501139', NULL, NULL, '2025-06-19', NULL, 'School Cashier', 'First tuition instalment'),
(672, 1140, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501140', NULL, NULL, '2025-06-19', NULL, 'School Cashier', 'First tuition instalment'),
(673, 1141, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501141', NULL, NULL, '2025-06-14', NULL, 'School Cashier', 'First tuition instalment'),
(674, 1142, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501142', NULL, NULL, '2025-06-09', NULL, 'School Cashier', 'First tuition instalment'),
(675, 1143, 'Tuition', 2250.00, 'Cash', 'Approved', 'OR-2026-501143', NULL, NULL, '2025-06-10', NULL, 'School Cashier', 'First tuition instalment'),
(676, 1144, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501144', NULL, NULL, '2025-06-28', NULL, 'School Cashier', 'First tuition instalment'),
(677, 1145, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501145', NULL, NULL, '2025-06-12', NULL, 'School Cashier', 'First tuition instalment'),
(678, 1146, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501146', NULL, NULL, '2025-06-19', NULL, 'School Cashier', 'First tuition instalment'),
(679, 1147, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501147', NULL, NULL, '2025-06-15', NULL, 'School Cashier', 'First tuition instalment'),
(680, 1148, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501148', NULL, NULL, '2025-06-23', NULL, 'School Cashier', 'First tuition instalment'),
(681, 1149, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501149', NULL, NULL, '2025-06-11', NULL, 'School Cashier', 'First tuition instalment'),
(682, 1150, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501150', NULL, NULL, '2025-06-09', NULL, 'School Cashier', 'First tuition instalment'),
(683, 1151, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501151', NULL, NULL, '2025-06-17', NULL, 'School Cashier', 'First tuition instalment'),
(684, 1152, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501152', NULL, NULL, '2025-06-03', NULL, 'School Cashier', 'First tuition instalment'),
(685, 1153, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501153', NULL, NULL, '2025-06-17', NULL, 'School Cashier', 'First tuition instalment'),
(686, 1154, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501154', NULL, NULL, '2025-06-11', NULL, 'School Cashier', 'First tuition instalment'),
(687, 1155, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501155', NULL, NULL, '2025-06-12', NULL, 'School Cashier', 'First tuition instalment'),
(688, 1156, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501156', NULL, NULL, '2025-06-17', NULL, 'School Cashier', 'First tuition instalment'),
(689, 1157, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501157', NULL, NULL, '2025-06-28', NULL, 'School Cashier', 'First tuition instalment'),
(690, 1158, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501158', NULL, NULL, '2025-06-12', NULL, 'School Cashier', 'First tuition instalment'),
(691, 1159, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501159', NULL, NULL, '2025-06-09', NULL, 'School Cashier', 'First tuition instalment'),
(692, 1160, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501160', NULL, NULL, '2025-06-13', NULL, 'School Cashier', 'First tuition instalment'),
(693, 1161, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501161', NULL, NULL, '2025-06-13', NULL, 'School Cashier', 'First tuition instalment'),
(694, 1162, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501162', NULL, NULL, '2025-06-04', NULL, 'School Cashier', 'First tuition instalment'),
(695, 1163, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501163', NULL, NULL, '2025-06-21', NULL, 'School Cashier', 'First tuition instalment'),
(696, 1164, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501164', NULL, NULL, '2025-06-06', NULL, 'School Cashier', 'First tuition instalment'),
(697, 1165, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501165', NULL, NULL, '2025-06-05', NULL, 'School Cashier', 'First tuition instalment'),
(698, 1166, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501166', NULL, NULL, '2025-06-22', NULL, 'School Cashier', 'First tuition instalment'),
(699, 1167, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501167', NULL, NULL, '2025-06-08', NULL, 'School Cashier', 'First tuition instalment'),
(700, 1168, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501168', NULL, NULL, '2025-06-08', NULL, 'School Cashier', 'First tuition instalment'),
(701, 1169, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501169', NULL, NULL, '2025-06-03', NULL, 'School Cashier', 'First tuition instalment'),
(702, 1170, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501170', NULL, NULL, '2025-06-11', NULL, 'School Cashier', 'First tuition instalment'),
(703, 1171, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501171', NULL, NULL, '2025-06-17', NULL, 'School Cashier', 'First tuition instalment'),
(704, 1172, 'Tuition', 3125.00, 'Cash', 'Approved', 'OR-2026-501172', NULL, NULL, '2025-06-17', NULL, 'School Cashier', 'First tuition instalment'),
(705, 1173, 'Tuition', 3125.00, 'Cash', 'Approved', 'OR-2026-501173', NULL, NULL, '2025-06-15', NULL, 'School Cashier', 'First tuition instalment'),
(706, 1174, 'Tuition', 3125.00, 'Cash', 'Approved', 'OR-2026-501174', NULL, NULL, '2025-06-16', NULL, 'School Cashier', 'First tuition instalment'),
(811, 1279, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501279', NULL, NULL, '2026-06-20', NULL, 'School Cashier', 'First tuition instalment'),
(812, 1280, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501280', NULL, NULL, '2026-06-16', NULL, 'School Cashier', 'First tuition instalment'),
(813, 1283, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501283', NULL, NULL, '2026-06-27', NULL, 'School Cashier', 'First tuition instalment'),
(814, 1284, 'Tuition', 2625.00, 'Cash', 'Approved', 'OR-2026-501284', NULL, NULL, '2026-06-14', NULL, 'School Cashier', 'First tuition instalment'),
(815, 1286, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501286', NULL, NULL, '2026-06-11', NULL, 'School Cashier', 'First tuition instalment'),
(816, 1287, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501287', NULL, NULL, '2026-06-17', NULL, 'School Cashier', 'First tuition instalment'),
(817, 1288, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501288', NULL, NULL, '2026-06-18', NULL, 'School Cashier', 'First tuition instalment'),
(818, 1289, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501289', NULL, NULL, '2026-06-24', NULL, 'School Cashier', 'First tuition instalment'),
(819, 1290, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501290', NULL, NULL, '2026-06-05', NULL, 'School Cashier', 'First tuition instalment'),
(820, 1294, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501294', NULL, NULL, '2026-06-07', NULL, 'School Cashier', 'First tuition instalment'),
(821, 1297, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501297', NULL, NULL, '2026-06-22', NULL, 'School Cashier', 'First tuition instalment'),
(822, 1298, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501298', NULL, NULL, '2026-06-09', NULL, 'School Cashier', 'First tuition instalment'),
(823, 1299, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501299', NULL, NULL, '2026-06-03', NULL, 'School Cashier', 'First tuition instalment'),
(824, 1302, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501302', NULL, NULL, '2026-06-24', NULL, 'School Cashier', 'First tuition instalment'),
(825, 1303, 'Tuition', 3000.00, 'Cash', 'Approved', 'OR-2026-501303', NULL, NULL, '2026-06-03', NULL, 'School Cashier', 'First tuition instalment'),
(826, 1304, 'Tuition', 3125.00, 'Cash', 'Approved', 'OR-2026-501304', NULL, NULL, '2026-06-23', NULL, 'School Cashier', 'First tuition instalment'),
(827, 1305, 'Tuition', 3125.00, 'Cash', 'Approved', 'OR-2026-501305', NULL, NULL, '2026-06-10', NULL, 'School Cashier', 'First tuition instalment'),
(828, 1306, 'Tuition', 3125.00, 'Cash', 'Approved', 'OR-2026-501306', NULL, NULL, '2026-06-10', NULL, 'School Cashier', 'First tuition instalment'),
(829, 1307, 'Tuition', 3125.00, 'Cash', 'Approved', 'OR-2026-501307', NULL, NULL, '2026-06-21', NULL, 'School Cashier', 'First tuition instalment'),
(830, 1309, 'Tuition', 3125.00, 'Cash', 'Approved', 'OR-2026-501309', NULL, NULL, '2026-06-07', NULL, 'School Cashier', 'First tuition instalment'),
(831, 1310, 'Tuition', 3125.00, 'Cash', 'Approved', 'OR-2026-501310', NULL, NULL, '2026-06-15', NULL, 'School Cashier', 'First tuition instalment'),
(832, 1312, 'Tuition', 3125.00, 'Cash', 'Approved', 'OR-2026-501312', NULL, NULL, '2026-06-09', NULL, 'School Cashier', 'First tuition instalment'),
(894, 1313, 'Enrollment Fee', 1000.00, 'Cash', 'Approved', '4534535345', NULL, NULL, '2026-09-14', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblprovinces`
--

CREATE TABLE `tblprovinces` (
  `PROV_PSGC` bigint(20) NOT NULL,
  `PROVINCE` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblprovinces`
--

INSERT INTO `tblprovinces` (`PROV_PSGC`, `PROVINCE`) VALUES
(1400100000, 'Abra'),
(1600200000, 'Agusan del Norte'),
(1600300000, 'Agusan del Sur'),
(600400000, 'Aklan'),
(500500000, 'Albay'),
(600600000, 'Antique'),
(1408100000, 'Apayao'),
(307700000, 'Aurora'),
(1900700000, 'Basilan'),
(300800000, 'Bataan'),
(200900000, 'Batanes'),
(401000000, 'Batangas'),
(1401100000, 'Benguet'),
(807800000, 'Biliran'),
(701200000, 'Bohol'),
(1001300000, 'Bukidnon'),
(301400000, 'Bulacan'),
(201500000, 'Cagayan'),
(501600000, 'Camarines Norte'),
(501700000, 'Camarines Sur'),
(1001800000, 'Camiguin'),
(601900000, 'Capiz'),
(502000000, 'Catanduanes'),
(402100000, 'Cavite'),
(702200000, 'Cebu'),
(990100000, 'City of Isabela (Not a Province)'),
(1204700000, 'Cotabato'),
(1108200000, 'Davao de Oro'),
(1102300000, 'Davao del Norte'),
(1102400000, 'Davao del Sur'),
(1108600000, 'Davao Occidental'),
(1102500000, 'Davao Oriental'),
(1608500000, 'Dinagat Islands'),
(802600000, 'Eastern Samar'),
(607900000, 'Guimaras'),
(1402700000, 'Ifugao'),
(102800000, 'Ilocos Norte'),
(102900000, 'Ilocos Sur'),
(603000000, 'Iloilo'),
(203100000, 'Isabela'),
(1403200000, 'Kalinga'),
(103300000, 'La Union'),
(403400000, 'Laguna'),
(1003500000, 'Lanao del Norte'),
(1903600000, 'Lanao del Sur'),
(803700000, 'Leyte'),
(1908700000, 'Maguindanao del Norte'),
(1908800000, 'Maguindanao del Sur'),
(1704000000, 'Marinduque'),
(504100000, 'Masbate'),
(1004200000, 'Misamis Occidental'),
(1004300000, 'Misamis Oriental'),
(1404400000, 'Mountain Province'),
(1303900000, 'NCR, City of Manila, First District (Not a Province)'),
(1307600000, 'NCR, Fourth District (Not a Province)'),
(1307400000, 'NCR, Second District (Not a Province)'),
(1307500000, 'NCR, Third District (Not a Province)'),
(604500000, 'Negros Occidental'),
(704600000, 'Negros Oriental'),
(804800000, 'Northern Samar'),
(304900000, 'Nueva Ecija'),
(205000000, 'Nueva Vizcaya'),
(1705100000, 'Occidental Mindoro'),
(1705200000, 'Oriental Mindoro'),
(1705300000, 'Palawan'),
(305400000, 'Pampanga'),
(105500000, 'Pangasinan'),
(405600000, 'Quezon'),
(205700000, 'Quirino'),
(405800000, 'Rizal'),
(1705900000, 'Romblon'),
(806000000, 'Samar'),
(1208000000, 'Sarangani'),
(706100000, 'Siquijor'),
(506200000, 'Sorsogon'),
(1206300000, 'South Cotabato'),
(806400000, 'Southern Leyte'),
(1909900000, 'Special Geographic Area'),
(1206500000, 'Sultan Kudarat'),
(1906600000, 'Sulu'),
(1606700000, 'Surigao del Norte'),
(1606800000, 'Surigao del Sur'),
(306900000, 'Tarlac'),
(1907000000, 'Tawi-Tawi'),
(307100000, 'Zambales'),
(907200000, 'Zamboanga del Norte'),
(907300000, 'Zamboanga del Sur'),
(908300000, 'Zamboanga Sibugay');

-- --------------------------------------------------------

--
-- Table structure for table `tblschoolyear`
--

CREATE TABLE `tblschoolyear` (
  `SY_ID` int(11) NOT NULL,
  `SCHOOL_YEAR` varchar(20) NOT NULL,
  `STATUS` varchar(20) NOT NULL DEFAULT 'Inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tblschoolyear`
--

INSERT INTO `tblschoolyear` (`SY_ID`, `SCHOOL_YEAR`, `STATUS`) VALUES
(1, '2025-2026', 'Inactive'),
(4, '2024-2025', 'Inactive'),
(5, '2026-2027', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `tblsections`
--

CREATE TABLE `tblsections` (
  `SECTION_ID` int(11) NOT NULL,
  `SECTION_NAME` varchar(50) NOT NULL,
  `COURSE_ID` int(11) NOT NULL,
  `SY_ID` int(11) NOT NULL,
  `YEAR_LEVEL` varchar(20) NOT NULL,
  `PROGRAM_HEAD` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tblsections`
--

INSERT INTO `tblsections` (`SECTION_ID`, `SECTION_NAME`, `COURSE_ID`, `SY_ID`, `YEAR_LEVEL`, `PROGRAM_HEAD`) VALUES
(13, 'Sampaguita', 10, 5, 'Kindergarten', NULL),
(14, 'Rosal', 11, 5, 'Grade 1', NULL),
(15, 'Ilang-Ilang', 12, 5, 'Grade 2', NULL),
(16, 'Camia', 13, 5, 'Grade 3', NULL),
(17, 'Dahlia', 14, 5, 'Grade 4', NULL),
(18, 'Jasmin', 15, 5, 'Grade 5', NULL),
(19, 'Orchid', 16, 5, 'Grade 6', NULL),
(20, 'St. Peter', 17, 5, 'Grade 7', NULL),
(21, 'St. Paul', 18, 5, 'Grade 8', NULL),
(22, 'St. John', 19, 5, 'Grade 9', NULL),
(23, 'St. Luke', 20, 5, 'Grade 10', NULL),
(200, 'Sampaguita', 8, 4, 'Nursery 1', NULL),
(201, 'Rosal', 9, 4, 'Nursery 2', NULL),
(202, 'Ilang-Ilang', 10, 4, 'Kindergarten', NULL),
(203, 'Camia', 11, 4, 'Grade 1', NULL),
(204, 'Dahlia', 12, 4, 'Grade 2', NULL),
(214, 'Rosal', 9, 1, 'Nursery 2', NULL),
(215, 'Ilang-Ilang', 10, 1, 'Kindergarten', NULL),
(216, 'Camia', 11, 1, 'Grade 1', NULL),
(217, 'Dahlia', 12, 1, 'Grade 2', NULL),
(218, 'Jasmin', 13, 1, 'Grade 3', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblsettings`
--

CREATE TABLE `tblsettings` (
  `SETTING_KEY` varchar(50) NOT NULL,
  `SETTING_VALUE` text DEFAULT NULL,
  `DESCRIPTION` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblsettings`
--

INSERT INTO `tblsettings` (`SETTING_KEY`, `SETTING_VALUE`, `DESCRIPTION`) VALUES
('ACCENT_COLOR', '#128A52', 'Emerald green brand color'),
('ENTRANCE_EXAM_APPLIES_TO', 'Incoming First Year', 'Applicant types required to take/pay the entrance exam'),
('PRIMARY_COLOR', '#0B5E39', 'Dark green brand color'),
('SCHOOL_NAME', 'St. Joseph Catholic School of Sagay Inc.', 'Displayed sitewide');

-- --------------------------------------------------------

--
-- Table structure for table `tblstudent`
--

CREATE TABLE `tblstudent` (
  `S_ID` int(11) NOT NULL,
  `IDNO` varchar(20) NOT NULL,
  `FNAME` varchar(40) NOT NULL,
  `LNAME` varchar(40) NOT NULL,
  `MNAME` varchar(40) NOT NULL,
  `SUFFIX` varchar(10) DEFAULT NULL,
  `SEX` varchar(10) NOT NULL DEFAULT 'Male',
  `CIVIL_STATUS` varchar(20) DEFAULT NULL,
  `BDAY` date DEFAULT NULL,
  `BPLACE` text DEFAULT NULL,
  `STATUS` varchar(30) NOT NULL DEFAULT 'Active',
  `AGE` int(11) DEFAULT NULL,
  `NATIONALITY` varchar(40) DEFAULT NULL,
  `RELIGION` varchar(255) DEFAULT NULL,
  `CONTACT_NO` varchar(40) DEFAULT NULL,
  `HOME_ADD` text DEFAULT NULL,
  `PROVINCE` varchar(80) DEFAULT NULL,
  `CITY_MUN` varchar(80) DEFAULT NULL,
  `BRGY` varchar(80) DEFAULT NULL,
  `EMAIL` varchar(150) DEFAULT NULL,
  `ACC_PASSWORD` text DEFAULT NULL,
  `LRNNO` varchar(15) DEFAULT NULL,
  `CONTACTPERSON` varchar(150) DEFAULT NULL,
  `COMPANYIDNO` int(11) DEFAULT NULL,
  `COURSE_ID` int(11) DEFAULT NULL,
  `MAJOR_ID` int(11) DEFAULT NULL,
  `APPLICANT_ID` int(11) DEFAULT NULL,
  `AddedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblstudent`
--

INSERT INTO `tblstudent` (`S_ID`, `IDNO`, `FNAME`, `LNAME`, `MNAME`, `SUFFIX`, `SEX`, `CIVIL_STATUS`, `BDAY`, `BPLACE`, `STATUS`, `AGE`, `NATIONALITY`, `RELIGION`, `CONTACT_NO`, `HOME_ADD`, `PROVINCE`, `CITY_MUN`, `BRGY`, `EMAIL`, `ACC_PASSWORD`, `LRNNO`, `CONTACTPERSON`, `COMPANYIDNO`, `COURSE_ID`, `MAJOR_ID`, `APPLICANT_ID`, `AddedBy`) VALUES
(7, '2026-0001', 'Maria Clara', 'Alvarez', 'Reyes', NULL, 'Female', 'Single', '2021-02-19', 'Sagay City, Negros Occidental', 'Active', 5, 'Filipino', 'Roman Catholic', '09171234567', 'Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'maria.alvarez@sjcssagay.edu.ph', NULL, '100000000259', 'Parent/Guardian of Maria Clara Alvarez', NULL, 10, NULL, NULL, NULL),
(8, '2026-0002', 'Juan Miguel', 'Bacolod', 'Santos', NULL, 'Male', 'Single', '2020-02-26', 'Sagay City, Negros Occidental', 'Active', 6, 'Filipino', 'Roman Catholic', '09171234567', 'Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'juan.bacolod@sjcssagay.edu.ph', NULL, '100000000296', 'Parent/Guardian of Juan Miguel Bacolod', NULL, 11, NULL, NULL, NULL),
(9, '2026-0003', 'Angel Grace', 'Cordero', 'Lim', NULL, 'Female', 'Single', '2019-03-05', 'Sagay City, Negros Occidental', 'Active', 7, 'Filipino', 'Roman Catholic', '09171234567', 'Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'angel.cordero@sjcssagay.edu.ph', NULL, '100000000333', 'Parent/Guardian of Angel Grace Cordero', NULL, 12, NULL, NULL, NULL),
(10, '2026-0004', 'Jose Rizal', 'Dela Cruz', 'Cruz', NULL, 'Male', 'Single', '2018-03-12', 'Sagay City, Negros Occidental', 'Active', 8, 'Filipino', 'Roman Catholic', '09171234567', 'Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'jose.delacruz@sjcssagay.edu.ph', NULL, '100000000370', 'Parent/Guardian of Jose Rizal Dela Cruz', NULL, 13, NULL, NULL, NULL),
(11, '2026-0005', 'Bea Nicole', 'Espinosa', 'Tan', NULL, 'Female', 'Single', '2017-03-19', 'Sagay City, Negros Occidental', 'Active', 9, 'Filipino', 'Roman Catholic', '09171234567', 'Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'bea.espinosa@sjcssagay.edu.ph', NULL, '100000000407', 'Parent/Guardian of Bea Nicole Espinosa', NULL, 14, NULL, NULL, NULL),
(12, '2026-0006', 'Mark Anthony', 'Fernandez', 'Uy', NULL, 'Male', 'Single', '2016-03-25', 'Sagay City, Negros Occidental', 'Active', 10, 'Filipino', 'Roman Catholic', '09171234567', 'Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'mark.fernandez@sjcssagay.edu.ph', NULL, '100000000444', 'Parent/Guardian of Mark Anthony Fernandez', NULL, 15, NULL, NULL, NULL),
(13, '2026-0007', 'Sofia Isabel', 'Gonzales', 'Ong', NULL, 'Female', 'Single', '2015-04-02', 'Sagay City, Negros Occidental', 'Active', 11, 'Filipino', 'Roman Catholic', '09171234567', 'Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'sofia.gonzales@sjcssagay.edu.ph', NULL, '100000000481', 'Parent/Guardian of Sofia Isabel Gonzales', NULL, 16, NULL, NULL, NULL),
(14, '2026-0008', 'Paolo Luis', 'Hernandez', 'Sy', NULL, 'Male', 'Single', '2014-04-09', 'Sagay City, Negros Occidental', 'Active', 12, 'Filipino', 'Roman Catholic', '09171234567', 'Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'paolo.hernandez@sjcssagay.edu.ph', NULL, '100000000518', 'Parent/Guardian of Paolo Luis Hernandez', NULL, 17, NULL, NULL, NULL),
(15, '2026-0009', 'Kyla Marie', 'Ignacio', 'Chua', NULL, 'Female', 'Single', '2013-04-16', 'Sagay City, Negros Occidental', 'Active', 13, 'Filipino', 'Roman Catholic', '09171234567', 'Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'kyla.ignacio@sjcssagay.edu.ph', NULL, '100000000555', 'Parent/Guardian of Kyla Marie Ignacio', NULL, 18, NULL, NULL, NULL),
(16, '2026-0010', 'Carlo Emmanuel', 'Jimenez', 'Go', NULL, 'Male', 'Single', '2012-04-22', 'Sagay City, Negros Occidental', 'Active', 14, 'Filipino', 'Roman Catholic', '09171234567', 'Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'carlo.jimenez@sjcssagay.edu.ph', NULL, '100000000592', 'Parent/Guardian of Carlo Emmanuel Jimenez', NULL, 19, NULL, NULL, NULL),
(17, '2026-0011', 'Andrea Nicole', 'Katigbak', 'Lee', NULL, 'Female', 'Single', '2011-04-30', 'Sagay City, Negros Occidental', 'Active', 15, 'Filipino', 'Roman Catholic', '09171234567', 'Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'andrea.katigbak@sjcssagay.edu.ph', NULL, '100000000629', 'Parent/Guardian of Andrea Nicole Katigbak', NULL, 20, NULL, NULL, NULL),
(18, '2026-0012', 'Gabriel John', 'Lorenzo', 'Pua', NULL, 'Male', 'Single', '2014-05-07', 'Sagay City, Negros Occidental', 'Active', 12, 'Filipino', 'Roman Catholic', '09171234567', 'Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'gabriel.lorenzo@sjcssagay.edu.ph', NULL, '100000000666', 'Parent/Guardian of Gabriel John Lorenzo', NULL, 17, NULL, NULL, NULL),
(19, '2026-0013', 'Trisha Mae', 'Mendoza', 'Yap', NULL, 'Female', 'Single', '2017-05-14', 'Sagay City, Negros Occidental', 'Active', 9, 'Filipino', 'Roman Catholic', '09171234567', 'Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'trisha.mendoza@sjcssagay.edu.ph', NULL, '100000000703', 'Parent/Guardian of Trisha Mae Mendoza', NULL, 14, NULL, NULL, NULL),
(20, '2026-0014', 'Christian Dave', 'Navarro', 'Ang', NULL, 'Male', 'Single', '2019-05-21', 'Sagay City, Negros Occidental', 'Active', 7, 'Filipino', 'Roman Catholic', '09171234567', 'Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'christian.navarro@sjcssagay.edu.ph', NULL, '100000000740', 'Parent/Guardian of Christian Dave Navarro', NULL, 12, NULL, NULL, NULL),
(1000, '2024-0101', 'Bea Nicole', 'Katigbak', 'Salas', NULL, 'Female', 'Single', '2023-06-10', 'Sagay City, Negros Occidental', 'Active', 3, 'Filipino', 'Roman Catholic', '09546347738', 'Brgy. Rizal, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'bea.katigbak@sjcssagay.edu.ph', NULL, '100000037000', 'Parent/Guardian of Bea Nicole Katigbak', NULL, 8, NULL, NULL, NULL),
(1001, '2024-0102', 'Maria Clara', 'Fernandez', 'Lim', NULL, 'Female', 'Single', '2023-06-17', 'Sagay City, Negros Occidental', 'Active', 3, 'Filipino', 'Roman Catholic', '09241473664', 'Brgy. Old Sagay, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'maria.fernandez@sjcssagay.edu.ph', NULL, '100000037037', 'Parent/Guardian of Maria Clara Fernandez', NULL, 8, NULL, NULL, NULL),
(1002, '2024-0103', 'Shaira', 'Yulo', 'Dizon', NULL, 'Female', 'Single', '2023-06-24', 'Sagay City, Negros Occidental', 'Active', 3, 'Filipino', 'Roman Catholic', '09130549404', 'Roxas City, Capiz', 'Capiz', NULL, NULL, 'shaira.yulo@sjcssagay.edu.ph', NULL, '100000037074', 'Parent/Guardian of Shaira Yulo', NULL, 8, NULL, NULL, NULL),
(1003, '2024-0104', 'Jelyn', 'Jimenez', 'Salas', NULL, 'Female', 'Single', '2023-07-01', 'Sagay City, Negros Occidental', 'Active', 3, 'Filipino', 'Roman Catholic', '09762053907', 'Brgy. Bulanon, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'jelyn.jimenez@sjcssagay.edu.ph', NULL, '100000037111', 'Parent/Guardian of Jelyn Jimenez', NULL, 8, NULL, NULL, NULL),
(1004, '2024-0105', 'Ma. Cristina', 'Navarro', 'Bautista', NULL, 'Female', 'Single', '2023-07-08', 'Sagay City, Negros Occidental', 'Active', 3, 'Filipino', 'Roman Catholic', '09519042563', 'Brgy. Malubon, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'ma.navarro@sjcssagay.edu.ph', NULL, '100000037148', 'Parent/Guardian of Ma. Cristina Navarro', NULL, 8, NULL, NULL, NULL),
(1005, '2024-0106', 'Miguel Angelo', 'Salvador', 'Aguilar', NULL, 'Male', 'Single', '2023-07-15', 'Sagay City, Negros Occidental', 'Active', 3, 'Filipino', 'Roman Catholic', '09506894428', 'Brgy. Rizal, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'miguel.salvador@sjcssagay.edu.ph', NULL, '100000037185', 'Parent/Guardian of Miguel Angelo Salvador', NULL, 8, NULL, NULL, NULL),
(1006, '2024-0107', 'Jose Rafael', 'Zamora', 'Rivera', NULL, 'Male', 'Single', '2023-07-22', 'Sagay City, Negros Occidental', 'Active', 3, 'Filipino', 'Roman Catholic', '09444658915', 'Brgy. Taba-ao, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'jose.zamora@sjcssagay.edu.ph', NULL, '100000037222', 'Parent/Guardian of Jose Rafael Zamora', NULL, 8, NULL, NULL, NULL),
(1007, '2024-0108', 'Sofia Isabel', 'Jimenez', 'Bautista', NULL, 'Female', 'Single', '2023-07-29', 'Sagay City, Negros Occidental', 'Active', 3, 'Filipino', 'Roman Catholic', '09395401761', 'Bacolod City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'sofia.jimenez@sjcssagay.edu.ph', NULL, '100000037259', 'Parent/Guardian of Sofia Isabel Jimenez', NULL, 8, NULL, NULL, NULL),
(1008, '2024-0109', 'Shaira', 'Valdez', 'Aguilar', NULL, 'Female', 'Single', '2022-08-05', 'Sagay City, Negros Occidental', 'Active', 4, 'Filipino', 'Roman Catholic', '09298545812', 'Brgy. Molocaboc, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'shaira.valdez@sjcssagay.edu.ph', NULL, '100000037296', 'Parent/Guardian of Shaira Valdez', NULL, 9, NULL, NULL, NULL),
(1009, '2024-0110', 'Maria Clara', 'Zamora', 'Gomez', NULL, 'Female', 'Single', '2022-08-12', 'Sagay City, Negros Occidental', 'Active', 4, 'Filipino', 'Roman Catholic', '09653560853', 'Brgy. Vito, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'maria.zamora@sjcssagay.edu.ph', NULL, '100000037333', 'Parent/Guardian of Maria Clara Zamora', NULL, 9, NULL, NULL, NULL),
(1010, '2024-0111', 'Gabriel John', 'Uy', 'Santos', NULL, 'Male', 'Single', '2022-08-19', 'Sagay City, Negros Occidental', 'Active', 4, 'Filipino', 'Roman Catholic', '09630053344', 'Brgy. Malubon, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'gabriel.uy@sjcssagay.edu.ph', NULL, '100000037370', 'Parent/Guardian of Gabriel John Uy', NULL, 9, NULL, NULL, NULL),
(1011, '2024-0112', 'Joshua Emmanuel', 'Santos', 'Cruz', NULL, 'Male', 'Single', '2022-08-26', 'Sagay City, Negros Occidental', 'Active', 4, 'Filipino', 'Roman Catholic', '09125705505', 'Brgy. Old Sagay, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'joshua.santos@sjcssagay.edu.ph', NULL, '100000037407', 'Parent/Guardian of Joshua Emmanuel Santos', NULL, 9, NULL, NULL, NULL),
(1012, '2024-0113', 'Althea Joy', 'Ignacio', 'Reyes', NULL, 'Female', 'Single', '2022-09-02', 'Sagay City, Negros Occidental', 'Active', 4, 'Filipino', 'Roman Catholic', '09973675074', 'Brgy. Bulanon, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'althea.ignacio@sjcssagay.edu.ph', NULL, '100000037444', 'Parent/Guardian of Althea Joy Ignacio', NULL, 9, NULL, NULL, NULL),
(1013, '2024-0114', 'Bea Nicole', 'Salvador', 'Bautista', NULL, 'Female', 'Single', '2022-09-09', 'Sagay City, Negros Occidental', 'Active', 4, 'Filipino', 'Roman Catholic', '09603768720', 'Brgy. Malubon, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'bea.salvador@sjcssagay.edu.ph', NULL, '100000037481', 'Parent/Guardian of Bea Nicole Salvador', NULL, 9, NULL, NULL, NULL),
(1014, '2024-0115', 'Kristine Joy', 'Katigbak', 'Bautista', NULL, 'Female', 'Single', '2022-09-16', 'Sagay City, Negros Occidental', 'Active', 4, 'Filipino', 'Roman Catholic', '09268987475', 'Escalante City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'kristine.katigbak@sjcssagay.edu.ph', NULL, '100000037518', 'Parent/Guardian of Kristine Joy Katigbak', NULL, 9, NULL, NULL, NULL),
(1015, '2024-0116', 'Nathaniel', 'Dela Cruz', 'Rivera', NULL, 'Male', 'Single', '2022-09-23', 'Sagay City, Negros Occidental', 'Active', 4, 'Filipino', 'Roman Catholic', '09267321599', 'Brgy. Bulanon, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'nathaniel.delacruz@sjcssagay.edu.ph', NULL, '100000037555', 'Parent/Guardian of Nathaniel Dela Cruz', NULL, 9, NULL, NULL, NULL),
(1016, '2024-0117', 'Joshua Emmanuel', 'Garcia', 'Cruz', NULL, 'Male', 'Single', '2022-09-30', 'Sagay City, Negros Occidental', 'Active', 4, 'Filipino', 'Roman Catholic', '09716104027', 'Bais City, Negros Oriental', 'Negros Oriental', NULL, NULL, 'joshua.garcia@sjcssagay.edu.ph', NULL, '100000037592', 'Parent/Guardian of Joshua Emmanuel Garcia', NULL, 9, NULL, NULL, NULL),
(1017, '2024-0118', 'Jasmine Faye', 'Padilla', 'Lim', NULL, 'Female', 'Single', '2022-10-07', 'Sagay City, Negros Occidental', 'Active', 4, 'Filipino', 'Roman Catholic', '09919421006', 'Brgy. Andres Bonifacio, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'jasmine.padilla@sjcssagay.edu.ph', NULL, '100000037629', 'Parent/Guardian of Jasmine Faye Padilla', NULL, 9, NULL, NULL, NULL),
(1018, '2024-0119', 'Andrea Nicole', 'Ramos', 'Gomez', NULL, 'Female', 'Single', '2021-10-14', 'Sagay City, Negros Occidental', 'Active', 5, 'Filipino', 'Roman Catholic', '09821469639', 'Brgy. Taba-ao, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'andrea.ramos@sjcssagay.edu.ph', NULL, '100000037666', 'Parent/Guardian of Andrea Nicole Ramos', NULL, 10, NULL, NULL, NULL),
(1019, '2024-0120', 'Juan Miguel', 'Uy', 'Gomez', NULL, 'Male', 'Single', '2021-10-21', 'Sagay City, Negros Occidental', 'Active', 5, 'Filipino', 'Roman Catholic', '09611562584', 'Roxas City, Capiz', 'Capiz', NULL, NULL, 'juan.uy@sjcssagay.edu.ph', NULL, '100000037703', 'Parent/Guardian of Juan Miguel Uy', NULL, 10, NULL, NULL, NULL),
(1020, '2024-0121', 'Bea Nicole', 'Katigbak', 'Aguilar', NULL, 'Female', 'Single', '2021-10-28', 'Sagay City, Negros Occidental', 'Active', 5, 'Filipino', 'Roman Catholic', '09228741787', 'Brgy. Malubon, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'bea.katigbak@sjcssagay.edu.ph', NULL, '100000037740', 'Parent/Guardian of Bea Nicole Katigbak', NULL, 10, NULL, NULL, NULL),
(1021, '2024-0122', 'Rey Vincent', 'Gonzales', 'Tan', NULL, 'Male', 'Single', '2021-11-04', 'Sagay City, Negros Occidental', 'Active', 5, 'Filipino', 'Roman Catholic', '09128964835', 'Brgy. Taba-ao, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'rey.gonzales@sjcssagay.edu.ph', NULL, '100000037777', 'Parent/Guardian of Rey Vincent Gonzales', NULL, 10, NULL, NULL, NULL),
(1022, '2024-0123', 'Hannah Grace', 'Fernandez', 'Salas', NULL, 'Female', 'Single', '2021-11-11', 'Sagay City, Negros Occidental', 'Active', 5, 'Filipino', 'Roman Catholic', '09236125677', 'Brgy. Taba-ao, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'hannah.fernandez@sjcssagay.edu.ph', NULL, '100000037814', 'Parent/Guardian of Hannah Grace Fernandez', NULL, 10, NULL, NULL, NULL),
(1023, '2024-0124', 'Juan Miguel', 'Ocampo', 'Uy', NULL, 'Male', 'Single', '2021-11-18', 'Sagay City, Negros Occidental', 'Active', 5, 'Filipino', 'Roman Catholic', '09643079874', 'Sitio Palanas, Brgy. Poblacion II, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'juan.ocampo@sjcssagay.edu.ph', NULL, '100000037851', 'Parent/Guardian of Juan Miguel Ocampo', NULL, 10, NULL, NULL, NULL),
(1024, '2024-0125', 'Andrea Nicole', 'Lorenzo', 'Dizon', NULL, 'Female', 'Single', '2021-11-25', 'Sagay City, Negros Occidental', 'Active', 5, 'Filipino', 'Roman Catholic', '09252549295', 'Brgy. Old Sagay, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'andrea.lorenzo@sjcssagay.edu.ph', NULL, '100000037888', 'Parent/Guardian of Andrea Nicole Lorenzo', NULL, 10, NULL, NULL, NULL),
(1025, '2024-0126', 'Adrian Cole', 'Flores', 'Gomez', NULL, 'Male', 'Single', '2021-12-02', 'Sagay City, Negros Occidental', 'Active', 5, 'Filipino', 'Roman Catholic', '09293100078', 'Brgy. Poblacion I, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'adrian.flores@sjcssagay.edu.ph', NULL, '100000037925', 'Parent/Guardian of Adrian Cole Flores', NULL, 10, NULL, NULL, NULL),
(1026, '2024-0127', 'Hannah Grace', 'Padilla', 'Lim', NULL, 'Female', 'Single', '2021-12-09', 'Sagay City, Negros Occidental', 'Active', 5, 'Filipino', 'Roman Catholic', '09461737998', 'Brgy. Taba-ao, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'hannah.padilla@sjcssagay.edu.ph', NULL, '100000037962', 'Parent/Guardian of Hannah Grace Padilla', NULL, 10, NULL, NULL, NULL),
(1027, '2024-0128', 'Angel Grace', 'Herrera', 'Santos', NULL, 'Female', 'Single', '2021-12-16', 'Sagay City, Negros Occidental', 'Active', 5, 'Filipino', 'Roman Catholic', '09230148488', 'Brgy. Vito, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'angel.herrera@sjcssagay.edu.ph', NULL, '100000037999', 'Parent/Guardian of Angel Grace Herrera', NULL, 10, NULL, NULL, NULL),
(1028, '2024-0129', 'Shaira', 'Jimenez', 'Salas', NULL, 'Female', 'Single', '2021-12-23', 'Sagay City, Negros Occidental', 'Active', 5, 'Filipino', 'Roman Catholic', '09284083379', 'Brgy. Malubon, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'shaira.jimenez@sjcssagay.edu.ph', NULL, '100000038036', 'Parent/Guardian of Shaira Jimenez', NULL, 10, NULL, NULL, NULL),
(1029, '2024-0130', 'Althea Joy', 'Castillo', 'Salas', NULL, 'Female', 'Single', '2021-01-04', 'Sagay City, Negros Occidental', 'Active', 5, 'Filipino', 'Roman Catholic', '09870707041', 'Brgy. Andres Bonifacio, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'althea.castillo@sjcssagay.edu.ph', NULL, '100000038073', 'Parent/Guardian of Althea Joy Castillo', NULL, 10, NULL, NULL, NULL),
(1030, '2024-0131', 'Rey Vincent', 'Nolasco', 'Uy', NULL, 'Male', 'Single', '2020-01-11', 'Sagay City, Negros Occidental', 'Active', 6, 'Filipino', 'Roman Catholic', '09355256737', 'Brgy. Himoga-an Baybay, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'rey.nolasco@sjcssagay.edu.ph', NULL, '100000038110', 'Parent/Guardian of Rey Vincent Nolasco', NULL, 11, NULL, NULL, NULL),
(1031, '2024-0132', 'Joshua Emmanuel', 'Lorenzo', 'Dizon', NULL, 'Male', 'Single', '2020-01-18', 'Sagay City, Negros Occidental', 'Active', 6, 'Filipino', 'Roman Catholic', '09839325257', 'Brgy. Andres Bonifacio, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'joshua.lorenzo@sjcssagay.edu.ph', NULL, '100000038147', 'Parent/Guardian of Joshua Emmanuel Lorenzo', NULL, 11, NULL, NULL, NULL),
(1032, '2024-0133', 'Charlene Mae', 'Katigbak', 'Reyes', NULL, 'Female', 'Single', '2020-01-25', 'Sagay City, Negros Occidental', 'Active', 6, 'Filipino', 'Roman Catholic', '09711677819', 'Brgy. Andres Bonifacio, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'charlene.katigbak@sjcssagay.edu.ph', NULL, '100000038184', 'Parent/Guardian of Charlene Mae Katigbak', NULL, 11, NULL, NULL, NULL),
(1033, '2024-0134', 'Ericka Shane', 'Dela Cruz', 'Lim', NULL, 'Female', 'Single', '2020-02-01', 'Sagay City, Negros Occidental', 'Active', 6, 'Filipino', 'Roman Catholic', '09817641425', 'Brgy. Andres Bonifacio, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'ericka.delacruz@sjcssagay.edu.ph', NULL, '100000038221', 'Parent/Guardian of Ericka Shane Dela Cruz', NULL, 11, NULL, NULL, NULL),
(1034, '2024-0135', 'Kristine Joy', 'Ocampo', 'Reyes', NULL, 'Female', 'Single', '2020-02-08', 'Sagay City, Negros Occidental', 'Active', 6, 'Filipino', 'Roman Catholic', '09775626143', 'Brgy. Fabrica, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'kristine.ocampo@sjcssagay.edu.ph', NULL, '100000038258', 'Parent/Guardian of Kristine Joy Ocampo', NULL, 11, NULL, NULL, NULL),
(1035, '2024-0136', 'Maria Clara', 'Ubaldo', 'Cruz', NULL, 'Female', 'Single', '2020-02-15', 'Sagay City, Negros Occidental', 'Active', 6, 'Filipino', 'Roman Catholic', '09350941349', 'Brgy. Andres Bonifacio, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'maria.ubaldo@sjcssagay.edu.ph', NULL, '100000038295', 'Parent/Guardian of Maria Clara Ubaldo', NULL, 11, NULL, NULL, NULL),
(1036, '2024-0137', 'Ericka Shane', 'Reyes', 'Lim', NULL, 'Female', 'Single', '2020-02-22', 'Sagay City, Negros Occidental', 'Active', 6, 'Filipino', 'Roman Catholic', '09578426358', 'Brgy. Rizal, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'ericka.reyes@sjcssagay.edu.ph', NULL, '100000038332', 'Parent/Guardian of Ericka Shane Reyes', NULL, 11, NULL, NULL, NULL),
(1037, '2024-0138', 'Althea Joy', 'Bautista', 'Rivera', NULL, 'Female', 'Single', '2019-03-01', 'Sagay City, Negros Occidental', 'Active', 7, 'Filipino', 'Roman Catholic', '09165539112', 'Brgy. Taba-ao, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'althea.bautista@sjcssagay.edu.ph', NULL, '100000038369', 'Parent/Guardian of Althea Joy Bautista', NULL, 12, NULL, NULL, NULL),
(1038, '2024-0139', 'Hannah Grace', 'Jimenez', 'Santos', NULL, 'Female', 'Single', '2019-03-08', 'Sagay City, Negros Occidental', 'Active', 7, 'Filipino', 'Roman Catholic', '09394504397', 'Escalante City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'hannah.jimenez@sjcssagay.edu.ph', NULL, '100000038406', 'Parent/Guardian of Hannah Grace Jimenez', NULL, 12, NULL, NULL, NULL),
(1039, '2024-0140', 'Kyla Marie', 'Ignacio', 'Lim', NULL, 'Female', 'Single', '2019-03-15', 'Sagay City, Negros Occidental', 'Active', 7, 'Filipino', 'Roman Catholic', '09461269722', 'Brgy. Rizal, Sagay City, Negros Occidental', 'Negros Occidental', NULL, NULL, 'kyla.ignacio@sjcssagay.edu.ph', NULL, '100000038443', 'Parent/Guardian of Kyla Marie Ignacio', NULL, 12, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblsubjects`
--

CREATE TABLE `tblsubjects` (
  `SUBJECT_ID` int(11) NOT NULL,
  `SUBJECT_CODE` varchar(20) NOT NULL,
  `SUBJECT_NAME` varchar(150) NOT NULL,
  `UNITS` int(11) NOT NULL DEFAULT 3,
  `COURSE_ID` int(11) NOT NULL,
  `YEAR_LEVEL` varchar(20) DEFAULT NULL,
  `SEMESTER` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tblsubjects`
--

INSERT INTO `tblsubjects` (`SUBJECT_ID`, `SUBJECT_CODE`, `SUBJECT_NAME`, `UNITS`, `COURSE_ID`, `YEAR_LEVEL`, `SEMESTER`) VALUES
(138, 'G1-MTB', 'Mother Tongue', 0, 11, 'Grade 1', 'Whole Year'),
(139, 'G2-MTB', 'Mother Tongue', 0, 12, 'Grade 2', 'Whole Year'),
(140, 'G3-MTB', 'Mother Tongue', 0, 13, 'Grade 3', 'Whole Year'),
(141, 'G1-FIL', 'Filipino', 0, 11, 'Grade 1', 'Whole Year'),
(142, 'G2-FIL', 'Filipino', 0, 12, 'Grade 2', 'Whole Year'),
(143, 'G3-FIL', 'Filipino', 0, 13, 'Grade 3', 'Whole Year'),
(144, 'G1-ENG', 'English', 0, 11, 'Grade 1', 'Whole Year'),
(145, 'G2-ENG', 'English', 0, 12, 'Grade 2', 'Whole Year'),
(146, 'G3-ENG', 'English', 0, 13, 'Grade 3', 'Whole Year'),
(147, 'G1-MATH', 'Mathematics', 0, 11, 'Grade 1', 'Whole Year'),
(148, 'G2-MATH', 'Mathematics', 0, 12, 'Grade 2', 'Whole Year'),
(149, 'G3-MATH', 'Mathematics', 0, 13, 'Grade 3', 'Whole Year'),
(150, 'G1-AP', 'Araling Panlipunan', 0, 11, 'Grade 1', 'Whole Year'),
(151, 'G2-AP', 'Araling Panlipunan', 0, 12, 'Grade 2', 'Whole Year'),
(152, 'G3-AP', 'Araling Panlipunan', 0, 13, 'Grade 3', 'Whole Year'),
(153, 'G1-ESP', 'Edukasyon sa Pagpapakatao', 0, 11, 'Grade 1', 'Whole Year'),
(154, 'G2-ESP', 'Edukasyon sa Pagpapakatao', 0, 12, 'Grade 2', 'Whole Year'),
(155, 'G3-ESP', 'Edukasyon sa Pagpapakatao', 0, 13, 'Grade 3', 'Whole Year'),
(156, 'G1-MAPEH', 'MAPEH', 0, 11, 'Grade 1', 'Whole Year'),
(157, 'G2-MAPEH', 'MAPEH', 0, 12, 'Grade 2', 'Whole Year'),
(158, 'G3-MAPEH', 'MAPEH', 0, 13, 'Grade 3', 'Whole Year'),
(159, 'G1-CLE', 'Christian Living Education', 0, 11, 'Grade 1', 'Whole Year'),
(160, 'G2-CLE', 'Christian Living Education', 0, 12, 'Grade 2', 'Whole Year'),
(161, 'G3-CLE', 'Christian Living Education', 0, 13, 'Grade 3', 'Whole Year'),
(169, 'G4-FIL', 'Filipino', 0, 14, 'Grade 4', 'Whole Year'),
(170, 'G5-FIL', 'Filipino', 0, 15, 'Grade 5', 'Whole Year'),
(171, 'G6-FIL', 'Filipino', 0, 16, 'Grade 6', 'Whole Year'),
(172, 'G4-ENG', 'English', 0, 14, 'Grade 4', 'Whole Year'),
(173, 'G5-ENG', 'English', 0, 15, 'Grade 5', 'Whole Year'),
(174, 'G6-ENG', 'English', 0, 16, 'Grade 6', 'Whole Year'),
(175, 'G4-MATH', 'Mathematics', 0, 14, 'Grade 4', 'Whole Year'),
(176, 'G5-MATH', 'Mathematics', 0, 15, 'Grade 5', 'Whole Year'),
(177, 'G6-MATH', 'Mathematics', 0, 16, 'Grade 6', 'Whole Year'),
(178, 'G4-SCI', 'Science', 0, 14, 'Grade 4', 'Whole Year'),
(179, 'G5-SCI', 'Science', 0, 15, 'Grade 5', 'Whole Year'),
(180, 'G6-SCI', 'Science', 0, 16, 'Grade 6', 'Whole Year'),
(181, 'G4-AP', 'Araling Panlipunan', 0, 14, 'Grade 4', 'Whole Year'),
(182, 'G5-AP', 'Araling Panlipunan', 0, 15, 'Grade 5', 'Whole Year'),
(183, 'G6-AP', 'Araling Panlipunan', 0, 16, 'Grade 6', 'Whole Year'),
(184, 'G4-ESP', 'Edukasyon sa Pagpapakatao', 0, 14, 'Grade 4', 'Whole Year'),
(185, 'G5-ESP', 'Edukasyon sa Pagpapakatao', 0, 15, 'Grade 5', 'Whole Year'),
(186, 'G6-ESP', 'Edukasyon sa Pagpapakatao', 0, 16, 'Grade 6', 'Whole Year'),
(187, 'G4-EPP', 'Edukasyong Pantahanan at Pangkabuhayan', 0, 14, 'Grade 4', 'Whole Year'),
(188, 'G5-EPP', 'Edukasyong Pantahanan at Pangkabuhayan', 0, 15, 'Grade 5', 'Whole Year'),
(189, 'G6-EPP', 'Edukasyong Pantahanan at Pangkabuhayan', 0, 16, 'Grade 6', 'Whole Year'),
(190, 'G4-MAPEH', 'MAPEH', 0, 14, 'Grade 4', 'Whole Year'),
(191, 'G5-MAPEH', 'MAPEH', 0, 15, 'Grade 5', 'Whole Year'),
(192, 'G6-MAPEH', 'MAPEH', 0, 16, 'Grade 6', 'Whole Year'),
(193, 'G4-CLE', 'Christian Living Education', 0, 14, 'Grade 4', 'Whole Year'),
(194, 'G5-CLE', 'Christian Living Education', 0, 15, 'Grade 5', 'Whole Year'),
(195, 'G6-CLE', 'Christian Living Education', 0, 16, 'Grade 6', 'Whole Year'),
(200, 'G7-FIL', 'Filipino', 0, 17, 'Grade 7', 'Whole Year'),
(201, 'G8-FIL', 'Filipino', 0, 18, 'Grade 8', 'Whole Year'),
(202, 'G9-FIL', 'Filipino', 0, 19, 'Grade 9', 'Whole Year'),
(203, 'G10-FIL', 'Filipino', 0, 20, 'Grade 10', 'Whole Year'),
(204, 'G7-ENG', 'English', 0, 17, 'Grade 7', 'Whole Year'),
(205, 'G8-ENG', 'English', 0, 18, 'Grade 8', 'Whole Year'),
(206, 'G9-ENG', 'English', 0, 19, 'Grade 9', 'Whole Year'),
(207, 'G10-ENG', 'English', 0, 20, 'Grade 10', 'Whole Year'),
(208, 'G7-MATH', 'Mathematics', 0, 17, 'Grade 7', 'Whole Year'),
(209, 'G8-MATH', 'Mathematics', 0, 18, 'Grade 8', 'Whole Year'),
(210, 'G9-MATH', 'Mathematics', 0, 19, 'Grade 9', 'Whole Year'),
(211, 'G10-MATH', 'Mathematics', 0, 20, 'Grade 10', 'Whole Year'),
(212, 'G7-SCI', 'Science', 0, 17, 'Grade 7', 'Whole Year'),
(213, 'G8-SCI', 'Science', 0, 18, 'Grade 8', 'Whole Year'),
(214, 'G9-SCI', 'Science', 0, 19, 'Grade 9', 'Whole Year'),
(215, 'G10-SCI', 'Science', 0, 20, 'Grade 10', 'Whole Year'),
(216, 'G7-AP', 'Araling Panlipunan', 0, 17, 'Grade 7', 'Whole Year'),
(217, 'G8-AP', 'Araling Panlipunan', 0, 18, 'Grade 8', 'Whole Year'),
(218, 'G9-AP', 'Araling Panlipunan', 0, 19, 'Grade 9', 'Whole Year'),
(219, 'G10-AP', 'Araling Panlipunan', 0, 20, 'Grade 10', 'Whole Year'),
(220, 'G7-ESP', 'Edukasyon sa Pagpapakatao', 0, 17, 'Grade 7', 'Whole Year'),
(221, 'G8-ESP', 'Edukasyon sa Pagpapakatao', 0, 18, 'Grade 8', 'Whole Year'),
(222, 'G9-ESP', 'Edukasyon sa Pagpapakatao', 0, 19, 'Grade 9', 'Whole Year'),
(223, 'G10-ESP', 'Edukasyon sa Pagpapakatao', 0, 20, 'Grade 10', 'Whole Year'),
(224, 'G7-TLE', 'Technology and Livelihood Education', 0, 17, 'Grade 7', 'Whole Year'),
(225, 'G8-TLE', 'Technology and Livelihood Education', 0, 18, 'Grade 8', 'Whole Year'),
(226, 'G9-TLE', 'Technology and Livelihood Education', 0, 19, 'Grade 9', 'Whole Year'),
(227, 'G10-TLE', 'Technology and Livelihood Education', 0, 20, 'Grade 10', 'Whole Year'),
(228, 'G7-MAPEH', 'MAPEH', 0, 17, 'Grade 7', 'Whole Year'),
(229, 'G8-MAPEH', 'MAPEH', 0, 18, 'Grade 8', 'Whole Year'),
(230, 'G9-MAPEH', 'MAPEH', 0, 19, 'Grade 9', 'Whole Year'),
(231, 'G10-MAPEH', 'MAPEH', 0, 20, 'Grade 10', 'Whole Year'),
(232, 'G7-CLE', 'Christian Living Education', 0, 17, 'Grade 7', 'Whole Year'),
(233, 'G8-CLE', 'Christian Living Education', 0, 18, 'Grade 8', 'Whole Year'),
(234, 'G9-CLE', 'Christian Living Education', 0, 19, 'Grade 9', 'Whole Year'),
(235, 'G10-CLE', 'Christian Living Education', 0, 20, 'Grade 10', 'Whole Year'),
(263, 'NUR1-KP', 'Kindergarten Program', 0, 8, 'Nursery 1', 'Whole Year'),
(264, 'NUR1-CLE', 'Christian Living Education', 0, 8, 'Nursery 1', 'Whole Year'),
(265, 'NUR2-KP', 'Kindergarten Program', 0, 9, 'Nursery 2', 'Whole Year'),
(266, 'NUR2-CLE', 'Christian Living Education', 0, 9, 'Nursery 2', 'Whole Year'),
(267, 'KINDER-KP', 'Kindergarten Program', 0, 10, 'Kindergarten', 'Whole Year'),
(268, 'KINDER-CLE', 'Christian Living Education', 0, 10, 'Kindergarten', 'Whole Year');

-- --------------------------------------------------------

--
-- Table structure for table `tblsubject_prerequisites`
--

CREATE TABLE `tblsubject_prerequisites` (
  `SUBJECT_ID` int(11) NOT NULL,
  `PREREQ_SUBJECT_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblteacher_subjects`
--

CREATE TABLE `tblteacher_subjects` (
  `TS_ID` int(11) NOT NULL,
  `UID` int(11) NOT NULL COMMENT 'FK tblusers, the teacher account',
  `SUBJECT_ID` int(11) NOT NULL,
  `SECTION_ID` int(11) NOT NULL,
  `SY_ID` int(11) NOT NULL,
  `SEMESTER` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE `tblusers` (
  `UID` int(11) NOT NULL,
  `DISPLAYNAME` varchar(30) NOT NULL,
  `USERNAME` varchar(50) NOT NULL,
  `PASSWORD` text NOT NULL,
  `TYPE` varchar(15) NOT NULL,
  `TYPEID` int(11) DEFAULT NULL,
  `ADDEDBY` int(3) NOT NULL,
  `DATEADDED` date NOT NULL,
  `MODIFIEDBY` int(3) NOT NULL,
  `DATEMODIFIED` date NOT NULL,
  `STATUSACTIVE` int(2) NOT NULL DEFAULT 1,
  `RFID_NUMBER` varchar(50) DEFAULT NULL,
  `PHOTO` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`UID`, `DISPLAYNAME`, `USERNAME`, `PASSWORD`, `TYPE`, `TYPEID`, `ADDEDBY`, `DATEADDED`, `MODIFIEDBY`, `DATEMODIFIED`, `STATUSACTIVE`, `RFID_NUMBER`, `PHOTO`) VALUES
(1, 'Jason', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'Administrator', 1, 1, '2020-08-27', 1, '2021-07-05', 1, NULL, NULL),
(77, 'dfd', 'dfd', '6bb65257fcab4e2975cd96b0f7fc4b53d97c10b6', 'Staff', 3, 1, '2025-01-16', 1, '2026-08-02', 1, NULL, NULL),
(78, 'dfd', 'dfdf', '6bb65257fcab4e2975cd96b0f7fc4b53d97c10b6', 'Staff', 3, 1, '2025-01-16', 1, '2025-01-16', 1, NULL, NULL),
(79, 'Erick', 'jason', '5c2dd944dde9e08881bef0894fe7b22a5c9c4b06', 'Administrator', 1, 1, '2025-01-24', 1, '2025-01-24', 1, NULL, NULL),
(81, 'Bruce', 'bruce', '9ae9c44b775ee9b29f4f502415e02e61a9b1a6a0', 'Administrator', 1, 1, '2026-08-18', 1, '2026-08-18', 1, NULL, NULL),
(82, 'Registrar Account', 'registrar1', 'd82c8914ba7493040f6806f0e134cb2db25587e8', 'Registrar', 16, 1, '2026-09-07', 1, '2026-09-07', 1, NULL, NULL),
(83, 'Cashier Account', 'cashier1', '60e63b2213ff379fabf41e49b92223f277a4856e', 'Cashier', 17, 1, '2026-09-07', 1, '2026-09-07', 1, NULL, NULL),
(84, 'Teacher Account', 'teacher1', '18950a0c60e410597278b0638cae8cf2e7ceca19', 'Teacher', 18, 1, '2026-09-07', 1, '2026-09-07', 1, NULL, NULL),
(85, 'hfghdfh ghdfhdf', 'brucee', 'f2dd0ae06c073ac3132759f780ddefeecb31a1fe', 'Applicant', 20, 0, '2026-09-11', 0, '2026-09-11', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblusertype`
--

CREATE TABLE `tblusertype` (
  `TYPEID` int(11) NOT NULL,
  `USERTYPE` varchar(30) NOT NULL,
  `STATUS` varchar(20) NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblusertype`
--

INSERT INTO `tblusertype` (`TYPEID`, `USERTYPE`, `STATUS`) VALUES
(1, 'Administrator', 'Active'),
(3, 'Staff', 'Active'),
(16, 'Registrar', 'Active'),
(17, 'Cashier', 'Active'),
(18, 'Teacher', 'Active'),
(19, 'Student', 'Active'),
(20, 'Applicant', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alumni_details`
--
ALTER TABLE `alumni_details`
  ADD PRIMARY KEY (`AlumniID`),
  ADD KEY `fk_alumni_student` (`S_ID`),
  ADD KEY `fk_alumni_addedby` (`AddedBy`);

--
-- Indexes for table `tbladmission_payments`
--
ALTER TABLE `tbladmission_payments`
  ADD PRIMARY KEY (`PAYMENT_ID`),
  ADD KEY `idx_payment_status` (`STATUS`),
  ADD KEY `fk_payment_applicant` (`APPLICANT_ID`),
  ADD KEY `fk_payment_student` (`S_ID`),
  ADD KEY `fk_payment_sy` (`SY_ID`),
  ADD KEY `fk_payment_verifiedby` (`VERIFIED_BY`);

--
-- Indexes for table `tbladmission_payment_items`
--
ALTER TABLE `tbladmission_payment_items`
  ADD PRIMARY KEY (`ITEM_ID`),
  ADD KEY `fk_item_payment` (`PAYMENT_ID`),
  ADD KEY `fk_item_feetype` (`FEE_TYPE_ID`);

--
-- Indexes for table `tbladmission_receipts`
--
ALTER TABLE `tbladmission_receipts`
  ADD PRIMARY KEY (`RECEIPT_ID`),
  ADD UNIQUE KEY `uq_receipt_no` (`RECEIPT_NO`),
  ADD KEY `fk_receipt_payment` (`PAYMENT_ID`),
  ADD KEY `fk_receipt_issuedby` (`ISSUED_BY`);

--
-- Indexes for table `tblannouncements`
--
ALTER TABLE `tblannouncements`
  ADD PRIMARY KEY (`ANNOUNCEMENT_ID`),
  ADD KEY `fk_announcement_postedby` (`POSTED_BY`);

--
-- Indexes for table `tblapplicants`
--
ALTER TABLE `tblapplicants`
  ADD PRIMARY KEY (`APPLICANT_ID`),
  ADD KEY `idx_applicant_status` (`STATUS`),
  ADD KEY `fk_applicant_user` (`UID`),
  ADD KEY `fk_applicant_course` (`COURSE_ID`),
  ADD KEY `fk_applicant_major` (`MAJOR_ID`),
  ADD KEY `fk_applicant_sy` (`SY_ID`),
  ADD KEY `fk_applicant_student` (`APPROVED_S_ID`),
  ADD KEY `IX_APP_PROVINCE` (`PROVINCE`);

--
-- Indexes for table `tblattendance`
--
ALTER TABLE `tblattendance`
  ADD PRIMARY KEY (`ATTENDANCE_ID`),
  ADD UNIQUE KEY `uq_attendance_day` (`UID`,`ATTENDANCE_DATE`);

--
-- Indexes for table `tblaudit_logs`
--
ALTER TABLE `tblaudit_logs`
  ADD PRIMARY KEY (`LOG_ID`),
  ADD KEY `fk_audit_user` (`UID`);

--
-- Indexes for table `tblclass_attendance`
--
ALTER TABLE `tblclass_attendance`
  ADD PRIMARY KEY (`ATTENDANCE_ID`),
  ADD UNIQUE KEY `uq_attendance` (`S_ID`,`TS_ID`,`ATTENDANCE_DATE`),
  ADD KEY `fk_attendance_ts` (`TS_ID`),
  ADD KEY `fk_attendance_encodedby` (`ENCODED_BY`);

--
-- Indexes for table `tblclass_schedules`
--
ALTER TABLE `tblclass_schedules`
  ADD PRIMARY KEY (`SCHEDULE_ID`),
  ADD KEY `fk_schedule_ts` (`TS_ID`);

--
-- Indexes for table `tblcourses`
--
ALTER TABLE `tblcourses`
  ADD PRIMARY KEY (`COURSE_ID`),
  ADD UNIQUE KEY `COURSE_CODE` (`COURSE_CODE`),
  ADD KEY `IX_LEVEL_ORDER` (`LEVEL_ORDER`);

--
-- Indexes for table `tbldocuments`
--
ALTER TABLE `tbldocuments`
  ADD PRIMARY KEY (`DOCUMENT_ID`),
  ADD KEY `fk_doc_applicant` (`APPLICANT_ID`),
  ADD KEY `fk_doc_student` (`S_ID`);

--
-- Indexes for table `tblenrollment`
--
ALTER TABLE `tblenrollment`
  ADD PRIMARY KEY (`ENROLLMENT_ID`),
  ADD UNIQUE KEY `uq_enrollment_term` (`S_ID`,`SY_ID`,`SEMESTER`),
  ADD KEY `fk_enrollment_student` (`S_ID`),
  ADD KEY `fk_enrollment_course` (`COURSE_ID`),
  ADD KEY `fk_enrollment_section` (`SECTION_ID`),
  ADD KEY `fk_enrollment_sy` (`SY_ID`),
  ADD KEY `fk_enrollment_encodedby` (`ENCODED_BY`);

--
-- Indexes for table `tblenrollment_details`
--
ALTER TABLE `tblenrollment_details`
  ADD PRIMARY KEY (`DETAIL_ID`),
  ADD UNIQUE KEY `uq_enrollment_subject` (`ENROLLMENT_ID`,`SUBJECT_ID`),
  ADD KEY `fk_endetails_enrollment` (`ENROLLMENT_ID`),
  ADD KEY `fk_endetails_subject` (`SUBJECT_ID`);

--
-- Indexes for table `tblentrance_exams`
--
ALTER TABLE `tblentrance_exams`
  ADD PRIMARY KEY (`EXAM_ID`),
  ADD KEY `fk_exam_sy` (`SY_ID`);

--
-- Indexes for table `tblexam_results`
--
ALTER TABLE `tblexam_results`
  ADD PRIMARY KEY (`RESULT_ID`),
  ADD KEY `fk_result_applicant` (`APPLICANT_ID`),
  ADD KEY `fk_result_exam` (`EXAM_ID`),
  ADD KEY `fk_result_encodedby` (`ENCODED_BY`);

--
-- Indexes for table `tblfeeschedule`
--
ALTER TABLE `tblfeeschedule`
  ADD PRIMARY KEY (`FEESCHEDULE_ID`),
  ADD UNIQUE KEY `uq_feeschedule` (`COURSE_ID`,`YEAR_LEVEL`);

--
-- Indexes for table `tblfee_types`
--
ALTER TABLE `tblfee_types`
  ADD PRIMARY KEY (`FEE_TYPE_ID`),
  ADD UNIQUE KEY `uq_fee_code` (`FEE_CODE`);

--
-- Indexes for table `tblgrades`
--
ALTER TABLE `tblgrades`
  ADD PRIMARY KEY (`GRADE_ID`),
  ADD KEY `fk_grades_student` (`S_ID`),
  ADD KEY `fk_grades_subject` (`SUBJECT_ID`),
  ADD KEY `fk_grades_enrollment` (`ENROLLMENT_ID`),
  ADD KEY `fk_grades_sy` (`SY_ID`);

--
-- Indexes for table `tblhomepage_content_blocks`
--
ALTER TABLE `tblhomepage_content_blocks`
  ADD PRIMARY KEY (`BLOCK_ID`),
  ADD KEY `fk_block_section` (`SECTION_ID`);

--
-- Indexes for table `tblhomepage_sections`
--
ALTER TABLE `tblhomepage_sections`
  ADD PRIMARY KEY (`SECTION_ID`),
  ADD UNIQUE KEY `uq_section_key` (`SECTION_KEY`);

--
-- Indexes for table `tblmajors`
--
ALTER TABLE `tblmajors`
  ADD PRIMARY KEY (`MAJOR_ID`),
  ADD UNIQUE KEY `uq_major_course` (`COURSE_ID`,`MAJOR_CODE`);

--
-- Indexes for table `tblnews`
--
ALTER TABLE `tblnews`
  ADD PRIMARY KEY (`NEWS_ID`),
  ADD KEY `fk_news_createdby` (`CREATED_BY`);

--
-- Indexes for table `tblnews_media`
--
ALTER TABLE `tblnews_media`
  ADD PRIMARY KEY (`MEDIA_ID`),
  ADD KEY `fk_media_news` (`NEWS_ID`);

--
-- Indexes for table `tblnotifications`
--
ALTER TABLE `tblnotifications`
  ADD PRIMARY KEY (`NOTIF_ID`),
  ADD KEY `fk_notif_user` (`UID`);

--
-- Indexes for table `tblparents`
--
ALTER TABLE `tblparents`
  ADD PRIMARY KEY (`PARENT_ID`),
  ADD KEY `fk_parent_applicant` (`APPLICANT_ID`),
  ADD KEY `fk_parent_student` (`S_ID`);

--
-- Indexes for table `tblpayments`
--
ALTER TABLE `tblpayments`
  ADD PRIMARY KEY (`PAYMENT_ID`),
  ADD KEY `fk_payments_enrollment` (`ENROLLMENT_ID`),
  ADD KEY `fk_payments_receivedby` (`RECEIVED_BY`);

--
-- Indexes for table `tblprovinces`
--
ALTER TABLE `tblprovinces`
  ADD PRIMARY KEY (`PROV_PSGC`),
  ADD KEY `IX_PROVINCE` (`PROVINCE`);

--
-- Indexes for table `tblschoolyear`
--
ALTER TABLE `tblschoolyear`
  ADD PRIMARY KEY (`SY_ID`),
  ADD UNIQUE KEY `SCHOOL_YEAR` (`SCHOOL_YEAR`);

--
-- Indexes for table `tblsections`
--
ALTER TABLE `tblsections`
  ADD PRIMARY KEY (`SECTION_ID`),
  ADD KEY `fk_sections_course` (`COURSE_ID`),
  ADD KEY `fk_sections_sy` (`SY_ID`);

--
-- Indexes for table `tblsettings`
--
ALTER TABLE `tblsettings`
  ADD PRIMARY KEY (`SETTING_KEY`);

--
-- Indexes for table `tblstudent`
--
ALTER TABLE `tblstudent`
  ADD PRIMARY KEY (`S_ID`),
  ADD UNIQUE KEY `IDNO` (`IDNO`),
  ADD KEY `fk_student_course` (`COURSE_ID`),
  ADD KEY `fk_student_addedby` (`AddedBy`),
  ADD KEY `fk_student_major` (`MAJOR_ID`),
  ADD KEY `fk_student_applicant` (`APPLICANT_ID`),
  ADD KEY `IX_STUD_PROVINCE` (`PROVINCE`);

--
-- Indexes for table `tblsubjects`
--
ALTER TABLE `tblsubjects`
  ADD PRIMARY KEY (`SUBJECT_ID`),
  ADD UNIQUE KEY `uq_subject_code` (`SUBJECT_CODE`),
  ADD KEY `fk_subjects_course` (`COURSE_ID`);

--
-- Indexes for table `tblsubject_prerequisites`
--
ALTER TABLE `tblsubject_prerequisites`
  ADD PRIMARY KEY (`SUBJECT_ID`,`PREREQ_SUBJECT_ID`),
  ADD KEY `fk_prereq_prereq` (`PREREQ_SUBJECT_ID`);

--
-- Indexes for table `tblteacher_subjects`
--
ALTER TABLE `tblteacher_subjects`
  ADD PRIMARY KEY (`TS_ID`),
  ADD UNIQUE KEY `uq_teacher_assignment` (`UID`,`SUBJECT_ID`,`SECTION_ID`,`SY_ID`,`SEMESTER`),
  ADD KEY `fk_ts_subject` (`SUBJECT_ID`),
  ADD KEY `fk_ts_section` (`SECTION_ID`),
  ADD KEY `fk_ts_sy` (`SY_ID`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD PRIMARY KEY (`UID`),
  ADD UNIQUE KEY `uq_username` (`USERNAME`),
  ADD UNIQUE KEY `uq_users_rfid` (`RFID_NUMBER`),
  ADD KEY `fk_users_usertype` (`TYPEID`);

--
-- Indexes for table `tblusertype`
--
ALTER TABLE `tblusertype`
  ADD PRIMARY KEY (`TYPEID`),
  ADD UNIQUE KEY `uq_usertype` (`USERTYPE`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alumni_details`
--
ALTER TABLE `alumni_details`
  MODIFY `AlumniID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbladmission_payments`
--
ALTER TABLE `tbladmission_payments`
  MODIFY `PAYMENT_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbladmission_payment_items`
--
ALTER TABLE `tbladmission_payment_items`
  MODIFY `ITEM_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbladmission_receipts`
--
ALTER TABLE `tbladmission_receipts`
  MODIFY `RECEIPT_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblannouncements`
--
ALTER TABLE `tblannouncements`
  MODIFY `ANNOUNCEMENT_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblapplicants`
--
ALTER TABLE `tblapplicants`
  MODIFY `APPLICANT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=521;

--
-- AUTO_INCREMENT for table `tblattendance`
--
ALTER TABLE `tblattendance`
  MODIFY `ATTENDANCE_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblaudit_logs`
--
ALTER TABLE `tblaudit_logs`
  MODIFY `LOG_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblclass_attendance`
--
ALTER TABLE `tblclass_attendance`
  MODIFY `ATTENDANCE_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblclass_schedules`
--
ALTER TABLE `tblclass_schedules`
  MODIFY `SCHEDULE_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblcourses`
--
ALTER TABLE `tblcourses`
  MODIFY `COURSE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tbldocuments`
--
ALTER TABLE `tbldocuments`
  MODIFY `DOCUMENT_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblenrollment`
--
ALTER TABLE `tblenrollment`
  MODIFY `ENROLLMENT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1422;

--
-- AUTO_INCREMENT for table `tblenrollment_details`
--
ALTER TABLE `tblenrollment_details`
  MODIFY `DETAIL_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblentrance_exams`
--
ALTER TABLE `tblentrance_exams`
  MODIFY `EXAM_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblexam_results`
--
ALTER TABLE `tblexam_results`
  MODIFY `RESULT_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblfeeschedule`
--
ALTER TABLE `tblfeeschedule`
  MODIFY `FEESCHEDULE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tblfee_types`
--
ALTER TABLE `tblfee_types`
  MODIFY `FEE_TYPE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblgrades`
--
ALTER TABLE `tblgrades`
  MODIFY `GRADE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblhomepage_content_blocks`
--
ALTER TABLE `tblhomepage_content_blocks`
  MODIFY `BLOCK_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblhomepage_sections`
--
ALTER TABLE `tblhomepage_sections`
  MODIFY `SECTION_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblmajors`
--
ALTER TABLE `tblmajors`
  MODIFY `MAJOR_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblnews`
--
ALTER TABLE `tblnews`
  MODIFY `NEWS_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblnews_media`
--
ALTER TABLE `tblnews_media`
  MODIFY `MEDIA_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblnotifications`
--
ALTER TABLE `tblnotifications`
  MODIFY `NOTIF_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblparents`
--
ALTER TABLE `tblparents`
  MODIFY `PARENT_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblpayments`
--
ALTER TABLE `tblpayments`
  MODIFY `PAYMENT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=895;

--
-- AUTO_INCREMENT for table `tblschoolyear`
--
ALTER TABLE `tblschoolyear`
  MODIFY `SY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblsections`
--
ALTER TABLE `tblsections`
  MODIFY `SECTION_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=228;

--
-- AUTO_INCREMENT for table `tblstudent`
--
ALTER TABLE `tblstudent`
  MODIFY `S_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1172;

--
-- AUTO_INCREMENT for table `tblsubjects`
--
ALTER TABLE `tblsubjects`
  MODIFY `SUBJECT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=269;

--
-- AUTO_INCREMENT for table `tblteacher_subjects`
--
ALTER TABLE `tblteacher_subjects`
  MODIFY `TS_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `UID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `tblusertype`
--
ALTER TABLE `tblusertype`
  MODIFY `TYPEID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alumni_details`
--
ALTER TABLE `alumni_details`
  ADD CONSTRAINT `fk_alumni_addedby` FOREIGN KEY (`AddedBy`) REFERENCES `tblusers` (`UID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_alumni_student` FOREIGN KEY (`S_ID`) REFERENCES `tblstudent` (`S_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tbladmission_payments`
--
ALTER TABLE `tbladmission_payments`
  ADD CONSTRAINT `fk_payment_applicant` FOREIGN KEY (`APPLICANT_ID`) REFERENCES `tblapplicants` (`APPLICANT_ID`),
  ADD CONSTRAINT `fk_payment_student` FOREIGN KEY (`S_ID`) REFERENCES `tblstudent` (`S_ID`),
  ADD CONSTRAINT `fk_payment_sy` FOREIGN KEY (`SY_ID`) REFERENCES `tblschoolyear` (`SY_ID`),
  ADD CONSTRAINT `fk_payment_verifiedby` FOREIGN KEY (`VERIFIED_BY`) REFERENCES `tblusers` (`UID`);

--
-- Constraints for table `tbladmission_payment_items`
--
ALTER TABLE `tbladmission_payment_items`
  ADD CONSTRAINT `fk_item_feetype` FOREIGN KEY (`FEE_TYPE_ID`) REFERENCES `tblfee_types` (`FEE_TYPE_ID`),
  ADD CONSTRAINT `fk_item_payment` FOREIGN KEY (`PAYMENT_ID`) REFERENCES `tbladmission_payments` (`PAYMENT_ID`) ON DELETE CASCADE;

--
-- Constraints for table `tbladmission_receipts`
--
ALTER TABLE `tbladmission_receipts`
  ADD CONSTRAINT `fk_receipt_issuedby` FOREIGN KEY (`ISSUED_BY`) REFERENCES `tblusers` (`UID`),
  ADD CONSTRAINT `fk_receipt_payment` FOREIGN KEY (`PAYMENT_ID`) REFERENCES `tbladmission_payments` (`PAYMENT_ID`);

--
-- Constraints for table `tblannouncements`
--
ALTER TABLE `tblannouncements`
  ADD CONSTRAINT `fk_announcement_postedby` FOREIGN KEY (`POSTED_BY`) REFERENCES `tblusers` (`UID`);

--
-- Constraints for table `tblapplicants`
--
ALTER TABLE `tblapplicants`
  ADD CONSTRAINT `fk_applicant_course` FOREIGN KEY (`COURSE_ID`) REFERENCES `tblcourses` (`COURSE_ID`),
  ADD CONSTRAINT `fk_applicant_major` FOREIGN KEY (`MAJOR_ID`) REFERENCES `tblmajors` (`MAJOR_ID`),
  ADD CONSTRAINT `fk_applicant_student` FOREIGN KEY (`APPROVED_S_ID`) REFERENCES `tblstudent` (`S_ID`),
  ADD CONSTRAINT `fk_applicant_sy` FOREIGN KEY (`SY_ID`) REFERENCES `tblschoolyear` (`SY_ID`),
  ADD CONSTRAINT `fk_applicant_user` FOREIGN KEY (`UID`) REFERENCES `tblusers` (`UID`);

--
-- Constraints for table `tblattendance`
--
ALTER TABLE `tblattendance`
  ADD CONSTRAINT `fk_staffattendance_user` FOREIGN KEY (`UID`) REFERENCES `tblusers` (`UID`);

--
-- Constraints for table `tblaudit_logs`
--
ALTER TABLE `tblaudit_logs`
  ADD CONSTRAINT `fk_audit_user` FOREIGN KEY (`UID`) REFERENCES `tblusers` (`UID`);

--
-- Constraints for table `tblclass_attendance`
--
ALTER TABLE `tblclass_attendance`
  ADD CONSTRAINT `fk_attendance_encodedby` FOREIGN KEY (`ENCODED_BY`) REFERENCES `tblusers` (`UID`),
  ADD CONSTRAINT `fk_attendance_student` FOREIGN KEY (`S_ID`) REFERENCES `tblstudent` (`S_ID`),
  ADD CONSTRAINT `fk_attendance_ts` FOREIGN KEY (`TS_ID`) REFERENCES `tblteacher_subjects` (`TS_ID`);

--
-- Constraints for table `tblclass_schedules`
--
ALTER TABLE `tblclass_schedules`
  ADD CONSTRAINT `fk_schedule_ts` FOREIGN KEY (`TS_ID`) REFERENCES `tblteacher_subjects` (`TS_ID`);

--
-- Constraints for table `tbldocuments`
--
ALTER TABLE `tbldocuments`
  ADD CONSTRAINT `fk_doc_applicant` FOREIGN KEY (`APPLICANT_ID`) REFERENCES `tblapplicants` (`APPLICANT_ID`),
  ADD CONSTRAINT `fk_doc_student` FOREIGN KEY (`S_ID`) REFERENCES `tblstudent` (`S_ID`);

--
-- Constraints for table `tblenrollment`
--
ALTER TABLE `tblenrollment`
  ADD CONSTRAINT `fk_enrollment_course` FOREIGN KEY (`COURSE_ID`) REFERENCES `tblcourses` (`COURSE_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_enrollment_encodedby` FOREIGN KEY (`ENCODED_BY`) REFERENCES `tblusers` (`UID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_enrollment_section` FOREIGN KEY (`SECTION_ID`) REFERENCES `tblsections` (`SECTION_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_enrollment_student` FOREIGN KEY (`S_ID`) REFERENCES `tblstudent` (`S_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_enrollment_sy` FOREIGN KEY (`SY_ID`) REFERENCES `tblschoolyear` (`SY_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblenrollment_details`
--
ALTER TABLE `tblenrollment_details`
  ADD CONSTRAINT `fk_endetails_enrollment` FOREIGN KEY (`ENROLLMENT_ID`) REFERENCES `tblenrollment` (`ENROLLMENT_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_endetails_subject` FOREIGN KEY (`SUBJECT_ID`) REFERENCES `tblsubjects` (`SUBJECT_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblentrance_exams`
--
ALTER TABLE `tblentrance_exams`
  ADD CONSTRAINT `fk_exam_sy` FOREIGN KEY (`SY_ID`) REFERENCES `tblschoolyear` (`SY_ID`);

--
-- Constraints for table `tblexam_results`
--
ALTER TABLE `tblexam_results`
  ADD CONSTRAINT `fk_result_applicant` FOREIGN KEY (`APPLICANT_ID`) REFERENCES `tblapplicants` (`APPLICANT_ID`),
  ADD CONSTRAINT `fk_result_encodedby` FOREIGN KEY (`ENCODED_BY`) REFERENCES `tblusers` (`UID`),
  ADD CONSTRAINT `fk_result_exam` FOREIGN KEY (`EXAM_ID`) REFERENCES `tblentrance_exams` (`EXAM_ID`);

--
-- Constraints for table `tblfeeschedule`
--
ALTER TABLE `tblfeeschedule`
  ADD CONSTRAINT `fk_feeschedule_course` FOREIGN KEY (`COURSE_ID`) REFERENCES `tblcourses` (`COURSE_ID`);

--
-- Constraints for table `tblgrades`
--
ALTER TABLE `tblgrades`
  ADD CONSTRAINT `fk_grades_enrollment` FOREIGN KEY (`ENROLLMENT_ID`) REFERENCES `tblenrollment` (`ENROLLMENT_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_grades_student` FOREIGN KEY (`S_ID`) REFERENCES `tblstudent` (`S_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_grades_subject` FOREIGN KEY (`SUBJECT_ID`) REFERENCES `tblsubjects` (`SUBJECT_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_grades_sy` FOREIGN KEY (`SY_ID`) REFERENCES `tblschoolyear` (`SY_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblhomepage_content_blocks`
--
ALTER TABLE `tblhomepage_content_blocks`
  ADD CONSTRAINT `fk_block_section` FOREIGN KEY (`SECTION_ID`) REFERENCES `tblhomepage_sections` (`SECTION_ID`) ON DELETE CASCADE;

--
-- Constraints for table `tblmajors`
--
ALTER TABLE `tblmajors`
  ADD CONSTRAINT `fk_majors_course` FOREIGN KEY (`COURSE_ID`) REFERENCES `tblcourses` (`COURSE_ID`);

--
-- Constraints for table `tblnews`
--
ALTER TABLE `tblnews`
  ADD CONSTRAINT `fk_news_createdby` FOREIGN KEY (`CREATED_BY`) REFERENCES `tblusers` (`UID`);

--
-- Constraints for table `tblnews_media`
--
ALTER TABLE `tblnews_media`
  ADD CONSTRAINT `fk_media_news` FOREIGN KEY (`NEWS_ID`) REFERENCES `tblnews` (`NEWS_ID`) ON DELETE CASCADE;

--
-- Constraints for table `tblnotifications`
--
ALTER TABLE `tblnotifications`
  ADD CONSTRAINT `fk_notif_user` FOREIGN KEY (`UID`) REFERENCES `tblusers` (`UID`);

--
-- Constraints for table `tblparents`
--
ALTER TABLE `tblparents`
  ADD CONSTRAINT `fk_parent_applicant` FOREIGN KEY (`APPLICANT_ID`) REFERENCES `tblapplicants` (`APPLICANT_ID`),
  ADD CONSTRAINT `fk_parent_student` FOREIGN KEY (`S_ID`) REFERENCES `tblstudent` (`S_ID`);

--
-- Constraints for table `tblpayments`
--
ALTER TABLE `tblpayments`
  ADD CONSTRAINT `fk_payments_enrollment` FOREIGN KEY (`ENROLLMENT_ID`) REFERENCES `tblenrollment` (`ENROLLMENT_ID`),
  ADD CONSTRAINT `fk_payments_receivedby` FOREIGN KEY (`RECEIVED_BY`) REFERENCES `tblusers` (`UID`);

--
-- Constraints for table `tblsections`
--
ALTER TABLE `tblsections`
  ADD CONSTRAINT `fk_sections_course` FOREIGN KEY (`COURSE_ID`) REFERENCES `tblcourses` (`COURSE_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sections_sy` FOREIGN KEY (`SY_ID`) REFERENCES `tblschoolyear` (`SY_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblstudent`
--
ALTER TABLE `tblstudent`
  ADD CONSTRAINT `fk_student_addedby` FOREIGN KEY (`AddedBy`) REFERENCES `tblusers` (`UID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_student_applicant` FOREIGN KEY (`APPLICANT_ID`) REFERENCES `tblapplicants` (`APPLICANT_ID`),
  ADD CONSTRAINT `fk_student_course` FOREIGN KEY (`COURSE_ID`) REFERENCES `tblcourses` (`COURSE_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_student_major` FOREIGN KEY (`MAJOR_ID`) REFERENCES `tblmajors` (`MAJOR_ID`);

--
-- Constraints for table `tblsubjects`
--
ALTER TABLE `tblsubjects`
  ADD CONSTRAINT `fk_subjects_course` FOREIGN KEY (`COURSE_ID`) REFERENCES `tblcourses` (`COURSE_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblsubject_prerequisites`
--
ALTER TABLE `tblsubject_prerequisites`
  ADD CONSTRAINT `fk_prereq_prereq` FOREIGN KEY (`PREREQ_SUBJECT_ID`) REFERENCES `tblsubjects` (`SUBJECT_ID`),
  ADD CONSTRAINT `fk_prereq_subject` FOREIGN KEY (`SUBJECT_ID`) REFERENCES `tblsubjects` (`SUBJECT_ID`);

--
-- Constraints for table `tblteacher_subjects`
--
ALTER TABLE `tblteacher_subjects`
  ADD CONSTRAINT `fk_ts_section` FOREIGN KEY (`SECTION_ID`) REFERENCES `tblsections` (`SECTION_ID`),
  ADD CONSTRAINT `fk_ts_subject` FOREIGN KEY (`SUBJECT_ID`) REFERENCES `tblsubjects` (`SUBJECT_ID`),
  ADD CONSTRAINT `fk_ts_sy` FOREIGN KEY (`SY_ID`) REFERENCES `tblschoolyear` (`SY_ID`),
  ADD CONSTRAINT `fk_ts_user` FOREIGN KEY (`UID`) REFERENCES `tblusers` (`UID`);

--
-- Constraints for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD CONSTRAINT `fk_users_usertype` FOREIGN KEY (`TYPEID`) REFERENCES `tblusertype` (`TYPEID`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
