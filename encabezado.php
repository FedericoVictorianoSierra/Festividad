<?php
// El ID del usuario está disponible en $_SESSION['idusuario']
$idusuario = $_SESSION['idusuario'];

// Obtener el valor de idrol del usuario actual desde la tabla usuario
$query_usuario = "SELECT idrol FROM usuario WHERE idusuario = $idusuario";
$resultado_usuario = mysqli_query($conexion, $query_usuario);
$fila_usuario = mysqli_fetch_assoc($resultado_usuario);
$idrol = $fila_usuario['idrol'];

// Consulta SQL para obtener la suma de la cantidad del articulos en el carrito
$query_suma_cantidad = "SELECT SUM(cantidad) AS suma_cantidad FROM carrito WHERE idusuario = $idusuario AND activo = 1";

// Ejecutar la consulta
$resultado_suma_cantidad = mysqli_query($conexion, $query_suma_cantidad);

// Verificar si se obtuvieron resultados
if ($resultado_suma_cantidad) {
    // Obtener el resultado como un array asociativo
    $fila_suma_cantidad = mysqli_fetch_assoc($resultado_suma_cantidad);
    
    // Obtener la suma de la cantidad
    $suma_cantidad = $fila_suma_cantidad['suma_cantidad'];
    
    // Verificar si la suma es NULL (no hay registros que cumplan con la condición)
    if ($suma_cantidad === null) {
        $suma_cantidad = 0; // Si es NULL, asignar cero
    }
} else {
    // Manejar el caso de error en la consulta
    echo "(" . mysqli_error($conexion). ")";
}

?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Festividad 3.0</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free Website Template" name="keywords">
    <meta content="Free Website Template" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;400&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="https://necolas.github.io/normalize.css/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.min.css" rel="stylesheet">
    <link href="css/carrito.css" rel="stylesheet">
    <link href="css/perfil.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar Start -->
    <div class="container-fluid p-0 nav-bar">
        <nav class="navbar navbar-expand-lg bg-none navbar-dark py-3">
            <a href="index.php" class="navbar-brand px-lg-4 m-0">
                <h1 class="m-0 display-4 text-uppercase text-white">Festividad 3.0</h1>
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                <div class="navbar-nav ml-auto p-4">
                    <a href="index.php" class="nav-item nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">INICIO</a>
                    <a href="categoria.php" class="nav-item nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'categoria.php') ? 'active' : ''; ?>">CATEGORÍA</a>
                    <a href="articulos.php" class="nav-item nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'articulos.php') ? 'active' : ''  ?>">PRODUCTOS Y SERVICIOS</a>
                    <?php
                    if ($idrol == 1) { ?>
                        <a href="carrito.php" class="nav-item nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'carrito.php') ? 'active' : ''; ?>">CARRITO (<?php echo $suma_cantidad; ?>)</a>
                        <a href="compras.php" class="nav-item nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'compras.php') ? 'active' : ''; ?>">COMPRAS</a>

                        <?php } else { ?>
                        <a href="ventas.php" class="nav-item nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'ventas.php') ? 'active' : ''; ?>">VENDIDO</a>
                    <?php } ?>
                    <a href="perfil.php" class="nav-item nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'perfil.php') ? 'active' : ''; ?>">PERFIL</a>
                    <a href="cerrarsesion.php" class="nav-item nav-link">CERRAR SESIÓN</a>
                </div>
            </div>
    </div>
    </div>
    </nav>
    </div>