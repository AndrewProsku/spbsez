CREATE TABLE IF NOT EXISTS `kelnik_infrastructure_platform` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `SORT` int(11) DEFAULT '500',
  `IMAGE_ID` int(11) unsigned DEFAULT '0',
  `VIDEO_ID` int(11) unsigned DEFAULT '0',
  `ACTIVE` enum('Y','N') NOT NULL DEFAULT 'N',
  `NAME_RU` varchar(255) DEFAULT NULL,
  `NAME_EN` varchar(255) DEFAULT NULL,
  `TEXT_RU_TEXT_TYPE` varchar(4) DEFAULT NULL,
  `TEXT_EN_TEXT_TYPE` varchar(4) DEFAULT NULL,
  `TEXT_FEATURES_RU_TEXT_TYPE` varchar(4) DEFAULT NULL,
  `TEXT_FEATURES_EN_TEXT_TYPE` varchar(4) DEFAULT NULL,
  `MAP_COORDS_LAT` varchar(50) DEFAULT NULL,
  `MAP_COORDS_LNG` varchar(50) DEFAULT NULL,
  `TEXT_RU` text,
  `TEXT_EN` text,
  `TEXT_FEATURES_RU` text,
  `TEXT_FEATURES_EN` text,
  `TEXT_TRAITS_RU` text,
  `TEXT_TRAITS_EN` text,
  `TEXT_AREA_RU` text,
  `TEXT_AREA_EN` text,
  `TEXT_MAP_RU` text,
  `TEXT_MAP_EN` text,
  `TEXT_INFRA_RU` text,
  `TEXT_INFRA_EN` text,
  `TEXT_CUSTOMS_RU` text,
  `TEXT_CUSTOMS_EN` text,
  PRIMARY KEY (`ID`),
  KEY `SORT` (`SORT`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


INSERT INTO `kelnik_infrastructure_platform` (`ID`, `SORT`, `IMAGE_ID`, `VIDEO_ID`, `ACTIVE`, `NAME_RU`, `NAME_EN`, `TEXT_RU_TEXT_TYPE`, `TEXT_EN_TEXT_TYPE`, `TEXT_FEATURES_RU_TEXT_TYPE`, `TEXT_FEATURES_EN_TEXT_TYPE`, `MAP_COORDS_LAT`, `MAP_COORDS_LNG`, `TEXT_RU`, `TEXT_EN`, `TEXT_FEATURES_RU`, `TEXT_FEATURES_EN`, `TEXT_TRAITS_RU`, `TEXT_TRAITS_EN`) VALUES
	(1, 500, NULL, 0, 'Y', '«Новоорловская»', '«Novoorlovskaya»', 'html', 'html', NULL, NULL, NULL, NULL, '<div class="b-icons-list__item">\r\n	<div class="b-icons-list__img">\r\n <img src="/images/infrastructure/area1.svg">\r\n	</div>\r\n	<div class="b-icons-list__content">\r\n		<div class="b-icons-list__title">\r\n			 163,33 га\r\n		</div>\r\n		<div class="b-icons-list__description">\r\n			 Общая площадь\r\n		</div>\r\n	</div>\r\n</div>\r\n<div class="b-icons-list__item">\r\n	<div class="b-icons-list__img">\r\n <img src="/images/infrastructure/residents1.svg">\r\n	</div>\r\n	<div class="b-icons-list__content">\r\n		<div class="b-icons-list__title">\r\n			 38\r\n		</div>\r\n		<div class="b-icons-list__description">\r\n			 Резидентов\r\n		</div>\r\n	</div>\r\n</div>\r\n<div class="b-icons-list__item">\r\n	<div class="b-icons-list__img">\r\n <img src="/images/infrastructure/free1.svg">\r\n	</div>\r\n	<div class="b-icons-list__content">\r\n		<div class="b-icons-list__title">\r\n			 48 га\r\n		</div>\r\n		<div class="b-icons-list__description">\r\n			 Свободных земельных участков\r\n		</div>\r\n	</div>\r\n</div>\r\n<div class="b-icons-list__item">\r\n	<div class="b-icons-list__img">\r\n <img src="/images/infrastructure/map1.svg">\r\n	</div>\r\n	<div class="b-icons-list__content">\r\n		<div class="b-icons-list__title">\r\n			 Приморйский район\r\n		</div>\r\n		<div class="b-icons-list__description">\r\n			 В&nbsp;лесопарке «Новоорловский»\r\n		</div>\r\n	</div>\r\n</div>\r\n <br>', '', NULL, NULL, NULL, NULL),
	(2, 500, NULL, 0, 'Y', '«Нойдорф»', '«Neudorf»', 'html', 'html', NULL, NULL, NULL, NULL, '<div class="b-icons-list__item">\r\n	<div class="b-icons-list__img">\r\n <img src="/images/infrastructure/area2.svg">\r\n	</div>\r\n	<div class="b-icons-list__content">\r\n		<div class="b-icons-list__title">\r\n			18,99 га\r\n		</div>\r\n		<div class="b-icons-list__description">\r\n			Общая площадь\r\n		</div>\r\n	</div>\r\n</div>\r\n<div class="b-icons-list__item">\r\n	<div class="b-icons-list__img">\r\n <img src="/images/infrastructure/residents2.svg">\r\n	</div>\r\n	<div class="b-icons-list__content">\r\n		<div class="b-icons-list__title">\r\n			11\r\n		</div>\r\n		<div class="b-icons-list__description">\r\n			Резидентов\r\n		</div>\r\n	</div>\r\n</div>\r\n<div class="b-icons-list__item">\r\n	<div class="b-icons-list__img">\r\n <img src="/images/infrastructure/free2.svg">\r\n	</div>\r\n	<div class="b-icons-list__content">\r\n		<div class="b-icons-list__title">\r\n			0 га\r\n		</div>\r\n		<div class="b-icons-list__description">\r\n			Свободных земельных участков\r\n		</div>\r\n	</div>\r\n</div>\r\n<div class="b-icons-list__item">\r\n	<div class="b-icons-list__img">\r\n <img src="/images/infrastructure/map2.svg">\r\n	</div>\r\n	<div class="b-icons-list__content">\r\n		<div class="b-icons-list__title">\r\n			Петродворцовый район\r\n		</div>\r\n		<div class="b-icons-list__description">\r\n			Поселок Стрельна\r\n		</div>\r\n	</div>\r\n</div>', '', NULL, NULL, NULL, NULL);
