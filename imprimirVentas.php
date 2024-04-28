<?php
// Include the database connection file
include 'conexion.php';

// Obtener la fecha seleccionada de la URL
$fecha_seleccionada = isset($_GET['fecha']) ? $_GET['fecha'] : '';

// Set up SQL query to select sales data for the specified date
$sql = "SELECT v.fecha, u.nombre AS usuario, a.codigo, a.nombre AS articulo, a.descripcion, c.cantidad, v.total 
        FROM venta v
        INNER JOIN carrito c ON v.idcarrito = c.idcarrito
        INNER JOIN articulo a ON c.idarticulo = a.idarticulo
        INNER JOIN usuario u ON v.idusuario = u.idusuario
        WHERE DATE(v.fecha) = '$fecha_seleccionada'";
$result = mysqli_query($conexion, $sql);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas Ticket</title>
</head>

<body>
    <div class="ticket">
        <h1 class="centrado">VENTAS</h1>
        <table>
            <thead>
                <tr>
                    <th>Fecha de compra</th>
                    <th>Usuario</th>
                    <th>Código</th>
                    <th>Artículo</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['fecha']; ?></td>
                        <td><?php echo $row['usuario']; ?></td>
                        <td><?php echo $row['codigo']; ?></td>
                        <td><?php echo $row['articulo']; ?></td>
                        <td><?php echo $row['descripcion']; ?></td>
                        <td><?php echo $row['cantidad']; ?></td>
                        <td><?php echo "$" . $row['total']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
        // JavaScript for printing ticket and redirecting after a second
        document.addEventListener("DOMContentLoaded", () => {
            window.print(); // Print the ticket
            setTimeout(() => {
                window.location.href = "./ventas.php"; // Redirect to sales page after a second
            }, 1000);
        });
    </script>
</body>

</html>