ALTER TABLE N_ONE_USERS
	ADD TOKEN VARCHAR(64) DEFAULT NULL,
	ADD INDEX (TOKEN)