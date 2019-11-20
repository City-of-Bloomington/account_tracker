create table people (
	id        int unsigned not null primary key auto_increment,
	firstname varchar(128) not null,
	lastname  varchar(128) not null,
	email     varchar(128) unique,
	username  varchar(40)  unique,
	password  varchar(40),
	role      varchar(30),
	authentication_method varchar(40)
);

create table resources (
	id         int unsigned not null primary key auto_increment,
	code       varchar(32)  not null unique,
	type       varchar(32)  not null,
	name       varchar(32)  not null,
	class      varchar(128) not null,
	`order`    int unsigned,
	api_key    varchar(64),
	api_secret varchar(128)
);

create table resource_managers (
    resource_code varchar(32)  not null,
    manager_id    int unsigned not null,
    foreign key (resource_code) references resources(code),
    foreign key (manager_id   ) references people   (id)
);

create table account_requests (
	id              int unsigned not null primary key auto_increment,
	requester_id    int unsigned not null,
	employee_number int unsigned not null,
	type            varchar(16)  not null,
	status          varchar(16)  not null,
	created         timestamp    not null default CURRENT_TIMESTAMP,
	modified        timestamp    not null default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	completed       timestamp        null,
	employee        json         not null,
	resources       json         not null,
	foreign key (requester_id) references people(id)
);

create table profiles (
	id        int unsigned not null primary key auto_increment,
	name      varchar(32)  not null unique,
	questions json,
	resources json         not null
);
