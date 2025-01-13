<?php
    	$servidor = 'localhost';
        $usuario = 'admin';
        $clave = 'W3B#t4nts3v4t';
        $baseDatos = 'tantsevat';
        $charset = 'utf8mb4';

        $con = new PDO('mysql:host=localhost;dbname=tantsevat', $usuario, $clave);

    if($con)
    {
        echo 'conexión a base de datos exitosa';
    }
    else
    {
        echo 'error en la base de datos';
    }
?>