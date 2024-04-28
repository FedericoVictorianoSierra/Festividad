<?php
$idusuario = "";
include 'conexion.php';
include 'consultas.php';

// Comprobar si se ha enviado el formulario de agregar al carrito
if (isset($_POST['agregar_carrito'])) {
    $idarticulo = $_POST['idarticulo']; // Obtener el ID del artículo a agregar
    $cantidad = $_POST['cantidad']; // Obtener la cantidad del artículo a agregar
    $idtalla = $_POST['idtalla_']; // Obtener la cantidad del artículo a agregar

    // Consulta SQL para verificar si ya existe una fila en la tabla de carrito con la misma ID de artículo, talla y carrito activo
    $sql_verificar = "SELECT * FROM carrito WHERE idarticulo = $idarticulo AND idusuario = $idusuario AND idtalla = $idtalla AND activo = 1";

    $result_verificar = mysqli_query($conexion, $sql_verificar);

    if (mysqli_num_rows($result_verificar) > 0) { // Si ya existe una fila en la tabla de carrito con la misma ID de artículo y el carrito está activo
        $fila_verificar = mysqli_fetch_assoc($result_verificar);
        // Actualizar la cantidad del producto en la fila existente

        // Obtener la cantidad actual de productos en la fila existente
        $cantidad_actual = $fila_verificar['cantidad'];
        // Calcular la nueva cantidad de productos que habrá en la fila
        $cantidad_nueva = $cantidad_actual + $cantidad;
        // Obtener el ID de la fila existente en la tabla "carrito"
        $idcarrito = $fila_verificar['idcarrito'];

        // Crear una consulta SQL para actualizar la cantidad de productos en la fila existente
        $sql_actualizar = "UPDATE carrito SET cantidad = $cantidad_nueva WHERE idcarrito = $idcarrito";
        mysqli_query($conexion, $sql_actualizar); // Ejecutar la consulta SQL
    } else { // Si no existe una fila en la tabla de carrito con la misma ID de artículo y carrito activo

        // Insertar una nueva fila en la tabla de carrito
        $sql_insertar = "INSERT INTO carrito (idarticulo, idusuario, cantidad, activo, idtalla) VALUES ($idarticulo, $idusuario, $cantidad, '1', $idtalla)";

        mysqli_query($conexion, $sql_insertar);
    }

    echo "<script>alert('Artículo agregado al carrito correctamente.');</script>";

    // Redirigir al usuario a la página del carrito de compras
    //echo "<script>location.href='carrito.php';</script>";
}

?>



<?php include_once "encabezado.php" ?>

<!-- Page Header Start -->
<div class="container-fluid page-header mb-4 position-relative overlay-bottom">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
        <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">PRODUCTOS Y SERVICIOS</h1><!-- Titulo de la sección -->
    </div>
</div>
</div>

<!-- Menu Start -->
<div class="container-fluid pt-5">
    <div class="container">
        <div class="row">

            <?php
            // Mostrar el botón "Nuevo" solo si el valor de idrol es igual a 2
            if ($idrol == 2) {
                echo '<a class="btn btn-success" href="./insertar.php">Nuevo <i class="fa fa-plus"></i></a>';
            } else {
            ?>
                <form method="get" action="">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Buscar Servicio..." name="q" value="<?php echo isset($_GET['q']) ? $_GET['q'] : ''; ?>">
                        <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                    </div>
                </form>

            <?php
            }
            ?>

            <?php
            // Ejemplo de uso de función de consulta
            $resultado_categorias = obtenerTodasLasCategorias($conexion);

            // Recorrer todas las categorías
            while ($fila_categorias = mysqli_fetch_assoc($resultado_categorias)) {
                // Mostrar título de la categoría con su respectivo identificador para el scroll
            ?>
                <div class='container' id='categoria-<?php echo $fila_categorias['idcategoria']; ?>'>


                    <?php if (empty($_GET['q'])) { //si se ha realizado una busqueda no mostrar imprimirCatalogo y categoria
                    ?>
                        <div class='section-title'>
                            <h4 class='text-primary text-uppercase' style='letter-spacing: 5px;'>Productos y servicios principales</h4>
                            <h1 class='display-4'><?php echo $fila_categorias['nombre']; ?></h1>
                            <?php
                            // Mandar el ID de la categoría a imprimir para mostrar el catálogo
                            echo "<a class='btn btn-info' href='imprimirCatalogo.php?id={$fila_categorias['idcategoria']}'><i class='fa fa-print'></i> Imprimir Catálogo</a>";
                            ?>
                        </div>
                    <?php }
                    ?>
                    <?php

                    // si es el provedor solo mostrarle sus articulos
                    if ($idrol == 2) {
                        $query_articulos = "SELECT * FROM articulo WHERE idcategoria = " . $fila_categorias['idcategoria'] . " AND idprovedor = $idusuario";
                    } else {


                        // si ha realizado una búsqueda, mostrar solo los artículos de la búsqueda, sino mostrar todos los artículos de la categoría
                        if (isset($_GET['q']) && !empty($_GET['q'])) {
                            $busqueda = $_GET['q'];
                            // Si se ha realizado una búsqueda, filtrar los artículos según el término de búsqueda
                            $query_articulos = "SELECT * FROM articulo WHERE idcategoria = " . $fila_categorias['idcategoria'] . " AND (nombre LIKE '%$busqueda%' OR descripcion LIKE '%$busqueda%')";
                        } else {
                            $query_articulos = "SELECT * FROM articulo WHERE idcategoria = " . $fila_categorias['idcategoria'];
                        }
                    }
                    $resultado_articulos = mysqli_query($conexion, $query_articulos);

                    // Mostrar los artículos de la categoría actual
                    ?>
                    <div class='row'>
                        <?php
                        // Recorrer todos los artículos de la categoría actual
                        while ($fila_articulos = mysqli_fetch_assoc($resultado_articulos)) {
                            // Obtener la imagen del artículo actual
                            $query_imagen = "SELECT nuevaImagen, imagen FROM img WHERE id_imagen = " . $fila_articulos['id_imagen'];
                            $result_imagen = mysqli_query($conexion, $query_imagen);
                            $row_imagen = mysqli_fetch_assoc($result_imagen);

                            // Mostrar cada artículo en su respectiva columna
                        ?>
                            <div class='col-lg-6'>
                                <div class='row align-items-center mb-5'>
                                    <div class='col-4 col-sm-3'>

                                        <!-- Mostrar la imagen del artículo actual -->
                                        <img class='w-100 rounded-circle mb-3 mb-sm-0' src='<?php echo $row_imagen['nuevaImagen']; ?>' alt='imagen'>

                                        <h5 class='menu-price'><?php echo "$" . $fila_articulos['precio_venta']; ?></h5>
                                    </div>



                                    <div class='col-8 col-sm-9'>
                                        <!-- Mostrar los datos del artículo actual -->
                                        <!-- Mostrar el nombre del artículo como un enlace que redirige a detallesArticulo.php -->
                                        <h4><a href="detallesArticulo.php?id=<?php echo $fila_articulos['idarticulo']; ?>"><?php echo $fila_articulos['nombre']; ?></a></h4>


                                        <p><?php echo $fila_articulos['descripcion']; ?></p>

                                        <!-- Mostrar el selector de tallas según la existencia en cada talla -->
                                        <?php
                                        // Consulta SQL para obtener las tallas disponibles según la existencia en la tabla existencia
                                        $query_tallas = "SELECT t.idtalla, t.nombre, e.existencia 
                                                FROM talla t 
                                                INNER JOIN existencia e ON t.idtalla = e.id_talla 
                                                WHERE e.id_articulo = " . $fila_articulos['idarticulo'];

                                        $resultado_tallas = mysqli_query($conexion, $query_tallas);

                                        // Declarar un arreglo para almacenar las tallas
                                        $tallas_disponibles = array();


                                        ?>


                                        <p>Modelo: <?php echo $fila_articulos['modelo']; ?></p>
                                        <p>Existencias:
                                            <?php
                                            // Obtener la existencia del artículo actual
                                            $query_existencia = "SELECT SUM(existencia) AS total_existencia FROM existencia WHERE id_articulo = " . $fila_articulos['idarticulo'];
                                            $resultado_existencia = mysqli_query($conexion, $query_existencia);
                                            $fila_existencia = mysqli_fetch_assoc($resultado_existencia);
                                            echo $fila_existencia['total_existencia'];
                                            ?>
                                        </p>
                                        <?php

                                        $query = "SELECT * FROM usuario WHERE idusuario = {$fila_articulos['idprovedor']}";
                                        $resultado_usuario = mysqli_query($conexion, $query);
                                        $fila_usuario = mysqli_fetch_assoc($resultado_usuario);

                                        ?>

                                        <p>Vendedor: <?php echo $fila_usuario['nombre']; ?></p>



                                        <?php
                                        if ($idrol == 1) { ?>
                                            <!-- Formulario para agregar el artículo al carrito -->
                                            <form method='post'>
                                                <input type='hidden' name='idarticulo' value='<?php echo $fila_articulos['idarticulo']; ?>'>

                                                <input class='btn btn-primary btn-lg px-4 me-sm-3' type='number' name='cantidad' value='1' min='1' max='<?php echo $fila_existencia['total_existencia']; ?>' id='cantidad_input'>

                                                <?php

                                                // Mostrar el selector de tallas según la existencia en cada talla
                                                if (mysqli_num_rows($resultado_tallas) > 0) {
                                                    echo "<label for='talla'>Talla:</label>";
                                                    echo "<select name='idtalla_'  id='idtalla_'>";

                                                    // Iterar sobre las tallas disponibles
                                                    while ($fila_tallas = mysqli_fetch_assoc($resultado_tallas)) {
                                                        if ($fila_tallas['existencia'] > 0) {
                                                            // Mostrar la opción de talla con su respectiva existencia
                                                            echo "<option value='" . $fila_tallas['idtalla'] . "' data-existencia='" . $fila_tallas['existencia'] . "'>" . $fila_tallas['nombre'] . " (Existencia: " . $fila_tallas['existencia'] . ")" . "</option>";
                                                            // Agregar el idtalla al arreglo de tallas disponibles
                                                            $tallas_disponibles[] = $fila_tallas['idtalla'];
                                                        }
                                                    }

                                                    echo "</select>";
                                                    echo "<br>";
                                                    echo "<br>";
                                                } else {
                                                    echo "<p>No hay tallas disponibles para este artículo.</p>";
                                                }
                                                ?>


                                                <br>


                                                <button class='btn btn-primary btn-lg px-4 me-sm-3' type='submit' name='agregar_carrito'><i class="fa fa-cart-plus"></i></button>
                                            </form>
                                        <?php } ?>

                                        <?php
                                        // Mostrar el botón solo si el valor de idrol es igual a 2
                                        if ($idrol == 2) { ?>




                                            <form method='post'>
                                                <?php $id = $fila_articulos['idarticulo'] ?>
                                                <input type='hidden' name='idarticulo' value='<?php echo $fila_articulos['idarticulo']; ?>'>
                                                <a class="btn btn-warning" href="actualizar.php?id=<?php echo $fila_articulos['idarticulo']; ?>"><i class="fa fa-edit"></i></a>

                                                <a class="btn btn-danger" href="#" onclick="confirmarEliminacion(<?php echo $fila_articulos['idarticulo']; ?>);"><i class="fa fa-trash"></i></a>

                                                <script>
                                                    function confirmarEliminacion(idArticulo) {
                                                        if (confirm("¿Estás seguro de que deseas eliminar este artículo?")) {
                                                            // Si el usuario confirma la eliminación, redirigir a la página de eliminación
                                                            window.location.href = "eliminar.php?id=" + idArticulo;
                                                        } else {
                                                            // Si el usuario cancela, no hacer nada
                                                        }
                                                    }
                                                </script>

                                            </form>

                                        <?php


                                        } ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            <?php
            }
            ?>

        </div>
    </div>

</div>
<!-- Menu End -->

<?php include_once "pie.php" ?>

<script src="js/scroll.js"></script>

<script>

</script>