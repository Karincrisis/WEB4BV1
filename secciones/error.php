<?php
    session_start();
    $error = $_SESSION['error'] ?? null;
    unset($_SESSION['error']);
    include('../plantillas/cabecera.php');
?>
<main>
    <div class="presentacion-inicio">
        <h1><?php echo $error; ?></h1>
            <?php
                switch ($error) {
                    case 'Contraseña incorrecta':
                        echo '<img src="../imagenes/error1.jpeg" alt="Contraseña incorrecta" class="imagen-error">';
                        echo '<p>La contraseña que ingresaste es incorrecta. Por favor, intenta nuevamente.</p>';
                        break;
                    case 'Usuario no encontrado':
                        echo '<img src="../imagenes/error2.jpeg" alt="Usuario no encontrado" class="imagen-error">';
                        echo '<p>No hemos encontrado un usuario con ese nombre. Asegúrate de escribirlo correctamente.</p>';
                        break;
                    case 'Error en consulta':
                        echo '<img src="../imagenes/error3.jpeg" alt="Tipo de usuario no reconocido" class="imagen-error">';
                        echo '<p>El tipo de usuario no ha sido reconocido. Por favor, contacta con el soporte.</p>';
                        break;
                    case 'Error en conexión a base de datos':
                        echo '<img src="../imagenes/error4.jpeg" alt="Error de conexión" class="imagen-error">';
                        echo '<p>Hubo un problema al intentar conectarnos a la base de datos. Por favor, intenta más tarde.</p>';
                        break;
                    case 'Ingresa tu datos nuevamente':
                        echo '<img src="../imagenes/error5.jpeg" alt="Método de solicitud no válido" class="imagen-error">';
                        echo '<p>La solicitud que enviaste no es válida. Por favor, intenta nuevamente.</p>';
                        break;
                    default:
                        echo '<img src="../imagenes/error.jpeg" alt="Error desconocido" class="imagen-error">';
                        echo '<p>Hubo un error desconocido. Por favor, intenta más tarde.</p>';
                        break;
                }
            ?>
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
    </div>
</main>
<?php
include('../plantillas/pie.php');
?>