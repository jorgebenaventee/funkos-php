create table categories
(
    id         char(36) primary key  default (uuid()),
    name       varchar(255) not null unique,
    is_deleted boolean      not null default false,
    created_at timestamp    not null default now(),
    updated_at timestamp    not null default now() on update now()

);

create table funkos
(
    id          char(36) primary key    default (uuid()),
    name        varchar(255)   not null,
    price       numeric(10, 2) not null,
    stock       integer        not null,
    category_id char(36)       not null,
    image       varchar(255)   not null,
    created_at  timestamp      not null default now(),
    updated_at  timestamp      not null default now() on update now(),
    foreign key (category_id) references categories (id)
);


create table roles
(
    id   int primary key auto_increment,
    name varchar(255) not null
);

create table users
(
    id         int primary key auto_increment,
    username   varchar(255) not null,
    password   varchar(255) not null,
    created_at timestamp    not null default now(),
    updated_at timestamp    not null default now() on update now()
);


create table user_roles
(
    user_id int not null,
    role_id int not null,
    foreign key (user_id) references users (id),
    foreign key (role_id) references roles (id)

);

insert into roles(name)
values ('ADMIN'),
       ('USER');

-- admin admin1234 | user user1234
insert
into users(username, password)
values ('admin', '$2a$12$osH79SQXtSksL60A19n8uOJXHztnmSSPqXhaYJ6hiDfmH9kf3iwpi'),
       ('user', '$2a$12$mtcVy/iehJCDpxmB.xFFa.8TW3RBK.sAV38RzbLUHtWdLUkazJKZu');


insert
into user_roles(user_id, role_id)
values (1, 1),
       (1, 2),
       (2, 2);