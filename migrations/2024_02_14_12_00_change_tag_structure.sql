ALTER TABLE N_ONE_TAGS
	ADD PARENT_ID int default 0,
	ADD INDEX(PARENT_ID);
