create table if not exists powerUser (
    id int(11) not null PRIMARY KEY AUTO_INCREMENT,
    admin boolean not null,
    moderator boolean not null, 
    userId int(11) not null,
    CONSTRAINT `fk_powerUser_users`
        FOREIGN KEY (userId) REFERENCES users (id)
);

create table if not exists users (
    id int(11) not null PRIMARY KEY AUTO_INCREMENT,
    name varchar(128) not null, 
    email varchar(128) not null,
    uid varchar(128) not null,
    password varchar(128) not null
);

--one user can have one profile
--all forum articles the user partakes in will be listed by using the foreign key
create table if not exists profile (
    id int(11) PRIMARY KEY AUTO_INCREMENT not null,
    bioTitle varchar(128),
    bioDesc varchar(512),
    bioName varchar(128),
    userId int(11) not null,
    CONSTRAINT `fk_profile_users`
        FOREIGN KEY (userId) REFERENCES users (id)
);

create table if not exists profileimg(
    imgId int(11) not null PRIMARY KEY AUTO_INCREMENT,
    userId int(11) not null,
    status int(11) not null,
    CONSTRAINT `fk_profileimg_users`
        FOREIGN KEY (userId) references users (id)
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

--one admin user can have many forum groups
--individual group, made by admins, with articles
create table if not exists forumGroup(
    id int(11) PRIMARY KEY AUTO_INCREMENT not null,
    title not null varchar(128),
    orderNumber longtext not null, 
    userId int(11) not null,
    numberArticles int(11) not null,
    numberFavorites int(11) not null,
    isClosed boolean not null default false,
    isDeleted boolean not null default false,

    description varchar(1024),
    imageFullName longtext,
    CONSTRAINT `fk_forumGroup_users`
        FOREIGN KEY (userId) REFERENCES users (id)
);

--one article can have one user
--many articles can have one group
--individual article, made by users, with post items
create table if not exists forumArticle(
    id int(11) PRIMARY KEY AUTO_INCREMENT not null,
    orderNumber longtext not null, 
    userId int(11) not null,
    forumGroupId int(11) not null,
    numberComments int(11) not null default 0,
    numberDislikes int(11) not null default 0,
    numberLikes int(11) not null default 0,
    isClosed boolean not null default false, 
    isDeleted boolean not null default false,
    title varchar(128),
    description varchar(1024),
    imageFullName longtext,
    CONSTRAINT `fk_forumArticle_forumGroup_users_`
        FOREIGN KEY (userId) REFERENCES users (id),
        FOREIGN KEY (forumGroupId) REFERENCES forumGroup(id)
);


--one item can have one user
--many items can have one article
--individual posts made by users
create table if not exists forumItem(
    id int(11) PRIMARY KEY AUTO_INCREMENT not null,
    orderNumber longtext not null, 
    userId int(11) not null,
    forumArticleId int(11) not null,
    numberDislikes int(11) not null default 0,
    numberLikes int(11) not null default 0,
    isDeleted boolean not null default false,
    text varchar(4096),
    imageFullName longtext,
    CONSTRAINT `fk_forumGroup_users_forumArticle`
        FOREIGN KEY (userId) REFERENCES users (id),
        FOREIGN KEY (forumArticleId) REFERENCES forumArticle (id)
);

create table if not exists moderation(
    id int(11) PRIMARY KEY AUTO_INCREMENT not null,
    reason longtext not null,
    moderationType varchar(128) not null,
    moderatorUserId int(11) not null,
    moderatedUserId int(11) not null,
    groupId int(11) not null,
    articleId int(11),
    itemId int(11),
    CONSTRAINT `fk_users_group_article_item`
        FOREIGN KEY (moderatorUserId) references users (id), 
        FOREIGN KEY (moderatedUserId) references users (id), 
        FOREIGN KEY (groupId) references forumGroup(id),
        FOREIGN KEY (articleId) references forumArticle(id),
        FOREIGN KEY (itemId) references forumItem(id)
);

create table if not exists forumArticle_usersLikes_bridge(
    articleId int(11) not null,
    userId int(11) not null,
    likesArticle boolean,
    dislikesArticle boolean,
    PRIMARY KEY (articleId, userId),
    CONSTRAINT `fk_forumArticles_userLikes_bridge`
        FOREIGN KEY (articleId) REFERENCES forumArticle(id),
        FOREIGN KEY (userId) REFERENCES users (id)
);

create table if not exists forumGroup_userFavorites_bridge(
    forumGroupId int(11) not null,
    userId int(11) not null,
    PRIMARY KEY (forumGroupId, userId),
    CONSTRAINT `fk_forumGroup_usersFavorites_bridge`
        FOREIGN KEY (forumGroupId) REFERENCES forumGroup(id),
        FOREIGN KEY (userId) REFERENCES users (id)
);

