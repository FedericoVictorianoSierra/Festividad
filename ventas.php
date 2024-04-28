<?php

include 'conexion.php';


// Inicializar la variable $fecha_seleccionada
$fecha_seleccionada = "";

// Consulta SQL para seleccionar las ventas del proveedor actual
$sql = "SELECT v.idusuario, v.idcarrito, v.fecha, v.impuesto, v.total 
        FROM venta v
        INNER JOIN carrito c ON v.idcarrito = c.idcarrito
        INNER JOIN articulo a ON c.idarticulo = a.idarticulo
        WHERE a.idprovedor = $idusuario";

// Si se envió una fecha en el formulario, agregar la condición para filtrar por fecha
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fecha'])) {
    $fecha_seleccionada = $_POST['fecha'];
    $sql .= " AND DATE(v.fecha) = '$fecha_seleccionada'";
}

$result = mysqli_query($conexion, $sql);
?>


<?php include_once "encabezado.php" ?>


<!-- Page Header Start -->
<div class="container-fluid page-header mb-4 position-relative overlay-bottom">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
        <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">VENTAS</h1>

    </div>
</div>
</div>
<!-- Ventas (Administrador)-->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">



            <!-- Formulario para seleccionar la fecha -->
            <div class="container">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <form method="post" class="mb-4">
                            <div class="form-group">
                                <label for="fecha">Filtrar por fecha:</label>
                                <input type="date" id="fecha" name="fecha" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </form>
                    </div>
                </div>
            </div>



            <!-- Ventas (Administrador)-->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fecha']) && mysqli_num_rows($result) > 0) : ?>
                            <?php echo "<td><a class='btn btn-info' href='imprimirVentas.php?fecha=$fecha_seleccionada'><i class='fa fa-print'></i></a></td>"; ?>
                        <?php endif; ?>
                        <?php if (mysqli_num_rows($result) > 0) : ?>

                            <table class="table tabla1">
                                <thead>
                                    <tr>
                                        <th>Fecha de compra</th>
                                        <th>Usuario</th>
                                        <th>Dirección</th>
                                        <th>Teléfono</th>
                                        <th>Imagen</th>
                                        <th>Código</th>
                                        <th>Artículos</th>
                                        <th>Descripción</th>
                                        <th>Cantidad</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = mysqli_fetch_assoc($result)) {

                                        $fecha = $row['fecha'];
                                        $impuesto = $row['impuesto'];
                                        $total = $row['total'];

                                        // Realizar consulta para obtener el usuario asociada al idusuario
                                        $query_usuario = "SELECT nombre, iddireccion, telefono FROM usuario WHERE idusuario= " . $row['idusuario'];
                                        $result_usuario = mysqli_query($conexion, $query_usuario);
                                        $row_usuario = mysqli_fetch_assoc($result_usuario);

                                        $usuarionombre = $row_usuario['nombre'];
                                        $telefono = $row_usuario['telefono'];


                                        /*obtener direccion*/
                                        $iddireccion = $row_usuario['iddireccion'];
                                        // Realizar consulta para obtener la dirección asociada al ID de dirección del usuario
                                        $query_direccion = "SELECT pais, estado, ciudad, colonia, calle, numcalle, codigopostal FROM direccion WHERE iddireccion = " . $iddireccion;
                                        $result_direccion = mysqli_query($conexion, $query_direccion);
                                        $row_direccion = mysqli_fetch_assoc($result_direccion);

                                        // Almacenar la información de la dirección en variables
                                        $Pais = $row_direccion['pais'];
                                        $Estado = $row_direccion['estado'];
                                        $Ciudad = $row_direccion['ciudad'];
                                        $CodigoP = $row_direccion['codigopostal'];
                                        $colonia = $row_direccion['colonia'];
                                        $calle = $row_direccion['calle'];


                                        $idcarrito = $row['idcarrito'];
                                        $sqlcarrito = "SELECT * FROM carrito WHERE idcarrito = $idcarrito";
                                        $resultadocarrito = mysqli_query($conexion, $sqlcarrito);
                                        $rowcarrito = mysqli_fetch_assoc($resultadocarrito);

                                        $idarticulo = $rowcarrito['idarticulo'];
                                        $sqlarticulo = "SELECT * FROM articulo WHERE idarticulo = $idarticulo";
                                        $resultadoarticulo = mysqli_query($conexion, $sqlarticulo);
                                        $rowarticulo = mysqli_fetch_assoc($resultadoarticulo);

                                        // Obtener imagen de la base de datos
                                        $query_imagen = "SELECT nuevaImagen FROM img WHERE id_imagen = " . $rowarticulo['id_imagen'];
                                        $result_imagen = mysqli_query($conexion, $query_imagen);
                                        $row_imagen = mysqli_fetch_assoc($result_imagen);
                                    ?>
                                        <?php
                                        ?>
                                        <tr>

                                            <td><?php echo $fecha; ?></td>
                                            <td><?php echo $usuarionombre; ?></td>
                                            <!-- mostrar Dierccion -->
                                            <td>
                                                <i class="icono fas fa-map-marker-alt"></i> <?php echo $calle . ", " . $colonia . ", " . $Ciudad . ", " . $Estado . ", " . $CodigoP ?>
                                                <br>
                                            </td>
                                            <td><?php echo $telefono; ?></td>
                                            <td><img src='<?php echo $row_imagen['nuevaImagen']; ?>' alt='imagen' width='100'></td>
                                            <td><?php echo $rowarticulo['codigo']; ?></td>
                                            <td><?php echo $rowarticulo['nombre']; ?></td>


                                            <td><?php echo $rowarticulo['descripcion']; ?></td>
                                            <td><?php echo $rowcarrito['cantidad']; ?></td>
                                            <td><?php echo "$" . $total; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <p>No se encontraron ventas.</p>
                        <?php endif; ?>
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
            <?php include_once "pie.php" ?>