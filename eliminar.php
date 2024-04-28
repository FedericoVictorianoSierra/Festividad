<?php
session_start(); // Iniciar sesión

if (!isset($_SESSION['idusuario'])) {
    // Redirigir al usuario a la página de inicio de sesión si no hay una sesión iniciada
    header("Location: sesion.php");
    exit();
}

// El ID del usuario está disponible en $_SESSION['idusuario']
$idusuario = $_SESSION['idusuario'];

require("php_con/db.php"); // Incluir el archivo que contiene la función de conexión 
$conexion = conexion(); // Crear la conexión a la base de datos

$id = $_GET["id"];

// Eliminar registros relacionados en la tabla 'venta'
$query_delete_venta = "DELETE FROM venta WHERE idcarrito IN (SELECT idcarrito FROM carrito WHERE idarticulo = '$id')";
mysqli_query($conexion, $query_delete_venta);

// Eliminar registros relacionados en la tabla 'carrito'
$query_delete_carrito = "DELETE FROM carrito WHERE idarticulo = '$id'";
mysqli_query($conexion, $query_delete_carrito);

// Eliminar el artículo de la tabla 'articulo'
$query_delete_articulo = "DELETE FROM articulo WHERE idarticulo = '$id'";


if (mysqli_query($conexion, $query_delete_articulo)) {
    $textoModal = "El artículo ha sido eliminado correctamente.";
    $mostrarModal = true;
    $nombreArchivo = "articulos.php";
} else {
    $textoModal = "Error al eliminar el artículo: " . mysqli_error($conexion);
    $mostrarModal = true;
    $nombreArchivo = "articulos.php";
}
?>

<?php include_once "encabezado.php" ?>

<?php include_once "ventana.php" ?>