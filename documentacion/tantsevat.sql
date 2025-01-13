
create database tantsevat character set utf8mb4 collate utf8mb4_spanish_ci;
use tantsevat;

create table usuarios(
    idUsuario int unsigned zerofill auto_increment primary key,
    nombreUsuario varchar(30) unique not null,
    contrasenia varchar(70) not null,
    estaoUsuario enum('activo','inactivo') default 'activo',
    tipoUsuario varchar(13),
    check(tipoUsuario in ('administrador', 'empleador', 'candidato'))
);

create table domicilios(
    idDomicilio int unsigned zerofill auto_increment primary key,
    estado varchar(20) not null,
    municipio varchar(50) not null,
    colonia varchar(50) not null, 
    calle varchar(50) not null, 
    numeroExterior varchar(4) default '----',
    numeroInterior varchar(4) default '----'
);

create table empleadores(
    idEmpleador int unsigned zerofill auto_increment primary key,
    idUsuario int unsigned not null,
    nombreEmpresa varchar(50) unique not null,
    descripcion varchar(250) not null,
    industria varchar(30) default 'no especificada',
    tamano enum('pequeña', 'mediana', 'grande') default 'pequeña',
    rfc varchar(13) unique not null,
    idDomicilio int unsigned,
    foreign key (idDomicilio) references domicilios(idDomicilio),
    foreign key (idUsuario) references usuarios(idUsuario)
);

create table candidatos (
    idCandidato int unsigned zerofill auto_increment primary key,
    idUsuario int unsigned not null,
    nombre varchar(30) not null,
    apellidoPaterno varchar(50) not null,
    apellidoMaterno varchar(50) default '---',
    fechaNacimiento date not null,
    escolaridad varchar(40) default 'ninguna',
    industria varchar(30),
    aspiracionSalarial decimal(10,2) check(aspiracionSalarial > 0),
    idDomicilio int unsigned,
    foreign key (idDomicilio) references domicilios(idDomicilio),
    foreign key (idUsuario) references usuarios(idUsuario)
);

create table ofertas (
    idOferta int unsigned zerofill auto_increment primary key,
    puesto varchar(50) not null,
    sueldo decimal(10,2) check(sueldo > 0),
    descripcion varchar(300) not null,
    cantidadVacantes int(4) check( cantidadVacantes > 0),
    industria varchar(30) default 'no especificada',
    duracionContrato enum('temporal', 'indefinido') not null,
    horario varchar(50),
    fechaExpiracion date not null,
    idEmpleador int unsigned,
    idDomicilio int unsigned,
    foreign key (idEmpleador) references empleadores(idEmpleador),
    foreign key (idDomicilio) references domicilios(idDomicilio)
);

create table aplicaciones(
    idAplicacion int unsigned zerofill auto_increment primary key,
    idCandidato int unsigned not null,
    idOferta int unsigned not null,
    fechaAplicacion date not null,
    estadoAplicacion enum('pendiente','aceptado','rechazado') default 'pendiente',
    foreign key(idCandidato) references candidatos(idCandidato),
    foreign key(idOferta) references ofertas(idOferta)
);