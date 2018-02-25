ALTER TABLE `jos_rgpremium_codes`
	ADD COLUMN `redeemerGuid` INT(10) NULL DEFAULT NULL AFTER `for`,
	ADD COLUMN `redeemTime` TIMESTAMP NULL DEFAULT NULL AFTER `redeemerGuid`;
