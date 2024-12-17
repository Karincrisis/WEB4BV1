create database tantsevat;

use tantsevat;


--Tabla de usuarios del sistema. Administrador, empleadores y candidatos
create table usuarios(
    idUsuario int(4) unsigned zerofill auto_increment primary key,
    usuario varchar(50) unique not null,
    contrasenia varchar(20) not null,
    tipoUsuario varchar(30) not null,
    check(tipoUsuario in ('administrador','empleador','candidato'));
);


--Tabla de empresas