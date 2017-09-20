CREATE TABLE `visitors` (
	`session_token` VARCHAR(50) NOT NULL,
	`date` INT(11) NOT NULL DEFAULT '0',
	`session_locked` INT(11) NOT NULL DEFAULT '0'
)
ENGINE=InnoDB
;
