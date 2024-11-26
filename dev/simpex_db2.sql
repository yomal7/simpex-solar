-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2024 at 08:09 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AdminReviewServiceRequest` (IN `p_service_id` INT, IN `p_operationManager_id` INT, IN `p_proposed_datetime` DATETIME, IN `p_operationManager_comments` TEXT, IN `p_service_technician_id` INT)   BEGIN
    -- Update After-Sale Service Status
    UPDATE AfterSaleService
    SET 
        status = 'reviewed'
    WHERE service_id = p_service_id;
    
    -- Update Service Schedule
    UPDATE ServiceSchedule
    SET 
        operationManager_id = p_operationManager_id,
        proposed_datetime = p_proposed_datetime,
        status = 'scheduled',
        operationManager_comments = p_operationManager_comments,
        service_technician_id = p_service_technician_id
    WHERE service_id = p_service_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ApprovePaymentSlip` (IN `p_payment_id` INT, IN `p_operationManager_id` INT, IN `p_operationManager_comments` TEXT)   BEGIN
    DECLARE v_phase_id INT;
    DECLARE v_project_id INT;
    
    -- Update payment details
    UPDATE Payment 
    SET 
        payment_status = 'approved',
        operationManager_id = p_operationManager_id,
        operationManager_comments = p_operationManager_comments,
        payment_date = NOW()
    WHERE payment_id = p_payment_id;
    
    -- Get phase and project details
    SELECT phase_id INTO v_phase_id 
    FROM Payment 
    WHERE payment_id = p_payment_id;
    
    -- Update payment phase status
    UPDATE PaymentPhase 
    SET status = 'completed'
    WHERE phase_id = v_phase_id;
    
    -- Get project ID
    SELECT project_id INTO v_project_id
    FROM PaymentPhase
    WHERE phase_id = v_phase_id;
    
    -- Check if all payment phases are completed
    IF NOT EXISTS (
        SELECT 1 
        FROM PaymentPhase 
        WHERE project_id = v_project_id AND status != 'completed'
    ) THEN
        -- Update project status if all payments are completed
        UPDATE Project 
        SET status = 'completed'
        WHERE project_id = v_project_id;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CompleteAfterSaleService` (IN `p_service_id` INT, IN `p_technician_notes` TEXT)   BEGIN
    -- Update After-Sale Service Status
    UPDATE AfterSaleService
    SET 
        status = 'completed'
    WHERE service_id = p_service_id;
    
    -- Update Service Schedule
    UPDATE ServiceSchedule
    SET 
        status = 'completed',
        technician_notes = p_technician_notes
    WHERE service_id = p_service_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CompleteEngineerApproval` (IN `p_approval_id` INT, IN `p_engineer_id` INT, IN `p_approval_notes` TEXT)   BEGIN
    -- Update Approval Schedule
    UPDATE ApprovalSchedule
    SET 
        status = 'completed',
        engineer_id = p_engineer_id,
        approval_notes = p_approval_notes
    WHERE approval_id = p_approval_id
    AND status = 'agreed';
    
    -- Update Engineer Approval Phase Status
    UPDATE EngineerApproval
    SET status = 'completed'
    WHERE approval_id = p_approval_id;
    
    -- Update Project Phase Status
    UPDATE ProjectPhase 
    SET status = 'completed'
    WHERE project_id = (
        SELECT project_id 
        FROM EngineerApproval 
        WHERE approval_id = p_approval_id
    ) AND phase_name = 'engineer_approval';
    
    -- Check if all project phases are completed
    IF NOT EXISTS (
        SELECT 1 
        FROM ProjectPhase 
        WHERE project_id = (
            SELECT project_id 
            FROM EngineerApproval 
            WHERE approval_id = p_approval_id
        ) AND status != 'completed'
    ) THEN
        -- Update Project Status to Completed
        UPDATE Project
        SET status = 'completed'
        WHERE project_id = (
            SELECT project_id 
            FROM EngineerApproval 
            WHERE approval_id = p_approval_id
        );
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CompleteInstallation` (IN `p_installation_id` INT, IN `p_technical_person_id` INT, IN `p_installation_notes` TEXT)   BEGIN
    -- Update Installation Schedule
    UPDATE InstallationSchedule
    SET 
        status = 'completed',
        technical_person_id = p_technical_person_id,
        installation_notes = p_installation_notes
    WHERE installation_id = p_installation_id
    AND status = 'agreed';
    
    -- Update Installation Phase Status
    UPDATE InstallationPhase
    SET status = 'completed'
    WHERE installation_id = p_installation_id;
    
    -- Update Project Phase Status
    UPDATE ProjectPhase 
    SET status = 'completed'
    WHERE project_id = (
        SELECT project_id 
        FROM InstallationPhase 
        WHERE installation_id = p_installation_id
    ) AND phase_name = 'installation';
    
    -- Trigger Final Payment Phase
    CALL CreateFinalPaymentPhase(
        (SELECT project_id 
         FROM InstallationPhase 
         WHERE installation_id = p_installation_id)
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CompleteSiteVisit` (IN `p_site_visit_id` INT, IN `p_site_visit_person_id` INT, IN `p_site_visit_notes` TEXT)   BEGIN
    -- Update Site Visit Schedule
    UPDATE SiteVisitSchedule
    SET 
        status = 'completed',
        site_visit_person_id = p_site_visit_person_id,
        site_visit_notes = p_site_visit_notes
    WHERE site_visit_id = p_site_visit_id
    AND status = 'agreed';
    
    -- Update Site Visit Status
    UPDATE SiteVisit
    SET status = 'completed'
    WHERE site_visit_id = p_site_visit_id;
    
    -- Update Project Phase Status
    UPDATE ProjectPhase 
    SET status = 'completed'
    WHERE project_id = (
        SELECT project_id 
        FROM SiteVisit 
        WHERE site_visit_id = p_site_visit_id
    ) AND phase_name = 'site_visit';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateAfterSaleServiceRequest` (IN `p_project_id` INT, IN `p_customer_id` INT, IN `p_service_type` ENUM('maintenance','repair','consultation','other'), IN `p_description` TEXT)   BEGIN
    DECLARE v_service_id INT;
    
    -- Create After-Sale Service Record
    INSERT INTO AfterSaleService (
        project_id, 
        customer_id, 
        service_type, 
        description
    ) VALUES (
        p_project_id, 
        p_customer_id, 
        p_service_type, 
        p_description
    );
    
    SET v_service_id = LAST_INSERT_ID();
    
    -- Create Initial Service Schedule
    INSERT INTO ServiceSchedule (
        service_id, 
        requested_datetime,
        customer_description
    ) VALUES (
        v_service_id, 
        NOW(),
        p_description
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CustomerApprovalResponse` (IN `p_schedule_id` INT, IN `p_customer_id` INT, IN `p_response` ENUM('agreed','reschedule_request'), IN `p_customer_comment` TEXT)   BEGIN
    -- Update Approval Schedule
    UPDATE ApprovalSchedule
    SET 
        customer_response = p_response,
        customer_comment = p_customer_comment
    WHERE schedule_id = p_schedule_id;
    
    -- If reschedule requested, reset status
    IF p_response = 'reschedule_request' THEN
        UPDATE ApprovalSchedule
        SET status = 'rejected'
        WHERE schedule_id = p_schedule_id;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CustomerInstallationResponse` (IN `p_schedule_id` INT, IN `p_customer_id` INT, IN `p_response` ENUM('agreed','reschedule_request'), IN `p_customer_comment` TEXT)   BEGIN
    -- Update Installation Schedule
    UPDATE InstallationSchedule
    SET 
        customer_response = p_response,
        customer_comment = p_customer_comment
    WHERE schedule_id = p_schedule_id;
    
    -- If reschedule requested, reset status
    IF p_response = 'reschedule_request' THEN
        UPDATE InstallationSchedule
        SET status = 'rejected'
        WHERE schedule_id = p_schedule_id;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CustomerSiteVisitResponse` (IN `p_schedule_id` INT, IN `p_customer_id` INT, IN `p_response` ENUM('agreed','reschedule_request'), IN `p_customer_comment` TEXT)   BEGIN
    -- Update Site Visit Schedule
    UPDATE SiteVisitSchedule
    SET 
        customer_response = p_response,
        customer_comment = p_customer_comment
    WHERE schedule_id = p_schedule_id;
    
    -- If reschedule requested, reset status
    IF p_response = 'reschedule_request' THEN
        UPDATE SiteVisitSchedule
        SET status = 'rejected'
        WHERE schedule_id = p_schedule_id;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ProcessOnlinePayment` (IN `p_payment_id` INT, IN `p_transaction_id` VARCHAR(100))   BEGIN
    DECLARE v_phase_id INT;
    DECLARE v_project_id INT;
    
    -- Update payment details
    UPDATE Payment 
    SET 
        payment_status = 'completed',
        transaction_id = p_transaction_id,
        payment_date = NOW()
    WHERE payment_id = p_payment_id;
    
    -- Get phase and project details
    SELECT phase_id INTO v_phase_id 
    FROM Payment 
    WHERE payment_id = p_payment_id;
    
    -- Update payment phase status
    UPDATE PaymentPhase 
    SET status = 'completed'
    WHERE phase_id = v_phase_id;
    
    -- Check if all payment phases are completed
    SELECT project_id INTO v_project_id
    FROM PaymentPhase
    WHERE phase_id = v_phase_id;
    
    IF NOT EXISTS (
        SELECT 1 
        FROM PaymentPhase 
        WHERE project_id = v_project_id AND status != 'completed'
    ) THEN
        -- Update project status if all payments are completed
        UPDATE Project 
        SET status = 'completed'
        WHERE project_id = v_project_id;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ProposeAdminEngineerApproval` (IN `p_project_id` INT, IN `p_operationManager_id` INT, IN `p_proposed_datetime` DATETIME)   BEGIN
    DECLARE v_approval_id INT;
    
    -- Create Engineer Approval Phase Record
    INSERT INTO EngineerApproval (project_id, status) 
    VALUES (p_project_id, 'pending');
    
    SET v_approval_id = LAST_INSERT_ID();
    
    -- Create Approval Schedule
    INSERT INTO ApprovalSchedule (
        approval_id, 
        proposed_datetime, 
        proposed_by, 
        proposed_user_id
    ) VALUES (
        v_approval_id, 
        p_proposed_datetime, 
        'operationManager', 
        p_operationManager_id
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ProposeAdminInstallation` (IN `p_project_id` INT, IN `p_operationManager_id` INT, IN `p_proposed_datetime` DATETIME)   BEGIN
    DECLARE v_installation_id INT;
    
    -- Create Installation Phase Record
    INSERT INTO InstallationPhase (project_id, status) 
    VALUES (p_project_id, 'pending');
    
    SET v_installation_id = LAST_INSERT_ID();
    
    -- Create Installation Schedule
    INSERT INTO InstallationSchedule (
        installation_id, 
        proposed_datetime, 
        proposed_by, 
        proposed_user_id
    ) VALUES (
        v_installation_id, 
        p_proposed_datetime, 
        'operationManager', 
        p_operationManager_id
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ProposeAdminSiteVisit` (IN `p_project_id` INT, IN `p_operationManager_id` INT, IN `p_proposed_datetime` DATETIME)   BEGIN
    DECLARE v_site_visit_id INT;
    
    -- Create Site Visit Record
    INSERT INTO SiteVisit (project_id, status) 
    VALUES (p_project_id, 'pending');
    
    SET v_site_visit_id = LAST_INSERT_ID();
    
    -- Create Site Visit Schedule
    INSERT INTO SiteVisitSchedule (
        site_visit_id, 
        proposed_datetime, 
        proposed_by, 
        proposed_user_id
    ) VALUES (
        v_site_visit_id, 
        p_proposed_datetime, 
        'operationManager', 
        p_operationManager_id
    );
END$$

DELIMITER ;

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
-- Table structure for table `package`
--

CREATE TABLE `package` (
  `package_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `warranty_years` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packageequipment`
--

CREATE TABLE `packageequipment` (
  `equipment_id` int(11) NOT NULL,
  `package_id` int(11) DEFAULT NULL,
  `equipment_name` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `description_link` varchar(255) DEFAULT NULL
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

-- --------------------------------------------------------

--
-- Table structure for table `post_topic`
--

CREATE TABLE `post_topic` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
-- Table structure for table `topics`
--

CREATE TABLE `topics` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
  `role` enum('customer','admin','chiefManager','supplierManager','HrManager','operationManager','employee') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  ADD KEY `package_id` (`package_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_topic`
--
ALTER TABLE `post_topic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `topics`
--
ALTER TABLE `topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

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
-- Constraints for table `packageequipment`
--
ALTER TABLE `packageequipment`
  ADD CONSTRAINT `packageequipment_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `package` (`package_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
