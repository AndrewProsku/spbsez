CREATE TABLE IF NOT EXISTS `kelnik_report_status` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `SORT` int(11) DEFAULT '500',
  `ACTIVE` enum('Y','N') DEFAULT 'N',
  `NAME` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `SORT` (`SORT`),
  KEY `ACTIVE` (`ACTIVE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

CREATE TABLE IF NOT EXISTS `kelnik_report` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `COMPANY_ID` int(11) unsigned NOT NULL DEFAULT '0',
  `USER_ID` int(11) unsigned NOT NULL DEFAULT '0',
  `STATUS_ID` int(11) unsigned NOT NULL DEFAULT '0',
  `QUARTER` tinyint(1) unsigned NOT NULL,
  `YEAR` year(4) NOT NULL,
  `DATE_CREATED` datetime NOT NULL,
  `DATE_MODIFIED` datetime NOT NULL,
  `NAME` varchar(255) DEFAULT NULL,
  `NAME_RESIDENT` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `RESIDENT_ID` (`COMPANY_ID`),
  KEY `USER_ID` (`USER_ID`),
  KEY `STATUS_ID` (`STATUS_ID`),
  KEY `QUARTER` (`QUARTER`),
  KEY `YEAR` (`YEAR`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
