<?php
// Iniciar sesión para obtener el candidato
session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo "No hay un usuario con sesión iniciada.";
    exit;
}

$usuario_id = $_SESSION['usuario_id'];  // Usar idUsuario para identificar al candidato

// Conectar a la base de datos
$host = 'localhost';
$db = 'tantsevat';
$user = 'admin';
$pass = 'W3B#t4nts3v4t';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Obtener el idCandidato, industria, aspiración salarial y domicilio del candidato usando el idUsuario
$stmt = $pdo->prepare("SELECT idCandidato, industria, aspiracionSalarial, idDomicilio FROM candidatos WHERE idUsuario = :idUsuario");
$stmt->execute(['idUsuario' => $usuario_id]);
$candidato = $stmt->fetch();

if (!$candidato) {
    echo "Candidato no encontrado.";
    exit;
}

$candidato_id = $candidato['idCandidato']; // Usar idCandidato
$industria = $candidato['industria'];
$aspiracionSalarial = $candidato['aspiracionSalarial'];
$idDomicilio = $candidato['idDomicilio'];

// Obtener el filtro desde la URL
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'default';  // Por defecto, se usa 'industria'

// Funciones para obtener ofertas según diferentes criterios
function getOffersByIndustry($pdo, $industry) {
    $stmt = $pdo->prepare("SELECT * FROM ofertas WHERE industria = :industry");
    $stmt->execute(['industry' => $industry]);
    return $stmt->fetchAll();
}

// Búsqueda por salario (sin cambios)
function getOffersBySalary($pdo, $salary) {
    $stmt = $pdo->prepare("SELECT * FROM ofertas WHERE sueldo >= :salary");
    $stmt->execute(['salary' => $salary]);
    return $stmt->fetchAll();
}

function getOffersByProximity($pdo, $candidateDomicilioId) {
    // Obtener el estado del domicilio del candidato
    $stmt = $pdo->prepare("
        SELECT estado 
        FROM domicilios 
        WHERE idDomicilio = :idDomicilio
    ");
    $stmt->execute(['idDomicilio' => $candidateDomicilioId]);
    $candidateDomicilio = $stmt->fetch();

    if (!$candidateDomicilio) {
        return []; // Si no se encuentra el domicilio, devolver lista vacía
    }

    $estadoCandidato = $candidateDomicilio['estado'];

    // Buscar ofertas cuyo estado coincida con el estado del domicilio del candidato
    $stmt = $pdo->prepare("
        SELECT o.* 
        FROM ofertas o
        JOIN domicilios d ON o.idDomicilio = d.idDomicilio
        WHERE d.estado = :estado
    ");
    $stmt->execute(['estado' => $estadoCandidato]);

    return $stmt->fetchAll();
}

// Función para obtener todas las ofertas
function getAllOffers($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM ofertas");
    $stmt->execute();
    return $stmt->fetchAll();
}



// Lógica para obtener las ofertas según el filtro
switch ($filtro) {
    case 'industria':
        $offers = getOffersByIndustry($pdo, $industria);
        break;

    case 'salario':
        $offers = getOffersBySalary($pdo, $aspiracionSalarial);
        break;

    case 'proximidad':
        $offers = getOffersByProximity($pdo, $idDomicilio);
        break;

    default:
        $offers = getAllOffers($pdo);
        break;
}

// Función para obtener los datos del domicilio asociado a la oferta
function getDomicilioData($pdo, $domicilioId) {
    $stmt = $pdo->prepare("SELECT * FROM domicilios WHERE idDomicilio = :idDomicilio");
    $stmt->execute(['idDomicilio' => $domicilioId]);
    return $stmt->fetch();
}

// Función para verificar si el candidato ya ha aplicado a una oferta
function hasAppliedToOffer($pdo, $candidato_id, $oferta_id) {
    $stmt = $pdo->prepare("SELECT * FROM aplicaciones WHERE idCandidato = :idCandidato AND idOferta = :idOferta");
    $stmt->execute(['idCandidato' => $candidato_id, 'idOferta' => $oferta_id]);
    return $stmt->fetch() !== false;
}

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
            $domicilioData = getDomicilioData($pdo, $offer['idDomicilio']);
            $direccion = '';
            if ($domicilioData) {
                $direccion = urlencode($domicilioData['estado'] . ', ' . 
                                       $domicilioData['municipio'] . ', ' . 
                                       $domicilioData['colonia']);
                $googleMapsUrl = "https://www.google.com/maps?q=" . $direccion;
            }

            // Verificar si el candidato ya ha aplicado a la oferta
            $applied = hasAppliedToOffer($pdo, $candidato_id, $offer['idOferta']);
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
                    <iframe src="https://www.google.com/maps?q=<?php echo $direccion; ?>&output=embed" 
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