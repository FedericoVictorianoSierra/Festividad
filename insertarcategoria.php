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

// Consulta SQL para recuperar los materiales
$material_query = "SELECT idmaterial, nombre FROM material";

// Ejecutar la consulta
$resultado_materiales = mysqli_query($conexion, $material_query);
//obtener el idmaterial
$row_materiales = mysqli_fetch_array($resultado_materiales);
$idmaterial = $row_materiales['idmaterial'];

// Verificar si se materiales
if (!$resultado_materiales) {
    echo "Error al recuperar materiales: " . mysqli_error($conexion);
    exit();
}

// Verificar si se ha enviado el formulario
if (isset($_POST['crear'])) {

    // Obtener los valores del formulario
    $idcategoria = $_POST['idcategoria'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    if (isset($_FILES['foto_'])) { // Se comprueba que se haya subido una foto
        $nombreimg = basename($_FILES['foto_']['name']); // Se obtiene el nombre de la imagen
        $imagen = addslashes(file_get_contents($_FILES['foto_']['tmp_name'])); // Se obtiene el contenido binario de la imagen
        $tip = exif_imagetype($_FILES['foto_']['tmp_name']); //Se obtiene el tipo de imagen
        $extension = image_type_to_extension($tip); // Se obtiene la extensión de la imagen
    }

    // Se inserta la imagen del usuario en la tabla img
    $query_imagen = "INSERT INTO img(nombre,imagen, Tipo) VALUES ('$nombreimg','$imagen','$extension')";
    mysqli_query($conexion, $query_imagen);
    $idimagen = mysqli_insert_id($conexion);

    // Insertar los datos en la tabla categoria
    $sql = "INSERT INTO categoria (nombre, descripcion, idmaterial, id_imagen) VALUES ('$nombre', '$descripcion', '$idmaterial', '$idimagen')";
    if (mysqli_query($conexion, $sql)) {
        $textoModal = "Los datos se han insertado correctamente.";
        $mostrarModal = true;
        $nombreArchivo = "insertarcategoria.php";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
    }
}


?>


<?php include_once "encabezado.php" ?>
<!-- Page Header Start -->
<div class="container-fluid page-header mb-4 position-relative overlay-bottom">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
        <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">NUEVA CATEGORÍA</h1>

    </div>
</div>
</div>
<!-- Page Header End -->
<div class="container-fluid py-5">
    <div class="container">
        <form class="" action="" method="POST" enctype="multipart/form-data">
            <label>Material:</label>
            <select class="btn-primary btn-lg px-4 me-sm-3" name="idcategoria" id="idcategoria">
                <?php while ($row = mysqli_fetch_array($resultado_materiales)) : ?>
                    <option value="<?php echo $row['idmaterial']; ?>"><?php echo $row['nombre']; ?></option>
                <?php endwhile; ?>
            </select>
            <br>
            <label for="nombre">Nombre:</label>
            <input class="px-4 me-sm-3" type="text" name="nombre" id="nombre" required="required">
            <br>
            <label for="descripcion">Descripción:</label>
            <textarea class="px-4 me-sm-3" name="descripcion" id="descripcion" required="required"></textarea>
            <br>
            <label for="foto_">Imagen:</label>
            <input class="px-4 me-sm-3" type="file" name="foto_" id="foto_" required="required" accept="image/png, image/jpeg, image/jpg">
            <br>
            <input class="btn btn-secondary font-weight-bold py-2 px-4 mt-2" type="submit" name="crear" value="Crear artículo">
        </form>
    </div>
</div>

<?php include_once "pie.php" ?>
<?php include_once "ventana.php" ?>