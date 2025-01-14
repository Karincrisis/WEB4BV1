<?php
//obtiene la sesion para destruirla y redireccionar a la pagina web de inicio de sesión
//inicio de la sesión
session_start();
//destrucción dd la sesión 
session_destroy();
//redireccionamiento al inicio de la web
header("location: ../index.html")
?>