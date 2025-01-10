<?php
// Abner Ismael Gálvez Hernández
    include('./conexion.php');
// Verificar si los datos fueron enviados por el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Obtener los datos del formulario
    $nombreUsuario = $_POST['nombreUsuario'];
    $contrasenia = $_POST['contrasenia'];

    // Conectar a la base de datos
    $conexion = conectar();

    if ($conexion) {
        // Escapar los datos para evitar inyecciones SQL
        $nombreUsuario = $conexion->real_escape_string($nombreUsuario);
        $contrasenia = $conexion->real_escape_string($contrasenia);

        $consulta = "SELECT * FROM usuarios WHERE nombreUsuario = '$nombreUsuario'";
        $resultado = $conexion->query($consulta);

        if ($resultado && $resultado->num_rows > 0) {
            // El usuario existe, obtener datos
            $usuario = $resultado->fetch_assoc();

            // Verificar si la contraseña es correcta (suponiendo que está almacenada como un hash)
            if (password_verify($contrasenia, $usuario['contrasenia'])) {
                // Iniciar sesión y redirigir al usuario
                session_start();
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nombreUsuario'] = $usuario['nombreUsuario'];
                header("Location: ../vistas/inicio.php"); // Redirigir a la página principal o dashboard
                exit();
            } else {
                // Contraseña incorrecta
                $error = "Contraseña incorrecta.";
            }
        } else {
            // El usuario no existe
            $error = "El usuario o correo no existe.";
        }

        // Cerrar la conexión
        cerrarConexion($conexion);
    } else {
        $error = "No se pudo conectar a la base de datos.";
    }
}

?>
