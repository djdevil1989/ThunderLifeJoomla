CREATE TABLE IF NOT EXISTS `#__opensim_clientinfo` (
  `PrincipalID` char(36) NOT NULL,
  `userName` varchar(255) DEFAULT NULL,
  `grid` varchar(255) DEFAULT NULL,
  `remoteip` varchar(50) DEFAULT NULL,
  `lastseen` datetime DEFAULT NULL,
  `from` char(2) DEFAULT NULL,
  PRIMARY KEY (`PrincipalID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__opensim_config` (
  `configname` varchar(50) NOT NULL,
  `configtype` enum('char','int','float') NOT NULL DEFAULT 'char',
  `configvalue` longtext NOT NULL,
  PRIMARY KEY (`configname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';

CREATE TABLE IF NOT EXISTS `#__opensim_groupactive` (
  `AgentID`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `ActiveGroupID`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  PRIMARY KEY (`AgentID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';


CREATE TABLE IF NOT EXISTS `#__opensim_groupinvite` (
  `InviteID`  varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `GroupID`  varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `RoleID`  varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `AgentID`  varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `TMStamp`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`InviteID`),
  UNIQUE INDEX `GroupID` USING BTREE (`GroupID`, `RoleID`, `AgentID`) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';


CREATE TABLE IF NOT EXISTS `#__opensim_groupmembership` (
  `GroupID`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `AgentID`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `SelectedRoleID`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `Contribution`  int(11) NOT NULL DEFAULT 0 ,
  `ListInProfile`  int(11) NOT NULL DEFAULT 1 ,
  `AcceptNotices`  int(11) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`GroupID`, `AgentID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';


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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';


CREATE TABLE IF NOT EXISTS `#__opensim_grouprole` (
  `GroupID`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `RoleID`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `Name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `Description`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `Title`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `Powers`  bigint(20) UNSIGNED NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`GroupID`, `RoleID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';


CREATE TABLE IF NOT EXISTS `#__opensim_grouprolemembership` (
  `GroupID`  varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `RoleID`  varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `AgentID`  varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  PRIMARY KEY (`GroupID`, `RoleID`, `AgentID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';

CREATE TABLE IF NOT EXISTS `#__opensim_inworldident` (
  `joomlaID` int(11) unsigned NOT NULL,
  `opensimID` varchar(36) DEFAULT NULL,
  `inworldIdent` varchar(36) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`joomlaID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';

CREATE TABLE IF NOT EXISTS `#__opensim_mapinfo` (
  `regionUUID`  varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
  `articleId`  int(11) UNSIGNED NULL DEFAULT NULL ,
  `hidemap` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`regionUUID`),
  INDEX `articleId` USING BTREE (`articleId`) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';

CREATE TABLE `#__opensim_moneybalances` (
  `user` varchar(128) NOT NULL,
  `balance` int(10) NOT NULL,
  `status` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='jOpenSim Rev.1';

CREATE TABLE `#__opensim_moneysettings` (
  `field` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`field`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='jOpenSim Rev.1';

CREATE TABLE IF NOT EXISTS `#__opensim_offlinemessages` (
  `imSessionID` varchar(36) NOT NULL DEFAULT '',
  `fromAgentID` varchar(36) DEFAULT NULL,
  `fromAgentName` varchar(128) DEFAULT NULL,
  `toAgentID` varchar(36) DEFAULT NULL,
  `fromGroup` varchar(5) DEFAULT NULL,
  `message` text,
  `remoteip` varchar(15) default NULL,
  KEY `from` (`fromAgentID`),
  KEY `to` (`toAgentID`),
  KEY `session` (`imSessionID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';

CREATE TABLE IF NOT EXISTS `#__opensim_search_allparcels` (
  `regionUUID` varchar(255) NOT NULL,
  `parcelname` varchar(255) NOT NULL,
  `ownerUUID` char(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `groupUUID` char(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `landingpoint` varchar(255) NOT NULL,
  `parcelUUID` char(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `infoUUID` char(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `parcelarea` int(11) NOT NULL,
  PRIMARY KEY (`parcelUUID`),
  KEY `regionUUID` (`regionUUID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';

CREATE TABLE IF NOT EXISTS `#__opensim_search_events` (
  `owneruuid` char(40) NOT NULL,
  `name` varchar(255) NOT NULL,
  `eventid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `creatoruuid` char(40) NOT NULL,
  `category` int(2) NOT NULL,
  `description` text NOT NULL,
  `dateUTC` int(10) NOT NULL,
  `duration` int(10) NOT NULL,
  `covercharge` int(10) NOT NULL,
  `coveramount` int(10) NOT NULL,
  `simname` varchar(255) NOT NULL,
  `globalPos` varchar(255) NOT NULL,
  `eventflags` int(10) NOT NULL,
  `mature` enum('true','false') NOT NULL,
  PRIMARY KEY (`eventid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';

CREATE TABLE IF NOT EXISTS `#__opensim_search_hostsregister` (
  `host` varchar(255) NOT NULL,
  `port` int(5) NOT NULL,
  `register` int(10) NOT NULL,
  `lastcheck` int(10) NOT NULL,
  `failcounter` int(1) NOT NULL,
  PRIMARY KEY (`host`,`port`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';

CREATE TABLE IF NOT EXISTS `#__opensim_search_objects` (
  `objectuuid` varchar(255) NOT NULL,
  `parceluuid` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `regionuuid` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`objectuuid`,`parceluuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';

CREATE TABLE IF NOT EXISTS `#__opensim_search_parcels` (
  `regionUUID` varchar(255) NOT NULL,
  `parcelname` varchar(255) NOT NULL,
  `parcelUUID` varchar(255) NOT NULL,
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';

CREATE TABLE IF NOT EXISTS `#__opensim_search_parcelsales` (
  `regionUUID` varchar(255) NOT NULL,
  `parcelname` varchar(255) NOT NULL,
  `parcelUUID` varchar(255) NOT NULL,
  `area` int(6) NOT NULL,
  `saleprice` int(11) NOT NULL,
  `landingpoint` varchar(255) NOT NULL,
  `infoUUID` char(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `dwell` int(11) NOT NULL,
  `parentestate` int(11) NOT NULL DEFAULT '1',
  `mature` varchar(32) NOT NULL DEFAULT 'false',
  PRIMARY KEY (`regionUUID`,`parcelUUID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';

CREATE TABLE IF NOT EXISTS `#__opensim_search_popularplaces` (
  `parcelUUID` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `dwell` float NOT NULL,
  `infoUUID` char(36) NOT NULL,
  `has_picture` tinyint(4) NOT NULL,
  `mature` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';

CREATE TABLE `#__opensim_userlevel` (
  `userlevel` smallint(3) NOT NULL,
  `description` varchar(150) NOT NULL,
  PRIMARY KEY (`userlevel`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__opensim_usernotes` (
  `avatar_id` varchar(36) NOT NULL,
  `target_id` varchar(36) NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`avatar_id`,`target_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';

CREATE TABLE IF NOT EXISTS `#__opensim_userrelation` (
  `joomlaID` int(11) NOT NULL,
  `opensimID` char(36) NOT NULL,
  PRIMARY KEY (`joomlaID`,`opensimID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';

CREATE TABLE IF NOT EXISTS `#__opensim_usersettings` (
  `uuid`  varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
  `im2email`  tinyint(1) UNSIGNED NOT NULL ,
  `visible`  tinyint(1) UNSIGNED NOT NULL ,
  `timezone` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';

CREATE TABLE IF NOT EXISTS `#__opensim_usertemp` (
  `joomlaid` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`joomlaid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='FoTo50\'s jOpenSim component';
