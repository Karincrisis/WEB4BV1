<?php

	$conexion = new mysqli('localhost','root','su57_f22','prospectosdb');
	$conexion->set_charset('utf8');
	

	if($conexion->connect_error)
	{
		echo 'conexión fallida';
	}else
	{
		echo 'conexión exitosa a base de datos';
	}

	$conexion->close();
?>
