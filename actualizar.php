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

// Consulta SQL para recuperar las categorías
$categorias_query = "SELECT idcategoria, nombre FROM categoria";

// Ejecutar la consulta
$resultado_categorias = mysqli_query($conexion, $categorias_query);

// Verificar si se encontraron categorías
if (!$resultado_categorias) {
    echo "Error al recuperar las categorías: " . mysqli_error($conexion);
    exit();
}

// Verificar si se ha enviado el formulario
if (isset($_POST['actualizar'])) {

    // Obtener los valores del formulario
    $idarticulo = $_POST['idarticulo'];
    $idcategoria = $_POST['idcategoria'];
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $precio_venta = $_POST['precio_venta'];
    $existencia = $_POST['existencia'];
    $descripcion = $_POST['descripcion'];
    $talla = $_POST['talla'];
    $imagen = $_POST['foto_'];

    $nombreimg = "imagen"; // Se obtiene el nombre de la imagen

    // Consulta SQL para recuperar los datos de articulo
    $articulo_query = "SELECT * FROM articulo WHERE idarticulo='$idarticulo'";
    // Ejecutar la consulta
    $resultado_articulo = mysqli_query($conexion, $articulo_query);
    // Obtener los datos de articulo
    $articulo = mysqli_fetch_assoc($resultado_articulo);

    /// Se actualiza la imagen del articulo en la tabla img
    $query_imagen = "UPDATE img SET nombre='$nombreimg',nuevaImagen='$imagen' WHERE Id_imagen='$articulo[id_imagen]'";
    mysqli_query($conexion, $query_imagen);
    $idimagen = mysqli_insert_id($conexion);

    // Actualizar los datos en la tabla articulo sin actualizar la imagen
    $sql = "UPDATE articulo SET idcategoria='$idcategoria', codigo='$codigo', nombre='$nombre', precio_venta='$precio_venta', existencia='$existencia', descripcion='$descripcion', talla='$talla' WHERE idarticulo='$idarticulo'";

    if (mysqli_query($conexion, $sql)) {
        $textoModal = "Los datos se han actualizado correctamente.";
        $mostrarModal = true;
        $nombreArchivo = "articulos.php";
    } else {
        $textoModal = "Error al actualizar los datos:" . mysqli_error($conexion);
        $mostrarModal = true;
        $nombreArchivo = "actualizar.php";
    }
}


?>


<?php include_once "encabezado.php" ?>
<!-- Page Header Start -->
<div class="container-fluid page-header mb-4 position-relative overlay-bottom">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
        <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">ACTUALIZAR PRODUCTO</h1>

    </div>
</div>
</div>
<!-- Page Header End -->
<?php

// Obtener el id del artículo a actualizar
$idarticulo = $_GET['id'];

// Consulta SQL para recuperar los datos del artículo
$articulo_query = "SELECT * FROM articulo WHERE idarticulo='$idarticulo'";

// Ejecutar la consulta
$resultado_articulo = mysqli_query($conexion, $articulo_query);

// Verificar si se encontró el artículo
if (!$resultado_articulo) {
    echo "Error al recuperar el artículo: " . mysqli_error($conexion);
    exit();
}

// Obtener los datos del artículo
$articulo = mysqli_fetch_assoc($resultado_articulo);

// Consulta SQL para recuperar las categorías
$categorias_query = "SELECT idcategoria, nombre FROM categoria";

// Ejecutar la consulta
$resultado_categorias = mysqli_query($conexion, $categorias_query);

// Verificar si se encontraron categorías
if (!$resultado_categorias) {
    echo "Error al recuperar las categorías: " . mysqli_error($conexion);
    exit();
}
?>
<div class="container-fluid py-5">
    <div class="container">
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="idarticulo" value="<?php echo $articulo['idarticulo']; ?>">
            <label for="idcategoria">Categoría:</label>
            <select class="btn-primary btn-lg px-4 me-sm-3" name="idcategoria" id="idcategoria">
                <?php while ($categoria = mysqli_fetch_assoc($resultado_categorias)) : ?>
                    <option value="<?php echo $categoria['idcategoria']; ?>" <?php if ($categoria['idcategoria'] == $articulo['idcategoria']) echo "selected"; ?>><?php echo $categoria['nombre']; ?></option>
                <?php endwhile; ?>
            </select>
            <br>
            <label for="codigo">Código:</label>
            <input class="px-4 me-sm-3" type="text" name="codigo" id="codigo" value="<?php echo $articulo['codigo']; ?>" required="required">
            <br>
            <label for="nombre">Nombre:</label>
            <input class="px-4 me-sm-3" type="text" name="nombre" id="nombre" value="<?php echo $articulo['nombre']; ?>" required="required">
            <br>
            <label for="precio_venta">Precio de venta:</label>
            <input class="px-4 me-sm-3" type="number" name="precio_venta" id="precio_venta" value="<?php echo $articulo['precio_venta']; ?>" required="required">
            <br>
            <label for="existencia">Existencia:</label>
            <input class="px-4 me-sm-3" type="number" name="existencia" id="existencia" value="<?php echo $articulo['existencia']; ?>" required="required">
            <br>
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" required="required"><?php echo $articulo['descripcion']; ?></textarea>
            <br>
            <label for="talla">Talla:</label>
            <input class="px-4 me-sm-3" type="text" name="talla" id="talla" value="<?php echo $articulo['talla']; ?>" required="required">

            <label for="foto_">URL Foto:</label>
            <!--<input class="px-4 me-sm-3" type="file" name="foto_" id="foto_">-->
            <?php
            // Consulta SQL para recuperar la URL de la imagen
            $query_imagen = "SELECT nuevaImagen FROM img WHERE Id_imagen='$articulo[id_imagen]'";
            // Ejecutar la consulta
            $resultado_imagen = mysqli_query($conexion, $query_imagen);
            // Verificar si se encontró la imagen
            if ($resultado_imagen && mysqli_num_rows($resultado_imagen) > 0) {
                // Obtener la URL de la imagen
                $imagen = mysqli_fetch_assoc($resultado_imagen);
                $url_imagen = $imagen['nuevaImagen'];
            } else {
                // Si no se encuentra la imagen, establecer una URL predeterminada o manejar el caso según tus necesidades
                $url_imagen = ""; // Puedes establecer una URL predeterminada o un mensaje de error
            } 
            
            // Mostrar la URL de la imagen en el campo de entrada de texto del formulario
            ?>

            <input class="px-4 me-sm-3" type="text" name="foto_" id="foto_" value="<?php echo $url_imagen; ?>" required="required">

            <br>
            <input class="btn btn-secondary font-weight-bold py-2 px-4 mt-2" type="submit" name="actualizar" value="Actualizar">
        </form>
    </div>
</div>
<?php include_once "pie.php" ?>
<?php include_once "ventana.php" ?>