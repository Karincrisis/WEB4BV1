-- Insertar usuarios
INSERT INTO usuarios (nombreUsuario, contrasenia, tipoUsuario) VALUES
('admin_user', '$2y$10$zyDZH6c8XDJ3nNQj9P659u8d0/8onNDMCrvy9DpvUk3CTMiXmD642', 'administrador'),
('candidato1', '', 'candidato'),
('candidato2', '', 'candidato'),
('candidato3', '', 'candidato'),
('candidato4', '', 'candidato'),
('candidato5', '', 'candidato'),
('candidato6', '', 'candidato'),
('candidato7', '', 'candidato'),
('candidato8', '', 'candidato'),
('candidato9', '', 'candidato'),
('candidato10', '', 'candidato'),
('empleador1', '', 'empleador'),
('empleador2', '', 'empleador'),
('empleador3', '', 'empleador'),
('empleador4', '', 'empleador'),
('empleador5', '', 'empleador'),
('empleador6', '', 'empleador'),
('empleador7', '', 'empleador'),
('empleador8', '', 'empleador'),
('empleador9', '', 'empleador'),
('empleador10', '', 'empleador');

-- Insertar domicilios
INSERT INTO domicilios (estado, municipio, colonia, calle, numeroExterior, numeroInterior) VALUES
('Jalisco', 'Guadalajara', 'Centro', 'Avenida Juárez', '123', '1'),
('Jalisco', 'Zapopan', 'La Estancia', 'Calle del Sol', '456', '2'),
('Nuevo León', 'Monterrey', 'San Pedro', 'Calle de la Paz', '789', '3'),
('CDMX', 'Cuauhtémoc', 'Roma', 'Calle de Durango', '101', '4'),
('Puebla', 'Puebla', 'Centro Histórico', 'Calle 5 de Mayo', '202', '5'),
('Jalisco', 'Tlaquepaque', 'El Salto', 'Calle de la Luz', '303', '6'),
('Yucatán', 'Mérida', 'Centro', 'Calle 62', '404', '7'),
('Veracruz', 'Veracruz', 'Centro', 'Calle 16 de Septiembre', '505', '8'),
('Guanajuato', 'Guanajuato', 'Centro', 'Calle de la Universidad', '606', '9'),
('Querétaro', 'Querétaro', 'Centro', 'Calle 5 de Febrero', '707', '10'),
('Jalisco', 'Guadalajara', 'Zapata', 'Calle de la Amistad', '808', '11'),
('Jalisco', 'Guadalajara', 'Ladrón de Guevara', 'Calle de la Libertad', '909', '12'),
('CDMX', 'Coyoacán', 'Coyoacán', 'Calle de la Coyoacán', '111', '13'),
('CDMX', 'Iztapalapa', 'San Lázaro', 'Calle de la Paz', '222', '14'),
('Baja California', 'Tijuana', 'Zona Río', 'Calle del Río', '333', '15'),
('Chihuahua', 'Chihuahua', 'Centro', 'Calle de la Revolución', '444', '16'),
('Sonora', 'Hermosillo', 'Centro', 'Calle de la Independencia', '555', '17'),
('Sinaloa', 'Culiacán', 'Centro', 'Calle de la Libertad', '666', '18'),
('Durango', 'Durango', 'Centro', 'Calle de la Constitución', '777', '19'),
('Zacatecas', 'Zacatecas', 'Centro', 'Calle de la Plaza', '888', '20');

-- Insertar empleadores
INSERT INTO empleadores (idUsuario, nombreEmpresa, descripcion, industria, tamano, rfc, idDomicilio) VALUES
(12, 'Empresa A', 'Descripción de Empresa A', 'Tecnología', 'mediana', 'RFC123456789', 1),
(13, 'Empresa B', 'Descripción de Empresa B', 'Salud', 'grande', 'RFC987654321', 2),
(14, 'Empresa C', 'Descripción de Empresa C', 'Educación', 'pequena', 'RFC111222333', 3),
(15, 'Empresa D', 'Descripción de Empresa D', 'Tecnología', 'mediana', 'RFC444555666', 4),
(16, 'Empresa E', 'Descripción de Empresa E', ' Marketing', 'grande', 'RFC777888999', 5),
(17, 'Empresa F', 'Descripción de Empresa F', 'Salud', 'pequena', 'RFC000111222', 6),
(18, 'Empresa G', 'Descripción de Empresa G', 'Educación', 'mediana', 'RFC333444555', 7),
(19, 'Empresa H', 'Descripción de Empresa H', 'Tecnología', 'grande', 'RFC666777888', 8),
(20, 'Empresa I', 'Descripción de Empresa I', 'Marketing', 'mediana', 'RFC999000111', 9),
(21, 'Empresa J', 'Descripción de Empresa J', 'Salud', 'pequena', 'RFC222333444', 10);

-- Insertar candidatos
INSERT INTO candidatos (idCandidato, idUsuario, nombre, apellidoPaterno, apellidoMaterno, fechaNacimiento, escolaridad, industria, aspiracionSalarial, idDomicilio) VALUES
(2, 2, 'Juan', 'Pérez', 'García', '1990-01-15', 'Licenciatura', 'Tecnología', 30000.00, 1),
(3, 3, 'María', 'López', 'Hernández', '1992-02-20', 'Maestría', 'Salud', 25000.00, 2),
(4, 4, 'Carlos', 'González', 'Martínez', '1988-03-10', 'Licenciatura', 'Educación', 20000.00, 3),
(5, 5, 'Ana', 'Ramírez', 'Sánchez', '1995-04-25', 'Licenciatura', 'Tecnología', 28000.00, 4),
(6, 6, 'Luis', 'Torres', 'Morales', '1991-05-30', 'Licenciatura', 'Marketing', 22000.00, 5),
(7, 7, 'Sofía', 'Flores', 'Jiménez', '1993-06-15', 'Licenciatura', 'Salud', 24000.00, 6),
(8, 8, 'Diego', 'Hernández', 'Cruz', '1989-07-22', 'Licenciatura', 'Educación', 21000.00, 7),
(9, 9, 'Valeria', 'Mendoza', 'Ríos', '1994-08-18', 'Licenciatura', 'Tecnología', 35000.00, 8),
(10, 10, 'Andrés', 'Vázquez', 'Salazar', '1990-09-05', 'Licenciatura', 'Marketing', 19000.00, 9),
(11, 11, 'Claudia', 'Jiménez', 'Pérez', '1992-10-12', 'Maestría', 'Salud', 26000.00, 10);

-- Insertar ofertas
INSERT INTO ofertas (puesto, sueldo, descripcion, cantidadVacantes, industria, duracionContrato, horario, fechaExpiracion, idEmpleador, idDomicilio) VALUES
('Desarrollador', 32000.00, 'Desarrollador de software', 5, 'Tecnología', 'indefinido', '9am - 5pm', '2023-12-31', 1, 1),
('Médico', 24000.00, 'Médico general', 3, 'Salud', 'temporal', '8am - 4pm', '2023-11-30', 2, 2),
('Profesor', 22000.00, 'Profesor de matemáticas', 2, 'Educación', 'indefinido', '8am - 3pm', '2023-10-31', 3, 3),
('Analista de Marketing', 21000.00, 'Analista de marketing digital', 4, 'Marketing', 'temporal', '10am - 6pm', '2023-11-15', 4, 4),
('Enfermero', 20000.00, 'Enfermero para hospital', 2, 'Salud', 'indefinido', '7am - 3pm', '2023-12-15', 5, 5),
('Desarrollador Web', 35000.00, 'Desarrollador web senior', 3, 'Tecnología', 'indefinido', '9am - 5pm', '2023-12-31', 6, 6),
('Coordinador Académico', 26000.00, 'Coordinador de programas académicos',  3, 'Educación', 'temporal', '8am - 4pm', '2023-11-30', 7, 7),
('Gerente de Marketing', 30000.00, 'Gerente de marketing estratégico', 2, 'Marketing', 'indefinido', '9am - 5pm', '2023-12-31', 8, 8),
('Asistente Médico', 18000.00, 'Asistente para consulta médica', 4, 'Salud', 'temporal', '8am - 4pm', '2023-11-15', 9, 9),
('Desarrollador Junior', 15000.00, 'Desarrollador de software junior', 5, 'Tecnología', 'indefinido', '9am - 5pm', '2023-12-31', 10, 10);

-- Insertar aplicaciones
INSERT INTO aplicaciones (idCandidato, idOferta, fechaAplicacion, estadoAplicacion) VALUES
(2, 1, '2023-10-01', 'aceptado'),  -- Juan aplica a Desarrollador
(2, 2, '2023-10-02', 'rechazado'), -- Juan aplica a Médico
(2, 3, '2023-10-03', 'pendiente'); -- Juan aplica a Profesor

-- Modificar contraseñas de los usuarios
UPDATE usuarios SET contrasenia = '$2y$10$zyDZH6c8XDJ3nNQj9P659u8d0/8onNDMCrvy9DpvUk3CTMiXmD642' WHERE nombreUsuario = 'admin_user';
UPDATE usuarios SET contrasenia = '$2y$10$2BLnVJGwg9SMvILQL3dZvege4cGAkXTU0XG3gDU4.KevTh3QYr3wm' WHERE nombreUsuario = 'candidato1';
UPDATE usuarios SET contrasenia = '$2y$10$dAdiVV7PB/PIDlwydFuEB.3YWq6/sF/xwBBYGV7TLkjlzn69kzRAe' WHERE nombreUsuario = 'candidato2';
UPDATE usuarios SET contrasenia = '$2y$10$/srHQsI/lOLxYbvauOaiDO0dTkq8P/uAjBjJzrxjtXElNtpzybJm2' WHERE nombreUsuario = 'candidato3';
UPDATE usuarios SET contrasenia = '$2y$10$j65Y74FpIW/da8sUWrur3.94eiuBXA/Ff5Ag85pFfNs0io3t2zBm2' WHERE nombreUsuario = 'candidato4';
UPDATE usuarios SET contrasenia = '$2y$10$qtK6IiM4dfMhnwP9kWByU.NaloVwZOxiplajP7OY7H/ZBG4MYjEn.' WHERE nombreUsuario = 'candidato5';
UPDATE usuarios SET contrasenia = '$2y$10$9RFiIvMt/JDacNAmvtc5dOIplatJGc4rRGe14VIF4c/akNhBRrn2C' WHERE nombreUsuario = 'candidato6';
UPDATE usuarios SET contrasenia = '$2y$10$RWiXXdzYhjoSxKIyRTB8DO96O/SSwTxgw1sCwpbGRFX6mYuouCB8a' WHERE nombreUsuario = 'candidato7';
UPDATE usuarios SET contrasenia = '$2y$10$FYDhDuW9b28R8Z1G801zRuZa2gttgCMpxRIC43NrKWzPV/.jBpI.q' WHERE nombreUsuario = 'candidato8';
UPDATE usuarios SET contrasenia = '$2y$10$cs22yj2XKyuTHBjLcss/COus/t27Oq5ydYLP2gWqnJE7.1Dhe6pt6' WHERE nombreUsuario = 'candidato9';
UPDATE usuarios SET contrasenia = '$2y$10$hN1epA9SIj/4eMVuDx8ebOtV/4.r85Xf6UlzMlchDqBuG5lS/SS1i' WHERE nombreUsuario = 'candidato10';
UPDATE usuarios SET contrasenia = '$2y$10$4vzd1ZC6lhn/uXmxfUwCT.OvMEyeqa/MUGZITpK6RWdgzoasp0kRi' WHERE nombreUsuario = 'empleador1';
UPDATE usuarios SET contrasenia = '$2y$10$9RA/APt311taLVmRFqafzuaHgwU31p6ZnuDi1OhPTfb7obcJv2t96' WHERE nombreUsuario = 'empleador2';
UPDATE usuarios SET contrasenia = '$2y$10$pfAArRBf2UOQtOibTdQaKugdCU28EL1cW.pfMQLwXJFnWKXv8ASxS' WHERE nombreUsuario = 'empleador3';
UPDATE usuarios SET contrasenia = '$2y$10$Y/ukKKrhrufeHNC7RK4dh.yvuohEXEhD4FUFSHKHm6e7pjgS3SU8G' WHERE nombreUsuario = 'empleador4';
UPDATE usuarios SET contrasenia = '$2y$10$VIOtbFR9VzbL6xJ0qg1fQOej6RWRSg3Wh9oLe3EvlJZRLzCQC1QZ6' WHERE nombreUsuario = 'empleador5';
UPDATE usuarios SET contrasenia = '$2y$10$J1DTzkK5to9/c6ebXYD3MuCguJDRLEuZMOuG8.NBS9r.N5fVTnA76' WHERE nombreUsuario = 'empleador6';
UPDATE usuarios SET contrasenia = '$2y$10$K3OuE4CtEuREnV027WPJse40sNzErVILDsVEyTcyRKjXfnbSHa0S.' WHERE nombreUsuario = 'empleador7';
UPDATE usuarios SET contrasenia = '$2y$10$EdCvAKwx/9K6vreUzUENde.zH8pPjuXeXIrKy2/R2h343aljCGzXO' WHERE nombreUsuario = 'empleador8';
UPDATE usuarios SET contrasenia = '$2y$10$wTkgNcI.vWP9fAkWEcGSt.t0CwDTDAUZtFFHh.Im9dfhztvMJxHcO' WHERE nombreUsuario = 'empleador9';
UPDATE usuarios SET contrasenia = '$2y$10$P4le8QiZUwF24gjTgvqFDulwOrfKuV3yQJe.ZdinMrqWYiLDtvKJS' WHERE nombreUsuario = 'empleador10';