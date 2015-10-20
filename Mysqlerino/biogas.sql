use test;

create table IF NOT EXISTS rol(
	id int not null AUTO_INCREMENT,
	nombre varchar(255), primary key(id));

create table IF NOT EXISTS usuario(
	id int not null AUTO_INCREMENT,
	display_name varchar(255), 
	nombre1 varchar(255), 
	nombre2 varchar(255), 
	apellido1 varchar(255), 
	apellido2 varchar(255), 
	rol_id int, 
	foreign key(rol_id) references rol(id), 
	email varchar(255), 
	date_created dateTime, 
	last_updated dateTime, 
	password varchar(255), 
	enabled int, 
	password_expired int, 
	primary key(id));

create table IF NOT EXISTS sitio(
	id int not null AUTO_INCREMENT,
	display_name varchar(255), 
	nombre varchar(255), 
	calle varchar(255), 
	codigo_postal varchar(255), 
	colonia varchar(255), 
	estado varchar(255), 
	numero_ext varchar(255), 
	numero_int varchar(255), 
	pais varchar(255), 
	tipo varchar(255), 
	
	primary key(id));

create table IF NOT EXISTS usuario_sitio(
	id int not null AUTO_INCREMENT,
	usuario_id int, 
	foreign key(usuario_id) references usuario(id), 
	sitio_id int, 
	foreign key(sitio_id) references sitio(id), 
	primary key(id));

create table IF NOT EXISTS modulo(
	id int not null AUTO_INCREMENT,
	display_name varchar(255), 
	nombre varchar(255), 
	sitio_id int, 
	foreign key(sitio_id) references sitio(id), 
	ip varchar(255), 
	primary key(id));

create table IF NOT EXISTS controlador_biofiltro(
	id int not null AUTO_INCREMENT,
	date_created dateTime, 
	modulo_id int, 
	foreign key(modulo_id) references modulo(id), 
	h2s double, 
	ch4 double, 
	co2 double, 
	o2 double, 
	temperatura double, 
	presion double, 
	flujo double, 
	primary key(id));

create table IF NOT EXISTS sensor(
	id int not null AUTO_INCREMENT,
	date_created dateTime, 
	modulo_id int, 
	foreign key(modulo_id) references modulo(id), 
	ia double, 
	ib double, 
	ic double, 
	va double, 
	vb double, 
	vc double, 
	primary key(id));

create table IF NOT EXISTS controlador_microturbina(
	id int not null AUTO_INCREMENT,
	date_created dateTime, 
	modulo_id int, 
	foreign key(modulo_id) references modulo(id), 
	h2s double, 
	ch4 double, 
	co2 double, 
	o2 double, 
	va double, 
	vb double, 
	vc double, 
	ia double, 
	ib double, 
	ic double, 
	i_neutral double, 
	potencia_total double, 
	temperatura double, 
	presion double, 
	flujo double, 
	primary key(id));
