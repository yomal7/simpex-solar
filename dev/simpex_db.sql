-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2024 at 07:32 AM
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
-- Database: `simpex_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `aftersaleservice`
--

CREATE TABLE `aftersaleservice` (
  `service_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `service_type` enum('maintenance','repair','consultation','other') NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','reviewed','scheduled','completed','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agreement`
--

CREATE TABLE `agreement` (
  `agreement_id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `final_price` decimal(10,2) NOT NULL,
  `terms` text NOT NULL,
  `signature_path` varchar(255) DEFAULT NULL,
  `signed_at` timestamp NULL DEFAULT NULL,
  `pdf_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agreementrevision`
--

CREATE TABLE `agreementrevision` (
  `revision_id` int(11) NOT NULL,
  `agreement_id` int(11) DEFAULT NULL,
  `customer_comment` text DEFAULT NULL,
  `revised_terms` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `approvalschedule`
--

CREATE TABLE `approvalschedule` (
  `schedule_id` int(11) NOT NULL,
  `approval_id` int(11) NOT NULL,
  `proposed_datetime` datetime NOT NULL,
  `proposed_by` enum('operationManager','customer') NOT NULL,
  `proposed_user_id` int(11) DEFAULT NULL,
  `customer_response` enum('pending','agreed','reschedule_request') DEFAULT 'pending',
  `customer_comment` text DEFAULT NULL,
  `final_datetime` datetime DEFAULT NULL,
  `status` enum('proposed','agreed','rejected','completed') DEFAULT 'proposed',
  `engineer_id` int(11) DEFAULT NULL,
  `approval_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blogcomments`
--

CREATE TABLE `blogcomments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `body` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE `blog_categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_categories`
--

INSERT INTO `blog_categories` (`category_id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Equipment Section', 'equipment', 'Information about solar equipment and components', '2024-11-24 11:36:52', '2024-11-24 11:36:52'),
(2, 'User Guide Section', 'user-guide', 'Guides and tutorials for system usage', '2024-11-24 11:36:52', '2024-11-24 11:36:52'),
(3, 'Package Selection and Customization', 'package-selection', 'Details about available packages and customization options', '2024-11-24 11:36:52', '2024-11-24 11:36:52'),
(4, 'Installation Process Section', 'installation', 'Information about the installation process', '2024-11-24 11:36:52', '2024-11-24 11:36:52'),
(5, 'Energy Management Section', 'energy-management', 'Tips and guides for energy management', '2024-11-24 11:36:52', '2024-11-24 11:36:52'),
(6, 'Agreement and Documentation Section', 'agreements', 'Information about contracts and documentation', '2024-11-24 11:36:52', '2024-11-24 11:36:52'),
(7, 'Payment and Financing Section', 'payment-financing', 'Details about payment options and financing', '2024-11-24 11:36:52', '2024-11-24 11:36:52'),
(8, 'Customer Support and After-Sales Section', 'customer-support', 'Support information and after-sales services', '2024-11-24 11:36:52', '2024-11-24 11:36:52'),
(9, 'Industry News and Innovations Section', 'industry-news', 'Latest updates and innovations in solar industry', '2024-11-24 11:36:52', '2024-11-24 11:36:52'),
(10, 'Sustainability and Environmental Section', 'sustainability', 'Environmental impact and sustainability information', '2024-11-24 11:36:52', '2024-11-24 11:36:52');

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `summary` text DEFAULT NULL,
  `body` text NOT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`post_id`, `user_id`, `category_id`, `title`, `slug`, `summary`, `body`, `featured_image`, `views`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'Growat solar panles new', 'growat-solar-panles-new', 'Growatt Solar Panels: A Comprehensive Guide to Sustainable Energy Solutions new', 'As the global demand for renewable energy surges, Growatt has emerged as a leading provider of solar energy solutions. Known for its high-performance solar panels and inverters, Growatt offers sustainable, efficient, and affordable energy solutions for residential, commercial, and industrial applications. This article explores the features, benefits, and applications of Growatt solar panels, providing an insightful overview for potential customers and eco-conscious enthusiasts.\r\n\r\nAbout Growatt\r\nFounded in 2010, Growatt specializes in the research, development, and manufacturing of solar energy products, including solar panels, inverters, and energy storage systems. The company is recognized worldwide for its innovative technology, high efficiency, and commitment to sustainability.\r\n\r\nGrowatt has a global presence with installations in over 100 countries, supported by a robust network of customer service centers. Its products are widely acclaimed for their reliability and advanced features, making it a popular choice for energy-conscious consumers.\r\n\r\nKey Features of Growatt Solar Panels\r\nHigh Efficiency\r\nGrowatt solar panels are designed with advanced photovoltaic (PV) technology, ensuring high energy conversion efficiency. This means you can generate more power from fewer panels, optimizing space and investment.\r\n\r\nDurability and Longevity\r\nBuilt with premium materials, Growatt solar panels are engineered to withstand harsh weather conditions, including extreme heat, heavy rain, and snow. The panels come with warranties of up to 25 years, ensuring long-term performance and reliability.\r\n\r\nSmart Technology Integration\r\nGrowatt panels are compatible with smart monitoring systems, allowing users to track energy production, consumption, and efficiency in real-time. This integration enhances energy management and ensures optimal performance.\r\n\r\nEnvironmentally Friendly Design\r\nGrowatt solar panels are designed with sustainability in mind, using recyclable materials and energy-efficient manufacturing processes to minimize their environmental footprint.\r\n\r\nVersatile Applications\r\nGrowatt offers solar panels suitable for various applications, from small residential rooftops to large-scale solar farms. The panels are also compatible with Growatt inverters and storage systems, making them ideal for integrated solar solutions.\r\n\r\nBenefits of Growatt Solar Panels\r\nCost Savings: By harnessing solar energy, users can significantly reduce electricity bills and even earn credits through net metering programs.\r\nEnergy Independence: Growatt panels enable households and businesses to generate their own electricity, reducing dependence on traditional power grids.\r\nReduced Carbon Footprint: Using solar energy helps reduce greenhouse gas emissions, contributing to a cleaner, greener planet.\r\nScalability: Whether you start with a single panel or a full solar array, Growatt solutions can be scaled to meet growing energy needs.\r\nApplications of Growatt Solar Panels\r\nResidential Solutions\r\nHomeowners can install Growatt solar panels on rooftops to generate clean energy for daily use. The panels are designed to integrate seamlessly with battery storage systems, providing backup power during outages.\r\n\r\nCommercial and Industrial Use\r\nGrowatt solar panels are a preferred choice for businesses aiming to reduce operational costs and demonstrate environmental responsibility. From warehouses to office buildings, these panels can power diverse facilities efficiently.\r\n\r\nAgricultural Applications\r\nIn rural and agricultural settings, Growatt panels can power irrigation systems, lighting, and equipment, ensuring sustainable and cost-effective energy for farmers.\r\n\r\nOff-Grid Installations\r\nFor remote areas with limited access to electricity, Growatt panels paired with energy storage systems provide a reliable and independent power source.\r\n\r\nWhy Choose Growatt?\r\nReputation for Excellence\r\nGrowatt has received numerous awards and certifications, showcasing its commitment to quality and innovation in renewable energy.\r\n\r\nGlobal Support Network\r\nWith a strong presence in multiple countries, Growatt offers reliable customer support and technical assistance.\r\n\r\nAffordability\r\nGrowatt solar panels provide excellent value for money, making renewable energy accessible to a wider audience.', '674311ada6f38_Growatt-5kw-lithium-ion-solar-kit.jpg', 59, 'published', '2024-11-24 11:44:45', '2024-11-26 06:06:40');

-- --------------------------------------------------------

--
-- Table structure for table `customerquotation`
--

CREATE TABLE `customerquotation` (
  `quotation_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `address` text NOT NULL,
  `monthly_consumption` decimal(10,2) NOT NULL,
  `nearest_city` varchar(100) NOT NULL,
  `customizations` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custompackage`
--

CREATE TABLE `custompackage` (
  `custom_package_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `base_package_id` int(11) DEFAULT NULL,
  `customizations` text DEFAULT NULL,
  `estimated_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` enum('technician','deliveryPerson','engineer','clerk') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `engineerapproval`
--

CREATE TABLE `engineerapproval` (
  `approval_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `status` enum('pending','scheduled','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `installationphase`
--

CREATE TABLE `installationphase` (
  `installation_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `status` enum('pending','scheduled','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `installationschedule`
--

CREATE TABLE `installationschedule` (
  `schedule_id` int(11) NOT NULL,
  `installation_id` int(11) NOT NULL,
  `proposed_datetime` datetime NOT NULL,
  `proposed_by` enum('operationManager','customer') NOT NULL,
  `proposed_user_id` int(11) DEFAULT NULL,
  `customer_response` enum('pending','agreed','reschedule_request') DEFAULT 'pending',
  `customer_comment` text DEFAULT NULL,
  `final_datetime` datetime DEFAULT NULL,
  `status` enum('proposed','agreed','rejected','completed') DEFAULT 'proposed',
  `technical_person_id` int(11) DEFAULT NULL,
  `installation_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `blog_link` varchar(255) DEFAULT NULL,
  `supplier_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `name`, `price`, `quantity`, `description`, `blog_link`, `supplier_id`) VALUES
(1, 'Solar Panel A', 5000.00, 100, 'High-efficiency solar panel', 'http://example.com/blog/solar-panel-a', 1),
(2, 'Inverter B', 1500.00, 50, 'Power inverter for solar systems', 'http://example.com/blog/inverter-b', 2);

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE `package` (
  `package_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `warranty_years` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` enum('on-grid','off-grid','hybrid') NOT NULL,
  `service_charge` decimal(10,2) DEFAULT 0.00,
  `final_price` decimal(10,2) DEFAULT 0.00,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packageequipment`
--

CREATE TABLE `packageequipment` (
  `equipment_id` int(11) NOT NULL,
  `package_id` int(11) DEFAULT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packagefeature`
--

CREATE TABLE `packagefeature` (
  `feature_id` int(11) NOT NULL,
  `package_id` int(11) DEFAULT NULL,
  `feature_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `phase_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('online','slip') NOT NULL,
  `payment_status` enum('pending','approved','rejected','completed') DEFAULT 'pending',
  `payment_slip_path` varchar(255) DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `operationManager_id` int(11) DEFAULT NULL,
  `operationManager_comments` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paymentphase`
--

CREATE TABLE `paymentphase` (
  `phase_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `phase_name` enum('first_payment','final_payment') NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `percentage` decimal(5,2) NOT NULL,
  `status` enum('pending','completed','cancelled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personaldetails`
--

CREATE TABLE `personaldetails` (
  `details_id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `connection_type` enum('ongrid','offgrid','hybrid') NOT NULL,
  `supply_type` enum('single_phase','three_phase') NOT NULL,
  `self_submission` tinyint(1) DEFAULT 0,
  `submission_status` enum('pending','submitted','verified') DEFAULT 'pending',
  `submitted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `title`, `slug`, `views`, `image`, `body`, `published`, `created_at`, `updated_at`) VALUES
(1, 1, '5 Habits that can improve your life', '5-habits-that-can-improve-your-life', 0, 'banner.jpg', 'Read every day', 1, '2018-02-03 02:28:02', '2018-02-01 13:44:31'),
(2, 1, 'Second post on LifeBlog', 'second-post-on-lifeblog', 0, 'banner.jpg', 'This is the body of the second post on this site', 1, '2024-11-23 09:33:07', '2024-11-23 09:33:07');

-- --------------------------------------------------------

--
-- Table structure for table `post_topic`
--

CREATE TABLE `post_topic` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `post_topic`
--

INSERT INTO `post_topic` (`id`, `post_id`, `topic_id`) VALUES
(1, 1, 1),
(2, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `project_id` int(11) NOT NULL,
  `quotation_id` int(11) DEFAULT NULL,
  `custom_package_id` int(11) DEFAULT NULL,
  `status` enum('active','completed','cancelled') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `project`
--
DELIMITER $$
CREATE TRIGGER `create_payment_phases` AFTER INSERT ON `project` FOR EACH ROW BEGIN
    DECLARE total_project_cost DECIMAL(10,2);
    
    -- Get total project cost (assumed to be stored in the project or agreement)
    SELECT final_price INTO total_project_cost 
    FROM Agreement 
    WHERE project_id = NEW.project_id;
    
    -- Create first payment phase (25%)
    INSERT INTO PaymentPhase (
        project_id, 
        phase_name, 
        total_amount, 
        percentage, 
        status
    ) VALUES (
        NEW.project_id, 
        'first_payment', 
        total_project_cost * 0.25, 
        25.00, 
        'pending'
    );
    
    -- Create final payment phase (75%)
    INSERT INTO PaymentPhase (
        project_id, 
        phase_name, 
        total_amount, 
        percentage, 
        status
    ) VALUES (
        NEW.project_id, 
        'final_payment', 
        total_project_cost * 0.75, 
        75.00, 
        'pending'
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `projectphase`
--

CREATE TABLE `projectphase` (
  `phase_id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `phase_name` enum('agreement','personal_details','equipment_ordering','installation','testing','completion') NOT NULL,
  `status` enum('not_started','pending','completed','cancelled') DEFAULT 'not_started',
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `serviceschedule`
--

CREATE TABLE `serviceschedule` (
  `schedule_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `operationManager_id` int(11) DEFAULT NULL,
  `requested_datetime` datetime NOT NULL,
  `proposed_datetime` datetime DEFAULT NULL,
  `service_technician_id` int(11) DEFAULT NULL,
  `status` enum('pending','reviewed','scheduled','completed','rejected') DEFAULT 'pending',
  `customer_description` text DEFAULT NULL,
  `operationManager_comments` text DEFAULT NULL,
  `technician_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sitevisit`
--

CREATE TABLE `sitevisit` (
  `site_visit_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `status` enum('pending','scheduled','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sitevisitschedule`
--

CREATE TABLE `sitevisitschedule` (
  `schedule_id` int(11) NOT NULL,
  `site_visit_id` int(11) NOT NULL,
  `proposed_datetime` datetime NOT NULL,
  `proposed_by` enum('operationManager','customer') NOT NULL,
  `proposed_user_id` int(11) DEFAULT NULL,
  `customer_response` enum('pending','agreed','reschedule_request') DEFAULT 'pending',
  `customer_comment` text DEFAULT NULL,
  `final_datetime` datetime DEFAULT NULL,
  `status` enum('proposed','agreed','rejected','completed') DEFAULT 'proposed',
  `site_visit_person_id` int(11) DEFAULT NULL,
  `site_visit_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `other_details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `address`, `email`, `contact_number`, `other_details`, `created_at`, `updated_at`) VALUES
(1, 'ComapanyA', '123 Solar St, Colombo', 'john.doe@example.com', '0712345678', 'Preferred supplier for solar panels', '2024-11-25 08:23:19', '2024-11-25 08:23:19'),
(2, 'JComapanyB', '456 Green Ave, Colombo', 'jane.smith@example.com', '0723456789', 'Specializes in inverters and batteries', '2024-11-25 08:23:19', '2024-11-25 08:23:19'),
(3, 'helys', 'address1, road2, city 3', 'helys@gmail.com', '0712362378', 'no details', '2024-11-25 08:54:30', '2024-11-25 08:54:30'),
(4, 'new company', 'new, road, city', 'new@gmail.com', '0712321324', 'no', '2024-11-25 10:05:18', '2024-11-25 10:05:18');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `employee_id` int(11) NOT NULL,
  `status` enum('incomplete','in_progress','completed') NOT NULL DEFAULT 'incomplete',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE `topics` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`id`, `name`, `slug`) VALUES
(1, 'Inspiration', 'inspiration'),
(2, 'Motivation', 'motivation'),
(3, 'Diary', 'diary');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `role` enum('customer','admin','chiefCoordinator','operationsCoordinator','hRAdministrator','supplierCoordinator','employee') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `phone`, `role`, `created_at`, `updated_at`, `profile_picture`) VALUES
(1, 'first', 'first@gmail.com', '$2y$10$2.3uy81juXArBe2moaooV.m/Ks1vka/rQzpye4Cw775rROrUGZwtm', NULL, 'customer', '2024-11-21 06:45:27', '2024-11-21 06:45:27', NULL),
(2, 'admin', 'admin@gmail.com', '$2y$10$aNpJXxHKNXXoIlO2WsSOHejhMtKC4OLT1Pf.G.yqPjc/aomJ61Cb6', NULL, 'admin', '2024-11-23 07:12:45', '2024-11-23 07:12:45', NULL),
(3, 'manager', 'm@gmail.com', '$2y$10$5.Dy033hIeXxQYhrij4Ii.zn3/mPJfREDNWsj6uHGPbjW34lniJxW', NULL, '', '2024-11-23 07:13:29', '2024-11-23 07:13:29', NULL),
(4, 'user1', 'user1@gmail.com', '$2y$10$xqJ5XMWPA51bZDkRX4OvvuacVI9fwdUPxxez1j7JvObg3jEyG10qe', '0713642725', 'customer', '2024-11-23 13:08:38', '2024-11-24 17:27:32', '67436204be135_Growatt-5kw-lithium-ion-solar-kit.jpg'),
(5, 'user2', 'user2@gmail.com', '$2y$10$3xW56eqCz3HvW3jaN2eEBuVQa3RKCmS96/04MozdUvo0qS8B1.m6.', NULL, 'customer', '2024-11-23 15:21:14', '2024-11-23 15:21:14', NULL),
(6, 'uesr3', 'user3@gmail.com', '$2y$10$MoLTIZiyOGH2YxKUoYMtq.N43/LPNYYOzVH9JoHIn5Y4X9DRPgwz6', NULL, 'customer', '2024-11-23 15:59:59', '2024-11-23 15:59:59', NULL),
(8, 'Operation coordinator', 'operations.simplex@gmail.com', '$2y$10$wQGNNinmSu0zBJKyqUf/kOYbu0n.UjyPWzPeVH7PJ2lITGD5hNriK', NULL, 'operationsCoordinator', '2024-11-26 05:08:44', '2024-11-26 05:08:44', NULL),
(9, 'hr admin', 'hr.simplex@gmail.com', '$2y$10$3AuIxAc0PxbGeKgLm0GF/.MO8ETeru.3xhQn6wgn7oCmHlsjDhGLa', NULL, 'hRAdministrator', '2024-11-26 06:25:18', '2024-11-26 06:25:18', NULL),
(10, 'supplier coordinator', 'supplier.simplex@gmail.com', '$2y$10$xiNJZrzPUKY91AduR6I3TuUU4Yqc4Xz.tHx8Y8jU33xdMp4lsbxqW', NULL, 'supplierCoordinator', '2024-11-26 06:26:08', '2024-11-26 06:26:08', NULL),
(11, 'chief coordinator', 'chief.simplex@gmail.com', '$2y$10$b6ghp5./SZTkT5/eX6jnXO2PxrFBKWZMklqqjVScENKUl98iUAQam', NULL, 'chiefCoordinator', '2024-11-26 06:26:36', '2024-11-26 06:26:36', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aftersaleservice`
--
ALTER TABLE `aftersaleservice`
  ADD PRIMARY KEY (`service_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `idx_service_project` (`project_id`),
  ADD KEY `idx_service_status` (`status`);

--
-- Indexes for table `agreement`
--
ALTER TABLE `agreement`
  ADD PRIMARY KEY (`agreement_id`),
  ADD UNIQUE KEY `project_id` (`project_id`);

--
-- Indexes for table `agreementrevision`
--
ALTER TABLE `agreementrevision`
  ADD PRIMARY KEY (`revision_id`),
  ADD KEY `agreement_id` (`agreement_id`);

--
-- Indexes for table `approvalschedule`
--
ALTER TABLE `approvalschedule`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `proposed_user_id` (`proposed_user_id`),
  ADD KEY `engineer_id` (`engineer_id`),
  ADD KEY `idx_schedule_approval` (`approval_id`),
  ADD KEY `idx_schedule_status` (`status`);

--
-- Indexes for table `blogcomments`
--
ALTER TABLE `blogcomments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`post_id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `customerquotation`
--
ALTER TABLE `customerquotation`
  ADD PRIMARY KEY (`quotation_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `custompackage`
--
ALTER TABLE `custompackage`
  ADD PRIMARY KEY (`custom_package_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `base_package_id` (`base_package_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_employee_role` (`role`);

--
-- Indexes for table `engineerapproval`
--
ALTER TABLE `engineerapproval`
  ADD PRIMARY KEY (`approval_id`),
  ADD KEY `idx_approval_project` (`project_id`);

--
-- Indexes for table `installationphase`
--
ALTER TABLE `installationphase`
  ADD PRIMARY KEY (`installation_id`),
  ADD KEY `idx_installation_project` (`project_id`);

--
-- Indexes for table `installationschedule`
--
ALTER TABLE `installationschedule`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `proposed_user_id` (`proposed_user_id`),
  ADD KEY `technical_person_id` (`technical_person_id`),
  ADD KEY `idx_schedule_installation` (`installation_id`),
  ADD KEY `idx_schedule_status` (`status`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `package`
--
ALTER TABLE `package`
  ADD PRIMARY KEY (`package_id`),
  ADD KEY `idx_package_title` (`title`);

--
-- Indexes for table `packageequipment`
--
ALTER TABLE `packageequipment`
  ADD PRIMARY KEY (`equipment_id`),
  ADD KEY `package_id` (`package_id`),
  ADD KEY `fk_inventory` (`item_id`);

--
-- Indexes for table `packagefeature`
--
ALTER TABLE `packagefeature`
  ADD PRIMARY KEY (`feature_id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `operationManager_id` (`operationManager_id`),
  ADD KEY `idx_payment_phase` (`phase_id`),
  ADD KEY `idx_payment_status` (`payment_status`),
  ADD KEY `idx_payment_method` (`payment_method`);

--
-- Indexes for table `paymentphase`
--
ALTER TABLE `paymentphase`
  ADD PRIMARY KEY (`phase_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `personaldetails`
--
ALTER TABLE `personaldetails`
  ADD PRIMARY KEY (`details_id`),
  ADD UNIQUE KEY `project_id` (`project_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post_topic`
--
ALTER TABLE `post_topic`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `topic_id` (`topic_id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `quotation_id` (`quotation_id`),
  ADD KEY `custom_package_id` (`custom_package_id`),
  ADD KEY `idx_project_status` (`status`);

--
-- Indexes for table `projectphase`
--
ALTER TABLE `projectphase`
  ADD PRIMARY KEY (`phase_id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `idx_phase_status` (`status`);

--
-- Indexes for table `serviceschedule`
--
ALTER TABLE `serviceschedule`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `operationManager_id` (`operationManager_id`),
  ADD KEY `service_technician_id` (`service_technician_id`),
  ADD KEY `idx_schedule_status` (`status`);

--
-- Indexes for table `sitevisit`
--
ALTER TABLE `sitevisit`
  ADD PRIMARY KEY (`site_visit_id`),
  ADD KEY `idx_site_visit_project` (`project_id`);

--
-- Indexes for table `sitevisitschedule`
--
ALTER TABLE `sitevisitschedule`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `proposed_user_id` (`proposed_user_id`),
  ADD KEY `site_visit_person_id` (`site_visit_person_id`),
  ADD KEY `idx_schedule_site_visit` (`site_visit_id`),
  ADD KEY `idx_schedule_status` (`status`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_user_role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aftersaleservice`
--
ALTER TABLE `aftersaleservice`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agreement`
--
ALTER TABLE `agreement`
  MODIFY `agreement_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agreementrevision`
--
ALTER TABLE `agreementrevision`
  MODIFY `revision_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `approvalschedule`
--
ALTER TABLE `approvalschedule`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blogcomments`
--
ALTER TABLE `blogcomments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customerquotation`
--
ALTER TABLE `customerquotation`
  MODIFY `quotation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custompackage`
--
ALTER TABLE `custompackage`
  MODIFY `custom_package_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `engineerapproval`
--
ALTER TABLE `engineerapproval`
  MODIFY `approval_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `installationphase`
--
ALTER TABLE `installationphase`
  MODIFY `installation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `installationschedule`
--
ALTER TABLE `installationschedule`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `package`
--
ALTER TABLE `package`
  MODIFY `package_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packageequipment`
--
ALTER TABLE `packageequipment`
  MODIFY `equipment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packagefeature`
--
ALTER TABLE `packagefeature`
  MODIFY `feature_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paymentphase`
--
ALTER TABLE `paymentphase`
  MODIFY `phase_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personaldetails`
--
ALTER TABLE `personaldetails`
  MODIFY `details_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `post_topic`
--
ALTER TABLE `post_topic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projectphase`
--
ALTER TABLE `projectphase`
  MODIFY `phase_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `serviceschedule`
--
ALTER TABLE `serviceschedule`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sitevisit`
--
ALTER TABLE `sitevisit`
  MODIFY `site_visit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sitevisitschedule`
--
ALTER TABLE `sitevisitschedule`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `topics`
--
ALTER TABLE `topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aftersaleservice`
--
ALTER TABLE `aftersaleservice`
  ADD CONSTRAINT `aftersaleservice_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `aftersaleservice_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `agreement`
--
ALTER TABLE `agreement`
  ADD CONSTRAINT `agreement_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`) ON UPDATE CASCADE;

--
-- Constraints for table `agreementrevision`
--
ALTER TABLE `agreementrevision`
  ADD CONSTRAINT `agreementrevision_ibfk_1` FOREIGN KEY (`agreement_id`) REFERENCES `agreement` (`agreement_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `approvalschedule`
--
ALTER TABLE `approvalschedule`
  ADD CONSTRAINT `approvalschedule_ibfk_1` FOREIGN KEY (`approval_id`) REFERENCES `engineerapproval` (`approval_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `approvalschedule_ibfk_2` FOREIGN KEY (`proposed_user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `approvalschedule_ibfk_3` FOREIGN KEY (`engineer_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `blogcomments`
--
ALTER TABLE `blogcomments`
  ADD CONSTRAINT `blogcomments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `blogcomments_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `blog_posts_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `blog_categories` (`category_id`);

--
-- Constraints for table `customerquotation`
--
ALTER TABLE `customerquotation`
  ADD CONSTRAINT `customerquotation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `customerquotation_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `package` (`package_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `custompackage`
--
ALTER TABLE `custompackage`
  ADD CONSTRAINT `custompackage_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `custompackage_ibfk_2` FOREIGN KEY (`base_package_id`) REFERENCES `package` (`package_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `engineerapproval`
--
ALTER TABLE `engineerapproval`
  ADD CONSTRAINT `engineerapproval_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `installationphase`
--
ALTER TABLE `installationphase`
  ADD CONSTRAINT `installationphase_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `installationschedule`
--
ALTER TABLE `installationschedule`
  ADD CONSTRAINT `installationschedule_ibfk_1` FOREIGN KEY (`installation_id`) REFERENCES `installationphase` (`installation_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `installationschedule_ibfk_2` FOREIGN KEY (`proposed_user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `installationschedule_ibfk_3` FOREIGN KEY (`technical_person_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `packageequipment`
--
ALTER TABLE `packageequipment`
  ADD CONSTRAINT `fk_inventory` FOREIGN KEY (`item_id`) REFERENCES `inventory` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_package` FOREIGN KEY (`package_id`) REFERENCES `package` (`package_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `packagefeature`
--
ALTER TABLE `packagefeature`
  ADD CONSTRAINT `packagefeature_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `package` (`package_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`phase_id`) REFERENCES `paymentphase` (`phase_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_ibfk_3` FOREIGN KEY (`operationManager_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `paymentphase`
--
ALTER TABLE `paymentphase`
  ADD CONSTRAINT `paymentphase_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `personaldetails`
--
ALTER TABLE `personaldetails`
  ADD CONSTRAINT `personaldetails_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`) ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `post_topic`
--
ALTER TABLE `post_topic`
  ADD CONSTRAINT `post_topic_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `post_topic_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `project_ibfk_1` FOREIGN KEY (`quotation_id`) REFERENCES `customerquotation` (`quotation_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `project_ibfk_2` FOREIGN KEY (`custom_package_id`) REFERENCES `custompackage` (`custom_package_id`) ON UPDATE CASCADE;

--
-- Constraints for table `projectphase`
--
ALTER TABLE `projectphase`
  ADD CONSTRAINT `projectphase_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `serviceschedule`
--
ALTER TABLE `serviceschedule`
  ADD CONSTRAINT `serviceschedule_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `aftersaleservice` (`service_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `serviceschedule_ibfk_2` FOREIGN KEY (`operationManager_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `serviceschedule_ibfk_3` FOREIGN KEY (`service_technician_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `sitevisit`
--
ALTER TABLE `sitevisit`
  ADD CONSTRAINT `sitevisit_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sitevisitschedule`
--
ALTER TABLE `sitevisitschedule`
  ADD CONSTRAINT `sitevisitschedule_ibfk_1` FOREIGN KEY (`site_visit_id`) REFERENCES `sitevisit` (`site_visit_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sitevisitschedule_ibfk_2` FOREIGN KEY (`proposed_user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `sitevisitschedule_ibfk_3` FOREIGN KEY (`site_visit_person_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
