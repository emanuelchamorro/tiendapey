CREATE TABLE IF NOT EXISTS `_PREFIX_kb_web_push_template` (
    `id_template` int(10) unsigned NOT NULL auto_increment,
    `notification_type` enum('0','1', '2', '3', '4') NOT NULL DEFAULT '0',
    `notify_icon` text,
    `notify_icon_path` text,
    `primary_url` text,
    `action_button_link1` text,
    `action_button_link2` text,
    `active` int(2) unsigned NOT NULL DEFAULT '0',
    `date_add` datetime NOT NULL,
    `date_upd` datetime NOT NULL,
    PRIMARY KEY (`id_template`)
) ENGINE=ENGINE_TYPE  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `_PREFIX_kb_web_push_template_lang` (
    `id_template_lang` int(10) unsigned NOT NULL auto_increment,
    `id_template` int(10) unsigned DEFAULT NULL,
    `id_lang` int(10) unsigned DEFAULT NULL,
    `id_shop` int(10) unsigned DEFAULT NULL,
    `notification_title` text,
    `notification_message` text,
    `action_button1` text,
    `action_button2` text,
    PRIMARY KEY (`id_template_lang`),
    FOREIGN KEY (id_template) references _PREFIX_kb_web_push_template(id_template) ON DELETE CASCADE
) ENGINE=ENGINE_TYPE  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `_PREFIX_kb_web_push_template_shop` (
    `id_template_shop` int(10) unsigned NOT NULL auto_increment,
    `id_template` int(10) unsigned DEFAULT NULL,
    `id_shop` int(10) unsigned DEFAULT NULL,
    PRIMARY KEY (`id_template_shop`),
     FOREIGN KEY (id_template) references _PREFIX_kb_web_push_template(id_template) ON DELETE CASCADE
) ENGINE=ENGINE_TYPE  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `_PREFIX_kb_web_push_subscribers` (
    `id_subscriber` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `id_shop` int(11) unsigned default null,
    `id_lang` int(11) unsigned default null,
    `id_guest` int(11) unsigned default null,
    `id_country` int(11) unsigned default null,
    `country` varchar(255) NOT NULL,
    `reg_id` varchar(255) NOT NULL,
    `ip` varchar(40) NOT NULL,
    `browser` varchar(255) DEFAULT NULL,
    `browser_version` varchar(255) DEFAULT NULL,
    `platform` varchar(255) DEFAULT NULL,
    `device` enum('Mobile','Desktop','Tablet') NOT NULL DEFAULT 'Desktop',
    `token_id` int(11) NOT NULL,
--     `cart_token` varchar(255) DEFAULT NULL,
--     `cart_item_length` int(11) DEFAULT NULL,
    `is_admin` int(11) NOT NULL DEFAULT '0',
    `date_add` datetime NOT NULL,
    `date_upd` datetime NOT NULL,
    PRIMARY KEY (`id_subscriber`)
) ENGINE=ENGINE_TYPE  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `_PREFIX_kb_web_push_pushes` (
  `id_push` int(11) NOT NULL AUTO_INCREMENT,
  `id_shop` int(11) NOT NULL,
--   `title` varchar(50) NOT NULL,
  `type` enum('0','1', '2', '3', '4') NOT NULL DEFAULT '0',
--   `message` varchar(100) NOT NULL,
  `primary_url` varchar(255) NOT NULL,
--   `action_button1` varchar(50) DEFAULT NULL,
  `action_button_link1` varchar(255) DEFAULT NULL,
--   `action_button2` varchar(50) DEFAULT NULL,
  `action_button_link2` varchar(255) DEFAULT NULL,
  `notify_icon` varchar(255) NOT NULL,
  `notify_icon_path` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_sent` tinyint(1) NOT NULL,
  `sent_to` int(11) NOT NULL,
  `is_clicked` int(11) DEFAULT '0',
  `token_id` int(11) NOT NULL,
  `schedule_at` datetime DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `expire_at` datetime DEFAULT NULL,
--   `is_update_order_push` int(11) DEFAULT '0',
--   `is_price_alert_push` int(11) NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  `date_upd` datetime DEFAULT NULL,
  PRIMARY KEY (`id_push`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `_PREFIX_kb_web_push_pushes_lang` (
    `id_push_lang` int(10) unsigned NOT NULL auto_increment,
    `id_push` int(10) unsigned DEFAULT NULL,
    `id_lang` int(10) unsigned DEFAULT NULL,
    `title` text,
    `message` text,
    `action_button1` text,
    `action_button2` text,
    PRIMARY KEY (`id_push_lang`)
) ENGINE=ENGINE_TYPE  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `_PREFIX_kb_web_push_subscriber_mapping` (
  `id_mapping` int(11) NOT NULL AUTO_INCREMENT,
  `id_push` int(11) NOT NULL,
  `id_shop` int(11) NOT NULL,
  `reg_id` text NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id_mapping`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `_PREFIX_kb_web_push_product_subscriber_mapping` (
    `id_mapping` int(11) NOT NULL AUTO_INCREMENT,
    `id_subscriber` int(11) DEFAULT NULL,
    `id_guest` int(11) NOT NULL,
    `id_lang` int(11) not null,
    `id_shop` int(11) NOT NULL,
    `id_product` int(11) NOT NULL,
    `id_product_attribute` int(11) NOT NULL,
    `currency_iso` varchar(20) NULL,
    `product_price` varchar(50) DEFAULT NULL,
    `subscribe_type` enum('price','stock') NOT NULL DEFAULT 'price',
    `reg_id` text DEFAULT NULL,
    `is_sent` int(11) DEFAULT '0',
    `is_clicked` int(11) DEFAULT '0',
    `sent_at` datetime DEFAULT NULL,
    `date_add` datetime DEFAULT NULL,
    `date_upd` datetime DEFAULT NULL,
    PRIMARY KEY (`id_mapping`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `_PREFIX_kb_web_push_delay` (
    `id_delay` int(11) NOT NULL AUTO_INCREMENT,
    `id_template` int(10) unsigned NOT NULL,
    `id_shop` int(11) NOT NULL,
    `delay_time` datetime DEFAULT NULL,
    `is_sent` tinyint(1) NOT NULL,
    `is_expired` tinyint(1) NOT NULL,
    `sent_at` datetime DEFAULT NULL,
    `date_add` datetime DEFAULT NULL,
    `date_upd` datetime DEFAULT NULL,
    PRIMARY KEY (`id_delay`),
FOREIGN KEY (id_template) references _PREFIX_kb_web_push_template(id_template) ON DELETE CASCADE
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8;