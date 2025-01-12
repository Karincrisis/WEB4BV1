<?php
// Iniciar sesión para obtener el usuario
session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo "No hay un usuario con sesión iniciada.";
    exit;
}

$usuario_id = $_SESSION['usuario_id']; // ID del usuario logueado

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
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

// Obtener el idCandidato asociado al usuario
$stmt = $pdo->prepare("SELECT idCandidato FROM candidatos WHERE idUsuario = :idUsuario");
$stmt->execute(['idUsuario' => $usuario_id]);
$candidato = $stmt->fetch();

if (!$candidato) {
    echo "Candidato no encontrado.";
    exit;
}

$candidato_id = $candidato['idCandidato'];

// Consulta para obtener datos de las aplicaciones, ofertas y ubicación
$query = "
    SELECT 
        o.puesto,
        o.sueldo,
        o.industria,
        o.descripcion,
        d.estado AS estadoUbicacion,
        d.municipio,
        d.colonia,
        a.estadoAplicacion AS estadoAplicacion
    FROM aplicaciones a
    JOIN ofertas o ON a.idOferta = o.idOferta
    JOIN domicilios d ON o.idDomicilio = d.idDomicilio
    WHERE a.idCandidato = :idCandidato;

";

$stmt = $pdo->prepare($query);
$stmt->execute(['idCandidato' => $candidato_id]);
$aplicaciones = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Aplicaciones</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 20px;
        }

        .application {
            background: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .application h3 {
            margin: 0;
        }

        .application p {
            margin: 5px 0;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            color: white;
            font-weight: bold;
        }

        .status.pending {
            background-color: #f0ad4e;
        }

        .status.accepted {
            background-color: #5cb85c;
        }

        .status.rejected {
            background-color: #d9534f;
        }

        iframe {
            width: 100%;
            height: 300px;
            border: 0;
            border-radius: 5px;
            margin-top: 10px;
        }

        .no-applications {
            text-align: center;
            color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Mis Aplicaciones</h2>

    <?php if (!empty($aplicaciones)): ?>
        <?php foreach ($aplicaciones as $aplicacion): ?>
            <div class="application">
                <h3><?php echo htmlspecialchars($aplicacion['puesto']); ?></h3>
                <p><strong>Sueldo:</strong> $<?php echo number_format($aplicacion['sueldo'], 2); ?></p>
                <p><strong>Industria:</strong> <?php echo htmlspecialchars($aplicacion['industria']); ?></p>
                <p><strong>Descripción:</strong> <?php echo htmlspecialchars($aplicacion['descripcion']); ?></p>
                <p><strong>Estado de la Aplicación:</strong> 
                    <span class="status 
                        <?php 
                            echo $aplicacion['estadoAplicacion'] === 'aceptado' ? 'accepted' : 
                                 ($aplicacion['estadoAplicacion'] === 'rechazado' ? 'rejected' : 'pending'); 
                        ?>">
                        <?php echo ucfirst($aplicacion['estadoAplicacion']); ?>
                    </span>
                </p>
                <p><strong>Ubicación:</strong> 
                    <?php 
                        $ubicacion = htmlspecialchars($aplicacion['colonia'] . ', ' . $aplicacion['municipio'] . ', ' . $aplicacion['estadoUbicacion']);
                        echo $ubicacion;
                    ?>
                </p>
                <!-- iFrame de Google Maps -->
                <iframe 
                    src="https://www.google.com/maps?q=<?php echo urlencode($ubicacion); ?>&output=embed" 
                    allowfullscreen>
                </iframe>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-applications">No has aplicado a ninguna oferta aún.</p>
    <?php endif; ?>
</div>

</body>
</html>