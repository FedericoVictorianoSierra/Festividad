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

?>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link href="css/pagoEfectivo.css" media="all" rel="stylesheet" type="text/css">
</head>

<body>
  <div class="opps">
    <div class="opps-header">
      <div class="opps-info">
        <h3>Lista de Compras:</h3>
        <ol>
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
            <!--Datos que se agregan al comprobante a pagar con efectivo-->
            <li>
              <h3><?php echo $articulo['nombre']; ?></h3>
              <p><?php echo $articulo['descripcion']; ?></p>
              <p>$<?php echo $articulo['precio_venta']; ?> (<?php echo $datos1['cantidad']; ?>)</p><br>
            </li>
          <?php endforeach; ?>
        </ol>
      </div>

      <div class="opps-header">
        <div class="opps-reminder">Ficha digital. No es necesario imprimir.</div>
        <div class="opps-info">
          <div class="opps-brand"><img src="./img/oxxopay_brand.png" alt="OXXOPay"></div>
          <div class="opps-ammount">
            <h3>Monto a pagar</h3>
            <!-- Mostrar el total -->
            <h2>$<?php echo $total; ?> <sup>MXN</sup></h2>
            <p>OXXO cobrará una comisión adicional al momento de realizar el pago.</p>
          </div>
        </div>
        <div class="opps-reference">
          <h3>Referencia</h3>
          <h1>0000-0000-0000-00<?php echo $idusuario; ?></h1> <!-- Usar el ID del usuario como referencia -->
        </div>
      </div>
      <div class="opps-instructions">
        <h3>Instrucciones</h3>
        <ol>
          <li>Acude a la tienda OXXO más cercana. <a href="https://www.google.com.mx/maps/search/oxxo/" target="_blank">Encuéntrala aquí</a>.</li>
          <li>Indica en caja que quieres realizar un pago de servicio<strong></strong>.</li>
          <li>Dicta al cajero el número de referencia en esta ficha para que tecleé directamete en la pantalla de venta.</li>
          <li>Realiza el pago correspondiente con dinero en efectivo.</li>
          <li>Al confirmar tu pago, el cajero te entregará un comprobante impreso. <strong>En el podrás verificar que se haya realizado correctamente.</strong> Conserva este comprobante de pago.</li>
        </ol>
        <div class="opps-footnote">Al completar estos pasos recibirás un correo de <strong>Nombre del negocio</strong> confirmando tu pago.</div>
        <br>
		<button onclick="window.print()">Imprimir</button> <!-- Botón para imprimir -->
        <button onclick="window.location.href='compras.php'">Ir al apartado de ventas</button> <!-- Botón para ir al apartado de compras -->
      </div>
    </div>
  </div>
</body>
