<?php
// Iniciar sesión para obtener el candidato
session_start();
include('../controladores/controladores_vistas.php'); // Incluir las funciones del controlador de vistas

verificarSesion();

$usuario_id = $_SESSION['usuario_id'];  // Usar idUsuario para identificar al candidato
$candidato_id = obtenerIdCandidato($usuario_id); // Obtener idCandidato del usuario

if (!$candidato_id) {
    echo "Candidato no encontrado.";
    exit;
}

// Obtener los datos del candidato

$candidato = obtenerDatosCandidato($candidato_id);
$industria = $candidato['industria'];
$aspiracionSalarial = $candidato['aspiracionSalarial'];
$idDomicilio = $candidato['idDomicilio'];

// Obtener el filtro desde la URL

$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'default';

// Obtener las ofertas según el filtro

$offers = obtenerOfertas($filtro, $candidato_id);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ofertas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            padding: 20px;
        }

        .offer {
            background-color: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .offer h3 {
            margin: 0;
            font-size: 1.5em;
        }

        .offer p {
            margin: 10px 0;
        }

        .no-offers {
            font-size: 1.2em;
            color: #888;
        }

        .apply-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        .apply-button.disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Ofertas Encontradas</h2>

    <?php if (!empty($offers)): ?>
        <?php foreach ($offers as $offer): ?>
            <?php
            // Obtener los datos del domicilio de la oferta
            $domicilioData = obtenerDomicilioCandidato($offer['idDomicilio']);
            $googleMapsUrl = '';
            if ($domicilioData) {
                $googleMapsUrl = generarUrlGoogleMaps($domicilioData);
            }

            // Verificar si el candidato ya ha aplicado a la oferta
            $applied = verificarAplicacionOferta($candidato_id, $offer['idOferta']);
            ?>

            <div class="offer">
                <h3><?php echo htmlspecialchars($offer['puesto']); ?></h3>
                <p><strong>Sueldo:</strong> $<?php echo number_format($offer['sueldo'], 2); ?></p>
                <p><strong>Industria:</strong> <?php echo htmlspecialchars($offer['industria']); ?></p>
                <p><strong>Descripción:</strong> <?php echo htmlspecialchars($offer['descripcion']); ?></p>

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

                <!-- Botón aplicar -->
                <?php if (!$applied): ?>
                    <a href="aplicar.php?idOferta=<?php echo $offer['idOferta']; ?>" class="apply-button">Aplicar</a>
                <?php else: ?>
                    <button class="apply-button disabled" disabled>Ya aplicaste a esta oferta</button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-offers">No se encontraron ofertas para los criterios seleccionados.</p>
    <?php endif; ?>
</div>

</body>
</html>