insert into N_ONE_TAGS (TITLE)
values ('Тип привода'),
       ('Коробка передач'),
       ('Тип топлива'),
       ('Тип двигателя');

UPDATE N_ONE_TAGS
SET PARENT_ID = 10
where ID in (1, 2, 3);

UPDATE N_ONE_TAGS
SET PARENT_ID = 11
where ID in (4, 5);

UPDATE N_ONE_TAGS
SET PARENT_ID = 12
where ID in (6, 7);

UPDATE N_ONE_TAGS
SET PARENT_ID = 13
where ID in (8, 9);