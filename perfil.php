<?php
$idusuario = "";
include 'conexion.php';

// Realizar consulta para obtener información del usuario a partir de su ID de usuario
$query = "SELECT * FROM usuario WHERE idusuario = $idusuario";
$result = mysqli_query($conexion, $query);
if (mysqli_num_rows($result) == 1) { // Verificar si la consulta devolvió exactamente una fila de resultados.
    $row = mysqli_fetch_assoc($result); // Almacenar los resultados en un arreglo

    // Almacenar la información del usuario en variables
    $nombreusuario = $row['nombreUsuario'];
    $Nombre = $row['nombre'];
    $apellidoM = $row['apellidoMat'];
    $apellidoP = $row['apellidoPat'];
    $Telefono = $row['telefono'];
    $Email = $row['email'];
    $iddireccion = $row['iddireccion'];

    // Realizar consulta para obtener la dirección asociada al ID de dirección del usuario
    $query_direccion = "SELECT pais, estado, ciudad, colonia, calle, numcalle, codigopostal FROM direccion WHERE iddireccion = " . $iddireccion;
    $result_direccion = mysqli_query($conexion, $query_direccion);
    $row_direccion = mysqli_fetch_assoc($result_direccion);

    // Almacenar la información de la dirección en variables
    $Pais = $row_direccion['pais'];
    $Estado = $row_direccion['estado'];
    $Ciudad = $row_direccion['ciudad'];
    $CodigoP = $row_direccion['codigopostal'];

    // Realizar consulta para obtener la imagen asociada al usuario
    $query_imagen = "SELECT Tipo, nombre, imagen FROM img WHERE id_imagen = " . $row['id_imagen'];
    $result_imagen = mysqli_query($conexion, $query_imagen);
    $row_imagen = mysqli_fetch_assoc($result_imagen);

    // Almacenar la imagen en formato base64 para mostrarla en la página
    $imagen_base64 = "data:" . $row_imagen['Tipo'] . ";base64," . base64_encode($row_imagen['imagen']);

    // Realizar consulta para obtener el rol asociada al usuario
    $query_rol = "SELECT nombre FROM rol WHERE idrol= " . $row['idrol'];
    $result_rol = mysqli_query($conexion, $query_rol);
    $row_rol = mysqli_fetch_assoc($result_rol);

    $rolnombre = $row_rol['nombre'];
}

?>
<?php include_once "encabezado.php" ?>

<section class="seccion-perfil-usuario">
    <div class="perfil-usuario-header">
        <div class="perfil-usuario-portada">
            <div class="perfil-usuario-avatar">
                <div class="imagen-avatar">
                    <img src="<?php echo $imagen_base64; ?>" alt="img-avatar">
                </div>
            </div>

        </div>
    </div>
    <div class="perfil-usuario-body">
        <div class="perfil-usuario-bio">
            <h3 class="titulo">Nombre del Usuario: <br> <?php echo $nombreusuario; ?></h3>
            <p class="texto">Descripción del perfil: <?php echo $rolnombre ?></p>
        </div>
        <div class="perfil-usuario-footer">
            <ul class="lista-datos">
                <li><i class="icono fas fa-map-signs"></i> Nombre: <?php echo $Nombre ?></li>
                <!--<li><i class="icono fas fa-user-check"></i> Apellido Paterno:--> <?php /*echo $apellidoP*/ ?></li>
                <!--<li><i class="icono fas fa-user-check"></i> Apellido Materno:--> <?php /*echo $apellidoM*/ ?></li>
                <li><i class="icono fas fa-phone-alt"></i> Telefono: <?php echo $Telefono ?></li>
                <li><i class="icono fas fa-briefcase"></i> Email: <?php echo $Email ?></li>
            </ul>
            <ul class="lista-datos">
                <!--<li><i class="icono fas fa-map-marker-alt"></i> País:--> <?php /*echo $Pais*/ ?></li>
                <li><i class="icono fas fa-map-marker-alt"></i> Estado: <?php echo $Estado ?></li>
                <li><i class="icono fas fa-map-marker-alt"></i> Ciudad: <?php echo $Ciudad ?></li>
                <li><i class="icono fas fa-map-marker-alt"></i> Codigo Postal: <?php echo $CodigoP ?></li>
                <form method='post'>
                    <li>
                        <a class="btn btn-warning" href="actualizarperfil.php"><i class="fa fa-edit"></i></a>
                    </li>
                </form>


            </ul>
        </div>
        <!-- Redes Sociales
        <div class="redes-sociales">
            <a href="" class="boton-redes facebook fab fa-facebook-f"><i class="icon-facebook"></i></a>
            <a href="" class="boton-redes twitter fab fa-twitter"><i class="icon-twitter"></i></a>
            <a href="" class="boton-redes instagram fab fa-instagram"><i class="icon-instagram"></i></a>
        </div>
        -->
    </div>
</section>

<!-- Redes Sociales
<div class="mis-redes" style="display: block;position: fixed;bottom: 1rem;left: 1rem; opacity: 0.5; z-index: 1000;">
    <p style="font-size: .75rem;">Ropa típica</p>
    <div>
        <a target="_blank" href="https://www.facebook.com/ApockGraficos"><i class="fab fa-facebook-square"></i></a>
        <a target="_blank" href="https://twitter.com/ApockGraficos"><i class="fab fa-twitter"></i></a>
        <a target="_blank" href="https://www.instagram.com/ApockGraficos"><i class="fab fa-instagram"></i></a>
        <a target="_blank" href="https://www.youtube.com/channel/UC15DkMZQ80aoW_Rqk4n2T_w"><i class="fab fa-youtube"></i></a>
    </div>
</div>
-->

<?php include_once "pie.php" ?>
<?php include_once "ventana.php" ?>