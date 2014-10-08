/*Create database arcanna

Tablas*/
create table upload(
	id serial not null primary key,
	filename varchar not null,
	fileurl varchar not null,
	parts int not null default 0,
	time_per_chunk real not null default 0	
);

create table result_parts(
	id serial not null primary key,
	upload_id int,
	filename varchar not null,
	fileurl varchar not null	
);

/*Foreing Key*/
alter table result_parts
add constraint fk_upload_result_parts
foreign key (upload_id)
references upload(id);
