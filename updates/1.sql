CREATE TABLE `press_articles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `description` mediumtext,
  `content` mediumtext,
  `slug` varchar(100) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL,
  `is_enabled` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;