<?php
session_start(); // iniciar sesión

// Destruir sesión
session_destroy();

// Redireccionar a la página de inicio
header("Location: sesion.php");
exit;
