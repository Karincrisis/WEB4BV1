<?php
session_start();
include('../controladores/controladores_vistas.php'); 

// Verificar si el empleador está logueado
verificarSesionEmpleador();
$empleadorId = $_SESSION['usuario_id'];

// Verificar si el formulario fue enviado

publicarOfertaFormulario();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar Oferta</title>
</head>
<body>
    <h2>Publicar Nueva Oferta de Trabajo</h2>
    <form action="publicar.php" method="POST">
        <label for="puesto">Puesto:</label>
        <input type="text" id="puesto" name="puesto" required><br><br>

        <label for="sueldo">Sueldo:</label>
        <input type="number" id="sueldo" name="sueldo" step="0.01" required><br><br>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea><br><br>

        <label for="cantidadVacantes">Cantidad de Vacantes:</label>
        <input type="number" id="cantidadVacantes" name="cantidadVacantes" required><br><br>

        <label for="industria">Industria:</label>
        <input type="text" id="industria" name="industria"><br><br>


        <label for="duracionContrato">Duración del Contrato:</label>
        <select id="duracionContrato" name="duracionContrato" required>
            <option value="temporal">Temporal</option>
            <option value="indefinido">Indefinido</option>
        </select><br><br>

        <label for="horario">Horario:</label>
        <input type="text" id="horario" name="horario" required><br><br>

        <label for="fechaExpiracion">Fecha de Expiración:</label>
        <input type="date" id="fechaExpiracion" name="fechaExpiracion" required><br><br>

        <!-- Mostrar siempre el formulario de domicilio nuevo -->
        <h3>Datos del Domicilio</h3>
        <label for="estado">Estado:</label>
        <input type="text" id="estado" name="estado" required><br><br>

        <label for="municipio">Municipio:</label>
        <input type="text" id="municipio" name="municipio" required><br><br>

        <label for="colonia">Colonia:</label>
        <input type="text" id="colonia" name="colonia" required><br><br>

        <label for="calle">Calle:</label>
        <input type="text" id="calle" name="calle" required><br><br>

        <label for="numeroExterior">Número Exterior:</label>
        <input type="text" id="numeroExterior" name="numeroExterior"><br><br>

        <label for="numeroInterior">Número Interior:</label>
        <input type="text" id="numeroInterior" name="numeroInterior"><br><br>

        <button type="submit" name="publicarOferta">Publicar Oferta</button>
        <a href="../secciones/publicaciones.php"><button type="button">Volver</button></a>
    </form>
</body>
</html>
