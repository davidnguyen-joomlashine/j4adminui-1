ALTER TABLE `#__banners` MODIFY `created` datetime NOT NULL;
ALTER TABLE `#__banners` MODIFY `modified` datetime NOT NULL;

ALTER TABLE `#__banners` MODIFY `reset` datetime NULL DEFAULT NULL;
ALTER TABLE `#__banners` MODIFY `publish_up` datetime NULL DEFAULT NULL;
ALTER TABLE `#__banners` MODIFY `publish_down` datetime NULL DEFAULT NULL;
ALTER TABLE `#__banners` MODIFY `checked_out_time` datetime NULL DEFAULT NULL;

ALTER TABLE `#__banner_clients` MODIFY `checked_out_time` datetime NULL DEFAULT NULL;

UPDATE `#__banners` SET `modified` = `created` WHERE `modified` = '0000-00-00 00:00:00';

UPDATE `#__banners` SET `reset` = NULL WHERE `reset` = '0000-00-00 00:00:00';
UPDATE `#__banners` SET `publish_up` = NULL WHERE `publish_up` = '0000-00-00 00:00:00';
UPDATE `#__banners` SET `publish_down` = NULL WHERE `publish_down` = '0000-00-00 00:00:00';
UPDATE `#__banners` SET `checked_out_time` = NULL WHERE `checked_out_time` = '0000-00-00 00:00:00';

UPDATE `#__banner_clients` SET `checked_out_time` = NULL WHERE `checked_out_time` = '0000-00-00 00:00:00';

UPDATE `#__ucm_content` SET `core_modified_time` = `core_created_time`
 WHERE `core_type_alias` = 'com_banners.banner'
   AND `core_modified_time` = '0000-00-00 00:00:00';

UPDATE `#__ucm_content` SET `core_publish_up` = NULL
 WHERE `core_type_alias` = 'com_banners.banner'
   AND `core_publish_up` = '0000-00-00 00:00:00';
UPDATE `#__ucm_content` SET `core_publish_down` = NULL
 WHERE `core_type_alias` = 'com_banners.banner'
   AND `core_publish_down` = '0000-00-00 00:00:00';
UPDATE `#__ucm_content` SET `core_checked_out_time` = NULL
 WHERE `core_type_alias` = 'com_banners.banner'
   AND `core_checked_out_time` = '0000-00-00 00:00:00';
