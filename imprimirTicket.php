<?php
// Verificar si se proporcionó un ID en la URL
if (!isset($_GET["id"])) {
    exit("No hay id");
}
$id = $_GET["id"];

// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

// Consultar la información de la venta específica
$sql = "SELECT idusuario, idcarrito, fecha, impuesto, total FROM venta WHERE idventa='$id'";
$result = mysqli_query($conexion, $sql);
$row = mysqli_fetch_assoc($result);

// Obtener la fecha y hora exacta para filtrar los tickets de la misma venta
$fecha_exacta = $row['fecha'];

// Extraer la parte de la fecha y hora exacta para filtrar los tickets
list($fecha, $hora) = explode(' ', $fecha_exacta);
$fecha_hora_exacta = $fecha . ' ' . substr($hora, 0, 8);

// Consulta para obtener todos los tickets con la misma fecha, hora y segundos
$sql_tickets = "SELECT idventa, idusuario, idcarrito, fecha, impuesto, total FROM venta WHERE fecha LIKE '%$fecha_hora_exacta%'";
$result_tickets = mysqli_query($conexion, $sql_tickets);
?>

<style>
    * {
        font-size: 12px;
        font-family: 'Times New Roman';
    }

    td,
    th,
    tr,
    table {
        border-top: 1px solid black;
        border-collapse: collapse;
    }

    td.producto,
    th.producto {
        width: 75px; /* Ancho de la columna PRODUCTO */
        max-width: 75px;
    }

    td.cantidad,
    th.cantidad {
        width: 70px; /* Ancho de la columna CANTIDAD */
        max-width: 70px;
        word-break: break-all;
        text-align: center;
    }

    td.precio,
    th.precio {
        width: 150px; /* Ancho de la columna TOTAL */
        max-width: 150px;
        word-break: break-all;
        text-align: right;
    }

    .centrado {
        text-align: center;
        align-content: center;
    }

    .ticket {
        width: 380px;
        max-width: 380px;
    }

    img {
        max-width: inherit;
        width: inherit;
    }

    @media print {

        .oculto-impresion,
        .oculto-impresion * {
            display: none !important;
        }
    }
</style>
<div class="ticket">
    <!-- Encabezado del ticket -->
    <p class="centrado">TICKET DE VENTA
        <br><?php echo $row['fecha']; ?>
    </p>
    <table>
        <thead>
            <!-- Encabezados de las columnas -->
            <tr>
                <th class="cantidad">CANT</th> <!-- Cantidad de productos -->
                <th class="producto">PRODUCTO</th> <!-- Nombre del producto -->
                <th class="precio">PRECIO UNITARIO</th> <!-- Precio unitario de cada producto -->
                <th class="precio">IMPORTE</th> <!-- Importe total por producto -->
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_venta = 0; // Inicializamos la variable para la suma total de la venta
            // Recorremos cada ticket de la venta
            while ($row_ticket = mysqli_fetch_assoc($result_tickets)) : ?>
                <?php
                // Consultar los detalles del carrito para cada ticket
                $idcarrito = $row_ticket['idcarrito'];
                $sqlcarrito = "SELECT * FROM carrito WHERE idcarrito = $idcarrito";
                $resultadocarrito = mysqli_query($conexion, $sqlcarrito);
                // Recorremos los productos del carrito para cada ticket
                while ($rowcarrito = mysqli_fetch_assoc($resultadocarrito)) {
                    // Consultar información detallada de cada producto
                    $idarticulo = $rowcarrito['idarticulo'];
                    $sqlarticulo = "SELECT * FROM articulo WHERE idarticulo = $idarticulo";
                    $resultadoarticulo = mysqli_query($conexion, $sqlarticulo);
                    $rowarticulo = mysqli_fetch_assoc($resultadoarticulo);
                    
                    // Calcular el total por artículo y sumarlo al total de la venta
                    $total_articulo = $rowcarrito['cantidad'] * $rowarticulo['precio_venta'];
                    $total_venta += $total_articulo;
                ?>
                    <!-- Filas de la tabla para cada producto del carrito -->
                    <tr>
                        <td class="cantidad"><?php echo $rowcarrito['cantidad']; ?></td>
                        <td class="producto"><?php echo $rowarticulo['nombre'] ?> <strong></strong></td>
                        <td class="precio">$<?php echo number_format($rowarticulo['precio_venta'], 2) ?></td>
                        <td class="precio">$<?php echo number_format($total_articulo, 2) ?></td>
                    </tr>
                <?php } ?>
            <?php endwhile; ?>
            <!-- Fila para mostrar el total neto de la venta -->
            <tr>
                <td colspan="3" style="text-align: right;">TOTAL NETO: </td>
                <td class="precio">
                    <strong>$<?php echo number_format($total_venta, 2) ?></strong>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- Mensaje de agradecimiento en el pie del ticket -->
    <p class="centrado">¡GRACIAS POR SU COMPRA!</p>
</div>
</body>

<script>
    // Script para imprimir automáticamente el ticket y redirigir después de un segundo
    document.addEventListener("DOMContentLoaded", () => {
        window.print(); // Imprimir el ticket
        setTimeout(() => {
            window.location.href = "./compras.php"; // Redireccionar a la página de compras después de un segundo
        }, 1000);
    });
</script>