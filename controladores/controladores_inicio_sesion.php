<?php
// Abner Ismael Gálvez Hernández
// Diana Karina Zarate Sanchez
include('./conexion.php');

// Mostrar errores de PHP para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombreUsuario = $_POST['nombreUsuario'];
    $contrasenia = $_POST['contrasenia'];

    $conexion = conectar();

    if ($conexion) {
        // Escapar entradas
        $nombreUsuario = $conexion->real_escape_string($nombreUsuario);

        $consulta = "SELECT * FROM usuarios WHERE nombreUsuario = '$nombreUsuario'";
        $resultado = $conexion->query($consulta);

        if ($resultado && $resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();

            // Verificar contraseña
            if (password_verify($contrasenia, $usuario['contrasenia'])) {
                session_start();
                $_SESSION['usuario_id'] = $usuario['idUsuario'];
                $_SESSION['nombreUsuario'] = $usuario['nombreUsuario'];
                $_SESSION['tipoUsuario'] = $usuario['tipoUsuario'];

                // Redirigir según tipo de usuario
                switch ($usuario['tipoUsuario']) {
                    case 'empleador':
                        header("Location: ../secciones/Empleador.php");
                        break;
                    case 'candidato':
                        header("Location: ../secciones/Candidato.php");
                        break;
                    case 'administrador':
                        header("Location: ../secciones/administrador.php");
                        break;
                    default:
                        header("Location: ../secciones/error.php?error=" . urlencode("Tipo de usuario no reconocido."));
                }
                exit();
            } else {
                // Contraseña incorrecta
                header("Location: ../secciones/error.php?error=" . urlencode("Contraseña incorrecta."));
            }
        } else {
            // Usuario no encontrado
            header("Location: ../secciones/error.php?error=" . urlencode("Usuario no encontrado."));
        }
        cerrarConexion($conexion);
    } else {
        header("Location: ../secciones/error.php?error=" . urlencode("Error de conexión a la base de datos."));
    }
} else {
    header("Location: ../secciones/error.php?error=" . urlencode("Método de solicitud no válido."));
}
exit();
?>