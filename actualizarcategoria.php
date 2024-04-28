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

// Verificar si se materiales
if (!$resultado_materiales) {
    echo "Error al recuperar materiales: " . mysqli_error($conexion);
    exit();
}

// Verificar si se ha enviado el formulario
if (isset($_POST['actualizar'])) {

    // Obtener los valores del formulario
    $idcategoria = $_POST['idcategoria'];
    $idmaterial = $_POST['idmaterial'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    if (isset($_FILES['foto_']) && !empty($_FILES['foto_']['tmp_name'])) { // Se comprueba que se haya subido una foto
        $nombreimg = basename($_FILES['foto_']['name']); // Se obtiene el nombre de la imagen
        $imagen = addslashes(file_get_contents($_FILES['foto_']['tmp_name'])); // Se obtiene el contenido binario de la imagen
        $tip = exif_imagetype($_FILES['foto_']['tmp_name']); //Se obtiene el tipo de imagen
        $extension = image_type_to_extension($tip); // Se obtiene la extensión de la imagen


        // Consulta SQL para recuperar los datos de categoria
        $categoria_query = "SELECT * FROM categoria WHERE idcategoria='$idcategoria'";
        // Ejecutar la consulta
        $resultado_categoria = mysqli_query($conexion, $categoria_query);
        // Obtener los datos de categoria
        $categoria = mysqli_fetch_assoc($resultado_categoria);

        /// Se actualiza la imagen del categoria en la tabla img
        $query_imagen = "UPDATE img SET nombre='$nombreimg',imagen='$imagen', Tipo='$extension' WHERE Id_imagen='$categoria[id_imagen]'";
        mysqli_query($conexion, $query_imagen);
        $idimagen = mysqli_insert_id($conexion);

        // Actualizar los datos en la tabla categoria, incluyendo el nuevo id_imagen
        $sql = "UPDATE categoria SET idmaterial='$idmaterial', nombre='$nombre', descripcion='$descripcion' WHERE idcategoria='$idcategoria'";
    } else {
        // Actualizar los datos en la tabla categoria sin actualizar la imagen
        $sql = "UPDATE categoria SET idmaterial='$idmaterial', nombre='$nombre', descripcion='$descripcion' WHERE idcategoria='$idcategoria'";
    }

    if (mysqli_query($conexion, $sql)) {
        $textoModal = "Los datos se han actualizado correctamente..";
        $mostrarModal = true;
        $nombreArchivo = "categoria.php";
    } else {
        $textoModal = "Error al actualizar los datos: " . mysqli_error($conexion);
        $mostrarModal = true;
        $nombreArchivo = "actualizarcategoria.php";
    }
}


?>


<?php include_once "encabezado.php" ?>
<!-- Page Header Start -->
<div class="container-fluid page-header mb-4 position-relative overlay-bottom">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
        <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">ACTUALIZAR CATEGORÍA</h1>

    </div>
</div>
</div>
<!-- Page Header End -->
<?php

// Obtener el id de la categoria a actualizar
$idcategoria = $_GET['id'];

// Consulta SQL para recuperar los datos de categoria
$categoria_query = "SELECT * FROM categoria WHERE idcategoria='$idcategoria'";

// Ejecutar la consulta
$resultado_categoria = mysqli_query($conexion, $categoria_query);

// Verificar si se encontró el categoria
if (!$resultado_categoria) {
    echo "Error al recuperar la categoria: " . mysqli_error($conexion);
    exit();
}

// Obtener los datos de la categoria
$categoria = mysqli_fetch_assoc($resultado_categoria);

// Consulta SQL para recuperar las categorías
$materiales_query = "SELECT idmaterial, nombre FROM material";

// Ejecutar la consulta
$resultado_material = mysqli_query($conexion, $materiales_query);

// Verificar si se encontraron categorías
if (!$resultado_material) {
    echo "Error al recuperar las categorías: " . mysqli_error($conexion);
    exit();
}
?>
<div class="container-fluid py-5">
    <div class="container">
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="idcategoria" value="<?php echo $categoria['idcategoria']; ?>">
            <label for="idmaterial">Material:</label>
            <select class="btn-primary btn-lg px-4 me-sm-3" name="idmaterial" id="idmaterial">
                <?php while ($material = mysqli_fetch_assoc($resultado_materiales)) : ?>
                    <option value="<?php echo $material['idmaterial']; ?>" <?php if ($material['idmaterial'] == $categoria['idmaterial']) echo "selected"; ?>><?php echo $material['nombre']; ?></option>
                <?php endwhile; ?>
            </select>
            <br>
            <label for="nombre">Nombre:</label>
            <input class="px-4 me-sm-3" type="text" name="nombre" id="nombre" value="<?php echo $categoria['nombre']; ?>" required="required">
            <br>
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" required="required"><?php echo $categoria['descripcion']; ?></textarea>
            <br>
            <label for="foto_">Foto:</label>
            <input class="px-4 me-sm-3" type="file" name="foto_" id="foto_">
            <br>
            <input class="btn btn-secondary font-weight-bold py-2 px-4 mt-2" type="submit" name="actualizar" value="Actualizar">
        </form>
    </div>
</div>
<?php include_once "pie.php" ?>
<?php include_once "ventana.php" ?>