<?php 
session_start(); // Iniciar sesión 
include 'php_con/db.php'; // Incluir el archivo de conexión a la base de datos
$conexion = conexion(); // Establecer la conexión a la base de datos

if (!isset($_SESSION['idusuario'])) { // Si no hay una sesión iniciada
    // Redirigir al usuario a la página de inicio de sesión
    header("Location: sesion.php");
    exit(); // Salir del script
}

// El ID del usuario está disponible en $_SESSION['idusuario']
$idusuario = $_SESSION['idusuario'];

// Obtener el valor de idrol del usuario actual desde la tabla usuario
$query_usuario = "SELECT idrol FROM usuario WHERE idusuario = $idusuario";
$resultado_usuario = mysqli_query($conexion, $query_usuario);
$fila_usuario = mysqli_fetch_assoc($resultado_usuario);
$idrol = $fila_usuario['idrol'];
