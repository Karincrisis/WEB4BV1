<?php
session_start();
include('../controladores/controladores_vistas.php');
include('../conexion.php');

// Verificar que el usuario tiene sesión activa
verificarSesion();

if (!isset($_GET['idOferta'])) {
    echo "ID de la oferta no proporcionado.";
    exit;
}

$idOferta = intval($_GET['idOferta']);
$usuario_id = $_SESSION['usuario_id'];
$candidato_id = obtenerIdCandidato($usuario_id);

if (!$candidato_id) {
    echo "Candidato no encontrado.";
    exit;
}

// Registrar la aplicación en la tabla 'aplicaciones'
$conexion = conectar();
$stmt = $conexion->prepare("INSERT INTO aplicaciones (idCandidato, idOferta, fechaAplicacion, estadoAplicacion) VALUES (?, ?, NOW(), 'pendiente')");
$stmt->bind_param("ii", $candidato_id, $idOferta);

if ($stmt->execute()) {
    // Redirigir al usuario de vuelta a la página de ofertas
    header("Location: ../secciones/ofertas.php");
    exit;
} else {
    echo "Error al aplicar a la oferta: " . $stmt->error;
}

cerrarConexion($conexion);
?>