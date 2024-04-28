<?php
// Verificar si se proporcionó un ID de categoría en la URL
if (!isset($_GET["id"])) {
    exit("No hay ID de categoría");
}
$id_categoria = $_GET["id"];

// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

// Consultar el nombre de la categoría
$sql_categoria = "SELECT nombre FROM categoria WHERE idcategoria = $id_categoria";
$result_categoria = mysqli_query($conexion, $sql_categoria);
$row_categoria = mysqli_fetch_assoc($result_categoria);

// Consultar los artículos de la categoría específica
$sql_articulos = "SELECT * FROM articulo WHERE idcategoria = $id_categoria";
$result_articulos = mysqli_query($conexion, $sql_articulos);
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
        width: 75px;
        /* Ancho de la columna producto */
        max-width: 75px;
    }

    td.precio,
    th.precio {
        width: 100px;
        /* Ancho de la columna precio */
        max-width: 100px;
        word-break: break-all;
        text-align: center;
    }

    td.codigo,
    th.codigo {
        width: 100px;
        /* Ancho de la columna codigo */
        max-width: 100px;
        word-break: break-all;
        text-align: center;
    }

    td.nombre,
    th.nombre {
        width: 100px;
        /* Ancho de la columna nombre */
        max-width: 100px;
        word-break: break-all;
        text-align: right;
    }

    .centrado {
        text-align: center;
        align-content: center;
    }

    .catalogo {
        width: 700px;
        max-width: 700px;
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


<!-- Contenido del catalogo -->
<div class="catalogo">
    <!-- Encabezado donde se muetra la categoria del catalogo-->
    <h1 class="centrado">Catálogo de <?php echo $row_categoria['nombre']; ?></h1>
    <table>
        <thead>
            <!-- Encabezados de las columnas -->
            <tr>
                <th class="cantidad">CODIGO</th>
                <th class="cantidad">NOMBRE</th>
                <th class="precio">PRECIO VENTA</th>
                <th class="precio">EXISTENCIA</th>
                <th class="precio">DESCRIPCION</th>
                <th class="precio">TALLA</th>
                <th class="precio">MODELO</th>
            </tr>
        </thead>
        <tbody>

            <?php
            // Iterar sobre los artículos de la categoría y mostrarlos en tarjetas
            while ($row_articulo = mysqli_fetch_assoc($result_articulos)) {
            ?>
                <!-- Filas de la tabla para cada articulo -->
                <tr>
                    <td class="codigo"><?php echo $row_articulo['codigo']; ?></td>
                    <td class="nombre"><?php echo $row_articulo['nombre']; ?></td>
                    <td class="precio"><?php echo $row_articulo['precio_venta']; ?> <strong></strong></td>
                    <td class="precio"><?php echo $row_articulo['existencia']; ?></td>
                    <td class="nombre"><?php echo $row_articulo['descripcion']; ?></td>
                    <td class="precio"><?php echo $row_articulo['talla']; ?></td>
                    <td class="precio"><?php echo $row_articulo['modelo']; ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    <h1 class="centrado">Contáctanos al 9532100526</h1>
    <h1 class="centrado">Colonia Centro, Heroica Ciudad de Tlaxiaco, Oax </h1>
</div>
</div>

<script>
    // Script para imprimir automáticamente el catalogo y redirigir después de un segundo
    document.addEventListener("DOMContentLoaded", () => {
        window.print(); // Imprimir el catalogo
        setTimeout(() => {
            window.location.href = "./categoria.php"; // Redireccionar a la página de compras después de un segundo
        }, 1000);
    });
</script>