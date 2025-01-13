<?php
session_start();
include('../controladores/controladores_vistas.php');  // Incluye el archivo de controladores

// Verificar si el usuario está logueado como empleador

verificarSesionEmpleador();
$userId = $_SESSION['usuario_id'];
$empleadorData = obtenerDatosEmpleadorDesdeId($userId);

// Obtener los datos del domicilio si existe un idDomicilio válido
$domicilioData = null;

if ($empleadorData && isset($empleadorData['idDomicilio'])) {
    $domicilioData = obtenerDomicilioEmpleador($empleadorData['idDomicilio']);
}

// Generar URL para Google Maps

$googleMapsUrl = '';
if ($domicilioData) {
    $googleMapsUrl = generarUrlGoogleMaps($domicilioData);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos del Empleador</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

    <div class="contenedor">
        <h1>Datos del Empleador</h1>
        <?php if ($empleadorData): ?>
            <p><strong>Nombre de la empresa:</strong> <?php echo htmlspecialchars($empleadorData['nombreEmpresa']); ?></p>
            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($empleadorData['descripcion']); ?></p>
            <p><strong>Industria:</strong> <?php echo htmlspecialchars($empleadorData['industria']); ?></p>
            <p><strong>Tamaño:</strong> <?php echo htmlspecialchars($empleadorData['tamano']); ?></p>
            <p><strong>RFC:</strong> <?php echo htmlspecialchars($empleadorData['rfc']); ?></p>
        <?php else: ?>
            <p>No se encontraron datos para este empleador.</p>
        <?php endif; ?>

        <h2>Domicilio</h2>
        <?php if ($domicilioData): ?>
            <p><strong>Dirección:</strong> 
                <?php echo htmlspecialchars($domicilioData['estado']) . ', ' . 
                           htmlspecialchars($domicilioData['municipio']) . '. ' . 
                           htmlspecialchars($domicilioData['colonia']) . ', ' . 
                           htmlspecialchars($domicilioData['calle']) . ' #' . 
                           htmlspecialchars($domicilioData['numeroExterior']); ?>
            </p>
            <h3>Ubicación en Google Maps</h3>
            <iframe src="<?php echo $googleMapsUrl; ?>" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        <?php else: ?>
            <p>No se encontraron datos de domicilio para este empleador.</p>
        <?php endif; ?>
    </div>

</body>
</html>