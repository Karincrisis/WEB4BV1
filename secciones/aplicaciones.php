<?php
// Abner Ismael Gálvez Hernández
// Diana Karina Zarate Sanchez

session_start();
include('../controladores/controladores_vistas.php'); // Incluir el archivo de controladores

$candidato_id = obtenerIdCandidatoDesdeSesion();
$aplicacionesHTML = mostrarAplicaciones($candidato_id);
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

        .no-applications {
            text-align: center;
            color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Mis Aplicaciones</h2>
    <?php echo $aplicacionesHTML; ?> <!-- Imprimimos las aplicaciones obtenidas -->
</div>

</body>
</html>