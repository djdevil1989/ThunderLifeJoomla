<?php
/*
 * @package Joomla 2.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html, see LICENSE.php
 *
 * @component jOpenSim
 * @copyright Copyright (C) 2013 FoTo50 http://www.jopensim.com/
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 *
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$db =& JFactory::getDBO();

// create the tables if they dont exist yet

$createquery = array();

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_clientinfo` (
  `PrincipalID` char(36) NOT NULL,
  `userName` varchar(255) DEFAULT NULL,
  `grid` varchar(255) DEFAULT NULL,
  `remoteip` varchar(50) DEFAULT NULL,
  `lastseen` datetime DEFAULT NULL,
  `from` char(2) DEFAULT NULL,
  PRIMARY KEY (`PrincipalID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_config` (
  `configname` varchar(50) NOT NULL,
  `configtype` enum('char','int','float') NOT NULL DEFAULT 'char',
  `configvalue` longtext NOT NULL,
  PRIMARY KEY (`configname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_group` (
  `GroupID`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `Name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `Charter`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
  `InsigniaID`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `FounderID`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `MembershipFee`  int(11) NOT NULL DEFAULT 0 ,
  `OpenEnrollment`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `ShowInList`  tinyint(1) NOT NULL DEFAULT 0 ,
  `AllowPublish`  tinyint(1) NOT NULL DEFAULT 0 ,
  `MaturePublish`  tinyint(1) NOT NULL DEFAULT 0 ,
  `OwnerRoleID`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  PRIMARY KEY (`GroupID`),
  UNIQUE INDEX `Name` USING BTREE (`Name`) ,
  FULLTEXT INDEX `Name_2` (`Name`) 
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_groupactive` (
  `AgentID`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `ActiveGroupID`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  PRIMARY KEY (`AgentID`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_groupinvite` (
  `InviteID`  varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `GroupID`  varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `RoleID`  varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `AgentID`  varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `TMStamp`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`InviteID`),
  UNIQUE INDEX `GroupID` USING BTREE (`GroupID`, `RoleID`, `AgentID`) 
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_groupmembership` (
  `GroupID`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `AgentID`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `SelectedRoleID`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `Contribution`  int(11) NOT NULL DEFAULT 0 ,
  `ListInProfile`  int(11) NOT NULL DEFAULT 1 ,
  `AcceptNotices`  int(11) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`GroupID`, `AgentID`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_groupnotice` (
  `GroupID`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `NoticeID`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `Timestamp`  int(10) UNSIGNED NOT NULL DEFAULT 0 ,
  `FromName`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `Subject`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `Message`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
  `BinaryBucket`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
  PRIMARY KEY (`GroupID`, `NoticeID`),
  INDEX `Timestamp` USING BTREE (`Timestamp`) 
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_grouprole` (
  `GroupID`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `RoleID`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `Name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `Description`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `Title`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `Powers`  bigint(20) UNSIGNED NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`GroupID`, `RoleID`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_grouprolemembership` (
  `GroupID`  varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `RoleID`  varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `AgentID`  varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  PRIMARY KEY (`GroupID`, `RoleID`, `AgentID`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_inworldident` (
  `joomlaID` int(11) unsigned NOT NULL,
  `opensimID` varchar(36) DEFAULT NULL,
  `inworldIdent` varchar(36) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`joomlaID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_mapinfo` (
  `regionUUID`  varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `articleId`  int(11) UNSIGNED NULL DEFAULT NULL ,
  `hidemap` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`regionUUID`),
  INDEX `articleId` USING BTREE (`articleId`) 
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE `#__opensim_moneybalances` (
  `user` varchar(128) NOT NULL,
  `balance` int(10) NOT NULL,
  `status` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='jOpenSim Rev.1';";

$createquery[] = "
CREATE TABLE `#__opensim_moneysettings` (
  `field` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`field`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$createquery[] = "
CREATE TABLE `#__opensim_moneytransactions` (
  `UUID` varchar(36) NOT NULL,
  `sender` varchar(128) NOT NULL,
  `receiver` varchar(128) NOT NULL,
  `amount` int(10) NOT NULL,
  `objectUUID` varchar(36) DEFAULT NULL,
  `regionHandle` varchar(36) NOT NULL,
  `type` int(10) NOT NULL,
  `time` int(11) NOT NULL,
  `secure` varchar(36) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`UUID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='jOpenSim Rev.1';";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_offlinemessages` (
  `imSessionID` varchar(36) NOT NULL DEFAULT '',
  `fromAgentID` varchar(36) DEFAULT NULL,
  `fromAgentName` varchar(128) DEFAULT NULL,
  `toAgentID` varchar(36) DEFAULT NULL,
  `fromGroup` varchar(5) DEFAULT NULL,
  `message` text,
  `remoteip` varchar(15) default NULL,
  `sent` datetime NOT NULL,
  KEY `from` (`fromAgentID`),
  KEY `to` (`toAgentID`),
  KEY `session` (`imSessionID`)
) ENGINE=MyISAM CHARACTER SET `utf8` COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_search_allparcels` (
  `regionUUID` varchar(255) NOT NULL,
  `regionName` varchar(255) NOT NULL,
  `parcelname` varchar(255) NOT NULL,
  `ownerUUID` char(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `ownerName` varchar(255) NOT NULL,
  `groupUUID` char(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `landingpoint` varchar(255) NOT NULL,
  `parcelUUID` char(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `infoUUID` char(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `parcelarea` int(11) NOT NULL,
  PRIMARY KEY (`parcelUUID`),
  KEY `regionUUID` (`regionUUID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_search_classifieds` (
  `classifieduuid` char(36) NOT NULL,
  `creatoruuid` char(36) NOT NULL,
  `creationdate` int(20) NOT NULL,
  `expirationdate` int(20) NOT NULL,
  `category` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `parceluuid` char(36) NOT NULL,
  `parentestate` int(11) NOT NULL,
  `snapshotuuid` char(36) NOT NULL,
  `simname` varchar(255) NOT NULL,
  `posglobal` varchar(255) NOT NULL,
  `parcelname` varchar(255) NOT NULL,
  `classifiedflags` int(8) NOT NULL,
  `priceforlisting` int(5) NOT NULL,
  PRIMARY KEY (`classifieduuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_search_events` (
  `owneruuid` char(40) NOT NULL,
  `name` varchar(255) NOT NULL,
  `eventid` int(11) NOT NULL AUTO_INCREMENT,
  `creatoruuid` char(40) NOT NULL,
  `category` int(2) NOT NULL,
  `description` text NOT NULL,
  `dateUTC` int(10) NOT NULL,
  `duration` int(10) NOT NULL,
  `covercharge` int(10) NOT NULL,
  `coveramount` int(10) NOT NULL,
  `simname` varchar(255) NOT NULL,
  `parcelUUID` char(40) NOT NULL,
  `globalPos` varchar(255) NOT NULL,
  `eventflags` int(10) NOT NULL,
  `mature` enum('true','false') NOT NULL,
  PRIMARY KEY (`eventid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_search_hostsregister` (
  `host` varchar(255) NOT NULL,
  `port` int(5) NOT NULL,
  `register` int(10) NOT NULL,
  `lastcheck` int(10) NOT NULL,
  `failcounter` int(1) NOT NULL,
  PRIMARY KEY (`host`,`port`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_search_objects` (
  `objectuuid` varchar(36) NOT NULL,
  `parceluuid` varchar(36) NOT NULL,
  `location` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `regionuuid` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`objectuuid`,`parceluuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE `#__opensim_search_options` (
  `searchoption` varchar(50) NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `reiheintern` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `reihe` tinyint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`searchoption`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_search_parcels` (
  `regionUUID` varchar(36) NOT NULL,
  `parcelname` varchar(255) NOT NULL,
  `parcelUUID` varchar(36) NOT NULL,
  `landingpoint` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `searchcategory` varchar(50) NOT NULL,
  `build` enum('true','false') NOT NULL,
  `script` enum('true','false') NOT NULL,
  `public` enum('true','false') NOT NULL,
  `dwell` float NOT NULL DEFAULT '0',
  `infouuid` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`regionUUID`,`parcelUUID`),
  KEY `name` (`parcelname`),
  KEY `description` (`description`),
  KEY `searchcategory` (`searchcategory`),
  KEY `dwell` (`dwell`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_search_parcelsales` (
  `regionUUID` varchar(36) NOT NULL,
  `parcelname` varchar(255) NOT NULL,
  `parcelUUID` varchar(72) NOT NULL,
  `area` int(6) NOT NULL,
  `saleprice` int(11) NOT NULL,
  `landingpoint` varchar(255) NOT NULL,
  `infoUUID` char(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `dwell` int(11) NOT NULL,
  `parentestate` int(11) NOT NULL DEFAULT '1',
  `mature` varchar(32) NOT NULL DEFAULT 'false',
  PRIMARY KEY (`regionUUID`,`parcelUUID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_search_popularplaces` (
  `parcelUUID` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `dwell` float NOT NULL,
  `infoUUID` char(36) NOT NULL,
  `has_picture` tinyint(4) NOT NULL,
  `mature` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_search_regions` (
  `regionname` varchar(255) NOT NULL,
  `regionuuid` varchar(255) NOT NULL,
  `regionhandle` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `owneruuid` varchar(255) NOT NULL,
  `locX` int(10) DEFAULT NULL,
  `locY` int(10) DEFAULT NULL,
  PRIMARY KEY (`regionuuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_terminals` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `terminalName` varchar(50) DEFAULT NULL,
  `terminalDescription` text,
  `terminalKey` varchar(36) DEFAULT NULL,
  `terminalUrl` varchar(255) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `location_x` smallint(3) unsigned DEFAULT NULL,
  `location_y` smallint(3) unsigned DEFAULT NULL,
  `location_z` smallint(3) unsigned DEFAULT NULL,
  `staticLocation` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_useravatars` (
  `userid` varchar(72) NOT NULL,
  `avatarname` varchar(255) NOT NULL,
  `reihe` int(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_userclassifieds` (
  `classifieduuid` char(36) NOT NULL,
  `creatoruuid` char(36) NOT NULL,
  `creationdate` int(20) NOT NULL,
  `expirationdate` int(20) NOT NULL,
  `category` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `parceluuid` char(36) NOT NULL,
  `parentestate` int(11) NOT NULL,
  `snapshotuuid` char(36) NOT NULL,
  `simname` varchar(255) NOT NULL,
  `posglobal` varchar(255) NOT NULL,
  `parcelname` varchar(255) NOT NULL,
  `classifiedflags` int(8) NOT NULL,
  `priceforlisting` int(5) NOT NULL,
  PRIMARY KEY (`classifieduuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE `#__opensim_userlevel` (
  `userlevel` smallint(3) NOT NULL,
  `description` varchar(150) NOT NULL,
  PRIMARY KEY (`userlevel`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_usernotes` (
  `avatar_id` varchar(36) NOT NULL,
  `target_id` varchar(36) NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`avatar_id`,`target_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_userpicks` (
  `pickuuid` varchar(36) NOT NULL,
  `creatoruuid` varchar(36) NOT NULL,
  `toppick` enum('true','false') NOT NULL,
  `parceluuid` varchar(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `snapshotuuid` varchar(36) NOT NULL,
  `user` varchar(255) NOT NULL,
  `originalname` varchar(255) NOT NULL,
  `simname` varchar(255) NOT NULL,
  `posglobal` varchar(255) NOT NULL,
  `sortorder` int(2) NOT NULL,
  `enabled` enum('true','false') NOT NULL,
  PRIMARY KEY (`pickuuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_userprofile` (
  `avatar_id` varchar(36) NOT NULL,
  `partner` varchar(36) NOT NULL,
  `image` varchar(36) NOT NULL,
  `aboutText` text NOT NULL,
  `allowPublish` binary(1) NOT NULL,
  `maturePublish` binary(1) NOT NULL,
  `url` varchar(255) NOT NULL,
  `wantToMask` int(3) NOT NULL,
  `wantToText` text NOT NULL,
  `skillsMask` int(3) NOT NULL,
  `skillsText` text NOT NULL,
  `languagesText` text NOT NULL,
  `firstLifeImage` varchar(36) NOT NULL,
  `firstLifeText` text NOT NULL,
  PRIMARY KEY (`avatar_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_userrelation` (
  `joomlaID` int(11) NOT NULL,
  `opensimID` char(36) NOT NULL,
  PRIMARY KEY (`joomlaID`,`opensimID`)
) ENGINE=MyISAM CHARACTER SET `utf8` COMMENT='FoTo50\'s jOpenSim component';";

$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_usersettings` (
  `uuid`  varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
  `im2email`  tinyint(1) UNSIGNED NOT NULL ,
  `visible`  tinyint(1) UNSIGNED NOT NULL ,
  `timezone` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`uuid`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';";


$createquery[] = "
CREATE TABLE IF NOT EXISTS `#__opensim_usertemp` (
  `joomlaid` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`joomlaid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';";



foreach($createquery AS $tablequery) {
	$db->setQuery($tablequery);
	$db->query();
}

// We probably need to alter already existing older tables

// adding new fields in existing tables
//$newfields['tblname'][]	= array('name' => 'fieldname', 'args' => 'arguments of field');
$newfields = array();

$newfields['opensim_offlinemessages'][]		= array('name' => 'remoteip', 'args' => 'varchar(15) NULL AFTER `message`');
$newfields['opensim_offlinemessages'][]		= array('name' => 'sent', 'args' => 'datetime NOT NULL AFTER `remoteip`');

$newfields['opensim_mapinfo'][]				= array('name' => 'hidemap', 'args' => 'tinyint(1) unsigned NOT NULL DEFAULT 0 AFTER `articleId`');
$newfields['opensim_mapinfo'][]				= array('name' => 'public', 'args' => 'tinyint(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `hidemap`');

$newfields['opensim_usersettings'][]		= array('name' => 'timezone', 'args' => 'varchar(150) NOT NULL AFTER `visible`');

$newfields['opensim_search_allparcels'][]	= array('name' => 'regionName', 'args' => 'varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `regionUUID`');
$newfields['opensim_search_allparcels'][]	= array('name' => 'ownerName', 'args' => 'varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `ownerUUID`');

$newfields['opensim_search_events'][]		= array('name' => 'parcelUUID', 'args' => 'char(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `simname`');

// changing fields in existing tables
//$changefields['tblname'][]	= array('name' => 'fieldname', 'args' => 'arguments of field');
$changefields = array();

$changefields['opensim_userprofile'][]		= array('name' => 'wantToMask', 'args' => '`wantmask`  int(3) NOT NULL AFTER `url`');
$changefields['opensim_userprofile'][]		= array('name' => 'wantToText', 'args' => '`wanttext`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `wantmask`');
$changefields['opensim_userprofile'][]		= array('name' => 'skillsMask', 'args' => '`skillsmask`  int(3) NOT NULL AFTER `wanttext`');
$changefields['opensim_userprofile'][]		= array('name' => 'skillsText', 'args' => '`skillstext`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `skillsmask`');
$changefields['opensim_userprofile'][]		= array('name' => 'languagesText', 'args' => '`languages`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `skillstext`');

// removing fields of existing tables
$oldfields = array();
// $oldfields = array('opensim_settings' => array('gridserver_host'));

// are there any new fields? lets add them
foreach($newfields AS $table => $newfield) {
	$query = "DESCRIBE #__".$table;
	$db->setQuery($query);
	$existingfields = $db->loadResultArray();
	foreach($newfield AS $field) {
		if(!in_array($field['name'],$existingfields)) {
			$query = sprintf("ALTER TABLE #__%s ADD COLUMN `%s` %s",$table,$field['name'],$field['args']);
			$db->setQuery($query);
			$db->query();
		}
	}
}

// are there any fields to be changed?
foreach($changefields AS $table => $changefield) {
	$query = "DESCRIBE #__".$table;
	$db->setQuery($query);
	$existingfields = $db->loadResultArray();
	foreach($changefield AS $field) {
		if(in_array($field['name'],$existingfields)) {
			$query = sprintf("ALTER TABLE #__%s CHANGE COLUMN `%s` %s",$table,$field['name'],$field['args']);
			$db->setQuery($query);
			$db->query();
		}
	}
}

// keep the DB clean ... delete not anymore needed fields in case they still exist from a previous version :)
foreach($oldfields AS $table => $oldfield) {
	$query = "DESCRIBE #__".$table;
	$db->setQuery($query);
	$existingfields = $db->loadResultArray();
	foreach($existingfields AS $field) {
		if(in_array($field,$oldfield)) {
			$query = sprintf("ALTER TABLE #__%s DROP COLUMN `%s`",$table,$field);
			$db->setQuery($query);
			$db->query();
		}
	}
}

$oldsettings = array(); // this is to avoid notices

// check for setting storage version
$jconfig = new JConfig();
$prefix = $jconfig->dbprefix;
$query = "SHOW TABLES;";
$db->setQuery($query);
$db->query();
$table_list = $db->loadResultArray();
if(in_array($prefix."opensim_settings",$table_list)) {
	$query = "SELECT * FROM #__opensim_settings";
	$db->setQuery($query);
	$db->query();
	$num_rows = $db->getNumRows();
	
	if($num_rows == 1) { // old style settings still present, lets update
		$oldsettings = $db->loadAssoc(); // first get the values
		$query = "DROP TABLE `#__opensim_settings`"; // then drop the table
		$db->setQuery($query);
		$db->query();
	}
}

$confignames = array(
		"allow_zoom"							=> 'int',
		"defaulthome"							=> 'char',
		"mapstartX"								=> 'int',
		"mapstartY"								=> 'int',
		"mapstartZ"								=> 'int',
		"loginscreen_image"						=> 'char',
		"loginscreen_gridstatus"				=> 'int',
		"loginscreen_gridbox"					=> 'int',
		"loginscreen_xdays"						=> 'int',
		"loginscreen_boxes"						=> 'int',
		"loginscreen_msgbox_title"				=> 'char',
		"loginscreen_msgbox_title_background"	=> 'char',
		"loginscreen_msgbox_title_text"			=> 'char',
		"loginscreen_msgbox_message"			=> 'char',
		"loginscreen_color"						=> 'char',
		"loginscreen_msgbox_color"				=> 'char',
		"loginscreen_text_color"				=> 'char',
		"loginscreen_online_color"				=> 'char',
		"loginscreen_offline_color"				=> 'char',
		"loginscreen_msgbox_border"				=> 'char',
		"userchange"							=> 'int',
		"addons"								=> 'int',
		"terminalchannel"						=> 'int',
		"identminutes"							=> 'int',
		"welcomecontent"						=> 'int',
		"lastnametype"							=> 'int',
		"lastnamelist"							=> 'char',
		"mapcontainer_width"					=> 'int',
		"mapcontainer_height"					=> 'int',
		"mapcenter_offsetX"						=> 'float',
		"mapcenter_offsetY"						=> 'float',
		"map_defaultsize"						=> 'int',
		"map_minsize"							=> 'int',
		"map_maxsize"							=> 'int',
		"map_zoomstep"							=> 'int',
		"eventtimedefault"						=> 'char',
		"listmatureevents"						=> 'char'
		);

foreach($confignames AS $configname => $configtype) {
	$query = sprintf("SELECT * FROM #__opensim_config WHERE configname = '%s'",$configname);
	$db->setQuery($query);
	$db->query();
	$num_rows = $db->getNumRows();
	if($num_rows == 0) { // Only update the table if this value does not exist yet
		if(array_key_exists($configname,$oldsettings)) $configvalue = "'".$oldsettings[$configname]."'";
		else $configvalue = "NULL";
		$query = sprintf("INSERT INTO #__opensim_config (configname,configtype,configvalue) VALUES ('%s','%s',%s)",
								$configname,
								$confignames[$configname],
								$configvalue);
		$db->setQuery($query);
		$db->query();
	}
}

// Check, if #__opensim_userlevel is new
$query = "SELECT * FROM #__opensim_userlevel";
$db->setQuery($query);
$db->query();
$num_rows = $db->getNumRows();
if($num_rows < 6) { // This table seems to be new or incomplete, insert/update initial values
	$values[0]['level']			= -3;
	$values[0]['description']	= "JOPENSIM_USERLEVEL_AVATAR";
	$values[1]['level']			= -2;
	$values[1]['description']	= "JOPENSIM_USERLEVEL_BANKER";
	$values[2]['level']			= -1;
	$values[2]['description']	= "JOPENSIM_USERLEVEL_DISABLED";
	$values[3]['level']			= 0;
	$values[3]['description']	= "JOPENSIM_USERLEVEL_REGULAR";
	$values[4]['level']			= 100;
	$values[4]['description']	= "JOPENSIM_USERLEVEL_MAINTENANCE";
	$values[5]['level']			= 200;
	$values[5]['description']	= "JOPENSIM_USERLEVEL_GOD";
	foreach($values AS $value) {
		$query = sprintf("INSERT INTO #__opensim_userlevel (userlevel,description) VALUES ('%1\$d','%2\$s') ON DUPLICATE KEY UPDATE userlevel = '%2\$s'",$value['level'],$value['description']);
		$db->setQuery($query);
		$db->query();
	}
}


// Check, if #__opensim_moneysettings is new
$query = "SELECT * FROM #__opensim_moneysettings";
$db->setQuery($query);
$db->query();
$num_rows = $db->getNumRows();
if($num_rows < 7) { // This table seems to be new or incomplete, insert initial values (or dummy update them)
	$mvalues[0]['field']	= "bankerUID";
	$mvalues[0]['value']	= "";
	$mvalues[1]['field']	= "groupCharge";
	$mvalues[1]['value']	= "0";
	$mvalues[2]['field']	= "uploadCharge";
	$mvalues[2]['value']	= "0";
	$mvalues[3]['field']	= "groupMinDividend";
	$mvalues[3]['value']	= "0";
	$mvalues[4]['field']	= "startBalance";
	$mvalues[4]['value']	= "0";
	$mvalues[5]['field']	= "name";
	$mvalues[5]['value']	= "";
	$mvalues[6]['field']	= "bankerName";
	$mvalues[6]['value']	= "";
	foreach($mvalues AS $value) {
		$query = sprintf("INSERT INTO #__opensim_moneysettings (`field`,`value`) VALUES ('%1\$s','%2\$s') ON DUPLICATE KEY UPDATE `field` = '%1\$s'",$value['field'],$value['value']);
		$db->setQuery($query);
		$db->query();
	}
}

// Check, if #__opensim_search_options is new
$query = "SELECT * FROM #__opensim_search_options";
$db->setQuery($query);
$db->query();
$num_rows = $db->getNumRows();
if($num_rows < 5) { // This table seems to be new or incomplete, insert initial values (or dummy update them)
	$svalues[0]['searchoption']	= "JOPENSIM_SEARCH_OBJECTS";
	$svalues[0]['enabled']		= "1";
	$svalues[0]['reiheintern']	= "1";
	$svalues[0]['reihe']		= "0";
	$svalues[1]['searchoption']	= "JOPENSIM_SEARCH_PARCELS";
	$svalues[1]['enabled']		= "1";
	$svalues[1]['reiheintern']	= "2";
	$svalues[1]['reihe']		= "1";
	$svalues[2]['searchoption']	= "JOPENSIM_SEARCH_PARCELSALES";
	$svalues[2]['enabled']		= "1";
	$svalues[2]['reiheintern']	= "3";
	$svalues[2]['reihe']		= "2";
	$svalues[3]['searchoption']	= "JOPENSIM_SEARCH_POPULARPLACES";
	$svalues[3]['enabled']		= "1";
	$svalues[3]['reiheintern']	= "4";
	$svalues[3]['reihe']		= "3";
	$svalues[4]['searchoption']	= "JOPENSIM_SEARCH_REGIONS";
	$svalues[4]['enabled']		= "1";
	$svalues[4]['reiheintern']	= "5";
	$svalues[4]['reihe']		= "4";
	foreach($svalues AS $value) {
		$query = sprintf("INSERT INTO #__opensim_search_options (`searchoption`,`enabled`,`reiheintern`,`reihe`) VALUES ('%1\$s','%2\$d','%3\$d','%4\$d') ON DUPLICATE KEY UPDATE `searchoption` = '%1\$s'",
							$value['searchoption'],
							$value['enabled'],
							$value['reiheintern'],
							$value['reihe']);
		$db->setQuery($query);
		$db->query();
	}
}

?>
