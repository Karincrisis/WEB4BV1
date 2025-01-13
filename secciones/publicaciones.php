<?php
session_start();
include('../controladores/controladores_vistas.php'); // Incluir controladores

// Verificar si el empleador está logueado
verificarSesionEmpleador();
$userId = $_SESSION['usuario_id'];
$empleadorId = obtenerIdEmpleador($userId);

// Obtener las ofertas publicadas por el empleador
$ofertas = obtenerOfertasPorEmpleador($empleadorId);

// Obtener datos de ofertas
$datosO = obtenerDatosOfertas($empleadorId);

// Obtener idDomicilio de las ofertas
$idDomicilio = $datosO['idDomicilio'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Ofertas</title>
    <style>
        .oferta-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .oferta-card {
            width: 30%;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            box-sizing: border-box;
            transition: box-shadow 0.3s;
        }
        .oferta-card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .oferta-title {
            font-size: 18px;
            font-weight: bold;
        }
        .oferta-info {
            margin-top: 10px;
            font-size: 14px;
        }
        .map-container {
            margin-top: 10px;
            width: 100%;
            height: 200px;
            background-color: #f0f0f0;
        }
        #publicar-btn {
            display: block;
            width: 200px;
            padding: 10px;
            margin-top: 20px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
        }
        #publicar-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h2>Mis Ofertas Publicadas</h2>

    <div class="oferta-container">
        <?php foreach ($ofertas as $oferta): ?>
            <?php
            // Obtener los datos del domicilio de la oferta
            $domicilioData = obtenerDomicilioEmpleador($oferta['idDomicilio']);
            $googleMapsUrl = '';
            if ($domicilioData) {
                $googleMapsUrl = generarUrlGoogleMaps($domicilioData);
            }
            ?>
            <div class="oferta-card">
                <div class="oferta-title"><?= htmlspecialchars($oferta['puesto']) ?></div>
                <div class="oferta-info">
                    <p><strong>Industria:</strong> <?= htmlspecialchars($oferta['industria']) ?></p>
                    <p><strong>Sueldo:</strong> $<?php echo number_format($oferta['sueldo'], 2); ?></p>
                    <p><strong>Descripción:</strong> <?= htmlspecialchars($oferta['descripcion']) ?></p>
                    <p><strong>Vacantes:</strong> <?= htmlspecialchars($oferta['cantidadVacantes']) ?></p>
                    <p><strong>Contrato:</strong> <?= htmlspecialchars($oferta['duracionContrato']) ?></p>
                    <p><strong>Horario:</strong> <?= htmlspecialchars($oferta['horario']) ?></p>
                    <p><strong>Expiración:</strong> <?= htmlspecialchars($oferta['fechaExpiracion']) ?></p>
                    
                    <?php if ($domicilioData): ?>
                        <p><strong>Ubicación:</strong> 
                            <?php echo htmlspecialchars($domicilioData['estado']) . ', ' . 
                                   htmlspecialchars($domicilioData['municipio']) . '. ' . 
                                   htmlspecialchars($domicilioData['colonia']); ?>
                        </p>
                        <h3>Ubicación en Google Maps</h3>
                        <iframe src="<?php echo $googleMapsUrl; ?>" 
                                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    <?php else: ?>
                        <p>No se encontró la ubicación para esta oferta.</p>
                    <?php endif; ?>

                    <!-- Mostrar el botón de ocultar o desocultar según el estado -->
                    <?php if ($oferta['estado'] == 'visible'): ?>
                        <!-- Si la oferta está visible, se muestra el botón para ocultarla -->
                        <form method="POST" action="../controladores/ocultar_oferta.php">
                            <input type="hidden" name="idOferta" value="<?= $oferta['idOferta'] ?>">
                            <button type="submit" class="ocultar-btn">Ocultar Oferta</button>
                        </form>
                    <?php else: ?>
                        <!-- Si la oferta está oculta, se muestra el botón para desocultarla -->
                        <form method="POST" action="../controladores/desocultar_oferta.php">
                            <input type="hidden" name="idOferta" value="<?= $oferta['idOferta'] ?>">
                            <button type="submit" class="desocultar-btn">Desocultar Oferta</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <a href="publicar.php" id="publicar-btn">Publicar Nueva Oferta</a>

</body>
</html>

