<?php
$idusuario = "";
include 'conexion.php';

// Verificar si se ha enviado el formulario
if (isset($_POST['actualizar'])) {

    // Obtener los valores del formulario
    $nombre = $_POST['nombre'];
    $nombreUsuario = $_POST['nombreUsuario'];
    $password = $_POST['password'];
    $correo = $_POST['correo'];

    if (isset($_FILES['foto_']) && !empty($_FILES['foto_']['tmp_name'])) { // Se comprueba que se haya subido una foto
        $nombreimg = basename($_FILES['foto_']['name']); // Se obtiene el nombre de la imagen
        $imagen = addslashes(file_get_contents($_FILES['foto_']['tmp_name'])); // Se obtiene el contenido binario de la imagen
        $tip = exif_imagetype($_FILES['foto_']['tmp_name']); //Se obtiene el tipo de imagen
        $extension = image_type_to_extension($tip); // Se obtiene la extensión de la imagen

        // Consulta SQL para recuperar los datos de usuario
        $usuario_query = "SELECT * FROM usuario WHERE idusuario='$idusuario'";
        // Ejecutar la consulta
        $resultado_usuario = mysqli_query($conexion, $usuario_query);
        // Obtener los datos de usuario
        $usuario = mysqli_fetch_assoc($resultado_usuario);

        /// Se actualiza la imagen del usuario en la tabla img
        $query_imagen = "UPDATE img SET nombre='$nombreimg',imagen='$imagen', Tipo='$extension' WHERE Id_imagen='$usuario[id_imagen]'";
        mysqli_query($conexion, $query_imagen);
        $idimagen = mysqli_insert_id($conexion);

        // Actualizar los datos en la tabla usuario, incluyendo el nuevo id_imagen
        $sql = "UPDATE usuario SET nombre='$nombre', nombreUsuario='$nombreUsuario', password='$password', email='$correo' WHERE idusuario='$idusuario'";
    } else {
        // Actualizar los datos en la tabla usuario sin actualizar la imagen
        $sql = "UPDATE usuario SET nombre='$nombre', nombreUsuario='$nombreUsuario' , password='$password', email='$correo' WHERE idusuario='$idusuario'";
    }

    if (mysqli_query($conexion, $sql)) {
        $textoModal = "Los datos se han actualizado correctamente..";
        $mostrarModal = true;
        $nombreArchivo = "perfil.php";
    } else {
        $textoModal = "Error al actualizar los datos: " . mysqli_error($conexion);
        $mostrarModal = true;
        $nombreArchivo = "actualizarperfil.php";
    }
}


?>


<?php include_once "encabezado.php" ?>
<!-- Page Header Start -->
<div class="container-fluid page-header mb-4 position-relative overlay-bottom">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
        <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">ACTUALIZAR PERFIL</h1>

    </div>
</div>
</div>
<!-- Page Header End -->
<?php

// Consulta SQL para recuperar los datos de usuario
$usuario_query = "SELECT * FROM usuario WHERE idusuario='$idusuario'";

// Ejecutar la consulta
$resultado_usuario = mysqli_query($conexion, $usuario_query);

// Verificar si se encontró el usuario
if (!$resultado_usuario) {
    echo "Error al recuperar usuario: " . mysqli_error($conexion);
    exit();
}

// Obtener los datos de usuario
$usuario = mysqli_fetch_assoc($resultado_usuario);

// Consulta SQL para recuperar rol
$rol_query = "SELECT idrol, nombre FROM rol";

// Ejecutar la consulta
$resultado_rol = mysqli_query($conexion, $rol_query);

// Verificar si se encontraron rol
if (!$resultado_rol) {
    echo "Error al recuperar rol: " . mysqli_error($conexion);
    exit();
}
?>
<div class="container-fluid py-5">
    <div class="container">
        <form action="" method="POST" enctype="multipart/form-data">

            <br>
            <label for="nombre">Nombre:</label>
            <input class="px-4 me-sm-3" minlength="8" maxlength="25" type="text" name="nombre" id="nombre" value="<?php echo $usuario['nombre']; ?>" required="required">
            <br>
            <label for="nombreUsuario">Nombre usuario:</label>
            <input name="nombreUsuario" minlength="6" maxlength="25" type="text" id="nombreUsuario" value="<?php echo $usuario['nombreUsuario']; ?>" required="required">
            <br>
            <label for="password">Contraseña:</label>
            <input name="password" minlength="8" maxlength="25" type="password" id="password" value="<?php echo $usuario['password']; ?>" required="required">
            <br> 
            <label for="correo">Correo:</label>
            <input name="correo" type="email" id="correo" value="<?php echo $usuario['email']; ?>" required="required">
            <br>
            <label for="foto_">Foto:</label>
            <input class="px-4 me-sm-3" type="file" name="foto_" id="foto_">
            <br>
            <input class="btn btn-secondary font-weight-bold py-2 px-4 mt-2" type="submit" name="actualizar" onclick="validar(event)" value="Actualizar">
        </form>
    </div>
</div>

<script>
  function validar(evt) {
    var exp = /^[A-Za-z]+\s[A-Za-z]+\s[A-Za-z]+$/;
    var nombre = document.getElementById("nombre").value.trim(); // Elimina espacios en blanco al principio y al final

    if (!exp.test(nombre)) {
      alert("Nombre inválido. Por favor, ingresa un nombre con al menos un nombre y dos apellidos y sin números.");
      evt.preventDefault(); // Solo evita el envío del formulario si el nombre es inválido
    }
  }
</script>
<?php include_once "pie.php" ?>
<?php include_once "ventana.php" ?>