CREATE TABLE IF NOT EXISTS `#__brafton_categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `brafton_cat_id` int(10) NOT NULL,
   PRIMARY KEY  (`id`),
   FOREIGN KEY (`cat_id`) REFERENCES #__categories(`id`) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__brafton_content` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `content_id` int(10) UNSIGNED NOT NULL,
  `brafton_content_id` int(10) NOT NULL,
   PRIMARY KEY  (`id`),
   FOREIGN KEY (`content_id`) REFERENCES #__content(`id`) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__brafton_options` (
  `option` varchar(100) NOT NULL,
  `value` varchar(500) NOT NULL,
   PRIMARY KEY  (`option`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT IGNORE INTO  `#__brafton_options` (`option`,`value`) VALUES ('api-key',  ''), ('base-url',  ''), ('author', ''), ('import-order', ''), ('published-state', ''), ('update-articles', 'Off'), ('parent-category', 1), ('secret-key', ''), ('public-key', ''), ('feed-number', 0), ('import-assets', 'articles'), ('stop-importer', 'Off'), ('pause-text', ''), ('pause-link', ''), ('pause-asset-id', ''), ('end-title', ''), ('end-subtitle', ''), ('end-text', ''), ('end-link', ''), ('end-asset-id', ''), ('end-background', ''), ('debug', 'Off');