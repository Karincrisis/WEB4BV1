<?php
    include('../plantillas/cabecera.php');
?>

<?php
// Mostrar errores de PHP para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obtener el mensaje de error desde la URL
$error = isset($_GET['error']) ? urldecode($_GET['error']) : 'Error desconocido';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Error</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<main>
    <div class="error-container">
        <h1>Error: <?php echo $error; ?></h1>
        
        <!-- Imágenes y descripciones dependiendo del error -->
        <div class="error-images">
            <?php
            switch ($error) {
                case 'Contraseña incorrecta.':
                    echo '<img src="../imagenes/error1.jpeg" alt="Contraseña incorrecta">';
                    echo '<p>La contraseña que ingresaste es incorrecta. Por favor, intenta nuevamente.</p>';
                    break;
                case 'Usuario no encontrado.':
                    echo '<img src="../imagenes/error2.jpeg" alt="Usuario no encontrado">';
                    echo '<p>No hemos encontrado un usuario con ese nombre. Asegúrate de escribirlo correctamente.</p>';
                    break;
                case 'Tipo de usuario no reconocido.':
                    echo '<img src="../imagenes/error3.jpeg" alt="Tipo de usuario no reconocido">';
                    echo '<p>El tipo de usuario no ha sido reconocido. Por favor, contacta con el soporte.</p>';
                    break;
                case 'Error de conexión a la base de datos.':
                    echo '<img src="../imagenes/error4.jpeg" alt="Error de conexión">';
                    echo '<p>Hubo un problema al intentar conectarnos a la base de datos. Por favor, intenta más tarde.</p>';
                    break;
                case 'Método de solicitud no válido.':
                    echo '<img src="../imagenes/error5.jpeg" alt="Método de solicitud no válido">';
                    echo '<p>La solicitud que enviaste no es válida. Por favor, intenta nuevamente.</p>';
                    break;
                default:
                    echo '<img src="../imagenes/error.jpeg" alt="Error desconocido">';
                    echo '<p>Hubo un error desconocido. Por favor, intenta más tarde.</p>';
                    break;
            }
            ?>
        </div>

        <!-- Botones para navegar -->
        <div class="buttons">
            <a href="../secciones/iniciarSesion.php" class="button">Volver a iniciar sesión</a>
            <a href="../index.html" class="button">Ir a página principal</a>
            <a href="../secciones/registro.php" class="button">Registrarme</a>
        </div>
    </div>
</main>

</body>
</html>

<?php
include('../plantillas/pie.php');
?>