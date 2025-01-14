<?php
session_start();
include('../controladores/controladores_vistas.php');

verificarSesionEmpleador();
verificarSesion();

$idEmpleador = obtenerIdEmpleadorDesdeSesion();
$solicitudes = obtenerSolicitudes($idEmpleador);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        button {
            padding: 5px 10px;
            margin: 0 5px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .accept {
            background-color: #4CAF50;
            color: white;
        }

        .reject {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <h1>Solicitudes Recibidas</h1>
    <table>
        <thead>
            <tr>
                <th>Candidato</th>
                <th>Puesto Solicitado</th>
                <th>Compatibilidad (%)</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($solicitudes)) : ?>
                <tr>
                    <td colspan="5">No hay solicitudes pendientes.</td>
                </tr>
            <?php else : ?>
                <?php foreach ($solicitudes as $solicitud) : ?>
                    <?php
                    $compatibilidad = calcularCompatibilidad($solicitud, $solicitud);
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($solicitud['nombre'] . ' ' . $solicitud['apellidoPaterno'] . ' ' . $solicitud['apellidoMaterno']); ?></td>
                        <td><?= htmlspecialchars($solicitud['puesto']); ?></td>
                        <td><?= $compatibilidad; ?>%</td>
                        <td><?= htmlspecialchars($solicitud['estadoAplicacion']); ?></td>
                        <td>
                            <?php if ($solicitud['estadoAplicacion'] === 'pendiente') : ?>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="idAplicacion" value="<?= $solicitud['idAplicacion']; ?>">
                                    <button type="submit" name="accion" value="aceptar" class="accept">Aceptar</button>
                                    <button type="submit" name="accion" value="rechazar" class="reject">Rechazar</button>
                                </form>
                            <?php else : ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<?php
// Manejo de acciones (Aceptar/Rechazar)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'], $_POST['idAplicacion'])) {
    $accion = $_POST['accion'];
    $idAplicacion = (int)$_POST['idAplicacion'];
    $nuevoEstado = ($accion === 'aceptar') ? 'aceptado' : 'rechazado';

    if (actualizarEstadoSolicitud($idAplicacion, $nuevoEstado)) {
        echo "<script>
            alert('Solicitud actualizada correctamente.');
            window.location.href = '" . $_SERVER['PHP_SELF'] . "';
        </script>";
    } else {
        echo "<script>alert('Hubo un error al actualizar la solicitud.');</script>";
    }
    
}
?>
