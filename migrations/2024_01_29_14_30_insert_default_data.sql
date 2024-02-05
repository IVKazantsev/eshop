insert into N_ONE_ROLES (ID, TITLE)
VALUES (1, 'administrator'),
       (2, 'customer');

insert into N_ONE_USERS (ROLE_ID, NAME, EMAIL, PASSWORD, PHONE_NUMBER, ADDRESS)
VALUES (1, 'Алексей Лаурент', 'alex@mail.ru', '12345', '88005553535', 'Калининград, Московский проспект, д. 5'),
       (1, 'Илья Казанцев', 'ilya@gmail.com', 'qwerty', '8800888888', 'Москва, ул. Старый Арбат д. 15'),
       (2, 'Сергей Александров', 'serj@yandex.ru', 'q1w2e3r4t5y6', '89997924950', 'Хабаровск, ул. строительная д. 4'),
       (2, 'Игорь Константинов', 'igor@mail.ru', '11111', '88004445656', 'Калининград, ул Калинина, д. 16');

insert into N_ONE_TAGS (TITLE)
VALUES ('Передний'),
       ('Задний'),
       ('Полный'),
       ('АКПП'),
       ('МКПП'),
       ('Бензин'),
       ('Дизель'),
       ('Атмосферный'),
       ('Турбированный');

insert into N_ONE_ITEMS (TITLE, IS_ACTIVE, PRICE, DESCRIPTION)
values ('MINI Cooper S', true, 3990000,
        'Mini Cooper S - это спортивная версия знаменитого автомобиля Mini Cooper, представленного компанией BMW. Mini Cooper S отличается от стандартной модели более высокой производительностью и спортивными характеристиками.'),
       ('MINI Countryman Cooper', true, 1247000,
        'MINI Countryman Cooper - это вариант компактного кроссовера от бренда MINI, который сочетает в себе уникальный стиль MINI с функциональностью и простором кроссовера.'),
       ('MINI Clubman Cooper S', true, 2149000,
        'MINI Clubman Cooper S - это спортивный и стильный компактный универсал, объединяющий в себе изысканный дизайн MINI и динамичную производительность.');


insert into N_ONE_STATUSES(TITLE)
values ('обработка'),
       ('доставка'),
       ('доставлен');


insert into N_ONE_ITEMS_TAGS(item_id, tag_id)
values (1, 1),
       (1, 5),
       (1, 7),
       (1, 9),
       (2, 3),
       (2, 5),
       (2, 7),
       (2, 9),
       (3, 1),
       (3, 5),
       (3, 7),
       (3, 9);

insert into N_ONE_ORDERS (USER_ID, ITEM_ID, STATUS_ID, PRICE)
values (3, 1, 1, 3990000),
       (4, 3, 2, 2149000);