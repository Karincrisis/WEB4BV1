<?php
session_start();
include('../controladores/controladores_vistas.php');  // Incluye el archivo de controladores

// Verificar si el usuario está logueado como candidato

verificarSesionCandidato();
$userId = $_SESSION['usuario_id'];
$candidateData = obtenerDatosCandidatoDesdeId($userId);

// Obtener los datos del domicilio si existe un idDomicilio válido
$domicilioData = null;

if ($candidateData && isset($candidateData['idDomicilio'])) {
    $domicilioData = obtenerDomicilioCandidato($candidateData['idDomicilio']);
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
    <title>Datos del Candidato</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

    <div class="contenedor">
        <h1>Datos del Candidato</h1>
        <?php if ($candidateData): ?>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($candidateData['nombre']); ?></p>
            <p><strong>Industria de Interés:</strong> <?php echo htmlspecialchars($candidateData['industria']); ?></p>
            <p><strong>Sueldo Deseado:</strong> $<?php echo number_format($candidateData['aspiracionSalarial'], 2); ?> MXN</p>
        <?php else: ?>
            <p>No se encontraron datos para este candidato.</p>
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
            <p>No se encontraron datos de domicilio para este candidato.</p>
        <?php endif; ?>
    </div>

</body>
</html>