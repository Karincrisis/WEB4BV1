<?php


function conectar()
{
	$servidor = 'localhost';
	$usuario = 'admin';
	$clave = 'W3B#t4nts3v4t';
	$baseDatos = 'tantsevat';

	try{
		$conexion = new mysqli($servidor,$usuario,$clave,$baseDatos);
		 // Verificar si la conexión es exitosa

        if ($conexion->connect_error) {
            throw new mysqli_sql_exception("Conexión fallida: " . $conexion->connect_error);
        }
		$conexion->set_charset('utf8');
		return $conexion;
	}catch (mysqli_sql_exception $e){
		// Registro del error
        error_log($e->getMessage());
		return false;
	}
}


function cerrarConexion($conexion){
	if ($conexion)
	{
		$conexion->close();
	}
}
?>