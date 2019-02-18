CREATE TABLE IF NOT EXISTS `kelnik_messages` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USER_ID` int(11) unsigned NOT NULL DEFAULT '0',
  `DATE_CREATED` datetime NOT NULL,
  `DATE_MODIFIED` datetime NOT NULL,
  `ACTIVE` enum('Y','N') NOT NULL DEFAULT 'N',
  `NAME` varchar(255) DEFAULT NULL,
  `TEXT_TEXT_TYPE` varchar(4) DEFAULT 'html',
  `TEXT` text,
  PRIMARY KEY (`ID`),
  KEY `USER_ID` (`USER_ID`),
  KEY `DATE_CREATED` (`DATE_MODIFIED`),
  KEY `ACTIVE` (`ACTIVE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `kelnik_messages_companies` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `MESSAGE_ID` int(11) unsigned NOT NULL DEFAULT '0',
  `USER_ID` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `MESSAGE_ID` (`MESSAGE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

CREATE TABLE IF NOT EXISTS `kelnik_messages_files` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ENTITY_ID` int(11) unsigned NOT NULL DEFAULT '0',
  `VALUE` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `ENTITY_ID` (`ENTITY_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `kelnik_messages_users` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `MESSAGE_ID` int(11) unsigned NOT NULL DEFAULT '0',
  `USER_ID` int(11) unsigned NOT NULL DEFAULT '0',
  `DATE_MODIFIED` datetime NOT NULL,
  `IS_NEW` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`ID`),
  KEY `IS_NEW` (`IS_NEW`),
  KEY `MESSAGE_ID` (`MESSAGE_ID`),
  KEY `USER_ID` (`USER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
