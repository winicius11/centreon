-- Change version of Centreon
UPDATE `informations` SET `value` = '19.04.0' WHERE CONVERT( `informations`.`key` USING utf8 ) = 'version' AND CONVERT ( `informations`.`value` USING utf8 ) = '18.10.4' LIMIT 1;

TRUNCATE TABLE ods_view_details;
ALTER TABLE ods_view_details MODIFY metric_id int(11);
