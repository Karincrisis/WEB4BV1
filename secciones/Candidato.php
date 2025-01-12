<?php
//Abner Ismael Gálvez Hernández
include('../plantillas/cabecera.php');
?>

<?php
session_start();

// Verificar si el usuario está logueado como candidato
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipoUsuario'] !== 'candidato') {
    header("Location: ../secciones/error.php?error=" . urlencode("Acceso no autorizado."));
    exit();
}

$usuario_id = $_SESSION['usuario_id']; // Usar idUsuario para buscar el candidato

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

// Obtener el idCandidato basado en idUsuario
$stmt = $pdo->prepare("SELECT idCandidato FROM candidatos WHERE idUsuario = :idUsuario");
$stmt->execute(['idUsuario' => $usuario_id]);
$candidato = $stmt->fetch();

if (!$candidato) {
    echo "Candidato no encontrado.";
    exit;
}

$candidato_id = $candidato['idCandidato']; // Ahora se usa idCandidato
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        #main-container {
            display: flex;
            flex: 1;
            background-color: #f5f5f5;
        }

        #sidebar {
            width: 250px;
            background-color: #fff;
            border-right: 1px solid #ccc;
            padding: 10px;
            box-sizing: border-box;
        }

        #sidebar button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #ff9966;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-align: left;
        }

        #sidebar button:hover {
            background-color: #ff704d;
        }

        #filter-container {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            padding: 10px;
            box-sizing: border-box;
        }

        #filter-container select {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #content {
            flex-grow: 1;
            height: 80vh;
            border: 1px solid #ccc;
            overflow: hidden;
        }

        #iframe-content {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>
    <div id="main-container">
        <!-- Sidebar con los botones -->
        <div id="sidebar">
            <button id="ofertas-btn">Ofertas</button>
            <button id="aplicaciones-btn">Aplicaciones</button>
            <button id="datos-btn">Datos</button>
        </div>

        <!-- Contenedor principal -->
        <div id="filter-container">
            <!-- Filtro -->
            <form id="filter-form">
                <select id="filter-select" name="filtro">
                    <!-- Las opciones se llenarán con JavaScript -->
                </select>
            </form>

            <!-- Contenedor del iframe -->
            <div id="content">
                <iframe id="iframe-content" src="about:blank"></iframe>
            </div>
        </div>
    </div>

    <script>
        // Referencias a los elementos
        const iframe = document.getElementById("iframe-content");
        const filterSelect = document.getElementById("filter-select");
        const ofertasBtn = document.getElementById("ofertas-btn");
        const aplicacionesBtn = document.getElementById("aplicaciones-btn");
        const datosBtn = document.getElementById("datos-btn");

        // Eventos para cargar las páginas correspondientes en el iframe
        ofertasBtn.addEventListener("click", () => {
            iframe.src = "../secciones/ofertas.php";
            updateFilterOptions([" ", "industria", "salario", "proximidad"]);
        });

        aplicacionesBtn.addEventListener("click", () => {
            iframe.src = "../secciones/aplicaciones.php";
            updateFilterOptions([]); // Pasamos un array vacío para que no haya opciones en el filtro
        });

        datosBtn.addEventListener("click", () => {
            iframe.src = "../secciones/datos_candidato.php";
            updateFilterOptions([]); // Pasamos un array vacío para que no haya opciones en el filtro
        });

        // Función para actualizar las opciones del filtro
        function updateFilterOptions(options) {
            filterSelect.innerHTML = ""; // Vaciar las opciones actuales
            
            // Si hay opciones, las agregamos al filtro
            if (options.length > 0) {
                options.forEach(option => {
                    const opt = document.createElement("option");
                    opt.value = option.toLowerCase();
                    opt.textContent = capitalize(option);
                    filterSelect.appendChild(opt);
                });
            } else {
                // Si no hay opciones, mostramos un mensaje vacío o no hacemos nada
                const opt = document.createElement("option");
                opt.disabled = true;
                opt.textContent = "Sin filtros disponibles";
                filterSelect.appendChild(opt);
            }
        }

        // Función para capitalizar la primera letra de las palabras
        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        // Función para manejar el cambio en el select
        filterSelect.addEventListener("change", () => {
            const selectedOption = filterSelect.value;
            switch (selectedOption) {
                case "industria":
                    iframe.src = "../secciones/ofertas.php?filtro=industria";
                    break;
                case "salario":
                    iframe.src = "../secciones/ofertas.php?filtro=salario";
                    break;
                case "proximidad":
                    iframe.src = "../secciones/ofertas.php?filtro=proximidad";
                    break;
                default:
                    iframe.src = "about:blank"; // O cualquier página que quieras mostrar por defecto
                    break;
            }
        });
    </script>
</body>
</html>
<?php
include('../plantillas/pie.php');
?>