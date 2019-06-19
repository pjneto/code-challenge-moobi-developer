CREATE DATABASE IF NOT EXISTS bd_toy_shop_dev;

USE bd_toy_shop_dev;

CREATE TABLE IF NOT EXISTS tb_product (
	id int PRIMARY KEY NOT NULL AUTO_INCREMENT, 
	name varchar(150) NOT NULL, 
	description varchar(500) NOT NULL, 
	barcode varchar (20) NOT NULL, 
	price numeric(10, 2) NOT NULL DEFAULT 0, 
	stock int NOT NULL DEFAULT 0,
	cod_status int NOT NULL, 
	date timestamp, 
	date_update timestamp NULL
);

CREATE TABLE IF NOT EXISTS tb_order (
	id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    discount numeric (10, 2) NOT NULL,
    value numeric (10, 2) NOT NULL,
    num_parcel int,
    value_parcel numeric (10, 2) NOT NULL,
    payment varchar(200) NOT NULL,
    cod_payment int NOT NULL,
    status varchar(200) NOT NULL,
    cod_status int NOT NULL,
    date timestamp,
    date_update timestamp NULL
);

CREATE TABLE IF NOT EXISTS tb_order_itens (
	id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_order int NOT NULL REFERENCES tb_order(id),
    id_product int NOT NULL REFERENCES tb_product(id),
    quantity int NOT NULL,
    date timestamp,
    date_update timestamp NULL
);

CREATE TABLE IF NOT EXISTS tb_payment_status (
	id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    code int NOT NULL,
    description varchar(200) NOT NULL
);

CREATE TABLE IF NOT EXISTS tb_order_status (
	id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
    code int NOT NULL,
    description varchar(200) NOT NULL
);


-- SELECT * FROM tb_payment_status;
INSERT INTO tb_payment_status (code, description) 
VALUES (0, "Cash"), (1, "Credit Card"), (2, "Bank Slip");

-- SELECT * FROM tb_order_status
INSERT INTO tb_order_status (code, description)
VALUES (0, "Payed"), (1, "Canceled"), (2, "Open");
