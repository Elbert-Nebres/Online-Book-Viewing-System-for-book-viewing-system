-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 30, 2023 at 04:45 PM
-- Server version: 8.0.34
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_nt3102`
--

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `EventManager`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `EventManager` (IN `eventNameVal` VARCHAR(255), IN `eventDescVal` VARCHAR(255), IN `eventDateVal` TIMESTAMP, IN `orgIDVal` INT)   BEGIN
	SET @statusID = 1;
    INSERT INTO events (eventName,eventDesc,e_date,org_ID,statusID) VALUES (eventNameVal,eventDescVal,eventDateVal,orgIDVal,@statusID);
    SET @eventID = LAST_INSERT_ID();
    SET @superID = orgIDVal;
    INSERT INTO eventrecords(eventID,superID) VALUES ( @eventID, orgIDVal);
END$$

DROP PROCEDURE IF EXISTS `RegisterModerator`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RegisterModerator` (IN `org_namevalue` VARCHAR(255), IN `passwordvalue` VARCHAR(255), IN `usernamevalue` VARCHAR(255), IN `deptIDvalue` INT)   BEGIN
	SET @salt = (SUBSTRING(MD5(RAND()), 1, 10));
    SET @password = SHA2(CONCAT(passwordvalue,@salt),256);
    INSERT INTO superusers (username,password,salt) VALUES (usernamevalue,@password,@salt);
    SET @superID = LAST_INSERT_ID();
    
    INSERT INTO organization (dept_ID,org_name,superID) VALUES ( deptIDvalue, org_namevalue,@superID);
END$$

DROP PROCEDURE IF EXISTS `registerStudents`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `registerStudents` (IN `sr_codeIN` VARCHAR(10), IN `firstnameIN` VARCHAR(255), IN `lastnameIN` VARCHAR(255))   BEGIN
    INSERT INTO tb_studentinfo(lastname, firstname, course) VALUES (lastnameIN, firstnameIN, 'BSIT'); 
    SET @studid = LAST_INSERT_ID();
    INSERT INTO students(sr_code, courseID, year, section, stud_id) VALUES (sr_codeIN, 6, '3rd', 'NT-3102', @studid);
    SET @salt =  SUBSTRING(MD5(RAND()) FROM 1 FOR 10);
	SET @password = SHA2(CONCAT(sr_codeIN,@salt),256);
	INSERT INTO userstudents(sr_code,password,salt) values(sr_codeIN,@password,@salt);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `add_stocks`
--

DROP TABLE IF EXISTS `add_stocks`;
CREATE TABLE IF NOT EXISTS `add_stocks` (
  `Product_ID` int NOT NULL AUTO_INCREMENT,
  `Quantity` int NOT NULL,
  `Transaction_No` int NOT NULL,
  `Date` date NOT NULL,
  `empid` int NOT NULL,
  PRIMARY KEY (`Product_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empid` int NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `empid`, `username`, `password`) VALUES
(1, 1, 'Admin', 'admin123'),
(2, 2, 'Librarian', 'librarian123');

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

DROP TABLE IF EXISTS `administrator`;
CREATE TABLE IF NOT EXISTS `administrator` (
  `administratorID` int NOT NULL,
  `empid` int NOT NULL,
  `administratorPassword` text,
  PRIMARY KEY (`administratorID`),
  KEY `fk_empid` (`empid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`administratorID`, `empid`, `administratorPassword`) VALUES
(1, 1001, 'password1'),
(2, 1002, 'password2'),
(3, 1003, 'password3');

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

DROP TABLE IF EXISTS `announcement`;
CREATE TABLE IF NOT EXISTS `announcement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `announcement` varchar(10000) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `atendees_view`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `atendees_view`;
CREATE TABLE IF NOT EXISTS `atendees_view` (
`userID` int
,`stud_deptid` int
,`eventName` varchar(50)
,`eventDesc` varchar(50)
,`department_Name` varchar(100)
,`event_deptid` int
,`org_Name` varchar(255)
,`attendeeID` int
,`eventID` int
,`DateRegistered` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
CREATE TABLE IF NOT EXISTS `attendance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sr_code` int NOT NULL,
  `studid` int NOT NULL,
  `date` date NOT NULL,
  `time_in` time NOT NULL,
  `status` int NOT NULL,
  `time_out` time NOT NULL,
  `num_hr` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_tbl`
--

DROP TABLE IF EXISTS `attendance_tbl`;
CREATE TABLE IF NOT EXISTS `attendance_tbl` (
  `AttendanceID` int NOT NULL AUTO_INCREMENT,
  `FacultyID` int NOT NULL,
  `studid` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `AttendanceDate` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `ClassSection` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `Room` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `TimeStart` time(6) NOT NULL,
  `TimeEnd` time(6) NOT NULL,
  PRIMARY KEY (`AttendanceID`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `attendance_tbl`
--

INSERT INTO `attendance_tbl` (`AttendanceID`, `FacultyID`, `studid`, `AttendanceDate`, `ClassSection`, `Room`, `TimeStart`, `TimeEnd`) VALUES
(31, 1, '3', '2023-11-25', 'BSCS-NT-1102', 'HEB 402', '12:00:00.000000', '16:00:00.000000'),
(32, 1, '1', '2023-11-25', 'BSCS-NT-1102', 'HEB 402', '12:00:00.000000', '16:00:00.000000');

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

DROP TABLE IF EXISTS `author`;
CREATE TABLE IF NOT EXISTS `author` (
  `authorID` int NOT NULL AUTO_INCREMENT,
  `authorFn` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `authorLn` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`authorID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `author`
--

INSERT INTO `author` (`authorID`, `authorFn`, `authorLn`) VALUES
(1, 'Aia', 'Amare'),
(2, 'Fulgur', 'Ovid'),
(3, 'John', 'Doe'),
(4, 'Milie', 'Parfait'),
(5, 'Johns', 'Doe'),
(6, 'John', 'Doo'),
(7, 'millie', 'Parfait'),
(8, 'Enna', 'alouette'),
(10, 'Enna ', 'alouette');

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

DROP TABLE IF EXISTS `book`;
CREATE TABLE IF NOT EXISTS `book` (
  `bookID` int NOT NULL AUTO_INCREMENT,
  `authorID` int DEFAULT NULL,
  `publisherID` int DEFAULT NULL,
  `bookTitle` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `ISBN` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `genre` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `publishDate` date DEFAULT NULL,
  PRIMARY KEY (`bookID`),
  KEY `authorID` (`authorID`),
  KEY `publisherID` (`publisherID`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`bookID`, `authorID`, `publisherID`, `bookTitle`, `ISBN`, `description`, `genre`, `publishDate`) VALUES
(9, 3, 2, 'Song Bird', '2432', 'enna is cute', 'Science Fiction', '2023-11-16'),
(10, 10, 1, 'Song Bird', '2432', 'dwada', 'Biography', '2023-11-18'),
(11, 3, 11, 'Song Bird', '2432', 'koko', 'Fiction', '2023-11-25'),
(2, 3, 2, 'Legatus 404', '0-1260-6478-4', 'A Cyborg from the future with unparalleled strength. His body is partly cold steel, and who is to say if he has a heart', 'Fiction', '2023-02-02'),
(3, 4, 3, 'though Doe', '0-4319-1393-5', 'A typical AI generated name', 'Comedy', '2023-03-03'),
(5, 6, 1, 'Flat is justice', '32141', 'A monster cat creeping through hell. A bit aggressive, but maybe she just needs some attention.[1]', 'Fantasy', '2023-01-01'),
(8, 8, 3, 'Song Bird', '2432', 'A blue bird fluttering in the heavens. She sings a love song for the souls of the living', 'Fantasy', '2023-11-01'),
(12, 10, 4, 'Song Bird', '2432', 'dawad', 'Mystery/Thriller', '2023-11-14');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

DROP TABLE IF EXISTS `course`;
CREATE TABLE IF NOT EXISTS `course` (
  `courseID` int NOT NULL AUTO_INCREMENT,
  `courseName` text NOT NULL,
  `dept_ID` int NOT NULL,
  PRIMARY KEY (`courseID`),
  KEY `dept_ID` (`dept_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`courseID`, `courseName`, `dept_ID`) VALUES
(1, 'Bachelor of Science in Business Administration', 1),
(2, 'Bachelor of Science in Management Accounting', 1),
(3, 'Bachelor of Science in Psychology', 2),
(4, 'Bachelor of Arts in Communication', 2),
(5, 'Bachelor of Industrial Technology', 3),
(6, 'Bachelor of Science in Information Technology', 4),
(7, 'Bachelor of Science in Computer Science', 4),
(8, 'Bachelor of Secondary Education', 5),
(9, 'Bachelor of Science in Industrial Engineering ', 6);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
CREATE TABLE IF NOT EXISTS `department` (
  `dept_ID` int NOT NULL AUTO_INCREMENT,
  `department_Name` varchar(100) NOT NULL,
  PRIMARY KEY (`dept_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`dept_ID`, `department_Name`) VALUES
(0, 'General Department'),
(1, 'CABEIHM'),
(2, 'CAS'),
(3, 'CIT'),
(4, 'CICS'),
(5, 'CTE'),
(6, 'CE');

-- --------------------------------------------------------

--
-- Table structure for table `emp_data`
--

DROP TABLE IF EXISTS `emp_data`;
CREATE TABLE IF NOT EXISTS `emp_data` (
  `empid` int NOT NULL AUTO_INCREMENT,
  `empCode` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `User_Type` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `User_Email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `User_Password` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`empid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eventattendees`
--

DROP TABLE IF EXISTS `eventattendees`;
CREATE TABLE IF NOT EXISTS `eventattendees` (
  `attendeeID` int NOT NULL AUTO_INCREMENT,
  `eventID` int NOT NULL,
  `sr_code` varchar(11) NOT NULL,
  `DateRegistered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`attendeeID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `eventattendees`
--

INSERT INTO `eventattendees` (`attendeeID`, `eventID`, `sr_code`, `DateRegistered`) VALUES
(1, 1, '21-33273', '2023-11-20 01:28:13');

-- --------------------------------------------------------

--
-- Table structure for table `eventrecords`
--

DROP TABLE IF EXISTS `eventrecords`;
CREATE TABLE IF NOT EXISTS `eventrecords` (
  `recordID` int NOT NULL AUTO_INCREMENT,
  `eventID` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) NOT NULL,
  `superID` int NOT NULL,
  PRIMARY KEY (`recordID`),
  KEY `superID` (`superID`),
  KEY `statusID` (`eventID`(250)),
  KEY `fk_eventID` (`eventID`(250))
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `eventrecords`
--

INSERT INTO `eventrecords` (`recordID`, `eventID`, `remarks`, `superID`) VALUES
(1, '1', 'NA', 1);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `eventID` int NOT NULL AUTO_INCREMENT,
  `eventName` varchar(50) NOT NULL,
  `eventDesc` varchar(50) NOT NULL,
  `org_ID` int NOT NULL,
  `statusID` int NOT NULL,
  `e_date` datetime NOT NULL,
  PRIMARY KEY (`eventID`),
  KEY `org_ID` (`org_ID`),
  KEY `statusID` (`statusID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`eventID`, `eventName`, `eventDesc`, `org_ID`, `statusID`, `e_date`) VALUES
(1, 'sample', 'sample', 1, 1, '2023-11-19 09:32:12'),
(2, 'sample2', 'sample3', 1, 1, '2023-11-19 13:44:43');

-- --------------------------------------------------------

--
-- Table structure for table `eventstatus`
--

DROP TABLE IF EXISTS `eventstatus`;
CREATE TABLE IF NOT EXISTS `eventstatus` (
  `statusID` int NOT NULL AUTO_INCREMENT,
  `status` varchar(255) NOT NULL,
  PRIMARY KEY (`statusID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `eventstatus`
--

INSERT INTO `eventstatus` (`statusID`, `status`) VALUES
(1, 'Pending'),
(2, 'Approved'),
(3, 'Cancelled');

-- --------------------------------------------------------

--
-- Stand-in structure for view `event_info`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `event_info`;
CREATE TABLE IF NOT EXISTS `event_info` (
`statusID` int
,`status` varchar(255)
,`eventID` int
,`eventName` varchar(50)
,`eventDesc` varchar(50)
,`org_ID` int
,`e_date` datetime
,`department_Name` varchar(100)
,`dept_ID` int
,`org_Name` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_tbl`
--

DROP TABLE IF EXISTS `faculty_tbl`;
CREATE TABLE IF NOT EXISTS `faculty_tbl` (
  `FacultyID` int NOT NULL AUTO_INCREMENT,
  `empid` int NOT NULL,
  `FacultyUsername` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `FacultyPassword` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`FacultyID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `faculty_tbl`
--

INSERT INTO `faculty_tbl` (`FacultyID`, `empid`, `FacultyUsername`, `FacultyPassword`) VALUES
(1, 1, 'Jennifer Reyes', 'jenniferreyes123');

-- --------------------------------------------------------

--
-- Table structure for table `librarian`
--

DROP TABLE IF EXISTS `librarian`;
CREATE TABLE IF NOT EXISTS `librarian` (
  `libID` int NOT NULL AUTO_INCREMENT,
  `empid` int NOT NULL,
  `librarianPassword` varchar(25) NOT NULL,
  PRIMARY KEY (`libID`),
  KEY `fk_empid_librarian` (`empid`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `librarian`
--

INSERT INTO `librarian` (`libID`, `empid`, `librarianPassword`) VALUES
(4, 1004, 'password4'),
(5, 1005, 'password5'),
(6, 1006, 'password6');

-- --------------------------------------------------------

--
-- Table structure for table `lost_items`
--

DROP TABLE IF EXISTS `lost_items`;
CREATE TABLE IF NOT EXISTS `lost_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `item_number` varchar(255) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `date_found` date NOT NULL,
  `date_claimed` date DEFAULT NULL,
  `Userid` int DEFAULT NULL,
  `StudentId` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`Userid`),
  KEY `student_id` (`StudentId`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lost_items`
--

INSERT INTO `lost_items` (`id`, `item_number`, `item_name`, `date_found`, `date_claimed`, `Userid`, `StudentId`) VALUES
(1, '000001', 'Cellphone', '2023-11-13', '2023-11-24', NULL, NULL),
(2, '000002', 'Ballpen', '2023-11-14', '2023-11-24', NULL, NULL),
(3, '000003', 'Book', '2023-11-14', '2023-11-24', NULL, NULL),
(4, '000004', 'Pencil', '2023-11-15', '2023-11-24', NULL, NULL),
(5, '000005', 'Bag', '2023-11-17', '2023-11-24', NULL, NULL),
(6, '000006', 'Money', '2023-11-17', '2023-11-24', NULL, NULL),
(7, '000007', 'Shoes', '2023-11-18', '2023-11-24', NULL, NULL),
(8, '000008', 'Ballpen', '2023-11-18', '2023-11-24', NULL, NULL),
(9, '000009', 'Wallet', '2023-11-19', '2023-11-24', NULL, NULL),
(10, '000010', 'ID Lace', '2023-11-20', '2023-11-25', NULL, NULL),
(11, '000011', 'Shades', '2023-11-20', NULL, NULL, NULL),
(12, '000012', 'Key', '2023-11-21', NULL, NULL, NULL),
(13, '000013', 'ID', '2023-11-21', NULL, NULL, NULL),
(14, '000014', 'Money', '2023-11-21', NULL, NULL, NULL),
(15, '000015', 'Headphone', '2023-11-22', NULL, NULL, NULL),
(16, '000016', 'Cellphone', '2023-11-22', NULL, NULL, NULL),
(17, '000017', 'Handkerchief', '2023-11-22', NULL, NULL, NULL),
(18, '000018', 'Adaptor', '2023-11-25', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `moderatorcookies`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `moderatorcookies`;
CREATE TABLE IF NOT EXISTS `moderatorcookies` (
`superID` int
,`userName` varchar(255)
,`password` varchar(255)
,`salt` varchar(10)
,`org_ID` int
,`dept_ID` int
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `moderators`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `moderators`;
CREATE TABLE IF NOT EXISTS `moderators` (
`superID` int
,`username` varchar(255)
,`org_Name` varchar(255)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `notifications`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
`statusID` int
,`status` varchar(255)
,`eventID` int
,`eventName` varchar(50)
,`eventDesc` varchar(50)
,`org_ID` int
,`e_date` datetime
,`department_Name` varchar(100)
,`dept_ID` int
,`org_Name` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `organization`
--

DROP TABLE IF EXISTS `organization`;
CREATE TABLE IF NOT EXISTS `organization` (
  `org_ID` int NOT NULL AUTO_INCREMENT,
  `dept_ID` int NOT NULL,
  `org_Name` varchar(255) NOT NULL,
  `superID` int NOT NULL,
  PRIMARY KEY (`org_ID`),
  KEY `superID` (`superID`),
  KEY `dept_ID` (`dept_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `organization`
--

INSERT INTO `organization` (`org_ID`, `dept_ID`, `org_Name`, `superID`) VALUES
(1, 1, 'Junior Philippine Association of Management Accountants', 1),
(2, 1, 'Junior Marketing Executives', 2),
(3, 1, 'College of Accountancy, Business and Economics Council', 3),
(4, 1, 'Public Administration Student Association', 4),
(5, 1, 'Association Of Operation Management Students', 5),
(6, 1, 'Young People Management Association of the Philippines', 6),
(7, 2, 'Association of College of Arts and Sciences Students', 7),
(8, 2, 'Circle of Psychology Students', 8),
(9, 2, 'Poderoso Communicador Sociedad', 9),
(10, 3, 'Alliance of Industrial Technology Students', 10),
(11, 3, 'CTRL+A', 11),
(12, 4, 'Junior Philippine Computer Society - Lipa Chapter', 12),
(13, 4, 'Tech Innovators Society', 13),
(14, 5, 'Aspiring Future Educators Guild', 14),
(15, 5, 'Language Educators Association', 15),
(16, 6, 'Junior Philippine Institute of Industrial Engineers', 16),
(17, 0, 'Red Spartan Sports Council', 17),
(18, 0, 'Supreme Student Council Lipa Campus', 18);

-- --------------------------------------------------------

--
-- Table structure for table `out_stocks`
--

DROP TABLE IF EXISTS `out_stocks`;
CREATE TABLE IF NOT EXISTS `out_stocks` (
  `Product_ID` int NOT NULL AUTO_INCREMENT,
  `SoldStocks` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Transaction_No` int NOT NULL,
  `Date` date NOT NULL,
  `empid` int NOT NULL,
  PRIMARY KEY (`Product_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `Product_ID` int NOT NULL AUTO_INCREMENT,
  `Category_Name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Product_Name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Description` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Price` int NOT NULL,
  `image` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`Product_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `publisher`
--

DROP TABLE IF EXISTS `publisher`;
CREATE TABLE IF NOT EXISTS `publisher` (
  `publisherID` int NOT NULL AUTO_INCREMENT,
  `publisherName` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `publisherAddress` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`publisherID`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `publisher`
--

INSERT INTO `publisher` (`publisherID`, `publisherName`, `publisherAddress`) VALUES
(1, 'ABC Production', 'Cuenca, Batangas'),
(2, 'Victory', 'Lipa City, Batangas'),
(3, 'Modico', 'BiÃ±an, Laguna'),
(4, 'City Publishers', '321 Maple St, City4, Country'),
(5, 'Metro Print House', '555 Pine St, City5, Country'),
(6, 'Sunny Publications', '777 Cedar St, City6, Country'),
(7, 'Valley Books', '999 Birch St, City7, Country'),
(8, 'Summit Press', '234 Spruce St, City8, Country'),
(9, 'Harbor Publishing', '876 Fir St, City9, Country'),
(10, 'Meadow Books Inc', '543 Redwood St, City10, Country'),
(11, '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `security_lostnfound`
--

DROP TABLE IF EXISTS `security_lostnfound`;
CREATE TABLE IF NOT EXISTS `security_lostnfound` (
  `UserId` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` enum('admin','security') DEFAULT 'security',
  `usersign` varchar(50) NOT NULL,
  PRIMARY KEY (`UserId`)
) ENGINE=MyISAM AUTO_INCREMENT=2126 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `security_lostnfound`
--

INSERT INTO `security_lostnfound` (`UserId`, `username`, `password`, `role`, `usersign`) VALUES
(1010, 'Admin_Sd', 'adminsd', 'admin', 'Seurity Department'),
(2125, '2125', '2125', 'security', 'Security_G0');

-- --------------------------------------------------------

--
-- Stand-in structure for view `studentinfoview`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `studentinfoview`;
CREATE TABLE IF NOT EXISTS `studentinfoview` (
`userID` int
,`sr_code` varchar(250)
,`password` varchar(255)
,`salt` varchar(10)
,`firstName` varchar(25)
,`lastName` varchar(25)
,`courseID` int
,`year` varchar(255)
,`section` varchar(250)
,`courseName` text
,`dept_ID` int
,`department_Name` varchar(100)
);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `sr_code` varchar(250) NOT NULL,
  `courseID` int NOT NULL,
  `year` varchar(255) NOT NULL,
  `section` varchar(250) NOT NULL,
  `stud_id` int NOT NULL,
  PRIMARY KEY (`sr_code`),
  KEY `courseID` (`courseID`),
  KEY `stud_id` (`stud_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`sr_code`, `courseID`, `year`, `section`, `stud_id`) VALUES
('21-33273', 6, '3rd', 'NT-3102', 1),
('21-38474', 6, '3rd', 'NT-3102', 2),
('21-32782', 6, '3rd', 'NT-3102', 3),
('21-36452', 6, '3rd', 'NT-3102', 4),
('21-35509', 6, '3rd', 'NT-3102', 5),
('21-31630', 6, '3rd', 'NT-3102', 6),
('21-30506', 6, '3rd', 'NT-3102', 7),
('21-36155', 6, '3rd', 'NT-3102', 8),
('22-35794', 6, '3rd', 'NT-3102', 9),
('21-35078', 6, '3rd', 'NT-3102', 10),
('21-30802', 6, '3rd', 'NT-3102', 11),
('21-34772', 6, '3rd', 'NT-3102', 12),
('21-36111', 6, '3rd', 'NT-3102', 13),
('21-30320', 6, '3rd', 'NT-3102', 14),
('21-36991', 6, '3rd', 'NT-3102', 15),
('21-37287', 6, '3rd', 'NT-3102', 16),
('21-32548', 6, '3rd', 'NT-3102', 17),
('21-37831', 6, '3rd', 'NT-3102', 18),
('21-37178', 6, '3rd', 'NT-3102', 19),
('21-30812', 6, '3rd', 'NT-3102', 20),
('21-34330', 6, '3rd', 'NT-3102', 21),
('21-32259', 6, '3rd', 'NT-3102', 22),
('21-33470', 6, '3rd', 'NT-3102', 23),
('21-37046', 6, '3rd', 'NT-3102', 24),
('21-36999', 6, '3rd', 'NT-3102', 25),
('21-34053', 6, '3rd', 'NT-3102', 26),
('21-33053', 6, '3rd', 'NT-3102', 27);

-- --------------------------------------------------------

--
-- Table structure for table `students_tbl`
--

DROP TABLE IF EXISTS `students_tbl`;
CREATE TABLE IF NOT EXISTS `students_tbl` (
  `id` int NOT NULL AUTO_INCREMENT,
  `studid` int NOT NULL,
  `sr_code` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `students_tbl`
--

INSERT INTO `students_tbl` (`id`, `studid`, `sr_code`) VALUES
(1, 1, '21-34990'),
(2, 2, '21-35881'),
(3, 3, '21-36992'),
(4, 4, '21-56882');

-- --------------------------------------------------------

--
-- Table structure for table `student_lostnfound`
--

DROP TABLE IF EXISTS `student_lostnfound`;
CREATE TABLE IF NOT EXISTS `student_lostnfound` (
  `StudentId` int NOT NULL AUTO_INCREMENT,
  `Sr_code` varchar(191) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`StudentId`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `student_lostnfound`
--

INSERT INTO `student_lostnfound` (`StudentId`, `Sr_code`, `password`) VALUES
(1, '21-36991', '21-36991'),
(2, '21-34551', '21-34551');

-- --------------------------------------------------------

--
-- Table structure for table `student_tbl`
--

DROP TABLE IF EXISTS `student_tbl`;
CREATE TABLE IF NOT EXISTS `student_tbl` (
  `StudentID` int NOT NULL AUTO_INCREMENT,
  `studid` int NOT NULL,
  `StudentQR` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`StudentID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `student_tbl`
--

INSERT INTO `student_tbl` (`StudentID`, `studid`, `StudentQR`) VALUES
(1, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzcmNvZGUiOiIyMS0zNjk5OSIsImZ1bGxuYW1lIjoiVkFMREVaLCBGUllBTiBBVVJJQyBMLiIsInRpbWVzdGFtcCI6IjIwMjMtMTEtMTYgMjI6MDA6MTUiLCJ0eXBlIjoiU1RVREVOVCIsInVzZXJpZCI6IjIxLTM2OTk5In0.Zvx0BjtFexJ1dKbr295nGIUDCA9vZ44yqmdoBBw7rfc'),
(2, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzcmNvZGUiOiIyMS0zNjQ1MiIsImZ1bGxuYW1lIjoiQkFZQkFZLCBFTU1BTlVFTCBULiIsInRpbWVzdGFtcCI6IjIwMjMtMTEtMjQgMTY6NDE6NDAiLCJ0eXBlIjoiU1RVREVOVCIsInVzZXJpZCI6IjIxLTM2NDUyIn0.PJOlVBipbUwubXYYsoC2leHBinMpTBces1s3VkQUGNE'),
(3, 3, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzcmNvZGUiOiIyMS0zNjExMSIsImZ1bGxuYW1lIjoiTEFUT1JSRSwgSk9ITiBBRVJPTiBELiIsInRpbWVzdGFtcCI6IjIwMjMtMTEtMTYgMjE6NTA6MDQiLCJ0eXBlIjoiU1RVREVOVCIsInVzZXJpZCI6IjIxLTM2MTExIn0.qVMtyeO9V_qiWnM9lJe8fNT9NnZPaAYVDTFgdyAstYo');

-- --------------------------------------------------------

--
-- Stand-in structure for view `stud_atendees_view`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `stud_atendees_view`;
CREATE TABLE IF NOT EXISTS `stud_atendees_view` (
`userID` int
,`dept_ID` int
,`stud_dept` varchar(100)
,`statusID` int
,`status` varchar(255)
,`stud_deptid` int
,`org_ID` int
,`e_date` datetime
,`attendeeID` int
,`eventID` int
,`DateRegistered` timestamp
,`sr_code` varchar(250)
);

-- --------------------------------------------------------

--
-- Table structure for table `stud_data`
--

DROP TABLE IF EXISTS `stud_data`;
CREATE TABLE IF NOT EXISTS `stud_data` (
  `studid` int NOT NULL AUTO_INCREMENT,
  `srCode` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `User_Type` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `User_Email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `User_Password` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`studid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `superusers`
--

DROP TABLE IF EXISTS `superusers`;
CREATE TABLE IF NOT EXISTS `superusers` (
  `superID` int NOT NULL AUTO_INCREMENT,
  `userName` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(10) NOT NULL,
  PRIMARY KEY (`superID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `superusers`
--

INSERT INTO `superusers` (`superID`, `userName`, `password`, `salt`) VALUES
(0, 'adminval', '95e1d817e753fe6392b68de89c337e4cbcf63d280e77245d5c7a3fc4938863d9', 'kckKVzx9k1'),
(1, 'JPAMA', '3913342ed7e247f86dc7bf83229b90a0cec7d8f7f9a6851927f7becc7fec9035', 'b79dc59cf1'),
(2, 'JME', '18d7d94a8343f46b943d963da0d1b8bb42520ba84d4329280be02e5c542a9ee4', 'fc39983032'),
(3, 'CABE Council', '423a577e2d08ee38ad7969840840efd3ebc0adde65ccce896eb93c3d2c491fc8', '02eb527701');

-- --------------------------------------------------------

--
-- Table structure for table `tbemployee`
--

DROP TABLE IF EXISTS `tbemployee`;
CREATE TABLE IF NOT EXISTS `tbemployee` (
  `empid` int NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `firstname` varchar(25) NOT NULL,
  `department` varchar(20) NOT NULL,
  PRIMARY KEY (`empid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbemployee`
--

INSERT INTO `tbemployee` (`empid`, `lastname`, `firstname`, `department`) VALUES
(1, 'Reyes', 'Jennifer', 'BSIT'),
(1001, 'Dela Cruz', 'Juan', 'BSIT'),
(1002, 'Santos', 'Maria', 'BSCS'),
(1003, 'Reyes', 'Pedro', 'BSIS'),
(1004, 'Doe', 'John', 'Librarian'),
(1005, 'Smith', 'Jane', 'Librarian'),
(1006, 'Johnson', 'Bob', 'Librarian'),
(1010, 'Admin_Sd', 'Admin_Sd', 'Security Department'),
(2125, '2125', 'Jose2125', 'Security Department');

-- --------------------------------------------------------

--
-- Table structure for table `tb_studentinfo`
--

DROP TABLE IF EXISTS `tb_studentinfo`;
CREATE TABLE IF NOT EXISTS `tb_studentinfo` (
  `studid` int NOT NULL AUTO_INCREMENT,
  `lastname` varchar(25) NOT NULL,
  `firstname` varchar(25) NOT NULL,
  `course` varchar(20) NOT NULL,
  PRIMARY KEY (`studid`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_studentinfo`
--

INSERT INTO `tb_studentinfo` (`studid`, `lastname`, `firstname`, `course`) VALUES
(1, 'ALINSUNURIN', 'ALEISTER', 'BSIT'),
(2, 'ANUYO', 'YVAN JEFF L.', 'BSIT'),
(3, 'ARADA', 'BERNARD ANGELO E.', 'BSIT'),
(4, 'BAYBAY', 'EMMANUEL T.', 'BSIT'),
(5, 'CANUEL', 'JED MHARWAYNE P.', 'BSIT'),
(6, 'DE LA CRUZ', 'LEOMAR P.', 'BSIT'),
(7, 'DIEZ', 'ROYSHANE MARU P.', 'BSIT'),
(8, 'ESGUERRA', 'AUBREY A.', 'BSIT'),
(9, 'FIESTADA', 'CEDRICK JHON', 'BSIT'),
(10, 'GRENIAS', 'GENELLA MAE E.', 'BSIT'),
(11, 'HORARIO', 'JOHN MATTHEW I.', 'BSIT'),
(12, 'IGLE', 'MIKKO D.', 'BSIT'),
(13, 'LATORRE', 'JOHN AERON D.', 'BSIT'),
(14, 'LAYLO', 'JULIUS MELWIN D.', 'BSIT'),
(15, 'MALUPA', 'JHON MARK L.', 'BSIT'),
(16, 'MARANAN', 'DON-DON C.', 'BSIT'),
(17, 'MARANAN', 'MIKAELA A.', 'BSIT'),
(18, 'MERCADO', 'KURT DRAHCIR C.', 'BSIT'),
(19, 'NEBRES', 'ELBERT D.', 'BSIT'),
(20, 'PANALIGAN', 'JOMARI M.', 'BSIT'),
(21, 'RAMOS', 'ALLEN EIDRIAN S.', 'BSIT'),
(22, 'RITUAL', 'JUN MARK C.', 'BSIT'),
(23, 'RONGAVILLA', 'EMJAY R.', 'BSIT'),
(24, 'SALANGSANG', 'MIKO JASPER M.', 'BSIT'),
(25, 'VALDEZ', 'FRYAN AURIC L.', 'BSIT'),
(26, 'VILLANUEVA', 'KURT XAVIER L. ', 'BSIT'),
(27, 'Parfait', 'MIllie. ', 'BSIT'),
(28, 'Aluette', 'Enna', 'BSIT');

-- --------------------------------------------------------

--
-- Table structure for table `updatelog`
--

DROP TABLE IF EXISTS `updatelog`;
CREATE TABLE IF NOT EXISTS `updatelog` (
  `updateID` int NOT NULL AUTO_INCREMENT,
  `action` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `entityType` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `entityID` int DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  PRIMARY KEY (`updateID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `updatelog`
--

INSERT INTO `updatelog` (`updateID`, `action`, `entityType`, `entityID`, `timestamp`) VALUES
(1, 'Delete', 'Staff', 1, '2023-11-24 13:52:26'),
(2, 'Delete', 'User', 2, '2023-11-24 13:57:34'),
(3, 'ADD', 'Staff', 3, '2023-11-24 13:57:34'),
(4, 'Delete', 'User', 4, '2023-11-24 13:57:34'),
(5, 'ADD', 'User', 5, '2023-11-24 13:57:34'),
(6, 'ADD', 'Staff', 6, '2023-11-24 13:57:34'),
(7, 'Delete', 'User', 7, '2023-11-24 13:57:34'),
(8, 'Delete', 'Staff', 8, '2023-11-24 13:57:34'),
(9, 'Add', 'Staff', 9, '2023-11-24 13:57:34'),
(10, 'Delete', 'Staff', 10, '2023-11-24 13:57:34');

-- --------------------------------------------------------

--
-- Table structure for table `usere`
--

DROP TABLE IF EXISTS `usere`;
CREATE TABLE IF NOT EXISTS `usere` (
  `UserEID` int NOT NULL AUTO_INCREMENT,
  `empid` int NOT NULL,
  `UserE_password` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`UserEID`),
  KEY `fk_empid` (`empid`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `usere`
--

INSERT INTO `usere` (`UserEID`, `empid`, `UserE_password`) VALUES
(1, 1001, 'Emp'),
(2, 1002, 'EMp'),
(3, 1003, 'emP');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `UserSID` int NOT NULL AUTO_INCREMENT,
  `SR-Code` int NOT NULL,
  `UserS_password` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`UserSID`),
  KEY `fk_studid` (`SR-Code`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserSID`, `SR-Code`, `UserS_password`) VALUES
(1, 1, 'Student'),
(2, 2, 'Student'),
(3, 3, 'Student'),
(4, 27, 'Flat');

-- --------------------------------------------------------

--
-- Table structure for table `userstudents`
--

DROP TABLE IF EXISTS `userstudents`;
CREATE TABLE IF NOT EXISTS `userstudents` (
  `userID` int NOT NULL AUTO_INCREMENT,
  `sr_code` varchar(250) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(10) NOT NULL,
  PRIMARY KEY (`userID`),
  KEY `sr_code` (`sr_code`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `userstudents`
--

INSERT INTO `userstudents` (`userID`, `sr_code`, `password`, `salt`) VALUES
(30, '21-33273', '0edae44cf0379e411603df820b0646a770283fca95ffae0fd882437c82c3b714', '70e4fe4b9b'),
(31, '21-38474', '5fb3b0c1d0260dada41e21912487da6593a2bcaf2c99ba296d120170559be825', '9cd654fb37'),
(32, '21-32782', 'f610e2f24c5f6347362f1fd329f9ffdbd40d64bb94afa6e71c1bb40aa7d120cc', '7812a75a8d'),
(33, '21-36452', '6fa4b0bee91e329c6796b9f47f3a62d19d5d76fed2818917376d314230cf9a68', '0cd0cc2011'),
(34, '21-35509', '433e473e634a7c93224ff9325349d2c61da48582c65ebe5e88ddde07a4939edb', '48795a8aa2'),
(35, '21-31630', 'fafd4dd0c0afcac57a83d72014843f8dc84446be99be4fd7c40cf468cf75eba0', 'be05fe06b0'),
(36, '21-30506', '0a8302be471e75a9383f70feba22df5acb2794eaefa3aaf006f45636a16c19ef', 'fba459156b'),
(37, '21-36155', '83efbcebb6bef984c84734e6e132fc667bdc4cbc69b20f66c2ae4bd740445621', '52469f1c92'),
(38, '22-35794', 'ed5325636f2a5bd0349f82f205d32ae5cbd7de7718e67c68d241be58e94759d8', 'a1a7cfebf6'),
(39, '21-35078', '104271682965fce84479421d3923c0c64d86c89c2ce1b32e93fa5be4b078f1de', '45a648aa7e'),
(40, '21-30802', '89af8e54d7777ce9976469c191371dc8ec37467385631283261ae1a377f10118', '5eb5f0ff5a'),
(41, '21-34772', '865a53a3ad21e463d382a50b4cff7f4b6281aafdb7bba4c83886ddf1f7a8e60f', '9625dbc1d6'),
(42, '21-36111', '8672a942802f99331bd2cf126d2adeb180a5dcb7c8727573a13b6962e695a12d', '1f742b45c1'),
(43, '21-30320', '8890b99035577b44195de8282bb2cff1627ca52cfa55c3f2fb37de37bbf3d4d0', 'cb372718cd'),
(44, '21-36991', '3efb356ce91132832ba21c9fe6c46917a17b76782dc867b481792e8c580f9ad0', 'ed9ceefa9a'),
(45, '21-37287', '91c0a586256c4c24789780b35af89008bda05634e6fab8b9bb5496cce537b390', 'e2fb43e1cc'),
(46, '21-32548', '7cf6e420f5e6f9bbd62ae7d01da549af13271e74d53a60c2aa7e8cc7c86994d7', '41c116f790'),
(47, '21-37831', '335d7de9c03a82e079e84135378d9013b5842e5db859d6c9976480931f634030', '7fb0242fe6'),
(48, '21-37178', 'a618c1859d32d4117de06b3f936678a315f60dba7bd4d9457e411eb94fbb32dc', '2c7d59f0c9'),
(49, '21-30812', '0337a3af9066af961df42446761f97403f3a1efa0aa42b0fed60d3817d4428a5', 'dae885950d'),
(50, '21-34330', 'c60a726a77d4494face9b109591b2630479dd04c6ded7d0ac2b25f40028ed737', 'a11c8c6f36'),
(51, '21-32259', '3ebdb44e63f127b4bda1c6987923c13bad7eef66b51dff1226c42dc2d591e228', '3318e4b8d4'),
(52, '21-33470', '0d3a6f40841c2d6ceed32c4ac718534ba504c8317b175de1d0bf456c91dc7d54', '6b7dc8fb45'),
(53, '21-37046', 'f2b68eb51f745b40a492bf2c6f59525a70a6550ada54fb3d1ca35f9734ec1905', '14eee6687e'),
(54, '21-36999', '499a28016be3f9e88d3a162decc06925c4baf7ff8a45ddba777321c0ac9a3a10', '5135aa856a'),
(55, '21-34053', '294bafdd92a592a10a2b719d1dadf7292fdeec18792d2f62994b80a53a864d5f', 'f4e1f515bb'),
(56, '21-33053', '6d7d8fe9f0e2ee19410e1b0dd267709275e28d2122b986c7d86cc0694dcdad8c', '4306fb59c3');

-- --------------------------------------------------------

--
-- Structure for view `atendees_view`
--
DROP TABLE IF EXISTS `atendees_view`;

DROP VIEW IF EXISTS `atendees_view`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `atendees_view`  AS SELECT `studentinfoview`.`userID` AS `userID`, `event_info`.`eventID` AS `stud_deptid`, `event_info`.`eventName` AS `eventName`, `event_info`.`eventDesc` AS `eventDesc`, `event_info`.`department_Name` AS `department_Name`, `event_info`.`dept_ID` AS `event_deptid`, `event_info`.`org_Name` AS `org_Name`, `eventattendees`.`attendeeID` AS `attendeeID`, `eventattendees`.`eventID` AS `eventID`, `eventattendees`.`DateRegistered` AS `DateRegistered` FROM ((`eventattendees` join `event_info` on((`event_info`.`eventID` = `eventattendees`.`eventID`))) join `studentinfoview` on((`studentinfoview`.`sr_code` = `eventattendees`.`sr_code`))) GROUP BY `eventattendees`.`attendeeID`, `eventattendees`.`eventID``eventID`  ;

-- --------------------------------------------------------

--
-- Structure for view `event_info`
--
DROP TABLE IF EXISTS `event_info`;

DROP VIEW IF EXISTS `event_info`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `event_info`  AS SELECT `eventstatus`.`statusID` AS `statusID`, `eventstatus`.`status` AS `status`, `events`.`eventID` AS `eventID`, `events`.`eventName` AS `eventName`, `events`.`eventDesc` AS `eventDesc`, `events`.`org_ID` AS `org_ID`, `events`.`e_date` AS `e_date`, `department`.`department_Name` AS `department_Name`, `organization`.`dept_ID` AS `dept_ID`, `organization`.`org_Name` AS `org_Name` FROM (((`eventstatus` join `events` on((`eventstatus`.`statusID` = `events`.`statusID`))) join `organization` on((`organization`.`org_ID` = `events`.`org_ID`))) join `department` on((`department`.`dept_ID` = `organization`.`dept_ID`)))  ;

-- --------------------------------------------------------

--
-- Structure for view `moderatorcookies`
--
DROP TABLE IF EXISTS `moderatorcookies`;

DROP VIEW IF EXISTS `moderatorcookies`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `moderatorcookies`  AS SELECT `superusers`.`superID` AS `superID`, `superusers`.`userName` AS `userName`, `superusers`.`password` AS `password`, `superusers`.`salt` AS `salt`, `organization`.`org_ID` AS `org_ID`, `department`.`dept_ID` AS `dept_ID` FROM ((`superusers` join `organization` on((`superusers`.`superID` = `organization`.`superID`))) join `department` on((`department`.`dept_ID` = `organization`.`dept_ID`)))  ;

-- --------------------------------------------------------

--
-- Structure for view `moderators`
--
DROP TABLE IF EXISTS `moderators`;

DROP VIEW IF EXISTS `moderators`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `moderators`  AS SELECT `superusers`.`superID` AS `superID`, `superusers`.`userName` AS `username`, `organization`.`org_Name` AS `org_Name` FROM (`organization` join `superusers` on((`organization`.`superID` = `superusers`.`superID`)))  ;

-- --------------------------------------------------------

--
-- Structure for view `notifications`
--
DROP TABLE IF EXISTS `notifications`;

DROP VIEW IF EXISTS `notifications`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `notifications`  AS SELECT `event_info`.`statusID` AS `statusID`, `event_info`.`status` AS `status`, `event_info`.`eventID` AS `eventID`, `event_info`.`eventName` AS `eventName`, `event_info`.`eventDesc` AS `eventDesc`, `event_info`.`org_ID` AS `org_ID`, `event_info`.`e_date` AS `e_date`, `event_info`.`department_Name` AS `department_Name`, `event_info`.`dept_ID` AS `dept_ID`, `event_info`.`org_Name` AS `org_Name` FROM `event_info` WHERE ((cast(`event_info`.`e_date` as date) between now() and (now() + interval 7 day)) AND (`event_info`.`status` = 'Approved'))  ;

-- --------------------------------------------------------

--
-- Structure for view `studentinfoview`
--
DROP TABLE IF EXISTS `studentinfoview`;

DROP VIEW IF EXISTS `studentinfoview`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `studentinfoview`  AS SELECT `userstudents`.`userID` AS `userID`, `students`.`sr_code` AS `sr_code`, `userstudents`.`password` AS `password`, `userstudents`.`salt` AS `salt`, `tb_studentinfo`.`firstname` AS `firstName`, `tb_studentinfo`.`lastname` AS `lastName`, `course`.`courseID` AS `courseID`, `students`.`year` AS `year`, `students`.`section` AS `section`, `course`.`courseName` AS `courseName`, `department`.`dept_ID` AS `dept_ID`, `department`.`department_Name` AS `department_Name` FROM ((((`userstudents` join `students` on((`students`.`sr_code` = `userstudents`.`sr_code`))) join `tb_studentinfo` on((`students`.`stud_id` = `tb_studentinfo`.`studid`))) join `course` on((`course`.`courseID` = `students`.`courseID`))) join `department` on((`department`.`dept_ID` = `course`.`dept_ID`)))  ;

-- --------------------------------------------------------

--
-- Structure for view `stud_atendees_view`
--
DROP TABLE IF EXISTS `stud_atendees_view`;

DROP VIEW IF EXISTS `stud_atendees_view`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `stud_atendees_view`  AS SELECT `studentinfoview`.`userID` AS `userID`, `studentinfoview`.`dept_ID` AS `dept_ID`, `studentinfoview`.`department_Name` AS `stud_dept`, `event_info`.`statusID` AS `statusID`, `event_info`.`status` AS `status`, `eventattendees`.`eventID` AS `stud_deptid`, `event_info`.`org_ID` AS `org_ID`, `event_info`.`e_date` AS `e_date`, `eventattendees`.`attendeeID` AS `attendeeID`, `eventattendees`.`eventID` AS `eventID`, `eventattendees`.`DateRegistered` AS `DateRegistered`, `studentinfoview`.`sr_code` AS `sr_code` FROM ((`eventattendees` join `event_info` on((`event_info`.`eventID` = `eventattendees`.`eventID`))) join `studentinfoview` on((`studentinfoview`.`sr_code` = `eventattendees`.`sr_code`)))  ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
