<?php
session_start(); // Iniciar sesión 
include 'php_con/db.php'; // Incluir el archivo de conexión a la base de datos
$conexion = conexion(); // Establecer la conexión a la base de datos

if (isset($_SESSION['idusuario'])) {
    // El ID del usuario está disponible en $_SESSION['idusuario']
    $idusuario = $_SESSION['idusuario'];

    // Obtener el valor de idrol del usuario actual desde la tabla usuario
    $query_usuario = "SELECT idrol FROM usuario WHERE idusuario = $idusuario";
    $resultado_usuario = mysqli_query($conexion, $query_usuario);
    $fila_usuario = mysqli_fetch_assoc($resultado_usuario);
    $idrol = $fila_usuario['idrol'];
}

?>

<html>

<head>
    <meta charset="utf-8">
    <title>ROPA TIPICA</title>
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

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.min.css" rel="stylesheet">
    <link href="css/cookies.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar Start -->
    <div class="container-fluid p-0 nav-bar">
        <nav class="navbar navbar-expand-lg bg-none navbar-dark py-3">
            <a href="index.php" class="navbar-brand px-lg-4 m-0">
                <h1 class="m-0 display-4 text-uppercase text-white">FESTIVIDAD 3.0</h1>
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                <div class="navbar-nav ml-auto p-4">
                    <a href="index.php" class="nav-item nav-link active">INICIO</a>
                    <a href="categoria.php" class="nav-item nav-link">CATEGORÍA</a>
                    <a href="articulos.php" class="nav-item nav-link">PRODUCTOS Y SERVICIOS</a>

                    <?php
                    if (isset($_SESSION['idusuario'])) {
                        if ($idrol == 1) { ?>
                            <a href="carrito.php" class="nav-item nav-link">CARRITO</a>
                            <a href="compras.php" class="nav-item nav-link">COMPRAS</a>
                        <?php } else { ?>
                            <a href="ventas.php" class="nav-item nav-link">VENDIDO</a>
                        <?php }
                    } else { ?>
                        <a href="carrito.php" class="nav-item nav-link">CARRITO</a>
                    <?php } ?>

                    <a href="perfil.php" class="nav-item nav-link">PERFIL</a>

                    <?php
                    if (!isset($_SESSION['idusuario'])) {
                        //iniciar sesión
                    ?>
                        <a href="cerrarsesion.php" class="nav-item nav-link">INICIAR SESIÓN</a>
                    <?php
                    } else {
                        //cerrar sesión
                    ?>
                        <a href="cerrarsesion.php" class="nav-item nav-link">CERRAR SESIÓN</a>
                    <?php
                    }
                    ?>

                    <div class="nav-item dropdown">
                        <!-- <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Pages</a> -->
                        <div class="dropdown-menu text-capitalize">
                            <a href="reservation.php" class="dropdown-item">Reservation</a>
                            <a href="testimonial.php" class="dropdown-item">Testimonial</a>
                        </div>
                    </div>
                    <!-- <a href="contact.php" class="nav-item nav-link">Contact</a> -->
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Carousel Start -->
    <div class="container-fluid p-0 mb-5">
        <div id="blog-carousel" class="carousel slide overlay-bottom" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="w-100" src="img/Tlaxiaco.jpg" alt="Image">
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <h2 class="text-primary font-weight-medium m-0">VISTE LA TRADICIÓN</h2>
                        <h1 class="display-1 text-white m-0">FESTIVIDAD 3.0</h1>
                        <h2 class="text-white m-0">CONECTA CON LA CULTURA</h2>
                    </div>
                </div>

                <div class="carousel-item ">
                    <img class="w-100" src="img/putla.jpg" alt="Image">
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <h2 class="text-primary font-weight-medium m-0">UNIENDO MANOS</h2>
                        <h1 class="display-1 text-white m-0">FESTIVIDAD 3.0</h1>
                        <h2 class="text-white m-0">TEJIENDO TRADICIONES</h2>
                    </div>


                </div>
            </div>
            <a class="carousel-control-prev" href="#blog-carousel" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>
            <a class="carousel-control-next" href="#blog-carousel" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>
            <a class="carousel-control-next" href="#blog-carousel" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>
        </div>
    </div>
    <!-- Carousel End -->


    <!-- About Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="section-title">
                <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">FESTIVIDAD 3.0</h4>
                <h1 class="display-4">UNIENDO MANOS</h1>
            </div>
            <div class="row">
                <div class="col-lg-4 py-0 py-lg-5">
                    <h1 class="mb-3">RAÍCES Y CORAZÓN</h1>
                    <h5 class="mb-3">Conoce un poco de tu consumo</h5>
                    <p>Haz clic en 'Comprar ahora' para adquirir estas piezas únicas de la Mixteca Oaxaqueña y contribuir al crecimiento y desarrollo de las comunidades artesanales locales.</p>
                    <a href="articulos.php" class="btn btn-secondary font-weight-bold py-2 px-4 mt-2">COMPRAR AHORA</a>
                </div>
                <div class="col-lg-4 py-5 py-lg-0" style="min-height: 500px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute w-100 h-100" src="img/about.jpg" style="object-fit: cover;">
                    </div>
                </div>

                <div class="col-lg-4 py-0 py-lg-5">
                    <h1 class="mb-3">MODA CON ALMA Y CORAZÓN</h1>
                    <p> Dirígete al carrito para completar tu compra y apoyar a los talentosos artesanos de la Mixteca Oaxaqueña.</p>

                    <?php
                    if ($idrol == 1) { ?>
                        <a href="carrito.php" class="btn btn-primary font-weight-bold py-2 px-4 mt-2">CARRITO</a>
                    <?php  } ?>
                    
                </div>

            </div>
        </div>
    </div>
    <!-- About End -->




    <!-- Offer Start -->
    <div class="offer container-fluid my-5 py-5 text-center position-relative overlay-top overlay-bottom">
        <div class="container py-5">
            <h1 class="display-3 text-primary mt-3">Bienvenido</h1>
            <h1 class="text-white mb-3">FESTIVIDAD 3.0</h1>
            <h4 class="text-white font-weight-normal mb-4 pb-3">http://FESTIVIDAD3.0.rf.gd/</h4>
            <!-- <form class="form-inline justify-content-center mb-4"> -->
            <!-- <div class="input-group">
                    <input type="text" class="form-control p-4" placeholder="Your Email" style="height: 30px;"> -->
            <!-- <div class="input-group-append">
                        <button class="btn btn-primary font-weight-bold px-4" type="submit">Sign Up</button>
                    </div> -->
        </div>
        </form>
    </div>
    </div>
    <!-- Offer End -->




    <!-- Footer Start -->
    <div class="container-fluid footer text-white mt-5 pt-5 px-0 position-relative overlay-top">
        <div class="row mx-0 pt-5 px-sm-3 px-lg-5 mt-4">
            <div class="col-lg-3 col-md-6 mb-5">
                <h4 class="text-white text-uppercase mb-4" style="letter-spacing: 3px;">INFORMACIÓN</h4>
                <p><i class="fa fa-map-marker-alt mr-2"></i>#23 DE CALLE ALDAMA TLAXIACO.OAXACA</p>
                <p><i class="fa fa-phone-alt mr-2"></i>953-975-6822</p>
                <p class="m-0"><i class="fa fa-envelope mr-2"></i>FESTIVIDAD3.0@gmail.com</p>
            </div>
            <div class="col-lg-3 col-md-6 mb-5">
                <h4 class="text-white text-uppercase mb-4" style="letter-spacing: 3px;">HORARIO DE ATENCION</h4>
                <div>
                    <h6 class="text-white text-uppercase">LUNES - VIERNES</h6>
                    <p>8.00 AM - 8.00 PM</p>
                    <h6 class="text-white text-uppercase">SABADO - DOMINGO</h6>
                    <p>2.00 PM - 8.00 PM</p>
                </div>
            </div>
        </div>
        <div class="container-fluid text-center text-white border-top mt-4 py-4 px-sm-3 px-md-5" style="border-color: rgba(256, 256, 256, .1) !important;">
            <p class="mb-2 text-white">FESTIVIDAD 3.0 &copy; <a class="font-weight-bold" href="#">COM</a>BORDADOS DEL CORAZON</a></p>
            <p class="m-0 text-white">EL BORDADO QUE NECESITAS <a class="font-weight-bold" href="#">LA MIXTEQUITA.COM</a></p>
        </div>
    </div>
    <!-- Footer End -->



    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script src="js/cookies.js"></script>
</body>