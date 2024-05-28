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
if (isset($_POST['crear'])) {

    // Obtener los valores del formulario
    $idcategoria = $_POST['idcategoria'];
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $precio_venta = $_POST['precio_venta'];
    $existencia = $_POST['existencia'];
    $descripcion = $_POST['descripcion'];
    $imagen = $_POST['foto_'];


    /// Se actualiza la imagen del articulo en la tabla img
    $query_imagen = "INSERT INTO img(nuevaImagen) VALUES ('$imagen')";

    // Se inserta la imagen del usuario en la tabla img
    mysqli_query($conexion, $query_imagen);
    $idimagen = mysqli_insert_id($conexion);

// Insertar los datos en la tabla articulos, incluyendo el idusuario
$sql = "INSERT INTO articulo (idcategoria, codigo, nombre, precio_venta, existencia, descripcion, id_imagen, idprovedor) VALUES ('$idcategoria', '$codigo', '$nombre', '$precio_venta', '$existencia', '$descripcion', '$idimagen', '$idusuario')";

if (mysqli_query($conexion, $sql)) {
        $textoModal = "Los datos se han insertado correctamente.";
        $mostrarModal = true;
        $nombreArchivo = "insertar.php";
    } else {
        $textoModal = "Error: " . $sql . "<br>" . mysqli_error($conexion);
        $mostrarModal = true;
        $nombreArchivo = "insertar.php";
    }
}


?>


<?php include_once "encabezado.php" ?>
<!-- Page Header Start -->
<div class="container-fluid page-header mb-4 position-relative overlay-bottom">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
        <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">NUEVO PRODUCTO</h1>

    </div>
</div>
</div>
<!-- Page Header End -->
<div class="container-fluid py-5">
    <div class="container">
        <form class="" action="" method="POST" enctype="multipart/form-data">
            <label for="idcategoria">Categoría:</label>
            <select class="btn-primary btn-lg px-4 me-sm-3" name="idcategoria" id="idcategoria">
                <?php while ($row = mysqli_fetch_array($resultado_categorias)) : ?>
                    <option value="<?php echo $row['idcategoria']; ?>"><?php echo $row['nombre']; ?></option>
                <?php endwhile; ?>
            </select>
            <br>
            <label for="codigo">Código:</label>
            <input class="px-4 me-sm-3" type="text" name="codigo" id="codigo" required="required">
            <br>
            <label for="nombre">Nombre:</label>
            <input class="px-4 me-sm-3" type="text" name="nombre" id="nombre" required="required">
            <br>
            <label for="precio_venta">Precio de venta:</label>
            <input class="px-4 me-sm-3" type="number" name="precio_venta" id="precio_venta" required="required">
            <br>
            <label for="existencia">Existencia:</label>
            <input class="px-4 me-sm-3" type="number" name="existencia" id="existencia" required="required">
            <br>
            <label for="descripcion">Descripción:</label>
            <textarea class="px-4 me-sm-3" name="descripcion" id="descripcion" required="required"></textarea>
            <br>
            <label for="foto_">URL Foto:</label>
            <!--<input class="px-4 me-sm-3" type="file" name="foto_" id="foto_">-->

            <input class="px-4 me-sm-3" type="text" name="foto_" id="foto_" required="required">
            <input class="btn btn-secondary font-weight-bold py-2 px-4 mt-2" type="submit" name="crear" value="Crear artículo">
        </form>
    </div>
</div>

<?php include_once "pie.php" ?>
<?php include_once "ventana.php" ?>