<?php
session_start();

// Verificar si el usuario está logueado como candidato
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipoUsuario'] !== 'candidato') {
    header("Location: ../secciones/error.php?error=" . urlencode("Acceso no autorizado."));
    exit();
}

$host = 'localhost';
$db = 'tantsevat';
$user = 'admin';
$pass = 'W3B#t4nts3v4t';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

// Función para obtener los datos del candidato
function getCandidateData($pdo, $userId) {
    $stmt = $pdo->prepare("SELECT * FROM candidatos WHERE idCandidato = :idCandidato");
    $stmt->execute(['idCandidato' => $userId]);

    return $stmt->fetch();
}

// Función para obtener los detalles del domicilio
function getDomicilioData($pdo, $domicilioId) {
    $stmt = $pdo->prepare("SELECT * FROM domicilios WHERE idDomicilio = :idDomicilio");
    $stmt->execute(['idDomicilio' => $domicilioId]);

    return $stmt->fetch();
}

$userId = $_SESSION['usuario_id'];
$candidateData = getCandidateData($pdo, $userId);

// Obtener los datos del domicilio si existe un idDomicilio válido
$domicilioData = null;
if ($candidateData && isset($candidateData['idDomicilio'])) {
    $domicilioData = getDomicilioData($pdo, $candidateData['idDomicilio']);
}

// Generar URL para Google Maps
$direccion = '';
if ($domicilioData) {
    $direccion = urlencode($domicilioData['estado'] . ', ' . 
                           $domicilioData['municipio'] . ', ' . 
                           $domicilioData['colonia']);
    $googleMapsUrl = "https://www.google.com/maps?q=" . $direccion;
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
            <iframe src="https://www.google.com/maps?q=<?php echo $direccion; ?>&output=embed" 
                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        <?php else: ?>
            <p>No se encontraron datos de domicilio para este candidato.</p>
        <?php endif; ?>
    </div>

</body>
</html>