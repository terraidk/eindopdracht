create database eindopdracht;

use eindopdracht;

create table users (
user_id int primary key auto_increment,
name varchar(50) not null,
email varchar(255) not null unique,
password varchar(255) not null,
address text not null
);

create table cars (
car_id int primary key unique not null auto_increment,
car_brand varchar(20) not null,
car_model varchar(45) not null,
car_year smallint(4) not null,
car_licenseplate varchar(10) not null unique,
car_availability bool not null,
car_dailyprice decimal(6,2)
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



select * from users;	

use test;

select * from users;

truncate table users;

drop table users;

drop table renting;
