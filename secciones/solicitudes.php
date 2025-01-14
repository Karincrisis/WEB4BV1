<?php
session_start();
include('../controladores/controladores_vistas.php');

verificarSesionEmpleador();
verificarSesion();

$idEmpleador = obtenerIdEmpleadorDesdeSesion();
$solicitudes = obtenerSolicitudes($idEmpleador);

?>
<main>
    <h1>Solicitudes Recibidas</h1>
    <table>
        <thead>
            <tr>
                <th>Candidato</th>
                <th>Puesto Solicitado</th>
                <th>Compatibilidad (%)</th>
                <th>Escolaridad</th>
                <th>Industria</th>
                <th>Aspiración Salarial</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($solicitudes)) : ?>
                <tr>
                    <td colspan="8">No hay solicitudes pendientes.</td>
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
                        <td><?= htmlspecialchars($solicitud['escolaridad']); ?></td>
                        <td><?= htmlspecialchars($solicitud['industriaOferta']); ?></td>
                        <td><?= htmlspecialchars($solicitud['aspiracionSalarial']); ?></td>
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
</main>

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
