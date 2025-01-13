<?php
// Abner Ismael Gálvez Hernández
// Diana Karina Zarate Sanchez
    include('./conexion.php');
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $nombreUsuario = limpiarCadenas($_POST['nombreUsuario']);
        $contrasenia = limpiarCadenas($_POST['contrasenia']);

        $conexion = conectar(); //conecta a base de datos

        if($conexion){

            try{
                //consulta con seguridad basica a la base de datos 
                $consulta = 'select * from usuarios where nombreUsuario = :nombreUsuario';
                $sentencia = $conexion->prepare($consulta);
                $sentencia->bindParam(':nombreUsuario',$nombreUsuario);
                //ejecución de la consulta
                $sentencia->execute();


                if($sentencia->rowCount() > 0) //verfica si existen datos obtenido de la consulta
                {
                    $usuario = $sentencia->fetch(PDO::FETCH_ASSOC); //ingresa loa datos obtenios a la variable usuarios
                    if(password_verify($contrasenia,$usuario['contrasenia'])){  //verifica si la contraseña es correcta 
                        $_SESSION['usuario'] = $usuario['nombreUsuario'];    //obtiene los datos para $_SESSION
                        $_SESSION['estado'] = $usuario['estadoUsuario']; 
                        $_SESSION['rol'] = $usuario['tipoUsuario']; 

                        $redireccion = 'Location: ../secciones'.$_SESSION['rol'].'.php' ; //redirecciona a la vista correspondiente 

                        header($redireccion);
                        exit();
                    }else{
                        $_SESSION['error'] = 'Contraseña incorrecta';
                    }
                }
                else{
                    $_SESSION['error']  = 'Usuario no encontrado';
                }
            }
            catch (PDOException $e){
                $_SESSION['error']  = 'Error en consulta';
            }
        }
        else{
            $_SESSION['error']  = 'Error en conexión a base de datos';
        }
    }
    else{
        $_SESSION['error']  = 'Ingresa tu datos nuevamente';
    }
    header('Location: ../secciones/error.php');
    exit();
?>