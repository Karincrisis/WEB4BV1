

<?php
//Abner Ismael Gálvez Hernández
include('../plantillas/cabecera.php');
?>

<!--Formulario para datos de inicio de sesión-->
<main>
	<div class="contenedorFormulario">
        <h3>Bienvenido, ingresa tus datos de inicio de sesión</h3>

		<form class="formulario" method="POST" action="../controladores/controladorInicioSesion.php">

		<label>Nombre de usuario o correo</label>
		<input type="text" name="nombreUsuario" required>	

		<label>Contraseña</label>
		<input type="password" name="contrasenia" required>
		<button type="submit">Iniciar Sesión</button>
		</form>
	</div>
</main>
<?php
include('../plantillas/pie.php');
?>
