create database eindopdracht;

drop database if exists eindopdracht;

use eindopdracht;


-- tables
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    licensenumber INT NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    phonenumber INT(10) UNIQUE,
    password VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    is_admin TINYINT(1) NOT NULL DEFAULT 0
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
rent_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
user_id int not null,
car_id int not null,
rent_startdate datetime not null,
rent_enddate datetime not null,
rent_price decimal(10,2),
foreign key (car_id) references cars(car_id),
foreign key (user_id) references users(user_id)
);


-- insert into
INSERT INTO cars (car_brand, car_model, car_year, car_licenseplate, car_availability, car_dailyprice, car_picture)
VALUES ('Toyota', 'Corolla', 2020, 'ABC123', 1, 50.00, "toyotacorolla.png");

INSERT INTO cars (car_brand, car_model, car_year, car_licenseplate, car_availability, car_dailyprice, car_picture)
VALUES ('Audi', 'R8', 2023, '9-VGB-14', 1, 75.00, "audir8.png");


-- random
UPDATE cars
SET car_availability = 1
WHERE car_id = 2;

ALTER TABLE users
ADD COLUMN is_admin TINYINT(1) NOT NULL DEFAULT 0;

ALTER TABLE users
ADD COLUMN licensenumber INT DEFAULT NULL;

ALTER TABLE users
MODIFY COLUMN licensenumber INT UNIQUE NOT NULL DEFAULT 0;

ALTER TABLE users
MODIFY COLUMN licensenumber INT UNIQUE NOT NULL DEFAULT 0;

ALTER TABLE renting
DROP FOREIGN KEY renting_ibfk_2;

ALTER TABLE renting
ADD CONSTRAINT renting_ibfk_2
FOREIGN KEY (user_id)
REFERENCES users (user_id)
ON DELETE CASCADE;


UPDATE users
SET is_admin = 1
WHERE user_id = 1; 

UPDATE users 
SET is_admin = 2
WHERE user_id = 2;

delete from users where user_id = 3;


-- select from
select * from users;

select * from cars;

select * from renting;


-- truncate table
truncate table users;

truncate table cars;

truncate table renting;


-- drop table
drop table users;

drop table renting;

drop table cars;