
DROP TABLE IF EXISTS `activity_columnslogs`;
CREATE TABLE `activity_columnslogs` (
  `id` int(11) NOT NULL,
  `date_added` datetime DEFAULT NULL,
  `month` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `query` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `columns_name` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `ndtype` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `table_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_downlaod_report`
--

DROP TABLE IF EXISTS `activity_downlaod_report`;
CREATE TABLE `activity_downlaod_report` (
  `id` int(11) NOT NULL,
  `content_owner` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type_table` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `process_date_start` datetime DEFAULT NULL,
  `status_flag` tinyint(1) DEFAULT NULL,
  `status_message` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `table_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `table_type_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `process_id` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `param_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `selected_date` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `controller_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type_cate` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `title_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `downlaodType` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'normal' COMMENT 'withholding '
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_downlaod_report_test`
--

DROP TABLE IF EXISTS `activity_downlaod_report_test`;
CREATE TABLE `activity_downlaod_report_test` (
  `id` int(11) NOT NULL,
  `content_owner` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type_table` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `process_date_start` datetime DEFAULT NULL,
  `status_flag` tinyint(1) DEFAULT NULL,
  `status_message` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `table_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `table_type_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `process_id` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `param_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `selected_date` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `controller_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type_cate` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `title_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_reports`
--

DROP TABLE IF EXISTS `activity_reports`;
CREATE TABLE `activity_reports` (
  `id` bigint(20) NOT NULL,
  `table_name` varchar(512) DEFAULT NULL,
  `file_name` tinytext,
  `status_name` varchar(25) DEFAULT NULL,
  `status_flag` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `ip_address` varchar(15) DEFAULT NULL,
  `login_user` varchar(150) DEFAULT NULL,
  `raw_data` text,
  `log_file` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `atemp_label_content_owner`
--

DROP TABLE IF EXISTS `atemp_label_content_owner`;
CREATE TABLE `atemp_label_content_owner` (
  `id` int(11) NOT NULL,
  `label` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `content_owner` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cms` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `a_temp_itune_label`
--

DROP TABLE IF EXISTS `a_temp_itune_label`;
CREATE TABLE `a_temp_itune_label` (
  `id` int(11) NOT NULL,
  `label` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `client` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `channel_co_map`
--

DROP TABLE IF EXISTS `channel_co_map`;
CREATE TABLE `channel_co_map` (
  `channel` varchar(50) DEFAULT NULL,
  `partner_provided` varchar(50) DEFAULT NULL,
  `ugc` varchar(50) DEFAULT NULL,
  `channel_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `channel_co_maping`
--

DROP TABLE IF EXISTS `channel_co_maping`;
CREATE TABLE `channel_co_maping` (
  `id` int(11) NOT NULL,
  `Channel` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_provided` varchar(22) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ugc` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Channel_id` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Label` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `assetChannelID` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Label2` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CMS` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `client_youtube_shares` decimal(10,2) NOT NULL DEFAULT '0.00',
  `added_by_file` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `channel_co_maping_amazon`
--

DROP TABLE IF EXISTS `channel_co_maping_amazon`;
CREATE TABLE `channel_co_maping_amazon` (
  `id` int(11) NOT NULL,
  `title_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `partner_provided` varchar(22) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `session_id` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `assin` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `channel_co_maping_bk_2022-11-20`
--

DROP TABLE IF EXISTS `channel_co_maping_bk_2022-11-20`;
CREATE TABLE `channel_co_maping_bk_2022-11-20` (
  `id` int(11) NOT NULL,
  `Channel` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_provided` varchar(22) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ugc` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Channel_id` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Label` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `assetChannelID` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Label2` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CMS` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `client_youtube_shares` decimal(10,2) NOT NULL DEFAULT '0.00',
  `added_by_file` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `channel_co_mapping_original`
--

DROP TABLE IF EXISTS `channel_co_mapping_original`;
CREATE TABLE `channel_co_mapping_original` (
  `id` int(11) NOT NULL,
  `Channel` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_provided` varchar(22) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ugc` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Channel_id` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Label` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `assetChannelID` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Label2` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CMS` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `channel_co_map_import`
--

DROP TABLE IF EXISTS `channel_co_map_import`;
CREATE TABLE `channel_co_map_import` (
  `id` int(11) NOT NULL,
  `Channel` varchar(50) DEFAULT NULL,
  `Partner_Provided` varchar(50) DEFAULT NULL,
  `UGC` varchar(50) DEFAULT NULL,
  `Channel_id` varchar(50) DEFAULT NULL,
  `Label` varchar(50) DEFAULT NULL,
  `AssetChannelID` varchar(50) DEFAULT NULL,
  `Label2` varchar(50) DEFAULT NULL,
  `CMS` varchar(50) DEFAULT NULL,
  `client_youtube_shares` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` int(11) DEFAULT '0',
  `reason` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `client_slab_groups`
--

DROP TABLE IF EXISTS `client_slab_groups`;
CREATE TABLE `client_slab_groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `content_owner` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_slab_group_content_owner`
--

DROP TABLE IF EXISTS `client_slab_group_content_owner`;
CREATE TABLE `client_slab_group_content_owner` (
  `id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `group_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `content_owner` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_slab_percentage`
--

DROP TABLE IF EXISTS `client_slab_percentage`;
CREATE TABLE `client_slab_percentage` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL DEFAULT '0',
  `client_username` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `slab_for` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'Share Youtube',
  `from_amt` decimal(20,2) NOT NULL,
  `to_amt` decimal(20,2) NOT NULL,
  `percentage` int(11) NOT NULL,
  `group_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_master`
--

DROP TABLE IF EXISTS `cms_master`;
CREATE TABLE `cms_master` (
  `id` int(11) NOT NULL,
  `CMS` enum('ND1','ND2','ND Music','ND Kids','applemusic','itune','saavan','gaana','spotify') DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `crep_cms_clients`
--

DROP TABLE IF EXISTS `crep_cms_clients`;
CREATE TABLE `crep_cms_clients` (
  `client_id` int(11) NOT NULL,
  `client_username` varchar(255) NOT NULL,
  `client_firstname` varchar(255) NOT NULL,
  `client_lastname` varchar(255) NOT NULL,
  `pan` varchar(50) NOT NULL,
  `gst_no` varchar(50) NOT NULL,
  `address` longtext NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `source` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email1` varchar(255) NOT NULL,
  `email2` varchar(255) NOT NULL,
  `email3` varchar(255) NOT NULL,
  `email4` varchar(255) NOT NULL,
  `comments` varchar(255) NOT NULL,
  `status` int(2) NOT NULL,
  `client_type_details` longtext NOT NULL,
  `company_details` longtext NOT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `gst_per` decimal(10,2) NOT NULL DEFAULT '18.00',
  `client_youtube_shares` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `crep_cms_clients_backup`
--

DROP TABLE IF EXISTS `crep_cms_clients_backup`;
CREATE TABLE `crep_cms_clients_backup` (
  `client_id` int(11) NOT NULL,
  `client_username` varchar(255) NOT NULL,
  `client_firstname` varchar(255) NOT NULL,
  `client_lastname` varchar(255) NOT NULL,
  `pan` varchar(50) NOT NULL,
  `gst_no` varchar(50) NOT NULL,
  `address` longtext NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `source` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email1` varchar(255) NOT NULL,
  `email2` varchar(255) NOT NULL,
  `email3` varchar(255) NOT NULL,
  `email4` varchar(255) NOT NULL,
  `comments` varchar(255) NOT NULL,
  `status` int(2) NOT NULL,
  `client_type_details` longtext NOT NULL,
  `company_details` longtext NOT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `crep_cms_clients_bk`
--

DROP TABLE IF EXISTS `crep_cms_clients_bk`;
CREATE TABLE `crep_cms_clients_bk` (
  `client_id` int(11) NOT NULL,
  `client_username` varchar(255) NOT NULL,
  `client_firstname` varchar(255) NOT NULL,
  `client_lastname` varchar(255) NOT NULL,
  `pan` varchar(50) NOT NULL,
  `gst_no` varchar(50) NOT NULL,
  `address` longtext NOT NULL,
  `mobile_number` bigint(11) NOT NULL,
  `source` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email1` varchar(255) NOT NULL,
  `email2` varchar(255) NOT NULL,
  `email3` varchar(255) NOT NULL,
  `email4` varchar(255) NOT NULL,
  `comments` varchar(255) NOT NULL,
  `status` int(2) NOT NULL,
  `client_type_details` longtext NOT NULL,
  `company_details` longtext NOT NULL,
  `created_on` timestamp NULL DEFAULT NULL,
  `updated_on` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `crep_cms_clients_temp`
--

DROP TABLE IF EXISTS `crep_cms_clients_temp`;
CREATE TABLE `crep_cms_clients_temp` (
  `client_id` int(11) NOT NULL,
  `client_username` varchar(255) NOT NULL,
  `client_firstname` varchar(255) NOT NULL,
  `client_lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gst_per` decimal(10,2) NOT NULL DEFAULT '18.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `crep_cms_distributor`
--

DROP TABLE IF EXISTS `crep_cms_distributor`;
CREATE TABLE `crep_cms_distributor` (
  `distributor_id` int(11) NOT NULL,
  `distributor_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `no_of_client` int(10) NOT NULL,
  `gst_no` varchar(50) NOT NULL,
  `beneficiary_name` varchar(255) NOT NULL,
  `ifsc_code` varchar(255) NOT NULL,
  `bank_account_no` bigint(20) NOT NULL,
  `account_branch` varchar(255) NOT NULL,
  `management_fee_sharing` float NOT NULL,
  `poc_details` longtext NOT NULL,
  `comments` longtext NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `crep_cms_groups`
--

DROP TABLE IF EXISTS `crep_cms_groups`;
CREATE TABLE `crep_cms_groups` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(255) DEFAULT NULL,
  `group_rights` varchar(255) DEFAULT NULL,
  `right_on_module` varchar(255) NOT NULL,
  `right_on_submodule` text,
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `crep_cms_ip_access`
--

DROP TABLE IF EXISTS `crep_cms_ip_access`;
CREATE TABLE `crep_cms_ip_access` (
  `id` int(50) NOT NULL,
  `ip_address` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `crep_cms_modules`
--

DROP TABLE IF EXISTS `crep_cms_modules`;
CREATE TABLE `crep_cms_modules` (
  `module_id` int(11) NOT NULL,
  `module_name` varchar(255) DEFAULT NULL,
  `module_pages` text,
  `module_status` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `submodule_virtual_name` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `crep_cms_rates`
--

DROP TABLE IF EXISTS `crep_cms_rates`;
CREATE TABLE `crep_cms_rates` (
  `rate_id` int(11) NOT NULL,
  `rate` longtext NOT NULL,
  `month` date DEFAULT NULL,
  `source` int(11) NOT NULL,
  `created_on` timestamp NULL DEFAULT NULL,
  `updated_on` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL,
  `last_update` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `crep_cms_user`
--

DROP TABLE IF EXISTS `crep_cms_user`;
CREATE TABLE `crep_cms_user` (
  `user_id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` bigint(11) DEFAULT NULL,
  `department` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `salt` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `groups` varchar(255) NOT NULL,
  `comments` longtext NOT NULL,
  `rights` varchar(100) NOT NULL DEFAULT '1,2',
  `image` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0-inactive / 1- active/ 2-deleted',
  `token` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `crep_cms_user_backup`
--

DROP TABLE IF EXISTS `crep_cms_user_backup`;
CREATE TABLE `crep_cms_user_backup` (
  `user_id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` bigint(11) DEFAULT NULL,
  `department` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `salt` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `groups` varchar(255) NOT NULL,
  `comments` longtext NOT NULL,
  `rights` varchar(100) NOT NULL DEFAULT '1,2',
  `image` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0-inactive / 1- active/ 2-deleted',
  `token` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `deleteme`
--

DROP TABLE IF EXISTS `deleteme`;
CREATE TABLE `deleteme` (
  `asset_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monthly_exchange_rate`
--

DROP TABLE IF EXISTS `monthly_exchange_rate`;
CREATE TABLE `monthly_exchange_rate` (
  `rate_id` int(11) NOT NULL,
  `month_year` varchar(50) DEFAULT NULL,
  `rates_json` longtext NOT NULL,
  `created_on` timestamp NULL DEFAULT NULL,
  `updated_on` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `monthly_rate_saavan_gaana_other`
--

DROP TABLE IF EXISTS `monthly_rate_saavan_gaana_other`;
CREATE TABLE `monthly_rate_saavan_gaana_other` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `Ad_Supported_Revenue` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Subscription_Revenue` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Trial_Streams_Revenue` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `status_activation` int(11) DEFAULT NULL COMMENT '1: activation table created so it should not be updated or deleted',
  `date_added` datetime DEFAULT NULL,
  `date_edit` datetime DEFAULT NULL,
  `free_playout_revenue` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `paid_playout_revenue` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `ndtype` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newreport_bk`
--

DROP TABLE IF EXISTS `newreport_bk`;
CREATE TABLE `newreport_bk` (
  `id` int(11) NOT NULL,
  `adjustmentType` timestamp NOT NULL,
  `country` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `videoID` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `channelID` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `assetID` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `assetChannelID` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `assetType` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cutsomID` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contentType` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `policy` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `claimType` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `claimOrigin` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ownedViews` int(10) NOT NULL,
  `youtubeRevenueSplitAuction` decimal(18,6) NOT NULL,
  `youtubeRevenueSplitReserved` decimal(18,6) NOT NULL,
  `youtubeRevenueSplitPartnerSoldYoutubeServed` decimal(18,6) NOT NULL,
  `youtubeRevenueSplitPartnerSoldPartnerServed` decimal(18,6) NOT NULL,
  `youtubeRevenueSplit` decimal(18,6) NOT NULL,
  `partnerRevenueAuction` decimal(18,6) NOT NULL,
  `partnerRevenueReserved` decimal(18,6) NOT NULL,
  `partnerRevenuePartnerSoldYouTubeServed` decimal(18,6) NOT NULL,
  `partnerRevenuePartnerSoldPartnerServed` decimal(18,6) NOT NULL,
  `partnerRevenue` decimal(18,6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `onlyvideoid`
--

DROP TABLE IF EXISTS `onlyvideoid`;
CREATE TABLE `onlyvideoid` (
  `video_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slab_report_status`
--

DROP TABLE IF EXISTS `slab_report_status`;
CREATE TABLE `slab_report_status` (
  `id` int(11) NOT NULL,
  `for_month` date DEFAULT NULL,
  `table_type` varchar(24) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_added` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_columnslogs`
--
ALTER TABLE `activity_columnslogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_downlaod_report`
--
ALTER TABLE `activity_downlaod_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_downlaod_report_test`
--
ALTER TABLE `activity_downlaod_report_test`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_reports`
--
ALTER TABLE `activity_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `atemp_label_content_owner`
--
ALTER TABLE `atemp_label_content_owner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `a_temp_itune_label`
--
ALTER TABLE `a_temp_itune_label`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `channel_co_map`
--
ALTER TABLE `channel_co_map`
  ADD KEY `channel_id` (`channel_id`);

--
-- Indexes for table `channel_co_maping`
--
ALTER TABLE `channel_co_maping`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assetChannelID` (`assetChannelID`,`Channel_id`,`partner_provided`,`ugc`,`CMS`),
  ADD KEY `Label` (`Label`),
  ADD KEY `partner_provided` (`partner_provided`),
  ADD KEY `ugc` (`ugc`),
  ADD KEY `CMS` (`CMS`);

--
-- Indexes for table `channel_co_maping_amazon`
--
ALTER TABLE `channel_co_maping_amazon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `channel_co_maping_bk_2022-11-20`
--
ALTER TABLE `channel_co_maping_bk_2022-11-20`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assetChannelID` (`assetChannelID`,`Channel_id`,`partner_provided`,`ugc`,`CMS`),
  ADD KEY `Label` (`Label`),
  ADD KEY `partner_provided` (`partner_provided`),
  ADD KEY `ugc` (`ugc`),
  ADD KEY `CMS` (`CMS`);

--
-- Indexes for table `channel_co_mapping_original`
--
ALTER TABLE `channel_co_mapping_original`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assetChannelID` (`assetChannelID`),
  ADD KEY `partner_provided` (`partner_provided`),
  ADD KEY `ugc` (`ugc`),
  ADD KEY `Label` (`Label`);

--
-- Indexes for table `channel_co_map_import`
--
ALTER TABLE `channel_co_map_import`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_slab_groups`
--
ALTER TABLE `client_slab_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_name_2` (`group_name`),
  ADD KEY `group_name` (`group_name`);

--
-- Indexes for table `client_slab_group_content_owner`
--
ALTER TABLE `client_slab_group_content_owner`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_name` (`group_name`),
  ADD KEY `content_owner` (`content_owner`);

--
-- Indexes for table `client_slab_percentage`
--
ALTER TABLE `client_slab_percentage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_master`
--
ALTER TABLE `cms_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crep_cms_clients`
--
ALTER TABLE `crep_cms_clients`
  ADD PRIMARY KEY (`client_id`),
  ADD UNIQUE KEY `client_username` (`client_username`),
  ADD KEY `email` (`email`) USING BTREE;

--
-- Indexes for table `crep_cms_clients_backup`
--
ALTER TABLE `crep_cms_clients_backup`
  ADD PRIMARY KEY (`client_id`),
  ADD UNIQUE KEY `client_username` (`client_username`),
  ADD KEY `email` (`email`) USING BTREE;

--
-- Indexes for table `crep_cms_clients_bk`
--
ALTER TABLE `crep_cms_clients_bk`
  ADD PRIMARY KEY (`client_id`),
  ADD UNIQUE KEY `client_username` (`client_username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `crep_cms_clients_temp`
--
ALTER TABLE `crep_cms_clients_temp`
  ADD PRIMARY KEY (`client_id`),
  ADD UNIQUE KEY `client_username` (`client_username`),
  ADD KEY `email` (`email`) USING BTREE;

--
-- Indexes for table `crep_cms_distributor`
--
ALTER TABLE `crep_cms_distributor`
  ADD PRIMARY KEY (`distributor_id`);

--
-- Indexes for table `crep_cms_groups`
--
ALTER TABLE `crep_cms_groups`
  ADD PRIMARY KEY (`group_id`),
  ADD KEY `right_on_module` (`right_on_module`);

--
-- Indexes for table `crep_cms_ip_access`
--
ALTER TABLE `crep_cms_ip_access`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crep_cms_modules`
--
ALTER TABLE `crep_cms_modules`
  ADD PRIMARY KEY (`module_id`);

--
-- Indexes for table `crep_cms_rates`
--
ALTER TABLE `crep_cms_rates`
  ADD PRIMARY KEY (`rate_id`);

--
-- Indexes for table `crep_cms_user`
--
ALTER TABLE `crep_cms_user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `crep_cms_user_backup`
--
ALTER TABLE `crep_cms_user_backup`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `monthly_exchange_rate`
--
ALTER TABLE `monthly_exchange_rate`
  ADD PRIMARY KEY (`rate_id`);

--
-- Indexes for table `monthly_rate_saavan_gaana_other`
--
ALTER TABLE `monthly_rate_saavan_gaana_other`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newreport_bk`
--
ALTER TABLE `newreport_bk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slab_report_status`
--
ALTER TABLE `slab_report_status`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_columnslogs`
--
ALTER TABLE `activity_columnslogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_downlaod_report`
--
ALTER TABLE `activity_downlaod_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_downlaod_report_test`
--
ALTER TABLE `activity_downlaod_report_test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_reports`
--
ALTER TABLE `activity_reports`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `atemp_label_content_owner`
--
ALTER TABLE `atemp_label_content_owner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `a_temp_itune_label`
--
ALTER TABLE `a_temp_itune_label`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `channel_co_maping`
--
ALTER TABLE `channel_co_maping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `channel_co_maping_amazon`
--
ALTER TABLE `channel_co_maping_amazon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `channel_co_maping_bk_2022-11-20`
--
ALTER TABLE `channel_co_maping_bk_2022-11-20`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `channel_co_mapping_original`
--
ALTER TABLE `channel_co_mapping_original`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `channel_co_map_import`
--
ALTER TABLE `channel_co_map_import`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_slab_groups`
--
ALTER TABLE `client_slab_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_slab_group_content_owner`
--
ALTER TABLE `client_slab_group_content_owner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_slab_percentage`
--
ALTER TABLE `client_slab_percentage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_master`
--
ALTER TABLE `cms_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crep_cms_clients`
--
ALTER TABLE `crep_cms_clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crep_cms_clients_backup`
--
ALTER TABLE `crep_cms_clients_backup`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crep_cms_clients_bk`
--
ALTER TABLE `crep_cms_clients_bk`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crep_cms_clients_temp`
--
ALTER TABLE `crep_cms_clients_temp`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crep_cms_distributor`
--
ALTER TABLE `crep_cms_distributor`
  MODIFY `distributor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crep_cms_groups`
--
ALTER TABLE `crep_cms_groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crep_cms_ip_access`
--
ALTER TABLE `crep_cms_ip_access`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crep_cms_modules`
--
ALTER TABLE `crep_cms_modules`
  MODIFY `module_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crep_cms_rates`
--
ALTER TABLE `crep_cms_rates`
  MODIFY `rate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crep_cms_user`
--
ALTER TABLE `crep_cms_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crep_cms_user_backup`
--
ALTER TABLE `crep_cms_user_backup`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `monthly_exchange_rate`
--
ALTER TABLE `monthly_exchange_rate`
  MODIFY `rate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `monthly_rate_saavan_gaana_other`
--
ALTER TABLE `monthly_rate_saavan_gaana_other`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `newreport_bk`
--
ALTER TABLE `newreport_bk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slab_report_status`
--
ALTER TABLE `slab_report_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
