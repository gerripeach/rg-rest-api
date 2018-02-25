CREATE TABLE `jos_rgpremium_codes` (
	`userid` INT(11) NOT NULL,
	`code` VARCHAR(255) NOT NULL,
	`used` TINYINT(3) NOT NULL,
	`for` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`code`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;
