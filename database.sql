CREATE TABLE IF NOT EXISTS users(

id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
username varchar(255) NOT NULL,
email varchar(255) NOT NULL,
password varchar(255) NOT NULL,
PRIMARY KEY(id),
UNIQUE KEY(email)
);