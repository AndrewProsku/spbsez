CREATE TABLE IF NOT EXISTS `kelnik_report` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `COMPANY_ID` int(11) unsigned NOT NULL DEFAULT '0',
  `CREATED_BY` int(11) unsigned NOT NULL DEFAULT '0',
  `MODIFIED_BY` int(11) unsigned NOT NULL DEFAULT '0',
  `STATUS_ID` int(11) unsigned NOT NULL DEFAULT '0',
  `TYPE` tinyint(1) unsigned NOT NULL,
  `YEAR` year(4) NOT NULL,
  `DATE_CREATED` datetime NOT NULL,
  `DATE_MODIFIED` datetime NOT NULL,
  `IS_LOCKED` enum('Y','N') NOT NULL DEFAULT 'N',
  `NAME` varchar(255) DEFAULT NULL,
  `NAME_SEZ` varchar(255) DEFAULT NULL,
  `NAME_COMMENT` varchar(255) DEFAULT NULL,
  `NAME_SEZ_COMMENT` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `USER_ID` (`MODIFIED_BY`),
  KEY `STATUS_ID` (`STATUS_ID`),
  KEY `YEAR` (`YEAR`),
  KEY `COMPANY_ID` (`COMPANY_ID`),
  KEY `TYPE` (`TYPE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

CREATE TABLE IF NOT EXISTS `kelnik_report_fields` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `REPORT_ID` int(11) unsigned NOT NULL DEFAULT '0',
  `GROUP_ID` int(11) unsigned NOT NULL DEFAULT '0',
  `NAME` varchar(100) DEFAULT NULL,
  `VALUE` varchar(1000) DEFAULT NULL,
  `COMMENT` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `REPORT_ID` (`REPORT_ID`),
  KEY `GROUP_ID` (`GROUP_ID`),
  KEY `NAME` (`NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

CREATE TABLE IF NOT EXISTS `kelnik_report_fields_group` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `REPORT_ID` int(11) unsigned NOT NULL DEFAULT '0',
  `FORM_NUM` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `TYPE` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `REPORT_ID` (`REPORT_ID`),
  KEY `TYPE` (`TYPE`),
  KEY `FORM_NUM` (`FORM_NUM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

CREATE TABLE IF NOT EXISTS `kelnik_report_status` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `SORT` int(11) DEFAULT '500',
  `ACTIVE` enum('Y','N') DEFAULT 'N',
  `NAME` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `SORT` (`SORT`),
  KEY `ACTIVE` (`ACTIVE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

INSERT INTO `kelnik_report_status` (`ID`, `SORT`, `ACTIVE`, `NAME`) VALUES
	(1, 500, 'Y', 'Нужно заполнить'),
	(2, 510, 'Y', 'На проверке'),
	(3, 520, 'Y', 'Принят'),
	(4, 530, 'Y', 'Отклонен');
