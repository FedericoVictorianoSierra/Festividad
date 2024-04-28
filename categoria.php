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

// Obtener el valor de idrol del usuario actual desde la tabla usuario
$query_usuario = "SELECT idrol FROM usuario WHERE idusuario = $idusuario";
$resultado_usuario = mysqli_query($conexion, $query_usuario);
$fila_usuario = mysqli_fetch_assoc($resultado_usuario);
$idrol = $fila_usuario['idrol'];
?>

<?php include_once "encabezado.php" ?>


<!-- Page Header Start -->
<div class="container-fluid page-header mb-4 position-relative overlay-bottom">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
        <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">EXPLORAR PODUCTOS Y SERVICIOS</h1>

    </div>
</div>
</div>
<!-- Page Header End -->


<div class="container-fluid pt-5">
    <div class="container">
        <?php
        // Mostrar el botón "Nuevo" solo si el valor de idrol es igual a 2
        if ($idrol == 2) {
            echo '<a class="btn btn-success" href="./insertarcategoria.php">Nuevo <i class="fa fa-plus"></i></a>';
        }
        ?>
        <div class="section-title">
            <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Los mejores produtos y servicios para tus fiestas</h4>
            <h1 class="display-4">Compra por categoria</h1>
        </div>
        <div class="row">



            <?php
            // Realizamos la consulta para obtener las categorías
            $query = "SELECT idcategoria, nombre, descripcion, id_imagen, idmaterial FROM categoria";
            $result = mysqli_query($conexion, $query);

            // Recorremos los resultados y mostramos la información de cada categoría
            while ($categoria = mysqli_fetch_assoc($result)) {

                // Obtener imagen de la base de datos
                $query_imagen = "SELECT imagen FROM img WHERE id_imagen = " . $categoria['id_imagen'];
                $result_imagen = mysqli_query($conexion, $query_imagen);
                $row_imagen = mysqli_fetch_assoc($result_imagen);

                // Mostramos la información de la categoría
            ?>

                <div class="col-lg-6 mb-5">
                    <div class="row align-items-center">
                        <div class="col-sm-5">
                            <!-- Mostramos la imagen de la categoría -->
                            <img src="data:<?php echo ';base64,' . base64_encode($row_imagen['imagen']) ?>" alt="imagen" width="100">
                        </div>
                        <div class="col-sm-7">
                            <!-- Mostramos el nombre y la descripción de la categoría -->
                            <h4><?php echo $categoria['nombre'] ?></h4>
                            <p>Descipción: <?php echo $categoria['descripcion'] ?></p>

                            <!-- Creamos un enlace a la página de artículos que utiliza el ID de la categoría para enfocar en la categoría -->
                            <a href="articulos.php#categoria-<?php echo $categoria['idcategoria'] ?>" class="btn btn-primary btn-lg px-4 me-sm-3" onclick="enfocarCategoria(<?php echo $categoria['idcategoria'] ?>)" type="button"><?php echo $categoria['nombre'] ?></a>
                        </div>
                        <?php
                        // Mostrar el botón solo si el valor de idrol es igual a 2
                        if ($idrol == 2) { ?>
                            <form method='post'>
                                <?php $id = $categoria['idcategoria'] ?>
                                <a class="btn btn-warning" href="actualizarcategoria.php?id=<?php echo $categoria['idcategoria']; ?>"><i class="fa fa-edit"></i></a>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            <?php
            }
            ?>


        </div>
    </div>
</div>
<?php include_once "pie.php" ?>