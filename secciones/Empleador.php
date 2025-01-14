<?php
// Abner Ismael Gálvez Hernández
// Diana Karina Zarate Sanchez

session_start();
if(empty($_SESSION['usuario_id'] or ($_SESSION['tipoUsuario']!='empleador'))){
    if($_SESSION['nombreUsuario'] != 'empleador'){
        session_destroy();
    }
    header('Location: ./error.php');
}
include('../controladores/controladores_vistas.php'); // Incluir el archivo de controladores
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./imagenes/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/estilo.css">
    <title>Empleador</title>
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
            transition: background-color 0.3s;
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
            font-size: 16px;
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
<header>
        <a href="https://www.tantsevat.com"><img src="../imagenes/logo.png" alt="tantsevat" title="tantsevat.com" class="logo"></a>
        <div class="titulo"><h2>Encuenta prospectos de colaboradores.</h2></div>
        <form action="../controladores/controlador_cerrar.php">
            <button type="submit">Cerrar sesión</button>
        </form>
</header>
<main>
    <div id="main-container">
        <div id="sidebar">
            <button id="solicitudes-btn" aria-label="Ver solicitudes de candidatos">Solicitudes</button>
            <button id="publicar-btn" aria-label="Publicar una nueva oferta de empleo">Ofertas publicadas</button>
            <button id="datos-btn" aria-label="Ver datos del empleador">Datos</button>
        </div>

        <div id="filter-container">
            <form id="filter-form" role="form">
                <select id="filter-select" name="filtro" aria-label="Filtrar solicitudes">
                    <!-- Las opciones se llenarán con JavaScript -->
                </select>
            </form>

            <div id="content">
                <iframe id="iframe-content" src="about:blank" title="Vista de solicitudes y ofertas"></iframe>
            </div>
        </div>
    </div>

    <script>
        const iframe = document.getElementById("iframe-content");
        const filterSelect = document.getElementById("filter-select");
        const solicitudesBtn = document.getElementById("solicitudes-btn");
        const publicarBtn = document.getElementById("publicar-btn");
        const datosBtn = document.getElementById("datos-btn");

        solicitudesBtn.addEventListener("click", () => {
            iframe.src = "../secciones/solicitudes.php";
            updateFilterOptions([" ", "industria", "salario", "proximidad"]);
        });

        publicarBtn.addEventListener("click", () => {
            iframe.src = "../secciones/publicaciones.php";
            updateFilterOptions([]);
        });

        datosBtn.addEventListener("click", () => {
            iframe.src = "../secciones/datos_empleador.php";
            updateFilterOptions([]);
        });

        function updateFilterOptions(options) {
            filterSelect.innerHTML = "";
            if (options.length > 0) {
                options.forEach(option => {
                    const opt = document.createElement("option");
                    opt.value = option.toLowerCase();
                    opt.textContent = capitalize(option);
                    filterSelect.appendChild(opt);
                });
            } else {
                const opt = document.createElement("option");
                opt.disabled = true;
                opt.textContent = "Sin filtros disponibles";
                filterSelect.appendChild(opt);
            }
        }

        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        filterSelect.addEventListener("change", () => {
            const selectedOption = filterSelect.value;
            switch (selectedOption) {
                case "industria":
                    iframe.src = "../secciones/solicitudes.php?filtro=industria";
                    break;
                case "salario":
                    iframe.src = "../secciones/solicitudes.php?filtro=salario";
                    break;
                case "proximidad":
                    iframe.src = "../secciones/solicitudes.php?filtro=proximidad";
                    break;
                default:
                    iframe.src = "about:blank";
                    break;
            }
        });
    </script>
</main>
<?php
include('../plantillas/pie.php'); // Incluir plantilla para el footer
?>
