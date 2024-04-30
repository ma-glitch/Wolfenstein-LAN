"# Wolfenstein-LAN" 

passord til admin: "password"

create database lan;
use lan;
create table pameldinger (
id int auto_increment not null,
navn varchar(255) not null,
epost varchar(255) not null,
adresse varchar(255) not null,
postnr int(4) not null,
primary key (id)
);
