<?php
    include('../plantillas/cabecera.php');
?>
<main>

<div class="contenedorFormulario">
<!-- Formulario de Registro -->
<form action="" method="POST">
    <h2>Registro de Usuario</h2>
    
    <!-- Datos comunes -->
    <label for="nombreUsuario">Nombre de Usuario:</label>
    <input type="text" name="nombreUsuario" required><br>

    <label for="contrasenia">Contraseña:</label>
    <input type="password" name="contrasenia" required><br>

    <label for="contrasenia2">Confirma la contraseña:</label>
    <input type="password" name="contrasenia2" required><br>

    <label for="tipoUsuario">Tipo de Usuario:</label>
    <select name="tipoUsuario" onchange="mostrarCamposEspecificos()" required>
        <option value="empleador">Empleador</option>
        <option value="candidato">Candidato</option>
    </select><br>

    <!-- Campos específicos para empleador -->
    <div id="empleadorCampos" style="display:none;">
        <h3>Datos de la Empresa</h3>
        
        <label for="nombreEmpresa">Nombre de la Empresa:</label>
        <input type="text" id="nombreEmpresa" name="nombreEmpresa"><br>

        <label for="rfc">RFC:</label>
        <input type="text" name="rfc"><br>

        <label for="descripcion">Descripción de la Empresa:</label>
        <textarea id="descripcion" name="descripcion"></textarea><br>

        <label for="tamano">Tamaño de la Empresa:</label>
        <select id="tamano" name="tamano">
            <option value="pequena">Pequeña</option>
            <option value="mediana">Mediana</option>
            <option value="grande">Grande</option>
        </select><br>
    </div>

    <!-- Campos específicos para candidato -->
    <div id="candidatoCampos" style="display:none;">
        <h3>Datos del Candidato</h3>
        
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre"><br>

        <label for="apellidoPaterno">Apellido Paterno:</label>
        <input type="text" id="apellidoPaterno" name="apellidoPaterno"><br>

        <label for="apellidoMaterno">Apellido Materno:</label>
        <input type="text" id="apellidoMaterno" name="apellidoMaterno"><br>

        <label for="fechaNacimiento">Fecha de Nacimiento:</label>
        <input type="date" id="fechaNacimiento" name="fechaNacimiento"><br>

        <label for="escolaridad">Escolaridad:</label>
        <input type="text" id="escolaridad" name="escolaridad"><br>

        <label for="aspiracionSalarial">Aspiración Salarial:</label>
        <input type="number" id="aspiracionSalarial" name="aspiracionSalarial"><br>
    </div>

    <!-- Datos del domicilio -->
    <h3>Datos del Domicilio</h3>
    <label for="estado">Estado:</label>
    <input type="text" id="estado" name="estado" required><br>

    <label for="municipio">Municipio:</label>
    <input type="text" id="municipio" name="municipio" required><br>

    <label for="colonia">Colonia:</label>
    <input type="text" id="colonia" name="colonia" required><br>

    <label for="calle">Calle:</label>
    <input type="text" id="calle" name="calle" required><br>

    <label for="numeroExterior">Número Exterior:</label>
    <input type="text" id="numeroExterior" name="numeroExterior"><br>

    <label for="numeroInterior">Número Interior:</label>
    <input type="text" id="numeroInterior" name="numeroInterior"><br>

    <button type="submit">Registrar</button>
</form>
</div>
<script>
    // Función para mostrar los campos específicos dependiendo del tipo de usuario
    function mostrarCamposEspecificos() {
        var tipoUsuario = document.querySelector('[name="tipoUsuario"]').value;
        if (tipoUsuario === 'empleador') {
            document.getElementById('empleadorCampos').style.display = 'block';
            document.getElementById('candidatoCampos').style.display = 'none';
        } else {
            document.getElementById('empleadorCampos').style.display = 'none';
            document.getElementById('candidatoCampos').style.display = 'block';
        }
    }

    // Validación de que las contraseñas coincidan antes de enviar el formulario
    document.querySelector('form').addEventListener('submit', function(event) {
        var contrasenia = document.querySelector('[name="contrasenia"]').value;
        var contrasenia2 = document.querySelector('[name="contrasenia2"]').value;
        if (contrasenia !== contrasenia2) {
            alert('Las contraseñas no coinciden.');
            event.preventDefault();  // Evita el envío del formulario
        }
    });
</script>
</main>
<?php
    include('../plantillas/pie.php');
?>