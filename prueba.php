<?php
$idusuario = "";
include 'conexion.php'; // Incluye el archivo de conexión a la base de datos

// Consulta SQL para obtener las compras del usuario ordenadas por fecha
$sql = "SELECT venta.fecha, venta.total, venta.idventa, carrito.idcarrito, carrito.cantidad, articulo.codigo, articulo.nombre, articulo.descripcion, img.Tipo, img.nuevaImagen FROM venta 
        INNER JOIN carrito ON venta.idcarrito = carrito.idcarrito 
        INNER JOIN articulo ON carrito.idarticulo = articulo.idarticulo 
        INNER JOIN img ON articulo.id_imagen = img.id_imagen 
        WHERE venta.idusuario = $idusuario 
        ORDER BY venta.fecha";
$result = mysqli_query($conexion, $sql); // Ejecuta la consulta y guarda el resultado en $result
?>

<?php include_once "encabezado.php" ?>

<section class="seccion-perfil-usuario">
    <div class="perfil-usuario-header">
        <div class="perfil-usuario-portada">
        </div>
    </div>
    <div class="perfil-usuario-body">
        <div class="perfil-usuario-bio">
        <table class="table">
                <thead>
                    <tr>
                        <th>Fecha de compra</th>
                        <th>Ticket</th>
                        <th>Imagen</th>
                        <th>Código</th>
                        <th>Artículos</th>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $current_date = null;
                    $subtotal_fecha = 0; // Inicializa el subtotal de la fecha
                    // Variable para almacenar la fecha anterior
                    $previous_date = null;

                    while ($row = mysqli_fetch_assoc($result)) { // Itera sobre cada fila de resultado
                        if ($current_date !== $row['fecha']) { // Comprueba si la fecha ha cambiado
                            if ($current_date !== null) { // Si no es la primera fecha
                                //juntar 6 columnas en Total
                                echo "<tr>
                                    <td colspan='7' style='text-align: right; background-color: #41C9E2; color: white;'>Total: </td>
                                    <td><strong>$subtotal_fecha</strong></td>
                                </tr>";
                                
                                $subtotal_fecha = 0; // Reinicia el subtotal para la nueva fecha
                            }
                            $current_date = $row['fecha']; // Actualiza la fecha actual
                        }
                        $subtotal_fecha += $row['total']; // Suma el total de la compra al subtotal de la fecha
                        
                        echo "<tr>";
                            // Verifica si la fecha actual es diferente de la anterior
                            if ($row['fecha'] !== $previous_date) {
                                echo "<td>{$row['fecha']}</td>"; // Imprime la fecha solo si es diferente a la anterior    
                                echo "<td><a class='btn btn-info' href='imprimirTicket.php?id={$row['idventa']}'><i class='fa fa-print'></i></a></td>";      
                            } else {
                                echo "<td></td>"; // Si la fecha es igual a la anterior, imprime una celda vacía
                                echo "<td></td>";
                            }
                            echo "
                            <td><img src='" . $row['nuevaImagen'] . "' alt='nuevaImagen' width='100'></td>
                            <td>{$row['codigo']}</td>
                            <td>{$row['nombre']}</td>
                            <td>{$row['descripcion']}</td>
                            <td>{$row['cantidad']}</td>
                            <td>{$row['total']}</td>
                            <td></td>
                        </tr>";

                        // Actualiza la fecha anterior
                        $previous_date = $row['fecha'];
                    }
                    
                    // Imprime el subtotal de la última fecha
                    if ($current_date !== null) {
                        echo "<tr>
                            <td colspan='7' style='text-align: right; background-color: #41C9E2; color: white;'>Total: </td>
                            <td><strong>$subtotal_fecha</strong></td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>


<?php include_once "ventana.php" ?>

<script src="js/jquery.min.js"></script>

<?php include_once "pie.php" ?> <!-- Incluye el pie de página -->
