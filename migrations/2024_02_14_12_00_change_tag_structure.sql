ALTER TABLE N_ONE_ITEMS_TAGS
	ADD VALUE int default null;

ALTER TABLE N_ONE_TAGS
	ADD PARENT_ID int default null;