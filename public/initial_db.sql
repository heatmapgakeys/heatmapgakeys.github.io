-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 06, 2023 at 09:09 AM
-- Server version: 5.7.24
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `xeroneit_heatmap_recurring`
--

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_earning_history`
--

DROP TABLE IF EXISTS `affiliate_earning_history`;
CREATE TABLE IF NOT EXISTS `affiliate_earning_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `affiliate_id` int(11) NOT NULL,
  `event` enum('signup','payment','recurring') NOT NULL,
  `amount` float NOT NULL,
  `event_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `affiliate_earning_history1` (`affiliate_id`),
  KEY `affiliate_earning_history2` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_payment_settings`
--

DROP TABLE IF EXISTS `affiliate_payment_settings`;
CREATE TABLE IF NOT EXISTS `affiliate_payment_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `signup_commission` enum('0','1') NOT NULL DEFAULT '0',
  `payment_commission` enum('0','1') NOT NULL DEFAULT '0',
  `payment_type` varchar(50) NOT NULL,
  `sign_up_amount` varchar(255) NOT NULL,
  `percentage` varchar(255) NOT NULL,
  `fixed_amount` varchar(255) NOT NULL,
  `is_recurring` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_requests`
--

DROP TABLE IF EXISTS `affiliate_requests`;
CREATE TABLE IF NOT EXISTS `affiliate_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `website` varchar(300) NOT NULL,
  `email` varchar(100) NOT NULL,
  `fb_link` varchar(300) NOT NULL,
  `affiliating_process` text NOT NULL,
  `submission_date` datetime NOT NULL,
  `status` enum('0','1','2','3') NOT NULL COMMENT '0=nothing,1=rejected,2=approved,3=pending',
  `otp` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `affiliate_requests1` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_withdrawal_methods`
--

DROP TABLE IF EXISTS `affiliate_withdrawal_methods`;
CREATE TABLE IF NOT EXISTS `affiliate_withdrawal_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `payment_type` varchar(255) NOT NULL,
  `paypal_email` varchar(150) NOT NULL,
  `bank_acc_no` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `affiliate id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_withdrawal_requests`
--

DROP TABLE IF EXISTS `affiliate_withdrawal_requests`;
CREATE TABLE IF NOT EXISTS `affiliate_withdrawal_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `method_id` int(11) DEFAULT NULL,
  `requested_amount` double NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '0(pending),1(approved),2(canceled)',
  `created_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `afiiliate Id` (`user_id`),
  KEY `affiliate_withdrawal_requests1` (`method_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
CREATE TABLE IF NOT EXISTS `blogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_category_id` int(11) DEFAULT NULL COMMENT 'blog_categories.id',
  `blog_title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_slug` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_img` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blog_keyword` text COLLATE utf8mb4_unicode_ci,
  `view_count` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL COMMENT 'users.id',
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_slug` (`blog_slug`),
  KEY `blog_category_id` (`blog_category_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

DROP TABLE IF EXISTS `blog_categories`;
CREATE TABLE IF NOT EXISTS `blog_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_slug` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_category_id` int(11) DEFAULT NULL COMMENT 'blog_category.id',
  `updated_at` datetime DEFAULT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `deleted` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_slug` (`category_slug`),
  KEY `parent_category_id` (`parent_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_categories`
--

INSERT INTO `blog_categories` (`id`, `category_name`, `category_slug`, `category_description`, `parent_category_id`, `updated_at`, `status`, `deleted`) VALUES
(1, 'Technical', '', '', NULL, NULL, '1', '0');

-- --------------------------------------------------------

--
-- Table structure for table `blog_comments`
--

DROP TABLE IF EXISTS `blog_comments`;
CREATE TABLE IF NOT EXISTS `blog_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL COMMENT 'blogs.id',
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_blog_comment_id` int(11) DEFAULT NULL COMMENT 'blog_comments.id',
  `updated_at` datetime DEFAULT NULL,
  `hidden` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `hidden_by` int(11) DEFAULT NULL COMMENT 'users.id',
  `hidden_at` datetime DEFAULT NULL,
  `display_admin_dashboard` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT 'if this is parent comment',
  `note` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `blog_comments_blog_cascade` (`blog_id`),
  KEY `blog_comments_parent_comment_cascade` (`parent_blog_comment_id`),
  KEY `blog_comments_user_cascade` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `domain_screenshot`
--

DROP TABLE IF EXISTS `domain_screenshot`;
CREATE TABLE IF NOT EXISTS `domain_screenshot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `website_code` varchar(50) NOT NULL,
  `visit_url` varchar(500) NOT NULL,
  `image` text NOT NULL,
  `session_value` varchar(255) NOT NULL,
  `cookie_value` varchar(255) NOT NULL,
  `device` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code-url-device` (`website_code`,`visit_url`,`device`),
  KEY `domain_screenshot1` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sl` int(11) NOT NULL,
  `module_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extra_text` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'month',
  `subscription_module` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `team_module` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `limit_enabled` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `bulk_limit_enabled` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `deleted` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `modules` (`id`, `sl`, `module_name`, `extra_text`, `subscription_module`, `team_module`, `limit_enabled`, `bulk_limit_enabled`, `status`, `deleted`) VALUES
(1, 1, 'No. of Website', '', '1', '1', '1', '0', '1', '0'),
(2, 3, 'Recorded Sessions', 'month', '1', '1', '1', '0', '1', '0'),
(3, 4, 'Month of data storage', '', '1', '1', '1', '0', '1', '0'),
(12, 7, 'Affiliate System', '', '0', '0', '1', '0', '1', '0'),
(14, 5, 'Heatmaps Suite', '', '1', '0', '0', '0', '1', '0'),
(15, 6, 'Playback Session Recordings', '', '1', '0', '0', '0', '1', '0');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '0 means all',
  `is_seen` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `seen_by` text COLLATE utf8mb4_unicode_ci COMMENT 'if user_id = 0 then comma seperated user_ids',
  `last_seen_at` datetime DEFAULT NULL,
  `color_class` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'primary',
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fas fa-bell',
  `published` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `linkable` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `custom_link` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

DROP TABLE IF EXISTS `packages`;
CREATE TABLE IF NOT EXISTS `packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `package_name` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `package_type` enum('subscription','team') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'subscription',
  `module_ids` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `team_access` text COLLATE utf8mb4_unicode_ci,
  `monthly_limit` text COLLATE utf8mb4_unicode_ci,
  `bulk_limit` text COLLATE utf8mb4_unicode_ci,
  `price` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `validity` int(11) DEFAULT NULL,
  `validity_extra_info` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '1,M',
  `is_default` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `is_agency` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `is_whitelabel` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `subscriber_limit` int(11) NOT NULL DEFAULT '-1',
  `user_limit` int(11) NOT NULL DEFAULT '-1',
  `product_data` text COLLATE utf8mb4_unicode_ci,
  `discount_data` text COLLATE utf8mb4_unicode_ci,
  `visible` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `highlight` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `deleted` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `user_id`, `package_name`, `package_type`, `module_ids`, `team_access`, `monthly_limit`, `bulk_limit`, `price`, `validity`, `validity_extra_info`, `is_default`, `is_agency`, `is_whitelabel`, `subscriber_limit`, `user_limit`, `product_data`, `discount_data`, `visible`, `highlight`, `deleted`) VALUES
(1, 1, 'Basic', 'subscription', '1,2,3', NULL, '{\"1\":\"3\",\"2\":\"2000\",\"3\":\"1\"}', '{\"1\":\"1\",\"2\":\"0\",\"3\":\"0\"}', '0', 30, '1,M', '1', '0', '0', -1, -1, '{\"fastspring\":{\"product_id\":\"\",\"coupon\":\"\"},\"paypro\":{\"product_id\":\"\",\"coupon\":\"\"},\"paypal\":{\"plan_id\":\"\"}}', '{\"percent\":\"\",\"start_date\":\"\",\"end_date\":\"\",\"timezone\":\"Asia\\/Dhaka\",\"status\":\"0\"}', '1', '0', '0');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_api_logs`
--

DROP TABLE IF EXISTS `payment_api_logs`;
CREATE TABLE IF NOT EXISTS `payment_api_logs` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `buyer_user_id` int(11) NOT NULL,
  `call_time` datetime DEFAULT NULL,
  `payment_method` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_response` text COLLATE utf8mb4_unicode_ci,
  `error` mediumtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `buyer_user_id` (`buyer_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `app_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo_alt` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `favicon` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `timezone` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT 'en',
  `analytics_code` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_settings` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `auto_responder_signup_settings` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sms_settings` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `social_apps_setting` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `upload_settings` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `agency_landing_settings` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `aws_settings` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `user_id`, `app_name`, `logo`, `logo_alt`, `favicon`, `timezone`, `language`, `analytics_code`, `email_settings`, `auto_responder_signup_settings`, `sms_settings`, `social_apps_setting`, `upload_settings`, `agency_landing_settings`, `updated_at`, `aws_settings`) VALUES
(1, 1, 'HeatSketch', '', '', '', 'Asia/Dhaka', 'en', '{\"fb_pixel_id\":\"\",\"google_analytics_id\":\"\",\"tme_widget_id\":\"\",\"whatsapp_widget_id\":\"\"}', '{\"default\":\"\",\"sender_email\":null,\"sender_name\":null}', '{\"mailchimp\":[],\"sendinblue\":[],\"activecampaign\":[],\"mautic\":[]}', '', '{\"google_app_setting\":\"{\\\"google_api_key\\\":\\\"\\\"}\",\"get_ip_info\":\"{\\\"ip_info_token\\\":\\\"\\\",\\\"ip2Location_api_key\\\":\\\"\\\"}\"}', '{\"bot\":{\"image\":\"1\",\"video\":\"20\",\"audio\":\"5\",\"file\":\"20\"}}', '{\"header_image\":\"\",\"details_feature_1_img\":\"\",\"details_feature_2_img\":\"\",\"details_feature_3_img\":\"\",\"details_feature_4_img\":\"\",\"company_title\":\"Heatmap & Sessions Recording Tool\",\"company_short_description\":\"Heatmap & Sessions Recording Tool\",\"company_address\":\"Holding #127, 1st Floor, Gonokpara, Boalia, Rajshahi-6100, Bangladesh\",\"company_email\":\"\",\"company_cover_image\":\"\",\"company_keywords\":\"heatmap,session recording,live user,analytics,seo\",\"company_fb_messenger\":\"\",\"company_fb_page\":\"\",\"company_telegram_bot\":\"\",\"company_telegram_channel\":\"\",\"company_youtube_channel\":\"\",\"company_twitter_account\":\"\",\"company_instagram_account\":\"\",\"company_linkedin_channel\":\"\",\"company_support_url\":\"\",\"links_docs_url\":\"\",\"disable_landing_page\":\"0\",\"disable_ecommerce_feature\":\"0\"}', '2023-03-11 07:11:40', '{\"access_key_id\":\"\",\"secret_access_key\":\"\",\"default_region\":\"\",\"bucket\":\"\",\"endpoint\":\"\"}');

-- --------------------------------------------------------

--
-- Table structure for table `settings_email_autoresponders`
--

DROP TABLE IF EXISTS `settings_email_autoresponders`;
CREATE TABLE IF NOT EXISTS `settings_email_autoresponders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `settings_data` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings_email_autoresponder_lists`
--

DROP TABLE IF EXISTS `settings_email_autoresponder_lists`;
CREATE TABLE IF NOT EXISTS `settings_email_autoresponder_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `settings_email_autoresponder_id` int(11) NOT NULL,
  `list_name` mediumtext COLLATE utf8mb4_unicode_ci,
  `list_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `string_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `list_folder_id` int(11) NOT NULL,
  `list_total_subscribers` int(11) NOT NULL,
  `list_total_blacklisted` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `list` (`settings_email_autoresponder_id`,`list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings_email_templates`
--

DROP TABLE IF EXISTS `settings_email_templates`;
CREATE TABLE IF NOT EXISTS `settings_email_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `template_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fas fa-folder-open',
  `tooltip` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `info` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_roles` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'comma separated',
  PRIMARY KEY (`id`),
  KEY `template_type` (`template_type`),
  KEY `user_id` (`user_id`),
  KEY `access_roles` (`access_roles`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings_payments`
--

DROP TABLE IF EXISTS `settings_payments`;
CREATE TABLE IF NOT EXISTS `settings_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ecommerce_store_id` int(11) DEFAULT NULL COMMENT 'null means payment settings not ecommerce',
  `user_id` int(11) NOT NULL,
  `paypal` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `razorpay` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `paystack` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `mercadopago` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `mollie` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sslcommerz` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `senangpay` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `instamojo` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `toyyibpay` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `xendit` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `myfatoorah` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `paymaya` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `fastspring` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `paypro` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `yoomoney` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cod_enabled` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `manual_payment_status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `manual_payment_instruction` text COLLATE utf8mb4_unicode_ci,
  `currency` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `decimal_point` tinyint(4) NOT NULL,
  `thousand_comma` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `currency_position` enum('left','right') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'left',
  `updated_at` datetime NOT NULL,
  `deleted` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`ecommerce_store_id`,`user_id`) USING BTREE,
  KEY `ecommerce_store_id` (`ecommerce_store_id`),
  KEY `zv0fyow7ez789lh41sb5` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `settings_payments` (`id`, `ecommerce_store_id`, `user_id`, `paypal`, `stripe`, `razorpay`, `paystack`, `mercadopago`, `mollie`, `sslcommerz`, `senangpay`, `instamojo`, `toyyibpay`, `xendit`, `myfatoorah`, `paymaya`, `fastspring`, `paypro`, `yoomoney`, `cod_enabled`, `manual_payment_status`, `manual_payment_instruction`, `currency`, `decimal_point`, `thousand_comma`, `currency_position`, `updated_at`, `deleted`) VALUES
(1, NULL, 1, '{\"paypal_client_id\":\"\",\"paypal_client_secret\":\"\",\"paypal_app_id\":\"\",\"paypal_status\":\"0\",\"paypal_mode\":\"sandbox\",\"paypal_payment_type\":\"manual\"}', '{\"stripe_secret_key\":\"\",\"stripe_publishable_key\":\"\",\"stripe_status\":\"0\"}', '{\"razorpay_key_id\":\"\",\"razorpay_key_secret\":\"\",\"razorpay_status\":\"0\"}', '{\"paystack_secret_key\":\"\",\"paystack_public_key\":\"\",\"paystack_status\":\"0\"}', '{\"mercadopago_public_key\":\"\",\"mercadopago_access_token\":\"\",\"mercadopago_country\":\"br\",\"mercadopago_status\":\"0\"}', '{\"mollie_api_key\":\"\",\"mollie_status\":\"0\"}', '{\"sslcommerz_store_id\":\"xeron5fe8151f5dfd1\",\"sslcommerz_store_password\":\"xeron5fe8151f5dfd1@ssl\",\"sslcommerz_status\":\"0\",\"sslcommerz_mode\":\"live\"}', '{\"senangpay_merchent_id\":\"\",\"senangpay_secret_key\":\"\",\"senangpay_status\":\"0\",\"senangpay_mode\":\"sandbox\"}', '{\"instamojo_api_key\":\"\",\"instamojo_auth_token\":\"\",\"instamojo_status\":\"0\",\"instamojo_mode\":\"sandbox\"}', '{\"toyyibpay_secret_key\":\"\",\"toyyibpay_category_code\":\"\",\"toyyibpay_status\":\"0\",\"toyyibpay_mode\":\"sandbox\"}', '{\"xendit_secret_api_key\":\"\",\"xendit_status\":\"0\"}', '{\"myfatoorah_api_key\":\"\",\"myfatoorah_status\":\"0\",\"myfatoorah_mode\":\"sandbox\"}', '{\"paymaya_public_key\":\"\",\"paymaya_secret_key\":\"\",\"paymaya_status\":\"0\",\"paymaya_mode\":\"sandbox\"}', '{\"fastspring_api_src\":\"\",\"fastspring_store_front\":\"\",\"fastspring_status\":\"0\"}', '{\"paypro_secret_key\":\"\",\"paypro_validation_key\":\"\",\"paypro_mode\":\"sandbox\",\"paypro_template_id\":\"\",\"paypro_status\":\"0\"}', '{\"yoomoney_shop_id\":\"\",\"yoomoney_secret_key\":\"\",\"yoomoney_status\":\"0\"}', '0', '1', '<p>Test</p>', 'USD', 2, '1', 'left', '2023-03-06 09:05:00', '0');


-- --------------------------------------------------------



--
-- Table structure for table `settings_sms_emails`
--

DROP TABLE IF EXISTS `settings_sms_emails`;
CREATE TABLE IF NOT EXISTS `settings_sms_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_type` enum('sms','email') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'email',
  `user_id` int(11) NOT NULL,
  `settings_data` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_email_send_logs`
--

DROP TABLE IF EXISTS `sms_email_send_logs`;
CREATE TABLE IF NOT EXISTS `sms_email_send_logs` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `email_api_id` int(11) DEFAULT NULL,
  `settings_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `response` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`api_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_logs`
--

DROP TABLE IF EXISTS `transaction_logs`;
CREATE TABLE IF NOT EXISTS `transaction_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `buyer_user_id` int(11) NOT NULL,
  `verify_status` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `buyer_email` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paid_currency` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paid_at` datetime NOT NULL,
  `payment_method` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_id` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paid_amount` float NOT NULL,
  `cycle_start_date` date DEFAULT NULL,
  `cycle_expired_date` date DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `package_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `response_source` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `paypal_txn_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_url` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `buyer_user_id` (`buyer_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_manual_logs`
--

DROP TABLE IF EXISTS `transaction_manual_logs`;
CREATE TABLE IF NOT EXISTS `transaction_manual_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `buyer_user_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `transaction_id` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paid_amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paid_currency` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `additional_info` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `thm_user_id` (`user_id`),
  KEY `buyer_user_id` (`buyer_user_id`),
  KEY `package_id` (`package_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

DROP TABLE IF EXISTS `translations`;
CREATE TABLE IF NOT EXISTS `translations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `language_id` int(10) UNSIGNED NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `key` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `translations_language_id_foreign` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `update_list`
--

DROP TABLE IF EXISTS `update_list`;
CREATE TABLE IF NOT EXISTS `update_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `files` text NOT NULL,
  `sql_query` text NOT NULL,
  `update_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `usage_logs`
--

DROP TABLE IF EXISTS `usage_logs`;
CREATE TABLE IF NOT EXISTS `usage_logs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `usage_month` int(11) NOT NULL,
  `usage_year` year(4) NOT NULL,
  `usage_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`,`user_id`),
  KEY `c7zsc35trvp4lcmgyi42` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `profile_pic` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `purchase_date` datetime DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `last_login_ip` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activation_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_user_id` int(11) NOT NULL DEFAULT '1',
  `user_type` enum('Member','Admin','Agent','Manager','Team','Affiliate') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Member',
  `agent_has_whitelabel` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `agent_domain` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agent_mailgun_username` mediumtext COLLATE utf8mb4_unicode_ci,
  `agent_mailgun_password` mediumtext COLLATE utf8mb4_unicode_ci,
  `package_id` int(11) DEFAULT NULL,
  `expired_date` datetime DEFAULT NULL,
  `status` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `bot_status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `enable_forum_thread` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `enable_blog_comment` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `enable_ticketing` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `deleted` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `timezone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vat_no` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subscription_enabled` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `subscription_data` text COLLATE utf8mb4_unicode_ci,
  `last_payment_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `allowed_domain_ids` text COLLATE utf8mb4_unicode_ci,
  `is_affiliate` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `under_which_affiliate_user` int(11) DEFAULT NULL,
  `total_earn` double DEFAULT NULL,
  `payment_commission` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `payment_type` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fixed_amount` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_recurring` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `percentage` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `affiliate_commission_given` double DEFAULT NULL,
  `paypal_subscriber_id` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paypal_next_check_time` datetime DEFAULT NULL,
  `paypal_processing` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `parent_user_id` (`parent_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `mobile`, `email_verified_at`, `password`, `remember_token`, `address`, `profile_pic`, `created_at`, `updated_at`, `purchase_date`, `last_login_at`, `last_login_ip`, `activation_code`, `parent_user_id`, `user_type`, `agent_has_whitelabel`, `agent_domain`, `agent_mailgun_username`, `agent_mailgun_password`, `package_id`, `expired_date`, `status`, `bot_status`, `enable_forum_thread`, `enable_blog_comment`, `enable_ticketing`, `deleted`, `timezone`, `language`, `vat_no`, `subscription_enabled`, `subscription_data`, `last_payment_method`, `comment`, `allowed_domain_ids`, `is_affiliate`, `under_which_affiliate_user`, `total_earn`, `payment_commission`, `payment_type`, `fixed_amount`, `is_recurring`, `percentage`, `affiliate_commission_given`, `paypal_subscriber_id`, `paypal_next_check_time`, `paypal_processing`) VALUES
(1, 'Admin', 'admin@gmail.com', '', '2022-04-12 10:07:10', '$2y$10$LEnPv7azu39xTMe3Vlhi7.PBAOeg6zS282ha335OxpPGMWcspKC1y', 'nWbrEb9AzqjVnucNzH3FzEkV20J6quqBzkPjE9ZMJ3sOebdInbqHPMCH2Gi1', '', 'C:\\Users\\mostofa\\AppData\\Local\\Temp\\phpE1C5.tmp', '2015-12-31 12:00:00', '2021-08-11 19:39:20', NULL, '2023-04-06 05:22:22', '127.0.0.1', NULL, 0, 'Admin', '0', NULL, NULL, NULL, 94, '2033-04-09 00:00:00', '1', '1', '1', '1', '1', '0', 'Asia/Dhaka', NULL, NULL, '0', NULL, '', NULL, NULL, '0', 0, 0, '0', '', '', '0', '', 0, '', NULL, '0');

-- --------------------------------------------------------

--
-- Table structure for table `version`
--

DROP TABLE IF EXISTS `version`;
CREATE TABLE IF NOT EXISTS `version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `current` enum('1','0') NOT NULL DEFAULT '1',
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `version` (`version`),
  KEY `Current` (`current`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_analysis_domain_list`
--

DROP TABLE IF EXISTS `visitor_analysis_domain_list`;
CREATE TABLE IF NOT EXISTS `visitor_analysis_domain_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `domain_name` varchar(200) NOT NULL,
  `domain_prefix` enum('https://','http://') NOT NULL DEFAULT 'https://',
  `domain_code` varchar(50) NOT NULL,
  `js_code` text NOT NULL,
  `pause_play` enum('pause','play') NOT NULL DEFAULT 'play',
  `excluded_ip` varchar(500) DEFAULT NULL,
  `add_date` date NOT NULL,
  `status` enum('on','off') NOT NULL DEFAULT 'on',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `screenshot` longtext,
  `recording_option` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain code` (`domain_code`),
  KEY `user id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_analysis_domain_list_data_even_even`
--

DROP TABLE IF EXISTS `visitor_analysis_domain_list_data_even_even`;
CREATE TABLE IF NOT EXISTS `visitor_analysis_domain_list_data_even_even` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `domain_list_id` int(11) NOT NULL,
  `domain_code` varchar(50) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `country` varchar(250) NOT NULL,
  `city` varchar(250) NOT NULL,
  `org` varchar(250) NOT NULL,
  `latitude` varchar(250) NOT NULL,
  `longitude` varchar(250) NOT NULL,
  `postal` varchar(250) NOT NULL,
  `os` varchar(250) NOT NULL,
  `device` varchar(250) NOT NULL,
  `browser_name` varchar(200) NOT NULL,
  `browser_version` varchar(200) NOT NULL,
  `referrer` varchar(200) DEFAULT NULL,
  `visit_url` varchar(400) NOT NULL,
  `page_title` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cookie_value` varchar(50) DEFAULT NULL,
  `session_value` varchar(50) DEFAULT NULL,
  `is_new` int(11) NOT NULL,
  `entry_time` datetime NOT NULL,
  `last_engagement_time` datetime NOT NULL,
  `total_stay_time` int(11) NOT NULL,
  `browser_rawdata` varchar(250) NOT NULL,
  `height` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `click_move_scroll` enum('click','move','scroll') DEFAULT NULL,
  `total_clicks` bigint(20) UNSIGNED NOT NULL,
  `json_data` varchar(300) DEFAULT NULL,
  `last_cron_time` datetime NOT NULL,
  `cron_processing` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_session` (`visit_url`,`cookie_value`,`session_value`,`click_move_scroll`),
  KEY `user domain_list_id` (`user_id`,`domain_list_id`,`last_engagement_time`) USING BTREE,
  KEY `for cron job entry_time` (`entry_time`),
  KEY `visitor_analysis_domain_list_data_even_even` (`domain_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_analysis_domain_list_data_even_odd`
--

DROP TABLE IF EXISTS `visitor_analysis_domain_list_data_even_odd`;
CREATE TABLE IF NOT EXISTS `visitor_analysis_domain_list_data_even_odd` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `domain_list_id` int(11) NOT NULL,
  `domain_code` varchar(50) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `country` varchar(250) NOT NULL,
  `city` varchar(250) NOT NULL,
  `org` varchar(250) NOT NULL,
  `latitude` varchar(250) NOT NULL,
  `longitude` varchar(250) NOT NULL,
  `postal` varchar(250) NOT NULL,
  `os` varchar(250) NOT NULL,
  `device` varchar(250) NOT NULL,
  `browser_name` varchar(200) NOT NULL,
  `browser_version` varchar(200) NOT NULL,
  `referrer` varchar(200) DEFAULT NULL,
  `visit_url` varchar(400) NOT NULL,
  `page_title` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cookie_value` varchar(50) DEFAULT NULL,
  `session_value` varchar(50) DEFAULT NULL,
  `is_new` int(11) NOT NULL,
  `entry_time` datetime NOT NULL,
  `last_engagement_time` datetime NOT NULL,
  `total_stay_time` int(11) NOT NULL,
  `browser_rawdata` varchar(250) NOT NULL,
  `height` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `click_move_scroll` enum('click','move','scroll') DEFAULT NULL,
  `total_clicks` bigint(20) UNSIGNED NOT NULL,
  `json_data` varchar(300) DEFAULT NULL,
  `last_cron_time` datetime NOT NULL,
  `cron_processing` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_session` (`visit_url`,`cookie_value`,`session_value`,`click_move_scroll`),
  KEY `user domain_list_id` (`user_id`,`domain_list_id`,`last_engagement_time`) USING BTREE,
  KEY `for cron job entry_time` (`entry_time`),
  KEY `visitor_analysis_domain_list_data_even_odd` (`domain_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_analysis_domain_list_data_odd_even`
--

DROP TABLE IF EXISTS `visitor_analysis_domain_list_data_odd_even`;
CREATE TABLE IF NOT EXISTS `visitor_analysis_domain_list_data_odd_even` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `domain_list_id` int(11) NOT NULL,
  `domain_code` varchar(50) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `country` varchar(250) NOT NULL,
  `city` varchar(250) NOT NULL,
  `org` varchar(250) NOT NULL,
  `latitude` varchar(250) NOT NULL,
  `longitude` varchar(250) NOT NULL,
  `postal` varchar(250) NOT NULL,
  `os` varchar(250) NOT NULL,
  `device` varchar(250) NOT NULL,
  `browser_name` varchar(200) NOT NULL,
  `browser_version` varchar(200) NOT NULL,
  `referrer` varchar(200) DEFAULT NULL,
  `visit_url` varchar(400) NOT NULL,
  `page_title` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cookie_value` varchar(50) DEFAULT NULL,
  `session_value` varchar(50) DEFAULT NULL,
  `is_new` int(11) NOT NULL,
  `entry_time` datetime NOT NULL,
  `last_engagement_time` datetime NOT NULL,
  `total_stay_time` int(11) NOT NULL,
  `browser_rawdata` varchar(250) NOT NULL,
  `height` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `click_move_scroll` enum('click','move','scroll') DEFAULT NULL,
  `total_clicks` bigint(20) UNSIGNED NOT NULL,
  `json_data` varchar(300) DEFAULT NULL,
  `last_cron_time` datetime NOT NULL,
  `cron_processing` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_session` (`visit_url`,`cookie_value`,`session_value`,`click_move_scroll`),
  KEY `user domain_list_id` (`user_id`,`domain_list_id`,`last_engagement_time`) USING BTREE,
  KEY `for cron job entry_time` (`entry_time`),
  KEY `visitor_analysis_domain_list_data_odd_even` (`domain_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_analysis_domain_list_data_odd_odd`
--

DROP TABLE IF EXISTS `visitor_analysis_domain_list_data_odd_odd`;
CREATE TABLE IF NOT EXISTS `visitor_analysis_domain_list_data_odd_odd` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `domain_list_id` int(11) NOT NULL,
  `domain_code` varchar(50) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `country` varchar(250) NOT NULL,
  `city` varchar(250) NOT NULL,
  `org` varchar(250) NOT NULL,
  `latitude` varchar(250) NOT NULL,
  `longitude` varchar(250) NOT NULL,
  `postal` varchar(250) NOT NULL,
  `os` varchar(250) NOT NULL,
  `device` varchar(250) NOT NULL,
  `browser_name` varchar(200) NOT NULL,
  `browser_version` varchar(200) NOT NULL,
  `referrer` varchar(200) DEFAULT NULL,
  `visit_url` varchar(400) NOT NULL,
  `page_title` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cookie_value` varchar(50) DEFAULT NULL,
  `session_value` varchar(50) DEFAULT NULL,
  `is_new` int(11) NOT NULL,
  `entry_time` datetime NOT NULL,
  `last_engagement_time` datetime NOT NULL,
  `total_stay_time` int(11) NOT NULL,
  `browser_rawdata` varchar(250) NOT NULL,
  `height` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `click_move_scroll` enum('click','move','scroll') DEFAULT NULL,
  `total_clicks` bigint(20) UNSIGNED NOT NULL,
  `json_data` varchar(300) DEFAULT NULL,
  `last_cron_time` datetime NOT NULL,
  `cron_processing` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_session` (`visit_url`,`cookie_value`,`session_value`,`click_move_scroll`),
  KEY `for cron job entry_time` (`entry_time`),
  KEY `user domain_list_id` (`user_id`,`domain_list_id`,`last_engagement_time`) USING BTREE,
  KEY `visitor_analysis_domain_list_data_odd_odd` (`domain_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_analysis_session_data_even_even`
--

DROP TABLE IF EXISTS `visitor_analysis_session_data_even_even`;
CREATE TABLE IF NOT EXISTS `visitor_analysis_session_data_even_even` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `domain_list_id` int(11) NOT NULL,
  `domain_code` varchar(50) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `country` varchar(250) NOT NULL,
  `city` varchar(250) NOT NULL,
  `org` varchar(250) NOT NULL,
  `latitude` varchar(250) NOT NULL,
  `longitude` varchar(250) NOT NULL,
  `postal` varchar(250) NOT NULL,
  `os` varchar(250) NOT NULL,
  `device` varchar(250) NOT NULL,
  `browser_name` varchar(200) NOT NULL,
  `browser_version` varchar(200) NOT NULL,
  `referrer` varchar(200) DEFAULT NULL,
  `visit_url` longtext,
  `page_title` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cookie_value` varchar(100) DEFAULT NULL,
  `session_value` varchar(100) DEFAULT NULL,
  `is_new` int(11) NOT NULL,
  `entry_time` datetime NOT NULL,
  `last_engagement_time` datetime NOT NULL,
  `total_stay_time` int(11) NOT NULL,
  `browser_rawdata` varchar(250) NOT NULL,
  `session_data` varchar(300) DEFAULT NULL,
  `last_cron_time` datetime NOT NULL,
  `cron_processing` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `for cron job` (`entry_time`),
  KEY `user domain` (`user_id`,`domain_list_id`,`last_engagement_time`) USING BTREE,
  KEY `visitor_analysis_session_data_even_even` (`domain_list_id`),
  KEY `domain_code session cookie` (`domain_code`,`session_value`,`cookie_value`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_analysis_session_data_even_odd`
--

DROP TABLE IF EXISTS `visitor_analysis_session_data_even_odd`;
CREATE TABLE IF NOT EXISTS `visitor_analysis_session_data_even_odd` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `domain_list_id` int(11) NOT NULL,
  `domain_code` varchar(50) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `country` varchar(250) NOT NULL,
  `city` varchar(250) NOT NULL,
  `org` varchar(250) NOT NULL,
  `latitude` varchar(250) NOT NULL,
  `longitude` varchar(250) NOT NULL,
  `postal` varchar(250) NOT NULL,
  `os` varchar(250) NOT NULL,
  `device` varchar(250) NOT NULL,
  `browser_name` varchar(200) NOT NULL,
  `browser_version` varchar(200) NOT NULL,
  `referrer` varchar(200) DEFAULT NULL,
  `visit_url` longtext,
  `page_title` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cookie_value` varchar(100) DEFAULT NULL,
  `session_value` varchar(100) DEFAULT NULL,
  `is_new` int(11) NOT NULL,
  `entry_time` datetime NOT NULL,
  `last_engagement_time` datetime NOT NULL,
  `total_stay_time` int(11) NOT NULL,
  `browser_rawdata` varchar(250) NOT NULL,
  `session_data` varchar(300) DEFAULT NULL,
  `last_cron_time` datetime NOT NULL,
  `cron_processing` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `for cron job` (`entry_time`),
  KEY `user domain` (`user_id`,`domain_list_id`,`last_engagement_time`) USING BTREE,
  KEY `visitor_analysis_session_data_even_odd` (`domain_list_id`),
  KEY `domain_code session cookie` (`domain_code`,`session_value`,`cookie_value`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_analysis_session_data_odd_even`
--

DROP TABLE IF EXISTS `visitor_analysis_session_data_odd_even`;
CREATE TABLE IF NOT EXISTS `visitor_analysis_session_data_odd_even` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `domain_list_id` int(11) NOT NULL,
  `domain_code` varchar(50) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `country` varchar(250) NOT NULL,
  `city` varchar(250) NOT NULL,
  `org` varchar(250) NOT NULL,
  `latitude` varchar(250) NOT NULL,
  `longitude` varchar(250) NOT NULL,
  `postal` varchar(250) NOT NULL,
  `os` varchar(250) NOT NULL,
  `device` varchar(250) NOT NULL,
  `browser_name` varchar(200) NOT NULL,
  `browser_version` varchar(200) NOT NULL,
  `referrer` varchar(200) DEFAULT NULL,
  `visit_url` longtext,
  `page_title` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cookie_value` varchar(100) DEFAULT NULL,
  `session_value` varchar(100) DEFAULT NULL,
  `is_new` int(11) NOT NULL,
  `entry_time` datetime NOT NULL,
  `last_engagement_time` datetime NOT NULL,
  `total_stay_time` int(11) NOT NULL,
  `browser_rawdata` varchar(250) NOT NULL,
  `session_data` varchar(300) DEFAULT NULL,
  `last_cron_time` datetime NOT NULL,
  `cron_processing` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `for cron job` (`entry_time`),
  KEY `user domain` (`user_id`,`domain_list_id`,`last_engagement_time`) USING BTREE,
  KEY `visitor_analysis_session_data_odd_even` (`domain_list_id`),
  KEY `domain_code session cookie` (`domain_code`,`session_value`,`cookie_value`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_analysis_session_data_odd_odd`
--

DROP TABLE IF EXISTS `visitor_analysis_session_data_odd_odd`;
CREATE TABLE IF NOT EXISTS `visitor_analysis_session_data_odd_odd` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `domain_list_id` int(11) NOT NULL,
  `domain_code` varchar(50) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `country` varchar(250) NOT NULL,
  `city` varchar(250) NOT NULL,
  `org` varchar(250) NOT NULL,
  `latitude` varchar(250) NOT NULL,
  `longitude` varchar(250) NOT NULL,
  `postal` varchar(250) NOT NULL,
  `os` varchar(250) NOT NULL,
  `device` varchar(250) NOT NULL,
  `browser_name` varchar(200) NOT NULL,
  `browser_version` varchar(200) NOT NULL,
  `referrer` varchar(200) DEFAULT NULL,
  `visit_url` longtext,
  `page_title` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cookie_value` varchar(100) DEFAULT NULL,
  `session_value` varchar(100) DEFAULT NULL,
  `is_new` int(11) NOT NULL,
  `entry_time` datetime NOT NULL,
  `last_engagement_time` datetime NOT NULL,
  `total_stay_time` int(11) NOT NULL,
  `browser_rawdata` varchar(250) NOT NULL,
  `session_data` varchar(300) DEFAULT NULL,
  `last_cron_time` datetime NOT NULL,
  `cron_processing` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `for cron job` (`entry_time`),
  KEY `user domain` (`user_id`,`domain_list_id`,`last_engagement_time`) USING BTREE,
  KEY `visitor_analysis_session_data_odd_odd` (`domain_list_id`),
  KEY `domain_code session cookie` (`domain_code`,`session_value`,`cookie_value`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_analysis_temp_heatmap_data_even_even`
--

DROP TABLE IF EXISTS `visitor_analysis_temp_heatmap_data_even_even`;
CREATE TABLE IF NOT EXISTS `visitor_analysis_temp_heatmap_data_even_even` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `list_data_table_id` bigint(20) UNSIGNED NOT NULL,
  `click_move_scroll` enum('click','move','scroll') DEFAULT NULL,
  `json_data` longtext,
  PRIMARY KEY (`id`),
  KEY `list data table id` (`id`),
  KEY `visitor_analysis_temp_heatmap_data_even_even` (`list_data_table_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_analysis_temp_heatmap_data_even_odd`
--

DROP TABLE IF EXISTS `visitor_analysis_temp_heatmap_data_even_odd`;
CREATE TABLE IF NOT EXISTS `visitor_analysis_temp_heatmap_data_even_odd` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `list_data_table_id` bigint(20) UNSIGNED NOT NULL,
  `click_move_scroll` enum('click','move','scroll') DEFAULT NULL,
  `json_data` longtext,
  PRIMARY KEY (`id`),
  KEY `list data table id` (`id`),
  KEY `visitor_analysis_temp_heatmap_data_even_odd` (`list_data_table_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_analysis_temp_heatmap_data_odd_even`
--

DROP TABLE IF EXISTS `visitor_analysis_temp_heatmap_data_odd_even`;
CREATE TABLE IF NOT EXISTS `visitor_analysis_temp_heatmap_data_odd_even` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `list_data_table_id` bigint(20) UNSIGNED NOT NULL,
  `click_move_scroll` enum('click','move','scroll') DEFAULT NULL,
  `json_data` longtext,
  PRIMARY KEY (`id`),
  KEY `list data table id` (`id`),
  KEY `visitor_analysis_temp_heatmap_data_odd_even` (`list_data_table_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_analysis_temp_heatmap_data_odd_odd`
--

DROP TABLE IF EXISTS `visitor_analysis_temp_heatmap_data_odd_odd`;
CREATE TABLE IF NOT EXISTS `visitor_analysis_temp_heatmap_data_odd_odd` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `list_data_table_id` bigint(20) UNSIGNED NOT NULL,
  `click_move_scroll` enum('click','move','scroll') DEFAULT NULL,
  `json_data` longtext,
  PRIMARY KEY (`id`),
  KEY `list data table id` (`id`),
  KEY `visitor_analysis_temp_heatmap_data_odd_odd` (`list_data_table_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_analysis_temp_sessions_even_even`
--

DROP TABLE IF EXISTS `visitor_analysis_temp_sessions_even_even`;
CREATE TABLE IF NOT EXISTS `visitor_analysis_temp_sessions_even_even` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_data_table_id` bigint(20) UNSIGNED NOT NULL,
  `visit_url` varchar(300) NOT NULL,
  `session_data` longtext CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `session table id` (`session_data_table_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_analysis_temp_sessions_even_odd`
--

DROP TABLE IF EXISTS `visitor_analysis_temp_sessions_even_odd`;
CREATE TABLE IF NOT EXISTS `visitor_analysis_temp_sessions_even_odd` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_data_table_id` bigint(20) UNSIGNED NOT NULL,
  `visit_url` varchar(300) NOT NULL,
  `session_data` longtext CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `session table id` (`session_data_table_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_analysis_temp_sessions_odd_even`
--

DROP TABLE IF EXISTS `visitor_analysis_temp_sessions_odd_even`;
CREATE TABLE IF NOT EXISTS `visitor_analysis_temp_sessions_odd_even` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_data_table_id` bigint(20) UNSIGNED NOT NULL,
  `visit_url` varchar(300) NOT NULL,
  `session_data` longtext CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `session table id` (`session_data_table_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_analysis_temp_sessions_odd_odd`
--

DROP TABLE IF EXISTS `visitor_analysis_temp_sessions_odd_odd`;
CREATE TABLE IF NOT EXISTS `visitor_analysis_temp_sessions_odd_odd` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_data_table_id` bigint(20) UNSIGNED NOT NULL,
  `visit_url` varchar(300) NOT NULL,
  `session_data` longtext CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `session table id` (`session_data_table_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `wasabi_files_for_heatmap`
--

DROP TABLE IF EXISTS `wasabi_files_for_heatmap`;
CREATE TABLE IF NOT EXISTS `wasabi_files_for_heatmap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `domain_code` varchar(50) NOT NULL,
  `storage_date` date DEFAULT NULL,
  `click_move_scroll` enum('click','move','scroll') NOT NULL,
  `visit_url` varchar(500) NOT NULL,
  `file_name` varchar(400) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `search query` (`domain_code`,`storage_date`,`click_move_scroll`,`visit_url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs` ADD FULLTEXT KEY `blog_content` (`blog_content`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `affiliate_payment_settings`
--
ALTER TABLE `affiliate_payment_settings`
  ADD CONSTRAINT `affiliate_payment_settings1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `affiliate_requests`
--
ALTER TABLE `affiliate_requests`
  ADD CONSTRAINT `affiliate_requests1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `affiliate_withdrawal_methods`
--
ALTER TABLE `affiliate_withdrawal_methods`
  ADD CONSTRAINT `affiliate id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `affiliate_withdrawal_requests`
--
ALTER TABLE `affiliate_withdrawal_requests`
  ADD CONSTRAINT `affiliate_withdrawal_requests1` FOREIGN KEY (`method_id`) REFERENCES `affiliate_withdrawal_methods` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `afiiliate Id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_comments`
--
ALTER TABLE `blog_comments`
  ADD CONSTRAINT `blog_comments_blog_cascade` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `blog_comments_parent_comment_cascade` FOREIGN KEY (`parent_blog_comment_id`) REFERENCES `blog_comments` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `blog_comments_user_cascade` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `domain_screenshot`
--
ALTER TABLE `domain_screenshot`
  ADD CONSTRAINT `domain_screenshot1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `packages`
--
ALTER TABLE `packages`
  ADD CONSTRAINT `j4imv733ji1t3jkcc5xn` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `payment_api_logs`
--
ALTER TABLE `payment_api_logs`
  ADD CONSTRAINT `j4imv733ji1t3jkcc5xp` FOREIGN KEY (`buyer_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `j4imv733ji1t3kk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `settings_email_autoresponders`
--
ALTER TABLE `settings_email_autoresponders`
  ADD CONSTRAINT `j4imv733ji1t30o` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `settings_email_autoresponder_lists`
--
ALTER TABLE `settings_email_autoresponder_lists`
  ADD CONSTRAINT `j4imv733ji1t366` FOREIGN KEY (`settings_email_autoresponder_id`) REFERENCES `settings_email_autoresponders` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `settings_email_templates`
--
ALTER TABLE `settings_email_templates`
  ADD CONSTRAINT `j4imv733ji1t399` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `settings_payments`
--
ALTER TABLE `settings_payments`
  ADD CONSTRAINT `zv0fyow7ez789lh41sb5` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;


--
-- Constraints for table `settings_sms_emails`
--
ALTER TABLE `settings_sms_emails`
  ADD CONSTRAINT `zv0fyow7ez789lh41sb0` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `sms_email_send_logs`
--
ALTER TABLE `sms_email_send_logs`
  ADD CONSTRAINT `zv0fyow7ez789lh41sb2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `transaction_logs`
--
ALTER TABLE `transaction_logs`
  ADD CONSTRAINT `c7zsc35trvp4lcmgyi46` FOREIGN KEY (`buyer_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `c7zsc35trvp4lcmgyi47` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `transaction_manual_logs`
--
ALTER TABLE `transaction_manual_logs`
  ADD CONSTRAINT `c7zsc35trvp4lcmgyi43` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `c7zsc35trvp4lcmgyi44` FOREIGN KEY (`buyer_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `translations`
--
ALTER TABLE `translations`
  ADD CONSTRAINT `translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`);

--
-- Constraints for table `usage_logs`
--
ALTER TABLE `usage_logs`
  ADD CONSTRAINT `c7zsc35trvp4lcmgyi42` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `visitor_analysis_domain_list`
--
ALTER TABLE `visitor_analysis_domain_list`
  ADD CONSTRAINT `visitor_analysis_domain_list1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `visitor_analysis_domain_list_data_even_even`
--
ALTER TABLE `visitor_analysis_domain_list_data_even_even`
  ADD CONSTRAINT `visitor_analysis_domain_list_data_even_even` FOREIGN KEY (`domain_list_id`) REFERENCES `visitor_analysis_domain_list` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `visitor_analysis_domain_list_data_even_odd`
--
ALTER TABLE `visitor_analysis_domain_list_data_even_odd`
  ADD CONSTRAINT `visitor_analysis_domain_list_data_even_odd` FOREIGN KEY (`domain_list_id`) REFERENCES `visitor_analysis_domain_list` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `visitor_analysis_domain_list_data_odd_even`
--
ALTER TABLE `visitor_analysis_domain_list_data_odd_even`
  ADD CONSTRAINT `visitor_analysis_domain_list_data_odd_even` FOREIGN KEY (`domain_list_id`) REFERENCES `visitor_analysis_domain_list` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `visitor_analysis_domain_list_data_odd_odd`
--
ALTER TABLE `visitor_analysis_domain_list_data_odd_odd`
  ADD CONSTRAINT `visitor_analysis_domain_list_data_odd_odd` FOREIGN KEY (`domain_list_id`) REFERENCES `visitor_analysis_domain_list` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `visitor_analysis_session_data_even_even`
--
ALTER TABLE `visitor_analysis_session_data_even_even`
  ADD CONSTRAINT `visitor_analysis_session_data_even_even` FOREIGN KEY (`domain_list_id`) REFERENCES `visitor_analysis_domain_list` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `visitor_analysis_session_data_even_odd`
--
ALTER TABLE `visitor_analysis_session_data_even_odd`
  ADD CONSTRAINT `visitor_analysis_session_data_even_odd` FOREIGN KEY (`domain_list_id`) REFERENCES `visitor_analysis_domain_list` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `visitor_analysis_session_data_odd_even`
--
ALTER TABLE `visitor_analysis_session_data_odd_even`
  ADD CONSTRAINT `visitor_analysis_session_data_odd_even` FOREIGN KEY (`domain_list_id`) REFERENCES `visitor_analysis_domain_list` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `visitor_analysis_session_data_odd_odd`
--
ALTER TABLE `visitor_analysis_session_data_odd_odd`
  ADD CONSTRAINT `visitor_analysis_session_data_odd_odd` FOREIGN KEY (`domain_list_id`) REFERENCES `visitor_analysis_domain_list` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `visitor_analysis_temp_heatmap_data_even_even`
--
ALTER TABLE `visitor_analysis_temp_heatmap_data_even_even`
  ADD CONSTRAINT `visitor_analysis_temp_heatmap_data_even_even` FOREIGN KEY (`list_data_table_id`) REFERENCES `visitor_analysis_domain_list_data_even_even` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `visitor_analysis_temp_heatmap_data_even_odd`
--
ALTER TABLE `visitor_analysis_temp_heatmap_data_even_odd`
  ADD CONSTRAINT `visitor_analysis_temp_heatmap_data_even_odd` FOREIGN KEY (`list_data_table_id`) REFERENCES `visitor_analysis_domain_list_data_even_odd` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `visitor_analysis_temp_heatmap_data_odd_even`
--
ALTER TABLE `visitor_analysis_temp_heatmap_data_odd_even`
  ADD CONSTRAINT `visitor_analysis_temp_heatmap_data_odd_even` FOREIGN KEY (`list_data_table_id`) REFERENCES `visitor_analysis_domain_list_data_odd_even` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `visitor_analysis_temp_heatmap_data_odd_odd`
--
ALTER TABLE `visitor_analysis_temp_heatmap_data_odd_odd`
  ADD CONSTRAINT `visitor_analysis_temp_heatmap_data_odd_odd` FOREIGN KEY (`list_data_table_id`) REFERENCES `visitor_analysis_domain_list_data_odd_odd` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `visitor_analysis_temp_sessions_even_even`
--
ALTER TABLE `visitor_analysis_temp_sessions_even_even`
  ADD CONSTRAINT `visitor_analysis_temp_sessions_even_even` FOREIGN KEY (`session_data_table_id`) REFERENCES `visitor_analysis_session_data_even_even` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `visitor_analysis_temp_sessions_even_odd`
--
ALTER TABLE `visitor_analysis_temp_sessions_even_odd`
  ADD CONSTRAINT `visitor_analysis_temp_sessions_even_odd` FOREIGN KEY (`session_data_table_id`) REFERENCES `visitor_analysis_session_data_even_odd` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `visitor_analysis_temp_sessions_odd_even`
--
ALTER TABLE `visitor_analysis_temp_sessions_odd_even`
  ADD CONSTRAINT `visitor_analysis_temp_sessions_odd_even` FOREIGN KEY (`session_data_table_id`) REFERENCES `visitor_analysis_session_data_odd_even` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `visitor_analysis_temp_sessions_odd_odd`
--
ALTER TABLE `visitor_analysis_temp_sessions_odd_odd`
  ADD CONSTRAINT `visitor_analysis_temp_sessions_odd_odd` FOREIGN KEY (`session_data_table_id`) REFERENCES `visitor_analysis_session_data_odd_odd` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;


ALTER TABLE `visitor_analysis_domain_list` ADD `last_validity_check_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `recording_option`; 

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
