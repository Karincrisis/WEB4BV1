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

// Función para obtener las ofertas según un filtro
function obtenerOfertas($filtro, $candidato_id) {
    $conexion = conectar();
    $stmt = null;

    switch ($filtro) {
        case 'industria':
            $stmt = $conexion->prepare("SELECT * FROM ofertas WHERE industria = (SELECT industria FROM candidatos WHERE idCandidato = ?)");
            break;
        case 'salario':
            $stmt = $conexion->prepare("SELECT * FROM ofertas WHERE sueldo >= (SELECT aspiracionSalarial FROM candidatos WHERE idCandidato = ?)");
            break;
        case 'proximidad':
            $stmt = $conexion->prepare("SELECT o.* FROM ofertas o JOIN domicilios d ON o.idDomicilio = d.idDomicilio WHERE d.estado = (SELECT estado FROM domicilios WHERE idDomicilio = (SELECT idDomicilio FROM candidatos WHERE idCandidato = ?))");
            break;
        default:
            $stmt = $conexion->prepare("SELECT * FROM ofertas");
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
?>