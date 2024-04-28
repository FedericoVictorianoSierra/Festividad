<?php
session_start(); // Iniciar sesión

if (!isset($_SESSION['idusuario'])) { // Si no hay una sesión iniciada
    // Redirigir al usuario a la página de inicio de sesión
    header("Location: sesion.php");
    exit(); // Salir del script
}

// El ID del usuario está disponible en $_SESSION['idusuario']
$idusuario = $_SESSION['idusuario'];

require("php_con/db.php"); // Incluir el archivo de conexión a la base de datos
$conexion = conexion(); // Establecer la conexión a la base de datos

// Obtener el ID del artículo desde la URL
$id_articulo = isset($_GET['id']) ? $_GET['id'] : 0;

if ($id_articulo === null || !is_numeric($id_articulo)) {
    echo "ID de artículo no válido";
    exit;
}

// Consulta SQL para obtener la información del artículo
$sql = "SELECT a.nombre, a.descripcion, a.precio_venta, e.existencia, u.nombre AS nombre_vendedor, img.nuevaImagen AS imagen
        FROM articulo AS a
        INNER JOIN usuario AS u ON a.idprovedor = u.idusuario
        LEFT JOIN existencia AS e ON a.idarticulo = e.id_articulo
        LEFT JOIN img ON a.id_imagen = img.id_imagen
        WHERE a.idarticulo = $id_articulo";
?>

<?php include_once "encabezado.php" ?>

<!-- Page Header Start -->
<div class="container-fluid page-header mb-4 position-relative overlay-bottom">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
        <?php
        $resultado = mysqli_query($conexion, $sql);

        // Verificar si se encontró el artículo
        if (mysqli_num_rows($resultado) > 0) {
            // Obtener los datos del artículo
            $datos_articulo = mysqli_fetch_assoc($resultado);
        ?>

            <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase"><?php echo $datos_articulo['nombre']; ?></h1>
    </div>
</div>
</div>
<!-- Page Header End -->


<div class="container-fluid pt-5">
    <div class="container">
        <p><?php echo $datos_articulo['descripcion']; ?></p>
        <p>Precio: $<?php echo number_format($datos_articulo['precio_venta'], 2); ?></p>
        <p>Existencias: <?php echo $datos_articulo['existencia']; ?></p>
        <p>Vendedor: <?php echo $datos_articulo['nombre_vendedor']; ?></p>
        <!-- Mostrar la imagen del artículo -->
        <img src="<?php echo $datos_articulo['imagen']; ?>" alt="<?php echo $datos_articulo['nombre']; ?>" class="img-fluid mb-3">
    <?php
        } else {
            echo "El artículo no existe";
        }

        
    ?>
    </div>
</div>
<?php include_once "pie.php" ?>