<?php
session_start();
    if(empty($_SESSION['usuario_id'] or ($_SESSION['tipoUsuario']!='administrador'))){
        if($_SESSION['nombreUsuario'] != 'administrador'){
            session_destroy();
        }
        header('Location: ./error.php');
    }
    include('../plantillas/cabecera.php');

?>

<main>

<h1>Administrador no disponible, ponganse en contacto con los administradores de servidor</h1>

</main>
<?php

    include('../plantillas/pie.php');
?>