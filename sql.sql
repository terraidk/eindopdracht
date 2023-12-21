create database eindopdracht;

drop database if exists eindopdracht;

use eindopdracht;

create table users (
user_id int primary key auto_increment,
name varchar(50) not null,
email varchar(255) not null unique,
password varchar(255) not null,
address text not null,
is_admin tinyint(1) not null default 0
);

create table cars (
car_id int primary key unique not null auto_increment,
car_brand varchar(20) not null,
car_model varchar(45) not null,
car_year smallint(4) not null,
car_licenseplate varchar(10) not null unique,
car_availability bool not null,
car_dailyprice decimal(6,2),
car_picture longblob
); 

create table renting (
rent_id int primary key unique not null auto_increment,
rent_date datetime not null,
user_id int unique not null,
car_id int unique not null,
rent_startdate datetime not null,
rent_enddate datetime not null,
rent_price decimal(10,2),
foreign key (car_id) references cars(car_id),
foreign key (user_id) references users(user_id)
);

INSERT INTO cars (car_brand, car_model, car_year, car_licenseplate, car_availability, car_dailyprice, car_picture)
VALUES ('Toyota', 'Corolla', 2020, 'ABC123', 1, 50.00, "toyotacorolla.png");

INSERT INTO cars (car_brand, car_model, car_year, car_licenseplate, car_availability, car_dailyprice, car_picture)
VALUES ('Audi', 'R8', 2023, '9-VGB-14', 1, 75.00, "audir8.png");

UPDATE cars
SET car_availability = 1
WHERE car_id = 1;

ALTER TABLE users
ADD COLUMN is_admin TINYINT(1) NOT NULL DEFAULT 0;

UPDATE users
SET is_admin = 1
WHERE user_id = 2; 


select * from users;	

select * from users;

truncate table users;

truncate table cars;

drop table users;

drop table renting;

drop table cars;
