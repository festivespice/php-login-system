create table if not exists users (
    id int(11) not null PRIMARY KEY AUTO_INCREMENT,
    name varchar(128) not null, 
    email varchar(128) not null,
    uid varchar(128) not null,
    password varchar(128) not null
);

--one user can have many gallery items
create table if not exists galleryItem (
    id int(11) PRIMARY KEY AUTO_INCREMENT not null,
    title longtext not null,
    description longtext not null,
    imageFullName longtext not null,
    orderNumber longtext not null, 
    userId int(11) not null,
    CONSTRAINT `fk_galleryItem_users`
        FOREIGN KEY (userId) REFERENCES users (id)
);
