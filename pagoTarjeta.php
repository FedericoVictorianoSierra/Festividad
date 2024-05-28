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

// Realizar consulta para obtener información del usuario a partir de su ID de usuario
$query = "SELECT * FROM usuario WHERE idusuario = ?";
$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "i", $idusuario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 1) {
  $row = mysqli_fetch_assoc($result);
  $nombreusuario = $row['nombreUsuario'];
  $nombre = $row['nombre'];
}

// Obtener los registros del carrito del usuario
$query_carrito = "SELECT * FROM carrito WHERE idusuario = ?";
$stmt_carrito = mysqli_prepare($conexion, $query_carrito);
mysqli_stmt_bind_param($stmt_carrito, "i", $idusuario);
mysqli_stmt_execute($stmt_carrito);
$result_carrito = mysqli_stmt_get_result($stmt_carrito);

$datos = array(); // Inicializar un array para almacenar los datos del carrito
while ($resultado = mysqli_fetch_array($result_carrito)) {
  // Obtener el carrito activo del usuario
  $carrito_id = $resultado['idcarrito'];
  $sqlv = "SELECT * FROM carrito WHERE idcarrito = ? AND activo = 1";
  $stmt_v = mysqli_prepare($conexion, $sqlv);
  mysqli_stmt_bind_param($stmt_v, "i", $carrito_id);
  mysqli_stmt_execute($stmt_v);
  $result_v = mysqli_stmt_get_result($stmt_v);

  // Verificar si el carrito está activo
  if (mysqli_num_rows($result_v) > 0) {
    $datos[] = $resultado; // Almacenar los registros en el array $datos
  }
}

// Calcular el total del carrito
$total = 0;
foreach ($datos as $datos1) {
  $sql = "SELECT * FROM articulo WHERE idarticulo = ?";
  $stmt_articulo = mysqli_prepare($conexion, $sql);
  mysqli_stmt_bind_param($stmt_articulo, "i", $datos1['idarticulo']);
  mysqli_stmt_execute($stmt_articulo);
  $result_articulo = mysqli_stmt_get_result($stmt_articulo);
  $articulo = mysqli_fetch_assoc($result_articulo);
  $total += $articulo['precio_venta'] * $datos1['cantidad'];
}

// Procesar la compra si se ha enviado el formulario
if (isset($_POST['accion']) && $_POST['accion'] == 'comprar') {
  // Obtener la fecha actual
  date_default_timezone_set('America/Mexico_City');
  $fecha = date('Y-m-d H:i:s');

  // Insertar cada carrito en la tabla "venta"
  foreach ($datos as $datos1) {
    $sql = "SELECT * FROM articulo WHERE idarticulo = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $datos1['idarticulo']);
    mysqli_stmt_execute($stmt);
    $result_articulo = mysqli_stmt_get_result($stmt);
    $articulo = mysqli_fetch_assoc($result_articulo);
    $precio_total = $articulo['precio_venta'] * $datos1['cantidad'];

    // Establecer el valor nulo para el parámetro "impuesto"
    $impuesto = 0;

    $sql_detalle_venta = "INSERT INTO venta(idusuario, fecha, impuesto, total, idcarrito) VALUES (?, ?, ?, ?, ?)";
    $stmt_venta = mysqli_prepare($conexion, $sql_detalle_venta);
    mysqli_stmt_bind_param($stmt_venta, "isdsi", $datos1['idusuario'], $fecha, $impuesto, $precio_total, $datos1['idcarrito']);
    mysqli_stmt_execute($stmt_venta);

    // Actualizar existencia del artículo
    $nueva_existencia = $articulo['existencia'] - $datos1['cantidad'];
    $sql_actualizar_existencia = "UPDATE articulo SET existencia = ? WHERE idarticulo = ?";
    $stmt_actualizar_existencia = mysqli_prepare($conexion, $sql_actualizar_existencia);
    mysqli_stmt_bind_param($stmt_actualizar_existencia, "ii", $nueva_existencia, $datos1['idarticulo']);
    mysqli_stmt_execute($stmt_actualizar_existencia);
  }

  // Eliminar los registros del carrito
  foreach ($datos as $datos1) {
    $sqlx = "UPDATE carrito SET activo='0' WHERE idcarrito = ?";
    $stmtx = mysqli_prepare($conexion, $sqlx);
    mysqli_stmt_bind_param($stmtx, "i", $datos1['idcarrito']);
    mysqli_stmt_execute($stmtx);
  }

  // Mostrar ventana de alerta
  echo "<script>alert('Compra exitosa');</script>";

  // Redirigir al usuario a una página de confirmación de compra
  echo "<script>location.href='compras.php';</script>";

  exit();
}

?>

<head>
  <link href="css/pagoTarjeta.css" rel="stylesheet">
</head>

<div class="container">
  <div class="card">
    <form action="" method="post" onsubmit="return validarFormulario()">
      <input type="hidden" name="accion" value="comprar">
      <button class="proceed" type="submit">
        <svg class="sendicon" width="24" height="24" viewBox="0 0 24 24">
          <path d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z"></path>
        </svg>
      </button>

      <img src="./img/VISA-logo.png" class="logo-card">
      <label>Número de tarjeta:</label>
      <input type="number" id="user" class="input cardnumber" placeholder="1234 5678 9101 1121" minlength="16" maxlength="16" onkeypress="return soloNumeros(event)" required>
      <label>Nombre:</label>
      <input id="nombre" class="input name" value='<?php echo $nombre ?>' type="text" onkeypress="return soloLetras(event)" required>
      <label class="toleft">CCV:</label>
      <input type="number" id="ccv" class="input toleft ccv" placeholder="321" minlength="3" maxlength ="3" onkeypress="return soloNumeros(event)" required>
    </form>
  </div>

  <div class="receipt">
    <!-- Información del recibo -->
    <div class="col">
      <p>Costo Total a Pagar:</p>
      <!-- Mostrar el total a pagar -->
      <h2 class="cost">$<?php echo $total; ?></h2><br>
      <p>Nombre:</p>
      <!-- Mostrar el nombre del usuario -->
      <h2 class="seller"><?php echo $nombreusuario ?></h2>
    </div>
    <!-- Lista de artículos comprados -->
    <div class="col">
      <p>Lista de compra:</p>
      <?php foreach ($datos as $datos1) : ?>
        <?php
        // Consultar información del artículo
        $sql = "SELECT * FROM articulo WHERE idarticulo = ?";
        $stmt_articulo = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt_articulo, "i", $datos1['idarticulo']);
        mysqli_stmt_execute($stmt_articulo);
        $result_articulo = mysqli_stmt_get_result($stmt_articulo);
        $articulo = mysqli_fetch_assoc($result_articulo);
        ?>
        <!-- Mostrar nombre del artículo -->
        <h3 class="bought-items"><?php echo $articulo['nombre']; ?></h3>
        <!-- Mostrar descripción del artículo -->
        <p class="bought-items description"><?php echo $articulo['descripcion']; ?></p>
        <!-- Mostrar precio del artículo -->
        <p class="bought-items price">$<?php echo $articulo['precio_venta']; ?></p><br>
      <?php endforeach; ?>
    </div>
    <br>
    <!-- Información sobre el envío del recibo por correo electrónico -->
    <p class="comprobe">Esta información será enviada por e-mail</p>
    <br>
    <br>
    <br>
  </div>
</div>
<script src="js/validacion.js"></script>

