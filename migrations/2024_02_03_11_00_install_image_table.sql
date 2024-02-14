CREATE TABLE IF NOT EXISTS N_ONE_IMAGES
(
	ID int not null auto_increment,
	ITEM_ID int not null,
	HEIGHT int not null,
	WIDTH int not null,
	IS_MAIN bool not null,
	TYPE int not null,
	EXTENSION varchar(10) default 'jpeg',
	DATE_CREATE TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	DATE_UPDATE TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (ID),
	FOREIGN KEY FK_IMAGES_ITEMS (ITEM_ID)
		REFERENCES N_ONE_ITEMS(ID)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

# PATH varchar(300) GENERATED ALWAYS AS
# 		   (CONCAT(
# 				ITEM_ID,
# 				'/',
# 				ID,
# 				'_',
# 				HEIGHT,
# 				'_',
# 				WIDTH,
# 				'_',
# 				(
# 					CASE
# 						WHEN TYPE = 1 THEN 'fullsize'
# 						WHEN TYPE = 2 THEN 'preview'
# 						ELSE               'thumbnail'
# 						END
# 					),
# 				'_',
# 				(IF (IS_MAIN, 'main', 'base')),
# 		        '.jpeg'
# 		    )
# 		   )
# 			STORED ,