-- --------------------------------------------------------
-- Host:                         203.89.28.26
-- Server version:               10.8.7-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table esensiaco_medkit_dev.admins
DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id_user` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `id_poli` int(11) NOT NULL DEFAULT 0,
  `poli` varchar(100) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `cpostname` varchar(20) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `level_user` varchar(15) NOT NULL DEFAULT 'user',
  `email` varchar(25) DEFAULT NULL,
  `no_telp` varchar(25) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `nip` varchar(5) DEFAULT NULL,
  `status` varchar(8) DEFAULT NULL,
  `datelogin` datetime NOT NULL,
  `cUser` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT '',
  `datecreate` datetime NOT NULL,
  `dateupdate` datetime NOT NULL,
  `photo` varchar(100) NOT NULL,
  `kd_approve` int(3) NOT NULL,
  `aboutme` text DEFAULT NULL,
  `web` varchar(100) DEFAULT NULL,
  `google+` varchar(100) DEFAULT NULL,
  `patch` varchar(100) DEFAULT NULL,
  `ccode` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT '',
  `crgcode` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `cmainmenu` varchar(10) DEFAULT NULL,
  `csubmenu` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id_user`,`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.admins: ~0 rows (approximately)
DELETE FROM `admins`;

-- Dumping structure for table esensiaco_medkit_dev.adm_menu
DROP TABLE IF EXISTS `adm_menu`;
CREATE TABLE IF NOT EXISTS `adm_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `nama_menu` varchar(100) DEFAULT NULL,
  `class_` varchar(15) DEFAULT NULL,
  `icon` varchar(40) DEFAULT NULL,
  `link` varchar(100) DEFAULT NULL,
  `parentid` int(3) DEFAULT NULL,
  `cUser` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT '',
  `dCreateDate` datetime DEFAULT '0000-00-00 00:00:00',
  `dLastUpdate` datetime DEFAULT NULL,
  `status` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.adm_menu: 0 rows
DELETE FROM `adm_menu`;
/*!40000 ALTER TABLE `adm_menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `adm_menu` ENABLE KEYS */;

-- Dumping structure for table esensiaco_medkit_dev.adm_smenu
DROP TABLE IF EXISTS `adm_smenu`;
CREATE TABLE IF NOT EXISTS `adm_smenu` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `nama_menu` varchar(100) DEFAULT NULL,
  `class_` varchar(15) DEFAULT NULL,
  `icon` varchar(40) DEFAULT NULL,
  `link` varchar(100) DEFAULT NULL,
  `parentid` int(3) DEFAULT NULL,
  `add` int(1) DEFAULT 1,
  `edit` int(1) DEFAULT 1,
  `del` int(1) DEFAULT 1,
  `cUser` varchar(35) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT '',
  `dCreateDate` datetime DEFAULT '0000-00-00 00:00:00',
  `dLastUpdate` datetime DEFAULT NULL,
  `aktif` varchar(1) DEFAULT 'y'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.adm_smenu: 0 rows
DELETE FROM `adm_smenu`;
/*!40000 ALTER TABLE `adm_smenu` DISABLE KEYS */;
/*!40000 ALTER TABLE `adm_smenu` ENABLE KEYS */;

-- Dumping structure for table esensiaco_medkit_dev.ci_sessions
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.ci_sessions: ~0 rows (approximately)
DELETE FROM `ci_sessions`;

-- Dumping structure for table esensiaco_medkit_dev.mcompany
DROP TABLE IF EXISTS `mcompany`;
CREATE TABLE IF NOT EXISTS `mcompany` (
  `id` int(11) NOT NULL,
  `cCode` varchar(5) NOT NULL DEFAULT '',
  `cName` varchar(50) NOT NULL DEFAULT '',
  `cTitle` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `cAddress1` varchar(50) NOT NULL DEFAULT '',
  `cAddress2` varchar(50) DEFAULT '',
  `cAddress3` varchar(50) DEFAULT '',
  `cCity` varchar(30) DEFAULT '',
  `cCodeNation` varchar(10) DEFAULT '',
  `cContact` varchar(50) DEFAULT '',
  `cPhone1` varchar(20) DEFAULT '',
  `cPhone2` varchar(20) DEFAULT '',
  `cPhone3` varchar(20) DEFAULT '',
  `cFax1` varchar(20) DEFAULT '',
  `cFax2` varchar(20) DEFAULT '',
  `cEmail` varchar(50) DEFAULT '',
  `cnpwp` varchar(50) DEFAULT '',
  `ctaxaddress` varchar(50) DEFAULT '',
  `cFaxRegNo` varchar(50) DEFAULT '',
  `cPresident` varchar(50) DEFAULT '',
  `cAccountDir` varchar(50) DEFAULT '',
  `ctechnicDir` varchar(50) DEFAULT '',
  `cMarketDir` varchar(50) DEFAULT '',
  `cLabel` longblob DEFAULT NULL,
  `dCurrDate` datetime DEFAULT NULL,
  `dCreateDate` datetime DEFAULT NULL,
  `dLastUpdate` datetime DEFAULT NULL,
  `SUser` varchar(10) DEFAULT '',
  `cLogo` varchar(100) DEFAULT NULL,
  `cDefault` varchar(1) DEFAULT '',
  `cMemo` longtext DEFAULT NULL,
  `cWall` longblob DEFAULT NULL,
  `sWall` longtext DEFAULT NULL,
  `cbank` varchar(5) DEFAULT '',
  `cnorek` varchar(25) DEFAULT NULL,
  `cTaxdir` varchar(100) DEFAULT '',
  `cRegCode` varchar(3) DEFAULT '',
  `cCoaCode` char(2) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.mcompany: ~0 rows (approximately)
DELETE FROM `mcompany`;

-- Dumping structure for table esensiaco_medkit_dev.mdepart
DROP TABLE IF EXISTS `mdepart`;
CREATE TABLE IF NOT EXISTS `mdepart` (
  `id` int(11) NOT NULL,
  `cCode` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT '',
  `cCompname` varchar(35) DEFAULT NULL,
  `cRgCode` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `cRgName` varchar(50) DEFAULT NULL,
  `cDeptcode` varchar(3) NOT NULL,
  `cDeptname` varchar(25) NOT NULL,
  `cFlag` char(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.mdepart: 0 rows
DELETE FROM `mdepart`;
/*!40000 ALTER TABLE `mdepart` DISABLE KEYS */;
/*!40000 ALTER TABLE `mdepart` ENABLE KEYS */;

-- Dumping structure for table esensiaco_medkit_dev.mkotakab
DROP TABLE IF EXISTS `mkotakab`;
CREATE TABLE IF NOT EXISTS `mkotakab` (
  `id` int(125) NOT NULL,
  `KdKab` varchar(125) NOT NULL,
  `NmKabKot` varchar(125) NOT NULL,
  `kordinat` varchar(25) DEFAULT NULL,
  `PusPem` varchar(125) NOT NULL,
  `NmKabKotLkp` varchar(125) NOT NULL,
  `KdKbKt` varchar(125) NOT NULL,
  `KdProp` varchar(125) NOT NULL,
  `NoBPS` varchar(125) NOT NULL,
  `LWil` double NOT NULL,
  `JmlPendd2` int(11) NOT NULL,
  `TSeptik` int(11) NOT NULL,
  `TTSeptik` int(11) NOT NULL,
  `TdkPunya` int(11) NOT NULL,
  `TAFasBAB` int(11) NOT NULL,
  `UserName` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.mkotakab: 0 rows
DELETE FROM `mkotakab`;
/*!40000 ALTER TABLE `mkotakab` DISABLE KEYS */;
/*!40000 ALTER TABLE `mkotakab` ENABLE KEYS */;

-- Dumping structure for table esensiaco_medkit_dev.mmenu
DROP TABLE IF EXISTS `mmenu`;
CREATE TABLE IF NOT EXISTS `mmenu` (
  `menu_id` int(11) DEFAULT NULL,
  `nama_menu` varchar(100) DEFAULT NULL,
  `class_` varchar(15) DEFAULT NULL,
  `icon` varchar(40) DEFAULT NULL,
  `link` varchar(100) DEFAULT NULL,
  `parentid` int(3) DEFAULT NULL,
  `aktif` varchar(1) DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.mmenu: ~0 rows (approximately)
DELETE FROM `mmenu`;

-- Dumping structure for table esensiaco_medkit_dev.mmenu_sb
DROP TABLE IF EXISTS `mmenu_sb`;
CREATE TABLE IF NOT EXISTS `mmenu_sb` (
  `menu_id` int(11) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `class_` varchar(15) DEFAULT NULL,
  `icon` varchar(40) NOT NULL,
  `link` varchar(100) NOT NULL,
  `parentid` int(11) NOT NULL,
  `aktif` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.mmenu_sb: ~0 rows (approximately)
DELETE FROM `mmenu_sb`;

-- Dumping structure for table esensiaco_medkit_dev.mpoli
DROP TABLE IF EXISTS `mpoli`;
CREATE TABLE IF NOT EXISTS `mpoli` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_poli` int(11) NOT NULL DEFAULT 0,
  `id_view` int(11) NOT NULL DEFAULT 0,
  `tgl_simpan` datetime NOT NULL,
  `tgl_modif` datetime NOT NULL,
  `kode` varchar(50) NOT NULL,
  `poli` varchar(160) NOT NULL,
  `icon` varchar(50) NOT NULL DEFAULT 'fa-hospital-o',
  `style` varchar(50) NOT NULL DEFAULT 'btn-info',
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `status_tmpl` enum('0','1','2') NOT NULL DEFAULT '0',
  `status_ant` enum('0','1') NOT NULL DEFAULT '1',
  `sp` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT 'SP = 1 untuk di depan',
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.mpoli: ~0 rows (approximately)
DELETE FROM `mpoli`;

-- Dumping structure for table esensiaco_medkit_dev.mposition
DROP TABLE IF EXISTS `mposition`;
CREATE TABLE IF NOT EXISTS `mposition` (
  `id` int(11) NOT NULL,
  `cposcode` varchar(5) DEFAULT NULL,
  `cpostname` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.mposition: ~0 rows (approximately)
DELETE FROM `mposition`;

-- Dumping structure for table esensiaco_medkit_dev.mprofile
DROP TABLE IF EXISTS `mprofile`;
CREATE TABLE IF NOT EXISTS `mprofile` (
  `cid` int(11) NOT NULL,
  `cnama` varchar(100) DEFAULT NULL,
  `calamat` varchar(100) DEFAULT NULL,
  `ckota` varchar(50) DEFAULT NULL,
  `chp` varchar(30) DEFAULT NULL,
  `cfax` varchar(30) DEFAULT NULL,
  `cemail` varchar(50) DEFAULT NULL,
  `clogo` varchar(100) DEFAULT NULL,
  `cjudul` varchar(150) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.mprofile: 0 rows
DELETE FROM `mprofile`;
/*!40000 ALTER TABLE `mprofile` DISABLE KEYS */;
/*!40000 ALTER TABLE `mprofile` ENABLE KEYS */;

-- Dumping structure for table esensiaco_medkit_dev.mprov
DROP TABLE IF EXISTS `mprov`;
CREATE TABLE IF NOT EXISTS `mprov` (
  `id` int(11) NOT NULL,
  `cProvCode` varchar(15) DEFAULT '',
  `cProvName` varchar(100) DEFAULT '',
  `cLandCode` varchar(100) DEFAULT '',
  `cLogoProv` longtext DEFAULT NULL,
  `cCoordninat` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.mprov: ~0 rows (approximately)
DELETE FROM `mprov`;

-- Dumping structure for table esensiaco_medkit_dev.mregion
DROP TABLE IF EXISTS `mregion`;
CREATE TABLE IF NOT EXISTS `mregion` (
  `id` int(11) NOT NULL,
  `cCode` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `cCompname` varchar(35) DEFAULT NULL,
  `cProvName` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `cRgCode` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `cRgName` varchar(35) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `cIrisCode` varchar(5) DEFAULT NULL,
  `cAlamat` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `cNoTelepon` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `cMobile` varchar(20) DEFAULT NULL,
  `cNoFax` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `cKota` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `cKacabang` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `cKamarketing` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `cKaservice` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `cKasparepart` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `cKakeu` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `cDealerCode` varchar(4) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `cService` int(2) NOT NULL DEFAULT 0,
  `cSales` int(2) NOT NULL DEFAULT 0,
  `cDownpay` int(2) NOT NULL DEFAULT 0,
  `cCoaCode` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `cRgStatus` char(3) NOT NULL DEFAULT 'CAB',
  `cNoRegis` char(3) DEFAULT '001'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.mregion: 0 rows
DELETE FROM `mregion`;
/*!40000 ALTER TABLE `mregion` DISABLE KEYS */;
/*!40000 ALTER TABLE `mregion` ENABLE KEYS */;

-- Dumping structure for table esensiaco_medkit_dev.mview
DROP TABLE IF EXISTS `mview`;
CREATE TABLE IF NOT EXISTS `mview` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl_simpan` timestamp NULL DEFAULT current_timestamp(),
  `kode` varchar(160) DEFAULT NULL,
  `keterangan` varchar(160) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.mview: 0 rows
DELETE FROM `mview`;
/*!40000 ALTER TABLE `mview` DISABLE KEYS */;
/*!40000 ALTER TABLE `mview` ENABLE KEYS */;

-- Dumping structure for table esensiaco_medkit_dev.news
DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id_news` int(11) NOT NULL,
  `desc_news` text NOT NULL,
  `date_news` datetime NOT NULL,
  `by_news` varchar(100) NOT NULL,
  `blokir` char(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.news: 0 rows
DELETE FROM `news`;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
/*!40000 ALTER TABLE `news` ENABLE KEYS */;

-- Dumping structure for table esensiaco_medkit_dev.tbl_ion_groups
DROP TABLE IF EXISTS `tbl_ion_groups`;
CREATE TABLE IF NOT EXISTS `tbl_ion_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  `akses` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_ion_groups: ~18 rows (approximately)
DELETE FROM `tbl_ion_groups`;
INSERT INTO `tbl_ion_groups` (`id`, `name`, `description`, `akses`) VALUES
	(1, 'superadmin', 'Super Administrator', '[{"id":"1","id_parent":"0","modules":"master","modules_action":"index","modules_name":"Master","modules_route":"master\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"},{"id":"101","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_detail","modules_name":null,"modules_route":null,"modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"0"},{"id":"1","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_dft","modules_name":"Antrian","modules_route":"medcheck\\/data_pendaftaran.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"100","id_parent":"99","modules":"medcheck","modules_action":"trans_medcheck","modules_name":"Tambah Checkup","modules_route":"medcheck\\/tambah.php","modules_icon":"fa-plus","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"113","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Rawat Jalan","modules_route":"medcheck\\/index.php?tipe=2","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"99","id_parent":"0","modules":"medcheck","modules_action":"index","modules_name":"Medical Check","modules_route":"medcheck\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"},{"id":"114","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Rawat Inap","modules_route":"medcheck\\/index.php?tipe=3","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"114","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Radiologi","modules_route":"medcheck\\/index.php?tipe=4","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"102","id_parent":"99","modules":"medcheck","modules_action":"medcheck_pemb_list","modules_name":"Pembayaran","modules_route":"medcheck\\/data_pemb.php","modules_icon":"fa-shopping-cart","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"103","id_parent":"99","modules":"medcheck","modules_action":"medcheck_batal_list","modules_name":"Medcheck Batal","modules_route":"medcheck\\/data_hapus.php","modules_icon":"fa-trash-alt","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"}]'),
	(2, 'owner2', 'Pemilik Perusahaan 2', '[{"id":"1","id_parent":"0","modules":"master","modules_action":"index","modules_name":"Master","modules_route":"master\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"},{"id":"101","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_detail","modules_name":null,"modules_route":null,"modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"0"},{"id":"1","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_dft_tambah","modules_name":"Pendaftaran","modules_route":"medcheck\\/daftar.php","modules_icon":"fa-user-plus","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"1","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_dft","modules_name":"Antrian","modules_route":"medcheck\\/data_pendaftaran.php","modules_icon":"fa-users","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"100","id_parent":"99","modules":"medcheck","modules_action":"trans_medcheck","modules_name":"Tambah Checkup","modules_route":"medcheck\\/tambah.php","modules_icon":"fa-plus","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"113","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Rawat Jalan","modules_route":"medcheck\\/index.php?tipe=2","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"99","id_parent":"0","modules":"medcheck","modules_action":"index","modules_name":"Medical Check","modules_route":"medcheck\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"},{"id":"114","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Rawat Inap","modules_route":"medcheck\\/index.php?tipe=3","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"114","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Radiologi","modules_route":"medcheck\\/index.php?tipe=4","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"102","id_parent":"99","modules":"medcheck","modules_action":"medcheck_pemb_list","modules_name":"Pembayaran","modules_route":"medcheck\\/data_pemb.php","modules_icon":"fa-shopping-cart","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"103","id_parent":"99","modules":"medcheck","modules_action":"medcheck_batal_list","modules_name":"Medcheck Batal","modules_route":"medcheck\\/data_hapus.php","modules_icon":"fa-trash-alt","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"}]'),
	(3, 'owner', 'Pemilik Perusahaan', '[{"id":"1","id_parent":"0","modules":"master","modules_action":"index","modules_name":"Master","modules_route":"master\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"},{"id":"101","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_detail","modules_name":null,"modules_route":null,"modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"0"},{"id":"1","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_dft_tambah","modules_name":"Pendaftaran","modules_route":"medcheck\\/daftar.php","modules_icon":"fa-user-plus","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"1","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_dft","modules_name":"Antrian","modules_route":"medcheck\\/data_pendaftaran.php","modules_icon":"fa-users","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"114","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Data Medcheck","modules_route":"medcheck\\/index.php?tipe=1&filter_bayar=0","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"99","id_parent":"0","modules":"medcheck","modules_action":"index","modules_name":"Medical Check","modules_route":"medcheck\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"},{"id":"102","id_parent":"99","modules":"medcheck","modules_action":"medcheck_pemb_list","modules_name":"Pembayaran","modules_route":"medcheck\\/data_pemb.php","modules_icon":"fa-shopping-cart","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"103","id_parent":"99","modules":"medcheck","modules_action":"medcheck_batal_list","modules_name":"Medcheck Batal","modules_route":"medcheck\\/data_hapus.php","modules_icon":"fa-trash-alt","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"}]'),
	(4, 'adminm', 'Manager', '[{"id":"1","id_parent":"0","modules":"master","modules_action":"index","modules_name":"Master","modules_route":"master\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"},{"id":"101","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_detail","modules_name":null,"modules_route":null,"modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"0"},{"id":"1","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_dft_tambah","modules_name":"Pendaftaran","modules_route":"medcheck\\/daftar.php","modules_icon":"fa-user-plus","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"1","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_dft","modules_name":"Antrian","modules_route":"medcheck\\/data_pendaftaran.php","modules_icon":"fa-users","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"114","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Laboratorium","modules_route":"medcheck\\/index.php?tipe=1&filter_bayar=0","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"113","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Rawat Jalan","modules_route":"medcheck\\/index.php?tipe=2","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"99","id_parent":"0","modules":"medcheck","modules_action":"index","modules_name":"Medical Check","modules_route":"medcheck\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"},{"id":"114","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Rawat Inap","modules_route":"medcheck\\/index.php?tipe=3","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"114","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Radiologi","modules_route":"medcheck\\/index.php?tipe=4","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"102","id_parent":"99","modules":"medcheck","modules_action":"medcheck_pemb_list","modules_name":"Pembayaran","modules_route":"medcheck\\/data_pemb.php","modules_icon":"fa-shopping-cart","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"103","id_parent":"99","modules":"medcheck","modules_action":"medcheck_batal_list","modules_name":"Medcheck Batal","modules_route":"medcheck\\/data_hapus.php","modules_icon":"fa-trash-alt","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"}]'),
	(5, 'admin', 'Staff Admin', '[{"id":"1","id_parent":"0","modules":"master","modules_action":"index","modules_name":"Master","modules_route":"master\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"},{"id":"101","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_detail","modules_name":null,"modules_route":null,"modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"0"},{"id":"1","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_dft_tambah","modules_name":"Pendaftaran","modules_route":"medcheck\\/daftar.php","modules_icon":"fa-user-plus","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"1","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_dft","modules_name":"Antrian","modules_route":"medcheck\\/data_pendaftaran.php","modules_icon":"fa-users","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"114","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Data Medcheck","modules_route":"medcheck\\/index.php?tipe=1&filter_bayar=0","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"99","id_parent":"0","modules":"medcheck","modules_action":"index","modules_name":"Medical Check","modules_route":"medcheck\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"},{"id":"102","id_parent":"99","modules":"medcheck","modules_action":"medcheck_pemb_list","modules_name":"Pembayaran","modules_route":"medcheck\\/data_pemb.php","modules_icon":"fa-shopping-cart","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"103","id_parent":"99","modules":"medcheck","modules_action":"medcheck_batal_list","modules_name":"Medcheck Batal","modules_route":"medcheck\\/data_hapus.php","modules_icon":"fa-trash-alt","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"}]'),
	(6, 'purchasing', 'Purchasing', '[{"id":"1","id_parent":"0","modules":"master","modules_action":"index","modules_name":"Master","modules_route":"master\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"}]'),
	(7, 'gudang', 'Gudang', '[{"id":"1","id_parent":"0","modules":"master","modules_action":"index","modules_name":"Master","modules_route":"master\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"},{"id":"101","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_detail","modules_name":null,"modules_route":null,"modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"0"},{"id":"1","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_dft","modules_name":"Pendaftaran","modules_route":"medcheck\\/data_pendaftaran.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"100","id_parent":"99","modules":"medcheck","modules_action":"trans_medcheck","modules_name":"Tambah Checkup","modules_route":"medcheck\\/tambah.php","modules_icon":"fa-plus","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"113","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Rawat Jalan","modules_route":"medcheck\\/index.php?tipe=2&filter_bayar=0","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"99","id_parent":"0","modules":"medcheck","modules_action":"index","modules_name":"Medical Check","modules_route":"medcheck\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"},{"id":"114","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Rawat Inap","modules_route":"medcheck\\/index.php?tipe=3","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"114","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Radiologi","modules_route":"medcheck\\/index.php?tipe=4","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"102","id_parent":"99","modules":"medcheck","modules_action":"medcheck_pemb_list","modules_name":"Pembayaran","modules_route":"medcheck\\/data_pemb.php","modules_icon":"fa-shopping-cart","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"103","id_parent":"99","modules":"medcheck","modules_action":"medcheck_batal_list","modules_name":"Medcheck Batal","modules_route":"medcheck\\/data_hapus.php","modules_icon":"fa-trash-alt","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"}]'),
	(8, 'perawat', 'Perawat Rawat Jalan', '[{"id":"101","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_detail","modules_name":null,"modules_route":null,"modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"0"},{"id":"1","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_dft_tambah","modules_name":"Pendaftaran","modules_route":"medcheck\\/daftar.php","modules_icon":"fa-user-plus","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"1","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_dft","modules_name":"Antrian","modules_route":"medcheck\\/data_pendaftaran.php","modules_icon":"fa-users","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"114","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Data Medcheck","modules_route":"medcheck\\/index.php?tipe=1&filter_bayar=0","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"99","id_parent":"0","modules":"medcheck","modules_action":"index","modules_name":"Medical Check","modules_route":"medcheck\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"},{"id":"99","id_parent":"0","modules":"master","modules_action":"data_barang_list","modules_name":"Cek Harga","modules_route":"master\\/data_barang_list.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"}]'),
	(9, 'kasir', 'Kasir', '[{"id":"101","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_detail","modules_name":null,"modules_route":null,"modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"0"},{"id":"99","id_parent":"0","modules":"medcheck","modules_action":"index","modules_name":"Medical Check","modules_route":"medcheck\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"},{"id":"102","id_parent":"99","modules":"medcheck","modules_action":"data_medcheck_kasir_list","modules_name":"Medcheck","modules_route":"medcheck\\/index.php?tipe=1&filter_bayar=0","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"102","id_parent":"99","modules":"medcheck","modules_action":"medcheck_pemb_list","modules_name":"Pembayaran","modules_route":"medcheck\\/data_pemb.php","modules_icon":"fa-shopping-cart","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"99","id_parent":"0","modules":"master","modules_action":"data_barang_list","modules_name":"Cek Harga","modules_route":"master\\/data_barang_list.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"}]'),
	(10, 'dokter', 'Dokter', '[{"id":"101","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_detail","modules_name":null,"modules_route":null,"modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"0"},{"id":"114","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Data Medcheck","modules_route":"medcheck\\/index.php?tipe=1&filter_bayar=0","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"99","id_parent":"0","modules":"medcheck","modules_action":"index","modules_name":"Medical Check","modules_route":"medcheck\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"},{"id":"99","id_parent":"0","modules":"master","modules_action":"data_barang_list","modules_name":"Cek Harga","modules_route":"master\\/data_barang_list.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"}]'),
	(11, 'farmasi', 'Apoteker', '[{"id":"101","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_detail","modules_name":null,"modules_route":null,"modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"0"},{"id":"113","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Data Medcheck","modules_route":"medcheck\\/index.php?tipe=1&filter_bayar=0","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"99","id_parent":"0","modules":"medcheck","modules_action":"index","modules_name":"Medical Check","modules_route":"medcheck\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"},{"id":"99","id_parent":"0","modules":"master","modules_action":"data_barang_list","modules_name":"Cek Harga","modules_route":"master\\/data_barang_list.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"}]'),
	(12, 'analis', 'Analis /  Lab', '[{"id":"101","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_detail","modules_name":null,"modules_route":null,"modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"0"},{"id":"1","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_dft_tambah","modules_name":"Pendaftaran","modules_route":"medcheck\\/daftar.php","modules_icon":"fa-user-plus","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"1","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_dft","modules_name":"Antrian","modules_route":"medcheck\\/data_pendaftaran.php","modules_icon":"fa-users","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"113","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Medcheck","modules_route":"medcheck\\/index.php?tipe=1&filter_bayar=0","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"99","id_parent":"0","modules":"medcheck","modules_action":"index","modules_name":"Medical Check","modules_route":"medcheck\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"},{"id":"99","id_parent":"0","modules":"master","modules_action":"data_barang_list","modules_name":"Cek Harga","modules_route":"master\\/data_barang_list.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"}]'),
	(13, 'radiografer', 'Radiografer', '[{"id":"101","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_detail","modules_name":null,"modules_route":null,"modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"0"},{"id":"1","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_dft_tambah","modules_name":"Pendaftaran","modules_route":"medcheck\\/daftar.php","modules_icon":"fa-user-plus","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"1","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_dft","modules_name":"Antrian","modules_route":"medcheck\\/data_pendaftaran.php","modules_icon":"fa-users","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"113","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Data Medcheck","modules_route":"medcheck\\/index.php?tipe=1&filter_bayar=0","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"99","id_parent":"0","modules":"medcheck","modules_action":"index","modules_name":"Medical Check","modules_route":"medcheck\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"},{"id":"99","id_parent":"0","modules":"master","modules_action":"data_barang_list","modules_name":"Cek Harga","modules_route":"master\\/data_barang_list.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"}]'),
	(14, 'perawat_ranap', 'Perawat Rawat Inap', '[{"id":"101","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_detail","modules_name":null,"modules_route":null,"modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"0"},{"id":"99","id_parent":"0","modules":"medcheck","modules_action":"index","modules_name":"Medical Check","modules_route":"medcheck\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"},{"id":"114","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Data Medcheck","modules_route":"medcheck\\/index.php?tipe=1&filter_tipe=3&filter_bayar=0","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"99","id_parent":"0","modules":"master","modules_action":"data_barang_list","modules_name":"Cek Harga","modules_route":"master\\/data_barang_list.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"}]'),
	(15, 'pasien', 'Pasien', NULL),
	(16, 'fisioterapi', 'Fisioterapi', '[{"id":"101","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_detail","modules_name":null,"modules_route":null,"modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"0"},{"id":"114","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Data Medcheck","modules_route":"medcheck\\/index.php?tipe=1&filter_bayar=0","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"99","id_parent":"0","modules":"medcheck","modules_action":"index","modules_name":"Medical Check","modules_route":"medcheck\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"},{"id":"99","id_parent":"0","modules":"master","modules_action":"data_barang_list","modules_name":"Cek Harga","modules_route":"master\\/data_barang_list.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"}]'),
	(17, 'gizi', 'Gizi', '[{"id":"101","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_detail","modules_name":null,"modules_route":null,"modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"0"},{"id":"99","id_parent":"0","modules":"medcheck","modules_action":"index","modules_name":"Medical Check","modules_route":"medcheck\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"},{"id":"114","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Data Medcheck","modules_route":"medcheck\\/index.php?tipe=3&filter_bayar=0","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"99","id_parent":"0","modules":"master","modules_action":"data_barang_list","modules_name":"Cek Harga","modules_route":"master\\/data_barang_list.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"}]'),
	(18, 'nonentri', 'Non Entri', NULL);

-- Dumping structure for table esensiaco_medkit_dev.tbl_ion_login_attempts
DROP TABLE IF EXISTS `tbl_ion_login_attempts`;
CREATE TABLE IF NOT EXISTS `tbl_ion_login_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_ion_login_attempts: ~0 rows (approximately)
DELETE FROM `tbl_ion_login_attempts`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_ion_modules
DROP TABLE IF EXISTS `tbl_ion_modules`;
CREATE TABLE IF NOT EXISTS `tbl_ion_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) NOT NULL DEFAULT 0,
  `modules` varchar(50) DEFAULT NULL,
  `modules_action` varchar(50) DEFAULT NULL,
  `modules_name` varchar(50) DEFAULT NULL,
  `modules_route` varchar(50) DEFAULT NULL,
  `modules_param` varchar(50) DEFAULT NULL,
  `modules_icon` varchar(50) DEFAULT NULL,
  `is_parent` enum('0','1') DEFAULT '0',
  `is_sidebar` enum('0','1') DEFAULT '0',
  `is_view` enum('0','1') DEFAULT '0',
  `is_save` enum('0','1') DEFAULT '0',
  `is_update` enum('0','1') DEFAULT '0',
  `is_delete` enum('0','1') DEFAULT '0',
  `note` text DEFAULT NULL,
  `sort` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_ion_modules: ~0 rows (approximately)
DELETE FROM `tbl_ion_modules`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_ion_users
DROP TABLE IF EXISTS `tbl_ion_users`;
CREATE TABLE IF NOT EXISTS `tbl_ion_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `id_app` int(11) unsigned NOT NULL DEFAULT 0,
  `ip_address` varchar(45) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned DEFAULT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `nama_dpn` varchar(50) DEFAULT NULL,
  `nama_blk` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `pss` varchar(50) DEFAULT NULL,
  `file_name` text DEFAULT NULL,
  `file_base64` longtext DEFAULT NULL,
  `status_gudang` enum('0','1','2') DEFAULT '0',
  `tipe` enum('0','1','2') DEFAULT '1' COMMENT '0=none;\r\n1=karyawan;\r\n2=pasien;',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52495 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_ion_users: ~140 rows (approximately)
DELETE FROM `tbl_ion_users`;
INSERT INTO `tbl_ion_users` (`id`, `username`, `id_app`, `ip_address`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `nama_dpn`, `nama_blk`, `nama`, `first_name`, `last_name`, `birthdate`, `address`, `company`, `phone`, `pss`, `file_name`, `file_base64`, `status_gudang`, `tipe`) VALUES
	(1, 'root', 1, '127.0.0.1', '$2y$08$r308QSZd8f7/68yKpkpMY.J.sfTc3ceK3raBNAPii4vRnLIe.jqD.', '', 'admin@admin.com', '', NULL, NULL, 'RxCZnvoR/mUeTe47', 1268889823, 1674577864, 1, NULL, NULL, NULL, 'Administrator', NULL, NULL, NULL, 'ADMIN', '', NULL, '', '', '1', '1'),
	(41, 'superadmin', 1, '112.78.39.51', '$2y$08$H7wD4wuHVPiAzZAjPM1Q3.wfDpeVEusYph457IQzON0TH7i3ggQga', 'EBo75QJvR14a7H9c', 'noreply@esensia.co.id', NULL, NULL, NULL, 'Uxra100ud6903EBO', 1560132540, 1729579155, 1, '', '', '', 'MIKHAEL FELIAN WASKITO', NULL, '1990-02-15', 'Pemilik Perusahaan', NULL, NULL, 'Admin@123', '', '', '1', '1'),
	(81, 'es_ayunda', 1, '149.113.90.64', '$2y$08$Mm2MZc6v92NtGn9uIEYAOeDbRY2V1GA950bjUXBBdmGae2.KIJRVy', 'Q1KOrLuAxBB7ctQz', 'noreply@esensia.co.id', NULL, NULL, NULL, 'SFN89/LXTO57uKwV', 1663432809, 1731122673, 1, '', 'S.Kep', '', 'AYUNDA AMALIA, S.Kep', NULL, '1995-10-05', 'DUSUN KRAJAN RT 003 RW 002 DESA KALIWENANG TANGGUNGHARJO', NULL, NULL, 'medkit05', '', '', '1', '1'),
	(82, 'es_yuli', 1, '149.113.90.64', '$2y$08$OFS0pKHfGV/QPvgfvTCe0.5Fzw/i05G7GXTz3ymtyR3Gu8tRkYO/y', 'GONIvZ8lsTAHjFC9', 'noreply@esensia.co.id', NULL, NULL, NULL, 'pJGqvlzFqz7L4mZ4', 1663433339, 1687685096, 1, NULL, NULL, '', 'Ns. YULI FAEDAH, A.Md.Kep', NULL, '0000-00-00', 'Perawat Rawat Inap', NULL, NULL, NULL, '', '', '1', '1'),
	(86, 'es_ridha', 1, '116.254.116.210', '$2y$08$zPv9TrxwTbtg4N4mCTd9q.u3c0pOHr7RiJ94rNjmp.N5L7ovrqvcm', 'FqkBjGTwuqgcPd6.', 'noreply@esensia.co.id', NULL, NULL, NULL, 'PiPRZQBagKlAngI.', 1663832710, 1731379225, 1, '', 'A.Md.Kep', '', 'RIDHA WIDHININGTYAS, A.Md.Kep', NULL, '1995-04-28', 'Jl. Taman Tlogomulyo No 51 Pedurungan ', NULL, NULL, '280495', '', '', '1', '1'),
	(151, 'es_tika', 1, '103.30.183.250', '$2y$08$iBa9d84lsKfvXf9z4g.uLuCpn8AXRqTT5TIo8czQfQ0nybE7jbvI.', 'lo9A5FhuClmfaZBr', 'noreply@esensia.co.id', NULL, NULL, NULL, 'JwL8R7OdnsimckMM', 1670666282, 1731463100, 1, '', '', '', 'YOESTIKA SARI, A.Md.Kep', NULL, '1993-08-11', 'JL. MERPATI II / 21 RT 007 RW 009 PEDURUNGAN TENGAH ', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(152, 'es_tari', 1, '103.30.183.250', '$2y$08$OATObsFBbFb3sfPYjNxOdeXIn2Mx3G4lm5rIm7SV6cRnsRTTl5hKW', '7aVhG7cM34oXO8n8', 'noreply@esensia.co.id', NULL, NULL, NULL, 'og.62HLx.0PploGo', 1670666332, 1725695018, 1, NULL, NULL, '', 'SRI LESTARI, A.Md.Kep', NULL, '1997-11-14', 'Turus Bumirejo Karangawen Demak', NULL, NULL, '141195', '', '', '1', '1'),
	(153, 'es_miranti', 1, '103.30.183.250', '$2y$08$uEZtZ22Wy4sNs.q0oM11BurdMLywp1xT5VGlwNx5.AGgPt29hBCZq', 'qbyadceH3IDT5.ex', 'noreply@esensia.co.id', NULL, NULL, NULL, '7nP5p/K5kZbn0PfU', 1670666386, 1731471574, 1, 'Ns.', 'S.Kep', '', 'Ns. PUTRI MIRANTI, S.Kep', NULL, '1994-10-18', 'Perawat Rawat Jalan', NULL, '081326982663', 'admin1234', '', '', '1', '1'),
	(155, 'es_ika', 1, '103.30.183.250', '$2y$08$gBAJ6EZJTW/jXwXwvwkvlehrh9INwKp.RyvooWPQ875e5.YXkVhsC', 'IckN1Ff0VcAvArJq', '', NULL, NULL, NULL, 'wm3ns2X8pA2L/xMO', 1670666517, 1731494472, 1, 'Ns.', 'S.Kep', '', 'Ns. IKA PRATIWI, S.Kep', NULL, '1996-07-12', 'Perawat Rawat Jalan', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(156, 'es_dani', 1, '103.30.183.250', '$2y$08$QQZvUh1AusGouBLl9kMmnuU/zYcO5sk0.8cWPK0y7grkyc3EjJIIC', 'seXWFFQkwOXLfkwU', '', NULL, NULL, NULL, '0lfbZKLXJ6WucN1Y', 1670666582, 1727076730, 1, 'Ns.', 'S.Kep', '', 'Ns. ANI FITRISARI, S.Kep', NULL, '0000-00-00', 'Perawat Rawat Jalan', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(157, 'es_hervina', 1, '103.30.183.250', '$2y$08$9jWDDztb1wY.hKG4RehQA.vW6QGdKRUJ/2sFmDOEBIx6opyK.1hsm', 'KSESci42uMZmLgri', '', NULL, NULL, NULL, 'ThHWitkhyK6BDhAa', 1670666614, 1731510536, 1, '', 'A.Md. A.k', '', 'HERVINA NUR LAILA, A.Md. Kep.', NULL, '1986-01-06', 'Jl. Banget Prasetya II no.49 RT 08/RW 06 Kel. Bangetayu Kulon, Kecamatan Genuk, Kota Semarang', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(158, 'es_titut', 0, '103.30.183.250', '$2y$08$K155J/NjYqN/1VteOMmEL.3zNSMbc/15x/Qk6uicn5tCD75/X.bSy', 'TEyn7IRGKpY1RTFN', '', NULL, NULL, NULL, '2Jq8n3RHpSMs1971', 1670666676, 1712328879, 1, NULL, NULL, '', 'Ns. TITUT FERRA SIAMI, S.Kep', NULL, '0000-00-00', 'Perawat Rawat Inap', NULL, NULL, 'dera123', '', '', '1', '1'),
	(159, 'es_lastri', 1, '103.30.183.250', '$2y$08$s.bEVBB3EI4sBDcl39H5gOXDy6ra1KM3kkdfM.JhYTFgqF///A/iu', 'oiwroG6XMB3RUayT', NULL, NULL, NULL, NULL, '/fcdEgFVVi2fMEhh', 1670666731, 1731485795, 1, 'Ns.', 'S.Kep', '', 'Ns. SULASTRI, S.Kep', NULL, '1986-03-16', 'PERUMAHAN MUTIARA SEBAKUNG BLOK B NO 4 RT 09/ RW 06 BANGETAYU KULON GENUK SEMARANG', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(160, 'es_intan', 1, '103.30.183.250', '$2y$08$XiHYXzZv96703bwWbVTqFeKDC4HGEZ63gFNtEtqTcmEXB2h/58oPC', '5ixY05TKKA4ET7vE', 'noreply@esensia.co.id', NULL, NULL, NULL, 'rxjLe.zwNqngE4yf', 1670666778, 1726737209, 1, '', 'A.Md.Kep', '', 'INTAN TRI UTAMI, A.Md.Kep', NULL, '1998-03-11', 'JAMUS GERJEN RT 03 RW 02 MRANGGEN DEMAK', NULL, NULL, 'intan123', '', '', '1', '1'),
	(161, 'es_handa', 1, '103.30.183.250', '$2y$08$alf5dpd1WUYyLCF2E8RJPObfMX448iZIiIgF1dwNcqgfJakVys50K', 'MZRDllO6re2gR3dM', 'noreply@esensia.co.id', NULL, NULL, NULL, 'hcY93XLoTTn3AKRM', 1670666833, 1699291915, 1, NULL, NULL, '', 'Ns. NS. NUR HANDAYANI, S.KEP, S.Kep', NULL, '0000-00-00', 'Perawat Rawat Inap', NULL, NULL, NULL, '', '', '1', '1'),
	(162, 'es_devi', 1, '103.30.183.250', '$2y$08$fjmF0wJyhFomNMYnJWfJwOmtjiBwF7wRTUmxhnIRQiHaDFnTA5.ei', 'Kh8q0FSWBYivBp8B', '', NULL, NULL, NULL, 'GAzx49V2ZIa5UFDs', 1670666944, 1731503417, 1, NULL, NULL, '', 'Ns. DEVI ARIYANTI, S.Kep', NULL, '1992-01-27', 'Perawat Rawat Inap', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(163, 'es_rita', 1, '103.30.183.250', '$2y$08$zf73Fr/fUv15CEI7h1QSZ.bPhgkiedOCZvKlVRy2.DX6ZmkLYA9vy', 'lv9Bs.Nj6GcixdIX', '', NULL, NULL, NULL, 'RCnnBtG.kYD78lzs', 1670666999, 1730517727, 1, NULL, NULL, '', 'Ns. RITA KURNIASIH, S.Kep', NULL, '1996-08-28', 'JL. GAJAH TIMUR DALAM IV NO. 7 RT 1 RW 9 , KEL. GAYAMSARI, KEC. GAYAMSARI, SEMARANG', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(164, 'es_wiwin', 0, '103.30.183.250', '$2y$08$qQgE8PMY5T34eDcQ9RS6FumN1n5CrjFWwjmsehZD.CxSjR4tjJBf6', 'v/bNvLe.nTZOjvQD', '', NULL, NULL, NULL, 'h.Rt4JzwLDxoAuXG', 1670667059, 1731497226, 1, NULL, NULL, '', 'Ns. WINARSIH, S.Kep', NULL, '0000-00-00', 'Perawat Rawat Inap', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(165, 'es_novi', 0, '103.30.183.250', '$2y$08$T0tTfmKihPuxn4uXSt4sxureWgBM.026Un10mR7uZQXPF.VvOmsuG', 'gP.phCXBX26Z5z2O', '', NULL, NULL, NULL, 'IbtPOLbnoEQr4098', 1670667110, 1715065820, 1, NULL, NULL, '', 'Ns. NOVI PRAMESTY PUTRI A, S.Kep', NULL, '0000-00-00', 'Perawat Rawat Inap', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(166, 'es_ana', 1, '103.30.183.250', '$2y$08$J3BvACHARGBIEmZM7hv27ubmcynoyF1JtfcbKI32EB/dgBeORiqEO', '/mmaJFfm3CFYGF7I', 'noreply@esensia.co.id', NULL, NULL, NULL, '0aFX6CA1z1f1TQR6', 1670667169, 1687590973, 1, NULL, NULL, '', 'Ns. TRIE BUANA NOVITASARI', NULL, '0000-00-00', 'Perawat Rawat Inap', NULL, NULL, NULL, '', '', '1', '1'),
	(167, 'es_lia', 1, '103.30.183.250', '$2y$08$eteIeOaNEUWL5tf8tje9r.NeB6ykfH6nPIYENN09DKRi0KldtQNVm', 'ns4Rh/4XXhfJvs97', '', NULL, NULL, NULL, '2goYpvGJlv9ZGBiv', 1670667263, 1731450177, 1, '', 'A.Md', '', 'MAFRUKHA ARBI ZULPRILIANA, A.Md', NULL, '1994-04-13', 'Perumahan Sembungharjo Permai Blok C No.5 RT 05 RW 08 Kel.Sembungharjo, Kec.Genuk, Kota Semarang', NULL, NULL, 'lia13', '', '', '1', '1'),
	(168, 'es_utami', 1, '103.30.183.250', '$2y$08$Uj5GsVMJJbhBmAFRJzhBlOVwqs.AFtfotB43rjYPTnVXsG0P8wgBi', 'HhaeuBGiB6b/ALQG', '', NULL, NULL, NULL, 'EdCydKFPdGAHGz.k', 1670667308, 1731467819, 1, NULL, NULL, '', 'SRI WAHYU BUDI UTAMI, A.Md. A.k', NULL, '1987-08-31', 'JL. BANGET PRASETYA IV NO.178 RT 02 RW 06 BANGETAYU KULON, GENUK, KOTA SEMARANG', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(169, 'es_ayu', 1, '103.30.183.250', '$2y$08$wE68/1HY7Iq67JTPeVHVzOAg7TUTqbnao8YcJY0HPn1QSwCAeRVDW', 'F7I.9b77I.TWokqa', 'noreply@esensia.co.id', NULL, NULL, NULL, 'm1JGZzXFw5TzLmMi', 1670667371, 1731503861, 1, '', 'A.Md. A.k', '', 'DIAH AYU KARTIKA, A.Md. A.k', NULL, '1997-01-22', 'JL. PUCANG SANTOSO TENGAH V NO.36 RT 12 RW 30 BATURSARI MRANGGEN', NULL, '085326187220', 'admin1234', '', '', '1', '1'),
	(170, 'es_farida', 1, '103.30.183.250', '$2y$08$GTHTIKsU5xVxj8j9D6rVPOFJyxGVb9RDNC5SQAaNxsHyKSETNLyuO', 'PW8sF9h8iIOIzpKl', '', NULL, NULL, NULL, 'wywRXPQKGst6wCV9', 1670667425, 1731507116, 1, '', 'A.Md. A.k', '', 'FARIDHATUL HIDAYAH, A.Md. A.k', NULL, '1996-08-30', 'Analis /  Lab', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(171, 'es_bintari', 0, '103.30.183.250', '$2y$08$PssWIpwl5MlaBccDNl74wuwzELnK01HVfoDRahwmLws91nsq12Z82', 'VncC8Gxa8UFtYHao', '', NULL, NULL, NULL, '8wVuMS4/yQBpJDHZ', 1670667470, 1731494468, 1, NULL, NULL, '', 'EKO WAHYU BINTARI, S.Tr.A.k', NULL, '0000-00-00', 'Analis /  Lab', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(172, 'es_siti', 1, '103.30.183.250', '$2y$08$NisCcBUtiGrKM4C5iALPG.n39e7UQyidVjy3jMVc00fiv/yZqi47C', 'YfHTNyf890LKq5it', '', NULL, NULL, NULL, 'FOERdm/njcnh8OLt', 1670667517, 1731372487, 1, '', '', '', 'SITI AMINAH ', NULL, '1998-06-23', 'Dsn. Krajan II, Dsa Kalanglundo, Kec Ngaringan Kabupaten Grobogan ', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(175, 'es_herin', 0, '103.30.183.250', '$2y$08$JnBNhJBp26GV5XiK1kL64uAntqSN3UELHpM9JdJub4Uy8cwxLTrEW', 'MMT6p7Pte8dudjav', '', NULL, NULL, NULL, 'feEciCbrrnI1vMfg', 1670667664, 1731374449, 1, NULL, NULL, '', 'DEWI HERINDA HAPSARI, Amd.kes', NULL, '0000-00-00', 'Analis /  Lab', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(177, 'es_arik', 1, '103.30.183.250', '$2y$08$ybcM94zut/U7t6eWjt4zQexLk3Y7tPpEC/GQUDbtJtJJBgNsP32AG', 'r2MvceFEUAa/W6lq', '', NULL, NULL, NULL, 'svzkwthTwrKebWBA', 1670667768, 1731038779, 1, '', ' M.Si', '', 'apt. ARIK DIAN EKA PRATIWI,  M.Si', NULL, '1990-09-19', 'Dusun Melian RT 2 RW 1\r\nDesa Kropak Kecamatan Winong\r\nKabupaten Pati - Jawa Tengah\r\n59181', NULL, NULL, 'Arik123', '', '', '1', '1'),
	(178, 'es_afrisa', 1, '103.30.183.250', '$2y$08$GVvbEKkcczoD4eHEcbV7Wu2qvgYj/P2MvKgWmI0btd8PgVH7B25Ja', 'omJM7nzNYDbDICa5', 'noreply@esensia.co.id', NULL, NULL, NULL, 'br0hviVC4ZQxevKq', 1670667820, 1708825398, 1, NULL, NULL, '', 'AFRISA DESY KHOIRIDA', NULL, '0000-00-00', 'Farmasi', NULL, NULL, 'always16', '', '', '1', '1'),
	(179, 'es_lina', 1, '103.30.183.250', '$2y$08$VN4aLoHu1XOAVMvFDkoUvO4XB1I6hZy8X1w5ff1auL1K.eewdeebu', 'Tf0kVivDyC7pYd2D', 'noreply@esensia.co.id', NULL, NULL, NULL, '32K1gj7ZTFYSrIND', 1670667877, 1731514662, 1, '', '', '', 'RIDA YATUL ARI FADLINA, S. Farm', NULL, '2000-08-06', 'KAYON RT 6/ RW 2 BATURSARI, MRANGGEN DEMAK', NULL, NULL, 'rida123', '', '', '1', '1'),
	(182, 'es_fira', 1, '103.30.183.250', '$2y$08$2l/hUG4CozpMbTnqeBfgHuLXm7W0pC5vXRp0J/KPZTanJMlB6GxFe', 'yAEKaNWHyVa.hMII', 'noreply@esensia.co.id', NULL, NULL, NULL, 'MjJ0OadW5BTxIEiR', 1670668045, 1731459237, 1, '', 'Amd.farm', '', 'FIRA SILVI ARIFA, Amd.farm', NULL, '1997-12-11', 'Farmasi', NULL, '0895604786419', '089842', '', '', '1', '1'),
	(183, 'es_rini', 1, '103.30.183.250', '$2y$08$RqK8cpWDEKlkGXz/uiJFauxWaoe3EWm8lQ48plfPDQfTZTK0heiCi', 'FwsY/bvQjNHJFnRL', '', NULL, NULL, NULL, '1Bc.ksT9u8dIDd7K', 1670668118, 1718586016, 1, '', 'Amd. Keb', '', 'RINI SUSILOWATI, Amd. Keb', NULL, '1995-09-07', 'Perawat Rawat Jalan', NULL, '083842647915', 'admin1234', '', '', '1', '1'),
	(184, 'es_bella', 1, '103.30.183.250', '$2y$08$ol0I9Kdtkq73YB5nMkYQLeKncYmthxnGqjCupJwcwT1zoDhHUOIgK', 'QNl8NZAli1dzJHdl', 'noreply@esensia.co.id', NULL, NULL, NULL, 'zbEOndN5tIFBid1p', 1670668164, 1731494571, 1, NULL, NULL, '', 'BELLA SINTIYA, Amd. Keb', NULL, '1997-08-12', 'Perawat Rawat Jalan', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(185, 'es_lala', 1, '103.30.183.250', '$2y$08$shcJa8WLC0Xww21Wt48LFeX45GW9jU4V1j7p7DTzk79hKS5KxanHC', 'GSlJb2nCfH0OBhRw', '', NULL, NULL, NULL, '7V5rmRFzCGAbFBC9', 1670668203, 1731480264, 1, NULL, NULL, '', 'NUR AULA, Amd. Keb', NULL, '1996-12-06', 'Banjarsari RT.002 RW.001 Kandangan\r\nKabupaten Temanggung', NULL, NULL, '06bidan', '', '', '1', '1'),
	(186, 'es_khusnul', 0, '103.30.183.250', '$2y$08$KEOwZ/XzVLXvEQy3b2rmt.f3r4q1Tc8Apq5xzXZWlof1wFOlp6uTi', 'oMjnVY4aFy84/vDE', '', NULL, NULL, NULL, '5xVpPX0yd8u5c.UM', 1670668244, 1695402893, 1, NULL, NULL, '', 'IKA KHUSNUL P, S.Tr.Keb', NULL, '0000-00-00', 'Perawat Rawat Jalan', NULL, NULL, NULL, '', '', '1', '1'),
	(187, 'es_wina', 0, '103.30.183.250', '$2y$08$ocFMKat3G1e/nuWaWQp7juyU1TzZfb83/9PZd6DbAtI9kyU63/oOa', '/ZMFSkPlZjyvkU7/', '', NULL, NULL, NULL, 'NnPhcn9LAbDS6uCf', 1670668323, 1694778456, 1, NULL, NULL, '', 'WINA ASTARI, Amd.keb', NULL, '0000-00-00', 'Perawat Rawat Jalan', NULL, NULL, NULL, '', '', '1', '1'),
	(190, 'es_imerza', 1, '103.30.183.250', '$2y$08$bKgwtujzxafYBuh.rlwsKumiXfuXoepfWdKg3tZgHMdA39Ai9shdK', 'G/xNDqUPqI8nVczG', '', NULL, NULL, NULL, 'GAeGZrMWG3XFe7Xz', 1670668570, 1731503831, 1, '', 'S.Tr.Kes (Rad)', '', 'ELIYAS ALVIA IMERZA PRAMESWARI, S.Tr.Kes (Rad)', NULL, '1998-10-29', 'TLAHAB KIDUL RT.001/RW.007, KEC. KARANGREJA, KAB.PURBALINGGA', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(191, 'es_risa', 1, '103.30.183.250', '$2y$08$GlDoNeE39pOs4acfr4bgMOK2pV/1qk/cHHizHQFOXLkqnEbsEFezm', 'yODpa/ajMMHpwWTk', 'noreply@esensia.co.id', NULL, NULL, NULL, '/CBqxtMAEf6WlU1h', 1670668673, 1731463981, 1, NULL, NULL, '', 'RISA AL JANAH, Amd. Keb', NULL, '1993-05-04', 'Candisari 003/004 Candisari Mranggen Demak', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(192, 'es_novia', 1, '103.30.183.250', '$2y$08$A6kjq2M47oL7wXltBXBWW.WVR8LerKjRQpnAUEyqFAYyJ8bMLU5Lm', 'k44gDlp3rTJArPU2', 'noreply@esensia.co.id', NULL, NULL, NULL, 'uDLzIP.dpXGtHB5S', 1670668739, 1731479926, 1, '', '', '', 'NOVIA IKA WULANDARI', NULL, '2001-01-20', 'JL. Taman Tlogomulyo Selatan V gg 6 (Raflesia) No. 12 Rt 04 Rw 06 Pedurungan Tengah Semarang', NULL, NULL, 'novia20', '', '', '1', '1'),
	(193, 'es_junita', 1, '103.30.183.250', '$2y$08$tLIg795JaPxEL7PfHfLbPeuDUqVqdFgHnoRrrgv4GgdwO0y6CMjNa', 'ESKBJyipzn6RM.ET', 'noreply@esensia.co.id', NULL, NULL, NULL, 'TIbHUYNIQC7mo6Nv', 1670668797, 1731505970, 1, 'NY', '', '', 'JUNITA PUTRI SONITA', NULL, '1996-06-24', 'JL. JATEN IV NO 19 RT 002 / 008 PEDURUNGAN TENGAH', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(195, 'es_indra', 1, '103.30.183.250', '$2y$08$e4AXkaCo.hWeTZAa8g88xOk0LdmE5XplIPFLQdhLam9QBggE7zgLS', 'I4HaxASSHAuNv0r0', 'noreply@esensia.co.id', NULL, NULL, NULL, 'D3yWcrouSJPec3rt', 1670668894, 1731464081, 1, '', '', '', 'INDRA AYU', NULL, '0000-00-00', 'Kasir', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(196, 'es_narulita', 1, '103.30.183.250', '$2y$08$.teRIv/bJXEs0rVs9yVfNefwqKARW4uX5yWEAD1TNkABGFONOnmjG', '9.9c8ApKWgdijk9/', 'noreply@esensia.co.id', NULL, NULL, NULL, '8M6XtecggBxmmHB1', 1670668987, 1731498111, 1, NULL, NULL, '', 'NARULITA NOOR WIDYA, S.E', NULL, '1995-11-17', 'JL PUCANG ASRI III/ 44 RT 001 RW 012 BATURSARI, MRANGGEN, DEMAK, JAWA TENGAH ', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(197, 'es_maulida', 1, '103.30.183.250', '$2y$08$OfZ1bS11j95qBZC8uDjm9.AHepbOp76GaG2PvFuQ4Q0lWukvfBcVm', 'GycGhm5MCHAcce8S', 'noreply@esensia.co.id', NULL, NULL, NULL, 'H1ZzeY46cXNQT5ip', 1670669037, 1731337414, 1, '', '', '', 'MAULIDA LAILYRAHMAH', NULL, '1999-06-16', 'JL. TLOGOSARI WETAN RT. 3 RW. 2 TLOGOSARI WETAN, PEDURUNGAN SEMARANG', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(198, 'yanuardani', 1, '103.30.183.250', '$2y$08$bNe8iQ.hwHQQsn/OBaZmeOIvOlPoX6P6jmOJxHZgUfJCsvN1b777G', 'Kv0SMXrjeZplgssa', '', NULL, NULL, NULL, '4YCQxnWj9DPPPUy0', 1670669186, 1731504509, 1, 'dr.', 'M. Kes, Sp.PD, K-Psi, FINASIM', '', 'dr. YANUAR ARDANI, M. Kes, Sp.PD, K-Psi, FINASIM', NULL, '1989-01-17', 'Jl.Wolter Monginsidi No.40 , RT 001 RW 006, Pedurungan Tengah', NULL, NULL, 'peace', '', '', '1', '1'),
	(199, 'es_dr.amalia', 1, '103.30.183.250', '$2y$08$6sAlbW5ujLMSPi3Gn6.NOOws3O1VrtLUDVIv0qhjnyt5WJ2P/5lbO', 'GLP3rELtWmcQS0ta', '', NULL, NULL, NULL, 'qyArk.rfaCOgCCWw', 1670669237, 1731505072, 1, 'dr.', 'M.Si.Med.,Sp.A', '', 'dr. AMALLIA NUGGETSIANA, M.Si.Med.,Sp.A', NULL, '1982-12-01', 'Jl. mahesa timur No. 360 Rt 07 rw 03 pedurungan tengah semarang', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(200, 'es_dr.erien', 0, '103.30.183.250', '$2y$08$QgYsfmTPsG671GArVMSaWee3NPpB.X/k1Q05txuCKn6EgsZ8E1TRi', 'LkrvNX3Pe/Ond43A', '', NULL, NULL, NULL, '8UvHTlmfzmU0VARA', 1670669303, 1680342733, 1, NULL, NULL, '', 'dr. ERIEN AFRINIA ASRI, Sp.DV', NULL, '0000-00-00', 'Dokter Jaga', NULL, NULL, NULL, '', '', '1', '1'),
	(201, 'es_dr.miftah', 1, '103.30.183.250', '$2y$08$.WQYgcOS4qDTeLj7JSG6xetIFfIZk5CvXAAr1ah6WuJ6ssjHRz3a6', 'N5LV1Ue0/yvf.obH', 'noreply@esensia.co.id', NULL, NULL, NULL, 'TuIfQeYCRSnY5scb', 1670669387, 1716431393, 1, 'dr.', 'Sp.RAD', '', 'dr. MIFTAKHUL JANNAH KURNIAWATI, Sp.RAD', NULL, '1987-09-17', 'Jl. bulu stalan II/297 Rt 02 Rw 03 Kec. Bulu Semarang', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(202, 'es_dr.rahmad', 1, '103.30.183.250', '$2y$08$bxIkeWzsRBa7kJXua3cHFO2is7ySCVvUpl3hVIYdt5sHZNurBKaiW', 'qaaXSZ04A9WrTG0.', 'noreply@esensia.co.id', NULL, NULL, NULL, 'AODpnO65CVW1hfdW', 1670669446, 1730193249, 1, 'dr.', 'Sp.OG (K)', '', 'dr. RAHMAD RIZAL BUDI W, Sp.OG (K)', NULL, '1984-10-15', 'Jl. mahesa raya  No 8 Rt 02 rw 03 pedurungan tengah semarang', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(203, 'es_dr.atik', 1, '103.30.183.250', '$2y$08$qHxWDglHAnau3wtNIBqMLeWNJtw3nIChmOM93Lm.WrhqT9npBiJ.2', '3PsOJjEUdMo/TvDm', '', NULL, NULL, NULL, 'ggS0RLSmOoem2i5E', 1670669491, 1730108707, 1, 'dr.', 'Sp.A', '', 'dr. NOOR HIDAYATI, Sp.A', NULL, '1960-12-27', 'Jl. beruang Raya Rt 005 rw 002, Kel. Gayamsari semarang', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(204, 'es_dr.ingga', 1, '103.30.183.250', '$2y$08$Z5N7cc295/.O8a.796DJnO9h1j2xciUVggz0w4NME8ZofKMU.jE9C', 'P5LnTKVtuP0heljR', 'noreply@esensia.co.id', NULL, NULL, NULL, 'iGhOi8wn7JG2iySr', 1670669549, 1692796224, 1, NULL, NULL, '', 'dr. INGGA IFADA', NULL, '0000-00-00', 'Dokter', NULL, NULL, NULL, '', '', '1', '1'),
	(205, 'es_drg.nella', 0, '103.30.183.250', '$2y$08$R4EKxGjGS3aUA7awalhTdeCj6PEyt9KKSH7EgBgFNzT4NOteaIvAu', 'UBgg2PMR.jU6jU8K', '', NULL, NULL, NULL, 'lgGcQYzaTc71tUS9', 1670669601, 1682412375, 1, NULL, NULL, '', 'drg. NELLA KEUMALA H', NULL, '0000-00-00', 'Dokter Jaga', NULL, NULL, NULL, '', '', '1', '1'),
	(206, 'es_faiza', 1, '103.30.183.250', '$2y$08$BUrflNrKkkt.aOK53J.7L.gNwazdqebrZoP9Me/KZ5HKMSj6qUE7W', '5KWzb2YHLrgul7Ik', 'noreply@esensia.co.id', NULL, NULL, NULL, 'c6oj7h.l.V5TeA2G', 1670669683, 1731487336, 1, '', 'S.M', '', 'NUR LAILATUL FAIZAH, S.M', NULL, '1993-02-04', 'Jl. Kebon Rejo Barat 9/1 RT 8 RW 14 Mranggen Demak', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(207, 'es_vivin', 1, '103.30.183.250', '$2y$08$90NR0p/EFLWumrLWsvQwLuQInvq.pti18eFmfPnfJstvzvJ0BXP0e', '9d2V9t2V15M0LjGU', NULL, NULL, NULL, NULL, 'x2fu8b1ckOzAouxN', 1670669731, 1689399757, 1, '', 'S.Farm', NULL, 'VIVIN AGUSTIANI', NULL, NULL, NULL, NULL, NULL, NULL, '', '', '1', '1'),
	(209, 'es_fitri', 1, '103.30.183.250', '$2y$08$TywqRDAE6etzxBxJSVUTeuDUQEQ7Napb6oy4kZu3Qrp1z8RwuDkUi', 'FcaifaLY3TiCM2Q2', 'noreply@esensia.co.id', NULL, NULL, NULL, '2Oxl9oP.r0O53AHh', 1670669826, 1731373781, 1, '', 'S.E.', '', 'FITRI PUJIANTI, S.E.', NULL, '1997-02-14', 'Jl MERPATI III ', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(210, 'es_dindaayu', 1, '103.30.183.250', '$2y$08$LEAhtmCM4PpcqW8QkKlPN.NnWeqBMQxEEBMCpjQZHYH6FdUXhx1vm', '6oAvx8R3QoV5Jzvd', 'noreply@esensia.co.id', NULL, NULL, NULL, 'zqUYwI4.QfD0CBEq', 1670669877, 1731461572, 1, NULL, NULL, '', 'DINDA AYU WANDHIRA', NULL, '1999-11-03', 'Staff Admin', NULL, NULL, 'muhana40', '', '', '1', '1'),
	(211, 'es_mawar', 1, '103.30.183.250', '$2y$08$DHpnMggvu77fRPQvUgf2tOGPlJYXxn9LfInwUXSdaaugkOxFpFr6S', 'T9oCw7QzLn2bvnKw', '', NULL, NULL, NULL, 'd7Nb24gOn5ab4eB/', 1670669940, 1731026304, 1, '', 'S.Tr.E', '', 'MAWAR ANAHIDAYAH, S.Tr.E', NULL, '1997-09-05', 'Staff Admin', NULL, NULL, 'klinikesensia!', '', '', '1', '1'),
	(213, 'es_yani', 0, '192.168.10.1', '$2y$08$LIPSd2ARXnoVMuTcHyO95OMDL9BuDDCnIbh03Mx1waiL7VakAyG1u', 'bMOAu5uOWw/4lbnc', 'noreply@esensia.co.id', NULL, NULL, NULL, 'cQb9sSwa1MWPk5q0', 1671674362, 1687336850, 1, NULL, NULL, '', 'Ns. SRI HANDAYANI, S.Kep', NULL, '0000-00-00', 'Perawat Rawat Inap', NULL, NULL, NULL, '', '', '1', '1'),
	(214, 'es_pradita', 1, '192.168.10.1', '$2y$08$z6oy9Pxw6nGM3vCkpuzsOOHSMkuiy3fyRXe51HQuWXGGwR.TJJvK2', 'SnlkSfFmj7zdBnqw', 'noreply@esensia.co.id', NULL, NULL, NULL, 'MzZWqj2zSBropH7P', 1671674920, 1731425995, 1, '', 'A.Md.Farm', '', 'PRADITA HUBTAMARA PUTRI, A.Md.Farm', NULL, '1998-05-12', 'JL. SEDAYU PELEM NO. 11, RT 12 RW 01, BANGETAYU KULON, KEC. GENUK, KOTA SEMARANG', NULL, NULL, '160322', '', '', '1', '1'),
	(215, 'es_solitia', 1, '192.168.10.1', '$2y$08$/JQczVHvflCgRZVFZeu40ujwmUGM4s7IKADOZ.860ckbY/f4M09/.', 'xfo766kZ.4NPd6qF', 'noreply@esensia.co.id', NULL, NULL, NULL, 'gEeRYIV9NxPqFEGE', 1671676123, 1731503873, 1, '', 'S. Keb', '', 'SOLITIA LUNGIT, S. Keb', NULL, '1999-01-23', 'Perawat Rawat Jalan', NULL, '089652058288', 'admin1234', '', '', '1', '1'),
	(216, 'es_dindaatika', 1, '192.168.10.1', '$2y$08$zr5cpmB754BYOromoXkZreLlEj7NNEZOF8FrF1bVi2xrL4qpKm/MK', 'iGxToNJ2C0PeN2D2', 'noreply@esensia.co.id', NULL, NULL, NULL, 'YKcDE74vjAjC7LLQ', 1671676229, 1731493522, 1, '', 'S.Tr.Kes (Rad)', '', 'DINDA ATIKA SARI, S.Tr.Kes (Rad)', NULL, '1998-04-08', 'Jl. Brotojoyo Timur III No.20 RT 07 RW 02 Panggung Kidul, Semarang Utara', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(217, 'es_aida', 1, '192.168.10.1', '$2y$08$RIN3dEsetXAKtTocOOLbOukPmbZZBgC4qbjVS/hQTJCzTSkZ8aBNG', '8fjU/tpDfUdDW88W', 'noreply@esensia.co.id', NULL, NULL, NULL, 'X5gAA9bjACmScmwT', 1671676382, 1731471370, 1, '', '', '', 'AIDA BERLIANI', NULL, '2001-05-04', 'Farmasi', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(218, 'es_dania', 1, '192.168.10.1', '$2y$08$maEF93LAG9IujrZeU0YqTux0X5VQaGM1oOClUm4hUB7L0S7JkN4/a', 'gmxp/Ts2ganATowJ', 'noreply@esensia.co.id', NULL, NULL, NULL, 'y1cy/0ANc/HW4v5P', 1671676416, 1731514346, 1, '', 'S.KM', '', 'DANIA GALUH MARDIANA, S.KM', NULL, '1999-03-28', 'BLORA', NULL, NULL, 'dania28', '', '', '1', '1'),
	(219, 'es_dr.Iman', 1, '192.168.10.1', '$2y$08$dT9fZOH9Qu6iHYfVfMOPIeqinvy8vV3lB0fxVgrpQkL1jPZMyRMom', 'QyX/jzg3EwidG05i', 'noreply@esensia.co.id', NULL, NULL, NULL, 'fXBsn3/yh2FkSXPT', 1671676793, 1731487689, 1, 'dr.', 'Sp.THT-KL', '', 'dr. NUR IMAN NUGROHO, Sp.THT-KL (K)', NULL, '1983-11-29', 'Jl. sapta prasetya no 44 Rt 04 rw 03 kel. pedurngan kidul pedurungan semarang', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(220, 'es_dr.agung', 1, '192.168.10.1', '$2y$08$n9DKSgsVLlq/YoRdeYI28eed4YFYv1AzV5KTgT6gdjI1LuQK2AXza', 'F.ono58dYdcjjtC9', 'noreply@esensia.co.id', NULL, NULL, NULL, '53tjwZKBKvhDkXWq', 1671676851, 1731494619, 1, NULL, NULL, '', 'dr. AGUNG PRAMARTHA IRAWAN, M.Biomed, Sp.OG', NULL, '1987-06-28', 'Jl. kelud utara iii no.2 rt 01 rw 01 petompon Kec. Gajah mungkur semarang', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(221, 'es_dr.anita', 1, '192.168.10.1', '$2y$08$gvQVkZK5hetNl09kdvfVTe4L.Ju2z5CJRYXOT0eJAff5.5lNgHZ7S', 'kP67CTpaVOKpHtqr', 'noreply@esensia.co.id', NULL, NULL, NULL, 'yTYA66QAQJJkO3uO', 1671677058, 1716145015, 1, NULL, NULL, '', 'dr. ANITA TRI HASTUTI, Sp.PK', NULL, '1980-09-24', 'Permata [uri, J;. Bukit Tunggal blok C.1/31 rt 05 rw 08 Ngaliyan semarang', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(222, 'es_desi', 1, '192.168.10.1', '$2y$08$X6bcyEbhBOPiNwZgaezr5eFm1KqpijOX4p//rhZSjHMSo4qX.R17S', 'jQdehS9vJpSBoHw2', 'noreply@esensia.co.id', NULL, NULL, NULL, 'hXfFIzWKTY0puihl', 1671677713, 1723191614, 1, '', '', '', 'DESI SUCI LESTARI', NULL, '1995-12-21', 'MAGESEN PONCOL NO 44 RT 008 RW 006', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(223, 'es_dr.titis', 1, '192.168.10.1', '$2y$08$qY8DJHSNZVB3Swj.3/kldOWi33AMivzwhBYQncOIfMUvOtaUKVn3u', 'mgKe1qlIJfPPxPm.', 'noreply@esensia.co.id', NULL, NULL, NULL, 's7/UZZ2CUO9Ppa5Q', 1671677815, 1731421728, 1, 'dr.', '', '', 'dr. TITIS YUNVICASARI', NULL, '1991-06-19', 'Jl. Menjangan Dalam II no 38 Rt 03 rw 10 kel. palebon semarang', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(224, 'es_dr.natalia', 1, '192.168.10.1', '$2y$08$Da30MNeDWRkNUachOYVFS.tu6SBuDW4Ag26hJrjP4tMYpivZ4EJEm', 'wzigT6A7IRINf8Ht', 'noreply@esensia.co.id', NULL, NULL, NULL, 'IgIJwVijW4Yjxm0s', 1671677872, 1731509342, 1, 'dr.', '', '', 'dr. NATALIA CAROLINA HARWANTO', NULL, '1993-05-25', 'Jl. rejosari gang V/25 rT 06 rW 10 REJOSARI semarang', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(225, 'es_dr.gina', 1, '192.168.10.1', '$2y$08$MlB.MPSGeEEC0bCENH4J/..O8Kb1cL3GDAkPwTzILDzBGrgRWegde', 'awHhUbxzVfsHDpKl', 'noreply@esensia.co.id', NULL, NULL, NULL, 'tqmiGKy.6GkDIav8', 1671677934, 1702198081, 1, 'dr.', '', '', 'dr. GINA AMALIA', NULL, '0000-00-00', 'Dokter Jaga', NULL, NULL, NULL, '', '', '1', '1'),
	(226, 'es_dr.gita', 1, '192.168.10.1', '$2y$08$A8catZ5vSBdGALF0tjfLh.pmAvJwlZjfkG2QcfWrdvIsNisi8Iw0.', 'ZyL0QOau8f.ctxrO', 'noreply@esensia.co.id', NULL, NULL, NULL, 'Q4TDIzee.vUResqS', 1671678086, 1721744247, 1, 'dr.', '', '', 'dr. REZA ANGGITA SALZABELLA', NULL, '1993-10-28', 'Jl. rafflesia Residence B.22 rT 07 RW 08 PEDURUNGAN TENGAH SEMARANG', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(227, 'es_dr.vania', 1, '192.168.10.1', '$2y$08$9EgxMBAjRMe.VoMQlnh74e7L8x44OHrOAxwUTZz9svx64oa2kZSqm', '0hYDHPULYP6Lbqqx', 'noreply@esensia.co.id', NULL, NULL, NULL, 'KdYF8mpGRn5x2GvX', 1671678184, 1719301336, 1, 'dr.', '', '', 'dr. VANIA OKTAVIANI SUJAMTO', NULL, '1995-10-25', 'Jl. Tirtomulyo  Mukti IV/145 A Rt 04 Rw 07 Tlogomulyo pedurungan semarang', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(228, 'es_dr.winny', 1, '192.168.10.1', '$2y$08$EGMc3wlVEQYu.jM8IyGfUeU7BjpsRurePdEOGKE99JeAfr41cG6Cm', '.BsxdXEv5brrkieK', 'noreply@esensia.co.id', NULL, NULL, NULL, 'wc.B7EzsSo6QLub7', 1671678298, 1722378410, 1, 'dr.', '', '', 'dr. WIANDARTI THERESIANI', NULL, '1974-09-23', 'Jl. mahesa raya No.6 rT 02 RW 03 PEDURUNGAN TENGAH PEDURUNGAN SEMARANG', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(229, 'es_dr.yani', 1, '192.168.10.1', '$2y$08$4330xkISryPPpseUbcx/oO6sqx4rgKB5FrluXtUmHWHy8CgAGmJUm', 'JC9nPnB49pxnnEPc', 'noreply@esensia.co.id', NULL, NULL, NULL, 'CUXjvfyR2.Mh6eLj', 1671678362, 1726063719, 1, NULL, NULL, '', 'dr. YANI FATRIYA', NULL, '1985-06-20', 'Jl. Mahesa Timur No. 360 Rt 07 Rw 03 pedurungan tengah semarang', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(231, 'es_dr.adli', 1, '192.168.10.1', '$2y$08$8JpS6Jqs8gYxejf1Dw6yhO3AGefiPj527UNmhZOLJnU7ePm8FBWLa', 'aWCderod0Ib5f70o', 'noreply@esensia.co.id', NULL, NULL, NULL, 'ahdJMG3ZvQKPW9cl', 1671678492, 1731494457, 1, 'dr.', '', '', 'dr. ADLI CHAIRUL UMAM', NULL, '1996-12-20', 'Jl. wonodri baru No. 68 Rt 09 rw 02 semarang selatan semarang', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(232, 'es_dr.ardhila', 0, '192.168.10.1', '$2y$08$wAD59tydWL2dCLWxPb3.nOEd.QmH/jgPbnghQion07Io25Fk..rsK', '8gxoKlGVZaw48Y45', 'noreply@esensia.co.id', NULL, NULL, NULL, 'nCX9/gl4Ib40dsFA', 1671678582, 1692868746, 1, NULL, NULL, '', 'dr. ARDHILLA', NULL, '0000-00-00', 'Dokter Jaga', NULL, NULL, NULL, '', '', '1', '1'),
	(233, 'es_dr.salma', 0, '192.168.10.1', '$2y$08$hvQqUhOJrhMDcNjwFhrg.u3w99u83IPX0cR2hpGClwqlhzKPRi9Py', '/6PM95IYJEWDl6P2', 'noreply@esensia.co.id', NULL, NULL, NULL, 'IVbyVvngAe2PrEsA', 1671678621, 1687573966, 1, NULL, NULL, '', 'dr. SALMA ADHENIA', NULL, '0000-00-00', 'Dokter Jaga', NULL, NULL, NULL, '', '', '1', '1'),
	(234, 'es_dr.fajar', 1, '192.168.10.1', '$2y$08$0Q/F2dgMTPHXlxRsTOgjxeButGH13NzyqtJCrI5TTzOcMJlNi.FNW', '8ql.hVk5pAg./uOE', 'noreply@esensia.co.id', NULL, NULL, NULL, 's1hWlf7IAO8N.aTb', 1671678650, 1731397315, 1, 'dr.', '', '', 'dr. MUHAMMAD FAJAR SHODIQ', NULL, '2024-04-26', 'Jl Pahlawan 30 RT 01/05 Temanggung', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(235, 'es_dr.glen', 1, '192.168.10.1', '$2y$08$y/JK1utujieIgPuHuBYxQ.DjVsekFkwjJ//OXXTrFPJlRV3TA2i32', 'PgKFNwzNeVHPmTF3', 'noreply@esensia.co.id', NULL, NULL, NULL, 'dkd5h8wt6bUUJjhm', 1671678671, 1731459635, 1, 'dr.', '', '', 'dr. GLENN FERNANDEZ YEREMIA ', NULL, '1996-05-19', 'JAKARTA', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(236, 'es_drg.muhtar', 1, '192.168.10.1', '$2y$08$Jpj2cxN2WtuD8nT0GN3QJOVc7R2X4bmr0MoMR9OB9L8oE2U0pnNVu', '9bb7AKD.CoQg9tz8', 'noreply@esensia.co.id', NULL, NULL, NULL, 'DZTeOq6VjN.03gf9', 1671678828, 1731405501, 1, NULL, NULL, '', 'drg. M MUHTAR SAFANGAT., Sp.Ort', NULL, '1985-05-18', 'SEMARANG ', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(237, 'es_drg.lola', 1, '192.168.10.1', '$2y$08$kTDlPxi8HCwW7IANtrzuNul2YKQVK3DLgZ4uR8Mszs.VBsuKI/un2', 'lhDRIHACo8lctA0m', 'noreply@esensia.co.id', NULL, NULL, NULL, 'EmXFtI9x9W0zNCC4', 1671679128, 1731463106, 1, NULL, NULL, '', 'drg. LOLA CAROLA', NULL, '1993-03-07', 'SEMARANG ', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(238, 'es_drg.lita', 0, '192.168.10.1', '$2y$08$wWBHpSFPBt9Hcq6rpzB.8ulXaIVfhTG6.dLKUE.K5/.Ab0wEPS14q', 'ffyUjYB7IYz7f6l1', 'noreply@esensia.co.id', NULL, NULL, NULL, 'icVMnJzfuZiWQ.4W', 1671679203, 1731111157, 1, NULL, NULL, '', 'drg. LITA PARAMITA', NULL, '0000-00-00', 'Dokter Jaga', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(240, 'es_wulan', 1, '192.168.10.1', '$2y$08$jU9MyLGwMgoAy.WNoz9ZIu/VVhNDHpepRBzUh7NMT1W3icAt1lwoq', 'F.l.bQ4n2qZ7W9bi', 'noreply@esensia.co.id', NULL, NULL, NULL, 'pKXMqWe6coiR8uMc', 1671679669, 1731476038, 1, '', 'Amd.kep', '', 'WULAN PRIHANDINI, Amd.kep', NULL, '1988-04-15', 'Perawat Rawat Jalan', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(241, 'es_dr.mahesa', 1, '192.168.10.1', '$2y$08$HhNV9hKpPwyR2oK/hClwCepGqV5TUHO0JpUhHFp14dNzp4YeFvFVS', 'RhKdQocIGuOEz3g9', 'noreply@esensia.co.id', NULL, NULL, NULL, 'U5zTKy2oTD7fxOyi', 1671681292, 1724932050, 1, 'dr.', 'Sp.KJ', '', 'dr. MAHESA, Sp.KJ', NULL, '1988-07-15', 'tamansari majapahit cluster grand indrapasta C.6 no 3 Rt 05 rw 06 Pedurungan lor semarang', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(242, 'es_dr.luqman', 0, '192.168.10.1', '$2y$08$3Y6eWeyjFge7dNTqxY9HXOac3yUl8XWEKxTC.EXUi5wDUWr2a5.qi', '9pqVCI4rqVAJTLxV', 'noreply@esensia.co.id', NULL, NULL, NULL, NULL, 1671681368, NULL, 1, NULL, NULL, '', 'dr. LUQMAN ALWI, M.Si.MED, Sp.B', NULL, '0000-00-00', 'Dokter Jaga', NULL, NULL, NULL, '', '', '1', '1'),
	(243, 'es_dr.sekar', 1, '192.168.10.1', '$2y$08$C3VbFLpqEkbNcOMB8a376uczTdPhWGu/yMe7S5Jwb/EQpEI0PX/5.', 'Wj5yDtrJxnQ3.B0N', 'noreply@esensia.co.id', NULL, NULL, NULL, NULL, 1671681509, NULL, 1, NULL, NULL, '', 'dr. SEKAR HAPSARI, Sp.RAD', NULL, '0000-00-00', 'Dokter Jaga', NULL, NULL, NULL, '', '', '1', '1'),
	(244, 'es_dr.priska', 1, '192.168.10.1', '$2y$08$hDTZvoeQ.yZZTuHSxvoRuu9xivgDhNrdQyEVmBGoURUxCP7uQyAzG', 'z.j12sr5TMVgCjyy', 'noreply@esensia.co.id', NULL, NULL, NULL, 'DBo/AcwPdbhq8kcL', 1671681542, 1722345197, 1, 'dr.', 'Sp.PD', '', 'dr. PRISKA LUFTIA R, Sp.PD', NULL, '1987-11-25', 'jl. bukit anyelir no. 28 Rt 00 rw 00 Kel. srondol kulon Kec. Banyumanik semarang', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(245, 'es_dr.marlina', 1, '192.168.10.1', '$2y$08$BAzqrntNYZUeMCJ9MkZAWeVPd5rzMLLk/xw376.BT3Hn1B2W2oclu', '.Ecmq30RgJVrpmii', 'noreply@esensia.co.id', NULL, NULL, NULL, NULL, 1671681573, NULL, 1, NULL, NULL, '', 'dr. MARLINA TANDI, Sp.RAD', NULL, '0000-00-00', 'Dokter Jaga', NULL, NULL, NULL, '', '', '1', '1'),
	(246, 'es_dr.fransiska', 0, '192.168.10.1', '$2y$08$02.rR/cRkv3Q8ktH5u5/c.nUfY8aJU7yLxXWpYXzxE.IUF4fgO0gS', 'J8Xq9ZkAtXgt2NjU', 'noreply@esensia.co.id', NULL, NULL, NULL, NULL, 1671681673, NULL, 1, NULL, NULL, '', 'dr. FRANSISKA BANJARHANOR, Sp.M', NULL, '0000-00-00', 'Dokter Jaga', NULL, NULL, NULL, '', '', '1', '1'),
	(247, 'es_dr.salmah', 0, '192.168.10.1', '$2y$08$1LU//Hw3BQo6MwyCzI9Vo.CN6OunR8VQGfHTdZ7tYvlCnH7HbkGPy', 'KApJ1YO5Cw1b6P5w', 'noreply@esensia.co.id', NULL, NULL, NULL, '/o3bXRwgNOKYhq4/', 1671681711, 1688462129, 1, NULL, NULL, '', 'SALMAH ALAYDRUS', NULL, '0000-00-00', 'Dokter Jaga', NULL, NULL, NULL, '', '', '1', '1'),
	(248, 'es_fina', 1, '192.168.10.1', '$2y$08$L68/.AbonO.LARtp01EEu.jmP1XC0X8.x5fqldFyxwcUVArCef1Ru', 'xi6EPyxYvfLkg5mY', 'noreply@esensia.co.id', NULL, NULL, NULL, 'r.T1cgIyi1mUXvzp', 1674703023, 1718795501, 1, NULL, NULL, '', 'FINA SULISTIYANI', NULL, '0000-00-00', 'Dokter', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(249, 'es_dr.ursheila', 1, '192.168.10.1', '$2y$08$qDBTNhDoQjMMntom0cwul.erMm.wl.CiUDXUU2IPIMTqmGVcAGNNm', 'TodCsBf8N15qeLiS', 'noreply@esensia.co.id', NULL, NULL, NULL, 'Oc.eZjdtX.9vGGEs', 1674812818, 1731393200, 1, 'dr.', '', '', 'dr. URSHEILA HAEKMATIAR', NULL, '1998-07-31', 'Jl. rogojembangan Timur Rt 03 rw 05 tandang tembalang', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(250, 'es_alfian', 1, '192.168.10.1', '$2y$08$/OZI5QWXYZOELXWGZA.XvOoNICMPgaQD3lV.4B.G4Yld4v1jb0kXO', 'UCrax4DIst1fRM4k', NULL, NULL, NULL, NULL, 'ON68yNwPygIU3yiN', 1674824602, 1731514651, 1, '', 'S.Kom', '', 'ALFIAN HARI SUSATYA, S.Kom', NULL, '1994-09-04', 'LAMPER', NULL, NULL, 'ogawa77', '', '', '1', '1'),
	(251, 'es_aaf.taftajani', 1, '192.168.10.1', '$2y$08$DamJRP1tswzGlxtviXLCs.XqhT2CsHdqVE4lFYF2V5dDbN4g/ps3W', 'ku3O8j.jRtwI8YC9', 'noreply@esensia.co.id', NULL, NULL, NULL, 'lsNKsW/0OPTfgRuF', 1674960044, 1731498136, 1, NULL, NULL, '', 'AAF TAFTAJANI', NULL, '0000-00-00', 'Pemilik Perusahaan', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(252, 'es_yuni', 1, '192.168.10.1', '$2y$08$c/PoHAqz0km/1jLG7QtCyeo2yA0Cuiq28Y3fnhnreUMizIchYlgT2', 'Z9FsgQ2i5Z8Wi74E', NULL, NULL, NULL, NULL, '1N014HKpveSShJ4o', 1675831040, 1730356343, 1, '', 'Amd', '', 'YUNI DARWATI, Amd', NULL, '1986-06-19', 'jl. Selomulyo Mukti Raya F 317 Semarang ', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(255, 'es_dr.nanda', 0, '192.168.10.1', '$2y$08$TMJPaCEnVjnKTYDGEVemrOxVDi9wcXb5XryMSQD6Sdla5fEruLzbG', 'g.VZqXUAPrUoYtxm', 'noreply@esensia.co.id', NULL, NULL, NULL, 'ZGxaacQ8PwvmEBMy', 1677474967, 1686236002, 1, NULL, NULL, '', 'NANDA ILHAM NK', NULL, '0000-00-00', '-', NULL, NULL, NULL, '', '', '1', '1'),
	(256, 'es_nailuz', 1, '192.168.10.1', '$2y$08$.EEc54ja7D51KCSvvK3BAunmYCoQ3ZDA6AjpyROGH/OGphh5O6u92', 'ZJtcIinnyICdVaHQ', 'noreply@esensia.co.id', NULL, NULL, NULL, 'Kr0OYn1F8JbKfk6X', 1678239529, 1731111166, 1, NULL, NULL, '', 'NAILUZ ZULFA, S.Tr.Kes', NULL, '1999-03-31', 'Jetis RT 01 RW 003 Tamansari, Mranggen, Demak', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(258, 'es_santi', 1, '202.80.217.183', '$2y$08$3pPprkR0lTOE.tb7lCWJ3uxAHfrYA5r6RUeE4waJYZi4myckvLKiy', 'c7C6fnnc2s2cO5AP', 'noreply@esensia.co.id', NULL, NULL, NULL, 'MnLOAd/3IaI8D9J1', 1682663934, 1731456626, 1, NULL, NULL, '', 'FITRI NUR HASANTI', NULL, '1996-05-29', 'kedungrejo 2/5 kedunguter karangtengah demak', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(259, 'es_dr.ditha', 1, '103.105.35.79', '$2y$08$Hdk/7P.7Gf1uuYNVydzequDIsxkhd9UWFBAhSHE/9KYfJrhGdgFTC', 'rezsCtGP5o/Mme5/', NULL, NULL, NULL, NULL, 'c2SkisK2CXpIS3j5', 1688438367, 1724053233, 1, 'dr.', 'Sp.M', '', 'dr. DITHA PARAMASITHA, Sp.M', NULL, '0000-00-00', '-', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(260, 'es_dr.ratna', 1, '103.105.35.79', '$2y$08$y92c5o84/EMgTlXL5zuDceWcp78v37Ao0KPFG5ThXWuMjaAqTnz/2', 'C33THoMXRpfE1rcr', 'noreply@esensia.co.id', NULL, NULL, NULL, 'rlPjpHIruCRQfWIc', 1688438459, 1731198233, 1, NULL, NULL, '', 'dr. RATNA DEWI FITRIA', NULL, '0000-00-00', 'Dokter', NULL, NULL, 'admin1234', '', '', '1', '1'),
	(531, 'es_vivin1', 1, '192.168.10.1', '$2y$08$ykW2jEbqwiU90RuvGgQ3Cu2So/HjxPzr8KJ0CIyS73C90j.1iixvS', 'Zwe2OBMaOAuZMsys', NULL, NULL, NULL, NULL, '9liQbL3YoLMIW2x.', 1689568905, 1731462534, 1, '', 'S.Farm', '', 'VIVIN AGUSTIANI, S.Farm', NULL, '1994-08-13', 'Jl. Sidorejo no. 9 RT 007 RW 007 Kelurahan Sambirejo Kecamatan Gayamsari Kota Semarang', NULL, NULL, 'admin1234', '', '', '0', '1'),
	(2269, 'es_nanda', 1, '192.168.10.1', '$2y$08$fQrh.TZN6leRG0omrI7TNuMtOjeRUNoWDgFdrCFlpoyJgSz/2XnaK', 'cPWW26huIA.5AiCi', NULL, NULL, NULL, NULL, 'hsyqiBluZ0lQyQQr', 1691231509, 1731512773, 1, 'apt', 's.farm', '', 'ANNISA NANDA SAFIRA', NULL, '1999-06-01', 'Sidokumpul 04/01 kec.Guntur Kab.Demak', NULL, NULL, 'admin1234', '', '', '0', '1'),
	(4952, 'es_coba2', 1, '192.168.10.1', '$2y$08$3.PMhC0EhgnI/tvOPvzEOuBhoREwcHtNLS9VhLWAxzm.xkHQXMVh2', 'GNhzcnWzO7mYSIan', NULL, NULL, NULL, NULL, NULL, 1694361790, NULL, 1, '', '', '', 'ZCOBA', NULL, '1998-09-16', 'xzczxc', NULL, NULL, NULL, '', '', '0', '1'),
	(5779, 'es_gizi', 1, '139.0.108.252', '$2y$08$fKp61o3tWOinzYuNR2Lwaub4ZfqGwD9PmUfevBZ1Xp/LpLM.6QYW.', 't.k7lqDmW.pmdiVy', NULL, NULL, NULL, NULL, 'O8gzF5EXrBCBqoc0', 1695397219, 1695397814, 1, '', '', '', 'GIZI', NULL, '2023-09-22', '-', NULL, NULL, NULL, '', '', '0', '1'),
	(5780, 'es_fisioterapi', 1, '139.0.108.252', '$2y$08$K9ETZ7ThNgesFqskGGL7jeFaJufwAfK0uiCZvvoPRyKl9Kbo.bsSu', '/F2Nt/WO6pwVrChe', NULL, NULL, NULL, NULL, 'n/HiRAI2Cb8G.P1P', 1695398068, 1709129120, 1, '', '', '', 'FISIOTERAPI', NULL, '2023-09-22', '-', NULL, NULL, 'admin1234', '', '', '0', '1'),
	(6506, 'es_desisuci', 1, '182.1.96.235', '$2y$08$iwSXmFIowBkaRDx/GLDe4uApBw2bHnADhvHUp9vkjWIrX.I88Yb3q', 'a68Jzo6/JyOREP7a', NULL, NULL, NULL, NULL, 'VQ1jD.A66MR2U.k5', 1696313992, 1731467693, 1, '', '', '', 'DESI SUCI LESTARI', NULL, '1995-12-21', 'MAGESEN PONCOL NO 44 RT 08 RW 06', NULL, NULL, 'admin1234', '', '', '0', '1'),
	(6860, 'es_hengki', 1, '103.162.237.218', '$2y$08$jwWdRi7SsjC44fucMc39BuXIeOINVSP2I2g9u02oRl/tFJji1FHN2', 'uYBGa8sSn1GwMa/p', NULL, NULL, NULL, NULL, 'g3nmb3y2.Oqj21E6', 1696644303, 1730695293, 1, '', '', '', 'HENGKI ULINUHA', NULL, '1992-12-27', '-', NULL, NULL, 'admin1234', '', '', '0', '1'),
	(6861, 'es_abdul', 1, '103.162.237.218', '$2y$08$p8/HYnC3Q1yI0sLL4NYmNenjILW/QkeqZwIDN/CM5tAFK3i7lkhxS', 'CVFBk6OrzB5dhC5H', NULL, NULL, NULL, NULL, 'Kc4Wm/KHx6gpx8K4', 1696644367, 1719211936, 1, '', '', '', 'ABDUL FATAH', NULL, '2021-01-10', '-', NULL, NULL, 'admin1234', '', '', '0', '1'),
	(6863, 'es_sugeng', 1, '103.162.237.218', '$2y$08$upea6TAYhrKIs/dKcj2WzumkPHMFNW1AMsd0GvK3tYXzoA2rNDTr6', 'byQL2NLaYip.KU0x', NULL, NULL, NULL, NULL, 'vFhGctJwQdeQVyp1', 1696644511, 1724209389, 1, '', '', '', 'SUGENG PURNOMO', NULL, '1975-02-22', '123456', NULL, NULL, 'admin1234', '', '', '0', '1'),
	(7004, 'es_srimurni', 1, '124.158.190.158', '$2y$08$2vUCrplvO2EnK6f.NuTEROkgCKbECTZ17GKEsmYiMlsKSXEbNOCQ6', 'fqoIIPAhREl4xk9G', NULL, NULL, NULL, NULL, 'um6YMNghwnD76Xgk', 1696816524, 1711339426, 1, '', '', '', 'SRI MURNI', NULL, '1974-04-13', 'non entry', NULL, NULL, 'admin1234', '', '', '0', '1'),
	(7005, 'es_retno', 1, '124.158.190.158', '$2y$08$QvLqzKOWWZbUi5.ZC2fuR.knBy7WlJ05ir4AiUlILBVZ/Xiu0yjAu', 'x/sthI/.WU1Y144q', NULL, NULL, NULL, NULL, 'wC5WHOe4KoBE48OC', 1696816636, 1720911741, 1, '', '', '', 'RETNO PRASETYONINGRUM', NULL, '1976-04-26', 'non entry', NULL, NULL, 'admin1234', '', '', '0', '1'),
	(7006, 'es_yulita', 1, '124.158.190.158', '$2y$08$dVwnW.Nq88muZ/yT5XlyOeP11suCQrN2.KO3UY79PkHp0fmDBdA8m', 'iB14Z6FL.dv29/oH', NULL, NULL, NULL, NULL, 'B4m1Z8J0ktCBmhRc', 1696816714, 1705471972, 1, '', '', '', 'YULITA ENDRIATI', NULL, '1981-07-14', 'non entry', NULL, NULL, NULL, '', '', '0', '1'),
	(7007, 'es_partiah', 1, '124.158.190.158', '$2y$08$HI8eigYtyeLoS3mx1acP..3WxY8Fo9.LDh19ycgd/CYs7Kb8WhEiG', 'SrPhXKUYvs1QnoxJ', NULL, NULL, NULL, NULL, 'tk7UjP/tUIV34Z4u', 1696817025, 1727481975, 1, '', '', '', 'PARTIAH', NULL, '1981-12-04', '-', NULL, NULL, 'admin1234', '', '', '0', '1'),
	(7008, 'es_anisetyo', 1, '124.158.190.158', '$2y$08$B8vnli9uxeiI9Fag2HSrm.8CcEUBMlPM9KdTm7K4MIFt/6YU6dzbO', 'qt7CUOIb92U/Mq1i', NULL, NULL, NULL, NULL, 'twA0zlZcQhLZcDsp', 1696817294, 1711338956, 1, '', '', '', 'ANI SETYO BUDI YATI', NULL, '1967-08-11', 'NON ENTRY', NULL, NULL, 'admin1234', '', '', '0', '1'),
	(7010, 'es_deasy', 1, '124.158.190.158', '$2y$08$dVGtg3D2JXPagDOhGpmdPOM9LbIWElQ3ce2SqPHxFc3FtBMYOhr/G', 'Y/W0CxjaqWPXp4jY', NULL, NULL, NULL, NULL, 'tfFMQYVai7be1ObI', 1696817714, 1702280755, 1, '', '', '', 'DEASY ARIYANTI', NULL, '1975-12-12', 'NON ENTRY', NULL, NULL, NULL, '', '', '0', '1'),
	(7011, 'es_supriyanti', 1, '124.158.190.158', '$2y$08$XQZJSOsn5nEN5PouQp2skuasFtz1cEUg5uk.KVDhf.sOG76QIaS2K', 'DwSNiBbDH55pBJgb', NULL, NULL, NULL, NULL, 'foiKzeKuwiq9jwYX', 1696817891, 1698891781, 1, '', '', '', 'SUPRIYANTI', NULL, '1990-08-13', 'NON ENTRY', NULL, NULL, NULL, '', '', '0', '1'),
	(7013, 'es_sulistyaningsih', 1, '124.158.190.158', '$2y$08$hMug.C75RP1SW3F/zv9BmODM8KvRlDZVzL25FFaTzSerohuFZhGAu', 'ba5ORnGaOmtHcbKe', NULL, NULL, NULL, NULL, '0S2tzzL7E92p9pPp', 1696818453, 1698580341, 1, '', '', '', 'SULISTYANINGSIH', NULL, '1960-12-18', 'NON ENTRY', NULL, NULL, NULL, '', '', '0', '1'),
	(7014, 'es_maryati', 1, '124.158.190.158', '$2y$08$dJdzrsBCNbBb1kBnj2OtnubEUKZYYBn8ArjR/vUWlQyyOv06DQdcq', 'KDZjQQK99trgHmDH', NULL, NULL, NULL, NULL, 'UQZ97iFIpfc9ZfbB', 1696818541, 1730164878, 1, '', '', '', 'MARYATI', NULL, '1971-02-07', 'NON ENTRY', NULL, NULL, 'admin1234', '', '', '0', '1'),
	(7016, 'es_christiana', 1, '124.158.190.158', '$2y$08$EQ7IhlEIuxhSqVsNNoxBq.CZQ1rhKIwg157YtDQD8jQGNAn9x41WW', 'OQoXSjHY/iTvBriR', NULL, NULL, NULL, NULL, 'ernzJv5iMCSKGyEe', 1696818818, 1698939439, 1, '', '', '', 'CHRISTIANA SUKO HARTANTI', NULL, '1977-09-23', 'NON ENTRY', NULL, NULL, NULL, '', '', '0', '1'),
	(7017, 'es_rachmad', 1, '124.158.190.158', '$2y$08$92FhPJG/Fx19CdAbclsS9OogI9Bbq00puD5ZjstIlzTTqBBl.7nHe', 'CbtFQgJ5Lq63RLo/', NULL, NULL, NULL, NULL, 'qPF9VBYjT/nJ.49K', 1696818933, 1729671230, 1, '', '', '', 'ANUGRAH RACHMAD HIDAYAT', NULL, '1986-09-16', 'Jalan kawi 5 no 70 rt 08 rw 03 kelurahan wonotingal kecamatan Candisari semarang', NULL, NULL, 'admin1234', '', '', '0', '1'),
	(7018, 'es_eko', 1, '124.158.190.158', '$2y$08$k2c2L6UQCA7nl6Uytggc2e1FeKZ.dh8ROSEfngye5Qtv/4eKm7txq', 'AkHY8OQt0/cao3By', NULL, NULL, NULL, NULL, 'wJibNhohyiriTguk', 1696819068, 1698746730, 1, '', '', '', 'EKO FITRIYADI', NULL, '1983-07-11', 'NON ENTRY', NULL, NULL, NULL, '', '', '0', '1'),
	(7019, 'es_slamet', 1, '124.158.190.158', '$2y$08$XvghUoMU7o8qraEauPCn0OlAKmHCjQJFLsVSY2G087bwerv3bL1TK', 'gAA8n1vI5BnqHWAd', NULL, NULL, NULL, NULL, 'wH5JJ3g1/ksbTpFf', 1696819210, 1724915797, 1, '', '', '', 'SLAMET SUGENG', NULL, '1984-03-05', 'NON ENTRY', NULL, NULL, 'admin1234', '', '', '0', '1'),
	(7020, 'es_siswahyudi', 1, '124.158.190.158', '$2y$08$RKyVUsNaVwqoccRzF7NDeuEakvEDksqSWikPoR2nr05TK1HY4UUYa', 'tnTdPuqoW/w1nLDJ', NULL, NULL, NULL, NULL, 'SGIJZlH1xK/75Gzh', 1696819350, 1718772382, 1, '', '', '', 'SISWAHYUDI', NULL, '1967-05-13', 'NON ENTRY', NULL, NULL, 'admin1234', '', '', '0', '1'),
	(7021, 'es_joko', 1, '124.158.190.158', '$2y$08$mLR3BoLqKfFcqgeFWVu66eYRiO2ZbwH00j3ply1OBjhH8Wzq7n..W', 'q/LGWLygdo1q696b', NULL, NULL, NULL, NULL, 'YQyTJ5x3vse2G36a', 1696819613, 1711610134, 1, '', '', '', 'JOKO PRASETYO', NULL, '1980-03-20', 'Jl.Batur sari VI  , Rt 03 / Rw 06  , Kel. Sawah besar  , Kec. Gayamsari Smg', NULL, NULL, 'admin1234', '', '', '0', '1'),
	(7022, 'es_apri', 1, '124.158.190.158', '$2y$08$aUh8NH7lV/Uw/7AHJEOddu4o.gduevAELl.203XcFNuCdRyqk4j2a', 'jQHId/YgxxyxHYlY', NULL, NULL, NULL, NULL, 'NLu4W6a6Sq6tWp1Q', 1696819805, 1698810942, 1, '', '', '', 'APRI WAHYONO', NULL, '1979-04-04', 'NON ENTRY', NULL, NULL, NULL, '', '', '0', '1'),
	(7023, 'es_heru', 1, '124.158.190.158', '$2y$08$KD9vOxMGAHst6zOdSnQF8ewRJbVp2KOZfbItdzkZbaSU8R4MEsl5S', 'iDY8ZtqbItBkcwnE', NULL, NULL, NULL, NULL, '6uHiKoEgQqXUEs2F', 1696819904, 1710916493, 1, '', '', '', 'HERU TRI KURNIADI S', NULL, '1979-10-23', 'NON ENTRY', NULL, NULL, 'admin1234', '', '', '0', '1'),
	(7025, 'Viky_sarebni', 1, '124.158.190.158', '$2y$08$Rj4s135MRLSiXurTP.9ky.QnJJ2b46qiyZezwZdPA.stdVIsBB7gm', '1ECvvY4Xk0Cba9ib', NULL, NULL, NULL, NULL, '0.SN3UUTITq74uot', 1696820066, 1729584989, 1, '', 'S.T.', '', 'AHMAD VIKY FURQON SAREBNI, S.T.', NULL, '1996-07-07', 'Jalan Sukarno-Hatta No.42 50196 Semarang', NULL, NULL, 'Sarebnicaem', '', '', '0', '1'),
	(7029, 'es_ulfie', 1, '124.158.190.158', '$2y$08$5r59x9TVxHZoXN1NEXiHyOZB6vhXkS/xgCsWEJ7qBbAPzrt1TTuKm', 'xtJ8NaSZEEees3xD', NULL, NULL, NULL, NULL, 'HksJcms4/R0AWXwp', 1696820990, 1731497626, 1, '', '', '', 'AGUSTINA ULFIE NIHAYATI', NULL, '1998-08-11', 'FARMASI', NULL, NULL, 'Upik11', '', '', '0', '1'),
	(7031, 'es_lira', 1, '124.158.190.158', '$2y$08$FuYCmpYnVSnxXMecqsTIn.acKm7gFKmA92WhgJ/X22bHyZs3qcOpu', 'TqpSCzEQVXthPpeg', NULL, NULL, NULL, NULL, 'k9E0uxrJez9DSNq/', 1696821070, 1731506035, 1, '', '', '', 'LIRA HANA SYAKIRA, Amd. Farm', NULL, '2001-08-11', 'FARMASI', NULL, NULL, '1108', '', '', '0', '1'),
	(7032, 'es_apoteker', 1, '124.158.190.158', '$2y$08$ULtvrOPqy6CuQs.orm5s4ujuIbN5tJQYcwPW13d1.6EWWltkcY.xK', '6gzvABlZAeOoUphP', NULL, NULL, NULL, NULL, '4yT8zFsjCTRnldn1', 1696821162, 1731462688, 1, '', '', '', 'AKUN APOTEKER', NULL, '2023-10-24', 'APOTEKER', NULL, NULL, 'admin1234', '', '', '0', '1'),
	(7251, 'es_dewi', 1, '124.158.190.158', '$2y$08$SrgoANC8iXIP3ILbDycR/OPNbFrAQpQQ41XtjPqIBqRar1UJh2uxu', '.uubvsD6LKaFcbnb', NULL, NULL, NULL, NULL, 'q8D.G.LnEJFUeSQk', 1697191141, 1731459121, 1, 'Ns.', 'S.Kep', '', 'Ns. DEWI CATUR MEI NINGRUM, S.Kep', NULL, '1995-05-16', 'JL. TANGGUL MAS BARAT X NO 446 RT 10 RW 10 SEMARANG', NULL, NULL, 'perawat16', '', '', '0', '1'),
	(7843, 'tes_lab', 1, '124.158.190.158', '$2y$08$53TQd6Y58OD49HwANJaR2.h8F8p0X1t.LZhvIbOxc6eEws6aZk5v2', '.Z3y3dnpO62sYhbv', NULL, NULL, NULL, NULL, 'IRMKhEhFW0TdYwYM', 1697606438, 1697606480, 1, '', '', '', 'AKUN LAB', NULL, '1940-10-17', '-', NULL, NULL, NULL, '', '', '0', '1'),
	(8510, 'es_manda', 1, '124.158.190.158', '$2y$08$23nFjyOdyXgMdn6a5eRsmOkcb7UhEpBPd1XUwKxtb6RZ5kRs3.nJ.', 'bnOuJXxEzsjAc4K7', NULL, NULL, NULL, NULL, 'ynidye4b9oO1EjVt', 1698039711, 1731460260, 1, '', 'Amd.kes', '', 'MANDA EKA MEYANY, Amd.kes', NULL, '1999-05-25', 'Link. Dluwangan RT. 02 RW 05 Kauman, Blora', NULL, NULL, 'Ekamandam2505', '', '', '0', '1'),
	(10661, 'es_ine', 1, '162.158.162.10', '$2y$08$wEB023xERbF5L0VjuZyue.UHNNCC85BacdzJHDZyxwp4mjEh/sCbm', 'ygRK5lduqFChZwKB', NULL, NULL, NULL, NULL, 'WDxfyyW40GswF0Wk', 1700103916, 1731505924, 1, '', 'A.Md.Kep', '', 'INE NOVA AYU, A.Md.Kep', NULL, '2023-11-16', 'Perawat Rawat Jalan', NULL, NULL, 'admin1234', '', '', '0', '1'),
	(32561, 'es_dr.husna', 1, '162.158.189.158', '$2y$08$z5b78c1a7VpUcTdtMxQTs.c9rszaLpygVKF/rkp9v3DuWsFsmZuum', 'jZ69hKY/ZyFSGh1d', NULL, NULL, NULL, NULL, 'Y7heZOCnAG9O.FA8', 1701401851, 1731075163, 1, 'dr.', '', '', 'dr. HUSNA HAPSARI PUTRI', NULL, '2023-12-01', '-', NULL, NULL, 'admin1234', '', '', '0', '1'),
	(34013, 'es_yanuaradmin', 1, '108.162.226.254', '$2y$08$mDwc2yNdpwMVk3Rnnrx17e3lXf00GYE91Q6xt/o4t8IiHjH7dFp1a', 'ODM7K7JFw9nnh3Tp', NULL, NULL, NULL, NULL, 'LIA1iHii1BsQH/tW', 1703645928, 1720427972, 1, 'dr.', 'M. Kes, Sp.PD, K-Psi, FINASIM', '', 'dr. YANUAR ARDANI, M. Kes, Sp.PD, K-Psi, FINASIM', NULL, '2023-12-27', 'Jl. Wolter Monginsidi No. 40 Pedurungan Semarang', NULL, NULL, 'admin1234', '', '', '0', '1'),
	(36990, 'es_dzulfikar', 1, '162.158.107.87', '$2y$08$yono8G6dCtOf98On4GkC.eKpCmKMYxfXW0IZTEhVYeGi2He1h/jb.', 'v2RSNaxhT1Zkpdse', NULL, NULL, NULL, NULL, '7ifHf4FUjZtzM2LD', 1708269641, 1731414631, 1, '', 'M.Pd', '', 'DZULFIKAR AKBAR, M.Pd', NULL, '1992-05-14', 'Pulo Gebang Permai Blok H5/1', NULL, NULL, 'fikar123', '', '', '0', '1'),
	(44549, 'es_dr.reza', 1, '162.158.189.204', '$2y$08$8KQ8aSeIkJJUU8/IG91jye1gz6eefi3auiY2aNSAAsX.pUiI9ZWEO', 'FcmekXzdRWTqjWBQ', NULL, NULL, NULL, NULL, '2obty2.amtO8aWVr', 1719195376, 1731413338, 1, NULL, NULL, NULL, 'dr. MOHAMAD REZA, Sp.N', NULL, '1983-06-07', '-', NULL, NULL, 'admin1234', '', '', '0', '1'),
	(46165, 'es_dr. Syifa Aulia', 1, '162.158.162.175', '$2y$08$sqQnOWmQAWZ0shVKmAWFmOJQUks6Qf3eq2ZuqLxmGLuFIlArv.BeS', 'jt0tne6FSUIRpZRs', NULL, NULL, NULL, NULL, NULL, 1721238089, NULL, 1, NULL, NULL, NULL, 'dr.  SYIFA AULIA, Sp.Rad', NULL, '2024-07-25', '-', NULL, NULL, NULL, '', '', '0', '1'),
	(48167, 'es_dr.yeni', 1, '162.158.106.205', '$2y$08$gtg1dPioCjigOuckozuNCODsOaPvjestCp92gB9hdxi9COdljZEGS', '8BXQdFfPpMJQvVh7', NULL, NULL, NULL, NULL, NULL, 1724829979, NULL, 1, NULL, NULL, NULL, 'dr YENI JAMILAH , Sp. MK', NULL, '2024-08-28', '-', NULL, NULL, NULL, '', '', '0', '1'),
	(48246, 'es_reni', 1, '162.158.189.183', '$2y$08$n82AfL9CmLyTwoPLuXzUL.N1sBL0wHjQEyYE5pWCHr6czSdcQzV86', 'tRhOtNhw/uVv.Kbn', NULL, NULL, NULL, NULL, NULL, 1724986119, NULL, 1, NULL, NULL, NULL, 'RENI MARDIKA MUNZIRIN S.FTR.,FTR', NULL, '2024-08-30', '-', NULL, NULL, NULL, '', '', '0', '1'),
	(49514, 'es_dr.dwibamas', 1, '172.69.166.29', '$2y$08$WO1EgeK0oK5CXUmVP3rxo.EKgz1hcvE0pPn2XzgwZRv5Exxdqv1ZK', 'rWw1qk/JgYkPq3R.', NULL, NULL, NULL, NULL, NULL, 1726833121, NULL, 1, NULL, NULL, NULL, 'dr.  DWI BAMAS AJI,  Sp. Rad', NULL, '2024-09-27', '-', NULL, NULL, NULL, '', '', '0', '1'),
	(51778, 'es_dr.putri', 1, '162.158.170.17', '$2y$08$6OfDFFaPmRXOOmhhLPKgsOliS6//gzEB.NL1xdZkm7YULjZL.vgy2', 'OApvhcsHEardZSFm', NULL, NULL, NULL, NULL, 'seD6Iw9ovoSAtBr1', 1730507123, 1730790654, 1, NULL, NULL, NULL, 'dr. PUTRI RAINA ,, M.Biomed', NULL, '2024-11-02', 'semarang', NULL, NULL, 'admin1234', '', '', '0', '1'),
	(52494, 'es_sonia', 1, '162.158.163.237', '$2y$08$CAsbFS2xg7zQFrz.wFNgpOQ7ncfYhJG6Q0S76Y/6VGQlrZ5L.hMpi', 'N9j68FORg97kHwc0', NULL, NULL, NULL, NULL, 'a1brF101KJmkJXGv', 1731297817, 1731463899, 1, NULL, NULL, NULL, 'ADE SONIA ROHMAWATI', NULL, '1999-05-07', '-', NULL, NULL, 'admin1234', '', '', '0', '1');

-- Dumping structure for table esensiaco_medkit_dev.tbl_ion_users_groups
DROP TABLE IF EXISTS `tbl_ion_users_groups`;
CREATE TABLE IF NOT EXISTS `tbl_ion_users_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `group_id` mediumint(8) unsigned DEFAULT NULL,
  `access` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  KEY `fk_users_groups_users1_idx` (`user_id`),
  KEY `fk_users_groups_groups1_idx` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=76609 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_ion_users_groups: ~131 rows (approximately)
DELETE FROM `tbl_ion_users_groups`;
INSERT INTO `tbl_ion_users_groups` (`id`, `user_id`, `group_id`, `access`) VALUES
	(76, 1, 1, '[{"id":"1","id_parent":"0","modules":"master","modules_action":"index","modules_name":"Master","modules_route":"master\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"},{"id":"101","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_detail","modules_name":null,"modules_route":null,"modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"0"},{"id":"1","id_parent":"0","modules":"medcheck","modules_action":"trans_medcheck_dft","modules_name":"Pendaftaran","modules_route":"medcheck\\/data_pendaftaran.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"100","id_parent":"99","modules":"medcheck","modules_action":"trans_medcheck","modules_name":"Tambah Checkup","modules_route":"medcheck\\/tambah.php","modules_icon":"fa-plus","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"113","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Rawat Jalan","modules_route":"medcheck\\/index.php?tipe=2","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"99","id_parent":"0","modules":"medcheck","modules_action":"index","modules_name":"Medical Check","modules_route":"medcheck\\/index.php","modules_icon":null,"modules_param":null,"modules_value":null,"is_parent":"1","is_sidebar":"0"},{"id":"114","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Rawat Inap","modules_route":"medcheck\\/index.php?tipe=3","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"114","id_parent":"99","modules":"medcheck","modules_action":"index","modules_name":"Radiologi","modules_route":"medcheck\\/index.php?tipe=4","modules_icon":"fa-list","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"102","id_parent":"99","modules":"medcheck","modules_action":"medcheck_pemb_list","modules_name":"Pembayaran","modules_route":"medcheck\\/data_pemb.php","modules_icon":"fa-shopping-cart","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"},{"id":"103","id_parent":"99","modules":"medcheck","modules_action":"medcheck_batal_list","modules_name":"Medcheck Batal","modules_route":"medcheck\\/data_hapus.php","modules_icon":"fa-trash-alt","modules_param":null,"modules_value":null,"is_parent":"0","is_sidebar":"1"}]'),
	(173, 158, 14, NULL),
	(174, 206, 14, NULL),
	(179, 164, 14, NULL),
	(180, 165, 14, NULL),
	(186, 171, 12, NULL),
	(190, 175, 12, NULL),
	(201, 186, 8, NULL),
	(202, 187, 8, NULL),
	(215, 200, 10, NULL),
	(220, 205, 10, NULL),
	(248, 166, 14, NULL),
	(250, 213, 14, NULL),
	(282, 232, 10, NULL),
	(283, 233, 10, NULL),
	(288, 238, 10, NULL),
	(292, 242, 10, NULL),
	(296, 243, 10, NULL),
	(298, 245, 10, NULL),
	(299, 246, 10, NULL),
	(300, 247, 10, NULL),
	(311, 204, 3, NULL),
	(317, 248, 10, NULL),
	(340, 82, 14, NULL),
	(342, 251, 3, NULL),
	(343, 255, 10, NULL),
	(365, 260, 10, NULL),
	(366, 178, 9, NULL),
	(368, 161, 8, NULL),
	(504, 207, 5, NULL),
	(505, 259, 10, NULL),
	(4947, 156, 8, NULL),
	(5865, 4952, 9, NULL),
	(6109, 5779, 17, NULL),
	(6110, 5780, 16, NULL),
	(7394, 7032, 8, NULL),
	(7403, 183, 8, NULL),
	(8215, 7843, 12, NULL),
	(56378, 32698, 4, NULL),
	(56379, 32697, 4, NULL),
	(56380, 32696, 4, NULL),
	(56381, 32694, 4, NULL),
	(56382, 32693, 4, NULL),
	(56383, 32692, 4, NULL),
	(56384, 32691, 4, NULL),
	(56473, 225, 10, NULL),
	(56556, 531, 5, NULL),
	(56838, 252, 5, NULL),
	(57232, 193, 9, NULL),
	(58124, 10661, 8, NULL),
	(58840, 206, 5, NULL),
	(59505, 170, 12, NULL),
	(59781, 195, 9, NULL),
	(60854, 41, 3, NULL),
	(61472, 216, 13, NULL),
	(62951, 34013, 3, NULL),
	(63186, 151, 8, NULL),
	(63944, 36990, 3, NULL),
	(64027, 81, 3, NULL),
	(64796, 240, 8, NULL),
	(64982, 182, 11, NULL),
	(65279, 222, 7, NULL),
	(65444, 198, 10, NULL),
	(65602, 8510, 12, NULL),
	(65615, 32699, 4, NULL),
	(65623, 211, 5, NULL),
	(65632, 250, 3, NULL),
	(65633, 258, 11, NULL),
	(65678, 256, 8, NULL),
	(65686, 7251, 8, NULL),
	(65977, 169, 12, NULL),
	(65983, 7031, 11, NULL),
	(65985, 215, 8, NULL),
	(65987, 214, 11, NULL),
	(65991, 218, 11, NULL),
	(66029, 159, 14, NULL),
	(66035, 191, 8, NULL),
	(66165, 86, 5, NULL),
	(66581, 197, 11, NULL),
	(66664, 244, 10, NULL),
	(66665, 203, 10, NULL),
	(66666, 199, 10, NULL),
	(66668, 241, 10, NULL),
	(66669, 202, 10, NULL),
	(66670, 220, 10, NULL),
	(66672, 221, 10, NULL),
	(66673, 224, 10, NULL),
	(66674, 229, 10, NULL),
	(66675, 226, 10, NULL),
	(66677, 249, 10, NULL),
	(66678, 231, 10, NULL),
	(66681, 228, 10, NULL),
	(66748, 172, 5, NULL),
	(66862, 201, 10, NULL),
	(66923, 237, 10, NULL),
	(67519, 219, 10, NULL),
	(67683, 210, 5, NULL),
	(67707, 32561, 10, NULL),
	(68035, 227, 10, NULL),
	(68168, 234, 10, NULL),
	(68289, 190, 13, NULL),
	(68540, 44549, 10, NULL),
	(68698, 160, 8, NULL),
	(69110, 152, 8, NULL),
	(69233, 6506, 5, NULL),
	(69294, 2269, 11, NULL),
	(69799, 177, 11, NULL),
	(69800, 217, 5, NULL),
	(70055, 179, 9, NULL),
	(71784, 155, 8, NULL),
	(71912, 192, 5, NULL),
	(72195, 48167, 10, NULL),
	(72220, 168, 12, NULL),
	(72224, 7029, 5, NULL),
	(72276, 48246, 10, NULL),
	(72991, 167, 12, NULL),
	(73643, 184, 8, NULL),
	(73674, 209, 5, NULL),
	(73769, 235, 10, NULL),
	(74180, 196, 9, NULL),
	(74250, 163, 14, NULL),
	(74548, 162, 14, NULL),
	(74647, 223, 10, NULL),
	(74819, 49514, 10, NULL),
	(75316, 153, 8, NULL),
	(75360, 46165, 10, NULL),
	(75881, 236, 10, NULL),
	(75882, 51778, 10, NULL),
	(76313, 185, 8, NULL),
	(76557, 157, 8, NULL),
	(76608, 52494, 5, NULL);

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_aksi
DROP TABLE IF EXISTS `tbl_m_aksi`;
CREATE TABLE IF NOT EXISTS `tbl_m_aksi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipe` int(11) NOT NULL DEFAULT 0,
  `kode` int(11) DEFAULT 0,
  `nama` varchar(50) DEFAULT NULL,
  `link` varchar(50) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `status` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_aksi: ~1 rows (approximately)
DELETE FROM `tbl_m_aksi`;
INSERT INTO `tbl_m_aksi` (`id`, `id_tipe`, `kode`, `nama`, `link`, `icon`, `status`) VALUES
	(1, 0, 1, 'Periksa', 'medcheck/tambah.php?id=', 'fa-stethoscope', '1');

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_ass_pny
DROP TABLE IF EXISTS `tbl_m_ass_pny`;
CREATE TABLE IF NOT EXISTS `tbl_m_ass_pny` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT 41,
  `tgl_simpan` datetime DEFAULT current_timestamp(),
  `kode` varchar(50) DEFAULT NULL,
  `penyakit` varchar(160) DEFAULT NULL,
  `satuan` varchar(160) DEFAULT NULL,
  `nilai` varchar(160) DEFAULT NULL,
  `status` enum('0','1') DEFAULT '1',
  `tipe` int(11) DEFAULT 16,
  `status_posisi` enum('N','L','R') DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel data asesment berisi tentang data nama penyakit';

-- Dumping data for table esensiaco_medkit_dev.tbl_m_ass_pny: ~0 rows (approximately)
DELETE FROM `tbl_m_ass_pny`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_departemen
DROP TABLE IF EXISTS `tbl_m_departemen`;
CREATE TABLE IF NOT EXISTS `tbl_m_departemen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `kode` varchar(50) DEFAULT NULL,
  `dept` varchar(160) DEFAULT NULL,
  `keterangan` varchar(160) DEFAULT NULL,
  `status` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Untuk menyimpan data departemen / divisi';

-- Dumping data for table esensiaco_medkit_dev.tbl_m_departemen: ~0 rows (approximately)
DELETE FROM `tbl_m_departemen`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_gelar
DROP TABLE IF EXISTS `tbl_m_gelar`;
CREATE TABLE IF NOT EXISTS `tbl_m_gelar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gelar` varchar(50) DEFAULT NULL,
  `ket` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_gelar: ~0 rows (approximately)
DELETE FROM `tbl_m_gelar`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_gudang
DROP TABLE IF EXISTS `tbl_m_gudang`;
CREATE TABLE IF NOT EXISTS `tbl_m_gudang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl_simpan` datetime DEFAULT NULL,
  `tgl_modif` datetime DEFAULT NULL,
  `kode` varchar(160) DEFAULT NULL,
  `gudang` varchar(160) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('0','1','2','3') DEFAULT NULL COMMENT '1 = Gudang aktif\r\n2 = Gudang Bazar (tertentu)\r\n0 = Gudang simpan (stok)\r\n3 = Gudang Brg Keluar / Pinjam / dll',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_gudang: ~0 rows (approximately)
DELETE FROM `tbl_m_gudang`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_icd
DROP TABLE IF EXISTS `tbl_m_icd`;
CREATE TABLE IF NOT EXISTS `tbl_m_icd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl_simpan` datetime NOT NULL DEFAULT current_timestamp(),
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `kode` varchar(100) DEFAULT NULL,
  `icd` text DEFAULT NULL,
  `diagnosa_en` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Untuk menyimpan data tentang ICD 10 (Daftar Penyakit).\r\nsesuai satu sehat';

-- Dumping data for table esensiaco_medkit_dev.tbl_m_icd: ~0 rows (approximately)
DELETE FROM `tbl_m_icd`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_jabatan
DROP TABLE IF EXISTS `tbl_m_jabatan`;
CREATE TABLE IF NOT EXISTS `tbl_m_jabatan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `kode` varchar(50) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Untuk menyimpan data jabatan';

-- Dumping data for table esensiaco_medkit_dev.tbl_m_jabatan: ~0 rows (approximately)
DELETE FROM `tbl_m_jabatan`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_jenis_kerja
DROP TABLE IF EXISTS `tbl_m_jenis_kerja`;
CREATE TABLE IF NOT EXISTS `tbl_m_jenis_kerja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jenis` varchar(50) DEFAULT NULL,
  `ket` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_jenis_kerja: ~0 rows (approximately)
DELETE FROM `tbl_m_jenis_kerja`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_kamar
DROP TABLE IF EXISTS `tbl_m_kamar`;
CREATE TABLE IF NOT EXISTS `tbl_m_kamar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT current_timestamp(),
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `kode` varchar(50) DEFAULT NULL,
  `kamar` varchar(50) DEFAULT NULL,
  `tipe` varchar(50) DEFAULT NULL,
  `jml` int(11) DEFAULT 0,
  `jml_max` int(11) DEFAULT 0,
  `style` varchar(50) DEFAULT NULL,
  `status` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Untuk Menyimpan data kamar';

-- Dumping data for table esensiaco_medkit_dev.tbl_m_kamar: ~0 rows (approximately)
DELETE FROM `tbl_m_kamar`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_karyawan
DROP TABLE IF EXISTS `tbl_m_karyawan`;
CREATE TABLE IF NOT EXISTS `tbl_m_karyawan` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `id_app` int(4) DEFAULT 0,
  `id_user` int(11) unsigned NOT NULL,
  `id_poli` int(4) DEFAULT 0,
  `id_user_group` int(4) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `kategori` varchar(10) DEFAULT NULL,
  `kode` varchar(10) DEFAULT NULL,
  `nik` varchar(100) DEFAULT NULL,
  `sip` varchar(100) DEFAULT NULL,
  `str` varchar(100) DEFAULT NULL,
  `no_ijin` varchar(100) DEFAULT NULL,
  `tgl_lahir` date DEFAULT '0000-00-00',
  `tmp_lahir` varchar(100) DEFAULT NULL,
  `nama_dpn` varchar(100) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `nama_blk` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `alamat_dom` text DEFAULT NULL,
  `jabatan` varchar(160) DEFAULT NULL,
  `kota` varchar(50) DEFAULT NULL,
  `jns_klm` enum('L','P','') DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `no_rmh` varchar(160) DEFAULT NULL,
  `file_name` varchar(160) DEFAULT NULL,
  `file_ext` varchar(10) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '1=perawat\\r\\n2=dokter\\r\\n3=kasir\\r\\n4=analis\\r\\n5=radiografer\\r\\n6=farmasi',
  `status_aps` int(11) DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=294 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_karyawan: ~149 rows (approximately)
DELETE FROM `tbl_m_karyawan`;
INSERT INTO `tbl_m_karyawan` (`id`, `id_app`, `id_user`, `id_poli`, `id_user_group`, `tgl_simpan`, `tgl_modif`, `kategori`, `kode`, `nik`, `sip`, `str`, `no_ijin`, `tgl_lahir`, `tmp_lahir`, `nama_dpn`, `nama`, `nama_blk`, `alamat`, `alamat_dom`, `jabatan`, `kota`, `jns_klm`, `no_hp`, `no_rmh`, `file_name`, `file_ext`, `file_type`, `status`, `status_aps`) VALUES
	(1, 0, 41, 0, 3, '2022-12-22 22:28:13', '2024-02-20 09:50:54', NULL, NULL, '-', NULL, NULL, NULL, '1990-02-15', 'Semarang', '', 'MIKHAEL FELIAN WASKITO', '', 'Pemilik Perusahaan', '-', '', 'Semarang', 'L', '085741220427', '', NULL, NULL, NULL, 0, 0),
	(2, 0, 81, 0, 3, '2022-12-22 22:28:13', '2024-04-05 14:48:31', NULL, NULL, '3315174510950001', NULL, NULL, NULL, '1995-10-05', 'GROBOGAN', '', 'AYUNDA AMALIA', 'S.Kep', 'DUSUN KRAJAN RT 003 RW 002 DESA KALIWENANG TANGGUNGHARJO', '', 'Oprasional', 'Semarang', 'P', '085712456028', '', NULL, NULL, NULL, 0, 0),
	(3, 0, 82, 0, 14, '2022-12-22 22:28:13', '2023-02-17 08:22:41', NULL, NULL, '-', NULL, NULL, NULL, '0000-00-00', NULL, 'Ns.', 'YULI FAEDAH', 'A.Md.Kep', 'Perawat Rawat Inap', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(4, 0, 86, 0, 5, '2022-12-22 22:28:13', '2024-05-10 14:21:42', NULL, NULL, '3374066804950003', NULL, NULL, NULL, '1995-04-28', '', '', 'RIDHA WIDHININGTYAS', 'A.Md.Kep', 'Jl. Taman Tlogomulyo No 51 Pedurungan ', '', '', 'Semarang', 'P', '087832230782', '', NULL, NULL, NULL, 0, 0),
	(5, 0, 10661, 0, 8, '2022-12-22 22:28:13', '2024-01-03 19:38:15', NULL, NULL, '-', NULL, NULL, NULL, '2023-11-16', 'SEMARANG', '', 'INE NOVA AYU', 'A.Md.Kep', 'Perawat Rawat Jalan', '-', 'PERAWAT', 'Semarang', 'L', '-', '', NULL, NULL, NULL, 0, 0),
	(6, 0, 151, 0, 8, '2022-12-22 22:28:13', '2024-03-23 14:28:36', NULL, NULL, '-', NULL, NULL, NULL, '1993-08-11', 'semarang', '', 'YOESTIKA SARI', 'A.Md.Kep', 'JL. MERPATI II / 21 RT 007 RW 009 PEDURUNGAN TENGAH ', 'JL. MERPATI II / 21 RT 007 RW 009 PEDURUNGAN TENGAH ', 'Perawat ', 'Semarang', 'P', '085641686941', '', NULL, NULL, NULL, 0, 0),
	(7, 0, 152, 0, 8, '2022-12-22 22:28:13', '2024-07-02 19:15:30', NULL, NULL, '-', NULL, NULL, NULL, '1997-11-14', 'Demak', '', 'SRI LESTARI', 'A.Md.Kep', 'Turus Bumirejo Karangawen Demak', '', 'perawat', 'Semarang', 'P', '083842749546', '', NULL, NULL, NULL, 0, 0),
	(8, 0, 153, 0, 8, '2022-12-22 22:28:13', '2024-10-21 21:21:14', NULL, NULL, '-', NULL, NULL, NULL, '1994-10-18', '-', 'Ns.', 'PUTRI MIRANTI', 'S.Kep', 'Perawat Rawat Jalan', '-', '', 'Semarang', 'P', '-', '', NULL, NULL, NULL, 0, 0),
	(9, 0, 7251, 0, 8, '2022-12-22 22:28:13', '2024-04-30 15:06:18', NULL, NULL, '3374025605950001', NULL, NULL, NULL, '1995-05-16', 'SEMARANG', 'Ns.', 'DEWI CATUR MEI NINGRUM', 'S.Kep', 'JL. TANGGUL MAS BARAT X NO 446 RT 10 RW 10 SEMARANG', '-', '-', 'Semarang', 'P', '089696025238', '', NULL, NULL, NULL, 0, 0),
	(10, 0, 155, 0, 8, '2022-12-22 22:28:13', '2024-08-19 16:37:17', NULL, NULL, '-', NULL, NULL, NULL, '1996-07-12', 'Semarang ', 'Ns.', 'IKA PRATIWI', 'S.Kep', 'Perawat Rawat Jalan', '', 'Perawat ', 'Semarang', 'P', '08180749879', '', NULL, NULL, NULL, 0, 0),
	(11, 0, 156, 0, 8, '2022-12-22 22:28:13', '0000-00-00 00:00:00', NULL, NULL, '-', NULL, NULL, NULL, '0000-00-00', NULL, 'Ns.', 'DANI FITRISARI', 'S.Kep', 'Perawat Rawat Jalan', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(12, 0, 157, 0, 8, '2022-12-22 22:28:13', '2024-11-10 08:04:42', NULL, NULL, '3374134601860005', NULL, NULL, NULL, '1986-01-06', 'Semarang', '', 'HERVINA NUR LAILA', 'A.Md. Kep.', 'Jl. Banget Prasetya II no.49 RT 08/RW 06 Kel. Bangetayu Kulon, Kecamatan Genuk, Kota Semarang', 'Jl. Banget Prasetya II no.49 RT 08/RW 06 Kel. Bangetayu Kulon, Kecamatan Genuk, Kota Semarang', 'perawat', 'Semarang', 'P', '082236078585', '', NULL, NULL, NULL, 0, 0),
	(13, 0, 158, 0, 14, '2022-12-22 22:28:13', '0000-00-00 00:00:00', NULL, NULL, '-', NULL, NULL, NULL, '0000-00-00', NULL, 'Ns.', 'TITUT FERRA SIAMI', 'S.Kep', 'Perawat Rawat Inap', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(14, 0, 159, 0, 14, '2022-12-22 22:28:13', '2024-05-07 21:29:14', NULL, NULL, '3402105603860002', NULL, NULL, NULL, '1986-03-16', 'BANTUL', 'Ns.', 'SULASTRI', 'S.Kep', 'PERUMAHAN MUTIARA SEBAKUNG BLOK B NO 4 RT 09/ RW 06 BANGETAYU KULON GENUK SEMARANG', 'PERUMAHAN MUTIARA SEBAKUNG BLOK B NO 4 RT 09/ RW 06 BANGETAYU KULON GENUK SEMARANG', 'PERAWAT RAWAT INAP', 'Semarang', 'P', '087863090002', '', NULL, NULL, NULL, 0, 0),
	(15, 0, 160, 0, 8, '2022-12-22 22:28:13', '2024-06-26 16:06:28', NULL, NULL, '-', '', '', NULL, '1998-03-11', 'DEMAK', '', 'INTAN TRI UTAMI', 'A.Md.Kep', 'JAMUS GERJEN RT 03 RW 02 MRANGGEN DEMAK', 'JAMUS GERJEN RT 03 RW 02 MRANGGEN DEMAK', 'PERAWAT', 'Semarang', 'P', '-', '(0', NULL, NULL, NULL, 0, 0),
	(16, 0, 161, 0, 14, '2022-12-22 22:28:13', '2023-07-11 14:15:11', NULL, NULL, '-', NULL, NULL, NULL, '0000-00-00', NULL, 'Ns.', 'NS. NUR HANDAYANI, S.KEP', 'S.Kep', 'Perawat Rawat Inap', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(17, 0, 162, 0, 14, '2022-12-22 22:28:13', '2024-10-07 10:42:23', NULL, NULL, '-', NULL, NULL, NULL, '1992-01-27', 'Semarang', 'Ns.', 'DEVI ARIYANTI', 'S.Kep', 'Perawat Rawat Inap', '', '', 'Semarang', 'P', '089523256148', '', NULL, NULL, NULL, 0, 0),
	(18, 0, 163, 0, 14, '2022-12-22 22:28:13', '2024-10-01 23:42:16', NULL, NULL, '3374046808960003', NULL, NULL, NULL, '1996-08-28', 'SEMARANG', 'Ns.', 'RITA KURNIASIH', 'S.Kep', 'JL. GAJAH TIMUR DALAM IV NO. 7 RT 1 RW 9 , KEL. GAYAMSARI, KEC. GAYAMSARI, SEMARANG', 'JL. GAJAH TIMUR DALAM IV NO. 7 RT 1 RW 9 , KEL. GAYAMSARI, KEC. GAYAMSARI, SEMARANG', 'PERAWAT', 'Semarang', 'P', '089672029735', '', NULL, NULL, NULL, 0, 0),
	(19, 0, 164, 0, 14, '2022-12-22 22:28:13', '0000-00-00 00:00:00', NULL, NULL, '-', NULL, NULL, NULL, '0000-00-00', NULL, 'Ns.', 'WINARSIH', 'S.Kep', 'Perawat Rawat Inap', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(20, 0, 165, 0, 14, '2022-12-22 22:28:13', '0000-00-00 00:00:00', NULL, NULL, '-', NULL, NULL, NULL, '0000-00-00', NULL, 'Ns.', 'NOVI PRAMESTY PUTRI A', 'S.Kep', 'Perawat Rawat Inap', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(21, 0, 166, 0, 14, '2022-12-22 22:28:13', '0000-00-00 00:00:00', NULL, NULL, '-', NULL, NULL, NULL, '0000-00-00', NULL, 'Ns.', 'TRIE BUANA NOVITASARI', '', 'Perawat Rawat Inap', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(22, 0, 167, 0, 12, '2022-12-22 22:28:13', '2024-09-13 10:08:26', NULL, NULL, '3316115304940001', NULL, NULL, NULL, '1994-04-13', 'Blora', '', 'MAFRUKHA ARBI ZULPRILIANA', 'A.Md', 'Perumahan Sembungharjo Permai Blok C No.5 RT 05 RW 08 Kel.Sembungharjo, Kec.Genuk, Kota Semarang', 'Perumahan Sembungharjo Permai Jl. Palem Raya Blok C No.5 RT 05 RW 08 Kel.Sembungharjo, Kec.Genuk, Kota Semarang', 'Analis / Lab/ ATLM', 'Semarang', 'P', '087833344631', '', NULL, NULL, NULL, 0, 0),
	(23, 0, 168, 0, 12, '2022-12-22 22:28:13', '2024-08-29 09:10:35', NULL, NULL, '3374037108870001', NULL, NULL, NULL, '1987-08-31', 'SEMARANG', '', 'SRI WAHYU BUDI UTAMI', 'A.Md. A.k', 'JL. BANGET PRASETYA IV NO.178 RT 02 RW 06 BANGETAYU KULON, GENUK, KOTA SEMARANG', '', 'ATLM', 'Semarang', 'P', '087731340622', '', NULL, NULL, NULL, 0, 0),
	(24, 0, 169, 0, 12, '2022-12-22 22:28:13', '2024-05-07 13:37:11', NULL, NULL, '6111036201970001', NULL, NULL, NULL, '1997-01-22', 'kETAPANG', '', 'DIAH AYU KARTIKA', 'A.Md. A.k', 'JL. PUCANG SANTOSO TENGAH V NO.36 RT 12 RW 30 BATURSARI MRANGGEN', 'IVORY PARK BLOK N NO.3 BATURSARI MRANGGEN', '', 'Semarang', 'P', '087834999306', '', NULL, NULL, NULL, 0, 0),
	(25, 0, 170, 0, 12, '2022-12-22 22:28:13', '2024-01-27 12:51:12', NULL, NULL, '-', NULL, NULL, NULL, '1996-08-30', 'Semarang', '', 'FARIDHATUL HIDAYAH', 'A.Md. A.k', 'Analis /  Lab', 'Kudu Rt 4 Rw 7', 'Analis', 'Semarang', 'P', '0895326921580', '', NULL, NULL, NULL, 0, 0),
	(26, 0, 171, 0, 12, '2022-12-22 22:28:13', '0000-00-00 00:00:00', NULL, NULL, '-', NULL, NULL, NULL, '0000-00-00', NULL, '', 'EKO WAHYU BINTARI', 'S.Tr.A.k', 'Analis /  Lab', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(27, 0, 172, 0, 5, '2022-12-22 22:28:13', '2024-05-21 08:47:59', NULL, NULL, '3315096306980001', '', '', NULL, '1998-06-23', 'Grobogan ', '', 'SITI AMINAH ', '', 'Dsn. Krajan II, Dsa Kalanglundo, Kec Ngaringan Kabupaten Grobogan ', 'Dsn. Krajan II, Dsa Kalanglundo, Kec Ngaringan Kabupaten Grobogan ', 'DIII Analis Kesehatan ', 'Semarang', 'P', '082242675695', '(', NULL, NULL, NULL, 0, 0),
	(28, 0, 8510, 0, 12, '2022-12-22 22:28:13', '2024-04-28 22:03:19', NULL, NULL, '3316116505990003', NULL, NULL, NULL, '1999-05-25', 'Blora', '', 'MANDA EKA MEYANY', 'Amd.kes', 'Link. Dluwangan RT. 02 RW 05 Kauman, Blora', 'Mess', 'Analis Kesehatan', 'Semarang', 'P', '081215877788', '', NULL, NULL, NULL, 0, 0),
	(30, 0, 175, 0, 12, '2022-12-22 22:28:13', '0000-00-00 00:00:00', NULL, NULL, '-', NULL, NULL, NULL, '0000-00-00', NULL, '', 'DEWI HERINDA HAPSARI', 'Amd.kes', 'Analis /  Lab', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(32, 0, 177, 0, 11, '2022-12-22 22:28:13', '2024-07-13 09:18:32', NULL, NULL, '-', '', '', NULL, '1990-09-19', 'Pati', 'apt.', 'ARIK DIAN EKA PRATIWI', ' M.Si', 'Dusun Melian RT 2 RW 1\r\nDesa Kropak Kecamatan Winong\r\nKabupaten Pati - Jawa Tengah\r\n59181', 'Jl. Arya Mukti Raya No K25\r\nPedurungan\r\nKota Semarang\r\n50192', 'Apoteker Penanggung Jawab', 'Semarang', 'P', '085225553563', '', NULL, NULL, NULL, 0, 0),
	(33, 0, 178, 0, 11, '2022-12-22 22:28:13', '2023-07-04 15:45:46', NULL, NULL, '-', NULL, NULL, NULL, '0000-00-00', NULL, '', 'AFRISA DESY KHOIRIDA', '', 'Farmasi', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(34, 0, 179, 0, 9, '2022-12-22 22:28:13', '2024-07-17 14:26:52', NULL, NULL, '3321014608000005', NULL, NULL, NULL, '2000-08-06', 'DEMAK', '', 'RIDA YATUL ARI FADLINA', 'S. Farm', 'KAYON RT 6/ RW 2 BATURSARI, MRANGGEN DEMAK', 'KAYON RT 6/ RW 2 BATURSARI, MRANGGEN DEMAK', 'KASIR', 'Semarang', 'P', '089512212306', '', NULL, NULL, NULL, 0, 0),
	(35, 0, 2269, 0, 11, '2022-12-22 22:28:13', '2024-07-06 16:28:55', NULL, NULL, '-', '', '', NULL, '1999-06-01', 'Demak', '', 'ANNISA NANDA SAFIRA', '', 'Sidokumpul 04/01 kec.Guntur Kab.Demak', 'Sidokumpul 04/01 kec.Guntur Kab.Demak', 'Asisten apoteker', 'Semarang', 'P', '082138209541', '(10', NULL, NULL, NULL, 0, 0),
	(36, 0, 182, 0, 11, '2022-12-22 22:28:13', '2024-04-20 02:23:45', NULL, NULL, '3315175112970002', NULL, NULL, NULL, '1997-12-11', 'GROBOGAN', '', 'FIRA SILVI ARIFA', 'Amd.farm', 'Farmasi', 'DUSUN PILANG KIDUL RT 002 RW 005, DESA GUBUG, KEC. GUBUG, KAB. GROBOGAN', 'TENAGA TEKNIS KEFARMASIAN', 'Semarang', 'P', '08984275922', '(', NULL, NULL, NULL, 0, 0),
	(37, 0, 183, 0, 8, '2022-12-22 22:28:13', '2023-10-09 14:41:08', NULL, NULL, '-', NULL, NULL, NULL, '1995-09-07', 'KENDAL', '', 'RINI SUSILOWATI', 'Amd. Keb', 'Perawat Rawat Jalan', '-', '-', 'Semarang', 'P', '', '', NULL, NULL, NULL, 0, 0),
	(38, 0, 184, 0, 8, '2022-12-22 22:28:13', '2024-09-22 15:41:04', NULL, NULL, '-', NULL, NULL, NULL, '1997-08-12', 'TEMANGGUNG', '', 'BELLA SINTIYA', 'Amd. Keb', 'Perawat Rawat Jalan', '', 'BIDAN', 'Semarang', 'P', '-', '', NULL, NULL, NULL, 0, 0),
	(39, 0, 185, 0, 8, '2022-12-22 22:28:13', '2024-11-05 15:52:38', NULL, NULL, '-', NULL, NULL, NULL, '1996-12-06', 'Temanggung', '', 'NUR AULA', 'Amd. Keb', 'Banjarsari RT.002 RW.001 Kandangan\r\nKabupaten Temanggung', 'Semarang', 'Bidan', 'Semarang', 'P', '085766629963', '', NULL, NULL, NULL, 0, 0),
	(40, 0, 186, 0, 8, '2022-12-22 22:28:13', '0000-00-00 00:00:00', NULL, NULL, '-', NULL, NULL, NULL, '0000-00-00', NULL, '', 'IKA KHUSNUL P', 'S.Tr.Keb', 'Perawat Rawat Jalan', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(41, 0, 187, 0, 8, '2022-12-22 22:28:13', '0000-00-00 00:00:00', NULL, NULL, '-', NULL, NULL, NULL, '0000-00-00', NULL, '', 'WINA ASTARI', 'Amd.keb', 'Perawat Rawat Jalan', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(43, 0, 190, 0, 13, '2022-12-22 22:28:13', '2024-06-19 09:30:46', NULL, NULL, '-', NULL, NULL, NULL, '1998-10-29', 'PURBALINGGA', '', 'ELIYAS ALVIA IMERZA PRAMESWARI', 'S.Tr.Kes (Rad)', 'TLAHAB KIDUL RT.001/RW.007, KEC. KARANGREJA, KAB.PURBALINGGA', '-', 'Radiografer', 'Semarang', 'P', '087738825991', '', NULL, NULL, NULL, 0, 0),
	(44, 0, 191, 0, 8, '2022-12-22 22:28:13', '2024-05-07 22:46:59', NULL, NULL, '3309194405930002', NULL, NULL, NULL, '1993-05-04', 'Boyolali', '', 'RISA AL JANAH', 'Amd. Keb', 'Candisari 003/004 Candisari Mranggen Demak', 'Candisari 003/004 Candisari Mranggen Demak', '', 'Semarang', 'P', '0895336728978', '', NULL, NULL, NULL, 0, 0),
	(45, 0, 192, 0, 5, '2022-12-22 22:28:13', '2024-08-22 09:02:06', NULL, NULL, '3374066001010001', '', '', NULL, '2001-01-20', 'SEMARANG', '', 'NOVIA IKA WULANDARI', '', 'JL. Taman Tlogomulyo Selatan V gg 6 (Raflesia) No. 12 Rt 04 Rw 06 Pedurungan Tengah Semarang', 'JL. Taman Tlogomulyo Selatan V gg 6 (Raflesia) No. 12 Rt 04 Rw 06 Pedurungan Tengah Semarang', 'Admin', 'Semarang', 'P', '089504336070', '', NULL, NULL, NULL, 0, 0),
	(46, 0, 193, 0, 9, '2022-12-22 22:28:13', '2023-12-19 08:49:47', NULL, NULL, '3374066406960005', NULL, NULL, NULL, '1996-06-24', 'SEMARANG', 'NY', 'JUNITA PUTRI SONITA', '', 'JL. JATEN IV NO 19 RT 002 / 008 PEDURUNGAN TENGAH', 'JL. JATEN IV NO 19 RT 002 / 008 PEDURUNGAN TENGAH', 'KASIR', 'Semarang', 'P', '085925358388', '', NULL, NULL, NULL, 0, 0),
	(48, 0, 195, 0, 9, '2022-12-22 22:28:13', '2023-02-10 15:39:20', NULL, NULL, '-', NULL, NULL, NULL, '0000-00-00', NULL, '', 'INDRA AYU', '', 'Kasir', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(49, 0, 196, 0, 9, '2022-12-22 22:28:13', '2024-09-30 11:42:36', NULL, NULL, '3321015711950009', '', '', NULL, '1995-11-17', 'SEMARANG', '', 'NARULITA NOOR WIDYA', 'S.E', 'JL PUCANG ASRI III/ 44 RT 001 RW 012 BATURSARI, MRANGGEN, DEMAK, JAWA TENGAH ', 'JL PUCANG ASRI III/ 44 RT 001 RW 012 BATURSARI, MRANGGEN, DEMAK, JAWA TENGAH ', 'ADMIN ', 'Semarang', 'P', '085641890092', '', NULL, NULL, NULL, 0, 0),
	(50, 0, 197, 0, 11, '2022-12-22 22:28:13', '2024-05-18 09:41:56', NULL, NULL, '3374065606990001', '', '', NULL, '1999-06-16', 'Semarang', '', 'MAULIDA LAILYRAHMAH', '', 'JL. TLOGOSARI WETAN RT. 3 RW. 2 TLOGOSARI WETAN, PEDURUNGAN SEMARANG', 'JL. SYUHADA TIMUR III RT. 3 RW. 2 TLOGOSARI WETAN, PEDURUNGAN SEMARANG', '', 'Semarang', 'P', '0895604786419', '', NULL, NULL, NULL, 0, 0),
	(51, 0, 198, 0, 10, '2022-12-22 22:28:13', '2024-04-26 09:26:07', NULL, NULL, '3374061701890001', '33747.50192/DS.1187/02/449.1/085/III/2020', '', NULL, '1989-01-17', 'Semarang', 'dr.', 'YANUAR ARDANI', 'M. Kes, Sp.PD, K-Psi, FINASIM', 'Jl.Wolter Monginsidi No.40 , RT 001 RW 006, Pedurungan Tengah', 'Jl. Taman Tlogomulyo Selatan 1 No 10 RT 03 RW 06 Pedurungan Tengah', 'Direktur Utama', 'Semarang', 'L', '0811296486', '(10', NULL, NULL, NULL, 0, 0),
	(52, 0, 199, 0, 10, '2022-12-22 22:28:13', '2024-05-20 01:43:01', NULL, NULL, '3374064112820004', '33747.50192/DS.1161/03/449.1/014/III/2020', '', NULL, '1982-12-01', 'Semarang', 'dr.', 'AMALLIA NUGGETSIANA', 'M.Si.Med.,Sp.A', 'Jl. mahesa timur No. 360 Rt 07 rw 03 pedurungan tengah semarang', 'Jl. mahesa timur No. 360 Rt 07 rw 03 pedurungan tengah semarang', 'Dokter spesialis anak', 'Semarang', 'P', '081326294457', '(360', NULL, NULL, NULL, 0, 0),
	(53, 0, 200, 0, 10, '2022-12-22 22:28:13', '0000-00-00 00:00:00', NULL, NULL, '-', '-', NULL, NULL, '0000-00-00', NULL, 'dr.', 'ERIEN AFRINIA ASRI', 'Sp.DV', 'Dokter Jaga', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(54, 0, 201, 0, 10, '2022-12-22 22:28:13', '2024-05-23 09:32:13', NULL, NULL, '3319025709870002', '33747.50192/DS.1086/02/449.1/264/VIII2023', '', NULL, '1987-09-17', 'KUDUS', 'dr.', 'MIFTAKHUL JANNAH KURNIAWATI', 'Sp.RAD', 'Jl. bulu stalan II/297 Rt 02 Rw 03 Kec. Bulu Semarang', 'Jl. bulu stalan II/297 Rt 02 Rw 03 Kec. Bulu Semarang', 'DOKTER SPESIALIS RADIOLOGI', 'Semarang', 'P', '', '', NULL, NULL, NULL, 0, 0),
	(55, 0, 202, 0, 10, '2022-12-22 22:28:13', '2024-05-20 01:49:44', NULL, NULL, '3374061510840002', '33747.50192/DS.890/03/449.1/164/VXI/2020', '-', NULL, '1984-10-15', 'Semarang', 'dr.', 'RAHMAD RIZAL BUDI W', 'Sp.OG (K)', 'Jl. mahesa raya  No 8 Rt 02 rw 03 pedurungan tengah semarang', 'Jl. mahesa raya  No 8 Rt 02 rw 03 pedurungan tengah semarang', 'Dokter spesialis obgyn konsultan', 'Semarang', 'L', '085726830340', '', NULL, NULL, NULL, 0, 0),
	(56, 0, 203, 0, 10, '2022-12-22 22:28:13', '2024-05-20 01:39:51', NULL, NULL, '3320066712600001', '33747.50192/DS.806/02/449.1/376/XII/2021', '', NULL, '1960-12-27', 'yogyakarta', 'dr.', 'NOOR HIDAYATI', 'Sp.A', 'Jl. beruang Raya Rt 005 rw 002, Kel. Gayamsari semarang', 'Jl. beruang Raya Rt 005 rw 002, Kel. Gayamsari semarang', 'Dokter spesialis anak', 'Semarang', 'P', '082242187492', '(0', NULL, NULL, NULL, 0, 0),
	(57, 0, 204, 0, 10, '2022-12-22 22:28:13', '2023-04-20 19:31:29', NULL, NULL, '-', '-', NULL, NULL, '0000-00-00', NULL, 'dr.', 'INGGA IFADA', '', 'Dokter', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(58, 0, 205, 0, 10, '2022-12-22 22:28:13', '0000-00-00 00:00:00', NULL, NULL, '-', '-', NULL, NULL, '0000-00-00', NULL, 'drg.', 'NELLA KEUMALA H', '', 'Dokter Jaga', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(59, 0, 206, 0, 5, '2022-12-22 22:28:13', '2024-01-16 08:19:44', NULL, NULL, '-', NULL, NULL, NULL, '1993-02-04', 'Semarang', '', 'NUR LAILATUL FAIZAH', 'S.M', 'Jl. Kebon Rejo Barat 9/1 RT 8 RW 14 Mranggen Demak', 'Jl. Kebon Rejo Barat 9/1 RT 8 RW 14 Mranggen Demak', 'Bendahara Umum', 'Semarang', 'P', '-', '', NULL, NULL, NULL, 0, 0),
	(60, 0, 531, 0, 5, '2022-12-22 22:28:13', '2023-12-07 03:37:12', NULL, NULL, '-', NULL, NULL, NULL, '1994-08-13', 'Semarang', '', 'VIVIN AGUSTIANI', 'S.Farm', 'Jl. Sidorejo no. 9 RT 007 RW 007 Kelurahan Sambirejo Kecamatan Gayamsari Kota Semarang', 'Jl. Sidorejo no. 9 RT 007 RW 007 Kelurahan Sambirejo Kecamatan Gayamsari Kota Semarang', 'Admin Penerimaan', 'Semarang', 'P', '089622601394', '(9', NULL, NULL, NULL, 0, 0),
	(62, 0, 209, 0, 5, '2022-12-22 22:28:13', '2024-09-23 13:36:44', NULL, NULL, '3374065402970002', '', '', NULL, '1997-02-14', 'Semarang', '', 'FITRI PUJIANTI', 'S.E.', 'Jl MERPATI III ', 'Jl MERPATI III ', 'Staff Administrasi', 'Semarang', 'P', '085726468728', '(17', NULL, NULL, NULL, 0, 0),
	(63, 0, 210, 0, 5, '2022-12-22 22:28:13', '2024-06-06 17:03:23', NULL, NULL, '-', '', '', NULL, '1999-11-03', 'semarang', '', 'DINDA AYU WANDHIRA', '', 'Staff Admin', 'supriyadi', '', 'Semarang', 'P', '085600295983', '', NULL, NULL, NULL, 0, 0),
	(64, 0, 211, 0, 5, '2022-12-22 22:28:13', '2024-04-29 10:58:18', NULL, NULL, '-', NULL, NULL, NULL, '1997-09-05', 'Semarang', '', 'MAWAR ANAHIDAYAH', 'S.Tr.E', 'Staff Admin', '', '', 'Semarang', 'P', '088215121854', '', NULL, NULL, NULL, 0, 0),
	(66, 0, 213, 0, 14, '2022-12-22 22:28:13', '0000-00-00 00:00:00', NULL, NULL, '-', NULL, NULL, NULL, '0000-00-00', NULL, 'Ns.', 'SRI HANDAYANI', 'S.Kep', 'Perawat Rawat Inap', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(67, 0, 214, 0, 11, '2022-12-22 22:28:13', '2024-05-07 15:55:29', NULL, NULL, '3308105205980003', NULL, NULL, NULL, '1998-05-12', 'MAGELANG', '', 'PRADITA HUBTAMARA PUTRI', 'A.Md.Farm', 'JL. SEDAYU PELEM NO. 11, RT 12 RW 01, BANGETAYU KULON, KEC. GENUK, KOTA SEMARANG', 'JL. SEDAYU PELEM NO. 11, RT 12 RW 01, BANGETAYU KULON, KEC. GENUK, KOTA SEMARANG', 'Asisten Apoteker', 'Semarang', 'P', '082116228825', '(11', NULL, NULL, NULL, 0, 0),
	(68, 0, 215, 0, 8, '2022-12-22 22:28:13', '2024-05-07 15:51:12', NULL, NULL, '-', NULL, NULL, NULL, '1999-01-23', 'DEMAK', '', 'SOLITIA LUNGIT', 'S. Keb', 'Perawat Rawat Jalan', '', '', 'Semarang', 'P', '087777731090', '', NULL, NULL, NULL, 0, 0),
	(69, 0, 216, 0, 13, '2022-12-22 22:28:13', '2024-02-28 13:00:17', NULL, NULL, '-', NULL, NULL, NULL, '1998-04-08', 'SEMARANG', '', 'DINDA ATIKA SARI', 'S.Tr.Kes (Rad)', 'Jl. Brotojoyo Timur III No.20 RT 07 RW 02 Panggung Kidul, Semarang Utara', 'Jasmine Park J7/24 Plamongan Indah', 'Radiografer', 'Semarang', 'P', '085643186086', '', NULL, NULL, NULL, 0, 0),
	(70, 0, 217, 0, 5, '2022-12-22 22:28:13', '2024-07-13 09:19:37', NULL, NULL, '-', '', '', NULL, '2001-05-04', 'Grobogan', '', 'AIDA BERLIANI', '', 'Farmasi', '-', '', 'Semarang', 'P', '0895358011448', '', NULL, NULL, NULL, 0, 0),
	(71, 0, 218, 0, 11, '2022-12-22 22:28:13', '2024-05-07 16:20:13', NULL, NULL, '-', NULL, NULL, NULL, '1999-03-28', 'BLORA', '', 'DANIA GALUH MARDIANA', 'S.KM', 'BLORA', 'MERPATI TIMUR, PEDURUNGAN', 'ADMIN FARMASI', 'Semarang', 'P', '082242863340', '', NULL, NULL, NULL, 0, 0),
	(72, 0, 219, 0, 10, '2022-12-22 22:28:13', '2024-06-03 16:47:40', NULL, NULL, '3374062911830004', '33747.50192/DS.836/03/449.1/020/III/2021', '', NULL, '1983-11-29', 'Bandung', 'dr.', 'NUR IMAN NUGROHO', 'Sp.THT-KL (K)', 'Jl. sapta prasetya no 44 Rt 04 rw 03 kel. pedurngan kidul pedurungan semarang', 'Jl. sapta prasetya no 44 Rt 04 rw 03 kel. pedurngan kidul pedurungan semarang', 'Dokter spesialis THT- BEDAH KEPALA DAN LEHER', 'Semarang', 'L', '08883961415', '', NULL, NULL, NULL, 0, 0),
	(73, 0, 220, 0, 10, '2022-12-22 22:28:13', '2024-05-20 01:52:16', NULL, NULL, '5171022806870004', '33747.50192/DS.1267/02/449.1/044/III/2021', NULL, NULL, '1987-06-28', 'Kupang', 'dr.', 'AGUNG PRAMARTHA IRAWAN', 'M.Biomed, Sp.OG', 'Jl. kelud utara iii no.2 rt 01 rw 01 petompon Kec. Gajah mungkur semarang', 'Jl. kelud utara iii no.2 rt 01 rw 01 petompon Kec. Gajah mungkur semarang', 'Dokter Spesialis obgyn', 'Semarang', 'L', '082236088995', '', NULL, NULL, NULL, 0, 0),
	(74, 0, 221, 0, 10, '2022-12-22 22:28:13', '2024-05-20 01:59:11', NULL, NULL, '33747.50192/DS.1295/02/449.1/129/V/2021', '33747.50192/DS.1295/02/449.1/129/V/2021', NULL, NULL, '1980-09-24', 'Boyolali', 'dr.', 'ANITA TRI HASTUTI', 'Sp.PK', 'Permata [uri, J;. Bukit Tunggal blok C.1/31 rt 05 rw 08 Ngaliyan semarang', 'Permata [uri, J;. Bukit Tunggal blok C.1/31 rt 05 rw 08 Ngaliyan semarang', 'Dokter spesialis patologi klinik', 'Semarang', 'P', '082135821393', '', NULL, NULL, NULL, 0, 0),
	(75, 0, 222, 0, 7, '2022-12-22 22:28:13', '2024-04-23 16:26:59', NULL, NULL, '3374026112950003', '-', '-', NULL, '1995-12-21', 'Semarang', '', 'DESI SUCI LESTARI', '', 'MAGESEN PONCOL NO 44 RT 008 RW 006', '-', 'STAFF ADMIN', 'Semarang', 'P', '087731702131', '', NULL, NULL, NULL, 0, 0),
	(76, 0, 223, 0, 10, '2022-12-22 22:28:13', '2024-10-08 21:49:28', NULL, NULL, '7471085906910002', '33747.50192/DU.2162/02/449.1/455/VII/2022', '-', NULL, '1991-06-19', 'Demak', 'dr.', 'TITIS YUNVICASARI', '', 'Jl. Menjangan Dalam II no 38 Rt 03 rw 10 kel. palebon semarang', 'Jl. Menjangan Dalam II no 38 Rt 03 rw 10 kel. palebon semarang', 'Dokter umum', 'Semarang', 'P', '087840945262', '', NULL, NULL, NULL, 0, 0),
	(77, 0, 224, 0, 10, '2022-12-22 22:28:13', '2024-05-20 02:02:41', NULL, NULL, '3374036505930002', '33747.50192/DU.2720/02/449.1/352/V/2023', '', NULL, '1993-05-25', 'Semarang', 'dr.', 'NATALIA CAROLINA HARWANTO', '', 'Jl. rejosari gang V/25 rT 06 rW 10 REJOSARI semarang', 'Jl. rejosari gang V/25 rT 06 rW 10 REJOSARI semarang', 'Dokter umum', 'Semarang', 'P', '088806223207', '', NULL, NULL, NULL, 0, 0),
	(78, 0, 225, 0, 10, '2022-12-22 22:28:13', '2023-12-05 13:38:31', NULL, NULL, '-', '-', NULL, NULL, '0000-00-00', '', 'dr.', 'GINA AMALIA', '', 'Dokter Jaga', '', '', 'Semarang', 'P', '-', '', NULL, NULL, NULL, 0, 0),
	(79, 0, 226, 0, 10, '2022-12-22 22:28:13', '2024-05-20 02:08:01', NULL, NULL, '3324156810930001', '-', NULL, NULL, '1993-10-28', 'Semarang', 'dr.', 'REZA ANGGITA SALZABELLA', '', 'Jl. rafflesia Residence B.22 rT 07 RW 08 PEDURUNGAN TENGAH SEMARANG', 'Jl. Mahesa Timur No. 360 Rt 07 Rw 03 pedurungan tengah semarang', '', 'Semarang', 'P', '081226457979', '', NULL, NULL, NULL, 0, 0),
	(80, 0, 227, 0, 10, '2022-12-22 22:28:13', '2024-06-13 15:17:23', NULL, NULL, '3374016510950002', '33747.50192/DU.2912/01/449.1/401/XI/2020', '', NULL, '1995-10-25', 'Semarang', 'dr.', 'VANIA OKTAVIANI SUJAMTO', '', 'Jl. Tirtomulyo  Mukti IV/145 A Rt 04 Rw 07 Tlogomulyo pedurungan semarang', 'Jl. Tirtomulyo  Mukti IV/145 A Rt 04 Rw 07 Tlogomulyo pedurungan semarang', 'Dokter umum', 'Semarang', 'P', '085771488922', '', NULL, NULL, NULL, 0, 0),
	(81, 0, 228, 0, 10, '2022-12-22 22:28:13', '2024-05-20 02:21:11', NULL, NULL, '3374066509740004', '33747.50192/DU.1986/02/449.1/499/XII/2021', '-', NULL, '1974-09-23', 'Jambi', 'dr.', 'WIANDARTI THERESIANI', '', 'Jl. mahesa raya No.6 rT 02 RW 03 PEDURUNGAN TENGAH PEDURUNGAN SEMARANG', 'Jl. mahesa raya No.6 rT 02 RW 03 PEDURUNGAN TENGAH PEDURUNGAN SEMARANG', 'dokter umum', 'Semarang', 'P', '081513346451', '', NULL, NULL, NULL, 0, 0),
	(82, 0, 229, 0, 10, '2022-12-22 22:28:13', '2024-05-20 02:04:56', NULL, NULL, '3372022006850001', '-', NULL, NULL, '1985-06-20', 'Surakarta', 'dr.', 'YANI FATRIYA', '', 'Jl. Mahesa Timur No. 360 Rt 07 Rw 03 pedurungan tengah semarang', 'Jl. Mahesa Timur No. 360 Rt 07 Rw 03 pedurungan tengah semarang', 'Dokter umum', 'Semarang', 'L', '081229444857', '', NULL, NULL, NULL, 0, 0),
	(84, 0, 231, 0, 10, '2022-12-22 22:28:13', '2024-05-20 02:15:12', NULL, NULL, '3374072012960002', '33747.50192/DU.1938a/01/449.1/014/I/2023', '', NULL, '1996-12-20', 'Semarang', 'dr.', 'ADLI CHAIRUL UMAM', '', 'Jl. wonodri baru No. 68 Rt 09 rw 02 semarang selatan semarang', 'Jl. wonodri baru No. 68 Rt 09 rw 02 semarang selatan semarang', 'dokter umum', 'Semarang', 'L', '082132698400', '', NULL, NULL, NULL, 0, 0),
	(85, 0, 232, 0, 10, '2022-12-22 22:28:13', '0000-00-00 00:00:00', NULL, NULL, '-', '-', NULL, NULL, '0000-00-00', NULL, 'dr.', 'ARDHILLA', '', 'Dokter Jaga', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(86, 0, 233, 0, 10, '2022-12-22 22:28:13', '0000-00-00 00:00:00', NULL, NULL, '-', '-', NULL, NULL, '0000-00-00', NULL, 'dr.', 'SALMA ADHENIA', '', 'Dokter Jaga', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(87, 0, 234, 0, 10, '2022-12-22 22:28:13', '2024-06-16 14:03:17', NULL, NULL, '3323032907960003', '33747.50192/DU.3280/02/449.1/022/1/2023', '', NULL, '2024-04-26', 'Temanggung', 'dr.', 'MUHAMMAD FAJAR SHODIQ', '', 'Jl Pahlawan 30 RT 01/05 Temanggung', 'Bukit Mutiara I, Gang Kelapa Hijau IX, BQ 17A, RT 07/12, Bukit Kencana Jaya, Meteseh Semarang', 'Dokter', 'Semarang', 'L', '08965714165', '', NULL, NULL, NULL, 0, 0),
	(88, 0, 235, 0, 10, '2022-12-22 22:28:13', '2024-09-25 07:43:45', NULL, NULL, '3674031905960005', '33747.50192/DU.3281/02/449.1/023/I/2023', '-', NULL, '1996-05-19', 'SEMARANG', 'dr.', 'GLENN FERNANDEZ YEREMIA ', '', 'JAKARTA', 'SEMARANG', 'DOKTER', 'Semarang', 'L', '081517123253', '', NULL, NULL, NULL, 0, 0),
	(89, 0, 236, 0, 10, '2022-12-22 22:28:13', '2024-11-02 07:22:40', NULL, NULL, '3308021805850001', '-', '', NULL, '1985-05-18', 'SEMARANG', 'drg.', 'M MUHTAR SAFANGAT.', 'Sp.Ort', 'SEMARANG ', 'SEMARANG ', 'DOKTER', 'Semarang', 'L', '081225550767', '', NULL, NULL, NULL, 0, 0),
	(90, 0, 237, 0, 10, '2022-12-22 22:28:13', '2024-05-24 13:12:38', NULL, NULL, '3324084703930003', '-', NULL, NULL, '1993-03-07', 'KENDAL', 'drg.', 'LOLA CAROLA', '', 'SEMARANG ', 'SEMARANG', 'DOKTER GIGI', 'Semarang', 'P', '-', '', NULL, NULL, NULL, 0, 0),
	(91, 0, 238, 0, 10, '2022-12-22 22:28:13', '0000-00-00 00:00:00', NULL, NULL, '3318124202920002', '-', NULL, NULL, '0000-00-00', NULL, 'drg.', 'LITA PARAMITA', '', 'Dokter Jaga', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(93, 0, 240, 0, 8, '2022-12-22 22:28:13', '2024-04-17 13:08:07', NULL, NULL, '-', NULL, NULL, NULL, '1988-04-15', 'SEMARANG', '', 'WULAN PRIHANDINI', 'Amd.kep', 'Perawat Rawat Jalan', '', 'PERAWAT KECANTIKAN', 'Semarang', 'P', '089519312193', '', NULL, NULL, NULL, 0, 0),
	(94, 0, 241, 0, 10, '2022-12-22 22:28:13', '2024-05-20 01:47:03', NULL, NULL, '3509211507880008', '33747.50192/DS.1169/02/449.1/189/VII/2021', '', NULL, '1988-07-15', 'Surabaya', 'dr.', 'MAHESA', 'Sp.KJ', 'tamansari majapahit cluster grand indrapasta C.6 no 3 Rt 05 rw 06 Pedurungan lor semarang', 'tamansari majapahit cluster grand indrapasta C.6 no 3 Rt 05 rw 06 Pedurungan lor semarang', 'Dokter spesialis kejiwaan', 'Semarang', 'L', '082233399787', '(03', NULL, NULL, NULL, 0, 0),
	(95, 0, 242, 0, 10, '2022-12-22 22:28:13', '0000-00-00 00:00:00', NULL, NULL, '-', '-', NULL, NULL, '0000-00-00', NULL, 'dr.', 'LUQMAN ALWI', 'M.Si.MED, Sp.B', 'Dokter Jaga', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(96, 0, 243, 0, 10, '2022-12-22 22:28:13', '0000-00-00 00:00:00', NULL, NULL, '-', '-', NULL, NULL, '0000-00-00', NULL, 'dr.', 'SEKAR HAPSARI', 'Sp.RAD', 'Dokter Jaga', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(97, 0, 244, 0, 10, '2022-12-22 22:28:13', '2024-05-20 01:36:39', NULL, NULL, '3374136511880002', '33747.50192/DS.1379/03/449.1/196/IX/2022', '', NULL, '1987-11-25', 'Semarang', 'dr.', 'PRISKA LUFTIA R', 'Sp.PD', 'jl. bukit anyelir no. 28 Rt 00 rw 00 Kel. srondol kulon Kec. Banyumanik semarang', '-jl. bukit anyelir no. 28 Rt 00 rw 00 Kel. srondol kulon Kec. Banyumanik semarang', 'dokter spesialis dalam', 'Semarang', 'P', '088238464425', '(0', NULL, NULL, NULL, 0, 0),
	(98, 0, 245, 0, 10, '2022-12-22 22:28:13', '0000-00-00 00:00:00', NULL, NULL, '-', '-', NULL, NULL, '0000-00-00', NULL, 'dr.', 'MARLINA TANDI', 'Sp.RAD', 'Dokter Jaga', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(99, 0, 246, 0, 10, '2022-12-22 22:28:13', '0000-00-00 00:00:00', NULL, NULL, '-', '-', NULL, NULL, '0000-00-00', NULL, 'dr.', 'FRANSISKA BANJARHANOR', 'Sp.M', 'Dokter Jaga', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(100, 0, 247, 0, 10, '2022-12-22 22:28:13', '0000-00-00 00:00:00', NULL, NULL, '-', '-', NULL, NULL, '0000-00-00', NULL, '', 'SALMAH ALAYDRUS', '', 'Dokter Jaga', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(101, 0, 248, 0, 10, '2023-01-26 10:17:03', '2023-02-04 16:48:29', NULL, NULL, '-', '-', NULL, NULL, '0000-00-00', NULL, '', 'FINA SULISTIYANI', '', 'Dokter', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(102, 0, 249, 0, 10, '2023-01-27 16:46:58', '2024-05-20 02:12:57', NULL, NULL, '3374107107980002', '33747.50192/DU.3199/03/449.1/433/X/2022', '', NULL, '1998-07-31', 'Semarang ', 'dr.', 'URSHEILA HAEKMATIAR', '', 'Jl. rogojembangan Timur Rt 03 rw 05 tandang tembalang', 'Jl. rogojembangan Timur Rt 03 rw 05 tandang tembalang', 'Dokter umum', 'Semarang', 'P', '085161235314', '', NULL, NULL, NULL, 0, 0),
	(103, 0, 250, 0, 3, '2023-01-27 20:03:22', '2024-04-29 15:59:43', NULL, NULL, '-', NULL, NULL, NULL, '1994-09-04', 'Semarang', '', 'ALFIAN HARI SUSATYA', 'S.Kom', 'LAMPER', '-', '', 'Semarang', 'L', '085157569393', '', NULL, NULL, NULL, 0, 0),
	(104, 0, 251, 0, 3, '2023-01-29 09:40:44', '2023-02-21 09:29:58', NULL, NULL, '-', NULL, NULL, NULL, '0000-00-00', NULL, NULL, 'AAF TAFTAJANI', NULL, 'Pemilik Perusahaan', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(105, 0, 252, 0, 5, '2023-02-08 11:37:20', '2023-12-12 08:03:48', NULL, NULL, '3374085906860002', NULL, NULL, NULL, '1986-06-19', 'Semarang ', '', 'YUNI DARWATI', 'Amd', 'jl. Selomulyo Mukti Raya F 317 Semarang ', 'jl. Selomulyo Mukti Raya F 317 Semarang ', 'Bagian Umum ', 'Semarang', 'P', '085712719118', '', NULL, NULL, NULL, 0, 0),
	(108, 0, 255, 0, 10, '2023-02-27 12:16:07', '0000-00-00 00:00:00', NULL, NULL, '-', '-', NULL, NULL, '0000-00-00', NULL, '', 'NANDA ILHAM NK', NULL, '-', NULL, NULL, 'Semarang', NULL, '-', NULL, NULL, NULL, NULL, 0, 0),
	(110, 0, 256, 0, 8, '2023-03-08 08:38:49', '2024-04-30 11:24:25', NULL, NULL, '-', NULL, NULL, NULL, '1999-03-31', 'Demak', '', 'NAILUZ ZULFA', 'S.Tr.Kes', 'Jetis RT 01 RW 003 Tamansari, Mranggen, Demak', '', 'Perawat Gigi', 'Semarang', 'P', '089630982841', '', NULL, NULL, NULL, 0, 0),
	(111, 0, 258, 0, 11, '2023-04-28 13:38:54', '2024-04-29 16:20:45', NULL, NULL, '3321056905960002', NULL, NULL, NULL, '1996-05-29', 'Demak', '', 'FITRI NUR HASANTI', '', 'kedungrejo 2/5 kedunguter karangtengah demak', '', 'Admin Farmasi', 'Semarang', 'P', '081215494505', '', NULL, NULL, NULL, 0, 0),
	(112, 0, 259, 0, 10, '2023-07-04 09:39:27', '2023-07-15 12:45:31', NULL, NULL, '-', '-', NULL, NULL, '0000-00-00', '', 'dr.', 'DITHA PARAMASITHA', 'Sp.M', '-', '', '', 'Semarang', 'L', '-', '', NULL, NULL, NULL, 0, 0),
	(113, 0, 260, 0, 10, '2023-07-04 09:40:59', '2023-07-04 09:41:35', NULL, NULL, '3374104603960003', '-', NULL, NULL, '0000-00-00', NULL, 'dr.', 'RATNA DEWI FITRIA', NULL, 'Dokter', NULL, NULL, 'Semarang', 'L', '-', NULL, NULL, NULL, NULL, 0, 0),
	(119, 0, 4952, 0, 9, '2023-09-10 23:03:10', '2023-09-19 07:49:42', NULL, NULL, '111', NULL, NULL, NULL, '1998-09-16', 'semarang', '', 'ZCOBA', '', 'xzczxc', 'zxcdfgdfg', '', NULL, 'L', '085741220427', '(534) 534534', NULL, NULL, NULL, NULL, 0),
	(120, 0, 5779, 0, 17, '2023-09-22 22:40:19', NULL, NULL, NULL, '-', NULL, NULL, NULL, '2023-09-22', '-', '', 'GIZI', '', '-', '-', '-', NULL, 'L', '', '', NULL, NULL, NULL, NULL, 0),
	(121, 0, 5780, 0, 16, '2023-09-22 22:54:28', NULL, NULL, NULL, '-', NULL, NULL, NULL, '2023-09-22', '-', '', 'FISIOTERAPI', '', '-', '-', '-', NULL, 'L', '', '', NULL, NULL, NULL, NULL, 0),
	(122, 0, 6506, 0, 5, '2023-10-03 13:19:52', '2024-07-05 14:07:43', NULL, NULL, '3374026112950003', '', '', NULL, '1995-12-21', 'Semarang', '', 'DESI SUCI LESTARI', '', 'MAGESEN PONCOL NO 44 RT 08 RW 06', '-', '-', NULL, 'P', '087731702131', '', NULL, NULL, NULL, NULL, 0),
	(123, 0, 6860, 0, 18, '2023-10-07 09:05:03', '2024-04-18 10:27:49', NULL, NULL, '-', '', '', NULL, '1992-12-27', 'Demak', '', 'HENGKI ULINUHA', '', '-', '-', 'KEAMANAN', NULL, 'L', '0895414152474', '', NULL, NULL, NULL, NULL, 0),
	(124, 0, 6861, 0, 18, '2023-10-07 09:06:07', '2024-04-19 15:01:59', NULL, NULL, '123', '', '', NULL, '2021-01-10', '-', '', 'ABDUL FATAH', '', '-', '-', 'DRIVER', NULL, 'L', '089637251709', '(', NULL, NULL, NULL, NULL, 0),
	(125, 0, 6863, 0, 18, '2023-10-07 09:08:31', '2024-04-19 15:04:35', NULL, NULL, '123456', '', '', NULL, '1975-02-22', 'Semarang', '', 'SUGENG PURNOMO', '', '123456', '-', 'CLEANING SERVICE', NULL, 'L', '081568293266', '(', NULL, NULL, NULL, NULL, 0),
	(126, 0, 7004, 0, 18, '2023-10-09 08:55:24', '2024-04-19 15:05:24', NULL, NULL, '123456789', '', '', NULL, '1974-04-13', '-', '', 'SRI MURNI', '', 'non entry', '-', 'CLEANING SERVICE', NULL, 'L', '', '', NULL, NULL, NULL, NULL, 0),
	(127, 0, 7005, 0, 18, '2023-10-09 08:57:16', '2024-04-19 15:15:19', NULL, NULL, '123456789876', '', '', NULL, '1976-04-26', '-', '', 'RETNO PRASETYONINGRUM', '', 'non entry', '-', 'CLEANING SERVICE', NULL, 'P', '085875574178', '', NULL, NULL, NULL, NULL, 0),
	(128, 0, 7006, 0, 18, '2023-10-09 08:58:34', '2024-04-29 00:33:37', NULL, NULL, '123456789', '', '', NULL, '1981-07-14', '-', '', 'YULITA ENDRIATI', '', 'non entry', '-', 'CLEANING SERVICE', NULL, 'P', '082133224943', '', NULL, NULL, NULL, NULL, 0),
	(129, 0, 7007, 0, 18, '2023-10-09 09:03:45', '2024-04-19 15:07:43', NULL, NULL, '14141343', '', '', NULL, '1981-12-04', '-', '', 'PARTIAH', '', '-', '-', '-', NULL, 'P', '085794125084', '', NULL, NULL, NULL, NULL, 0),
	(130, 0, 7008, 0, 18, '2023-10-09 09:08:14', '2024-04-19 15:14:11', NULL, NULL, '123456', '', '', NULL, '1967-08-11', '-', '', 'ANI SETYO BUDI YATI', '', 'NON ENTRY', '-', 'LAUNDRY', NULL, 'P', '081228400880', '', NULL, NULL, NULL, NULL, 0),
	(131, 0, 7010, 0, 18, '2023-10-09 09:15:15', '2024-04-19 15:06:30', NULL, NULL, '23456789', '', '', NULL, '1975-12-12', '-', '', 'DEASY ARIYANTI', '', 'NON ENTRY', '-', 'CLEANING SERVICE', NULL, 'P', '', '', NULL, NULL, NULL, NULL, 0),
	(132, 0, 7011, 0, 18, '2023-10-09 09:18:11', '2024-08-12 11:14:02', NULL, NULL, '1234567890', '', '', NULL, '1990-08-13', '-', '', 'SUPRIYANTI', '', 'NON ENTRY', '-', 'LAUNDRY', NULL, 'P', '', '', NULL, NULL, NULL, NULL, 0),
	(133, 0, 7013, 0, 18, '2023-10-09 09:27:33', '2024-04-19 15:18:48', NULL, NULL, '123456789', '', '', NULL, '1960-12-18', 'Seemarang', '', 'SULISTYANINGSIH', '', 'NON ENTRY', 'Perum graha permata no15. RT 16 RW 02 Bangetayu kulon.', 'JURU MASAK', NULL, 'P', '', '', NULL, NULL, NULL, NULL, 0),
	(134, 0, 7014, 0, 18, '2023-10-09 09:29:01', '2024-04-19 15:17:31', NULL, NULL, '1234567890-', '', '', NULL, '1971-02-07', '-', '', 'MARYATI', '', 'NON ENTRY', ' jl sinar Surya lll no 933c  RT 09 RW 01 sinar Waluyo Semarang', 'JURU MASAK', NULL, 'P', '085865186743', '', NULL, NULL, NULL, NULL, 0),
	(135, 0, 7016, 0, 18, '2023-10-09 09:33:38', '2024-04-19 15:19:57', NULL, NULL, '123456789', '', '', NULL, '1977-09-23', '-', '', 'CHRISTIANA SUKO HARTANTI', '', 'NON ENTRY', '-', 'JURU MASAK', NULL, 'P', '08975655995', '', NULL, NULL, NULL, NULL, 0),
	(136, 0, 7017, 0, 18, '2023-10-09 09:35:33', '2024-09-23 13:41:12', NULL, NULL, '12345678', NULL, NULL, NULL, '1986-09-16', 'Semarang', '', 'ANUGRAH RACHMAD HIDAYAT', '', 'Jalan kawi 5 no 70 rt 08 rw 03 kelurahan wonotingal kecamatan Candisari semarang', '- jalan kawi 5 no 70 rt 08 rw 03 kelurahan wonotingal kecamatan Candisari semarang', 'KURIR', NULL, 'L', '085602289586', '(70', NULL, NULL, NULL, NULL, 0),
	(137, 0, 7018, 0, 18, '2023-10-09 09:37:48', '2024-04-18 10:19:17', NULL, NULL, '134235465768798', '', '', NULL, '1983-07-11', '-', '', 'EKO FITRIYADI', '', 'NON ENTRY', '-', 'KEAMANAN', NULL, 'L', '082133379266', '', NULL, NULL, NULL, NULL, 0),
	(138, 0, 7019, 0, 18, '2023-10-09 09:40:10', '2024-04-18 10:29:09', NULL, NULL, '12345678', '', '', NULL, '1984-03-05', '-', '', 'SLAMET SUGENG', '', 'NON ENTRY', '-', 'KEAMANAN', NULL, 'L', '085729421606', '', NULL, NULL, NULL, NULL, 0),
	(139, 0, 7020, 0, 18, '2023-10-09 09:42:30', '2024-04-18 10:27:12', NULL, NULL, '123456789', '', '', NULL, '1967-05-13', '-', '', 'SISWAHYUDI', '', 'NON ENTRY', '-', 'KEAMANAN', NULL, 'L', '082265234991', '', NULL, NULL, NULL, NULL, 0),
	(140, 0, 7021, 0, 18, '2023-10-09 09:46:53', '2024-03-28 14:21:27', NULL, NULL, '3374042003800001', NULL, NULL, NULL, '1980-03-20', 'Semarang', '', 'JOKO PRASETYO', '', 'Jl.Batur sari VI  , Rt 03 / Rw 06  , Kel. Sawah besar  , Kec. Gayamsari Smg', 'Jl.Batur sari VI  , Rt 03 / Rw 06  , Kel. Sawah besar  , Kec . Gayamsari  Smg', 'KEAMANAN', NULL, 'L', '089504838516', '', NULL, NULL, NULL, NULL, 0),
	(141, 0, 7022, 0, 18, '2023-10-09 09:50:05', '2024-04-19 15:02:42', NULL, NULL, '1234567', '', '', NULL, '1979-04-04', 'Semarang', '', 'APRI WAHYONO', '', 'NON ENTRY', '083133277336', 'DRIVER', NULL, 'L', '083133277336', '', NULL, NULL, NULL, NULL, 0),
	(142, 0, 7023, 0, 18, '2023-10-09 09:51:44', '2024-04-19 14:59:36', NULL, NULL, '12345678', '', '', NULL, '1979-10-23', 'Semarang', '', 'HERU TRI KURNIADI S', '', 'NON ENTRY', 'Jl Tegal Wareng 2 no. 50', 'DRIVER', NULL, 'L', '0895367146200', '', NULL, NULL, NULL, NULL, 0),
	(144, 0, 7025, 0, 18, '2023-10-09 09:54:26', '2024-04-21 22:34:01', NULL, NULL, '02070230150', NULL, NULL, NULL, '1996-07-07', 'Semarang', '', 'AHMAD VIKY FURQON SAREBNI', 'S.T.', 'Jalan Sukarno-Hatta No.42 50196 Semarang', 'Jalan Sukarno-Hatta No.42 50196 Semarang', 'MANAGEMEN', NULL, 'L', '085740322709', '', NULL, NULL, NULL, NULL, 0),
	(147, 0, 7029, 0, 5, '2023-10-09 10:09:50', '2024-08-29 09:27:24', NULL, NULL, '`1234567890', '', '', NULL, '1998-08-11', 'SEMARANG', '', 'AGUSTINA ULFIE NIHAYATI', '', 'FARMASI', '-', 'ADMIN ', NULL, 'P', '', '', NULL, NULL, NULL, NULL, 0),
	(148, 0, 7031, 0, 11, '2023-10-09 10:11:10', '2024-05-07 15:36:55', NULL, NULL, '3374115108010002', NULL, NULL, NULL, '2001-08-11', 'SEMARANG', '', 'LIRA HANA SYAKIRA', 'Amd. Farm', 'FARMASI', '-', 'FARMASI', NULL, 'P', '081215044851', '', NULL, NULL, NULL, NULL, 0),
	(149, 0, 7032, 0, 8, '2023-10-09 10:12:42', NULL, NULL, NULL, '123456789', NULL, NULL, NULL, '2023-10-24', '-', '', 'AKUN APOTEKER', '', 'APOTEKER', '-', 'APOTEKER', NULL, 'P', '', '', NULL, NULL, NULL, NULL, 0),
	(150, 0, 7843, 0, 12, '2023-10-18 12:20:38', NULL, NULL, NULL, '-', NULL, NULL, NULL, '1940-10-17', '-', '', 'AKUN LAB', '', '-', '-', '-', NULL, 'L', '', '', NULL, NULL, NULL, NULL, 0),
	(151, 0, 32561, 0, 10, '2023-12-01 10:37:31', '2024-06-07 13:27:31', NULL, NULL, '3374024605960001', '33747.50192/DU.3282/03/449.1/289/XII/2023', '', NULL, '2023-12-01', '-', 'dr.', 'HUSNA HAPSARI PUTRI', '', '-', '-', '-', NULL, 'P', '', '', NULL, NULL, NULL, NULL, 0),
	(152, 0, 32691, 0, 4, '2023-12-03 21:39:44', '2023-12-04 09:01:20', NULL, NULL, '-', NULL, NULL, NULL, '2023-12-01', '-', '', 'TIM A', '', 'Tim A :\n-', 'Stok Opname', '-', NULL, 'L', '', '', NULL, NULL, NULL, NULL, 0),
	(153, 0, 32692, 0, 4, '2023-12-03 21:40:29', '2023-12-04 09:01:07', NULL, NULL, '-', NULL, NULL, NULL, '2023-12-01', '-', '', 'TIM B', '', 'Tim B', 'Stok Opname', '-', NULL, 'L', '', '', NULL, NULL, NULL, NULL, 0),
	(154, 0, 32693, 0, 4, '2023-12-03 21:41:29', '2023-12-04 09:00:53', NULL, NULL, '-', NULL, NULL, NULL, '2023-12-01', '-', '', 'TIM C', '', 'Tim C :', 'Stok Opname', '-', NULL, 'L', '', '', NULL, NULL, NULL, NULL, 0),
	(155, 0, 32694, 0, 4, '2023-12-03 21:42:14', '2023-12-04 09:00:42', NULL, NULL, '-', NULL, NULL, NULL, '2023-12-01', '-', '', 'TIM D', '', 'Tim D : ', 'Stok Opname', '', NULL, 'L', '', '', NULL, NULL, NULL, NULL, 0),
	(156, 0, 32696, 0, 4, '2023-12-03 21:44:30', '2023-12-04 09:00:14', NULL, NULL, '-', NULL, NULL, NULL, '2023-12-01', '-', '', 'TIM E', '', 'Tim E', 'Stok Opname', '-', NULL, 'L', '', '', NULL, NULL, NULL, NULL, 0),
	(157, 0, 32697, 0, 4, '2023-12-03 21:45:19', '2023-12-04 09:00:02', NULL, NULL, '-', NULL, NULL, NULL, '2023-12-01', '-', '', 'TIM F', '', 'Tim F :\n', 'Stok Opname', '-', NULL, 'L', '', '', NULL, NULL, NULL, NULL, 0),
	(158, 0, 32698, 0, 4, '2023-12-03 21:54:05', '2023-12-04 08:59:51', NULL, NULL, '-', NULL, NULL, NULL, '2023-12-01', '-', '', 'TIM G', '', 'Tim G', 'Stok Opname', '-', NULL, 'L', '', '', NULL, NULL, NULL, NULL, 0),
	(159, 0, 32699, 0, 4, '2023-12-03 21:54:51', '2024-04-29 00:33:53', NULL, NULL, '1111111111111111', '12345678', '', NULL, '2023-12-02', '-', '', 'TIM H', '', 'Tim H', 'Stok Opname', '', NULL, 'L', '', '', NULL, NULL, NULL, NULL, 0),
	(161, 0, 34013, 0, 3, '2023-12-27 09:58:48', '2024-03-19 18:37:10', NULL, NULL, '3374061701890001', '', '', NULL, '2023-12-27', 'Semarang', 'dr.', 'YANUAR ARDANI', 'M. Kes, Sp.PD, K-Psi, FINASIM', 'Jl. Wolter Monginsidi No. 40 Pedurungan Semarang', 'Jl. Taman Tlogomulyo Selatan I no 10 Pedurungan', 'Direktur Utama', NULL, 'L', '0811296486', '(024) 6714764', NULL, NULL, NULL, NULL, 0),
	(162, 0, 36990, 0, 3, '2024-02-18 22:20:41', '2024-04-04 08:15:47', NULL, NULL, '3175061405920002', NULL, NULL, NULL, '1992-05-14', 'Jakarta', '', 'DZULFIKAR AKBAR', 'M.Pd', 'Pulo Gebang Permai Blok H5/1', '-', 'Wakil Direktur', NULL, 'L', '081213909594', '', NULL, NULL, NULL, NULL, 0),
	(248, 0, 44549, 0, 10, '2024-06-24 09:16:16', '2024-06-24 10:44:07', NULL, NULL, '-', '-', '-', NULL, '1983-06-07', '-', 'dr.', 'MOHAMAD REZA', 'Sp.N', '-', '-', '-', NULL, 'L', '', '', NULL, NULL, NULL, NULL, 0),
	(257, 0, 46165, 0, 10, '2024-07-18 00:41:29', '2024-10-23 09:16:18', NULL, NULL, '-', '', '', NULL, '2024-07-25', 'Semarang ', 'dr.', ' SYIFA AULIA', 'Sp.Rad', '-', '-', '-', NULL, 'P', '-', '(', NULL, NULL, NULL, NULL, 0),
	(267, 0, 48167, 0, 10, '2024-08-28 14:26:19', '0000-00-00 00:00:00', NULL, NULL, '1235', NULL, NULL, NULL, '2024-08-28', '-', 'dr', 'YENI JAMILAH ', 'Sp. MK', '-', '-', '', NULL, 'P', '', '', NULL, NULL, NULL, NULL, 0),
	(269, 0, 48246, 0, 10, '2024-08-30 09:48:39', '0000-00-00 00:00:00', NULL, NULL, '1234', NULL, NULL, NULL, '2024-08-30', '-', '', 'RENI MARDIKA MUNZIRIN S.FTR.,FTR', '', '-', '-', 'Dokter', NULL, 'P', '', '', NULL, NULL, NULL, NULL, 0),
	(271, 0, 49514, 0, 10, '2024-09-20 18:52:01', '2024-10-12 09:28:04', NULL, NULL, '124', '', '', NULL, '2024-09-27', '-', 'dr. ', 'DWI BAMAS AJI', ' Sp. Rad', '-', '-', '-', NULL, 'L', '-', '', NULL, NULL, NULL, NULL, 0),
	(287, 0, 51778, 0, 10, '2024-11-02 07:25:23', '0000-00-00 00:00:00', NULL, NULL, '1234', NULL, NULL, NULL, '2024-11-02', 'semarang', 'dr.', 'PUTRI RAINA ,', 'M.Biomed', 'semarang', 'semarang', '-', NULL, 'P', '0', '(', NULL, NULL, NULL, NULL, 0),
	(293, 0, 52494, 0, 5, '2024-11-11 11:03:37', '0000-00-00 00:00:00', NULL, NULL, '-', NULL, NULL, NULL, '1999-05-07', '-', '', 'ADE SONIA ROHMAWATI', '', '-', '-', '', NULL, 'P', '', '', NULL, NULL, NULL, NULL, 0);

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_karyawan_absen
DROP TABLE IF EXISTS `tbl_m_karyawan_absen`;
CREATE TABLE IF NOT EXISTS `tbl_m_karyawan_absen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_karyawan` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` date DEFAULT '0000-00-00',
  `wkt_masuk` time DEFAULT '00:00:00',
  `wkt_keluar` time DEFAULT '00:00:00',
  `scan1` time DEFAULT '00:00:00',
  `scan2` time DEFAULT '00:00:00',
  `scan3` time DEFAULT '00:00:00',
  `scan4` time DEFAULT '00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_karyawan_absen: ~0 rows (approximately)
DELETE FROM `tbl_m_karyawan_absen`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_karyawan_cuti
DROP TABLE IF EXISTS `tbl_m_karyawan_cuti`;
CREATE TABLE IF NOT EXISTS `tbl_m_karyawan_cuti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_karyawan` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` date DEFAULT '0000-00-00',
  `tgl_keluar` date DEFAULT '0000-00-00',
  `kode` varchar(160) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('0','1','2') DEFAULT '0' COMMENT '0=pend\r\n1=terima\r\n2=tolak',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_karyawan_cuti: ~4 rows (approximately)
DELETE FROM `tbl_m_karyawan_cuti`;
INSERT INTO `tbl_m_karyawan_cuti` (`id`, `id_karyawan`, `id_user`, `tgl_simpan`, `tgl_modif`, `tgl_masuk`, `tgl_keluar`, `kode`, `keterangan`, `status`) VALUES
	(1, 1, 41, '2023-11-09 22:57:08', '0000-00-00 00:00:00', '2023-11-01', '2023-11-02', NULL, 'Kawin ', '0'),
	(3, 1, 41, '2023-11-09 23:02:24', '0000-00-00 00:00:00', '2023-11-08', '2023-11-08', NULL, 'TES', '0'),
	(4, 63, 210, '2023-11-14 08:35:32', '0000-00-00 00:00:00', '2023-11-14', '2023-11-22', NULL, 'Gsgd', '0'),
	(5, 103, 250, '2023-11-14 14:56:25', '0000-00-00 00:00:00', '2023-11-14', '2023-11-30', NULL, 'tidur nyenyak', '0');

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_karyawan_jadwal
DROP TABLE IF EXISTS `tbl_m_karyawan_jadwal`;
CREATE TABLE IF NOT EXISTS `tbl_m_karyawan_jadwal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_karyawan` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_poli` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `hari_1` varchar(50) DEFAULT NULL,
  `hari_2` varchar(50) DEFAULT NULL,
  `hari_3` varchar(50) DEFAULT NULL,
  `hari_4` varchar(50) DEFAULT NULL,
  `hari_5` varchar(50) DEFAULT NULL,
  `hari_6` varchar(50) DEFAULT NULL,
  `hari_7` varchar(50) DEFAULT NULL,
  `waktu` varchar(160) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status_prtk` int(11) DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_tbl_m_karyawan_jadwal_tbl_m_karyawan` (`id_karyawan`),
  CONSTRAINT `FK_tbl_m_karyawan_jadwal_tbl_m_karyawan` FOREIGN KEY (`id_karyawan`) REFERENCES `tbl_m_karyawan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Untuk menyimpan data riwayat sertifikasi karyawan';

-- Dumping data for table esensiaco_medkit_dev.tbl_m_karyawan_jadwal: ~0 rows (approximately)
DELETE FROM `tbl_m_karyawan_jadwal`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_karyawan_kel
DROP TABLE IF EXISTS `tbl_m_karyawan_kel`;
CREATE TABLE IF NOT EXISTS `tbl_m_karyawan_kel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_karyawan` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `nm_ayah` varchar(160) DEFAULT NULL,
  `nm_ibu` varchar(160) DEFAULT NULL,
  `nm_pasangan` varchar(160) DEFAULT NULL,
  `nm_anak` text DEFAULT NULL,
  `tgl_lhr_ayah` date DEFAULT '0000-00-00',
  `tgl_lhr_ibu` date DEFAULT '0000-00-00',
  `tgl_lhr_psg` date DEFAULT '0000-00-00',
  `jns_pasangan` enum('0','1','2') DEFAULT '0',
  `file_name` varchar(160) DEFAULT NULL,
  `file_name_ktp` varchar(160) DEFAULT NULL,
  `file_ext` varchar(160) DEFAULT NULL,
  `file_ext_ktp` varchar(160) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `file_type_ktp` varchar(50) DEFAULT NULL,
  `status_kawin` enum('0','1','2','3') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_karyawan_kel: ~0 rows (approximately)
DELETE FROM `tbl_m_karyawan_kel`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_karyawan_peg
DROP TABLE IF EXISTS `tbl_m_karyawan_peg`;
CREATE TABLE IF NOT EXISTS `tbl_m_karyawan_peg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_karyawan` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_dept` int(11) DEFAULT 0,
  `id_jabatan` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` date DEFAULT '0000-00-00',
  `tgl_keluar` date DEFAULT '0000-00-00',
  `kode` varchar(160) DEFAULT NULL,
  `no_bpjs_tk` varchar(50) DEFAULT NULL,
  `no_bpjs_ks` varchar(50) DEFAULT NULL,
  `no_npwp` varchar(50) DEFAULT NULL,
  `no_ptkp` varchar(5) DEFAULT NULL,
  `no_rek` varchar(50) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `tipe` int(11) DEFAULT 0 COMMENT 'Status karyawan kotrak, dll',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_m_karyawan_peg_tbl_m_karyawan` (`id_karyawan`),
  CONSTRAINT `FK_tbl_m_karyawan_peg_tbl_m_karyawan` FOREIGN KEY (`id_karyawan`) REFERENCES `tbl_m_karyawan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_karyawan_peg: ~0 rows (approximately)
DELETE FROM `tbl_m_karyawan_peg`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_karyawan_pend
DROP TABLE IF EXISTS `tbl_m_karyawan_pend`;
CREATE TABLE IF NOT EXISTS `tbl_m_karyawan_pend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_karyawan` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `no_dok` varchar(160) DEFAULT NULL,
  `pendidikan` varchar(160) DEFAULT NULL,
  `jurusan` varchar(160) DEFAULT NULL,
  `instansi` varchar(160) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `thn_masuk` year(4) DEFAULT NULL,
  `thn_keluar` year(4) DEFAULT NULL,
  `file_name` varchar(160) DEFAULT NULL,
  `file_ext` varchar(160) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `file_base64` longtext DEFAULT NULL,
  `status_lulus` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_m_karyawan_pend_tbl_m_karyawan` (`id_karyawan`),
  CONSTRAINT `FK_tbl_m_karyawan_pend_tbl_m_karyawan` FOREIGN KEY (`id_karyawan`) REFERENCES `tbl_m_karyawan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Untuk menyimpan data riwayat pendidikan karyawan';

-- Dumping data for table esensiaco_medkit_dev.tbl_m_karyawan_pend: ~0 rows (approximately)
DELETE FROM `tbl_m_karyawan_pend`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_karyawan_poli
DROP TABLE IF EXISTS `tbl_m_karyawan_poli`;
CREATE TABLE IF NOT EXISTS `tbl_m_karyawan_poli` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_karyawan` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_poli` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `FK__tbl_m_karyawan` (`id_karyawan`),
  CONSTRAINT `FK__tbl_m_karyawan` FOREIGN KEY (`id_karyawan`) REFERENCES `tbl_m_karyawan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_karyawan_poli: ~0 rows (approximately)
DELETE FROM `tbl_m_karyawan_poli`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_karyawan_sert
DROP TABLE IF EXISTS `tbl_m_karyawan_sert`;
CREATE TABLE IF NOT EXISTS `tbl_m_karyawan_sert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_karyawan` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `no_dok` varchar(160) DEFAULT NULL,
  `pt` varchar(160) DEFAULT NULL,
  `instansi` varchar(160) DEFAULT NULL,
  `tipe` varchar(160) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `tgl_masuk` date DEFAULT '0000-00-00',
  `tgl_keluar` date DEFAULT '0000-00-00',
  `file_name` varchar(160) DEFAULT NULL,
  `file_ext` varchar(160) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `file_base64` longtext DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_tbl_m_karyawan_pend_tbl_m_karyawan` (`id_karyawan`) USING BTREE,
  CONSTRAINT `FK_tbl_m_karyawan_sert_tbl_m_karyawan` FOREIGN KEY (`id_karyawan`) REFERENCES `tbl_m_karyawan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Untuk menyimpan data riwayat sertifikasi karyawan';

-- Dumping data for table esensiaco_medkit_dev.tbl_m_karyawan_sert: ~0 rows (approximately)
DELETE FROM `tbl_m_karyawan_sert`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_karyawan_tipe
DROP TABLE IF EXISTS `tbl_m_karyawan_tipe`;
CREATE TABLE IF NOT EXISTS `tbl_m_karyawan_tipe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) DEFAULT NULL,
  `tipe` varchar(50) DEFAULT NULL,
  `status` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_karyawan_tipe: ~0 rows (approximately)
DELETE FROM `tbl_m_karyawan_tipe`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_kategori
DROP TABLE IF EXISTS `tbl_m_kategori`;
CREATE TABLE IF NOT EXISTS `tbl_m_kategori` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_app` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT NULL,
  `tgl_modif` datetime DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `keterangan` varchar(160) DEFAULT NULL,
  `status_lab` enum('0','1') DEFAULT '0',
  `status_stok` enum('0','1') DEFAULT '1',
  `status` enum('0','1') DEFAULT '1' COMMENT '0=disabled;\r\n1=aktif;',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_kategori: ~0 rows (approximately)
DELETE FROM `tbl_m_kategori`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_kategori_cuti
DROP TABLE IF EXISTS `tbl_m_kategori_cuti`;
CREATE TABLE IF NOT EXISTS `tbl_m_kategori_cuti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipe` varchar(50) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_kategori_cuti: ~0 rows (approximately)
DELETE FROM `tbl_m_kategori_cuti`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_kategori_spiro
DROP TABLE IF EXISTS `tbl_m_kategori_spiro`;
CREATE TABLE IF NOT EXISTS `tbl_m_kategori_spiro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl_simpan` datetime DEFAULT NULL,
  `tgl_modif` datetime DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `keterangan` varchar(160) DEFAULT NULL,
  `jml_ukur` decimal(10,2) DEFAULT 0.00,
  `jml_pred` decimal(10,2) DEFAULT 0.00,
  `jml_pred2` decimal(10,2) DEFAULT 0.00 COMMENT 'Dalam jumlah persen',
  `jml_lln` decimal(10,2) DEFAULT 0.00,
  `status` enum('0','1') DEFAULT '1' COMMENT '0=disabled;\\r\\n1=aktif;',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_kategori_spiro: ~0 rows (approximately)
DELETE FROM `tbl_m_kategori_spiro`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_mcu
DROP TABLE IF EXISTS `tbl_m_mcu`;
CREATE TABLE IF NOT EXISTS `tbl_m_mcu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT 0,
  `id_kategori` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `kode` varchar(50) DEFAULT NULL,
  `pemeriksaan` varchar(160) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `status_bag` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_mcu: ~0 rows (approximately)
DELETE FROM `tbl_m_mcu`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_mcu_kat
DROP TABLE IF EXISTS `tbl_m_mcu_kat`;
CREATE TABLE IF NOT EXISTS `tbl_m_mcu_kat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_kat` int(11) NOT NULL DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `kode` varchar(60) DEFAULT NULL,
  `kategori` varchar(160) DEFAULT NULL,
  `keterangan` varchar(160) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `status_utm` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_mcu_kat: ~0 rows (approximately)
DELETE FROM `tbl_m_mcu_kat`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_mcu_pny
DROP TABLE IF EXISTS `tbl_m_mcu_pny`;
CREATE TABLE IF NOT EXISTS `tbl_m_mcu_pny` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `kode` varchar(50) DEFAULT NULL,
  `penyakit` varchar(50) DEFAULT NULL,
  `status_tmp` enum('0','1','2') DEFAULT '0' COMMENT '0=default\r\n1=Sisi Kiri\r\n2=Sisi Kanan',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Data Penyakit pada modul MCU';

-- Dumping data for table esensiaco_medkit_dev.tbl_m_mcu_pny: ~0 rows (approximately)
DELETE FROM `tbl_m_mcu_pny`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_merk
DROP TABLE IF EXISTS `tbl_m_merk`;
CREATE TABLE IF NOT EXISTS `tbl_m_merk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl_simpan` datetime DEFAULT NULL,
  `tgl_modif` datetime DEFAULT NULL,
  `merk` varchar(160) DEFAULT NULL,
  `diskon` decimal(10,2) DEFAULT 0.00,
  `keterangan` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_merk: ~0 rows (approximately)
DELETE FROM `tbl_m_merk`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_pasien
DROP TABLE IF EXISTS `tbl_m_pasien`;
CREATE TABLE IF NOT EXISTS `tbl_m_pasien` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_gelar` int(11) DEFAULT 0,
  `id_kategori` int(11) DEFAULT 0,
  `id_pekerjaan` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `kode` varchar(50) DEFAULT NULL,
  `kode_dpn` varchar(50) DEFAULT NULL,
  `nik` varchar(50) DEFAULT NULL,
  `nama` varchar(160) DEFAULT NULL,
  `nama_pgl` varchar(160) DEFAULT NULL,
  `no_telp` varchar(50) DEFAULT NULL,
  `no_hp` varchar(50) DEFAULT NULL,
  `no_rmh` varchar(50) DEFAULT NULL,
  `tmp_lahir` varchar(50) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `alamat_dom` text DEFAULT NULL,
  `kel` varchar(50) DEFAULT NULL,
  `instansi` varchar(160) DEFAULT NULL,
  `instansi_alamat` text DEFAULT NULL,
  `kec` varchar(50) DEFAULT NULL,
  `kota` varchar(50) DEFAULT NULL,
  `file_name` varchar(50) DEFAULT NULL,
  `file_name_id` varchar(50) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `file_ext` varchar(50) DEFAULT NULL,
  `file_base64` longtext DEFAULT NULL COMMENT 'Foto Pasien',
  `alergi` text DEFAULT NULL,
  `jns_klm` enum('L','P') DEFAULT 'L',
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `status_pas` enum('0','1','2') NOT NULL DEFAULT '0',
  `sp` enum('0','1','2') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_pasien: ~0 rows (approximately)
DELETE FROM `tbl_m_pasien`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_pasien_poin
DROP TABLE IF EXISTS `tbl_m_pasien_poin`;
CREATE TABLE IF NOT EXISTS `tbl_m_pasien_poin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pasien` int(11) NOT NULL DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `jml_poin` decimal(10,2) DEFAULT 0.00,
  `jml_poin_nom` decimal(10,2) DEFAULT 0.00,
  `status` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_m_pasien_poin_tbl_m_pasien` (`id_pasien`),
  CONSTRAINT `FK_tbl_m_pasien_poin_tbl_m_pasien` FOREIGN KEY (`id_pasien`) REFERENCES `tbl_m_pasien` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_pasien_poin: ~0 rows (approximately)
DELETE FROM `tbl_m_pasien_poin`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_pelanggan
DROP TABLE IF EXISTS `tbl_m_pelanggan`;
CREATE TABLE IF NOT EXISTS `tbl_m_pelanggan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl_simpan` datetime DEFAULT NULL,
  `tgl_modif` datetime DEFAULT NULL,
  `kode` varchar(160) DEFAULT NULL,
  `nik` varchar(160) DEFAULT NULL,
  `nama` varchar(360) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(160) DEFAULT NULL,
  `cp` varchar(160) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_pelanggan: ~0 rows (approximately)
DELETE FROM `tbl_m_pelanggan`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_pelanggan_agt
DROP TABLE IF EXISTS `tbl_m_pelanggan_agt`;
CREATE TABLE IF NOT EXISTS `tbl_m_pelanggan_agt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pelanggan_grup` int(11) NOT NULL,
  `id_pelanggan` int(11) NOT NULL,
  `potongan` decimal(13,2) NOT NULL,
  `keterangan` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_pelanggan_agt: ~0 rows (approximately)
DELETE FROM `tbl_m_pelanggan_agt`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_pelanggan_deposit
DROP TABLE IF EXISTS `tbl_m_pelanggan_deposit`;
CREATE TABLE IF NOT EXISTS `tbl_m_pelanggan_deposit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_app` int(11) NOT NULL DEFAULT 0,
  `id_pelanggan` int(11) NOT NULL,
  `tgl_simpan` datetime NOT NULL,
  `tgl_modif` datetime NOT NULL,
  `jml_deposit` decimal(32,4) NOT NULL,
  `keterangan` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_pelanggan_deposit: ~0 rows (approximately)
DELETE FROM `tbl_m_pelanggan_deposit`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_pelanggan_deposit_hist
DROP TABLE IF EXISTS `tbl_m_pelanggan_deposit_hist`;
CREATE TABLE IF NOT EXISTS `tbl_m_pelanggan_deposit_hist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_app` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) NOT NULL,
  `id_pelanggan` int(11) NOT NULL,
  `tgl_simpan` datetime NOT NULL,
  `jml_deposit` decimal(32,4) NOT NULL,
  `debet` decimal(32,4) NOT NULL,
  `kredit` decimal(32,4) NOT NULL,
  `keterangan` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_pelanggan_deposit_hist: ~0 rows (approximately)
DELETE FROM `tbl_m_pelanggan_deposit_hist`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_pelanggan_diskon
DROP TABLE IF EXISTS `tbl_m_pelanggan_diskon`;
CREATE TABLE IF NOT EXISTS `tbl_m_pelanggan_diskon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pelanggan` int(11) NOT NULL DEFAULT 0,
  `id_kategori` int(11) NOT NULL DEFAULT 0,
  `tgl_simpan` datetime NOT NULL,
  `disk1` decimal(10,2) NOT NULL DEFAULT 0.00,
  `disk2` decimal(10,2) NOT NULL DEFAULT 0.00,
  `disk3` decimal(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_m_pelanggan_diskon_tbl_m_pelanggan` (`id_pelanggan`),
  CONSTRAINT `FK_tbl_m_pelanggan_diskon_tbl_m_pelanggan` FOREIGN KEY (`id_pelanggan`) REFERENCES `tbl_m_pelanggan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_pelanggan_diskon: ~0 rows (approximately)
DELETE FROM `tbl_m_pelanggan_diskon`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_pelanggan_grup
DROP TABLE IF EXISTS `tbl_m_pelanggan_grup`;
CREATE TABLE IF NOT EXISTS `tbl_m_pelanggan_grup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_app` int(11) NOT NULL DEFAULT 0,
  `tgl_simpan` datetime NOT NULL,
  `tgl_modif` datetime NOT NULL,
  `grup` varchar(160) NOT NULL DEFAULT '0',
  `pot_nominal` decimal(13,2) NOT NULL DEFAULT 0.00,
  `pot_persen` decimal(13,2) NOT NULL DEFAULT 0.00,
  `keterangan` text NOT NULL,
  `status_deposit` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_pelanggan_grup: ~0 rows (approximately)
DELETE FROM `tbl_m_pelanggan_grup`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_penjamin
DROP TABLE IF EXISTS `tbl_m_penjamin`;
CREATE TABLE IF NOT EXISTS `tbl_m_penjamin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl_simpan` datetime DEFAULT current_timestamp(),
  `kode` varchar(160) DEFAULT NULL,
  `penjamin` varchar(160) DEFAULT NULL,
  `persen` decimal(10,1) DEFAULT 0.0,
  `status` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel master penjamin yang berisi penjamin pelayanan.\r\nYang berupa :\r\n- UMUM (Pasien UMUM / Bayar Duit Cash)\r\n- ASURANSI (Pasien spt Mandiri InHealth, ManuLife, dll)\r\n- BPJS (Pasti sudah tahu semua)';

-- Dumping data for table esensiaco_medkit_dev.tbl_m_penjamin: ~0 rows (approximately)
DELETE FROM `tbl_m_penjamin`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_platform
DROP TABLE IF EXISTS `tbl_m_platform`;
CREATE TABLE IF NOT EXISTS `tbl_m_platform` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `kode` varchar(160) DEFAULT NULL,
  `akun` varchar(160) DEFAULT NULL,
  `platform` varchar(160) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `persen` decimal(10,1) DEFAULT NULL,
  `status` enum('0','1','2') DEFAULT '1',
  `status_akt` enum('0','1','2') DEFAULT '0' COMMENT 'Cash\\r\\nAsuransi',
  PRIMARY KEY (`id`),
  KEY `id_kategori` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_platform: ~0 rows (approximately)
DELETE FROM `tbl_m_platform`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_poli
DROP TABLE IF EXISTS `tbl_m_poli`;
CREATE TABLE IF NOT EXISTS `tbl_m_poli` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_app` int(11) NOT NULL DEFAULT 0,
  `tgl_simpan` datetime DEFAULT NULL,
  `tgl_modif` datetime DEFAULT NULL,
  `kode` varchar(64) DEFAULT NULL,
  `lokasi` varchar(64) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `warna` varchar(64) DEFAULT NULL,
  `post_location` varchar(100) DEFAULT NULL,
  `tipe` enum('1','2') DEFAULT NULL,
  `status` enum('0','1') DEFAULT '0',
  `status_online` enum('0','1') DEFAULT '0',
  `status_ant` enum('0','1','2') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_poli: ~0 rows (approximately)
DELETE FROM `tbl_m_poli`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_poli_tipe
DROP TABLE IF EXISTS `tbl_m_poli_tipe`;
CREATE TABLE IF NOT EXISTS `tbl_m_poli_tipe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipe` varchar(50) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_poli_tipe: ~0 rows (approximately)
DELETE FROM `tbl_m_poli_tipe`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_produk
DROP TABLE IF EXISTS `tbl_m_produk`;
CREATE TABLE IF NOT EXISTS `tbl_m_produk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_satuan` int(11) DEFAULT 7,
  `id_kategori` int(11) DEFAULT 0,
  `id_kategori_lab` int(11) DEFAULT 0,
  `id_kategori_gol` int(11) DEFAULT 0,
  `id_lokasi` int(11) DEFAULT 0,
  `id_merk` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_user_arsip` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_simpan_arsip` datetime DEFAULT '0000-00-00 00:00:00',
  `kode` varchar(65) DEFAULT NULL,
  `barcode` varchar(65) DEFAULT NULL,
  `produk` varchar(160) DEFAULT NULL,
  `produk_alias` text DEFAULT NULL,
  `produk_kand` text DEFAULT NULL,
  `produk_kand2` text DEFAULT NULL,
  `jml` float DEFAULT NULL,
  `jml_display` float DEFAULT 0,
  `jml_limit` float DEFAULT 0,
  `harga_beli` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT 0.00,
  `harga_beli_ppn` decimal(10,2) DEFAULT NULL,
  `harga_jual` int(11) DEFAULT NULL,
  `harga_hasil` decimal(10,2) DEFAULT NULL,
  `harga_grosir` decimal(10,2) DEFAULT NULL,
  `remun_tipe` enum('0','1','2') DEFAULT '0',
  `remun_perc` decimal(10,2) DEFAULT 0.00,
  `remun_nom` decimal(10,2) DEFAULT 0.00,
  `apres_tipe` enum('0','1','2') DEFAULT '0',
  `apres_perc` decimal(10,2) DEFAULT 0.00,
  `apres_nom` decimal(10,2) unsigned DEFAULT 0.00,
  `status_promo` enum('0','1') DEFAULT '0',
  `status_subt` enum('0','1') DEFAULT '0',
  `status_lab` enum('0','1') DEFAULT '0',
  `status_brg_dep` enum('0','1') DEFAULT '0',
  `status_stok` enum('0','1') DEFAULT '0',
  `status_racikan` enum('0','1') DEFAULT '0',
  `status_etiket` enum('0','1','2') DEFAULT '0' COMMENT '0=netral;\r\n1=etiket putih;\r\n2=etiket biru;',
  `status_hps` enum('0','1') DEFAULT '0',
  `sl` enum('0','1','2') DEFAULT '0',
  `sp` enum('0','1') DEFAULT '0',
  `so` enum('0','1') DEFAULT '0',
  `status` int(11) DEFAULT NULL COMMENT '2=tindakan\r\n3=lab\r\n4=obat\r\n5=radiologi\r\n6=racikan',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_produk: ~0 rows (approximately)
DELETE FROM `tbl_m_produk`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_produk_deposit
DROP TABLE IF EXISTS `tbl_m_produk_deposit`;
CREATE TABLE IF NOT EXISTS `tbl_m_produk_deposit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_produk` int(11) NOT NULL,
  `tgl_simpan` datetime NOT NULL,
  `no_nota` varchar(50) NOT NULL,
  `keterangan` text NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `debet` decimal(10,2) NOT NULL,
  `kredit` decimal(10,2) NOT NULL,
  `saldo` decimal(10,2) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_m_produk_deposit_tbl_m_produk` (`id_produk`),
  CONSTRAINT `FK_tbl_m_produk_deposit_tbl_m_produk` FOREIGN KEY (`id_produk`) REFERENCES `tbl_m_produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_produk_deposit: ~0 rows (approximately)
DELETE FROM `tbl_m_produk_deposit`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_produk_harga
DROP TABLE IF EXISTS `tbl_m_produk_harga`;
CREATE TABLE IF NOT EXISTS `tbl_m_produk_harga` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_produk` int(11) NOT NULL,
  `tgl_simpan` date NOT NULL,
  `keterangan` varchar(160) NOT NULL,
  `harga` decimal(32,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_m_produk_harga_tbl_m_produk` (`id_produk`),
  CONSTRAINT `FK_tbl_m_produk_harga_tbl_m_produk` FOREIGN KEY (`id_produk`) REFERENCES `tbl_m_produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_produk_harga: ~0 rows (approximately)
DELETE FROM `tbl_m_produk_harga`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_produk_hist
DROP TABLE IF EXISTS `tbl_m_produk_hist`;
CREATE TABLE IF NOT EXISTS `tbl_m_produk_hist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_produk` int(11) DEFAULT 0,
  `id_gudang` int(11) DEFAULT 1,
  `id_user` int(11) DEFAULT 0,
  `id_pelanggan` int(11) DEFAULT 0,
  `id_supplier` int(11) DEFAULT 0,
  `id_penjualan` int(11) DEFAULT 0,
  `id_pembelian` int(11) DEFAULT 0,
  `id_pembelian_det` int(11) DEFAULT 0,
  `id_so` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT NULL,
  `tgl_modif` datetime DEFAULT NULL,
  `tgl_masuk` datetime DEFAULT NULL,
  `tgl_ed` date DEFAULT '0000-00-00',
  `no_nota` varchar(100) DEFAULT NULL,
  `kode` varchar(100) DEFAULT NULL,
  `kode_batch` varchar(100) DEFAULT NULL,
  `produk` varchar(100) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `nominal` decimal(10,2) DEFAULT 0.00,
  `jml` int(11) DEFAULT 0,
  `jml_satuan` int(11) DEFAULT 1,
  `satuan` varchar(50) DEFAULT NULL,
  `status` enum('1','2','3','4','5','6','7','8') DEFAULT NULL COMMENT '1 = Stok Masuk Pembelian\\r\\n2 = Stok Masuk\\r\\n3 = Stok Masuk Retur Jual\\r\\n4 = Stok Keluar Penjualan\\r\\n5 = Stok Keluar Retur Beli\\r\\n6 = SO\\r\\n7 = Stok Keluar\\r\\n8 = Mutasi Antar Gd',
  `sp` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_produk` (`id_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_produk_hist: ~0 rows (approximately)
DELETE FROM `tbl_m_produk_hist`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_produk_hist_harga
DROP TABLE IF EXISTS `tbl_m_produk_hist_harga`;
CREATE TABLE IF NOT EXISTS `tbl_m_produk_hist_harga` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_produk` int(11) NOT NULL,
  `id_harga` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_pembelian` int(11) DEFAULT NULL,
  `tgl_simpan` datetime DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `nom_awal` decimal(10,2) DEFAULT NULL,
  `nom_akhir` decimal(10,2) DEFAULT NULL,
  `status` enum('1','2') DEFAULT NULL COMMENT '1 = Harga Beli\r\n2 = Harga Jual',
  PRIMARY KEY (`id`),
  KEY `id_produk` (`id_produk`),
  CONSTRAINT `FK_tbl_m_produk_hist_harga_tbl_m_produk` FOREIGN KEY (`id_produk`) REFERENCES `tbl_m_produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_produk_hist_harga: ~0 rows (approximately)
DELETE FROM `tbl_m_produk_hist_harga`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_produk_nominal
DROP TABLE IF EXISTS `tbl_m_produk_nominal`;
CREATE TABLE IF NOT EXISTS `tbl_m_produk_nominal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_produk` int(11) NOT NULL,
  `tgl_simpan` datetime NOT NULL,
  `keterangan` text NOT NULL,
  `nominal` decimal(10,0) NOT NULL,
  `harga` decimal(10,0) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_m_produk_nominal_tbl_m_produk` (`id_produk`),
  CONSTRAINT `FK_tbl_m_produk_nominal_tbl_m_produk` FOREIGN KEY (`id_produk`) REFERENCES `tbl_m_produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Nominal Deposit';

-- Dumping data for table esensiaco_medkit_dev.tbl_m_produk_nominal: ~0 rows (approximately)
DELETE FROM `tbl_m_produk_nominal`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_produk_promo
DROP TABLE IF EXISTS `tbl_m_produk_promo`;
CREATE TABLE IF NOT EXISTS `tbl_m_produk_promo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_produk` int(11) NOT NULL DEFAULT 0,
  `id_promo` int(11) NOT NULL DEFAULT 0,
  `tgl_simpan` datetime NOT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_akhir` date NOT NULL,
  `nominal` decimal(10,2) NOT NULL,
  `disk1` decimal(10,2) NOT NULL,
  `disk2` decimal(10,2) NOT NULL,
  `disk3` decimal(10,2) NOT NULL,
  `keterangan` mediumtext NOT NULL,
  `tipe` enum('1','2') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_m_produk_promo_tbl_m_produk` (`id_produk`),
  CONSTRAINT `FK_tbl_m_produk_promo_tbl_m_produk` FOREIGN KEY (`id_produk`) REFERENCES `tbl_m_produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_produk_promo: ~0 rows (approximately)
DELETE FROM `tbl_m_produk_promo`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_produk_ref
DROP TABLE IF EXISTS `tbl_m_produk_ref`;
CREATE TABLE IF NOT EXISTS `tbl_m_produk_ref` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_produk` int(11) DEFAULT 0,
  `id_produk_item` int(11) DEFAULT 0,
  `id_satuan` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `kode` varchar(50) DEFAULT NULL,
  `item` varchar(160) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT 0.00,
  `jml` decimal(10,2) DEFAULT 0.00,
  `jml_satuan` int(11) DEFAULT 0,
  `satuan` varchar(50) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_m_produk_lab_tbl_m_produk` (`id_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_produk_ref: ~0 rows (approximately)
DELETE FROM `tbl_m_produk_ref`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_produk_ref_input
DROP TABLE IF EXISTS `tbl_m_produk_ref_input`;
CREATE TABLE IF NOT EXISTS `tbl_m_produk_ref_input` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_produk` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) NOT NULL DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `item_name` varchar(160) DEFAULT NULL,
  `item_value` text DEFAULT NULL,
  `item_value_l1` text DEFAULT NULL,
  `item_value_l2` text DEFAULT NULL,
  `item_value_p1` text DEFAULT NULL,
  `item_value_p2` text DEFAULT NULL,
  `item_satuan` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK__tbl_m_produk` (`id_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_produk_ref_input: ~0 rows (approximately)
DELETE FROM `tbl_m_produk_ref_input`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_produk_saldo
DROP TABLE IF EXISTS `tbl_m_produk_saldo`;
CREATE TABLE IF NOT EXISTS `tbl_m_produk_saldo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_produk` int(11) NOT NULL,
  `tgl_simpan` datetime NOT NULL,
  `jml` int(11) NOT NULL,
  `jml_satuan` int(11) NOT NULL,
  `satuan` varchar(50) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `keterangan` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_m_produk_saldo_tbl_m_produk` (`id_produk`),
  CONSTRAINT `FK_tbl_m_produk_saldo_tbl_m_produk` FOREIGN KEY (`id_produk`) REFERENCES `tbl_m_produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_produk_saldo: ~0 rows (approximately)
DELETE FROM `tbl_m_produk_saldo`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_produk_satuan
DROP TABLE IF EXISTS `tbl_m_produk_satuan`;
CREATE TABLE IF NOT EXISTS `tbl_m_produk_satuan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_produk` int(11) DEFAULT NULL,
  `id_satuan` int(11) DEFAULT NULL,
  `satuan` varchar(160) DEFAULT NULL,
  `jml` int(11) DEFAULT 0,
  `harga` decimal(10,2) DEFAULT 0.00,
  `diskon` decimal(10,2) DEFAULT 0.00,
  `subtotal` decimal(10,2) DEFAULT 0.00,
  `status` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_m_produk_satuan_tbl_m_produk` (`id_produk`),
  CONSTRAINT `FK_tbl_m_produk_satuan_tbl_m_produk` FOREIGN KEY (`id_produk`) REFERENCES `tbl_m_produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_produk_satuan: ~0 rows (approximately)
DELETE FROM `tbl_m_produk_satuan`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_produk_stok
DROP TABLE IF EXISTS `tbl_m_produk_stok`;
CREATE TABLE IF NOT EXISTS `tbl_m_produk_stok` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_produk` int(11) NOT NULL,
  `id_satuan` int(11) DEFAULT NULL,
  `id_gudang` int(11) DEFAULT 1,
  `id_user` int(11) DEFAULT 1,
  `tgl_simpan` timestamp NULL DEFAULT NULL,
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `jml` float DEFAULT 0,
  `jml_satuan` float DEFAULT 1,
  `satuan` varchar(160) DEFAULT NULL,
  `satuanKecil` varchar(160) DEFAULT 'PCS',
  `status` enum('0','1','2') DEFAULT '0',
  `so` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_m_produk_stok_tbl_m_produk` (`id_produk`),
  CONSTRAINT `FK_tbl_m_produk_stok_tbl_m_produk` FOREIGN KEY (`id_produk`) REFERENCES `tbl_m_produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_produk_stok: ~0 rows (approximately)
DELETE FROM `tbl_m_produk_stok`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_promo
DROP TABLE IF EXISTS `tbl_m_promo`;
CREATE TABLE IF NOT EXISTS `tbl_m_promo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl_simpan` datetime NOT NULL,
  `promo` varchar(160) NOT NULL,
  `keterangan` text NOT NULL,
  `nominal` decimal(10,4) NOT NULL,
  `disk1` decimal(10,4) NOT NULL,
  `disk2` decimal(10,4) NOT NULL,
  `disk3` decimal(10,4) NOT NULL,
  `tipe` enum('1','2') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_promo: ~0 rows (approximately)
DELETE FROM `tbl_m_promo`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_sales
DROP TABLE IF EXISTS `tbl_m_sales`;
CREATE TABLE IF NOT EXISTS `tbl_m_sales` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `id_app` int(4) NOT NULL DEFAULT 0,
  `id_user` int(4) NOT NULL DEFAULT 0,
  `id_user_group` int(4) NOT NULL DEFAULT 0,
  `tgl_simpan` datetime DEFAULT NULL,
  `tgl_modif` datetime DEFAULT NULL,
  `kategori` varchar(10) DEFAULT NULL,
  `kode` varchar(10) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `nik` varchar(100) DEFAULT NULL,
  `alamat` text NOT NULL,
  `kota` varchar(50) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `disk1` decimal(11,2) DEFAULT NULL,
  `disk2` decimal(11,2) DEFAULT NULL,
  `disk3` decimal(11,2) DEFAULT NULL,
  `status` enum('0','1','2','3','4') DEFAULT NULL COMMENT '1=perawat\r\n2=dokter\r\n3=kasir\r\n4=lab',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_sales: ~0 rows (approximately)
DELETE FROM `tbl_m_sales`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_satuan
DROP TABLE IF EXISTS `tbl_m_satuan`;
CREATE TABLE IF NOT EXISTS `tbl_m_satuan` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tgl_simpan` datetime DEFAULT NULL,
  `tgl_modif` datetime DEFAULT NULL,
  `satuanTerkecil` varchar(250) NOT NULL,
  `satuanBesar` varchar(250) DEFAULT NULL,
  `jml` int(11) NOT NULL,
  `status` enum('1','0') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_satuan: ~0 rows (approximately)
DELETE FROM `tbl_m_satuan`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_satuan_pakai
DROP TABLE IF EXISTS `tbl_m_satuan_pakai`;
CREATE TABLE IF NOT EXISTS `tbl_m_satuan_pakai` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tgl_simpan` datetime DEFAULT NULL,
  `tgl_modif` datetime DEFAULT NULL,
  `satuan` varchar(250) NOT NULL,
  `status` enum('1','0') DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_satuan_pakai: ~0 rows (approximately)
DELETE FROM `tbl_m_satuan_pakai`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_m_supplier
DROP TABLE IF EXISTS `tbl_m_supplier`;
CREATE TABLE IF NOT EXISTS `tbl_m_supplier` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `tgl_simpan` datetime DEFAULT NULL,
  `tgl_modif` datetime DEFAULT NULL,
  `kode` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `npwp` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `kota` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `no_tlp` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `no_hp` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `cp` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_m_supplier: ~0 rows (approximately)
DELETE FROM `tbl_m_supplier`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_pendaftaran
DROP TABLE IF EXISTS `tbl_pendaftaran`;
CREATE TABLE IF NOT EXISTS `tbl_pendaftaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_gelar` int(11) DEFAULT 0,
  `id_pasien` int(11) DEFAULT 0,
  `id_poli` int(11) DEFAULT 0,
  `id_platform` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `id_pekerjaan` int(11) DEFAULT 0,
  `id_ant` int(11) DEFAULT 0,
  `id_instansi` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` datetime DEFAULT '0000-00-00 00:00:00',
  `no_urut` int(11) DEFAULT 0,
  `no_antrian` int(11) DEFAULT 0,
  `nik` varchar(160) DEFAULT NULL,
  `nama` varchar(160) DEFAULT NULL,
  `nama_pgl` varchar(160) DEFAULT NULL,
  `tmp_lahir` varchar(160) DEFAULT NULL,
  `tgl_lahir` date DEFAULT '0000-00-00',
  `jns_klm` enum('L','P') DEFAULT 'L',
  `kontak` varchar(50) DEFAULT NULL,
  `kontak_rmh` varchar(50) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `alamat_dom` text DEFAULT NULL,
  `instansi` varchar(160) DEFAULT NULL,
  `instansi_alamat` text DEFAULT NULL,
  `alergi` text DEFAULT NULL,
  `file_base64` longtext DEFAULT NULL,
  `file_base64_id` longtext DEFAULT NULL,
  `tipe_bayar` int(11) DEFAULT 0 COMMENT '0 = tidak ada;\r\n1 = UMUM;\r\n2 = ASURANSI;\r\n3 = BPJS;',
  `tipe_rawat` int(11) DEFAULT 0,
  `tipe` int(11) DEFAULT 0,
  `status` enum('1','2') DEFAULT '1',
  `status_akt` enum('0','1','2') DEFAULT '1' COMMENT '0=pend\r\n1=konfirm\r\n2=selesai',
  `status_hdr` enum('0','1') DEFAULT '1',
  `status_gc` enum('0','1') DEFAULT '0',
  `status_dft` enum('0','1','2') DEFAULT '1',
  `status_hps` enum('0','1','2') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_pendaftaran: ~0 rows (approximately)
DELETE FROM `tbl_pendaftaran`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_pendaftaran_gc
DROP TABLE IF EXISTS `tbl_pendaftaran_gc`;
CREATE TABLE IF NOT EXISTS `tbl_pendaftaran_gc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pendaftaran` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` date DEFAULT '0000-00-00',
  `nik` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `no_hp` varchar(50) DEFAULT NULL,
  `jns_klm` enum('L','P') DEFAULT NULL,
  `tgl_lahir` date DEFAULT '0000-00-00',
  `tmp_lahir` varchar(50) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `file_name` text DEFAULT NULL,
  `status_hub` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_pendaftaran_gc_tbl_pendaftaran` (`id_pendaftaran`),
  CONSTRAINT `FK_tbl_pendaftaran_gc_tbl_pendaftaran` FOREIGN KEY (`id_pendaftaran`) REFERENCES `tbl_pendaftaran` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_pendaftaran_gc: ~0 rows (approximately)
DELETE FROM `tbl_pendaftaran_gc`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_pengaturan
DROP TABLE IF EXISTS `tbl_pengaturan`;
CREATE TABLE IF NOT EXISTS `tbl_pengaturan` (
  `id_pengaturan` int(3) NOT NULL AUTO_INCREMENT,
  `id_app` int(3) NOT NULL DEFAULT 0,
  `website` varchar(100) NOT NULL,
  `judul` varchar(500) NOT NULL,
  `judul_app` varchar(500) NOT NULL,
  `url_app` varchar(500) NOT NULL,
  `logo` varchar(500) NOT NULL,
  `logo_header` varchar(500) NOT NULL,
  `logo_header_kop` varchar(500) NOT NULL,
  `deskripsi` text NOT NULL,
  `deskripsi_pendek` text NOT NULL,
  `notifikasi` varchar(320) NOT NULL,
  `alamat` varchar(300) NOT NULL,
  `kota` varchar(100) NOT NULL,
  `email` varchar(360) NOT NULL,
  `pesan` text NOT NULL,
  `tlp` varchar(160) NOT NULL,
  `fax` varchar(160) NOT NULL,
  `url_antrian` varchar(160) NOT NULL,
  `ss_org_id` text NOT NULL,
  `ss_client_id` text NOT NULL,
  `ss_client_secret` text NOT NULL,
  `kode_surat_sht` varchar(50) NOT NULL,
  `kode_surat_skt` varchar(50) NOT NULL,
  `kode_surat_rnp` varchar(50) NOT NULL,
  `kode_surat_kntrl` varchar(50) NOT NULL,
  `kode_surat_lahir` varchar(50) NOT NULL,
  `kode_surat_mati` varchar(50) NOT NULL,
  `kode_surat_covid` varchar(50) NOT NULL,
  `kode_surat_rujukan` varchar(50) NOT NULL,
  `kode_surat_tht` varchar(50) NOT NULL,
  `kode_surat` varchar(50) NOT NULL,
  `kode_resep` varchar(50) NOT NULL,
  `kode_rad` varchar(50) NOT NULL,
  `kode_periksa` varchar(50) NOT NULL,
  `kode_pasien` varchar(50) NOT NULL,
  `ppn` decimal(10,2) NOT NULL DEFAULT 0.00,
  `ppn_tot` int(11) NOT NULL,
  `jml_ppn` int(11) NOT NULL,
  `jml_item` int(11) NOT NULL,
  `jml_limit_stok` int(11) NOT NULL,
  `jml_limit_tempo` int(11) NOT NULL,
  `jml_poin` int(11) NOT NULL,
  `jml_poin_nom` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tahun_poin` int(11) NOT NULL,
  `status_app` enum('pusat','cabang') NOT NULL,
  PRIMARY KEY (`id_pengaturan`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_pengaturan: ~0 rows (approximately)
DELETE FROM `tbl_pengaturan`;
INSERT INTO `tbl_pengaturan` (`id_pengaturan`, `id_app`, `website`, `judul`, `judul_app`, `url_app`, `logo`, `logo_header`, `logo_header_kop`, `deskripsi`, `deskripsi_pendek`, `notifikasi`, `alamat`, `kota`, `email`, `pesan`, `tlp`, `fax`, `url_antrian`, `ss_org_id`, `ss_client_id`, `ss_client_secret`, `kode_surat_sht`, `kode_surat_skt`, `kode_surat_rnp`, `kode_surat_kntrl`, `kode_surat_lahir`, `kode_surat_mati`, `kode_surat_covid`, `kode_surat_rujukan`, `kode_surat_tht`, `kode_surat`, `kode_resep`, `kode_rad`, `kode_periksa`, `kode_pasien`, `ppn`, `ppn_tot`, `jml_ppn`, `jml_item`, `jml_limit_stok`, `jml_limit_tempo`, `jml_poin`, `jml_poin_nom`, `tahun_poin`, `status_app`) VALUES
	(1, 1, 'esensia.co.id', 'KLINIK UTAMA dan LABORATORIUM "ESENSIA"', 'Medkit', 'https://simrs.esensia.co.id', 'logo-esensia-rs.png', 'logo-only.png', 'logo-esensia-2.png', 'logo-es-2.png\r\nKLINIK UTAMA RAWAT JALAN & INAP "ESENSIA"', 'KLINIK RAWAT JALAN & INAP "ESENSIA"', '', 'Jl. Wolter Monginsidi No. 40 Pedurungan - Semarang', 'Semarang', 'mikhaelfelian@gmail.com', '', 'Telp. (024) 76411636, 6714764', '', 'http://localhost/antrian2', '100018572', 'CeClLF3u1MJ06OpjirNOkPUWiPGBZmzIIyfP6IILYKVBDw7z', 'uvjqFLAEDm7XiijA1Zko8i9pfyMw7xVp8rpybeDTCQyvIoepfYFWiW0jFnbXpPso', 'SKS-ES', 'SKN-ES', 'SKRI-ES', 'SKn-ES', 'DOC-EH', 'DOC-EH', 'DOC-EH', 'DOC-EH', 'SKS-THT', 'DOC-EH', 'PRSC', 'SKRAD-ES', 'SKP', 'PKE', 1.11, 111, 11, 15, 12, 10, 500, 50000.00, 2024, '');

-- Dumping structure for table esensiaco_medkit_dev.tbl_pengaturan_cabang
DROP TABLE IF EXISTS `tbl_pengaturan_cabang`;
CREATE TABLE IF NOT EXISTS `tbl_pengaturan_cabang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl_simpan` datetime DEFAULT NULL,
  `keterangan` varchar(256) DEFAULT NULL,
  `npwp` varchar(256) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `kota` varchar(160) DEFAULT NULL,
  `no_tlp` varchar(160) DEFAULT NULL,
  `sn` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_pengaturan_cabang: ~0 rows (approximately)
DELETE FROM `tbl_pengaturan_cabang`;
INSERT INTO `tbl_pengaturan_cabang` (`id`, `tgl_simpan`, `keterangan`, `npwp`, `alamat`, `kota`, `no_tlp`, `sn`) VALUES
	(1, '2019-06-07 23:26:22', 'PUSAT', NULL, NULL, NULL, NULL, NULL);

-- Dumping structure for table esensiaco_medkit_dev.tbl_pengaturan_mail
DROP TABLE IF EXISTS `tbl_pengaturan_mail`;
CREATE TABLE IF NOT EXISTS `tbl_pengaturan_mail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `proto` enum('mail','sendmail','smtp') NOT NULL,
  `host` varchar(160) NOT NULL,
  `user` varchar(160) NOT NULL,
  `pass` varchar(160) NOT NULL,
  `port` int(11) NOT NULL,
  `timeout` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_pengaturan_mail: ~0 rows (approximately)
DELETE FROM `tbl_pengaturan_mail`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_pengaturan_notif
DROP TABLE IF EXISTS `tbl_pengaturan_notif`;
CREATE TABLE IF NOT EXISTS `tbl_pengaturan_notif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(160) NOT NULL,
  `keterangan` varchar(160) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_pengaturan_notif: ~0 rows (approximately)
DELETE FROM `tbl_pengaturan_notif`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_pengaturan_profile
DROP TABLE IF EXISTS `tbl_pengaturan_profile`;
CREATE TABLE IF NOT EXISTS `tbl_pengaturan_profile` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_pengaturan` int(10) unsigned NOT NULL DEFAULT 0,
  `npwp` char(15) NOT NULL DEFAULT '',
  `nama` varchar(100) NOT NULL DEFAULT '',
  `alamat` varchar(100) NOT NULL DEFAULT '',
  `kota` varchar(50) NOT NULL DEFAULT '',
  `telpon` varchar(30) NOT NULL DEFAULT '',
  `fax` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `jenis_usaha` varchar(45) NOT NULL DEFAULT '',
  `klu` char(6) NOT NULL DEFAULT '',
  `pemilik` varchar(100) NOT NULL DEFAULT '',
  `npwp_pemilik` char(15) NOT NULL DEFAULT '',
  `keterangan` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_pengaturan_profile: ~0 rows (approximately)
DELETE FROM `tbl_pengaturan_profile`;
INSERT INTO `tbl_pengaturan_profile` (`id`, `id_pengaturan`, `npwp`, `nama`, `alamat`, `kota`, `telpon`, `fax`, `email`, `jenis_usaha`, `klu`, `pemilik`, `npwp_pemilik`, `keterangan`) VALUES
	(1, 1, '121231231123123', 'PT. Keuangan Guyub', 'Jl. Keuangan No.30', 'Palembang', '07117744002', '', 'keuangan@guyub.co.id', 'Pelayanan Jasa', '123456', 'Muhammad Subair', '112223334555666', 'data wajib pajak');

-- Dumping structure for table esensiaco_medkit_dev.tbl_sdm_cuti
DROP TABLE IF EXISTS `tbl_sdm_cuti`;
CREATE TABLE IF NOT EXISTS `tbl_sdm_cuti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_karyawan` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_manajemen` int(11) DEFAULT 0,
  `id_kategori` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` date DEFAULT '0000-00-00',
  `tgl_keluar` date DEFAULT '0000-00-00',
  `keterangan` text DEFAULT NULL COMMENT 'Alasan cuti karyawan',
  `no_surat` varchar(100) DEFAULT NULL,
  `ttd` varchar(100) DEFAULT NULL,
  `file_name` varchar(100) DEFAULT NULL,
  `file_type` varchar(25) DEFAULT NULL,
  `file_ext` varchar(10) DEFAULT NULL,
  `catatan` text DEFAULT NULL COMMENT 'Catatan dari manajemen HR',
  `status` enum('0','1','2') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Untuk menyimpan tabel pengajuan cuti karyawan';

-- Dumping data for table esensiaco_medkit_dev.tbl_sdm_cuti: ~0 rows (approximately)
DELETE FROM `tbl_sdm_cuti`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_sdm_surat_krj
DROP TABLE IF EXISTS `tbl_sdm_surat_krj`;
CREATE TABLE IF NOT EXISTS `tbl_sdm_surat_krj` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_karyawan` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) NOT NULL DEFAULT 0,
  `id_pimpinan` int(11) NOT NULL DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` date DEFAULT '0000-00-00',
  `tgl_keluar` date DEFAULT '0000-00-00',
  `kode` varchar(50) DEFAULT NULL,
  `judul` varchar(160) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `tipe` enum('0','1','2') DEFAULT '0',
  `status` enum('0','1') DEFAULT '0',
  `status_acc` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_sdm_surat_krj: ~0 rows (approximately)
DELETE FROM `tbl_sdm_surat_krj`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_sdm_surat_tgs
DROP TABLE IF EXISTS `tbl_sdm_surat_tgs`;
CREATE TABLE IF NOT EXISTS `tbl_sdm_surat_tgs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_karyawan` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) NOT NULL DEFAULT 0,
  `id_pimpinan` int(11) NOT NULL DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` date DEFAULT '0000-00-00',
  `tgl_keluar` date DEFAULT '0000-00-00',
  `kode` varchar(50) DEFAULT NULL,
  `judul` varchar(160) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `tipe` enum('0','1','2') DEFAULT '0',
  `status` enum('0','1') DEFAULT '0',
  `status_acc` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_sdm_surat_tgs: ~0 rows (approximately)
DELETE FROM `tbl_sdm_surat_tgs`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_sdm_surat_tgs_kary
DROP TABLE IF EXISTS `tbl_sdm_surat_tgs_kary`;
CREATE TABLE IF NOT EXISTS `tbl_sdm_surat_tgs_kary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT 0,
  `id_surat_tgs` int(11) DEFAULT 0,
  `id_karyawan` int(11) DEFAULT 0,
  `nik` varchar(25) DEFAULT NULL,
  `nama` varchar(160) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_sdm_surat_tgs_kary_tbl_sdm_surat_tgs` (`id_surat_tgs`),
  CONSTRAINT `FK_tbl_sdm_surat_tgs_kary_tbl_sdm_surat_tgs` FOREIGN KEY (`id_surat_tgs`) REFERENCES `tbl_sdm_surat_tgs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_sdm_surat_tgs_kary: ~0 rows (approximately)
DELETE FROM `tbl_sdm_surat_tgs_kary`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_sessions_back
DROP TABLE IF EXISTS `tbl_sessions_back`;
CREATE TABLE IF NOT EXISTS `tbl_sessions_back` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `date_added` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT 0,
  `user_data` longblob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_sessions_back: ~0 rows (approximately)
DELETE FROM `tbl_sessions_back`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_sessions_front
DROP TABLE IF EXISTS `tbl_sessions_front`;
CREATE TABLE IF NOT EXISTS `tbl_sessions_front` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT 0,
  `user_data` longblob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_sessions_front: ~0 rows (approximately)
DELETE FROM `tbl_sessions_front`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_beli
DROP TABLE IF EXISTS `tbl_trans_beli`;
CREATE TABLE IF NOT EXISTS `tbl_trans_beli` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_app` int(11) DEFAULT 0,
  `id_penerima` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT NULL,
  `tgl_modif` datetime DEFAULT NULL,
  `tgl_bayar` date DEFAULT '0000-00-00',
  `tgl_masuk` date DEFAULT '0000-00-00',
  `tgl_keluar` date DEFAULT '0000-00-00',
  `id_supplier` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_po` int(11) DEFAULT NULL,
  `no_nota` varchar(160) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `no_po` varchar(160) DEFAULT NULL,
  `supplier` varchar(160) DEFAULT NULL,
  `jml_total` decimal(32,2) DEFAULT 0.00,
  `disk1` decimal(32,2) DEFAULT 0.00,
  `disk2` decimal(32,2) DEFAULT 0.00,
  `disk3` decimal(32,2) DEFAULT 0.00,
  `jml_potongan` decimal(32,2) DEFAULT 0.00,
  `jml_retur` decimal(32,2) DEFAULT 0.00,
  `jml_diskon` decimal(32,2) DEFAULT 0.00,
  `jml_biaya` decimal(32,2) DEFAULT 0.00,
  `jml_ongkir` decimal(32,2) DEFAULT 0.00,
  `jml_subtotal` decimal(32,2) DEFAULT 0.00,
  `jml_dpp` decimal(32,2) DEFAULT 0.00,
  `ppn` int(11) DEFAULT 0,
  `jml_ppn` decimal(32,2) DEFAULT 0.00,
  `jml_gtotal` decimal(32,2) DEFAULT 0.00,
  `jml_bayar` decimal(32,2) DEFAULT 0.00,
  `jml_kembali` decimal(32,2) DEFAULT 0.00,
  `jml_kurang` decimal(32,2) DEFAULT 0.00,
  `status_bayar` int(11) DEFAULT NULL,
  `status_nota` int(11) DEFAULT 0,
  `status_ppn` enum('0','1','2') DEFAULT NULL,
  `status_retur` enum('0','1') DEFAULT NULL,
  `status_penerimaan` enum('0','1','2','3') DEFAULT '0',
  `metode_bayar` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `status_jurnal` enum('0','1') DEFAULT '0',
  `status_hps` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idSupplier` (`id_supplier`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_beli: ~0 rows (approximately)
DELETE FROM `tbl_trans_beli`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_beli_det
DROP TABLE IF EXISTS `tbl_trans_beli_det`;
CREATE TABLE IF NOT EXISTS `tbl_trans_beli_det` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT 0,
  `id_pembelian` int(11) NOT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `id_satuan` int(11) DEFAULT NULL,
  `tgl_simpan` datetime DEFAULT NULL,
  `tgl_terima` date DEFAULT NULL,
  `tgl_ed` date DEFAULT '0000-00-00',
  `no_nota` varchar(50) DEFAULT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `kode_batch` varchar(50) DEFAULT NULL,
  `produk` varchar(160) DEFAULT NULL,
  `jml` decimal(10,2) DEFAULT NULL,
  `jml_satuan` int(11) DEFAULT NULL,
  `jml_diterima` int(11) DEFAULT 0,
  `jml_retur` int(11) DEFAULT 0,
  `satuan` varchar(160) DEFAULT NULL,
  `satuan_retur` varchar(160) DEFAULT NULL,
  `keterangan` varchar(160) DEFAULT NULL,
  `harga` decimal(32,2) DEFAULT NULL,
  `disk1` decimal(10,2) DEFAULT NULL,
  `disk2` decimal(10,2) DEFAULT NULL,
  `disk3` decimal(10,2) DEFAULT NULL,
  `diskon` decimal(32,2) DEFAULT NULL,
  `potongan` decimal(32,2) DEFAULT NULL,
  `subtotal` decimal(32,2) DEFAULT NULL,
  `status_brg` int(11) DEFAULT NULL,
  `sp` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idBarang` (`id_produk`),
  KEY `FK_tbl_trans_beli_det_tbl_trans_beli` (`id_pembelian`),
  CONSTRAINT `FK_tbl_trans_beli_det_tbl_trans_beli` FOREIGN KEY (`id_pembelian`) REFERENCES `tbl_trans_beli` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_beli_det: ~0 rows (approximately)
DELETE FROM `tbl_trans_beli_det`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_beli_plat
DROP TABLE IF EXISTS `tbl_trans_beli_plat`;
CREATE TABLE IF NOT EXISTS `tbl_trans_beli_plat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_platform` int(11) NOT NULL,
  `id_pembelian` int(11) NOT NULL,
  `tgl_simpan` datetime NOT NULL,
  `platform` varchar(160) NOT NULL,
  `keterangan` varchar(160) NOT NULL,
  `nominal` decimal(32,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `no_nota` (`id_pembelian`),
  CONSTRAINT `FK_tbl_trans_beli_plat_tbl_trans_beli` FOREIGN KEY (`id_pembelian`) REFERENCES `tbl_trans_beli` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_beli_plat: ~0 rows (approximately)
DELETE FROM `tbl_trans_beli_plat`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_beli_po
DROP TABLE IF EXISTS `tbl_trans_beli_po`;
CREATE TABLE IF NOT EXISTS `tbl_trans_beli_po` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_app` int(11) DEFAULT 0,
  `id_penerima` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT NULL,
  `tgl_modif` datetime DEFAULT NULL,
  `tgl_bayar` date DEFAULT NULL,
  `tgl_masuk` date DEFAULT NULL,
  `tgl_keluar` date DEFAULT NULL,
  `id_supplier` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `no_nota` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `supplier` varchar(160) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `pengiriman` text DEFAULT NULL,
  `status_nota` int(11) DEFAULT 0,
  `status_retur` enum('0','1') DEFAULT NULL,
  `status_penerimaan` enum('0','1','2','3') DEFAULT NULL,
  `metode_bayar` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `status_jurnal` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idSupplier` (`id_supplier`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_beli_po: ~0 rows (approximately)
DELETE FROM `tbl_trans_beli_po`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_beli_po_det
DROP TABLE IF EXISTS `tbl_trans_beli_po_det`;
CREATE TABLE IF NOT EXISTS `tbl_trans_beli_po_det` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT 0,
  `id_pembelian` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `id_satuan` int(11) NOT NULL,
  `tgl_simpan` datetime NOT NULL,
  `no_nota` varchar(50) DEFAULT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `produk` varchar(160) DEFAULT NULL,
  `jml` int(11) DEFAULT NULL,
  `jml_satuan` int(11) DEFAULT NULL,
  `satuan` varchar(160) DEFAULT NULL,
  `keterangan` varchar(160) DEFAULT NULL,
  `keterangan_itm` text DEFAULT NULL,
  `status` enum('0','1') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idBarang` (`id_produk`),
  KEY `FK_tbl_trans_beli_po_det_tbl_trans_beli_po` (`id_pembelian`),
  CONSTRAINT `FK_tbl_trans_beli_po_det_tbl_trans_beli_po` FOREIGN KEY (`id_pembelian`) REFERENCES `tbl_trans_beli_po` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_beli_po_det: ~0 rows (approximately)
DELETE FROM `tbl_trans_beli_po_det`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_jual
DROP TABLE IF EXISTS `tbl_trans_jual`;
CREATE TABLE IF NOT EXISTS `tbl_trans_jual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_sales` int(11) NOT NULL,
  `id_app` int(11) NOT NULL,
  `id_promo` int(11) DEFAULT NULL,
  `id_lokasi` int(11) DEFAULT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `id_gudang` int(11) DEFAULT NULL,
  `no_nota` varchar(50) NOT NULL,
  `tgl_simpan` datetime DEFAULT NULL,
  `tgl_modif` datetime DEFAULT NULL,
  `kode_nota_dpn` varchar(50) DEFAULT NULL,
  `kode_nota_blk` varchar(50) DEFAULT NULL,
  `kode_fp` varchar(50) DEFAULT NULL,
  `tgl_bayar` date DEFAULT '0000-00-00',
  `tgl_masuk` date DEFAULT '0000-00-00',
  `tgl_keluar` date DEFAULT '0000-00-00',
  `platform` varchar(160) DEFAULT NULL,
  `jml_total` decimal(32,2) DEFAULT 0.00,
  `jml_biaya` decimal(32,2) DEFAULT 0.00,
  `jml_ongkir` decimal(32,2) DEFAULT 0.00,
  `jml_retur` decimal(32,2) DEFAULT 0.00,
  `diskon` decimal(32,2) DEFAULT 0.00,
  `jml_diskon` decimal(32,2) DEFAULT 0.00,
  `jml_subtotal` decimal(32,2) DEFAULT 0.00,
  `ppn` int(11) DEFAULT 0,
  `jml_ppn` decimal(32,2) DEFAULT 0.00,
  `jml_gtotal` decimal(32,2) DEFAULT 0.00,
  `jml_bayar` decimal(32,2) DEFAULT 0.00,
  `jml_kembali` decimal(32,2) DEFAULT 0.00,
  `jml_kurang` decimal(32,2) DEFAULT 0.00,
  `disk1` decimal(32,2) DEFAULT 0.00,
  `jml_disk1` decimal(32,2) DEFAULT 0.00,
  `disk2` decimal(32,2) DEFAULT 0.00,
  `jml_disk2` decimal(32,2) DEFAULT 0.00,
  `disk3` decimal(32,2) DEFAULT 0.00,
  `jml_disk3` decimal(32,2) DEFAULT 0.00,
  `keterangan` text DEFAULT NULL,
  `breadcrump` text DEFAULT NULL,
  `anamnesa` text DEFAULT NULL,
  `metode_bayar` int(11) DEFAULT NULL,
  `status` enum('0','1','2','3','4') DEFAULT '0' COMMENT '\r\n1=pos\r\n2=rajal\r\n3=ranap',
  `status_nota` int(11) DEFAULT NULL COMMENT '1=anamnesa\r\n2=pemeriksaan\r\n3=tindakan\r\n4=obat\r\n5=dokter\r\n6=pembayaran\r\n7=finish',
  `status_ppn` enum('0','1') DEFAULT '0',
  `status_bayar` enum('0','1','2') DEFAULT '0',
  `status_retur` enum('0','1','2') DEFAULT '0',
  `status_jurnal` enum('0','1') DEFAULT '0',
  `status_grosir` enum('0','1') DEFAULT '0',
  `status_lap` enum('0','1') DEFAULT '1',
  `status_pjk` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `no_nota` (`no_nota`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_jual: ~0 rows (approximately)
DELETE FROM `tbl_trans_jual`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_jual_det
DROP TABLE IF EXISTS `tbl_trans_jual_det`;
CREATE TABLE IF NOT EXISTS `tbl_trans_jual_det` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_penjualan` int(11) DEFAULT NULL,
  `id_item` int(11) DEFAULT NULL,
  `id_satuan` int(11) DEFAULT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `tgl_simpan` datetime DEFAULT NULL,
  `no_nota` varchar(50) DEFAULT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `produk` varchar(256) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `harga` decimal(32,2) DEFAULT NULL,
  `harga_pokok` decimal(32,2) DEFAULT NULL,
  `disk1` decimal(32,2) DEFAULT NULL,
  `disk2` decimal(32,2) DEFAULT NULL,
  `disk3` decimal(32,2) DEFAULT NULL,
  `jml` int(6) DEFAULT NULL,
  `jml_satuan` int(6) DEFAULT NULL,
  `diskon` decimal(32,2) DEFAULT NULL,
  `potongan` decimal(32,2) DEFAULT NULL,
  `subtotal` decimal(32,2) DEFAULT NULL,
  `status_app` enum('0','1') DEFAULT NULL,
  `status_hrg` int(11) DEFAULT NULL,
  `status_brg` enum('0','1') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_penjualan` (`id_penjualan`),
  CONSTRAINT `FK_tbl_trans_jual_det_tbl_trans_jual` FOREIGN KEY (`id_penjualan`) REFERENCES `tbl_trans_jual` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_jual_det: ~0 rows (approximately)
DELETE FROM `tbl_trans_jual_det`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_jual_diskon
DROP TABLE IF EXISTS `tbl_trans_jual_diskon`;
CREATE TABLE IF NOT EXISTS `tbl_trans_jual_diskon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_penjualan` int(11) NOT NULL DEFAULT 0,
  `id_pelanggan` int(11) NOT NULL DEFAULT 0,
  `tgl_simpan` datetime NOT NULL,
  `no_nota` varchar(50) NOT NULL,
  `kode` varchar(50) NOT NULL,
  `disk1` decimal(10,2) NOT NULL,
  `disk2` decimal(10,2) NOT NULL,
  `disk3` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_penjualan` (`id_penjualan`),
  CONSTRAINT `FK_tbl_trans_jual_diskon_tbl_trans_jual` FOREIGN KEY (`id_penjualan`) REFERENCES `tbl_trans_jual` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_jual_diskon: ~0 rows (approximately)
DELETE FROM `tbl_trans_jual_diskon`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_jual_hist
DROP TABLE IF EXISTS `tbl_trans_jual_hist`;
CREATE TABLE IF NOT EXISTS `tbl_trans_jual_hist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_penjualan` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `tgl_simpan` datetime NOT NULL,
  `no_nota` varchar(50) NOT NULL,
  `stok` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_penjualan` (`id_penjualan`),
  CONSTRAINT `FK_tbl_trans_jual_hist_tbl_trans_jual` FOREIGN KEY (`id_penjualan`) REFERENCES `tbl_trans_jual` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_jual_hist: ~0 rows (approximately)
DELETE FROM `tbl_trans_jual_hist`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_jual_pen
DROP TABLE IF EXISTS `tbl_trans_jual_pen`;
CREATE TABLE IF NOT EXISTS `tbl_trans_jual_pen` (
  `no_nota` varchar(50) NOT NULL,
  `id_app` int(11) NOT NULL,
  `tgl_simpan` datetime NOT NULL,
  `tgl_modif` datetime NOT NULL,
  `kode_nota_dpn` varchar(50) DEFAULT NULL,
  `kode_nota_blk` varchar(50) DEFAULT NULL,
  `kode_fp` varchar(50) NOT NULL,
  `id_promo` int(11) NOT NULL,
  `tgl_bayar` date NOT NULL,
  `tgl_masuk` date NOT NULL,
  `tgl_keluar` date NOT NULL,
  `platform` varchar(160) NOT NULL,
  `jml_total` decimal(32,2) NOT NULL,
  `jml_diskon` decimal(32,2) NOT NULL,
  `jml_biaya` decimal(32,2) NOT NULL,
  `jml_subtotal` decimal(32,2) NOT NULL,
  `ppn` int(11) NOT NULL,
  `jml_ppn` decimal(32,2) NOT NULL,
  `jml_gtotal` decimal(32,2) NOT NULL,
  `jml_retur` decimal(32,2) NOT NULL,
  `jml_bayar` decimal(32,2) NOT NULL,
  `jml_kembali` decimal(32,2) NOT NULL,
  `jml_kurang` decimal(32,2) NOT NULL,
  `jml_ongkir` decimal(32,2) NOT NULL,
  `disk1` decimal(32,2) NOT NULL,
  `jml_disk1` decimal(32,2) NOT NULL,
  `disk2` decimal(32,2) NOT NULL,
  `jml_disk2` decimal(32,2) NOT NULL,
  `disk3` decimal(32,2) NOT NULL,
  `jml_disk3` decimal(32,2) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `id_sales` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_pelanggan` int(11) NOT NULL,
  `id_gudang` int(11) NOT NULL,
  `keterangan` text NOT NULL,
  `metode_bayar` enum('0','1','2') NOT NULL,
  `status_nota` enum('0','1','2','3') NOT NULL,
  `status_ppn` enum('0','1') NOT NULL,
  `status_bayar` enum('0','1','2') NOT NULL,
  `status_retur` enum('0','1','2') NOT NULL,
  PRIMARY KEY (`no_nota`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_jual_pen: ~0 rows (approximately)
DELETE FROM `tbl_trans_jual_pen`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_jual_pen_det
DROP TABLE IF EXISTS `tbl_trans_jual_pen_det`;
CREATE TABLE IF NOT EXISTS `tbl_trans_jual_pen_det` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_satuan` int(11) NOT NULL,
  `tgl_simpan` datetime NOT NULL,
  `no_nota` varchar(50) NOT NULL,
  `kode` varchar(50) NOT NULL,
  `produk` varchar(256) NOT NULL,
  `satuan` varchar(50) NOT NULL,
  `keterangan` text NOT NULL,
  `harga` decimal(32,2) NOT NULL,
  `disk1` decimal(32,2) NOT NULL,
  `disk2` decimal(32,2) NOT NULL,
  `disk3` decimal(32,2) NOT NULL,
  `jml` int(6) NOT NULL,
  `jml_satuan` int(6) NOT NULL,
  `diskon` decimal(32,2) NOT NULL,
  `potongan` decimal(32,2) NOT NULL,
  `subtotal` decimal(32,2) NOT NULL,
  `status_app` enum('0','1') NOT NULL,
  `status_hrg` int(11) NOT NULL,
  `status_brg` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_jual_pen_det_tbl_trans_jual_pen` (`no_nota`),
  CONSTRAINT `FK_tbl_trans_jual_pen_det_tbl_trans_jual_pen` FOREIGN KEY (`no_nota`) REFERENCES `tbl_trans_jual_pen` (`no_nota`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_jual_pen_det: ~0 rows (approximately)
DELETE FROM `tbl_trans_jual_pen_det`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_jual_plat
DROP TABLE IF EXISTS `tbl_trans_jual_plat`;
CREATE TABLE IF NOT EXISTS `tbl_trans_jual_plat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_penjualan` int(11) NOT NULL DEFAULT 0,
  `id_platform` int(11) NOT NULL,
  `tgl_simpan` datetime NOT NULL,
  `no_nota` varchar(50) NOT NULL,
  `platform` varchar(160) NOT NULL,
  `keterangan` varchar(160) DEFAULT NULL,
  `nominal` decimal(32,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_penjualan` (`id_penjualan`),
  CONSTRAINT `FK_tbl_trans_jual_plat_tbl_trans_jual` FOREIGN KEY (`id_penjualan`) REFERENCES `tbl_trans_jual` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_jual_plat: ~0 rows (approximately)
DELETE FROM `tbl_trans_jual_plat`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_konsul
DROP TABLE IF EXISTS `tbl_trans_konsul`;
CREATE TABLE IF NOT EXISTS `tbl_trans_konsul` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) DEFAULT 0,
  `id_pasien` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `id_medcheck` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `judul` varchar(60) DEFAULT NULL,
  `posting` text DEFAULT NULL,
  `status` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_konsul: ~0 rows (approximately)
DELETE FROM `tbl_trans_konsul`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_konsul_dokter
DROP TABLE IF EXISTS `tbl_trans_konsul_dokter`;
CREATE TABLE IF NOT EXISTS `tbl_trans_konsul_dokter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_konsul` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_konsul_dokter_tbl_trans_konsul` (`id_konsul`),
  CONSTRAINT `FK_tbl_trans_konsul_dokter_tbl_trans_konsul` FOREIGN KEY (`id_konsul`) REFERENCES `tbl_trans_konsul` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_konsul_dokter: ~0 rows (approximately)
DELETE FROM `tbl_trans_konsul_dokter`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck
DROP TABLE IF EXISTS `tbl_trans_medcheck`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_app` int(11) DEFAULT 1,
  `id_user` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `id_nurse` int(11) DEFAULT 0,
  `id_analis` int(11) DEFAULT 0,
  `id_farmasi` int(11) DEFAULT 0,
  `id_pasien` int(11) DEFAULT 0,
  `id_instansi` int(11) DEFAULT 0,
  `id_poli` int(11) DEFAULT 0,
  `id_dft` int(11) DEFAULT 0 COMMENT 'ID yang diambil dari tbl_pendaftaran kolom id',
  `id_ant` int(11) DEFAULT 0,
  `id_kasir` int(11) DEFAULT 0,
  `id_icd` int(11) DEFAULT 0,
  `id_icd10` int(11) DEFAULT 0,
  `id_encounter` text DEFAULT NULL,
  `id_condition` text DEFAULT NULL,
  `id_post_location` text DEFAULT NULL,
  `tgl_simpan` datetime DEFAULT NULL,
  `tgl_modif` datetime DEFAULT NULL,
  `tgl_masuk` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_periksa` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_periksa_lab` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_periksa_lab_keluar` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_periksa_rad` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_periksa_rad_kirim` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_periksa_rad_baca` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_periksa_rad_keluar` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_periksa_pen` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_resep_msk` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_resep_klr` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_resep_byr` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_resep_trm` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_ranap` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_ranap_keluar` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_keluar` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_bayar` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_ttd` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_lahir` date DEFAULT '0000-00-00',
  `no_rm` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `no_akun` varchar(50) DEFAULT NULL,
  `no_nota` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `dokter` varchar(160) DEFAULT NULL,
  `dokter_nik` varchar(160) DEFAULT NULL,
  `poli` varchar(160) DEFAULT NULL,
  `pasien` varchar(160) DEFAULT NULL,
  `pasien_alamat` varchar(160) DEFAULT NULL,
  `pasien_nik` varchar(160) DEFAULT NULL,
  `keluhan` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `ttv` text DEFAULT NULL,
  `ttv_st` varchar(50) DEFAULT NULL,
  `ttv_bb` varchar(50) DEFAULT NULL,
  `ttv_tb` varchar(50) DEFAULT NULL,
  `ttv_td` varchar(50) DEFAULT NULL,
  `ttv_sistole` varchar(50) DEFAULT NULL,
  `ttv_diastole` varchar(50) DEFAULT NULL,
  `ttv_nadi` varchar(50) DEFAULT NULL,
  `ttv_laju` varchar(50) DEFAULT NULL,
  `ttv_saturasi` varchar(50) DEFAULT NULL,
  `ttv_skala` varchar(50) DEFAULT NULL,
  `ttd_obat` varchar(160) DEFAULT NULL,
  `diagnosa` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `anamnesa` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `pemeriksaan` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `program` text DEFAULT NULL,
  `alergi` text DEFAULT NULL,
  `metode` int(11) DEFAULT 0,
  `platform` int(11) DEFAULT 0,
  `jml_total` decimal(10,2) DEFAULT 0.00,
  `jml_ongkir` decimal(10,2) DEFAULT 0.00,
  `jml_dp` decimal(10,2) DEFAULT 0.00,
  `jml_diskon` decimal(10,2) DEFAULT 0.00,
  `diskon` decimal(10,2) DEFAULT 0.00,
  `jml_potongan` decimal(10,2) DEFAULT 0.00,
  `jml_potongan_poin` decimal(10,2) DEFAULT 0.00,
  `jml_subtotal` decimal(10,2) DEFAULT 0.00,
  `jml_ppn` decimal(10,2) DEFAULT 0.00,
  `ppn` decimal(10,2) DEFAULT 0.00,
  `jml_gtotal` decimal(10,2) DEFAULT 0.00,
  `jml_bayar` decimal(10,2) DEFAULT 0.00,
  `jml_kembali` decimal(10,2) DEFAULT 0.00,
  `jml_kurang` decimal(10,2) DEFAULT 0.00,
  `jml_poin` decimal(10,2) DEFAULT 0.00,
  `jml_poin_nom` decimal(10,2) DEFAULT 0.00,
  `tipe` int(11) DEFAULT 0 COMMENT '2=rajal;3=ranap;',
  `tipe_bayar` int(11) DEFAULT 0 COMMENT '0 = tidak ada;\r\n1 = UMUM;\r\n2 = ASURANSI;\r\n3 = BPJS;',
  `status` int(11) DEFAULT 0 COMMENT '1=anamnesa;\r\n2=tindakan;\r\n3=obat;\r\n4=laborat;\r\n5=dokter;\r\n6=pembayaran;\r\n7=finish',
  `status_bayar` int(11) DEFAULT 0 COMMENT '0=belum;\r\n1=lunas;\r\n2=kurang;',
  `status_nota` int(11) DEFAULT 0 COMMENT '0=pend;\r\n1=proses;\r\n2=finish;\r\n3=batal',
  `status_hps` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT '0',
  `status_pos` enum('0','1','2') DEFAULT '0',
  `status_periksa` enum('0','1','2') CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT '0',
  `status_resep` enum('0','1','2') DEFAULT '0' COMMENT '1=Non-racikan\r\n2=Racikan',
  `sp` enum('0','1','2') DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_apres
DROP TABLE IF EXISTS `tbl_trans_medcheck_apres`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_apres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) DEFAULT 0,
  `id_medcheck_det` int(11) DEFAULT 0,
  `id_item` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `id_analis` int(11) DEFAULT 0,
  `tgl_simpan` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `item` varchar(168) DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  `jml` decimal(10,2) DEFAULT NULL,
  `apres_perc` decimal(10,2) DEFAULT NULL,
  `apres_nom` decimal(10,2) DEFAULT NULL,
  `apres_subtotal` decimal(10,2) DEFAULT NULL,
  `apres_tipe` int(11) DEFAULT 0 COMMENT '1=persen\r\n2=nominal',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_medcheck_apres_tbl_trans_medcheck` (`id_medcheck`),
  CONSTRAINT `FK_tbl_trans_medcheck_apres_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_apres: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_apres`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_ass_fisik
DROP TABLE IF EXISTS `tbl_trans_medcheck_ass_fisik`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_ass_fisik` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) NOT NULL DEFAULT 0,
  `id_pasien` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_analis` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_keluar` datetime DEFAULT '0000-00-00 00:00:00',
  `no_lab` varchar(50) DEFAULT NULL,
  `no_sample` varchar(50) DEFAULT NULL,
  `ket` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '0=pend\\\\\\\\r\\\\\\\\n1=proses\\\\\\\\r\\\\\\\\n2=diterima\\\\\\\\r\\\\\\\\n3=ditolak\\\\\\\\r\\\\\\\\n4=farmasi\\\\\\\\r\\\\\\\\n5=farmasi_proses',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_tbl_trans_medcheck_ass_fisik_tbl_trans_medcheck` (`id_medcheck`),
  CONSTRAINT `FK_tbl_trans_medcheck_ass_fisik_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Untuk menyimpan data form fisik';

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_ass_fisik: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_ass_fisik`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_ass_fisik_hsl
DROP TABLE IF EXISTS `tbl_trans_medcheck_ass_fisik_hsl`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_ass_fisik_hsl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(10) DEFAULT 0,
  `id_medcheck_ass` int(10) DEFAULT 0,
  `id_item` int(10) DEFAULT 0,
  `id_user` int(10) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `item_name` varchar(100) DEFAULT NULL,
  `item_value` int(10) DEFAULT 0,
  `item_value2` varchar(160) DEFAULT NULL,
  `item_value3` varchar(50) DEFAULT NULL,
  `tipe` int(10) DEFAULT 0,
  `status_posisi` enum('N','L','R') DEFAULT 'N',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_medcheck_ass_fisik_hsl_tbl_trans_medcheck_ass_fisik` (`id_medcheck_ass`),
  CONSTRAINT `FK_tbl_trans_medcheck_ass_fisik_hsl_tbl_trans_medcheck_ass_fisik` FOREIGN KEY (`id_medcheck_ass`) REFERENCES `tbl_trans_medcheck_ass_fisik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Untuk menyimpan hasil pemeriksaan assesment';

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_ass_fisik_hsl: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_ass_fisik_hsl`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_ass_ranap
DROP TABLE IF EXISTS `tbl_trans_medcheck_ass_ranap`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_ass_ranap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) NOT NULL DEFAULT 0,
  `id_pasien` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_keluar` datetime DEFAULT '0000-00-00 00:00:00',
  `no_surat` varchar(50) DEFAULT NULL,
  `keluhan` text DEFAULT NULL,
  `riwayat_skg` text DEFAULT NULL,
  `riwayat_klg` text DEFAULT NULL,
  `penyakit` text DEFAULT NULL,
  `ket_ranap` text DEFAULT NULL,
  `pemeriksaan` text DEFAULT NULL,
  `askep_masalah` text DEFAULT NULL,
  `askep_tujuan` text DEFAULT NULL,
  `skala_nyeri` decimal(10,2) DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '0=pend\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n1=proses\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n2=diterima\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n3=ditolak\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n4=farmasi\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n5=farmasi_proses',
  `status_rnp` enum('0','1') DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_tbl_trans_medcheck_ass_ranap_tbl_trans_medcheck` (`id_medcheck`),
  CONSTRAINT `FK_tbl_trans_medcheck_ass_ranap_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Untuk menyimpan data form fisik';

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_ass_ranap: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_ass_ranap`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_ass_ranap_obat
DROP TABLE IF EXISTS `tbl_trans_medcheck_ass_ranap_obat`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_ass_ranap_obat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ass_ranap` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `param` varchar(50) DEFAULT NULL,
  `param_nilai` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_Ass_ranap` (`id_ass_ranap`),
  CONSTRAINT `FK_Ass_ranap` FOREIGN KEY (`id_ass_ranap`) REFERENCES `tbl_trans_medcheck_ass_ranap` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Untuk menyimpan riwayat minum obat pada assesment rawat inap';

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_ass_ranap_obat: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_ass_ranap_obat`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_ass_rnp
DROP TABLE IF EXISTS `tbl_trans_medcheck_ass_rnp`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_ass_rnp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) NOT NULL DEFAULT 0,
  `id_pasien` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_analis` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_keluar` datetime DEFAULT '0000-00-00 00:00:00',
  `no_lab` varchar(50) DEFAULT NULL,
  `no_sample` varchar(50) DEFAULT NULL,
  `ket` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '0=pend\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n1=proses\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n2=diterima\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n3=ditolak\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n4=farmasi\\\\\\\\\\\\\\\\r\\\\\\\\\\\\\\\\n5=farmasi_proses',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Untuk menyimpan data form fisik';

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_ass_rnp: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_ass_rnp`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_det
DROP TABLE IF EXISTS `tbl_trans_medcheck_det`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_det` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) NOT NULL DEFAULT 0,
  `id_resep` int(11) NOT NULL DEFAULT 0,
  `id_resep_det` int(11) NOT NULL DEFAULT 0,
  `id_resep_det_rc` int(11) DEFAULT 0,
  `id_lab` int(11) NOT NULL DEFAULT 0,
  `id_lab_kat` int(11) NOT NULL DEFAULT 0,
  `id_rad` int(11) NOT NULL DEFAULT 0,
  `id_mcu` int(11) NOT NULL DEFAULT 0,
  `id_item` int(11) DEFAULT 0,
  `id_item_kat` int(11) DEFAULT 0,
  `id_item_sat` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `id_perawat` int(11) DEFAULT 0,
  `id_analis` int(11) DEFAULT 0,
  `id_radiografer` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_baca` datetime DEFAULT '0000-00-00 00:00:00',
  `kode` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `item` varchar(160) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `keterangan` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `jml` decimal(10,2) DEFAULT 0.00,
  `jml_resep` decimal(10,2) DEFAULT 0.00,
  `jml_satuan` decimal(10,2) DEFAULT 0.00,
  `satuan` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `file_rad` longtext DEFAULT NULL,
  `resep` longtext DEFAULT NULL,
  `kesan_rad` longtext DEFAULT NULL,
  `hasil_rad` longtext DEFAULT NULL,
  `hasil_lab` text DEFAULT NULL,
  `dosis` text DEFAULT NULL,
  `dosis_ket` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT 0.00,
  `disk1` decimal(10,2) DEFAULT 0.00,
  `disk2` decimal(10,2) DEFAULT 0.00,
  `disk3` decimal(10,2) DEFAULT 0.00,
  `diskon` decimal(10,2) DEFAULT 0.00,
  `potongan` decimal(10,2) DEFAULT 0.00,
  `potongan_poin` decimal(10,2) DEFAULT 0.00,
  `subtotal` decimal(10,2) DEFAULT 0.00,
  `status` int(11) DEFAULT 0 COMMENT '0=null\r\n1=obat\r\n2=lab\r\n3=tindakan\r\n4=radiologi',
  `status_ctk` enum('0','1') DEFAULT '0',
  `status_hsl` enum('0','1') DEFAULT '0',
  `status_hsl_lab` enum('0','1') DEFAULT '0',
  `status_hsl_rad` enum('0','1') DEFAULT '0',
  `status_baca` enum('0','1','2') DEFAULT '0' COMMENT '0=belum;\\r\\n1=sudah;',
  `status_post` enum('0','1','2') DEFAULT '0' COMMENT '0=pend\r\n1=posted\r\n2=canceled',
  `status_remun` enum('0','1','2') DEFAULT '0',
  `status_pj` enum('0','1','2') DEFAULT '0' COMMENT 'Status Penjamin (UMUM, BPJS, dll)\r\n0=tidak\r\n1=ya',
  `status_rc` enum('0','1','2') DEFAULT '0',
  `status_rf` enum('0','1') DEFAULT '0',
  `status_pkt` enum('0','1') DEFAULT '0',
  `sp` enum('0','1','2') DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_medcheck_det_tbl_trans_medcheck` (`id_medcheck`),
  CONSTRAINT `FK_tbl_trans_medcheck_det_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_det: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_det`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_dokter
DROP TABLE IF EXISTS `tbl_trans_medcheck_dokter`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_dokter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `id_pasien` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `keterangan` text DEFAULT NULL,
  `status` enum('0','1') DEFAULT '0' COMMENT 'Menyimpan status dokter utama, jika 1 maka menandakan dokter utama, jika 0 merupakan raber',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_medcheck_dokter_tbl_trans_medcheck` (`id_medcheck`),
  CONSTRAINT `FK_tbl_trans_medcheck_dokter_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Untuk mengakomodasi fitur rawat bersama.\r\nTabel ini akan menyimpan id_dokter, id_medcheck.';

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_dokter: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_dokter`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_file
DROP TABLE IF EXISTS `tbl_trans_medcheck_file`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) NOT NULL DEFAULT 0,
  `id_medcheck_det` int(11) NOT NULL DEFAULT 0,
  `id_berkas` int(11) NOT NULL DEFAULT 0,
  `id_medcheck_rsm` int(11) NOT NULL DEFAULT 0,
  `id_pasien` int(11) NOT NULL DEFAULT 0,
  `id_rad` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) NOT NULL DEFAULT 0,
  `tgl_simpan` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `judul` varchar(160) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `file_name_ori` varchar(160) DEFAULT NULL,
  `file_name` varchar(160) DEFAULT NULL,
  `file_ext` varchar(160) DEFAULT NULL,
  `file_type` varchar(160) DEFAULT NULL,
  `file_base64` longtext DEFAULT NULL,
  `file_base64_foto` longtext DEFAULT NULL,
  `status` enum('0','1','2','3') DEFAULT '0' COMMENT '0=none;\\r\\n1=berkas unggah;\\r\\n2=resume;\\r\\n3=Bukti Bayar',
  `sp` enum('0','1','2') DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_medcheck` (`id_medcheck`),
  CONSTRAINT `FK_tbl_trans_medcheck_file_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_file: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_file`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_hist
DROP TABLE IF EXISTS `tbl_trans_medcheck_hist`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_hist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) NOT NULL DEFAULT 0,
  `id_item` int(11) NOT NULL DEFAULT 0,
  `id_item_kat` int(11) NOT NULL DEFAULT 0,
  `tgl_simpan` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `keterangan` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_medcheck_hist_tbl_trans_medcheck` (`id_medcheck`),
  CONSTRAINT `FK_tbl_trans_medcheck_hist_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_hist: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_hist`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_icd
DROP TABLE IF EXISTS `tbl_trans_medcheck_icd`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_icd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) DEFAULT 0,
  `id_medcheck_rm` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `id_icd` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `kode` varchar(50) DEFAULT NULL,
  `icd` text DEFAULT NULL,
  `diagnosa` text DEFAULT NULL,
  `diagnosa_en` text DEFAULT NULL,
  `status_icd` enum('0','1','2') DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK__tbl_trans_medcheck` (`id_medcheck`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Untuk menyimpan multiple ICD';

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_icd: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_icd`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_kamar
DROP TABLE IF EXISTS `tbl_trans_medcheck_kamar`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_kamar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) DEFAULT 0,
  `id_inform` int(11) DEFAULT 0,
  `id_kamar` int(11) DEFAULT 0,
  `id_pasien` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `kamar` varchar(50) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_medcheck_kamar_tbl_trans_medcheck` (`id_medcheck`),
  CONSTRAINT `FK_tbl_trans_medcheck_kamar_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Untuk Menyimpan Kamar perawatan per transaksi';

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_kamar: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_kamar`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_konsul
DROP TABLE IF EXISTS `tbl_trans_medcheck_konsul`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_konsul` (
  `id` int(11) NOT NULL,
  `id_medcheck` int(11) DEFAULT 0,
  `id_pasien` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `id_konsul` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `judul` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_konsul: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_konsul`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_kwi
DROP TABLE IF EXISTS `tbl_trans_medcheck_kwi`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_kwi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` date DEFAULT '0000-00-00',
  `dari` varchar(160) DEFAULT NULL,
  `nominal` decimal(10,2) DEFAULT 0.00,
  `ket` text DEFAULT NULL,
  `diagnosa` text DEFAULT NULL,
  `status_kwi` enum('1','2','3') DEFAULT '1' COMMENT '1=Kwitansi;\r\n2=DP\r\n3=TPP (Tarif Permintaan Pasien)',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_medcheck_kwi_tbl_trans_medcheck` (`id_medcheck`),
  CONSTRAINT `FK_tbl_trans_medcheck_kwi_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Untuk menyimpan kwitansi';

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_kwi: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_kwi`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_lab
DROP TABLE IF EXISTS `tbl_trans_medcheck_lab`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_lab` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) NOT NULL DEFAULT 0,
  `id_pasien` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_analis` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `id_dokter_kirim` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_keluar` datetime DEFAULT '0000-00-00 00:00:00',
  `no_lab` varchar(50) DEFAULT NULL,
  `no_sample` varchar(50) DEFAULT NULL,
  `ket` text DEFAULT NULL,
  `item` longtext DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '0=pend\\r\\n1=proses\\r\\n2=diterima\\r\\n3=ditolak\\r\\n4=farmasi\\r\\n5=farmasi_proses',
  `status_cvd` enum('0','1','2') DEFAULT '0' COMMENT '0=tidak\r\n1=rapid\r\n2=pcr',
  `status_normal` enum('0','1','2') DEFAULT '0',
  `status_duplo` enum('0','1','2') DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_tbl_trans_medcheck_lab_tbl_trans_medcheck` (`id_medcheck`) USING BTREE,
  CONSTRAINT `FK_tbl_trans_medcheck_lab_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_lab: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_lab`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_lab_ekg
DROP TABLE IF EXISTS `tbl_trans_medcheck_lab_ekg`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_lab_ekg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) NOT NULL DEFAULT 0,
  `id_pasien` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_analis` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_keluar` datetime DEFAULT '0000-00-00 00:00:00',
  `no_lab` varchar(160) DEFAULT NULL,
  `hsl_irama` varchar(160) DEFAULT NULL COMMENT 'Irama',
  `hsl_frek` varchar(160) DEFAULT NULL COMMENT 'Frekuensi',
  `hsl_axis` varchar(160) DEFAULT NULL COMMENT 'Axis',
  `hsl_pos` varchar(160) DEFAULT NULL COMMENT 'Posisi',
  `hsl_zona` varchar(160) DEFAULT NULL COMMENT 'Zona Transisi',
  `hsl_gelp` varchar(160) DEFAULT NULL COMMENT 'Gelombang P',
  `hsl_int` varchar(160) DEFAULT NULL COMMENT 'Interval P - R',
  `hsl_qrs` varchar(160) DEFAULT NULL COMMENT 'Kompleks QRS',
  `hsl_st` varchar(160) DEFAULT NULL COMMENT 'Segment ST',
  `hsl_gelt` varchar(160) DEFAULT NULL COMMENT 'Gelombang T',
  `hsl_gelu` varchar(160) DEFAULT NULL COMMENT 'Gelombang U',
  `hsl_lain` varchar(160) DEFAULT NULL COMMENT 'Lain-lain',
  `kesimpulan` text DEFAULT NULL,
  `status` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_tbl_trans_medcheck_lab_ekg_tbl_trans_medcheck` (`id_medcheck`),
  CONSTRAINT `FK_tbl_trans_medcheck_lab_ekg_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Untuk menyimpan data ekg';

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_lab_ekg: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_lab_ekg`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_lab_ekg_file
DROP TABLE IF EXISTS `tbl_trans_medcheck_lab_ekg_file`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_lab_ekg_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) DEFAULT 0,
  `id_lab_ekg` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `judul` varchar(160) DEFAULT NULL,
  `file_name` varchar(160) DEFAULT NULL,
  `file_name_orig` varchar(160) DEFAULT NULL,
  `file_ext` varchar(50) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `file_base64` longtext DEFAULT NULL,
  `sp` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_medcheck_lab_ekg_file_tbl_trans_medcheck_lab_ekg` (`id_lab_ekg`),
  CONSTRAINT `FK_tbl_trans_medcheck_lab_ekg_file_tbl_trans_medcheck_lab_ekg` FOREIGN KEY (`id_lab_ekg`) REFERENCES `tbl_trans_medcheck_lab_ekg` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_lab_ekg_file: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_lab_ekg_file`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_lab_hsl
DROP TABLE IF EXISTS `tbl_trans_medcheck_lab_hsl`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_lab_hsl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) DEFAULT 0,
  `id_medcheck_det` int(11) DEFAULT 0,
  `id_lab` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_item` int(11) DEFAULT 0,
  `id_item_ref` int(11) DEFAULT 0,
  `id_item_ref_ip` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `item_name` varchar(160) DEFAULT NULL,
  `item_satuan` varchar(100) DEFAULT NULL,
  `item_value` text DEFAULT NULL,
  `item_hasil` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `status_hsl_lab` enum('0','1') DEFAULT '0',
  `status_hsl_wrn` enum('0','1') DEFAULT '0',
  `sp` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_medcheck_lab_tbl_trans_medcheck` (`id_medcheck`),
  CONSTRAINT `FK_tbl_trans_medcheck_lab_hsl_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Untuk menyimpan nilai normal lab';

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_lab_hsl: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_lab_hsl`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_lab_spiro
DROP TABLE IF EXISTS `tbl_trans_medcheck_lab_spiro`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_lab_spiro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) NOT NULL DEFAULT 0,
  `id_pasien` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_analis` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_keluar` datetime DEFAULT '0000-00-00 00:00:00',
  `no_lab` varchar(50) DEFAULT NULL,
  `no_sample` varchar(50) DEFAULT NULL,
  `keluhan` varchar(160) DEFAULT NULL,
  `riwayat` varchar(160) DEFAULT NULL,
  `ref` varchar(160) DEFAULT NULL,
  `tb` int(11) DEFAULT NULL,
  `bb` int(11) DEFAULT NULL,
  `imt` int(11) DEFAULT NULL,
  `ket` text DEFAULT NULL,
  `anjuran` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '0=pend\\\\r\\\\n1=proses\\\\r\\\\n2=diterima\\\\r\\\\n3=ditolak\\\\r\\\\n4=farmasi\\\\r\\\\n5=farmasi_proses',
  `status_rokok` enum('0','1','2') DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_tbl_trans_medcheck_lab_spiro_tbl_trans_medcheck` (`id_medcheck`),
  CONSTRAINT `FK_tbl_trans_medcheck_lab_spiro_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Untuk menyimpan data spirometri';

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_lab_spiro: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_lab_spiro`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_lab_spiro_hsl
DROP TABLE IF EXISTS `tbl_trans_medcheck_lab_spiro_hsl`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_lab_spiro_hsl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(10) DEFAULT 0,
  `id_lab_spiro` int(10) DEFAULT 0,
  `id_lab_spiro_kat` int(10) DEFAULT 0,
  `id_user` int(10) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `item_name` varchar(100) DEFAULT NULL,
  `item_value` decimal(10,2) DEFAULT 0.00,
  `item_value2` decimal(10,2) DEFAULT 0.00,
  `item_value3` decimal(10,2) DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_medcheck_lab_spiro_hsl_tbl_trans_medcheck` (`id_medcheck`),
  KEY `FK_tbl_trans_medcheck_lab_spiro_hsl_tbl_trans_medcheck_lab_spiro` (`id_lab_spiro`),
  CONSTRAINT `FK_tbl_trans_medcheck_lab_spiro_hsl_tbl_trans_medcheck_lab_spiro` FOREIGN KEY (`id_lab_spiro`) REFERENCES `tbl_trans_medcheck_lab_spiro` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Untuk menyimpan hasil pemeriksaan spirometri';

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_lab_spiro_hsl: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_lab_spiro_hsl`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_mcu
DROP TABLE IF EXISTS `tbl_trans_medcheck_mcu`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_mcu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) NOT NULL DEFAULT 0,
  `id_pasien` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_keluar` datetime DEFAULT '0000-00-00 00:00:00',
  `no_mcu` varchar(50) DEFAULT NULL,
  `no_sample` varchar(50) DEFAULT NULL,
  `ket` text DEFAULT NULL,
  `item` longtext DEFAULT NULL,
  `status` int(11) DEFAULT 0 COMMENT '0=pend\\\\r\\\\n1=proses\\\\r\\\\n2=diterima\\\\r\\\\n3=ditolak\\\\r\\\\n4=farmasi\\\\r\\\\n5=farmasi_proses',
  `status_mcu` int(11) DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_tbl_trans_medcheck_mcu_tbl_trans_medcheck` (`id_medcheck`) USING BTREE,
  CONSTRAINT `FK_tbl_trans_medcheck_mcu_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_mcu: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_mcu`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_mcu_det
DROP TABLE IF EXISTS `tbl_trans_medcheck_mcu_det`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_mcu_det` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) DEFAULT 0,
  `id_mcu` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `param` varchar(160) DEFAULT NULL,
  `param_nilai` text DEFAULT NULL,
  `param_sat` text DEFAULT NULL,
  `param_sat2` text DEFAULT NULL,
  `status_bag` int(11) DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_tbl_trans_medcheck_mcu_det_tbl_trans_medcheck_mcu` (`id_mcu`) USING BTREE,
  CONSTRAINT `FK_tbl_trans_medcheck_mcu_det_tbl_trans_medcheck_mcu` FOREIGN KEY (`id_mcu`) REFERENCES `tbl_trans_medcheck_mcu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_mcu_det: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_mcu_det`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_pen_hrv
DROP TABLE IF EXISTS `tbl_trans_medcheck_pen_hrv`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_pen_hrv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) NOT NULL DEFAULT 0,
  `id_pasien` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_analis` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_keluar` datetime DEFAULT '0000-00-00 00:00:00',
  `no_lab` varchar(160) DEFAULT NULL,
  `hsl_mhr` varchar(160) DEFAULT NULL COMMENT 'Irama',
  `hsl_sdnn` varchar(160) DEFAULT NULL COMMENT 'Frekuensi',
  `hsl_rmssd` varchar(160) DEFAULT NULL COMMENT 'Axis',
  `hsl_psi` varchar(160) DEFAULT NULL COMMENT 'Posisi',
  `hsl_lhhf` varchar(160) DEFAULT NULL COMMENT 'Zona Transisi',
  `hsl_eb` varchar(160) DEFAULT NULL COMMENT 'Gelombang P',
  `hsl_ans` varchar(160) DEFAULT NULL COMMENT 'Interval P - R',
  `hsl_pi` varchar(160) DEFAULT NULL COMMENT 'Kompleks QRS',
  `hsl_es` varchar(160) DEFAULT NULL COMMENT 'Segment ST',
  `hsl_rrv` varchar(160) DEFAULT NULL COMMENT 'Gelombang T',
  `keterangan` text DEFAULT NULL,
  `status` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_tbl_trans_medcheck_lab_hrv_tbl_trans_medcheck` (`id_medcheck`),
  CONSTRAINT `FK_tbl_trans_medcheck_lab_hrv_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Untuk menyimpan pemeriksaan penunjang hrv';

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_pen_hrv: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_pen_hrv`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_plat
DROP TABLE IF EXISTS `tbl_trans_medcheck_plat`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_plat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) NOT NULL DEFAULT 0,
  `id_platform` int(11) NOT NULL,
  `tgl_simpan` datetime NOT NULL,
  `no_nota` varchar(50) DEFAULT NULL,
  `platform` varchar(160) DEFAULT NULL,
  `keterangan` varchar(160) DEFAULT NULL,
  `nominal` decimal(32,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_medcheck` (`id_medcheck`),
  CONSTRAINT `FK_tbl_trans_medcheck_plat_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_plat: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_plat`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_rad
DROP TABLE IF EXISTS `tbl_trans_medcheck_rad`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_rad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) NOT NULL DEFAULT 0,
  `id_pasien` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_radiografer` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `id_dokter_kirim` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_keluar` datetime DEFAULT '0000-00-00 00:00:00',
  `no_rad` varchar(50) DEFAULT NULL,
  `no_sample` varchar(50) DEFAULT NULL,
  `dokter_kirim` varchar(160) DEFAULT NULL,
  `ket` text DEFAULT NULL,
  `item` longtext DEFAULT NULL,
  `status` int(11) DEFAULT 0 COMMENT '0=pend\\\\r\\\\n1=proses\\\\r\\\\n2=diterima\\\\r\\\\n3=ditolak\\\\r\\\\n4=farmasi\\\\r\\\\n5=farmasi_proses',
  `status_dokter_krm` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_medcheck_rad_tbl_trans_medcheck` (`id_medcheck`),
  CONSTRAINT `FK_tbl_trans_medcheck_rad_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_rad: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_rad`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_rad_det
DROP TABLE IF EXISTS `tbl_trans_medcheck_rad_det`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_rad_det` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) NOT NULL DEFAULT 0,
  `id_medcheck_det` int(11) NOT NULL DEFAULT 0,
  `id_rad` int(11) NOT NULL DEFAULT 0,
  `id_radiografer` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `item_name` varchar(100) DEFAULT NULL,
  `item_value` text DEFAULT NULL,
  `item_note` text DEFAULT NULL,
  `file` longtext DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_medcheck_rad_det_tbl_trans_medcheck_rad` (`id_rad`),
  CONSTRAINT `FK_tbl_trans_medcheck_rad_det_tbl_trans_medcheck_rad` FOREIGN KEY (`id_rad`) REFERENCES `tbl_trans_medcheck_rad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_rad_det: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_rad_det`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_rad_file
DROP TABLE IF EXISTS `tbl_trans_medcheck_rad_file`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_rad_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) DEFAULT 0,
  `id_medcheck_det` int(11) DEFAULT 0,
  `id_rad` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `judul` varchar(160) DEFAULT NULL,
  `file_name` varchar(160) DEFAULT NULL,
  `file_name_orig` varchar(160) DEFAULT NULL,
  `file_ext` varchar(50) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `file_base64` longtext DEFAULT NULL,
  `sp` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_medcheck_rad_file_tbl_trans_medcheck_rad` (`id_rad`),
  CONSTRAINT `FK_tbl_trans_medcheck_rad_file_tbl_trans_medcheck_rad` FOREIGN KEY (`id_rad`) REFERENCES `tbl_trans_medcheck_rad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_rad_file: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_rad_file`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_remun
DROP TABLE IF EXISTS `tbl_trans_medcheck_remun`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_remun` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) DEFAULT 0,
  `id_medcheck_det` int(11) DEFAULT 0,
  `id_item` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `tgl_simpan` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `item` varchar(168) DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  `jml` decimal(10,2) DEFAULT NULL,
  `remun_perc` decimal(10,2) DEFAULT NULL,
  `remun_nom` decimal(10,2) DEFAULT NULL,
  `remun_subtotal` decimal(10,2) DEFAULT NULL,
  `remun_tipe` int(11) DEFAULT 0 COMMENT '1=persen\r\n2=nominal',
  PRIMARY KEY (`id`),
  KEY `FK__tbl_trans_medcheck` (`id_medcheck`),
  KEY `FK_tbl_trans_medcheck_remun_tbl_trans_medcheck_det` (`id_medcheck_det`),
  CONSTRAINT `FK_tbl_trans_medcheck_remun_tbl_trans_medcheck_det` FOREIGN KEY (`id_medcheck_det`) REFERENCES `tbl_trans_medcheck_det` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_remun: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_remun`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_remun_old
DROP TABLE IF EXISTS `tbl_trans_medcheck_remun_old`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_remun_old` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) DEFAULT 0,
  `id_medcheck_det` int(11) DEFAULT 0,
  `id_item` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `tgl_simpan` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `item` varchar(168) DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  `jml` decimal(10,2) DEFAULT NULL,
  `remun_perc` decimal(10,2) DEFAULT NULL,
  `remun_nom` decimal(10,2) DEFAULT NULL,
  `remun_subtotal` decimal(10,2) DEFAULT NULL,
  `remun_tipe` int(11) DEFAULT 0 COMMENT '1=persen\r\n2=nominal',
  PRIMARY KEY (`id`),
  KEY `FK__tbl_trans_medcheck` (`id_medcheck`),
  CONSTRAINT `FK__tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_remun_old: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_remun_old`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_resep
DROP TABLE IF EXISTS `tbl_trans_medcheck_resep`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_resep` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) NOT NULL DEFAULT 0,
  `id_pasien` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `id_farmasi` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_keluar` datetime DEFAULT '0000-00-00 00:00:00',
  `no_resep` varchar(50) DEFAULT NULL,
  `nm_resep` varchar(160) DEFAULT NULL,
  `ket` varchar(160) DEFAULT NULL,
  `file_name` varchar(160) DEFAULT NULL,
  `item` longtext DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '0=pend\r\n1=proses\r\n2=diterima\r\n3=ditolak\r\n4=farmasi\r\n5=farmasi_proses',
  `status_plg` enum('0','1') DEFAULT '0' COMMENT 'Status Obat Pulang untuk pasien rawat inap',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_medcheck_resep_tbl_trans_medcheck` (`id_medcheck`),
  CONSTRAINT `FK_tbl_trans_medcheck_resep_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Tabel ini menyimpan no resep dan siapa yg membuat resepnya beserta siapa yang menerima resep tersebut';

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_resep: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_resep`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_resep_det
DROP TABLE IF EXISTS `tbl_trans_medcheck_resep_det`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_resep_det` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) NOT NULL DEFAULT 0,
  `id_resep` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_item` int(11) DEFAULT 0,
  `id_item_ref` int(11) DEFAULT 0,
  `id_item_kat` int(11) DEFAULT 0,
  `id_item_sat` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_ed` date DEFAULT '0000-00-00',
  `kode` varchar(50) DEFAULT NULL,
  `item` varchar(160) DEFAULT NULL,
  `dosis` varchar(160) DEFAULT NULL,
  `dosis_ket` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `resep` longtext DEFAULT NULL,
  `harga` decimal(20,6) DEFAULT NULL,
  `jml` decimal(20,2) DEFAULT NULL,
  `jml_satuan` decimal(20,2) DEFAULT NULL,
  `subtotal` decimal(20,2) DEFAULT NULL,
  `disk1` decimal(20,2) DEFAULT NULL,
  `disk2` decimal(20,2) DEFAULT NULL,
  `disk3` decimal(20,2) DEFAULT NULL,
  `diskon` decimal(20,2) DEFAULT NULL,
  `potongan` decimal(20,2) DEFAULT NULL,
  `satuan` varchar(160) DEFAULT NULL,
  `status_mkn` enum('0','1','2','3','4') DEFAULT '0' COMMENT '0=none;\\r\\n1=sebelum makan;\\r\\n2=satt makan;\\r\\n3=sesudah makan;\\r\\n4=lain',
  `status_resep` int(11) DEFAULT 0 COMMENT '0=pend;\\r\\n1=diterima;\\r\\n2=ditolak (barang habis / diganti);\\r\\n3=batal;\\r\\n4=proses;',
  `status_pj` enum('0','1','2') DEFAULT '0' COMMENT 'Status Penjamin (UMUM, BPJS, dll)\r\n0=tidak\r\n1=ya',
  `status_etiket` enum('0','1','2') DEFAULT '0' COMMENT '0=netral;\\r\\n1=etiket putih\\r\\n2=etiket biru',
  `sp` enum('0','1','2') DEFAULT '0',
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_medcheck_resep_det_tbl_trans_medcheck` (`id_resep`),
  CONSTRAINT `FK_tbl_trans_medcheck_resep_det_tbl_trans_medcheck_resep` FOREIGN KEY (`id_resep`) REFERENCES `tbl_trans_medcheck_resep` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_resep_det: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_resep_det`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_resep_det_rc
DROP TABLE IF EXISTS `tbl_trans_medcheck_resep_det_rc`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_resep_det_rc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) DEFAULT 0,
  `id_medcheck_det` int(11) DEFAULT 0,
  `id_resep` int(11) DEFAULT 0,
  `id_resep_det` int(11) DEFAULT 0,
  `id_item` int(11) DEFAULT 0,
  `id_item_kat` int(11) DEFAULT 0,
  `id_item_sat` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `kode` varchar(160) DEFAULT NULL,
  `item` varchar(160) DEFAULT NULL,
  `jml` decimal(10,2) DEFAULT NULL,
  `jml_satuan` decimal(10,2) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `satuan_farmasi` varchar(50) DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `catatan` varchar(160) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_medcheck_resep_det_rc_tbl_trans_medcheck_resep_det` (`id_resep_det`),
  CONSTRAINT `FK_tbl_trans_medcheck_resep_det_rc_tbl_trans_medcheck_resep_det` FOREIGN KEY (`id_resep_det`) REFERENCES `tbl_trans_medcheck_resep_det` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin COMMENT='Tabel ini untuk menyimpan detail resep';

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_resep_det_rc: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_resep_det_rc`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_resume
DROP TABLE IF EXISTS `tbl_trans_medcheck_resume`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_resume` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) DEFAULT 0,
  `id_pasien` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` date DEFAULT '0000-00-00',
  `no_surat` varchar(160) DEFAULT NULL,
  `no_sample` varchar(160) DEFAULT NULL,
  `pasien` varchar(160) DEFAULT NULL,
  `saran` longtext DEFAULT NULL,
  `kesimpulan` longtext DEFAULT NULL,
  `status` enum('0','1') DEFAULT '0',
  `status_rsm` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_medcheck_resume_tbl_trans_medcheck` (`id_medcheck`),
  CONSTRAINT `FK_tbl_trans_medcheck_resume_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_resume: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_resume`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_resume_det
DROP TABLE IF EXISTS `tbl_trans_medcheck_resume_det`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_resume_det` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) DEFAULT 0,
  `id_resume` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_produk` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `param` varchar(160) DEFAULT NULL,
  `param_nilai` text DEFAULT NULL,
  `status_rnp` enum('0','1') DEFAULT '0',
  `status_trp` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_medcheck_resume_det_tbl_trans_medcheck_resume` (`id_resume`),
  CONSTRAINT `FK_tbl_trans_medcheck_resume_det_tbl_trans_medcheck_resume` FOREIGN KEY (`id_resume`) REFERENCES `tbl_trans_medcheck_resume` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_resume_det: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_resume_det`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_retur
DROP TABLE IF EXISTS `tbl_trans_medcheck_retur`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_retur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) DEFAULT 0,
  `id_medcheck_det` int(11) DEFAULT 0,
  `id_pasien` int(11) DEFAULT 0,
  `id_item` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `kode` varchar(50) DEFAULT NULL,
  `item` varchar(160) DEFAULT NULL,
  `jml` decimal(10,2) DEFAULT 0.00,
  `status_item` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_medcheck_retur_tbl_trans_medcheck` (`id_medcheck`),
  CONSTRAINT `FK_tbl_trans_medcheck_retur_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_retur: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_retur`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_rm
DROP TABLE IF EXISTS `tbl_trans_medcheck_rm`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_rm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_perawat` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `id_pasien` int(11) DEFAULT 0,
  `id_icd` int(11) DEFAULT 0,
  `id_icd10` int(11) DEFAULT 0,
  `kode` varchar(50) DEFAULT '0',
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_modif` datetime DEFAULT '0000-00-00 00:00:00',
  `anamnesa` mediumtext DEFAULT NULL,
  `pemeriksaan` mediumtext DEFAULT NULL,
  `diagnosa` mediumtext DEFAULT NULL,
  `terapi` mediumtext DEFAULT NULL,
  `program` mediumtext DEFAULT NULL,
  `ttv_skala` decimal(10,2) DEFAULT NULL,
  `ttv_saturasi` decimal(10,2) DEFAULT NULL,
  `ttv_laju` decimal(10,2) DEFAULT NULL,
  `ttv_nadi` decimal(10,2) DEFAULT NULL,
  `ttv_diastole` decimal(10,2) DEFAULT NULL,
  `ttv_sistole` decimal(10,2) DEFAULT NULL,
  `ttv_tb` decimal(10,2) DEFAULT NULL,
  `ttv_bb` decimal(10,2) DEFAULT NULL,
  `ttv_st` decimal(10,2) DEFAULT NULL,
  `ns_subj` longtext DEFAULT NULL,
  `ns_obj` longtext DEFAULT NULL,
  `ns_ass` longtext DEFAULT NULL,
  `ns_prog` longtext DEFAULT NULL,
  `status` enum('0','1','2') DEFAULT '0' COMMENT '0=tidak\r\n1=perawat\r\n2=dokter',
  `tipe` enum('0','1','2') DEFAULT '0' COMMENT '0 = nothing\r\n1 = perawat\r\n2 = dokter',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_medcheck_rm_tbl_trans_medcheck` (`id_medcheck`),
  CONSTRAINT `FK_tbl_trans_medcheck_rm_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_rm: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_rm`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_stok
DROP TABLE IF EXISTS `tbl_trans_medcheck_stok`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_stok` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) DEFAULT 0,
  `id_medcheck_det` int(11) DEFAULT 0,
  `id_item` int(11) DEFAULT 0,
  `id_gudang` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` datetime DEFAULT '0000-00-00 00:00:00',
  `item` varchar(160) DEFAULT NULL,
  `stok_awal` decimal(10,2) DEFAULT 0.00,
  `jml` decimal(10,2) DEFAULT 0.00,
  `stok_akhir` decimal(10,2) DEFAULT 0.00,
  `keterangan` varchar(160) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_medcheck_stok_tbl_trans_medcheck` (`id_medcheck`),
  CONSTRAINT `FK_tbl_trans_medcheck_stok_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_stok: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_stok`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_surat
DROP TABLE IF EXISTS `tbl_trans_medcheck_surat`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_surat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) DEFAULT 0,
  `id_pasien` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` date DEFAULT '0000-00-00',
  `tgl_keluar` date DEFAULT '0000-00-00',
  `tgl_kontrol` date DEFAULT '0000-00-00',
  `no_surat` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `lahir_tgl` datetime DEFAULT '0000-00-00 00:00:00',
  `lahir_nm` varchar(160) DEFAULT NULL,
  `lahir_nm_ayah` varchar(160) DEFAULT NULL,
  `lahir_nm_ibu` varchar(160) DEFAULT NULL,
  `mati_tgl` datetime DEFAULT '0000-00-00 00:00:00',
  `mati_tmp` varchar(160) DEFAULT NULL,
  `mati_penyebab` varchar(160) DEFAULT NULL,
  `ruj_dokter` varchar(160) DEFAULT NULL,
  `ruj_faskes` varchar(160) DEFAULT NULL,
  `ruj_keluhan` text DEFAULT NULL,
  `ruj_diagnosa` text DEFAULT NULL,
  `ruj_terapi` text DEFAULT NULL,
  `jns_klm` varchar(50) DEFAULT NULL,
  `cvd_tgl_periksa` date DEFAULT '0000-00-00',
  `cvd_tgl_awal` date DEFAULT '0000-00-00',
  `cvd_tgl_akhir` date DEFAULT '0000-00-00',
  `vks_tgl_periksa` date DEFAULT '0000-00-00',
  `nza_tgl_periksa` date DEFAULT '0000-00-00' COMMENT 'Bebas Napza / Narkoba',
  `nza_status` enum('0','1','2') DEFAULT '0' COMMENT '0 = Belum;\\r\\n1 = Posistif;\\r\\n2 = Negatif;',
  `hml_periksa` text DEFAULT NULL,
  `hml_tipe_ijin` enum('0','1','2') DEFAULT '0',
  `hml_tipe_terbang` enum('0','1','2') DEFAULT '0',
  `hml_tgl_awal` date DEFAULT '0000-00-00',
  `hml_tgl_akhir` date DEFAULT '0000-00-00',
  `hml_status_sehat` enum('0','1','2') DEFAULT '0',
  `trb_tgl_awal` date DEFAULT '0000-00-00',
  `trb_tgl_akhir` date DEFAULT '0000-00-00',
  `trb_periksa` text DEFAULT NULL,
  `trb_tipe_terbang` enum('0','1','2') DEFAULT '0',
  `tb` decimal(10,2) DEFAULT NULL,
  `td` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `bb` decimal(10,2) DEFAULT NULL,
  `tht_lt_kanan` varchar(100) DEFAULT NULL,
  `tht_lt_kiri` varchar(100) DEFAULT NULL,
  `tht_membran_kanan` varchar(100) DEFAULT NULL,
  `tht_membran_kiri` varchar(100) DEFAULT NULL,
  `tht_mukosa_kanan` varchar(100) DEFAULT NULL,
  `tht_mukosa_kiri` varchar(100) DEFAULT NULL,
  `tht_konka_kanan` varchar(100) DEFAULT NULL,
  `tht_konka_kiri` varchar(100) DEFAULT NULL,
  `tht_timpa_kanan` varchar(100) DEFAULT NULL,
  `tht_timpa_kiri` varchar(100) DEFAULT NULL,
  `tht_tonsil_tg` varchar(100) DEFAULT NULL,
  `tht_mukosa_tg` varchar(100) DEFAULT NULL,
  `tht_faring_tg` varchar(100) DEFAULT NULL,
  `tht_audio` text DEFAULT NULL,
  `tht_kesimpulan` text DEFAULT NULL,
  `bw` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `bw_ket` varchar(50) DEFAULT NULL,
  `jml_hari` int(11) DEFAULT NULL,
  `keterangan` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `tipe` enum('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15') CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT '0' COMMENT '1=Surat Sehat\\\\\\\\r\\\\\\\\n2=Surat Sakit\\\\\\\\r\\\\\\\\n3=Surat Ranap\\\\\\\\r\\\\\\\\n4=Surat Kontrol\\\\\\\\r\\\\\\\\n5=Surat Hsl Radiolog\\\\\\\\r\\\\\\\\n6=Surat Kematian\\\\\\\\r\\\\\\\\n7=Surat Hsl Covid',
  `hasil` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT '0',
  `status` enum('0','1','2','3','4','5','6','7','8','9','10') CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT '0',
  `status_sembuh` enum('0','1','2','3','4') DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_medcheck_surat_tbl_trans_medcheck` (`id_medcheck`),
  CONSTRAINT `FK_tbl_trans_medcheck_surat_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_surat: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_surat`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_surat_inform
DROP TABLE IF EXISTS `tbl_trans_medcheck_surat_inform`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_surat_inform` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) DEFAULT 0,
  `id_pasien` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_ruang` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `tgl_masuk` date DEFAULT '0000-00-00',
  `tgl_lahir` date DEFAULT '0000-00-00',
  `no_surat` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `nama` varchar(160) DEFAULT NULL,
  `nama_saksi1` varchar(160) DEFAULT NULL,
  `nama_saksi2` varchar(160) DEFAULT NULL,
  `jns_klm` enum('L','P') DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `ruang` varchar(160) DEFAULT NULL,
  `penjamin` varchar(160) DEFAULT NULL,
  `penanggung` varchar(160) DEFAULT NULL,
  `tindakan` text DEFAULT NULL,
  `file_name1` varchar(160) DEFAULT NULL,
  `file_name2` varchar(160) DEFAULT NULL,
  `file_name3` varchar(160) DEFAULT NULL,
  `file_name4` varchar(160) DEFAULT NULL,
  `file_name5` varchar(160) DEFAULT NULL,
  `file_name6` varchar(160) DEFAULT NULL,
  `tipe` enum('0','1','2','3','4','5','6','7','8','9','10','11','12','13') CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT '0' COMMENT '1=Surat Sehat\\r\\n2=Surat Sakit\\r\\n3=Surat Ranap\\r\\n4=Surat Kontrol\\r\\n5=Surat Hsl Radiolog\\r\\n6=Surat Kematian\\r\\n7=Surat Hsl Covid',
  `status` enum('0','1','2','3','4','5','6','7','8','9','10') CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT '0',
  `status_hub` enum('0','1','2','3','4','5','6') DEFAULT '0' COMMENT 'Status Kekerabatan',
  `status_stj` enum('0','1','2') DEFAULT '0',
  `status_ttd` enum('0','1') DEFAULT '0' COMMENT 'Sudah ttd atau belum',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_tbl_trans_medcheck_surat_inform_tbl_trans_medcheck` (`id_medcheck`) USING BTREE,
  CONSTRAINT `FK_tbl_trans_medcheck_surat_inform_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_surat_inform: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_surat_inform`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_medcheck_trf
DROP TABLE IF EXISTS `tbl_trans_medcheck_trf`;
CREATE TABLE IF NOT EXISTS `tbl_trans_medcheck_trf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_poli_asal` int(11) DEFAULT 0,
  `id_poli_tujuan` int(11) DEFAULT 0,
  `id_pasien` int(11) DEFAULT 0,
  `id_dokter` int(11) DEFAULT 0,
  `tipe` int(11) DEFAULT 0,
  `tipe_asal` int(11) DEFAULT 0,
  `tipe_tujuan` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `no_surat` varchar(50) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `keterangan_dokter` text DEFAULT NULL,
  `keterangan_perawat` text DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_medcheck_trf_tbl_trans_medcheck` (`id_medcheck`),
  CONSTRAINT `FK_tbl_trans_medcheck_trf_tbl_trans_medcheck` FOREIGN KEY (`id_medcheck`) REFERENCES `tbl_trans_medcheck` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_medcheck_trf: ~0 rows (approximately)
DELETE FROM `tbl_trans_medcheck_trf`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_mutasi
DROP TABLE IF EXISTS `tbl_trans_mutasi`;
CREATE TABLE IF NOT EXISTS `tbl_trans_mutasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_user_terima` int(11) DEFAULT NULL,
  `no_nota` varchar(50) DEFAULT NULL,
  `tgl_simpan` datetime DEFAULT NULL,
  `tgl_modif` datetime DEFAULT NULL,
  `kode_nota_dpn` varchar(50) DEFAULT NULL,
  `kode_nota_blk` varchar(50) DEFAULT NULL,
  `tgl_masuk` date DEFAULT '0000-00-00',
  `tgl_keluar` date DEFAULT '0000-00-00',
  `id_gd_asal` int(11) DEFAULT NULL,
  `id_gd_tujuan` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status_nota` enum('0','1','2','3','4') DEFAULT '0',
  `status_terima` enum('0','1') DEFAULT '0',
  `tipe` enum('0','1','2','3') DEFAULT '0' COMMENT '1 = Pindah Gudang\r\n2 = Stok Masuk\r\n3 = Stok Keluar',
  PRIMARY KEY (`id`),
  KEY `no_nota` (`no_nota`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='Mencatat transaksi mutasi antar gudang';

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_mutasi: ~0 rows (approximately)
DELETE FROM `tbl_trans_mutasi`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_mutasi_det
DROP TABLE IF EXISTS `tbl_trans_mutasi_det`;
CREATE TABLE IF NOT EXISTS `tbl_trans_mutasi_det` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_mutasi` int(11) NOT NULL DEFAULT 0,
  `id_satuan` int(11) DEFAULT 0,
  `id_item` int(11) DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `tgl_simpan` datetime DEFAULT NULL,
  `tgl_terima` datetime DEFAULT NULL,
  `tgl_ed` varchar(50) DEFAULT NULL,
  `no_nota` varchar(50) DEFAULT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `kode_batch` varchar(50) DEFAULT NULL,
  `produk` varchar(256) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `jml` int(6) DEFAULT 0,
  `jml_diterima` int(6) DEFAULT 0,
  `jml_satuan` int(6) DEFAULT NULL,
  `status_brg` enum('0','1') DEFAULT '0',
  `status_terima` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_mutasi` (`id_mutasi`),
  CONSTRAINT `FK_tbl_trans_gudang_det_tbl_trans_gudang` FOREIGN KEY (`id_mutasi`) REFERENCES `tbl_trans_mutasi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='Mencatat transaksi mutasi antar gudang';

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_mutasi_det: ~0 rows (approximately)
DELETE FROM `tbl_trans_mutasi_det`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_retur_beli
DROP TABLE IF EXISTS `tbl_trans_retur_beli`;
CREATE TABLE IF NOT EXISTS `tbl_trans_retur_beli` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_app` int(11) NOT NULL DEFAULT 0,
  `id_pelanggan` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) NOT NULL DEFAULT 0,
  `id_pembelian` int(11) NOT NULL DEFAULT 0,
  `tgl_simpan` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `no_nota` varchar(50) DEFAULT NULL,
  `no_retur` varchar(50) DEFAULT NULL,
  `jml_total` decimal(32,2) DEFAULT NULL,
  `ppn` decimal(32,2) DEFAULT NULL,
  `jml_ppn` decimal(32,2) DEFAULT NULL,
  `jml_retur` decimal(32,2) DEFAULT NULL,
  `status_retur` int(11) NOT NULL,
  `status_ppn` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idCustomer` (`id_pelanggan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_retur_beli: ~0 rows (approximately)
DELETE FROM `tbl_trans_retur_beli`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_retur_beli_det
DROP TABLE IF EXISTS `tbl_trans_retur_beli_det`;
CREATE TABLE IF NOT EXISTS `tbl_trans_retur_beli_det` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_retur_beli` int(11) DEFAULT NULL,
  `id_beli_det` int(11) DEFAULT NULL,
  `id_satuan` int(11) DEFAULT NULL,
  `tgl_simpan` datetime DEFAULT '0000-00-00 00:00:00',
  `kode` varchar(50) DEFAULT NULL,
  `produk` varchar(256) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `harga` decimal(32,2) DEFAULT NULL,
  `disk1` decimal(10,2) DEFAULT NULL,
  `disk2` decimal(10,2) DEFAULT NULL,
  `disk3` decimal(10,2) DEFAULT NULL,
  `jml` int(6) DEFAULT NULL,
  `jml_satuan` int(6) DEFAULT NULL,
  `diskon` decimal(32,2) DEFAULT NULL,
  `potongan` decimal(32,2) DEFAULT NULL,
  `subtotal` decimal(32,2) DEFAULT NULL,
  `status_retur` enum('1','2') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_retur_beli_det_tbl_trans_retur_beli` (`id_retur_beli`),
  CONSTRAINT `FK_tbl_trans_retur_beli_det_tbl_trans_retur_beli` FOREIGN KEY (`id_retur_beli`) REFERENCES `tbl_trans_retur_beli` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_retur_beli_det: ~0 rows (approximately)
DELETE FROM `tbl_trans_retur_beli_det`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_retur_jual
DROP TABLE IF EXISTS `tbl_trans_retur_jual`;
CREATE TABLE IF NOT EXISTS `tbl_trans_retur_jual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_app` int(11) DEFAULT 0,
  `id_pelanggan` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_user_auth` int(11) DEFAULT NULL,
  `id_penjualan` int(11) DEFAULT NULL,
  `tgl_simpan` datetime DEFAULT NULL,
  `no_retur` varchar(50) DEFAULT '0',
  `no_nota` varchar(50) DEFAULT '0',
  `jml_total` decimal(32,2) DEFAULT NULL,
  `jml_diskon` decimal(32,2) DEFAULT NULL,
  `ppn` decimal(32,2) DEFAULT NULL,
  `jml_ppn` decimal(32,2) DEFAULT NULL,
  `jml_retur` decimal(32,2) DEFAULT NULL,
  `jml_gtotal` decimal(32,2) DEFAULT NULL,
  `status_retur` int(11) DEFAULT NULL,
  `status_ppn` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idCustomer` (`id_pelanggan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_retur_jual: ~0 rows (approximately)
DELETE FROM `tbl_trans_retur_jual`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_retur_jual_det
DROP TABLE IF EXISTS `tbl_trans_retur_jual_det`;
CREATE TABLE IF NOT EXISTS `tbl_trans_retur_jual_det` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_retur_jual` int(11) DEFAULT NULL,
  `id_satuan` int(11) DEFAULT NULL,
  `tgl_simpan` datetime DEFAULT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `produk` varchar(256) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `harga` decimal(32,2) DEFAULT NULL,
  `disk1` decimal(10,2) DEFAULT NULL,
  `disk2` decimal(10,2) DEFAULT NULL,
  `disk3` decimal(10,2) DEFAULT NULL,
  `jml` int(6) DEFAULT NULL,
  `jml_satuan` int(6) DEFAULT NULL,
  `diskon` decimal(32,2) DEFAULT NULL,
  `potongan` decimal(32,2) DEFAULT NULL,
  `subtotal` decimal(32,2) DEFAULT NULL,
  `status_retur` enum('1','2','3') DEFAULT NULL,
  `status_nota` enum('1','2') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_trans_retur_jual_det_tbl_trans_retur_jual` (`id_retur_jual`),
  CONSTRAINT `FK_tbl_trans_retur_jual_det_tbl_trans_retur_jual` FOREIGN KEY (`id_retur_jual`) REFERENCES `tbl_trans_retur_jual` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_retur_jual_det: ~0 rows (approximately)
DELETE FROM `tbl_trans_retur_jual_det`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_stok_tmp
DROP TABLE IF EXISTS `tbl_trans_stok_tmp`;
CREATE TABLE IF NOT EXISTS `tbl_trans_stok_tmp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_item` int(11) DEFAULT 0,
  `id_item_det` int(11) DEFAULT 0,
  `item` varchar(160) DEFAULT NULL,
  `keterangan` varchar(160) DEFAULT NULL,
  `stok_awal` decimal(10,2) DEFAULT 0.00,
  `jml` decimal(10,2) DEFAULT 0.00,
  `stok_akhir` decimal(10,2) DEFAULT 0.00,
  `status` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_stok_tmp: ~0 rows (approximately)
DELETE FROM `tbl_trans_stok_tmp`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_trans_stok_tmp_glob
DROP TABLE IF EXISTS `tbl_trans_stok_tmp_glob`;
CREATE TABLE IF NOT EXISTS `tbl_trans_stok_tmp_glob` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_item` int(11) DEFAULT 0,
  `jml` decimal(10,2) DEFAULT 0.00,
  `status` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_trans_stok_tmp_glob: ~0 rows (approximately)
DELETE FROM `tbl_trans_stok_tmp_glob`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_util_backup
DROP TABLE IF EXISTS `tbl_util_backup`;
CREATE TABLE IF NOT EXISTS `tbl_util_backup` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tgl` timestamp NULL DEFAULT NULL,
  `name` varchar(160) NOT NULL,
  `file_name` varchar(160) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_util_backup: ~0 rows (approximately)
DELETE FROM `tbl_util_backup`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_util_eksport
DROP TABLE IF EXISTS `tbl_util_eksport`;
CREATE TABLE IF NOT EXISTS `tbl_util_eksport` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl_simpan` timestamp NULL DEFAULT NULL,
  `file` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_util_eksport: ~0 rows (approximately)
DELETE FROM `tbl_util_eksport`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_util_import
DROP TABLE IF EXISTS `tbl_util_import`;
CREATE TABLE IF NOT EXISTS `tbl_util_import` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl_simpan` timestamp NULL DEFAULT NULL,
  `file` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_util_import: ~0 rows (approximately)
DELETE FROM `tbl_util_import`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_util_log
DROP TABLE IF EXISTS `tbl_util_log`;
CREATE TABLE IF NOT EXISTS `tbl_util_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl_simpan` timestamp NULL DEFAULT NULL,
  `id_user` int(11) DEFAULT 0,
  `keterangan` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_util_log: ~0 rows (approximately)
DELETE FROM `tbl_util_log`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_util_log_satusehat
DROP TABLE IF EXISTS `tbl_util_log_satusehat`;
CREATE TABLE IF NOT EXISTS `tbl_util_log_satusehat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_medcheck` int(11) NOT NULL DEFAULT 0,
  `no_register` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `response_status` longtext DEFAULT NULL,
  `postdate` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_util_log_satusehat: ~0 rows (approximately)
DELETE FROM `tbl_util_log_satusehat`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_util_so
DROP TABLE IF EXISTS `tbl_util_so`;
CREATE TABLE IF NOT EXISTS `tbl_util_so` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_gudang` int(11) DEFAULT 0,
  `sess_id` varchar(64) DEFAULT NULL,
  `tgl_simpan` datetime DEFAULT NULL,
  `tgl_modif` datetime DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `satuan` varchar(64) DEFAULT NULL,
  `nm_file` text DEFAULT NULL,
  `dl_file` text DEFAULT NULL,
  `reset` enum('0','1') DEFAULT '0',
  `status` enum('0','1','2','3') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_util_so: ~0 rows (approximately)
DELETE FROM `tbl_util_so`;

-- Dumping structure for table esensiaco_medkit_dev.tbl_util_so_det
DROP TABLE IF EXISTS `tbl_util_so_det`;
CREATE TABLE IF NOT EXISTS `tbl_util_so_det` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_so` int(11) NOT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tgl_simpan` datetime DEFAULT NULL,
  `tgl_modif` datetime DEFAULT NULL,
  `tgl_masuk` date DEFAULT NULL,
  `kode` varchar(100) DEFAULT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `produk` varchar(100) DEFAULT NULL,
  `satuan` varchar(100) DEFAULT NULL,
  `keterangan` longtext DEFAULT NULL,
  `jml` decimal(10,2) DEFAULT NULL,
  `jml_sys` decimal(10,2) DEFAULT NULL,
  `jml_so` decimal(10,2) DEFAULT NULL,
  `jml_sls` decimal(10,2) DEFAULT NULL,
  `jml_satuan` int(11) DEFAULT NULL,
  `merk` varchar(100) DEFAULT NULL,
  `sp` varchar(100) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_so` (`id_so`),
  CONSTRAINT `FK_tbl_util_so_det_tbl_util_so` FOREIGN KEY (`id_so`) REFERENCES `tbl_util_so` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Dumping data for table esensiaco_medkit_dev.tbl_util_so_det: ~0 rows (approximately)
DELETE FROM `tbl_util_so_det`;

-- Dumping structure for table esensiaco_medkit_dev.tr_queue
DROP TABLE IF EXISTS `tr_queue`;
CREATE TABLE IF NOT EXISTS `tr_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_view` int(11) NOT NULL DEFAULT 0,
  `id_medcheck` int(11) DEFAULT 0,
  `id_dft` int(11) DEFAULT 0,
  `ddate` date DEFAULT NULL,
  `cnoro` varchar(35) DEFAULT NULL,
  `ncount` decimal(3,0) DEFAULT NULL,
  `ccustsrv` varchar(35) DEFAULT NULL,
  `cnote` text DEFAULT NULL,
  `csflagqu` varchar(15) DEFAULT NULL,
  `csflaghd` varchar(15) DEFAULT NULL,
  `ccode` varchar(5) DEFAULT NULL,
  `crgcode` varchar(5) DEFAULT NULL,
  `ddatestart` datetime DEFAULT NULL,
  `ddatepross` datetime DEFAULT NULL,
  `ddateend` datetime DEFAULT NULL,
  `cUser` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT '',
  `suara` varchar(160) DEFAULT '',
  `suara2` varchar(160) DEFAULT '',
  `status` enum('0','1','2') DEFAULT '0',
  `status_pgl` enum('0','1') DEFAULT '0',
  `dCreateDate` datetime DEFAULT '0000-00-00 00:00:00',
  `dLastUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.tr_queue: ~0 rows (approximately)
DELETE FROM `tr_queue`;

-- Dumping structure for table esensiaco_medkit_dev.vtrans_antrian
DROP TABLE IF EXISTS `vtrans_antrian`;
CREATE TABLE IF NOT EXISTS `vtrans_antrian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nttrans` int(11) DEFAULT NULL,
  `ddate` date DEFAULT NULL,
  `cflag` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table esensiaco_medkit_dev.vtrans_antrian: ~0 rows (approximately)
DELETE FROM `vtrans_antrian`;

-- Dumping structure for view esensiaco_medkit_dev.v_karyawan_absen
DROP VIEW IF EXISTS `v_karyawan_absen`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_karyawan_absen` (
	`id` INT(4) NOT NULL,
	`id_user` INT(11) UNSIGNED NOT NULL,
	`nama_dpn` VARCHAR(1) NULL COLLATE 'latin1_general_ci',
	`nama` VARCHAR(1) NULL COLLATE 'latin1_general_ci',
	`nama_blk` VARCHAR(1) NULL COLLATE 'latin1_general_ci',
	`tgl_masuk` DATE NULL,
	`wkt_masuk` TIME NULL,
	`wkt_keluar` TIME NULL,
	`scan1` TIME NULL,
	`scan2` TIME NULL,
	`scan3` TIME NULL,
	`scan4` TIME NULL
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_laporan_stok
DROP VIEW IF EXISTS `v_laporan_stok`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_laporan_stok` (
	`id` INT(11) NOT NULL,
	`tgl_simpan` DATETIME NULL,
	`item` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`stok` FLOAT NULL,
	`laku` DECIMAL(32,2) NULL,
	`sisa_stok` DOUBLE NULL
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_master_absen
DROP VIEW IF EXISTS `v_master_absen`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_master_absen` (
	`id` INT(11) NOT NULL,
	`id_user` INT(11) NULL,
	`nama_dpn` VARCHAR(1) NULL COLLATE 'latin1_general_ci',
	`nama` VARCHAR(1) NULL COLLATE 'latin1_general_ci',
	`nama_blk` VARCHAR(1) NULL COLLATE 'latin1_general_ci',
	`tgl_simpan` DATETIME NULL,
	`tgl_masuk` DATE NULL,
	`wkt_masuk` TIME NULL,
	`wkt_keluar` TIME NULL,
	`scan1` TIME NULL,
	`scan2` TIME NULL,
	`scan3` TIME NULL,
	`scan4` TIME NULL
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_master_dokter
DROP VIEW IF EXISTS `v_master_dokter`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_master_dokter` (
	`id` INT(4) NOT NULL,
	`id_user` INT(11) UNSIGNED NOT NULL,
	`id_poli` INT(11) NULL,
	`poli` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`nama_dpn` VARCHAR(1) NULL COLLATE 'latin1_general_ci',
	`nama` VARCHAR(1) NULL COLLATE 'latin1_general_ci',
	`nama_blk` VARCHAR(1) NULL COLLATE 'latin1_general_ci',
	`hari_1` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`hari_2` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`hari_3` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`hari_4` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`hari_5` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`hari_6` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`hari_7` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`status_prtk` INT(11) NULL
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_medcheck
DROP VIEW IF EXISTS `v_medcheck`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_medcheck` (
	`id` INT(11) NOT NULL,
	`id_user` INT(11) NULL,
	`id_dokter` INT(11) NULL,
	`id_nurse` INT(11) NULL,
	`id_analis` INT(11) NULL,
	`id_pasien` INT(11) NULL,
	`id_poli` INT(11) NULL,
	`id_dft` INT(11) NULL COMMENT 'ID yang diambil dari tbl_pendaftaran kolom id',
	`id_ant` INT(11) NULL,
	`id_kasir` INT(11) NULL,
	`id_instansi` INT(11) NULL,
	`id_encounter` TEXT NULL COLLATE 'utf8mb3_general_ci',
	`id_condition` TEXT NULL COLLATE 'utf8mb3_general_ci',
	`id_location` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`id_icd` INT(11) NULL,
	`id_icd10` INT(11) NULL,
	`tgl_simpan` DATETIME NULL,
	`tgl_modif` DATETIME NULL,
	`tgl_masuk` DATETIME NULL,
	`tgl_periksa` DATETIME NULL,
	`tgl_periksa_lab` DATETIME NULL,
	`tgl_periksa_rad` DATETIME NULL,
	`tgl_periksa_pen` DATETIME NULL,
	`tgl_ranap` DATETIME NULL,
	`tgl_keluar` DATETIME NULL,
	`tgl_bayar` DATETIME NULL,
	`tgl_ttd` DATETIME NULL,
	`no_rm` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`no_akun` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`no_nota` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`poli` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`nik_dokter` VARCHAR(1) NULL COLLATE 'latin1_general_ci',
	`nama_dokter` VARCHAR(1) NULL COLLATE 'latin1_general_ci',
	`kode` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`nik_pasien` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`pasien` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`tgl_lahir` DATE NULL,
	`keluhan` TEXT NULL COLLATE 'latin1_swedish_ci',
	`ttv` TEXT NULL COLLATE 'utf8mb3_general_ci',
	`ttv_st` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`ttv_bb` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`ttv_tb` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`ttv_td` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`ttv_sistole` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`ttv_diastole` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`ttv_nadi` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`ttv_laju` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`ttv_saturasi` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`ttv_skala` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`diagnosa` TEXT NULL COLLATE 'latin1_swedish_ci',
	`anamnesa` TEXT NULL COLLATE 'latin1_swedish_ci',
	`pemeriksaan` TEXT NULL COLLATE 'latin1_swedish_ci',
	`program` TEXT NULL COLLATE 'utf8mb3_general_ci',
	`alergi` TEXT NULL COLLATE 'utf8mb3_general_ci',
	`metode` INT(11) NULL,
	`platform` INT(11) NULL,
	`jml_total` DECIMAL(10,2) NULL,
	`jml_dp` DECIMAL(10,2) NULL,
	`jml_diskon` DECIMAL(10,2) NULL,
	`jml_potongan` DECIMAL(10,2) NULL,
	`jml_potongan_poin` DECIMAL(10,2) NULL,
	`jml_subtotal` DECIMAL(10,2) NULL,
	`jml_ppn` DECIMAL(10,2) NULL,
	`ppn` DECIMAL(10,2) NULL,
	`jml_gtotal` DECIMAL(10,2) NULL,
	`jml_bayar` DECIMAL(10,2) NULL,
	`jml_kembali` DECIMAL(10,2) NULL,
	`jml_kurang` DECIMAL(10,2) NULL,
	`jml_poin` DECIMAL(10,2) NULL,
	`jml_poin_nom` DECIMAL(10,2) NULL,
	`tipe` INT(11) NULL COMMENT '2=rajal;3=ranap;',
	`tipe_bayar` INT(11) NULL COMMENT '0 = tidak ada;\r\n1 = UMUM;\r\n2 = ASURANSI;\r\n3 = BPJS;',
	`status` INT(11) NULL COMMENT '1=anamnesa;\r\n2=tindakan;\r\n3=obat;\r\n4=laborat;\r\n5=dokter;\r\n6=pembayaran;\r\n7=finish',
	`status_bayar` INT(11) NULL COMMENT '0=belum;\r\n1=lunas;\r\n2=kurang;',
	`status_nota` INT(11) NULL COMMENT '0=pend;\r\n1=proses;\r\n2=finish;\r\n3=batal',
	`status_hps` ENUM('0','1') NULL COLLATE 'latin1_swedish_ci',
	`status_pos` ENUM('0','1','2') NULL COLLATE 'utf8mb3_general_ci',
	`status_periksa` ENUM('0','1','2') NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_medcheck_apotik
DROP VIEW IF EXISTS `v_medcheck_apotik`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_medcheck_apotik` (
	`id` INT(11) NOT NULL,
	`id_pasien` INT(11) NULL,
	`tgl_simpan` DATETIME NULL,
	`pasien` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`item` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`harga` DECIMAL(10,2) NULL,
	`jml` DECIMAL(10,2) NULL,
	`subtotal` DECIMAL(10,2) NULL
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_medcheck_apres
DROP VIEW IF EXISTS `v_medcheck_apres`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_medcheck_apres` (
	`id` INT(11) NOT NULL,
	`id_dokter` INT(11) NULL,
	`tgl_simpan` TIMESTAMP NULL,
	`dokter` VARCHAR(1) NULL COLLATE 'latin1_general_ci',
	`dokter_blk` VARCHAR(1) NULL COLLATE 'latin1_general_ci',
	`poli` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`no_rm` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`nama_pgl` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`item` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`jml` DECIMAL(10,2) NULL,
	`harga` DECIMAL(10,2) NULL,
	`apres_nom` DECIMAL(10,2) NULL,
	`apres_subtotal` DECIMAL(10,2) NULL,
	`apres_perc` DECIMAL(10,2) NULL,
	`tipe` INT(11) NULL COMMENT '2=rajal;3=ranap;',
	`status_produk` INT(11) NULL COMMENT '2=tindakan\r\n3=lab\r\n4=obat\r\n5=radiologi\r\n6=racikan'
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_medcheck_bukti
DROP VIEW IF EXISTS `v_medcheck_bukti`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_medcheck_bukti` (
	`id` INT(11) NOT NULL,
	`id_pasien` INT(11) NULL,
	`id_user` INT(11) NOT NULL,
	`tgl_simpan` DATETIME NOT NULL,
	`username` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`no_rm` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`pasien` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`judul` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`file_name` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`status` ENUM('0','1','2','3') NULL COMMENT '0=none;\\r\\n1=berkas unggah;\\r\\n2=resume;\\r\\n3=Bukti Bayar' COLLATE 'utf8mb4_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_medcheck_dokter
DROP VIEW IF EXISTS `v_medcheck_dokter`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_medcheck_dokter` (
	`id` INT(11) NOT NULL,
	`id_dft` INT(11) NULL COMMENT 'ID yang diambil dari tbl_pendaftaran kolom id',
	`id_user` INT(11) NULL,
	`id_dokter` INT(11) NULL,
	`id_nurse` INT(11) NULL,
	`id_analis` INT(11) NULL,
	`id_pasien` INT(11) NULL,
	`id_poli` INT(11) NULL,
	`pasien` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`no_nota` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`no_rm` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`tgl_simpan` DATETIME NULL,
	`waktu_masuk` TIME NULL,
	`tgl_bayar` DATETIME NULL,
	`tgl_keluar` DATETIME NULL,
	`waktu_keluar` TIME NULL,
	`jml_total` DECIMAL(10,2) NULL,
	`jml_gtotal` DECIMAL(10,2) NULL,
	`ppn` DECIMAL(10,2) NULL,
	`jml_ppn` DECIMAL(10,2) NULL,
	`tipe` INT(11) NULL COMMENT '2=rajal;3=ranap;',
	`status` INT(11) NULL COMMENT '1=anamnesa;\r\n2=tindakan;\r\n3=obat;\r\n4=laborat;\r\n5=dokter;\r\n6=pembayaran;\r\n7=finish',
	`status_hps` ENUM('0','1') NULL COLLATE 'latin1_swedish_ci',
	`status_nota` INT(11) NULL COMMENT '0=pend;\r\n1=proses;\r\n2=finish;\r\n3=batal',
	`status_bayar` INT(11) NULL COMMENT '0=belum;\r\n1=lunas;\r\n2=kurang;',
	`status_periksa` ENUM('0','1','2') NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_medcheck_hapus
DROP VIEW IF EXISTS `v_medcheck_hapus`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_medcheck_hapus` (
	`id` INT(11) NOT NULL,
	`id_user` INT(11) NULL,
	`id_dokter` INT(11) NULL,
	`id_nurse` INT(11) NULL,
	`id_analis` INT(11) NULL,
	`id_pasien` INT(11) NULL,
	`id_poli` INT(11) NULL,
	`id_dft` INT(11) NULL COMMENT 'ID yang diambil dari tbl_pendaftaran kolom id',
	`id_ant` INT(11) NULL,
	`id_kasir` INT(11) NULL,
	`id_icd` INT(11) NULL,
	`id_icd10` INT(11) NULL,
	`tgl_simpan` DATETIME NULL,
	`tgl_modif` DATETIME NULL,
	`tgl_masuk` DATETIME NULL,
	`tgl_periksa` DATETIME NULL,
	`tgl_periksa_lab` DATETIME NULL,
	`tgl_periksa_rad` DATETIME NULL,
	`tgl_periksa_pen` DATETIME NULL,
	`tgl_ranap` DATETIME NULL,
	`tgl_keluar` DATETIME NULL,
	`tgl_bayar` DATETIME NULL,
	`tgl_ttd` DATETIME NULL,
	`no_rm` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`no_akun` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`no_nota` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`poli` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`pasien` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`keluhan` TEXT NULL COLLATE 'latin1_swedish_ci',
	`ttv` TEXT NULL COLLATE 'utf8mb3_general_ci',
	`ttv_st` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`ttv_bb` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`ttv_tb` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`ttv_td` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`ttv_sistole` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`ttv_diastole` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`ttv_nadi` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`ttv_laju` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`ttv_saturasi` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`ttv_skala` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`diagnosa` TEXT NULL COLLATE 'latin1_swedish_ci',
	`anamnesa` TEXT NULL COLLATE 'latin1_swedish_ci',
	`pemeriksaan` TEXT NULL COLLATE 'latin1_swedish_ci',
	`program` TEXT NULL COLLATE 'utf8mb3_general_ci',
	`alergi` TEXT NULL COLLATE 'utf8mb3_general_ci',
	`metode` INT(11) NULL,
	`platform` INT(11) NULL,
	`jml_total` DECIMAL(10,2) NULL,
	`jml_dp` DECIMAL(10,2) NULL,
	`jml_diskon` DECIMAL(10,2) NULL,
	`jml_potongan` DECIMAL(10,2) NULL,
	`jml_subtotal` DECIMAL(10,2) NULL,
	`jml_ppn` DECIMAL(10,2) NULL,
	`ppn` DECIMAL(10,2) NULL,
	`jml_gtotal` DECIMAL(10,2) NULL,
	`jml_bayar` DECIMAL(10,2) NULL,
	`jml_kembali` DECIMAL(10,2) NULL,
	`jml_kurang` DECIMAL(10,2) NULL,
	`tipe` INT(11) NULL COMMENT '2=rajal;3=ranap;',
	`tipe_bayar` INT(11) NULL COMMENT '0 = tidak ada;\r\n1 = UMUM;\r\n2 = ASURANSI;\r\n3 = BPJS;',
	`status` INT(11) NULL COMMENT '1=anamnesa;\r\n2=tindakan;\r\n3=obat;\r\n4=laborat;\r\n5=dokter;\r\n6=pembayaran;\r\n7=finish',
	`status_bayar` INT(11) NULL COMMENT '0=belum;\r\n1=lunas;\r\n2=kurang;',
	`status_nota` INT(11) NULL COMMENT '0=pend;\r\n1=proses;\r\n2=finish;\r\n3=batal',
	`status_hps` ENUM('0','1') NULL COLLATE 'latin1_swedish_ci',
	`status_pos` ENUM('0','1','2') NULL COLLATE 'utf8mb3_general_ci',
	`status_periksa` ENUM('0','1','2') NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_medcheck_mcu
DROP VIEW IF EXISTS `v_medcheck_mcu`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_medcheck_mcu` (
	`id` INT(11) NOT NULL,
	`id_medcheck` INT(11) NULL,
	`id_instansi` INT(11) NULL,
	`id_pasien` INT(11) NOT NULL,
	`id_user` INT(11) NULL,
	`tgl_simpan` DATETIME NULL,
	`nama_pgl` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`no_surat` VARCHAR(1) NULL COLLATE 'armscii8_bin',
	`saran` LONGTEXT NULL COLLATE 'armscii8_bin',
	`kesimpulan` LONGTEXT NULL COLLATE 'armscii8_bin'
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_medcheck_omset
DROP VIEW IF EXISTS `v_medcheck_omset`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_medcheck_omset` (
	`id` INT(11) NOT NULL,
	`id_medcheck` INT(11) NOT NULL,
	`id_pasien` INT(11) NULL,
	`id_poli` INT(11) NULL,
	`id_dokter` INT(11) NULL,
	`id_item` INT(11) NULL,
	`id_item_kat` INT(11) NULL,
	`tgl_simpan` DATETIME NULL,
	`tgl_masuk` DATETIME NULL,
	`tgl_bayar` DATETIME NULL,
	`no_akun` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`no_rm` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`pasien` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`tgl_lahir` DATE NULL,
	`kode` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`item` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`jml` DECIMAL(10,2) NULL,
	`harga` DECIMAL(10,2) NULL,
	`diskon` DECIMAL(10,2) NULL,
	`potongan` DECIMAL(10,2) NULL,
	`potongan_poin` DECIMAL(10,2) NULL,
	`subtotal` DECIMAL(10,2) NULL,
	`jml_gtotal` DECIMAL(10,2) NULL,
	`status_pkt` ENUM('0','1') NULL COLLATE 'utf8mb3_general_ci',
	`status` INT(11) NULL COMMENT '0=null\r\n1=obat\r\n2=lab\r\n3=tindakan\r\n4=radiologi',
	`tipe` INT(11) NULL COMMENT '2=rajal;3=ranap;',
	`tipe_bayar` INT(11) NULL COMMENT '0 = tidak ada;\r\n1 = UMUM;\r\n2 = ASURANSI;\r\n3 = BPJS;',
	`metode` INT(11) NULL
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_medcheck_pen_ekg
DROP VIEW IF EXISTS `v_medcheck_pen_ekg`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_medcheck_pen_ekg` (
	`id` INT(11) NOT NULL,
	`id_medcheck` INT(11) NOT NULL,
	`id_user` INT(11) NULL,
	`id_analis` INT(11) NULL,
	`id_dokter` INT(11) NULL,
	`tgl_simpan` DATETIME NULL,
	`tgl_modif` DATETIME NULL,
	`poli` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`no_rm` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`pasien` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`tgl_lahir` DATE NULL,
	`jns_klm` ENUM('L','P') NULL COLLATE 'latin1_swedish_ci',
	`tipe` INT(11) NULL COMMENT '2=rajal;3=ranap;',
	`status` ENUM('0','1') NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_medcheck_pen_hrv
DROP VIEW IF EXISTS `v_medcheck_pen_hrv`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_medcheck_pen_hrv` (
	`id` INT(11) NOT NULL,
	`id_medcheck` INT(11) NOT NULL,
	`id_user` INT(11) NULL,
	`id_analis` INT(11) NULL,
	`id_dokter` INT(11) NULL,
	`tgl_simpan` DATETIME NULL,
	`tgl_modif` DATETIME NULL,
	`poli` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`no_rm` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`pasien` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`tgl_lahir` DATE NULL,
	`jns_klm` ENUM('L','P') NULL COLLATE 'latin1_swedish_ci',
	`tipe` INT(11) NULL COMMENT '2=rajal;3=ranap;',
	`status` ENUM('0','1') NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_medcheck_pen_spiro
DROP VIEW IF EXISTS `v_medcheck_pen_spiro`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_medcheck_pen_spiro` (
	`id` INT(11) NOT NULL,
	`id_medcheck` INT(11) NOT NULL,
	`id_user` INT(11) NULL,
	`id_analis` INT(11) NULL,
	`id_dokter` INT(11) NULL,
	`tgl_simpan` DATETIME NULL,
	`tgl_modif` DATETIME NULL,
	`poli` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`no_rm` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`pasien` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`tgl_lahir` DATE NULL,
	`jns_klm` ENUM('L','P') NULL COLLATE 'latin1_swedish_ci',
	`tipe` INT(11) NULL COMMENT '2=rajal;3=ranap;',
	`status` INT(11) NULL COMMENT '0=pend\\\\r\\\\n1=proses\\\\r\\\\n2=diterima\\\\r\\\\n3=ditolak\\\\r\\\\n4=farmasi\\\\r\\\\n5=farmasi_proses'
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_medcheck_plat
DROP VIEW IF EXISTS `v_medcheck_plat`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_medcheck_plat` (
	`id` INT(11) NOT NULL,
	`id_medcheck` INT(11) NOT NULL,
	`id_platform` INT(11) NOT NULL,
	`tgl_simpan` DATETIME NOT NULL,
	`no_nota` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`platform` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`keterangan` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`nominal` DECIMAL(32,2) NULL
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_medcheck_remun
DROP VIEW IF EXISTS `v_medcheck_remun`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_medcheck_remun` (
	`id` INT(11) NOT NULL,
	`id_dokter` INT(11) NULL,
	`tgl_simpan` TIMESTAMP NULL,
	`dokter` VARCHAR(1) NULL COLLATE 'latin1_general_ci',
	`dokter_blk` VARCHAR(1) NULL COLLATE 'latin1_general_ci',
	`poli` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`no_rm` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`nama_pgl` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`item` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`jml` DECIMAL(10,2) NULL,
	`harga` DECIMAL(10,2) NULL,
	`remun_nom` DECIMAL(10,2) NULL,
	`remun_subtotal` DECIMAL(10,2) NULL,
	`remun_perc` DECIMAL(10,2) NULL,
	`tipe` INT(11) NULL COMMENT '2=rajal;3=ranap;',
	`status_produk` INT(11) NULL COMMENT '2=tindakan\r\n3=lab\r\n4=obat\r\n5=radiologi\r\n6=racikan'
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_medcheck_resep
DROP VIEW IF EXISTS `v_medcheck_resep`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_medcheck_resep` (
	`id` INT(11) NULL,
	`id_dft` INT(11) NULL COMMENT 'ID yang diambil dari tbl_pendaftaran kolom id',
	`id_pasien` INT(11) NULL,
	`id_resep` INT(11) NOT NULL,
	`id_farmasi` INT(11) NULL,
	`id_user` INT(11) NULL,
	`tgl_simpan` DATETIME NULL,
	`no_rm` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`poli` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`nik` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`pasien` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`tgl_lahir` DATE NULL,
	`alamat` TEXT NULL COLLATE 'latin1_swedish_ci',
	`jns_klm` ENUM('L','P') NULL COLLATE 'latin1_swedish_ci',
	`tgl_resep_msk` DATETIME NULL,
	`tgl_resep_klr` DATETIME NULL,
	`no_resep` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`tipe` INT(11) NULL COMMENT '2=rajal;3=ranap;',
	`status` INT(11) NULL COMMENT '0=pend\r\n1=proses\r\n2=diterima\r\n3=ditolak\r\n4=farmasi\r\n5=farmasi_proses',
	`status_plg` ENUM('0','1') NULL COMMENT 'Status Obat Pulang untuk pasien rawat inap' COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_medcheck_rm
DROP VIEW IF EXISTS `v_medcheck_rm`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_medcheck_rm` (
	`id` INT(11) NOT NULL,
	`id_medcheck` INT(11) NULL,
	`id_user` INT(11) NULL,
	`id_dokter` INT(11) NULL,
	`id_pasien` INT(11) NULL,
	`id_icd10` INT(11) NULL,
	`tgl_simpan` DATETIME NULL,
	`tgl_masuk` DATETIME NULL,
	`kode` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`nama` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`tgl_lahir` DATE NULL,
	`anamnesa` MEDIUMTEXT NULL COLLATE 'utf8mb4_unicode_ci',
	`pemeriksaan` MEDIUMTEXT NULL COLLATE 'utf8mb4_unicode_ci',
	`diagnosa` TEXT NULL COLLATE 'latin1_swedish_ci',
	`terapi` MEDIUMTEXT NULL COLLATE 'utf8mb4_unicode_ci',
	`program` MEDIUMTEXT NULL COLLATE 'utf8mb4_unicode_ci',
	`ttv_skala` DECIMAL(10,2) NULL,
	`ttv_saturasi` DECIMAL(10,2) NULL,
	`ttv_laju` DECIMAL(10,2) NULL,
	`ttv_nadi` DECIMAL(10,2) NULL,
	`ttv_diastole` DECIMAL(10,2) NULL,
	`ttv_sistole` DECIMAL(10,2) NULL,
	`ttv_tb` DECIMAL(10,2) NULL,
	`ttv_bb` DECIMAL(10,2) NULL,
	`ttv_st` DECIMAL(10,2) NULL,
	`tipe` ENUM('0','1','2') NULL COMMENT '0 = nothing\r\n1 = perawat\r\n2 = dokter' COLLATE 'utf8mb4_unicode_ci',
	`status` ENUM('0','1','2') NULL COMMENT '0=tidak\r\n1=perawat\r\n2=dokter' COLLATE 'utf8mb4_unicode_ci',
	`status_bayar` INT(11) NULL COMMENT '0=belum;\r\n1=lunas;\r\n2=kurang;'
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_medcheck_rm_rj
DROP VIEW IF EXISTS `v_medcheck_rm_rj`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_medcheck_rm_rj` (
	`id` INT(11) NOT NULL,
	`id_pasien` INT(11) NULL,
	`tgl_simpan` DATETIME NULL,
	`tgl_masuk` DATETIME NULL,
	`kode` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`pasien` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`tgl_lahir` DATE NULL,
	`poli` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`diagnosa` TEXT NULL COLLATE 'latin1_swedish_ci',
	`kode_icd` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`icd` TEXT NULL COLLATE 'utf8mb4_general_ci',
	`diagnosa_en` TEXT NULL COLLATE 'utf8mb4_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_medcheck_tracer
DROP VIEW IF EXISTS `v_medcheck_tracer`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_medcheck_tracer` (
	`id` INT(11) NOT NULL,
	`id_poli` INT(11) NULL,
	`no_rm` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`nama_pgl` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`tgl_simpan` DATETIME NULL,
	`tanggal` DATE NULL,
	`wkt_daftar` DATETIME NULL,
	`wkt_periksa` DATETIME NULL,
	`wkt_sampling_msk` DATETIME NULL,
	`wkt_sampling_klr` DATETIME NULL,
	`wkt_rad_msk` DATETIME NULL,
	`wkt_rad_klr` DATETIME NULL,
	`wkt_rad_krm` DATETIME NULL,
	`wkt_rad_baca` DATETIME NULL,
	`wkt_resep_msk` DATETIME NULL,
	`wkt_resep_klr` DATETIME NULL,
	`wkt_resep_byr` DATETIME NULL,
	`wkt_resep_trm` DATETIME NULL,
	`wkt_farmasi_msk` DATETIME NULL,
	`wkt_farmasi_klr` DATETIME NULL,
	`wkt_ranap` DATETIME NULL,
	`wkt_ranap_keluar` DATETIME NULL,
	`wkt_selesai` DATETIME NULL,
	`tipe` INT(11) NULL COMMENT '2=rajal;3=ranap;',
	`status` INT(11) NULL COMMENT '1=anamnesa;\r\n2=tindakan;\r\n3=obat;\r\n4=laborat;\r\n5=dokter;\r\n6=pembayaran;\r\n7=finish'
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_medcheck_visit
DROP VIEW IF EXISTS `v_medcheck_visit`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_medcheck_visit` (
	`id` INT(11) NOT NULL,
	`id_pasien` INT(11) NULL,
	`id_poli` INT(11) NULL,
	`tgl_simpan` DATETIME NULL,
	`tgl_masuk` DATETIME NULL,
	`poli` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`no_rm` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`kode` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`nama` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`tgl_lahir` DATE NULL,
	`jml_gtotal` DECIMAL(10,2) NULL,
	`tipe` INT(11) NULL COMMENT '2=rajal;3=ranap;',
	`status_bayar` INT(11) NULL COMMENT '0=belum;\r\n1=lunas;\r\n2=kurang;'
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_pasien_poin
DROP VIEW IF EXISTS `v_pasien_poin`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_pasien_poin` (
	`id` INT(11) NOT NULL,
	`pasien` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`jml_poin` DECIMAL(10,2) NULL,
	`jml_poin_nom` DECIMAL(10,2) NULL
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_produk_hist
DROP VIEW IF EXISTS `v_produk_hist`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_produk_hist` (
	`id` INT(11) NOT NULL,
	`id_produk` INT(11) NULL,
	`id_gudang` INT(11) NULL,
	`id_user` INT(11) NULL,
	`id_pelanggan` INT(11) NULL,
	`id_supplier` INT(11) NULL,
	`id_penjualan` INT(11) NULL,
	`id_pembelian` INT(11) NULL,
	`id_pembelian_det` INT(11) NULL,
	`id_so` INT(11) NULL,
	`tgl_simpan` DATETIME NULL,
	`tgl_masuk` DATETIME NULL,
	`no_nota` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`kode` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`produk` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`keterangan` LONGTEXT NULL COLLATE 'utf8mb3_general_ci',
	`nominal` DECIMAL(10,2) NULL,
	`jml` INT(11) NULL,
	`jml_satuan` INT(11) NULL,
	`satuan` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`status` ENUM('1','2','3','4','5','6','7','8') NULL COMMENT '1 = Stok Masuk Pembelian\\r\\n2 = Stok Masuk\\r\\n3 = Stok Masuk Retur Jual\\r\\n4 = Stok Keluar Penjualan\\r\\n5 = Stok Keluar Retur Beli\\r\\n6 = SO\\r\\n7 = Stok Keluar\\r\\n8 = Mutasi Antar Gd' COLLATE 'utf8mb3_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_produk_stok
DROP VIEW IF EXISTS `v_produk_stok`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_produk_stok` (
	`id` INT(11) NULL,
	`kode` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`barcode` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`item` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`produk_alias` TEXT NULL COLLATE 'utf8mb3_general_ci',
	`produk_kand` TEXT NULL COLLATE 'utf8mb3_general_ci',
	`jml` FLOAT NULL,
	`stok` DOUBLE NULL,
	`status` INT(11) NULL COMMENT '2=tindakan\r\n3=lab\r\n4=obat\r\n5=radiologi\r\n6=racikan'
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_produk_stok_keluar
DROP VIEW IF EXISTS `v_produk_stok_keluar`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_produk_stok_keluar` (
	`id` INT(11) NOT NULL,
	`tgl_simpan` DATETIME NULL,
	`item` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`stok_keluar` DECIMAL(32,2) NULL
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_produk_stok_masuk
DROP VIEW IF EXISTS `v_produk_stok_masuk`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_produk_stok_masuk` (
	`id` INT(11) NOT NULL,
	`tgl_simpan` DATETIME NULL,
	`item` VARCHAR(1) NULL COLLATE 'utf8mb3_general_ci',
	`stok_masuk` DECIMAL(32,2) NULL
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_satusehat
DROP VIEW IF EXISTS `v_satusehat`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_satusehat` (
	`id` INT(11) NOT NULL,
	`id_location` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`id_encounter` TEXT NULL COLLATE 'utf8mb3_general_ci',
	`id_condition` TEXT NULL COLLATE 'utf8mb3_general_ci',
	`waktu_kedatangan` DATETIME NULL,
	`waktu_periksa` DATETIME NULL,
	`waktu_selesai_periksa` DATETIME NULL,
	`no_rm` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`no_register` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`nik_pasien` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`nama_pasien` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`nik_dokter` VARCHAR(1) NULL COLLATE 'latin1_general_ci',
	`nama_dokter` VARCHAR(1) NULL COLLATE 'latin1_general_ci',
	`kode_poliklinik` INT(11) NULL,
	`nama_poliklinik` VARCHAR(1) NULL COLLATE 'latin1_swedish_ci',
	`kode_diagnosa` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`nama_diagnosa` TEXT NULL COLLATE 'utf8mb4_general_ci'
) ENGINE=MyISAM;

-- Dumping structure for view esensiaco_medkit_dev.v_trans_kamar
DROP VIEW IF EXISTS `v_trans_kamar`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_trans_kamar` (
	`id` INT(11) NOT NULL,
	`kode` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`kamar` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`tipe` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci',
	`jml_max` INT(11) NULL,
	`jml` BIGINT(21) NULL,
	`sisa` BIGINT(22) NULL
) ENGINE=MyISAM;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_karyawan_absen`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_karyawan_absen` AS select `tbl_m_karyawan`.`id` AS `id`,`tbl_m_karyawan`.`id_user` AS `id_user`,`tbl_m_karyawan`.`nama_dpn` AS `nama_dpn`,`tbl_m_karyawan`.`nama` AS `nama`,`tbl_m_karyawan`.`nama_blk` AS `nama_blk`,`tbl_m_karyawan_absen`.`tgl_masuk` AS `tgl_masuk`,`tbl_m_karyawan_absen`.`wkt_masuk` AS `wkt_masuk`,`tbl_m_karyawan_absen`.`wkt_keluar` AS `wkt_keluar`,`tbl_m_karyawan_absen`.`scan1` AS `scan1`,`tbl_m_karyawan_absen`.`scan2` AS `scan2`,`tbl_m_karyawan_absen`.`scan3` AS `scan3`,`tbl_m_karyawan_absen`.`scan4` AS `scan4` from (`tbl_m_karyawan_absen` join `tbl_m_karyawan` on(`tbl_m_karyawan_absen`.`id_karyawan` = `tbl_m_karyawan`.`id`)) order by `tbl_m_karyawan_absen`.`tgl_masuk`;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_laporan_stok`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_laporan_stok` AS select `tbl_trans_medcheck_det`.`id` AS `id`,`tbl_trans_medcheck_det`.`tgl_simpan` AS `tgl_simpan`,`tbl_m_produk`.`produk` AS `item`,`tbl_m_produk`.`jml` AS `stok`,sum(`tbl_trans_medcheck_det`.`jml`) AS `laku`,`tbl_m_produk`.`jml` - sum(`tbl_trans_medcheck_det`.`jml`) AS `sisa_stok` from (`tbl_trans_medcheck_det` join `tbl_m_produk` on(`tbl_trans_medcheck_det`.`id_item` = `tbl_m_produk`.`id`)) where `tbl_trans_medcheck_det`.`status` = '4' and `tbl_trans_medcheck_det`.`jml` >= 0 group by `tbl_m_produk`.`produk` order by `tbl_trans_medcheck_det`.`id`;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_master_absen`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_master_absen` AS select `tbl_m_karyawan_absen`.`id` AS `id`,`tbl_m_karyawan_absen`.`id_user` AS `id_user`,`tbl_m_karyawan`.`nama_dpn` AS `nama_dpn`,`tbl_m_karyawan`.`nama` AS `nama`,`tbl_m_karyawan`.`nama_blk` AS `nama_blk`,`tbl_m_karyawan_absen`.`tgl_simpan` AS `tgl_simpan`,`tbl_m_karyawan_absen`.`tgl_masuk` AS `tgl_masuk`,`tbl_m_karyawan_absen`.`wkt_masuk` AS `wkt_masuk`,`tbl_m_karyawan_absen`.`wkt_keluar` AS `wkt_keluar`,`tbl_m_karyawan_absen`.`scan1` AS `scan1`,`tbl_m_karyawan_absen`.`scan2` AS `scan2`,`tbl_m_karyawan_absen`.`scan3` AS `scan3`,`tbl_m_karyawan_absen`.`scan4` AS `scan4` from (`tbl_m_karyawan_absen` join `tbl_m_karyawan` on(`tbl_m_karyawan_absen`.`id_karyawan` = `tbl_m_karyawan`.`id`)) order by `tbl_m_karyawan`.`id`;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_master_dokter`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_master_dokter` AS select `tbl_m_karyawan`.`id` AS `id`,`tbl_m_karyawan`.`id_user` AS `id_user`,`tbl_m_karyawan_jadwal`.`id_poli` AS `id_poli`,`tbl_m_poli`.`lokasi` AS `poli`,`tbl_m_karyawan`.`nama_dpn` AS `nama_dpn`,`tbl_m_karyawan`.`nama` AS `nama`,`tbl_m_karyawan`.`nama_blk` AS `nama_blk`,`tbl_m_karyawan_jadwal`.`hari_1` AS `hari_1`,`tbl_m_karyawan_jadwal`.`hari_2` AS `hari_2`,`tbl_m_karyawan_jadwal`.`hari_3` AS `hari_3`,`tbl_m_karyawan_jadwal`.`hari_4` AS `hari_4`,`tbl_m_karyawan_jadwal`.`hari_5` AS `hari_5`,`tbl_m_karyawan_jadwal`.`hari_6` AS `hari_6`,`tbl_m_karyawan_jadwal`.`hari_7` AS `hari_7`,`tbl_m_karyawan_jadwal`.`status_prtk` AS `status_prtk` from ((`tbl_m_karyawan_jadwal` join `tbl_m_karyawan` on(`tbl_m_karyawan_jadwal`.`id_karyawan` = `tbl_m_karyawan`.`id`)) join `tbl_m_poli` on(`tbl_m_karyawan_jadwal`.`id_poli` = `tbl_m_poli`.`id`)) order by `tbl_m_poli`.`id`;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_medcheck`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_medcheck` AS select `tbl_trans_medcheck`.`id` AS `id`,`tbl_trans_medcheck`.`id_user` AS `id_user`,`tbl_trans_medcheck`.`id_dokter` AS `id_dokter`,`tbl_trans_medcheck`.`id_nurse` AS `id_nurse`,`tbl_trans_medcheck`.`id_analis` AS `id_analis`,`tbl_trans_medcheck`.`id_pasien` AS `id_pasien`,`tbl_trans_medcheck`.`id_poli` AS `id_poli`,`tbl_trans_medcheck`.`id_dft` AS `id_dft`,`tbl_trans_medcheck`.`id_ant` AS `id_ant`,`tbl_trans_medcheck`.`id_kasir` AS `id_kasir`,`tbl_trans_medcheck`.`id_instansi` AS `id_instansi`,`tbl_trans_medcheck`.`id_encounter` AS `id_encounter`,`tbl_trans_medcheck`.`id_condition` AS `id_condition`,`tbl_m_poli`.`post_location` AS `id_location`,`tbl_trans_medcheck`.`id_icd` AS `id_icd`,`tbl_trans_medcheck`.`id_icd10` AS `id_icd10`,`tbl_trans_medcheck`.`tgl_simpan` AS `tgl_simpan`,`tbl_trans_medcheck`.`tgl_modif` AS `tgl_modif`,`tbl_trans_medcheck`.`tgl_masuk` AS `tgl_masuk`,`tbl_trans_medcheck`.`tgl_periksa` AS `tgl_periksa`,`tbl_trans_medcheck`.`tgl_periksa_lab` AS `tgl_periksa_lab`,`tbl_trans_medcheck`.`tgl_periksa_rad` AS `tgl_periksa_rad`,`tbl_trans_medcheck`.`tgl_periksa_pen` AS `tgl_periksa_pen`,`tbl_trans_medcheck`.`tgl_ranap` AS `tgl_ranap`,`tbl_trans_medcheck`.`tgl_keluar` AS `tgl_keluar`,`tbl_trans_medcheck`.`tgl_bayar` AS `tgl_bayar`,`tbl_trans_medcheck`.`tgl_ttd` AS `tgl_ttd`,`tbl_trans_medcheck`.`no_rm` AS `no_rm`,`tbl_trans_medcheck`.`no_akun` AS `no_akun`,`tbl_trans_medcheck`.`no_nota` AS `no_nota`,`tbl_m_poli`.`lokasi` AS `poli`,`tbl_m_karyawan`.`nik` AS `nik_dokter`,concat(`tbl_m_karyawan`.`nama_dpn`,' ',`tbl_m_karyawan`.`nama`,' ',`tbl_m_karyawan`.`nama_blk`) AS `nama_dokter`,concat(`tbl_m_pasien`.`kode_dpn`,'',`tbl_m_pasien`.`kode`) AS `kode`,`tbl_m_pasien`.`nik` AS `nik_pasien`,`tbl_trans_medcheck`.`pasien` AS `pasien`,`tbl_m_pasien`.`tgl_lahir` AS `tgl_lahir`,`tbl_trans_medcheck`.`keluhan` AS `keluhan`,`tbl_trans_medcheck`.`ttv` AS `ttv`,`tbl_trans_medcheck`.`ttv_st` AS `ttv_st`,`tbl_trans_medcheck`.`ttv_bb` AS `ttv_bb`,`tbl_trans_medcheck`.`ttv_tb` AS `ttv_tb`,`tbl_trans_medcheck`.`ttv_td` AS `ttv_td`,`tbl_trans_medcheck`.`ttv_sistole` AS `ttv_sistole`,`tbl_trans_medcheck`.`ttv_diastole` AS `ttv_diastole`,`tbl_trans_medcheck`.`ttv_nadi` AS `ttv_nadi`,`tbl_trans_medcheck`.`ttv_laju` AS `ttv_laju`,`tbl_trans_medcheck`.`ttv_saturasi` AS `ttv_saturasi`,`tbl_trans_medcheck`.`ttv_skala` AS `ttv_skala`,`tbl_trans_medcheck`.`diagnosa` AS `diagnosa`,`tbl_trans_medcheck`.`anamnesa` AS `anamnesa`,`tbl_trans_medcheck`.`pemeriksaan` AS `pemeriksaan`,`tbl_trans_medcheck`.`program` AS `program`,`tbl_trans_medcheck`.`alergi` AS `alergi`,`tbl_trans_medcheck`.`metode` AS `metode`,`tbl_trans_medcheck`.`platform` AS `platform`,`tbl_trans_medcheck`.`jml_total` AS `jml_total`,`tbl_trans_medcheck`.`jml_dp` AS `jml_dp`,`tbl_trans_medcheck`.`jml_diskon` AS `jml_diskon`,`tbl_trans_medcheck`.`jml_potongan` AS `jml_potongan`,`tbl_trans_medcheck`.`jml_potongan_poin` AS `jml_potongan_poin`,`tbl_trans_medcheck`.`jml_subtotal` AS `jml_subtotal`,`tbl_trans_medcheck`.`jml_ppn` AS `jml_ppn`,`tbl_trans_medcheck`.`ppn` AS `ppn`,`tbl_trans_medcheck`.`jml_gtotal` AS `jml_gtotal`,`tbl_trans_medcheck`.`jml_bayar` AS `jml_bayar`,`tbl_trans_medcheck`.`jml_kembali` AS `jml_kembali`,`tbl_trans_medcheck`.`jml_kurang` AS `jml_kurang`,`tbl_trans_medcheck`.`jml_poin` AS `jml_poin`,`tbl_trans_medcheck`.`jml_poin_nom` AS `jml_poin_nom`,`tbl_trans_medcheck`.`tipe` AS `tipe`,`tbl_trans_medcheck`.`tipe_bayar` AS `tipe_bayar`,`tbl_trans_medcheck`.`status` AS `status`,`tbl_trans_medcheck`.`status_bayar` AS `status_bayar`,`tbl_trans_medcheck`.`status_nota` AS `status_nota`,`tbl_trans_medcheck`.`status_hps` AS `status_hps`,`tbl_trans_medcheck`.`status_pos` AS `status_pos`,`tbl_trans_medcheck`.`status_periksa` AS `status_periksa` from (((`tbl_trans_medcheck` join `tbl_m_poli` on(`tbl_trans_medcheck`.`id_poli` = `tbl_m_poli`.`id`)) join `tbl_m_pasien` on(`tbl_trans_medcheck`.`id_pasien` = `tbl_m_pasien`.`id`)) left join `tbl_m_karyawan` on(`tbl_trans_medcheck`.`id_dokter` = `tbl_m_karyawan`.`id_user`)) where `tbl_trans_medcheck`.`status_hps` = '0' order by `tbl_trans_medcheck`.`id` desc;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_medcheck_apotik`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_medcheck_apotik` AS select `tbl_trans_medcheck`.`id` AS `id`,`tbl_trans_medcheck`.`id_pasien` AS `id_pasien`,`tbl_trans_medcheck`.`tgl_simpan` AS `tgl_simpan`,`tbl_trans_medcheck`.`pasien` AS `pasien`,`tbl_trans_medcheck_det`.`item` AS `item`,`tbl_trans_medcheck_det`.`harga` AS `harga`,`tbl_trans_medcheck_det`.`jml` AS `jml`,`tbl_trans_medcheck_det`.`subtotal` AS `subtotal` from ((`tbl_trans_medcheck_det` join `tbl_trans_medcheck` on(`tbl_trans_medcheck_det`.`id_medcheck` = `tbl_trans_medcheck`.`id`)) join `tbl_m_pasien` on(`tbl_trans_medcheck`.`id_pasien` = `tbl_m_pasien`.`id`)) where `tbl_trans_medcheck`.`tipe` = '6' and `tbl_trans_medcheck`.`status_bayar` = '1' order by `tbl_trans_medcheck`.`id`;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_medcheck_apres`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_medcheck_apres` AS select `tbl_trans_medcheck_apres`.`id` AS `id`,`tbl_trans_medcheck_apres`.`id_dokter` AS `id_dokter`,`tbl_trans_medcheck_apres`.`tgl_simpan` AS `tgl_simpan`,concat(`tbl_m_karyawan`.`nama_dpn`,' ',`tbl_m_karyawan`.`nama`) AS `dokter`,`tbl_m_karyawan`.`nama_blk` AS `dokter_blk`,`tbl_m_poli`.`lokasi` AS `poli`,`tbl_trans_medcheck`.`no_rm` AS `no_rm`,`tbl_m_pasien`.`nama_pgl` AS `nama_pgl`,`tbl_trans_medcheck_det`.`item` AS `item`,`tbl_trans_medcheck_det`.`jml` AS `jml`,`tbl_trans_medcheck_apres`.`harga` AS `harga`,`tbl_trans_medcheck_apres`.`apres_nom` AS `apres_nom`,`tbl_trans_medcheck_apres`.`apres_subtotal` AS `apres_subtotal`,`tbl_trans_medcheck_apres`.`apres_perc` AS `apres_perc`,`tbl_trans_medcheck`.`tipe` AS `tipe`,`tbl_m_produk`.`status` AS `status_produk` from ((((((`tbl_trans_medcheck_apres` join `tbl_trans_medcheck` on(`tbl_trans_medcheck`.`id` = `tbl_trans_medcheck_apres`.`id_medcheck`)) join `tbl_trans_medcheck_det` on(`tbl_trans_medcheck_det`.`id` = `tbl_trans_medcheck_apres`.`id_medcheck_det`)) join `tbl_m_pasien` on(`tbl_m_pasien`.`id` = `tbl_trans_medcheck`.`id_pasien`)) join `tbl_m_poli` on(`tbl_m_poli`.`id` = `tbl_trans_medcheck`.`id_poli`)) join `tbl_m_produk` on(`tbl_m_produk`.`id` = `tbl_trans_medcheck_apres`.`id_item`)) join `tbl_m_karyawan` on(`tbl_m_karyawan`.`id_user` = `tbl_trans_medcheck_apres`.`id_dokter`)) order by `tbl_trans_medcheck_apres`.`id` desc;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_medcheck_bukti`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_medcheck_bukti` AS select `tbl_trans_medcheck`.`id` AS `id`,`tbl_trans_medcheck`.`id_pasien` AS `id_pasien`,`tbl_trans_medcheck_file`.`id_user` AS `id_user`,`tbl_trans_medcheck_file`.`tgl_simpan` AS `tgl_simpan`,`tbl_ion_users`.`first_name` AS `username`,`tbl_trans_medcheck`.`no_rm` AS `no_rm`,`tbl_trans_medcheck`.`pasien` AS `pasien`,`tbl_trans_medcheck_file`.`judul` AS `judul`,`tbl_trans_medcheck_file`.`file_name` AS `file_name`,`tbl_trans_medcheck_file`.`status` AS `status` from ((`tbl_trans_medcheck_file` join `tbl_trans_medcheck` on(`tbl_trans_medcheck_file`.`id_medcheck` = `tbl_trans_medcheck`.`id`)) join `tbl_ion_users` on(`tbl_trans_medcheck_file`.`id_user` = `tbl_ion_users`.`id`)) where `tbl_trans_medcheck`.`status_hps` = '0' and `tbl_trans_medcheck_file`.`status` = '3' order by `tbl_trans_medcheck_file`.`tgl_simpan` desc;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_medcheck_dokter`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_medcheck_dokter` AS select `tbl_trans_medcheck`.`id` AS `id`,`tbl_trans_medcheck`.`id_dft` AS `id_dft`,`tbl_trans_medcheck`.`id_user` AS `id_user`,if(`tbl_trans_medcheck_dokter`.`id_dokter` <> '',`tbl_trans_medcheck_dokter`.`id_dokter`,`tbl_trans_medcheck`.`id_dokter`) AS `id_dokter`,`tbl_trans_medcheck`.`id_nurse` AS `id_nurse`,`tbl_trans_medcheck`.`id_analis` AS `id_analis`,`tbl_trans_medcheck`.`id_pasien` AS `id_pasien`,`tbl_trans_medcheck`.`id_poli` AS `id_poli`,`tbl_trans_medcheck`.`pasien` AS `pasien`,`tbl_trans_medcheck`.`no_nota` AS `no_nota`,`tbl_trans_medcheck`.`no_rm` AS `no_rm`,`tbl_trans_medcheck`.`tgl_simpan` AS `tgl_simpan`,cast(`tbl_trans_medcheck`.`tgl_masuk` as time) AS `waktu_masuk`,`tbl_trans_medcheck`.`tgl_bayar` AS `tgl_bayar`,`tbl_trans_medcheck`.`tgl_keluar` AS `tgl_keluar`,cast(`tbl_trans_medcheck`.`tgl_keluar` as time) AS `waktu_keluar`,`tbl_trans_medcheck`.`jml_total` AS `jml_total`,`tbl_trans_medcheck`.`jml_gtotal` AS `jml_gtotal`,`tbl_trans_medcheck`.`ppn` AS `ppn`,`tbl_trans_medcheck`.`jml_ppn` AS `jml_ppn`,`tbl_trans_medcheck`.`tipe` AS `tipe`,`tbl_trans_medcheck`.`status` AS `status`,`tbl_trans_medcheck`.`status_hps` AS `status_hps`,`tbl_trans_medcheck`.`status_nota` AS `status_nota`,`tbl_trans_medcheck`.`status_bayar` AS `status_bayar`,`tbl_trans_medcheck`.`status_periksa` AS `status_periksa` from (`tbl_trans_medcheck_dokter` join `tbl_trans_medcheck` on(`tbl_trans_medcheck_dokter`.`id_medcheck` = `tbl_trans_medcheck`.`id`)) where `tbl_trans_medcheck`.`status_pos` = '0' and `tbl_trans_medcheck`.`status_hps` = '0' order by `tbl_trans_medcheck`.`id` desc;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_medcheck_hapus`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_medcheck_hapus` AS select `tbl_trans_medcheck`.`id` AS `id`,`tbl_trans_medcheck`.`id_user` AS `id_user`,`tbl_trans_medcheck`.`id_dokter` AS `id_dokter`,`tbl_trans_medcheck`.`id_nurse` AS `id_nurse`,`tbl_trans_medcheck`.`id_analis` AS `id_analis`,`tbl_trans_medcheck`.`id_pasien` AS `id_pasien`,`tbl_trans_medcheck`.`id_poli` AS `id_poli`,`tbl_trans_medcheck`.`id_dft` AS `id_dft`,`tbl_trans_medcheck`.`id_ant` AS `id_ant`,`tbl_trans_medcheck`.`id_kasir` AS `id_kasir`,`tbl_trans_medcheck`.`id_icd` AS `id_icd`,`tbl_trans_medcheck`.`id_icd10` AS `id_icd10`,`tbl_trans_medcheck`.`tgl_simpan` AS `tgl_simpan`,`tbl_trans_medcheck`.`tgl_modif` AS `tgl_modif`,`tbl_trans_medcheck`.`tgl_masuk` AS `tgl_masuk`,`tbl_trans_medcheck`.`tgl_periksa` AS `tgl_periksa`,`tbl_trans_medcheck`.`tgl_periksa_lab` AS `tgl_periksa_lab`,`tbl_trans_medcheck`.`tgl_periksa_rad` AS `tgl_periksa_rad`,`tbl_trans_medcheck`.`tgl_periksa_pen` AS `tgl_periksa_pen`,`tbl_trans_medcheck`.`tgl_ranap` AS `tgl_ranap`,`tbl_trans_medcheck`.`tgl_keluar` AS `tgl_keluar`,`tbl_trans_medcheck`.`tgl_bayar` AS `tgl_bayar`,`tbl_trans_medcheck`.`tgl_ttd` AS `tgl_ttd`,`tbl_trans_medcheck`.`no_rm` AS `no_rm`,`tbl_trans_medcheck`.`no_akun` AS `no_akun`,`tbl_trans_medcheck`.`no_nota` AS `no_nota`,`tbl_m_poli`.`lokasi` AS `poli`,`tbl_m_pasien`.`nama_pgl` AS `pasien`,`tbl_trans_medcheck`.`keluhan` AS `keluhan`,`tbl_trans_medcheck`.`ttv` AS `ttv`,`tbl_trans_medcheck`.`ttv_st` AS `ttv_st`,`tbl_trans_medcheck`.`ttv_bb` AS `ttv_bb`,`tbl_trans_medcheck`.`ttv_tb` AS `ttv_tb`,`tbl_trans_medcheck`.`ttv_td` AS `ttv_td`,`tbl_trans_medcheck`.`ttv_sistole` AS `ttv_sistole`,`tbl_trans_medcheck`.`ttv_diastole` AS `ttv_diastole`,`tbl_trans_medcheck`.`ttv_nadi` AS `ttv_nadi`,`tbl_trans_medcheck`.`ttv_laju` AS `ttv_laju`,`tbl_trans_medcheck`.`ttv_saturasi` AS `ttv_saturasi`,`tbl_trans_medcheck`.`ttv_skala` AS `ttv_skala`,`tbl_trans_medcheck`.`diagnosa` AS `diagnosa`,`tbl_trans_medcheck`.`anamnesa` AS `anamnesa`,`tbl_trans_medcheck`.`pemeriksaan` AS `pemeriksaan`,`tbl_trans_medcheck`.`program` AS `program`,`tbl_trans_medcheck`.`alergi` AS `alergi`,`tbl_trans_medcheck`.`metode` AS `metode`,`tbl_trans_medcheck`.`platform` AS `platform`,`tbl_trans_medcheck`.`jml_total` AS `jml_total`,`tbl_trans_medcheck`.`jml_dp` AS `jml_dp`,`tbl_trans_medcheck`.`jml_diskon` AS `jml_diskon`,`tbl_trans_medcheck`.`jml_potongan` AS `jml_potongan`,`tbl_trans_medcheck`.`jml_subtotal` AS `jml_subtotal`,`tbl_trans_medcheck`.`jml_ppn` AS `jml_ppn`,`tbl_trans_medcheck`.`ppn` AS `ppn`,`tbl_trans_medcheck`.`jml_gtotal` AS `jml_gtotal`,`tbl_trans_medcheck`.`jml_bayar` AS `jml_bayar`,`tbl_trans_medcheck`.`jml_kembali` AS `jml_kembali`,`tbl_trans_medcheck`.`jml_kurang` AS `jml_kurang`,`tbl_trans_medcheck`.`tipe` AS `tipe`,`tbl_trans_medcheck`.`tipe_bayar` AS `tipe_bayar`,`tbl_trans_medcheck`.`status` AS `status`,`tbl_trans_medcheck`.`status_bayar` AS `status_bayar`,`tbl_trans_medcheck`.`status_nota` AS `status_nota`,`tbl_trans_medcheck`.`status_hps` AS `status_hps`,`tbl_trans_medcheck`.`status_pos` AS `status_pos`,`tbl_trans_medcheck`.`status_periksa` AS `status_periksa` from ((`tbl_trans_medcheck` join `tbl_m_pasien` on(`tbl_trans_medcheck`.`id_pasien` = `tbl_m_pasien`.`id`)) join `tbl_m_poli` on(`tbl_trans_medcheck`.`id_poli` = `tbl_m_poli`.`id`)) where `tbl_trans_medcheck`.`status_hps` = '1' order by `tbl_trans_medcheck`.`id` desc;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_medcheck_mcu`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_medcheck_mcu` AS select `tbl_trans_medcheck_resume`.`id` AS `id`,`tbl_trans_medcheck_resume`.`id_medcheck` AS `id_medcheck`,`tbl_pendaftaran`.`id_instansi` AS `id_instansi`,`tbl_m_pasien`.`id` AS `id_pasien`,`tbl_trans_medcheck_resume`.`id_user` AS `id_user`,`tbl_trans_medcheck_resume`.`tgl_simpan` AS `tgl_simpan`,`tbl_m_pasien`.`nama_pgl` AS `nama_pgl`,`tbl_trans_medcheck_resume`.`no_surat` AS `no_surat`,`tbl_trans_medcheck_resume`.`saran` AS `saran`,`tbl_trans_medcheck_resume`.`kesimpulan` AS `kesimpulan` from (((`tbl_trans_medcheck_resume` join `tbl_trans_medcheck` on(`tbl_trans_medcheck_resume`.`id_medcheck` = `tbl_trans_medcheck`.`id`)) join `tbl_m_pasien` on(`tbl_trans_medcheck`.`id_pasien` = `tbl_m_pasien`.`id`)) join `tbl_pendaftaran` on(`tbl_trans_medcheck`.`id_dft` = `tbl_pendaftaran`.`id`)) where `tbl_trans_medcheck`.`tipe` = '5' order by `tbl_trans_medcheck_resume`.`id` desc;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_medcheck_omset`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_medcheck_omset` AS select `tbl_trans_medcheck_det`.`id` AS `id`,`tbl_trans_medcheck`.`id` AS `id_medcheck`,`tbl_trans_medcheck`.`id_pasien` AS `id_pasien`,`tbl_trans_medcheck`.`id_poli` AS `id_poli`,`tbl_trans_medcheck`.`id_dokter` AS `id_dokter`,`tbl_trans_medcheck_det`.`id_item` AS `id_item`,`tbl_trans_medcheck_det`.`id_item_kat` AS `id_item_kat`,`tbl_trans_medcheck_det`.`tgl_simpan` AS `tgl_simpan`,`tbl_trans_medcheck`.`tgl_masuk` AS `tgl_masuk`,`tbl_trans_medcheck`.`tgl_bayar` AS `tgl_bayar`,`tbl_trans_medcheck`.`no_akun` AS `no_akun`,`tbl_trans_medcheck`.`no_rm` AS `no_rm`,`tbl_m_pasien`.`nama_pgl` AS `pasien`,`tbl_m_pasien`.`tgl_lahir` AS `tgl_lahir`,`tbl_trans_medcheck_det`.`kode` AS `kode`,`tbl_trans_medcheck_det`.`item` AS `item`,`tbl_trans_medcheck_det`.`jml` AS `jml`,`tbl_trans_medcheck_det`.`harga` AS `harga`,`tbl_trans_medcheck_det`.`diskon` AS `diskon`,`tbl_trans_medcheck_det`.`potongan` AS `potongan`,`tbl_trans_medcheck_det`.`potongan_poin` AS `potongan_poin`,`tbl_trans_medcheck_det`.`subtotal` AS `subtotal`,`tbl_trans_medcheck`.`jml_gtotal` AS `jml_gtotal`,`tbl_trans_medcheck_det`.`status_pkt` AS `status_pkt`,`tbl_trans_medcheck_det`.`status` AS `status`,`tbl_trans_medcheck`.`tipe` AS `tipe`,`tbl_trans_medcheck`.`tipe_bayar` AS `tipe_bayar`,`tbl_trans_medcheck`.`metode` AS `metode` from ((`tbl_trans_medcheck_det` join `tbl_trans_medcheck` on(`tbl_trans_medcheck_det`.`id_medcheck` = `tbl_trans_medcheck`.`id`)) join `tbl_m_pasien` on(`tbl_trans_medcheck`.`id_pasien` = `tbl_m_pasien`.`id`)) where `tbl_trans_medcheck`.`status_hps` = '0' and `tbl_trans_medcheck`.`status_bayar` = '1' order by `tbl_trans_medcheck_det`.`id` desc;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_medcheck_pen_ekg`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_medcheck_pen_ekg` AS select `tbl_trans_medcheck_lab_ekg`.`id` AS `id`,`tbl_trans_medcheck_lab_ekg`.`id_medcheck` AS `id_medcheck`,`tbl_trans_medcheck_lab_ekg`.`id_user` AS `id_user`,`tbl_trans_medcheck_lab_ekg`.`id_analis` AS `id_analis`,`tbl_trans_medcheck_lab_ekg`.`id_dokter` AS `id_dokter`,`tbl_trans_medcheck_lab_ekg`.`tgl_simpan` AS `tgl_simpan`,`tbl_trans_medcheck_lab_ekg`.`tgl_modif` AS `tgl_modif`,`tbl_m_poli`.`lokasi` AS `poli`,`tbl_trans_medcheck`.`no_rm` AS `no_rm`,`tbl_trans_medcheck`.`pasien` AS `pasien`,`tbl_m_pasien`.`tgl_lahir` AS `tgl_lahir`,`tbl_m_pasien`.`jns_klm` AS `jns_klm`,`tbl_trans_medcheck`.`tipe` AS `tipe`,`tbl_trans_medcheck_lab_ekg`.`status` AS `status` from (((`tbl_trans_medcheck_lab_ekg` join `tbl_trans_medcheck` on(`tbl_trans_medcheck_lab_ekg`.`id_medcheck` = `tbl_trans_medcheck`.`id`)) join `tbl_m_pasien` on(`tbl_trans_medcheck`.`id_pasien` = `tbl_m_pasien`.`id`)) join `tbl_m_poli` on(`tbl_trans_medcheck`.`id_poli` = `tbl_m_poli`.`id`)) order by `tbl_trans_medcheck`.`id` desc;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_medcheck_pen_hrv`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_medcheck_pen_hrv` AS select `tbl_trans_medcheck_pen_hrv`.`id` AS `id`,`tbl_trans_medcheck_pen_hrv`.`id_medcheck` AS `id_medcheck`,`tbl_trans_medcheck_pen_hrv`.`id_user` AS `id_user`,`tbl_trans_medcheck_pen_hrv`.`id_analis` AS `id_analis`,`tbl_trans_medcheck_pen_hrv`.`id_dokter` AS `id_dokter`,`tbl_trans_medcheck_pen_hrv`.`tgl_simpan` AS `tgl_simpan`,`tbl_trans_medcheck_pen_hrv`.`tgl_modif` AS `tgl_modif`,`tbl_m_poli`.`lokasi` AS `poli`,`tbl_trans_medcheck`.`no_rm` AS `no_rm`,`tbl_trans_medcheck`.`pasien` AS `pasien`,`tbl_m_pasien`.`tgl_lahir` AS `tgl_lahir`,`tbl_m_pasien`.`jns_klm` AS `jns_klm`,`tbl_trans_medcheck`.`tipe` AS `tipe`,`tbl_trans_medcheck_pen_hrv`.`status` AS `status` from (((`tbl_trans_medcheck_pen_hrv` join `tbl_trans_medcheck` on(`tbl_trans_medcheck_pen_hrv`.`id_medcheck` = `tbl_trans_medcheck`.`id`)) join `tbl_m_pasien` on(`tbl_trans_medcheck`.`id_pasien` = `tbl_m_pasien`.`id`)) join `tbl_m_poli` on(`tbl_trans_medcheck`.`id_poli` = `tbl_m_poli`.`id`)) order by `tbl_trans_medcheck`.`id` desc;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_medcheck_pen_spiro`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_medcheck_pen_spiro` AS select `tbl_trans_medcheck_lab_spiro`.`id` AS `id`,`tbl_trans_medcheck_lab_spiro`.`id_medcheck` AS `id_medcheck`,`tbl_trans_medcheck_lab_spiro`.`id_user` AS `id_user`,`tbl_trans_medcheck_lab_spiro`.`id_analis` AS `id_analis`,`tbl_trans_medcheck_lab_spiro`.`id_dokter` AS `id_dokter`,`tbl_trans_medcheck_lab_spiro`.`tgl_simpan` AS `tgl_simpan`,`tbl_trans_medcheck_lab_spiro`.`tgl_modif` AS `tgl_modif`,`tbl_m_poli`.`lokasi` AS `poli`,`tbl_trans_medcheck`.`no_rm` AS `no_rm`,`tbl_trans_medcheck`.`pasien` AS `pasien`,`tbl_m_pasien`.`tgl_lahir` AS `tgl_lahir`,`tbl_m_pasien`.`jns_klm` AS `jns_klm`,`tbl_trans_medcheck`.`tipe` AS `tipe`,`tbl_trans_medcheck_lab_spiro`.`status` AS `status` from (((`tbl_trans_medcheck_lab_spiro` join `tbl_trans_medcheck` on(`tbl_trans_medcheck_lab_spiro`.`id_medcheck` = `tbl_trans_medcheck`.`id`)) join `tbl_m_pasien` on(`tbl_trans_medcheck`.`id_pasien` = `tbl_m_pasien`.`id`)) join `tbl_m_poli` on(`tbl_trans_medcheck`.`id_poli` = `tbl_m_poli`.`id`)) order by `tbl_trans_medcheck`.`id` desc;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_medcheck_plat`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_medcheck_plat` AS select `tbl_trans_medcheck_plat`.`id` AS `id`,`tbl_trans_medcheck_plat`.`id_medcheck` AS `id_medcheck`,`tbl_trans_medcheck_plat`.`id_platform` AS `id_platform`,`tbl_trans_medcheck_plat`.`tgl_simpan` AS `tgl_simpan`,`tbl_trans_medcheck_plat`.`no_nota` AS `no_nota`,`tbl_trans_medcheck_plat`.`platform` AS `platform`,`tbl_trans_medcheck_plat`.`keterangan` AS `keterangan`,`tbl_trans_medcheck_plat`.`nominal` AS `nominal` from `tbl_trans_medcheck_plat` group by `tbl_trans_medcheck_plat`.`id_medcheck`,`tbl_trans_medcheck_plat`.`id_platform`,`tbl_trans_medcheck_plat`.`tgl_simpan`,`tbl_trans_medcheck_plat`.`nominal` order by `tbl_trans_medcheck_plat`.`id` desc;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_medcheck_remun`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_medcheck_remun` AS select `tbl_trans_medcheck_remun`.`id` AS `id`,`tbl_trans_medcheck_remun`.`id_dokter` AS `id_dokter`,`tbl_trans_medcheck_remun`.`tgl_simpan` AS `tgl_simpan`,concat(`tbl_m_karyawan`.`nama_dpn`,' ',`tbl_m_karyawan`.`nama`) AS `dokter`,`tbl_m_karyawan`.`nama_blk` AS `dokter_blk`,`tbl_m_poli`.`lokasi` AS `poli`,`tbl_trans_medcheck`.`no_rm` AS `no_rm`,`tbl_m_pasien`.`nama_pgl` AS `nama_pgl`,`tbl_trans_medcheck_det`.`item` AS `item`,`tbl_trans_medcheck_det`.`jml` AS `jml`,`tbl_trans_medcheck_remun`.`harga` AS `harga`,`tbl_trans_medcheck_remun`.`remun_nom` AS `remun_nom`,`tbl_trans_medcheck_remun`.`remun_subtotal` AS `remun_subtotal`,`tbl_trans_medcheck_remun`.`remun_perc` AS `remun_perc`,`tbl_trans_medcheck`.`tipe` AS `tipe`,`tbl_m_produk`.`status` AS `status_produk` from ((((((`tbl_trans_medcheck_remun` join `tbl_trans_medcheck` on(`tbl_trans_medcheck`.`id` = `tbl_trans_medcheck_remun`.`id_medcheck`)) join `tbl_trans_medcheck_det` on(`tbl_trans_medcheck_det`.`id` = `tbl_trans_medcheck_remun`.`id_medcheck_det`)) join `tbl_m_pasien` on(`tbl_m_pasien`.`id` = `tbl_trans_medcheck`.`id_pasien`)) join `tbl_m_poli` on(`tbl_m_poli`.`id` = `tbl_trans_medcheck`.`id_poli`)) join `tbl_m_produk` on(`tbl_m_produk`.`id` = `tbl_trans_medcheck_remun`.`id_item`)) join `tbl_m_karyawan` on(`tbl_m_karyawan`.`id_user` = `tbl_trans_medcheck_remun`.`id_dokter`)) order by `tbl_trans_medcheck_remun`.`id` desc;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_medcheck_resep`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_medcheck_resep` AS select `tbl_trans_medcheck`.`id` AS `id`,`tbl_trans_medcheck`.`id_dft` AS `id_dft`,`tbl_trans_medcheck`.`id_pasien` AS `id_pasien`,`tbl_trans_medcheck_resep`.`id` AS `id_resep`,`tbl_trans_medcheck`.`id_farmasi` AS `id_farmasi`,`tbl_trans_medcheck_resep`.`id_user` AS `id_user`,`tbl_trans_medcheck`.`tgl_simpan` AS `tgl_simpan`,`tbl_trans_medcheck`.`no_rm` AS `no_rm`,`tbl_m_poli`.`lokasi` AS `poli`,`tbl_m_pasien`.`nik` AS `nik`,`tbl_m_pasien`.`nama_pgl` AS `pasien`,`tbl_m_pasien`.`tgl_lahir` AS `tgl_lahir`,`tbl_m_pasien`.`alamat` AS `alamat`,`tbl_m_pasien`.`jns_klm` AS `jns_klm`,`tbl_trans_medcheck_resep`.`tgl_simpan` AS `tgl_resep_msk`,`tbl_trans_medcheck`.`tgl_ttd` AS `tgl_resep_klr`,`tbl_trans_medcheck_resep`.`no_resep` AS `no_resep`,`tbl_trans_medcheck`.`tipe` AS `tipe`,`tbl_trans_medcheck_resep`.`status` AS `status`,`tbl_trans_medcheck_resep`.`status_plg` AS `status_plg` from (`tbl_trans_medcheck_resep` left join (((`tbl_trans_medcheck` join `tbl_m_pasien` on(`tbl_trans_medcheck`.`id_pasien` = `tbl_m_pasien`.`id`)) join `tbl_m_poli` on(`tbl_trans_medcheck`.`id_poli` = `tbl_m_poli`.`id`)) join `tbl_pendaftaran` on(`tbl_trans_medcheck`.`id_dft` = `tbl_pendaftaran`.`id`)) on(`tbl_trans_medcheck`.`id` = `tbl_trans_medcheck_resep`.`id_medcheck`)) order by `tbl_trans_medcheck_resep`.`id` desc;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_medcheck_rm`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_medcheck_rm` AS select `tbl_trans_medcheck_rm`.`id` AS `id`,`tbl_trans_medcheck_rm`.`id_medcheck` AS `id_medcheck`,`tbl_trans_medcheck_rm`.`id_user` AS `id_user`,`tbl_trans_medcheck_rm`.`id_dokter` AS `id_dokter`,`tbl_trans_medcheck_rm`.`id_pasien` AS `id_pasien`,`tbl_trans_medcheck_rm`.`id_icd10` AS `id_icd10`,`tbl_trans_medcheck_rm`.`tgl_simpan` AS `tgl_simpan`,`tbl_trans_medcheck`.`tgl_masuk` AS `tgl_masuk`,`tbl_trans_medcheck`.`no_rm` AS `kode`,`tbl_m_pasien`.`nama_pgl` AS `nama`,`tbl_m_pasien`.`tgl_lahir` AS `tgl_lahir`,`tbl_trans_medcheck_rm`.`anamnesa` AS `anamnesa`,`tbl_trans_medcheck_rm`.`pemeriksaan` AS `pemeriksaan`,`tbl_trans_medcheck`.`diagnosa` AS `diagnosa`,`tbl_trans_medcheck_rm`.`terapi` AS `terapi`,`tbl_trans_medcheck_rm`.`program` AS `program`,`tbl_trans_medcheck_rm`.`ttv_skala` AS `ttv_skala`,`tbl_trans_medcheck_rm`.`ttv_saturasi` AS `ttv_saturasi`,`tbl_trans_medcheck_rm`.`ttv_laju` AS `ttv_laju`,`tbl_trans_medcheck_rm`.`ttv_nadi` AS `ttv_nadi`,`tbl_trans_medcheck_rm`.`ttv_diastole` AS `ttv_diastole`,`tbl_trans_medcheck_rm`.`ttv_sistole` AS `ttv_sistole`,`tbl_trans_medcheck_rm`.`ttv_tb` AS `ttv_tb`,`tbl_trans_medcheck_rm`.`ttv_bb` AS `ttv_bb`,`tbl_trans_medcheck_rm`.`ttv_st` AS `ttv_st`,`tbl_trans_medcheck_rm`.`tipe` AS `tipe`,`tbl_trans_medcheck_rm`.`status` AS `status`,`tbl_trans_medcheck`.`status_bayar` AS `status_bayar` from ((`tbl_trans_medcheck_rm` join `tbl_trans_medcheck` on(`tbl_trans_medcheck_rm`.`id_medcheck` = `tbl_trans_medcheck`.`id`)) join `tbl_m_pasien` on(`tbl_trans_medcheck_rm`.`id_pasien` = `tbl_m_pasien`.`id`)) where `tbl_trans_medcheck`.`status_hps` = '0' order by `tbl_trans_medcheck_rm`.`id` desc;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_medcheck_rm_rj`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_medcheck_rm_rj` AS select `tbl_trans_medcheck`.`id` AS `id`,`tbl_trans_medcheck`.`id_pasien` AS `id_pasien`,`tbl_trans_medcheck`.`tgl_simpan` AS `tgl_simpan`,`tbl_trans_medcheck`.`tgl_masuk` AS `tgl_masuk`,concat(`tbl_m_pasien`.`kode_dpn`,'',`tbl_m_pasien`.`kode`) AS `kode`,`tbl_trans_medcheck`.`pasien` AS `pasien`,`tbl_m_pasien`.`tgl_lahir` AS `tgl_lahir`,`tbl_m_poli`.`lokasi` AS `poli`,`tbl_trans_medcheck`.`diagnosa` AS `diagnosa`,`tbl_trans_medcheck_icd`.`kode` AS `kode_icd`,`tbl_trans_medcheck_icd`.`icd` AS `icd`,`tbl_trans_medcheck_icd`.`diagnosa_en` AS `diagnosa_en` from (((`tbl_trans_medcheck` join `tbl_trans_medcheck_icd` on(`tbl_trans_medcheck`.`id` = `tbl_trans_medcheck_icd`.`id_medcheck`)) join `tbl_m_pasien` on(`tbl_trans_medcheck`.`id_pasien` = `tbl_m_pasien`.`id`)) join `tbl_m_poli` on(`tbl_trans_medcheck`.`id_poli` = `tbl_m_poli`.`id`)) where `tbl_trans_medcheck`.`tipe` = '2' order by `tbl_trans_medcheck`.`id` desc;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_medcheck_tracer`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_medcheck_tracer` AS select distinct `tbl_trans_medcheck`.`id` AS `id`,`tbl_trans_medcheck`.`id_poli` AS `id_poli`,`tbl_trans_medcheck`.`no_rm` AS `no_rm`,`tbl_pendaftaran`.`nama_pgl` AS `nama_pgl`,`tbl_trans_medcheck`.`tgl_simpan` AS `tgl_simpan`,cast(`tbl_trans_medcheck`.`tgl_simpan` as date) AS `tanggal`,`tbl_pendaftaran`.`tgl_simpan` AS `wkt_daftar`,`tbl_trans_medcheck`.`tgl_periksa` AS `wkt_periksa`,`tbl_trans_medcheck_lab`.`tgl_simpan` AS `wkt_sampling_msk`,`tbl_trans_medcheck_lab`.`tgl_keluar` AS `wkt_sampling_klr`,`tbl_trans_medcheck_rad`.`tgl_simpan` AS `wkt_rad_msk`,`tbl_trans_medcheck`.`tgl_periksa_rad_keluar` AS `wkt_rad_klr`,`tbl_trans_medcheck`.`tgl_periksa_rad_kirim` AS `wkt_rad_krm`,`tbl_trans_medcheck`.`tgl_periksa_rad_baca` AS `wkt_rad_baca`,`tbl_trans_medcheck`.`tgl_resep_msk` AS `wkt_resep_msk`,`tbl_trans_medcheck`.`tgl_resep_msk` AS `wkt_resep_klr`,`tbl_trans_medcheck`.`tgl_bayar` AS `wkt_resep_byr`,`tbl_trans_medcheck`.`tgl_ttd` AS `wkt_resep_trm`,`tbl_trans_medcheck_resep`.`tgl_simpan` AS `wkt_farmasi_msk`,`tbl_trans_medcheck_resep`.`tgl_keluar` AS `wkt_farmasi_klr`,`tbl_trans_medcheck`.`tgl_ranap` AS `wkt_ranap`,`tbl_trans_medcheck`.`tgl_ranap_keluar` AS `wkt_ranap_keluar`,`tbl_trans_medcheck`.`tgl_bayar` AS `wkt_selesai`,`tbl_trans_medcheck`.`tipe` AS `tipe`,`tbl_trans_medcheck`.`status` AS `status` from ((((`tbl_trans_medcheck` join `tbl_pendaftaran` on(`tbl_pendaftaran`.`id` = `tbl_trans_medcheck`.`id_dft`)) left join `tbl_trans_medcheck_lab` on(`tbl_trans_medcheck_lab`.`id_medcheck` = `tbl_trans_medcheck`.`id`)) left join `tbl_trans_medcheck_rad` on(`tbl_trans_medcheck_rad`.`id_medcheck` = `tbl_trans_medcheck`.`id`)) left join `tbl_trans_medcheck_resep` on(`tbl_trans_medcheck_resep`.`id_medcheck` = `tbl_trans_medcheck`.`id`)) where `tbl_trans_medcheck`.`status_hps` = '0' group by `tbl_trans_medcheck`.`no_rm`,`tbl_trans_medcheck`.`tgl_simpan` order by `tbl_trans_medcheck`.`id` desc;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_medcheck_visit`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_medcheck_visit` AS select `v_medcheck`.`id` AS `id`,`v_medcheck`.`id_pasien` AS `id_pasien`,`v_medcheck`.`id_poli` AS `id_poli`,`v_medcheck`.`tgl_bayar` AS `tgl_simpan`,`v_medcheck`.`tgl_masuk` AS `tgl_masuk`,`v_medcheck`.`poli` AS `poli`,`v_medcheck`.`no_rm` AS `no_rm`,`v_medcheck`.`kode` AS `kode`,`v_medcheck`.`pasien` AS `nama`,`v_medcheck`.`tgl_lahir` AS `tgl_lahir`,`v_medcheck`.`jml_gtotal` AS `jml_gtotal`,`v_medcheck`.`tipe` AS `tipe`,`v_medcheck`.`status_bayar` AS `status_bayar` from `v_medcheck` order by `v_medcheck`.`id` desc;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_pasien_poin`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_pasien_poin` AS select `tbl_m_pasien`.`id` AS `id`,`tbl_m_pasien`.`nama_pgl` AS `pasien`,`tbl_m_pasien_poin`.`jml_poin` AS `jml_poin`,`tbl_m_pasien_poin`.`jml_poin_nom` AS `jml_poin_nom` from (`tbl_m_pasien_poin` join `tbl_m_pasien` on(`tbl_m_pasien_poin`.`id_pasien` = `tbl_m_pasien`.`id`)) order by `tbl_m_pasien_poin`.`jml_poin` desc;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_produk_hist`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_produk_hist` AS select `tbl_m_produk_hist`.`id` AS `id`,`tbl_m_produk_hist`.`id_produk` AS `id_produk`,`tbl_m_produk_hist`.`id_gudang` AS `id_gudang`,`tbl_m_produk_hist`.`id_user` AS `id_user`,`tbl_m_produk_hist`.`id_pelanggan` AS `id_pelanggan`,`tbl_m_produk_hist`.`id_supplier` AS `id_supplier`,`tbl_m_produk_hist`.`id_penjualan` AS `id_penjualan`,`tbl_m_produk_hist`.`id_pembelian` AS `id_pembelian`,`tbl_m_produk_hist`.`id_pembelian_det` AS `id_pembelian_det`,`tbl_m_produk_hist`.`id_so` AS `id_so`,`tbl_m_produk_hist`.`tgl_simpan` AS `tgl_simpan`,`tbl_m_produk_hist`.`tgl_masuk` AS `tgl_masuk`,`tbl_m_produk_hist`.`no_nota` AS `no_nota`,`tbl_m_produk`.`kode` AS `kode`,`tbl_m_produk`.`produk` AS `produk`,`tbl_m_produk_hist`.`keterangan` AS `keterangan`,`tbl_m_produk_hist`.`nominal` AS `nominal`,`tbl_m_produk_hist`.`jml` AS `jml`,`tbl_m_produk_hist`.`jml_satuan` AS `jml_satuan`,`tbl_m_produk_hist`.`satuan` AS `satuan`,`tbl_m_produk_hist`.`status` AS `status` from (`tbl_m_produk_hist` join `tbl_m_produk` on(`tbl_m_produk_hist`.`id_produk` = `tbl_m_produk`.`id`)) order by `tbl_m_produk_hist`.`id` desc;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_produk_stok`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_produk_stok` AS select `tbl_m_produk`.`id` AS `id`,`tbl_m_produk`.`kode` AS `kode`,`tbl_m_produk`.`barcode` AS `barcode`,`tbl_m_produk`.`produk` AS `item`,`tbl_m_produk`.`produk_alias` AS `produk_alias`,`tbl_m_produk`.`produk_kand` AS `produk_kand`,`tbl_m_produk`.`jml` AS `jml`,sum(`tbl_m_produk_stok`.`jml`) AS `stok`,`tbl_m_produk`.`status` AS `status` from (`tbl_m_produk_stok` left join `tbl_m_produk` on(`tbl_m_produk`.`id` = `tbl_m_produk_stok`.`id_produk`)) group by `tbl_m_produk`.`produk` order by `tbl_m_produk`.`produk`;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_produk_stok_keluar`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_produk_stok_keluar` AS select `tbl_m_produk`.`id` AS `id`,`tbl_trans_medcheck_det`.`tgl_simpan` AS `tgl_simpan`,`tbl_m_produk`.`produk` AS `item`,sum(`tbl_trans_medcheck_det`.`jml`) AS `stok_keluar` from (`tbl_trans_medcheck_det` join `tbl_m_produk` on(`tbl_trans_medcheck_det`.`id_item` = `tbl_m_produk`.`id`)) where `tbl_m_produk`.`status` = '4' group by `tbl_trans_medcheck_det`.`tgl_simpan` order by `tbl_m_produk`.`id`,`tbl_trans_medcheck_det`.`tgl_simpan`;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_produk_stok_masuk`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_produk_stok_masuk` AS select `tbl_m_produk`.`id` AS `id`,`tbl_trans_beli_det`.`tgl_simpan` AS `tgl_simpan`,`tbl_m_produk`.`produk` AS `item`,sum(`tbl_trans_beli_det`.`jml`) AS `stok_masuk` from (`tbl_trans_beli_det` join `tbl_m_produk` on(`tbl_trans_beli_det`.`id_produk` = `tbl_m_produk`.`id`)) where `tbl_m_produk`.`status` = '4' group by `tbl_trans_beli_det`.`tgl_simpan` order by `tbl_m_produk`.`id`,`tbl_trans_beli_det`.`tgl_simpan`;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_satusehat`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_satusehat` AS select `v_medcheck`.`id` AS `id`,`v_medcheck`.`id_location` AS `id_location`,`v_medcheck`.`id_encounter` AS `id_encounter`,`v_medcheck`.`id_condition` AS `id_condition`,`v_medcheck`.`tgl_simpan` AS `waktu_kedatangan`,`v_medcheck`.`tgl_periksa` AS `waktu_periksa`,`v_medcheck`.`tgl_modif` AS `waktu_selesai_periksa`,`v_medcheck`.`kode` AS `no_rm`,`v_medcheck`.`no_rm` AS `no_register`,`tbl_m_pasien`.`nik` AS `nik_pasien`,`tbl_m_pasien`.`nama_pgl` AS `nama_pasien`,`v_medcheck`.`nik_dokter` AS `nik_dokter`,`v_medcheck`.`nama_dokter` AS `nama_dokter`,`v_medcheck`.`id_poli` AS `kode_poliklinik`,`v_medcheck`.`poli` AS `nama_poliklinik`,`tbl_m_icd`.`kode` AS `kode_diagnosa`,`tbl_m_icd`.`icd` AS `nama_diagnosa` from (((`v_medcheck` join `tbl_trans_medcheck_icd` on(`tbl_trans_medcheck_icd`.`id_medcheck` = `v_medcheck`.`id`)) join `tbl_m_pasien` on(`v_medcheck`.`id_pasien` = `tbl_m_pasien`.`id`)) left join `tbl_m_icd` on(`tbl_trans_medcheck_icd`.`id_icd` = `tbl_m_icd`.`id`)) where `v_medcheck`.`status_bayar` = '1' and `v_medcheck`.`nik_pasien` <> '-' and `v_medcheck`.`nik_pasien` <> '0' and `v_medcheck`.`nik_pasien` <> '' and `v_medcheck`.`nik_dokter` <> '-' and `v_medcheck`.`id_location` is not null and `v_medcheck`.`id_encounter` is null and cast(`v_medcheck`.`tgl_simpan` as date) = curdate() group by `v_medcheck`.`tgl_simpan` order by `v_medcheck`.`id` desc limit 100;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_trans_kamar`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_trans_kamar` AS select `tbl_m_kamar`.`id` AS `id`,`tbl_m_kamar`.`kode` AS `kode`,`tbl_m_kamar`.`kamar` AS `kamar`,`tbl_m_kamar`.`tipe` AS `tipe`,`tbl_m_kamar`.`jml_max` AS `jml_max`,(select count(0) from (`tbl_trans_medcheck_kamar` join `tbl_trans_medcheck` on(`tbl_trans_medcheck_kamar`.`id_medcheck` = `tbl_trans_medcheck`.`id`)) where `tbl_trans_medcheck_kamar`.`id_kamar` = `tbl_m_kamar`.`id` and `tbl_trans_medcheck_kamar`.`status` = '1' and `tbl_trans_medcheck`.`status_bayar` = '0') AS `jml`,`tbl_m_kamar`.`jml_max` - (select count(0) from (`tbl_trans_medcheck_kamar` join `tbl_trans_medcheck` on(`tbl_trans_medcheck_kamar`.`id_medcheck` = `tbl_trans_medcheck`.`id`)) where `tbl_trans_medcheck_kamar`.`id_kamar` = `tbl_m_kamar`.`id` and `tbl_trans_medcheck_kamar`.`status` = '1' and `tbl_trans_medcheck`.`status_bayar` = '0') AS `sisa` from `tbl_m_kamar` where `tbl_m_kamar`.`status` = '1' order by `tbl_m_kamar`.`id`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
