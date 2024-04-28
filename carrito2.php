<?php

session_start(); // Iniciar sesión

if (!isset($_SESSION['idusuario'])) {
    // Redirigir al usuario a la página de inicio de sesión si no hay una sesión iniciada
    header("Location: sesion.php");
    exit();
}

// El ID del usuario está disponible en $_SESSION['idusuario']
$idusuario = $_SESSION['idusuario'];
$ivas = 0.16; // Establecer el valor del impuesto IVA
global $datos; // Declarar una variable global para almacenar los datos del carrito

require("php_con/db.php"); // Incluir el archivo que contiene la función de conexión 
$conexion = conexion(); // Crear la conexión a la base de datos
// Obtener los registros del carrito del usuario
$registros = mysqli_query($conexion, "SELECT * FROM carrito WHERE idusuario = $idusuario");
while ($resultado = mysqli_fetch_array($registros)) {
     // Obtener el carrito activo del usuario
     $carrito_id = $resultado['idcarrito'];
     $sqlv = "SELECT * FROM carrito WHERE idcarrito = $carrito_id AND activo = 1";
     $resultadov = mysqli_query($conexion, $sqlv);
     
     // Verificar si el carrito está activo
     if (mysqli_num_rows($resultadov) > 0) {
         $datos[] = $resultado; // Almacenar los registros en la variable global $datos
     }
}

?>


<?php include_once "encabezado.php" ?>


<!-- Page Header Start -->
<div class="container-fluid page-header mb-4 position-relative overlay-bottom">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
        <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">CARRITO</h1>

    </div>
</div>
</div>
<!-- Page Header End -->
<!-- Navbar End -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <a href="compras.php" class="btn btn-secondary font-weight-bold py-2 px-4 mt-2">COMPRAS</a>
            <br>
            <br>
            <?php
            // Verificar si el carrito está vacío
            if (empty($datos)) {
                echo "El carrito está vacío";
            } else { // Si no está vacío, mostrar la tabla con los detalles del carrito
            ?>
                <table class="table tabla1">
                    <thead>
                        <tr class="encabezado">
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Existencias</th>
                            <th>Precio c/u</th>
                            <th>Cantidad</th>
                            <th>Sub Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <?php
                    $total = 0; // Inicializar la variable total en cero
                    $subtotal = 0; // Inicializar la variable subtotal en cero
                    foreach ($datos as $datos1) {
                        $sql = "SELECT * FROM articulo WHERE idarticulo = " . $datos1['idarticulo'];
                        $resultado = mysqli_query($conexion, $sql);
                        $articulo = mysqli_fetch_assoc($resultado);

                        // Obtener imagen de la base de datos
                        $query_imagen = "SELECT imagen FROM img WHERE id_imagen = " . $articulo['id_imagen'];
                        $result_imagen = mysqli_query($conexion, $query_imagen);
                        $row_imagen = mysqli_fetch_assoc($result_imagen);

                        // Cálculo del precio total del artículo
                        $precio_total = $articulo['precio_venta'] * $datos1['cantidad'];
                        $subtotal += $precio_total; // Agregar el precio total al subtotal
                        $total += $precio_total; // Agregar el precio total al total de la compra
                        // Guardar el ID del artículo en el carrito
                        $idcarrito = $datos1['idcarrito'] ?>


                        <tr class="cuerpotabla">
                            <!-- Mostrar la imagen del artículo -->
                            <td><img src='data:<?php echo $row_imagen['Tipo']; ?>;base64,<?php echo base64_encode($row_imagen['imagen']); ?>' alt='imagen' width='100'></td>
                            <td><?php echo $articulo['nombre']; ?></td>
                            <td><?php echo $articulo['existencia']; ?></td>
                            <td><?php echo "$" . $articulo['precio_venta']; ?></td>
                            <td><?php echo $datos1['cantidad']; ?></td>
                            <td><?php echo "$" . $precio_total; ?></td>
                            <td>
                                <!-- Botón para eliminar el artículo del carrito -->
                                <form action="" method="post">
                                    <input type="hidden" name="accion" value="eliminar">
                                    <input type="hidden" name="id_carrito" value="<?php echo $datos1['idcarrito']; ?>">
                                    <button class="btn btn-danger" type="submit"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>

                    <?php
                    // Si el usuario ha solicitado eliminar un artículo del carrito
                    if (isset($_POST['accion']) && $_POST['accion'] == 'eliminar') {
                        $id_carrito = $_POST['id_carrito']; // Obtener el ID del artículo en el carrito que se deseamos eliminar
                        // Creamos la consulta SQL para eliminar el artículo del carrito de la base de datos
                        $sql = "DELETE FROM carrito WHERE idcarrito = $id_carrito";
                        // Ejecutamos la consulta SQL en la base de datos
                        if (mysqli_query($conexion, $sql)) {
                            // Si la eliminación se realizó con éxito, redirige al usuario al carrito
                            echo "<script>location.href='carrito.php';</script>";
                            exit;
                        } else {
                            // Si ocurre algún error durante la eliminación, mostramos un mensaje de error
                            echo "Error al eliminar el artículo del carrito: " . mysqli_error($conexion);
                        }
                    }
                    ?>
                    </tr>

                    <tr>
                        <td>
                            <!-- Mostrar el Total -->
                            <p>Total: $<?php echo $total; ?></p>
                            <?php
                            ?>
                        </td>

                    </tr>
                </table>
                <?php
                if (isset($_POST['accion']) && $_POST['accion'] == 'comprar') {
                    // Obtener la fecha actual
                    date_default_timezone_set('America/Mexico_City');
                    $fecha = date('Y-m-d H:i:s');

                    // Modificar la estructura de la tabla "venta" para permitir valores nulos en la columna idcarrito
                    //$sql_alter_table = "ALTER TABLE venta MODIFY COLUMN idcarrito INT NULL";
                    //mysqli_query($conexion, $sql_alter_table);

                    // Insertar cada carrito  en la tabla "venta"
                    foreach ($datos as $datos1) {

                        $sql = "SELECT * FROM articulo WHERE idarticulo = " . $datos1['idarticulo'];
                        $resultado = mysqli_query($conexion, $sql);
                        $articulo = mysqli_fetch_assoc($resultado);
                        $precio_total = $articulo['precio_venta'] * $datos1['cantidad'];

                        $sql_detalle_venta = "INSERT INTO venta(idusuario, fecha, impuesto, total, idcarrito) VALUES ('{$datos1['idusuario']}', '{$fecha}', '{$ivas}', '{$precio_total}', '{$datos1['idcarrito']}')";
                        //$sql_detalle_venta = "INSERT INTO venta(idusuario, fecha, impuesto, total) VALUES ('{$datos1['idusuario']}', '{$fecha}', '{$ivas}', '{$precio_total}')";

                        mysqli_query($conexion, $sql_detalle_venta);

                        if ($articulo['existencia'] > 0 && $datos1['cantidad'] <= $articulo['existencia']) {
                            // Actualizar la existencia del artículo en la tabla "articulo"
                            $nueva_cantidad = $articulo['existencia'] - $datos1['cantidad'];
                            $sql_actualizar_cantidad = "UPDATE articulo SET existencia = $nueva_cantidad WHERE idarticulo = {$datos1['idarticulo']}";
                            mysqli_query($conexion, $sql_actualizar_cantidad);
                        } else {
                            echo "<script>alert('No hay articulos sufucientes ');</script>";
                        }
                    }

                    // Eliminar los registros del carrito
                    foreach ($datos as $datos1) {
                        $sqlx = "UPDATE carrito SET activo='0' WHERE idcarrito = {$datos1['idcarrito']}";
                        //$sqlx = "DELETE FROM carrito WHERE idcarrito = {$datos1['idcarrito']}";
                        mysqli_query($conexion, $sqlx);
                    }

                    // Redirigir al usuario a una página de confirmación de compra
                    $textoModal = "Compra Exitosa";
                    $mostrarModal = true;
                    $nombreArchivo = "compras.php";
                    //exit;
                }

                ?>

                <!-- Enviar a la forma de pago -->
                <form action="" method="post" class="alineacion">
                    <input type="hidden" name="accion" value="comprar">
                    <button class="btn btn-secondary font-weight-bold py-2 px-4 mt-2" type="submit">Comprar</button>
                </form>

            <?php } ?>
        </div>
    </div>
</div>
<script src="js/jquery.min.js"></script>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<?php include_once "ventana.php" ?>
<?php include_once "pie.php" ?>