create table users (
    id int(11) not null PRIMARY KEY AUTO_INCREMENT,
    name varchar(128) not null, 
    email varchar(128) not null,
    uid varchar(128) not null,
    password varchar(128) not null
);
--use varchar instead of text, because text appears as binary

create table profileimg(
    imgId int(11) not null PRIMARY KEY AUTO_INCREMENT,
    userId int(11) not null,
    status int(11) not null
);
