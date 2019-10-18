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
	definition json         not null
);

create table resource_managers (
    resource_code varchar(32)  not null,
    manager_id    int unsigned not null,
    foreign key (resource_code) references resources(code),
    foreign key (manager_id   ) references people   (id)
);

create table account_requests (
	id           int unsigned not null primary key auto_increment,
	requester_id int unsigned not null,
	username     varchar(32)  not null,
	type         varchar(16)  not null,
	status       varchar(16)  not null,
	created      timestamp    not null default CURRENT_TIMESTAMP,
	modified     timestamp    not null default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	completed    timestamp        null,
	foreign key (requester_id) references people(id)
);

create table resource_requests (
	id            int unsigned not null primary key auto_increment,
	request_id    int unsigned not null,
	resource_code varchar(32)  not null,
	type          varchar(16)  not null,
	status        varchar(16)  not null,
	created       timestamp    not null default CURRENT_TIMESTAMP,
	modified      timestamp    not null default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	completed     timestamp        null,
	resource_data json         not null,
	foreign key (request_id   ) references account_requests(id),
	foreign key (resource_code) references resources       (code)
);
