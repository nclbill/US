-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : dim. 01 juin 2025 à 20:49
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `espacemoto`
--
CREATE DATABASE IF NOT EXISTS `espacemoto` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `espacemoto`;

-- --------------------------------------------------------

--
-- Structure de la table `acheteurs`
--

CREATE TABLE `acheteurs` (
  `id` int(20) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `telephone` int(25) NOT NULL,
  `adress` varchar(255) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `cin_pass_recto` varchar(255) NOT NULL,
  `cin_pass_verso` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `audit`
--

CREATE TABLE `audit` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `page` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ip` varchar(255) NOT NULL,
  `viewed` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(40) NOT NULL,
  `date_commande` date NOT NULL,
  `categorie` varchar(40) NOT NULL,
  `marque` varchar(40) NOT NULL,
  `modele` varchar(40) NOT NULL,
  `version` varchar(40) NOT NULL,
  `couleur` varchar(40) NOT NULL,
  `entrepot` varchar(40) NOT NULL,
  `id_client_revendeur` int(11) NOT NULL,
  `Nom_revendeur` varchar(255) NOT NULL,
  `nom_acheteur` varchar(255) NOT NULL,
  `prenom_acheteur` varchar(255) NOT NULL,
  `tel_acheteur` varchar(30) NOT NULL,
  `ville_acheteur` varchar(255) NOT NULL,
  `cin_pass_recto_acheteur` varchar(255) NOT NULL,
  `cin_pass_verso_acheteur` varchar(255) NOT NULL,
  `status_commande` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `crons`
--

CREATE TABLE `crons` (
  `id` int(11) NOT NULL,
  `active` int(1) NOT NULL DEFAULT 1,
  `sort` int(3) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `createdby` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `crons_logs`
--

CREATE TABLE `crons_logs` (
  `id` int(11) NOT NULL,
  `cron_id` int(11) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `email`
--

CREATE TABLE `email` (
  `id` int(11) NOT NULL,
  `website_name` varchar(100) NOT NULL,
  `smtp_server` varchar(100) NOT NULL,
  `smtp_port` int(10) NOT NULL,
  `email_login` varchar(150) NOT NULL,
  `email_pass` varchar(100) NOT NULL,
  `from_name` varchar(100) NOT NULL,
  `from_email` varchar(150) NOT NULL,
  `transport` varchar(255) NOT NULL,
  `verify_url` varchar(255) NOT NULL,
  `email_act` int(1) NOT NULL,
  `debug_level` int(1) NOT NULL DEFAULT 0,
  `isSMTP` int(1) NOT NULL DEFAULT 0,
  `isHTML` varchar(5) NOT NULL DEFAULT 'true',
  `useSMTPauth` varchar(6) NOT NULL DEFAULT 'true',
  `authtype` varchar(50) DEFAULT 'CRAM-MD5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `groups_menus`
--

CREATE TABLE `groups_menus` (
  `id` int(11) UNSIGNED NOT NULL,
  `group_id` int(11) UNSIGNED NOT NULL,
  `menu_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `keys`
--

CREATE TABLE `keys` (
  `id` int(11) NOT NULL,
  `stripe_ts` varchar(255) NOT NULL,
  `stripe_tp` varchar(255) NOT NULL,
  `stripe_ls` varchar(255) NOT NULL,
  `stripe_lp` varchar(255) NOT NULL,
  `recap_pub` varchar(100) NOT NULL,
  `recap_pri` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `logdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `logtype` varchar(25) NOT NULL,
  `lognote` mediumtext NOT NULL,
  `ip` varchar(75) DEFAULT NULL,
  `metadata` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `menus`
--

CREATE TABLE `menus` (
  `id` int(10) NOT NULL,
  `menu_title` varchar(255) NOT NULL,
  `parent` int(10) NOT NULL,
  `dropdown` int(1) NOT NULL,
  `logged_in` int(1) NOT NULL,
  `display_order` int(10) NOT NULL,
  `label` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `icon_class` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `msg_from` int(11) NOT NULL,
  `msg_to` int(11) NOT NULL,
  `msg_body` mediumtext NOT NULL,
  `msg_read` int(1) NOT NULL,
  `msg_thread` int(11) NOT NULL,
  `deleted` int(1) NOT NULL,
  `sent_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `message_threads`
--

CREATE TABLE `message_threads` (
  `id` int(11) NOT NULL,
  `msg_to` int(11) NOT NULL,
  `msg_from` int(11) NOT NULL,
  `msg_subject` varchar(255) NOT NULL,
  `last_update` datetime NOT NULL,
  `last_update_by` int(11) NOT NULL,
  `archive_from` int(1) NOT NULL DEFAULT 0,
  `archive_to` int(1) NOT NULL DEFAULT 0,
  `hidden_from` int(1) NOT NULL DEFAULT 0,
  `hidden_to` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `is_read` tinyint(4) NOT NULL,
  `is_archived` tinyint(1) DEFAULT 0,
  `date_created` datetime DEFAULT NULL,
  `date_read` datetime DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `class` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `page` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `private` int(11) NOT NULL DEFAULT 0,
  `re_auth` int(1) NOT NULL DEFAULT 0,
  `core` int(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `descrip` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permission_page_matches`
--

CREATE TABLE `permission_page_matches` (
  `id` int(11) NOT NULL,
  `permission_id` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `plg_social_logins`
--

CREATE TABLE `plg_social_logins` (
  `id` int(11) NOT NULL,
  `plugin` varchar(50) NOT NULL,
  `provider` varchar(50) NOT NULL,
  `enabledsetting` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `built_in` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `plg_tags`
--

CREATE TABLE `plg_tags` (
  `id` int(11) UNSIGNED NOT NULL,
  `tag` varchar(255) DEFAULT NULL,
  `descrip` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `plg_tags_matches`
--

CREATE TABLE `plg_tags_matches` (
  `id` int(11) UNSIGNED NOT NULL,
  `tag_id` int(11) UNSIGNED NOT NULL,
  `tag_name` varchar(255) DEFAULT NULL,
  `user_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id` int(40) NOT NULL,
  `categorie` varchar(40) NOT NULL,
  `marque` varchar(40) NOT NULL,
  `modele` varchar(40) NOT NULL,
  `version` varchar(40) NOT NULL,
  `couleur` varchar(40) NOT NULL,
  `vin` varchar(40) NOT NULL,
  `entrepot` varchar(40) NOT NULL,
  `disponibilite` varchar(40) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bio` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE `settings` (
  `id` int(50) NOT NULL,
  `recaptcha` int(1) NOT NULL DEFAULT 0,
  `force_ssl` int(1) NOT NULL,
  `css_sample` int(1) NOT NULL,
  `site_name` varchar(100) NOT NULL,
  `language` varchar(15) DEFAULT NULL,
  `site_offline` int(1) NOT NULL,
  `force_pr` int(1) NOT NULL,
  `glogin` int(1) NOT NULL DEFAULT 0,
  `fblogin` int(1) NOT NULL,
  `gid` text DEFAULT NULL,
  `gsecret` text DEFAULT NULL,
  `gredirect` text DEFAULT NULL,
  `ghome` text DEFAULT NULL,
  `fbid` text DEFAULT NULL,
  `fbsecret` text DEFAULT NULL,
  `fbcallback` text DEFAULT NULL,
  `graph_ver` text DEFAULT NULL,
  `finalredir` text DEFAULT NULL,
  `req_cap` int(1) NOT NULL,
  `req_num` int(1) NOT NULL,
  `min_pw` int(2) NOT NULL,
  `max_pw` int(3) NOT NULL,
  `min_un` int(2) NOT NULL,
  `max_un` int(3) NOT NULL,
  `messaging` int(1) NOT NULL,
  `snooping` int(1) NOT NULL,
  `echouser` int(11) NOT NULL,
  `wys` int(1) NOT NULL,
  `change_un` int(1) NOT NULL,
  `backup_dest` text DEFAULT NULL,
  `backup_source` text DEFAULT NULL,
  `backup_table` text DEFAULT NULL,
  `msg_notification` int(1) NOT NULL,
  `permission_restriction` int(1) NOT NULL,
  `auto_assign_un` int(1) NOT NULL,
  `page_permission_restriction` int(1) NOT NULL,
  `msg_blocked_users` int(1) NOT NULL,
  `msg_default_to` int(1) NOT NULL,
  `notifications` int(1) NOT NULL,
  `notif_daylimit` int(3) NOT NULL,
  `recap_public` text DEFAULT NULL,
  `recap_private` text DEFAULT NULL,
  `page_default_private` int(1) NOT NULL,
  `navigation_type` tinyint(1) NOT NULL,
  `copyright` varchar(255) NOT NULL,
  `custom_settings` int(1) NOT NULL,
  `system_announcement` varchar(255) NOT NULL,
  `twofa` int(1) DEFAULT 0,
  `force_notif` tinyint(1) DEFAULT NULL,
  `cron_ip` varchar(255) DEFAULT NULL,
  `registration` tinyint(1) DEFAULT NULL,
  `join_vericode_expiry` int(9) UNSIGNED NOT NULL,
  `reset_vericode_expiry` int(9) UNSIGNED NOT NULL,
  `admin_verify` tinyint(1) NOT NULL,
  `admin_verify_timeout` int(9) NOT NULL,
  `session_manager` tinyint(1) NOT NULL,
  `template` varchar(255) DEFAULT 'standard',
  `saas` tinyint(1) DEFAULT NULL,
  `redirect_uri_after_login` mediumtext DEFAULT NULL,
  `show_tos` tinyint(1) DEFAULT 1,
  `default_language` varchar(11) DEFAULT NULL,
  `allow_language` tinyint(1) DEFAULT NULL,
  `spice_api` varchar(75) DEFAULT NULL,
  `announce` datetime DEFAULT NULL,
  `bleeding_edge` tinyint(1) DEFAULT 0,
  `err_time` int(11) DEFAULT 15,
  `container_open_class` varchar(255) DEFAULT 'container-fluid',
  `debug` tinyint(1) DEFAULT 0,
  `widgets` text DEFAULT NULL,
  `uman_search` tinyint(1) DEFAULT 0,
  `no_passwords` tinyint(1) DEFAULT 0,
  `email_login` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `updates`
--

CREATE TABLE `updates` (
  `id` int(11) NOT NULL,
  `migration` varchar(15) NOT NULL,
  `applied_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `update_skipped` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `permissions` tinyint(1) NOT NULL,
  `email` varchar(155) NOT NULL,
  `email_new` varchar(155) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `pin` varchar(255) DEFAULT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `raison_sociale` varchar(255) NOT NULL,
  `ice` varchar(255) NOT NULL,
  `language` varchar(15) DEFAULT 'en-US',
  `email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `vericode` text DEFAULT NULL,
  `vericode_expiry` datetime DEFAULT NULL,
  `oauth_provider` text DEFAULT NULL,
  `oauth_uid` text DEFAULT NULL,
  `gender` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `gpluslink` text DEFAULT NULL,
  `account_owner` tinyint(4) NOT NULL DEFAULT 1,
  `account_id` int(11) NOT NULL DEFAULT 0,
  `account_mgr` int(11) NOT NULL DEFAULT 0,
  `fb_uid` text DEFAULT NULL,
  `picture` text DEFAULT NULL,
  `created` datetime NOT NULL,
  `protected` tinyint(1) NOT NULL DEFAULT 0,
  `msg_exempt` tinyint(1) NOT NULL DEFAULT 0,
  `dev_user` tinyint(1) NOT NULL DEFAULT 0,
  `msg_notification` tinyint(1) NOT NULL DEFAULT 1,
  `cloak_allowed` tinyint(1) NOT NULL DEFAULT 0,
  `oauth_tos_accepted` tinyint(1) DEFAULT NULL,
  `un_changed` tinyint(1) NOT NULL DEFAULT 0,
  `force_pr` tinyint(1) NOT NULL DEFAULT 0,
  `logins` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `last_login` datetime DEFAULT NULL,
  `join_date` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `cin_pass` varchar(20) NOT NULL,
  `tel` int(20) DEFAULT NULL,
  `habilitation` varchar(20) NOT NULL,
  `ville` varchar(20) NOT NULL,
  `magasin` varchar(50) NOT NULL,
  `prompt_ncl` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users_online`
--

CREATE TABLE `users_online` (
  `id` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `timestamp` varchar(15) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users_session`
--

CREATE TABLE `users_session` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `uagent` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_permission_matches`
--

CREATE TABLE `user_permission_matches` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `us_announcements`
--

CREATE TABLE `us_announcements` (
  `id` int(11) NOT NULL,
  `dismissed` int(11) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `ignore` varchar(50) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `dismissed_by` int(11) DEFAULT 0,
  `update_announcement` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `us_email_logins`
--

CREATE TABLE `us_email_logins` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vericode` varchar(50) NOT NULL,
  `success` tinyint(1) DEFAULT 0,
  `login_ip` varchar(50) NOT NULL,
  `login_date` datetime NOT NULL,
  `expired` tinyint(1) DEFAULT 0,
  `expires` datetime DEFAULT NULL,
  `verification_code` varchar(10) DEFAULT NULL,
  `invalid_attempts` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `us_fingerprints`
--

CREATE TABLE `us_fingerprints` (
  `kFingerprintID` int(11) UNSIGNED NOT NULL,
  `fkUserID` int(11) NOT NULL,
  `Fingerprint` varchar(32) NOT NULL,
  `Fingerprint_Expiry` datetime NOT NULL,
  `Fingerprint_Added` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `us_fingerprint_assets`
--

CREATE TABLE `us_fingerprint_assets` (
  `kFingerprintAssetID` int(11) UNSIGNED NOT NULL,
  `fkFingerprintID` int(11) NOT NULL,
  `IP_Address` varchar(255) NOT NULL,
  `User_Browser` varchar(255) NOT NULL,
  `User_OS` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `us_forms`
--

CREATE TABLE `us_forms` (
  `id` int(11) NOT NULL,
  `form` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `us_form_validation`
--

CREATE TABLE `us_form_validation` (
  `id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `params` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `us_form_views`
--

CREATE TABLE `us_form_views` (
  `id` int(11) NOT NULL,
  `form_name` varchar(255) NOT NULL,
  `view_name` varchar(255) NOT NULL,
  `fields` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `us_ip_blacklist`
--

CREATE TABLE `us_ip_blacklist` (
  `id` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `last_user` int(11) NOT NULL DEFAULT 0,
  `reason` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `us_ip_list`
--

CREATE TABLE `us_ip_list` (
  `id` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `us_ip_whitelist`
--

CREATE TABLE `us_ip_whitelist` (
  `id` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `us_login_fails`
--

CREATE TABLE `us_login_fails` (
  `id` int(11) NOT NULL,
  `login_method` varchar(50) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `ts` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `us_management`
--

CREATE TABLE `us_management` (
  `id` int(11) NOT NULL,
  `page` varchar(255) NOT NULL,
  `view` varchar(255) NOT NULL,
  `feature` varchar(255) NOT NULL,
  `access` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `us_menus`
--

CREATE TABLE `us_menus` (
  `id` int(11) UNSIGNED NOT NULL,
  `menu_name` varchar(255) DEFAULT NULL,
  `type` varchar(75) DEFAULT NULL,
  `nav_class` varchar(255) DEFAULT NULL,
  `theme` varchar(25) DEFAULT NULL,
  `z_index` int(11) DEFAULT NULL,
  `brand_html` text DEFAULT NULL,
  `disabled` tinyint(1) DEFAULT 0,
  `justify` varchar(10) DEFAULT 'right',
  `show_active` tinyint(1) DEFAULT 0,
  `screen_reader_mode` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `us_menu_items`
--

CREATE TABLE `us_menu_items` (
  `id` int(11) UNSIGNED NOT NULL,
  `menu` int(11) UNSIGNED NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `link` text DEFAULT NULL,
  `icon_class` varchar(255) DEFAULT NULL,
  `li_class` varchar(255) DEFAULT NULL,
  `a_class` varchar(255) DEFAULT NULL,
  `link_target` varchar(50) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `display_order` int(11) DEFAULT NULL,
  `disabled` tinyint(1) DEFAULT 0,
  `permissions` varchar(1000) DEFAULT NULL,
  `tags` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `us_password_strength`
--

CREATE TABLE `us_password_strength` (
  `id` int(11) NOT NULL,
  `enforce_rules` tinyint(1) DEFAULT 0,
  `meter_active` tinyint(1) DEFAULT 0,
  `min_length` int(11) DEFAULT 8,
  `max_length` int(11) DEFAULT 24,
  `require_lowercase` tinyint(1) DEFAULT 1,
  `require_uppercase` tinyint(1) DEFAULT 1,
  `require_numbers` tinyint(1) DEFAULT 1,
  `require_symbols` tinyint(1) DEFAULT 1,
  `min_score` int(11) DEFAULT 5,
  `uppercase_score` int(11) NOT NULL DEFAULT 6,
  `lowercase_score` int(11) NOT NULL DEFAULT 6,
  `number_score` int(11) NOT NULL DEFAULT 6,
  `symbol_score` int(11) NOT NULL DEFAULT 11,
  `greater_eight` int(11) NOT NULL DEFAULT 15,
  `greater_twelve` int(11) NOT NULL DEFAULT 28,
  `greater_sixteen` int(11) NOT NULL DEFAULT 40
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `us_plugins`
--

CREATE TABLE `us_plugins` (
  `id` int(11) NOT NULL,
  `plugin` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `updates` mediumtext DEFAULT NULL,
  `last_check` datetime DEFAULT '2020-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `us_plugin_hooks`
--

CREATE TABLE `us_plugin_hooks` (
  `id` int(11) UNSIGNED NOT NULL,
  `page` varchar(255) NOT NULL,
  `folder` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `hook` varchar(255) NOT NULL,
  `disabled` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `us_saas_levels`
--

CREATE TABLE `us_saas_levels` (
  `id` int(11) NOT NULL,
  `level` varchar(255) NOT NULL,
  `users` int(11) NOT NULL,
  `details` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `us_saas_orgs`
--

CREATE TABLE `us_saas_orgs` (
  `id` int(11) NOT NULL,
  `org` varchar(255) NOT NULL,
  `owner` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `active` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `us_user_sessions`
--

CREATE TABLE `us_user_sessions` (
  `kUserSessionID` int(11) UNSIGNED NOT NULL,
  `fkUserID` int(11) UNSIGNED NOT NULL,
  `UserFingerprint` varchar(255) NOT NULL,
  `UserSessionIP` varchar(255) NOT NULL,
  `UserSessionOS` varchar(255) NOT NULL,
  `UserSessionBrowser` varchar(255) NOT NULL,
  `UserSessionStarted` datetime NOT NULL,
  `UserSessionLastUsed` datetime DEFAULT NULL,
  `UserSessionLastPage` varchar(255) NOT NULL,
  `UserSessionEnded` tinyint(1) NOT NULL DEFAULT 0,
  `UserSessionEnded_Time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `audit`
--
ALTER TABLE `audit`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `crons`
--
ALTER TABLE `crons`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `crons_logs`
--
ALTER TABLE `crons_logs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `email`
--
ALTER TABLE `email`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `groups_menus`
--
ALTER TABLE `groups_menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Index pour la table `keys`
--
ALTER TABLE `keys`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `message_threads`
--
ALTER TABLE `message_threads`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `permission_page_matches`
--
ALTER TABLE `permission_page_matches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `plg_social_logins`
--
ALTER TABLE `plg_social_logins`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `plg_tags`
--
ALTER TABLE `plg_tags`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `plg_tags_matches`
--
ALTER TABLE `plg_tags_matches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vin` (`vin`);

--
-- Index pour la table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `updates`
--
ALTER TABLE `updates`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `EMAIL` (`email`) USING BTREE;

--
-- Index pour la table `users_online`
--
ALTER TABLE `users_online`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users_session`
--
ALTER TABLE `users_session`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user_permission_matches`
--
ALTER TABLE `user_permission_matches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `us_announcements`
--
ALTER TABLE `us_announcements`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `us_email_logins`
--
ALTER TABLE `us_email_logins`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `us_fingerprints`
--
ALTER TABLE `us_fingerprints`
  ADD PRIMARY KEY (`kFingerprintID`);

--
-- Index pour la table `us_fingerprint_assets`
--
ALTER TABLE `us_fingerprint_assets`
  ADD PRIMARY KEY (`kFingerprintAssetID`);

--
-- Index pour la table `us_forms`
--
ALTER TABLE `us_forms`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `us_form_validation`
--
ALTER TABLE `us_form_validation`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `us_form_views`
--
ALTER TABLE `us_form_views`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `us_ip_blacklist`
--
ALTER TABLE `us_ip_blacklist`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `us_ip_list`
--
ALTER TABLE `us_ip_list`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `us_ip_whitelist`
--
ALTER TABLE `us_ip_whitelist`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `us_login_fails`
--
ALTER TABLE `us_login_fails`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `us_management`
--
ALTER TABLE `us_management`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `us_menus`
--
ALTER TABLE `us_menus`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `us_menu_items`
--
ALTER TABLE `us_menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `us_password_strength`
--
ALTER TABLE `us_password_strength`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `us_plugins`
--
ALTER TABLE `us_plugins`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `us_plugin_hooks`
--
ALTER TABLE `us_plugin_hooks`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `us_saas_levels`
--
ALTER TABLE `us_saas_levels`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `us_saas_orgs`
--
ALTER TABLE `us_saas_orgs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `us_user_sessions`
--
ALTER TABLE `us_user_sessions`
  ADD PRIMARY KEY (`kUserSessionID`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `audit`
--
ALTER TABLE `audit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(40) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `crons`
--
ALTER TABLE `crons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `crons_logs`
--
ALTER TABLE `crons_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `email`
--
ALTER TABLE `email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `groups_menus`
--
ALTER TABLE `groups_menus`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `keys`
--
ALTER TABLE `keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `message_threads`
--
ALTER TABLE `message_threads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `permission_page_matches`
--
ALTER TABLE `permission_page_matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `plg_social_logins`
--
ALTER TABLE `plg_social_logins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `plg_tags`
--
ALTER TABLE `plg_tags`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `plg_tags_matches`
--
ALTER TABLE `plg_tags_matches`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int(40) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `updates`
--
ALTER TABLE `updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users_session`
--
ALTER TABLE `users_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user_permission_matches`
--
ALTER TABLE `user_permission_matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `us_announcements`
--
ALTER TABLE `us_announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `us_email_logins`
--
ALTER TABLE `us_email_logins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `us_fingerprints`
--
ALTER TABLE `us_fingerprints`
  MODIFY `kFingerprintID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `us_fingerprint_assets`
--
ALTER TABLE `us_fingerprint_assets`
  MODIFY `kFingerprintAssetID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `us_forms`
--
ALTER TABLE `us_forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `us_form_validation`
--
ALTER TABLE `us_form_validation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `us_form_views`
--
ALTER TABLE `us_form_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `us_ip_blacklist`
--
ALTER TABLE `us_ip_blacklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `us_ip_list`
--
ALTER TABLE `us_ip_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `us_ip_whitelist`
--
ALTER TABLE `us_ip_whitelist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `us_login_fails`
--
ALTER TABLE `us_login_fails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `us_management`
--
ALTER TABLE `us_management`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `us_menus`
--
ALTER TABLE `us_menus`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `us_menu_items`
--
ALTER TABLE `us_menu_items`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `us_password_strength`
--
ALTER TABLE `us_password_strength`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `us_plugins`
--
ALTER TABLE `us_plugins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `us_plugin_hooks`
--
ALTER TABLE `us_plugin_hooks`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `us_saas_levels`
--
ALTER TABLE `us_saas_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `us_saas_orgs`
--
ALTER TABLE `us_saas_orgs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `us_user_sessions`
--
ALTER TABLE `us_user_sessions`
  MODIFY `kUserSessionID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
