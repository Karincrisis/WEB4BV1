<?php
session_start();
include('../controladores/controladores_vistas.php');

// Verificar que el usuario esté logueado
verificarSesionEmpleador();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idOferta = $_POST['idOferta'];
    // Cambiar el estado de la oferta a 'oculta'
    cambiarEstadoOferta($idOferta, 'oculta');
    header('Location: ../secciones/publicaciones.php'); // Redirigir a la lista de ofertas
    exit();
}
?>
