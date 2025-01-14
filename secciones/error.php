<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    $error = isset($_GET['error']) ? urldecode($_GET['error']) : 'Error desconocido';

    include('../plantillas/cabecera.php');
?>
<main>
    <div class="presentacion-inicio">
        <h1>Error: <?php echo $error; ?></h1>
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
        <div class="botones-inicio">
            <form action="./iniciarSesion.php">
                <button type="submit">Iniciar Sesión</button>
            </form>
            <form action="./registro.php">
                <button type="submit">Registrarme</button>
            </form>
            <form action="../index.html">
                <button type="submit">Ir a la pagina principal</button>
        </form>
    </div>
</main>
<?php
include('../plantillas/pie.php');
?>