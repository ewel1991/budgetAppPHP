CREATE TABLE IF NOT EXISTS users(

id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
username varchar(255) NOT NULL,
email varchar(255) NOT NULL,
password varchar(255) NOT NULL,
PRIMARY KEY(id),
UNIQUE KEY(email)
);


CREATE TABLE IF NOT EXISTS expenses(

id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
user_id bigint(20) unsigned NOT NULL,
expense_category_assigned_to_user_id	bigint(20) NOT NULL,
payment_method_assigned_to_user_id bigint(20) NOT NULL,
amount decimal(10,2) NOT NULL,
date_of_expense datetime NOT NULL,
expense_comment varchar(255) NOT NULL,
PRIMARY KEY (id),
FOREIGN KEY(user_id) REFERENCES users(id)
);


CREATE TABLE IF NOT EXISTS expense_category_assigned_to_users(

id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
user_id bigint(20) unsigned NOT NULL,
name varchar(255) NOT NULL,
category_limit DECIMAL(10,2) NULL,
PRIMARY KEY (id),
FOREIGN KEY(user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS expense_category_default(

id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
name varchar(255) NOT NULL,
PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS incomes(

id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
user_id bigint(20) unsigned NOT NULL,
income_category_assigned_to_user_id	bigint(20) NOT NULL,
amount decimal(10,2) NOT NULL,
date_of_income datetime NOT NULL,
income_comment varchar(255) NOT NULL,
PRIMARY KEY (id),
FOREIGN KEY(user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS income_category_assigned_to_users(

id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
user_id bigint(20) unsigned NOT NULL,
name varchar(255) NOT NULL,
PRIMARY KEY (id),
FOREIGN KEY(user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS income_category_default(

id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
name varchar(255) NOT NULL,
PRIMARY KEY (id)
);


CREATE TABLE IF NOT EXISTS payment_methods_assigned_to_users(

id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
user_id bigint(20) unsigned NOT NULL,
name varchar(255) NOT NULL,
PRIMARY KEY (id),
FOREIGN KEY(user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS payment_methods_default(

id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
name varchar(255) NOT NULL,
PRIMARY KEY (id)
);


INSERT INTO expense_category_default (name) VALUES
('jedzenie'),
('mieszkanie'),
('transport'),
('telekomunikacja'),
('opieka zdrowotna'),
('ubranie'),
('higiena'),
('dzieci'),
('rozrywka'),
('wycieczka'),
('szkolenia'),
('książki'),
('oszczędności'),
('emerytura'),
('spłata długów'),
('darowizna'),
('inne wydatki');

INSERT INTO income_category_default (name) VALUES
('wynagrodzenie'),
('odsetki bankowe'),
('sprzedaż na allegro'),
('inne');

INSERT INTO payment_methods_default (name) VALUES
('gotówka'),
('karta debetowa'),
('karta kredytowa');

ALTER TABLE expense_category_assigned_to_users
ADD COLUMN category_limit DECIMAL(10,2) NULL AFTER name;