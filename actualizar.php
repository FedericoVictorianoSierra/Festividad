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
    $descripcion = $_POST['descripcion'];
    $modelo = $_POST['modelo'];
    $imagen = $_POST['foto_'];

    // Consulta SQL para recuperar los datos de articulo
    $articulo_query = "SELECT * FROM articulo WHERE idarticulo='$idarticulo'";
    // Ejecutar la consulta
    $resultado_articulo = mysqli_query($conexion, $articulo_query);
    // Obtener los datos de articulo
    $articulo = mysqli_fetch_assoc($resultado_articulo);
    
    // Consulta SQL para actualizar la imagen del artículo en la tabla img
    $query_imagen = "UPDATE img SET nuevaImagen='$imagen' WHERE Id_imagen='$articulo[id_imagen]'";
    mysqli_query($conexion, $query_imagen);

    // Actualizar los datos en la tabla articulo sin actualizar la imagen
    $sql = "UPDATE articulo SET idcategoria='$idcategoria', codigo='$codigo', nombre='$nombre', precio_venta='$precio_venta', descripcion='$descripcion', modelo='$modelo' WHERE idarticulo='$idarticulo'";

    if (mysqli_query($conexion, $sql)) {
        // Eliminar las entradas de existencia relacionadas con este artículo
        $eliminar_existencia_query = "DELETE FROM existencia WHERE id_articulo='$idarticulo'";
        mysqli_query($conexion, $eliminar_existencia_query);

        // Recorrer las selecciones de tallas y cantidades
        foreach ($_POST['idtalla'] as $idtalla) {
            $cantidad = $_POST['cantidad_' . $idtalla];
            // Insertar los datos en la tabla de existencia
            $query_existencia = "INSERT INTO existencia (id_articulo, id_talla, existencia) VALUES ('$idarticulo', '$idtalla', '$cantidad')";
            mysqli_query($conexion, $query_existencia);
        }

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
if (isset($_GET['id'])) {
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

    // Consulta SQL para recuperar las tallas
    $talla_query = "SELECT idtalla, nombre FROM talla";

    // Ejecutar la consulta
    $resultado_talla = mysqli_query($conexion, $talla_query);

    // Verificar si se encontraron tallas
    if (!$resultado_talla) {
        echo "Error al recuperar las tallas: " . mysqli_error($conexion);
        exit();
    }
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
                <input class="px-4 me-sm-3" minlength="8" maxlength="25" type="number" name="codigo" id="codigo" value="<?php echo $articulo['codigo']; ?>" required="required">
                <br>
                <label for="nombre">Nombre:</label>
                <input class="px-4 me-sm-3" type="text" name="nombre" id="nombre" value="<?php echo $articulo['nombre']; ?>" required="required">
                <br>
                <label for="precio_venta">Precio de venta:</label>
                <input class="px-4 me-sm-3" minlength="8" maxlength="25" type="number" name="precio_venta" id="precio_venta" value="<?php echo $articulo['precio_venta']; ?>" required="required">
                <br>
                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion" id="descripcion" required="required"><?php echo $articulo['descripcion']; ?></textarea>
                <br>
                <label>Talla:</label><br>
                <?php while ($row = mysqli_fetch_array($resultado_talla)) : ?>
                    <div>
                        <input type="checkbox" name="idtalla[]" id="idtalla_<?php echo $row['idtalla']; ?>" value="<?php echo $row['idtalla']; ?>" <?php if (tallaSeleccionada($row['idtalla'], $articulo['idarticulo'], $conexion)) echo "checked"; ?>>
                        <label for="idtalla_<?php echo $row['idtalla']; ?>"><?php echo $row['nombre']; ?></label>
                        <input type="number" name="cantidad_<?php echo $row['idtalla']; ?>" id="cantidad_<?php echo $row['idtalla']; ?>" min="0" value="<?php echo obtenerCantidad($row['idtalla'], $articulo['idarticulo'], $conexion); ?>">
                    </div>
                <?php endwhile; ?>
                <br>
                <label for="modelo">Modelo:</label>
                <input class="px-4 me-sm-3" type="text" name="modelo" id="modelo" value="<?php echo $articulo['modelo']; ?>" required="required">
                <br>

                <label for="foto_">URL Foto:</label>
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
                ?>
                <input class="px-4 me-sm-3" type="text" name="foto_" id="foto_" value="<?php echo $url_imagen; ?>" required="required">

                <img src="<?php echo $url_imagen; ?>" alt="Imagen del artículo" style="max-width: 200px;">

                <br>
                <input class="btn btn-secondary font-weight-bold py-2 px-4 mt-2" type="submit" name="actualizar" value="Actualizar">
            </form>
        </div>
    </div>
<?php include_once "pie.php" ?>
<?php include_once "ventana.php" ?>

<?php
// Función para verificar si una talla está seleccionada para este artículo
function tallaSeleccionada($idtalla, $idarticulo, $conexion)
{
    $query = "SELECT * FROM existencia WHERE id_articulo='$idarticulo' AND id_talla='$idtalla'";
    $resultado = mysqli_query($conexion, $query);
    return mysqli_num_rows($resultado) > 0;
}

// Función para obtener la cantidad de una talla para este artículo
function obtenerCantidad($idtalla, $idarticulo, $conexion)
{
    $query = "SELECT existencia FROM existencia WHERE id_articulo='$idarticulo' AND id_talla='$idtalla'";
    $resultado = mysqli_query($conexion, $query);
    if ($fila = mysqli_fetch_assoc($resultado)) {
        return $fila['existencia'];
    }
    return 0;
}
?>
