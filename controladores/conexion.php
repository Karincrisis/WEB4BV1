<?php


function conectar()
{
	$servidor = 'localhost';
	$usuario = 'admin';
	$clave = 'W3B#t4nts3v4t';
	$baseDatos = 'tantsevat';
	$charset = 'utf8mb4';
	try{
		$conexion = new PDO('mysql:host=localhost;dbname=tantsevat', $usuario, $clave);
		$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $conexion;
	}
	catch (PDOException $e){
		error_log('conexion fallida: '.$e->getMessage());
		return false;
	}
}
function cerrarConexion($conexion){
	if ($conexion)
	{
		$conexion->close();
	}
}

function limpiarCadenas($cadena){
	$cadena = trim(htmlspecialchars($cadena));
	return $cadena;
}
?>