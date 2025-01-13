<?php
// Abner Ismael Gálvez Hernández
// Diana Karina Zarate Sanchez
include('../controladores/conexion.php');

// Mostrar errores de PHP para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Función para verificar si el usuario tiene sesión iniciada
function verificarSesion() {
    if (!isset($_SESSION['usuario_id'])) {
        echo "No hay un usuario con sesión iniciada.";
        exit;
    }
    return $_SESSION['usuario_id'];
}

// Función para verificar si el usuario está logueado como candidato
function verificarSesionCandidato() {
    if (!isset($_SESSION['usuario_id']) || $_SESSION['tipoUsuario'] !== 'candidato') {
        header("Location: ../secciones/error.php?error=" . urlencode("Acceso no autorizado."));
        exit();
    }
}

// Función para verificar si el usuario está logueado como empleador
function verificarSesionEmpleador() {
    if (!isset($_SESSION['usuario_id']) || $_SESSION['tipoUsuario'] !== 'empleador') {
        header("Location: ../secciones/error.php?error=" . urlencode("Acceso no autorizado."));
        exit();
    }
}

// Función para obtener el id del candidato
function obtenerIdCandidato($usuario_id) {
    $conexion = conectar();
    $stmt = $conexion->prepare("SELECT idCandidato FROM candidatos WHERE idUsuario = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $candidato = $resultado->fetch_assoc();
    cerrarConexion($conexion);
    return $candidato ? $candidato['idCandidato'] : null;
}

// Función para obtener el idCandidato basado en el idUsuario
function obtenerIdCandidatoDesdeSesion() {
    $usuario_id = verificarSesion();  // Verifica sesión y obtiene usuario_id
    $candidato_id = obtenerIdCandidato($usuario_id);  // Llama a la función para obtener el idCandidato
    if (!$candidato_id) {
        echo "Candidato no encontrado.";
        exit;
    }
    return $candidato_id;
}

// Función para obtener el idEmpleador basado en el idUsuario
function obtenerIdEmpleadorDesdeSesion() {
    $usuario_id = verificarSesion();  // Verifica sesión y obtiene usuario_id
    $empleador_id = obtenerIdEmpleador($usuario_id);  // Llama a la función para obtener el idEmpleador
    if (!$empleador_id) {
        echo "Empleador no encontrado.";
        exit;
    }
    return $empleador_id;
}

// Función auxiliar para obtener el idEmpleador basado en el idUsuario
function obtenerIdEmpleador($usuario_id) {
    $conexion = conectar();
    $stmt = $conexion->prepare("SELECT idEmpleador FROM empleadores WHERE idUsuario = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $empleador = $resultado->fetch_assoc();
    cerrarConexion($conexion);
    return $empleador ? $empleador['idEmpleador'] : null;
}


// Función para obtener y mostrar las aplicaciones de un candidato
function mostrarAplicaciones($candidato_id) {
    $aplicaciones = obtenerAplicaciones($candidato_id); // Llama a la función para obtener aplicaciones
    $html = '';
    if (!empty($aplicaciones)) {
        foreach ($aplicaciones as $aplicacion) {
            $ubicacion = htmlspecialchars($aplicacion['colonia'] . ', ' . $aplicacion['municipio'] . ', ' . $aplicacion['estadoUbicacion']);
            $estadoAplicacionClass = $aplicacion['estadoAplicacion'] === 'aceptado' ? 'accepted' : 
                                    ($aplicacion['estadoAplicacion'] === 'rechazado' ? 'rejected' : 'pending');
            $estadoAplicacionTexto = ucfirst($aplicacion['estadoAplicacion']);
            $html .= "
                <div class='application'>
                    <h3>" . htmlspecialchars($aplicacion['puesto']) . "</h3>
                    <p><strong>Sueldo:</strong> $" . number_format($aplicacion['sueldo'], 2) . "</p>
                    <p><strong>Industria:</strong> " . htmlspecialchars($aplicacion['industria']) . "</p>
                    <p><strong>Descripción:</strong> " . htmlspecialchars($aplicacion['descripcion']) . "</p>
                    <p><strong>Estado de la Aplicación:</strong> 
                        <span class='status $estadoAplicacionClass'>
                            $estadoAplicacionTexto
                        </span>
                    </p>
                    <p><strong>Ubicación:</strong> $ubicacion</p>
                    <iframe src='https://www.google.com/maps?q=" . urlencode($ubicacion) . "&output=embed' allowfullscreen></iframe>
                </div>
            ";
        }
    } else {
        $html .= "<p class='no-applications'>No has aplicado a ninguna oferta aún.</p>";
    }
    return $html;
}

// Función para obtener las aplicaciones de un candidato
function obtenerAplicaciones($candidato_id) {
    $conexion = conectar();
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
        WHERE a.idCandidato = ?
    ";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $candidato_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $aplicaciones = $resultado->fetch_all(MYSQLI_ASSOC);
    cerrarConexion($conexion);
    return $aplicaciones;
}

// Función para obtener los datos del candidato
function obtenerDatosCandidato($candidato_id) {
    $conexion = conectar();
    $stmt = $conexion->prepare("SELECT * FROM candidatos WHERE idCandidato = ?");
    $stmt->bind_param("i", $candidato_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $datos = $resultado->fetch_assoc();
    cerrarConexion($conexion);
    return $datos;
}

// Función para obtener los datos de las ofertas
function obtenerDatosOfertas($empleador_id) {
    $conexion = conectar();
    $stmt = $conexion->prepare("SELECT * FROM ofertas WHERE idEmpleador = ?");
    $stmt->bind_param("i", $empleador_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $datos = $resultado->fetch_assoc();
    cerrarConexion($conexion);
    return $datos;
}

// Función para obtener las ofertas según un filtro
function obtenerOfertas($filtro, $candidato_id) {
    $conexion = conectar();
    $stmt = null;

    switch ($filtro) {
        case 'industria':
            $stmt = $conexion->prepare("SELECT * FROM ofertas WHERE industria = (SELECT industria FROM candidatos WHERE idCandidato = ?) && estado = 'visible'");
            break;
        case 'salario':
            $stmt = $conexion->prepare("SELECT * FROM ofertas WHERE sueldo >= (SELECT aspiracionSalarial FROM candidatos WHERE idCandidato = ?) && estado = 'visible'");
            break;
        case 'proximidad':
            $stmt = $conexion->prepare("SELECT o.* 
            FROM ofertas o 
            JOIN domicilios d ON o.idDomicilio = d.idDomicilio 
            WHERE o.estado = 'visible' 
            AND d.estado = (SELECT estado FROM domicilios WHERE idDomicilio = (SELECT idDomicilio FROM candidatos WHERE idCandidato = ?))");
            break;
        default:
            $stmt = $conexion->prepare("SELECT * FROM ofertas WHERE estado = 'visible'");
            break;
    }

    if ($filtro !== 'default') {
        $stmt->bind_param("i", $candidato_id);
    }
    
    $stmt->execute();
    $resultado = $stmt->get_result();
    $ofertas = $resultado->fetch_all(MYSQLI_ASSOC);
    cerrarConexion($conexion);
    return $ofertas;
}

// Función para obtener los datos del candidato
function obtenerDatosCandidatoDesdeId($usuario_id) {
    $conexion = conectar();
    $stmt = $conexion->prepare("SELECT * FROM candidatos WHERE idUsuario = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $datosCandidato = $resultado->fetch_assoc();
    cerrarConexion($conexion);
    return $datosCandidato ? $datosCandidato : null;
}

// Función para obtener los datos del empleador
function obtenerDatosEmpleadorDesdeId($usuario_id) {
    $conexion = conectar();
    $stmt = $conexion->prepare("SELECT * FROM empleadores WHERE idUsuario = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $datosEmpleador = $resultado->fetch_assoc();
    cerrarConexion($conexion);
    return $datosEmpleador ? $datosEmpleador : null;
}


// Función para obtener los datos del domicilio del candidato
function obtenerDomicilioCandidato($idDomicilio) {
    $conexion = conectar();
    $stmt = $conexion->prepare("SELECT * FROM domicilios WHERE idDomicilio = ?");
    $stmt->bind_param("i", $idDomicilio);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $domicilio = $resultado->fetch_assoc();
    cerrarConexion($conexion);
    return $domicilio ? $domicilio : null;
}

// Función para obtener los datos del domicilio del empleador
function obtenerDomicilioEmpleador($idDomicilio) {
    $conexion = conectar();
    $stmt = $conexion->prepare("SELECT * FROM domicilios WHERE idDomicilio = ?");
    $stmt->bind_param("i", $idDomicilio);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $domicilio = $resultado->fetch_assoc();
    cerrarConexion($conexion);
    return $domicilio ? $domicilio : null;
}


// Función para generar la URL de Google Maps con la dirección del domicilio
function generarUrlGoogleMaps($domicilioData) {
    // Asegúrate de que el domicilio tiene todos los datos necesarios
    if (isset($domicilioData['estado']) && isset($domicilioData['municipio']) && isset($domicilioData['colonia'])) {
        $direccion = urlencode($domicilioData['estado'] . ', ' . 
                               $domicilioData['municipio'] . ', ' . 
                               $domicilioData['colonia'] . ', ' . 
                               $domicilioData['calle'] . ' ' . 
                               $domicilioData['numeroExterior']);
        // Retorna la URL de Google Maps
        return "https://www.google.com/maps?q=$direccion&output=embed";
    }
    return '';  // Retorna vacío si no hay datos de domicilio completos
}

// Obtener ofertas por industria
function obtenerOfertasPorIndustria($industria) {
    $conexion = conectar();
    $stmt = $conexion->prepare("SELECT * FROM ofertas WHERE industria = ?");
    $stmt->bind_param("s", $industria);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $ofertas = $resultado->fetch_all(MYSQLI_ASSOC);
    cerrarConexion($conexion);
    return $ofertas;
}

// Obtener ofertas por salario
function obtenerOfertasPorSalario($salario) {
    $conexion = conectar();
    $stmt = $conexion->prepare("SELECT * FROM ofertas WHERE sueldo >= ?");
    $stmt->bind_param("d", $salario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $ofertas = $resultado->fetch_all(MYSQLI_ASSOC);
    cerrarConexion($conexion);
    return $ofertas;
}

// Obtener ofertas por proximidad
function obtenerOfertasPorProximidad($idDomicilio) {
    $conexion = conectar();
    // Obtener el estado del domicilio del candidato
    $stmt = $conexion->prepare("SELECT estado FROM domicilios WHERE idDomicilio = ?");
    $stmt->bind_param("i", $idDomicilio);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $domicilio = $resultado->fetch_assoc();

    if (!$domicilio) {
        cerrarConexion($conexion);
        return [];
    }

    $estado = $domicilio['estado'];

    // Buscar ofertas cuyo estado coincida
    $stmt = $conexion->prepare(
        "SELECT o.* FROM ofertas o JOIN domicilios d ON o.idDomicilio = d.idDomicilio WHERE d.estado = ?"
    );
    $stmt->bind_param("s", $estado);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $ofertas = $resultado->fetch_all(MYSQLI_ASSOC);
    cerrarConexion($conexion);
    return $ofertas;
}

// Obtener todas las ofertas
function obtenerTodasLasOfertas() {
    $conexion = conectar();
    $query = "SELECT * FROM ofertas";
    $resultado = $conexion->query($query);
    $ofertas = $resultado->fetch_all(MYSQLI_ASSOC);
    cerrarConexion($conexion);
    return $ofertas;
}

// Obtener datos del domicilio asociado a una oferta
function obtenerDatosDomicilio($idDomicilio) {
    $conexion = conectar();
    $stmt = $conexion->prepare("SELECT * FROM domicilios WHERE idDomicilio = ?");
    $stmt->bind_param("i", $idDomicilio);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $domicilio = $resultado->fetch_assoc();
    cerrarConexion($conexion);
    return $domicilio;
}

// Verificar si el candidato ya aplicó a una oferta
function verificarAplicacionOferta($candidato_id, $idOferta) {
    $conexion = conectar();
    $stmt = $conexion->prepare("SELECT * FROM aplicaciones WHERE idCandidato = ? AND idOferta = ?");
    $stmt->bind_param("ii", $candidato_id, $idOferta);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $aplicacion = $resultado->fetch_assoc();
    cerrarConexion($conexion);
    return $aplicacion !== null;
}

// Verificar si el candidato existe
function verificarCandidato($candidato_id) {
    if (!$candidato_id) {
        echo "Candidato no encontrado.";
        exit;
    }
}

// Función para obtener las ofertas publicadas por el empleador
function obtenerOfertasPorEmpleador($idEmpleador) {
    // Conectar a la base de datos
    $conexion = conectar();

    // Consultar las ofertas publicadas por el empleador
    $query = "SELECT * FROM ofertas WHERE idEmpleador = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, 'i', $idEmpleador);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    
    $ofertas = [];
    while ($row = mysqli_fetch_assoc($resultado)) {
        $ofertas[] = $row;
    }

    // Cerrar la conexión
    cerrarConexion($conexion);

    return $ofertas;
}

// Función para publicar una nueva oferta
function publicarOferta($titulo, $industria, $salario, $ubicacion, $idEmpleador) {
    // Conectar a la base de datos
    $conexion = conectar();
    
    if (!$conexion) {
        die('Error de conexión: ' . mysqli_connect_error());
    }

    // Insertar la nueva oferta en la tabla de ofertas
    $query = "INSERT INTO ofertas (titulo, industria, salario, ubicacion, idEmpleador) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, 'ssisi', $titulo, $industria, $salario, $ubicacion, $idEmpleador);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "Oferta publicada con éxito.";
    } else {
        echo "Error al publicar la oferta.";
    }

    // Cerrar la conexión
    cerrarConexion($conexion);
}

// Funcion para ver el estado de una oferta:

function cambiarEstadoOferta($idOferta, $nuevoEstado) {
    $conexion = conectar();
    $sql = "UPDATE ofertas SET estado = ? WHERE idOferta = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('si', $nuevoEstado, $idOferta);
    $stmt->execute();
    $stmt->close();
    cerrarConexion($conexion);
    return true;
}

// Insertar una nueva oferta
function publicarOfertaFP($puesto, $sueldo, $descripcion, $cantidadVacantes, $industria, $duracionContrato, $horario, $fechaExpiracion, $empleadorId, $idDomicilio) {
    // Conectar a la base de datos
    $conexion = conectar(); 

    // Consulta SQL para insertar la oferta
    $sql = "INSERT INTO ofertas (puesto, sueldo, descripcion, cantidadVacantes, industria, duracionContrato, horario, fechaExpiracion, idEmpleador, idDomicilio) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar la consulta
    $stmt = $conexion->prepare($sql);
    if ($stmt === false) {
        // Manejar error en preparación de la consulta
        die('Error al preparar la consulta: ' . $conexion->error);
    }

    // Vincular parámetros
    $stmt->bind_param('sdssssssss', $puesto, $sueldo, $descripcion, $cantidadVacantes, $industria, $duracionContrato, $horario, $fechaExpiracion, $empleadorId, $idDomicilio);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Oferta publicada con éxito.";
    } else {
        echo "Error al publicar la oferta: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
    cerrarConexion($conexion);
}

// Insertar un nuevo domicilio
function insertarDomicilio($estado, $municipio, $colonia, $calle, $numeroExterior, $numeroInterior) {
    $conexion = conectar();
    $sql = "INSERT INTO domicilios (estado, municipio, colonia, calle, numeroExterior, numeroInterior) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('ssssss', $estado, $municipio, $colonia, $calle, $numeroExterior, $numeroInterior);
    $stmt->execute();
    $idDomicilio = $stmt->insert_id;
    cerrarConexion($conexion);
    return $idDomicilio;
}

// Funcion para publicar las ofertas del formulario

function publicarOfertaFormulario() {
    if (isset($_POST['publicarOferta'])) {
        // Obtener los datos del formulario
        $puesto = $_POST['puesto'];
        $sueldo = $_POST['sueldo'];
        $descripcion = $_POST['descripcion'];
        $cantidadVacantes = $_POST['cantidadVacantes'];
        $industria = $_POST['industria'];
        $duracionContrato = $_POST['duracionContrato'];
        $horario = $_POST['horario'];
        $fechaExpiracion = $_POST['fechaExpiracion'];

        // Obtener los datos del domicilio
        $estado = $_POST['estado'];
        $municipio = $_POST['municipio'];
        $colonia = $_POST['colonia'];
        $calle = $_POST['calle'];
        $numeroExterior = $_POST['numeroExterior'];
        $numeroInterior = $_POST['numeroInterior'];

        // Obtener el idUsuario desde la sesión
        $idUsuario = $_SESSION['usuario_id'];  // Asumir que ya tienes esta sesión iniciada

        // Obtener el idEmpleador asociado al idUsuario
        $conexion = conectar();
        $sql = "SELECT idEmpleador FROM empleadores WHERE idUsuario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $empleador = $result->fetch_assoc();
        $idEmpleador = $empleador['idEmpleador'];  // Obtener el idEmpleador
        cerrarConexion($conexion);

        // Insertar nuevo domicilio y obtener el idDomicilio
        $idDomicilio = insertarDomicilio($estado, $municipio, $colonia, $calle, $numeroExterior, $numeroInterior);

        // Publicar la oferta
        publicarOfertaFP($puesto, $sueldo, $descripcion, $cantidadVacantes, $industria, $duracionContrato, $horario, $fechaExpiracion, $idEmpleador, $idDomicilio);

        // Redirigir a una página de confirmación o a publicaciones.php
        header('Location: ../secciones/publicaciones.php');
        exit();
    }
}
?>