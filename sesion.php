<?php
session_start(); // iniciar sesión


// Verificar si hay una sesión activa
if (isset($_SESSION['idusuario'])) {
  // Hay una sesión activa, Destruir la sesión actual
   session_destroy();
   header("Location: sesion.php");
  exit();
}


// Verificar si se ha dado al botón de iniciar sesión
if (isset($_POST['login'])) {
  // Se incluye la conexión a la base de datos
  require("php_con/db.php");
  $conexion = conexion(); // Se crea la conexión

  // Obtener los datos de entrada de nombre de usuario y contraseña
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Verificar que haya un usuario con contraseña igual al ingresado en la base de datos
  $query = "SELECT * FROM usuario WHERE nombreUsuario='$username' AND password='$password'";
  $result = mysqli_query($conexion, $query);

  if (mysqli_num_rows($result) == 1) { // si hay un resultado obtenido

    //obtener la fila de resultados correspondiente a la consulta 
    $row = mysqli_fetch_assoc($result); // 

    // Guardar el ID del usuario en una variable de sesión
    $_SESSION['idusuario'] = $row['idusuario'];

    // Mostrar ventana de alerta
    echo "<script>alert('Bienvenido " . $row['nombreUsuario'] . "');</script>";

    // Redirigir al usuario a index.php después de mostrar la alerta
    echo "<script>location.href='index.php';</script>";

    exit();
  } else { // si no hay un usuario con dicha contraseña
    $error = "Usuario o password incorrecto";
  }
}
?>

<?php
if (isset($_POST['crear'])) { // Se verifica si se envió el formulario de registro
  include 'php_con/db.php'; // Se incluye el archivo de conexión a la base de datos
  $conexion = conexion(); // Se establece la conexión

  // Se recuperan los valores del formulario de registro
  $nombreUsuario = $_POST['nombreUsuario'];
  $password = $_POST['password'];
  $nombre = $_POST['nombre'];
  $apellidoPat = /*$_POST['apellidoPat'];*/ '';
  $apellidoMat = /*$_POST['apellidoMat'];*/ '';
  $telefono = $_POST['telefono'];
  $email = $_POST['email'];
  $pais = /*$_POST['pais'];*/ '';
  $estado = $_POST['estado'];
  $ciudad = $_POST['ciudad'];
  $colonia = $_POST['colonia'];
  $calle = $_POST['calle'];
  $numcalle = /*$_POST['numcalle'];*/ '';
  $codigopostal = $_POST['codigopostal'];
  $rol = $_POST['roles'];

  if (isset($_FILES['foto_'])) { // Se comprueba que se haya subido una foto
    $nombreimg = basename($_FILES['foto_']['name']); // Se obtiene el nombre de la imagen
    $imagen = addslashes(file_get_contents($_FILES['foto_']['tmp_name'])); // Se obtiene el contenido binario de la imagen
    $tip = exif_imagetype($_FILES['foto_']['tmp_name']); //Se obtiene el tipo de imagen
    $extension = image_type_to_extension($tip); // Se obtiene la extensión de la imagen
  }

  // Consulta para verificar si el nombre de usuario ya existe
  $query = "SELECT * FROM usuario WHERE nombreUsuario = '$nombreUsuario'";
  $resultado = mysqli_query($conexion, $query);

  if (mysqli_num_rows($resultado) > 0) { // Si el nombre de usuario ya existe
    // Se muestra una alerta
    echo "<script>alert('El nombre de usuario ya está en uso. Por favor, elige otro');</script>";
  } else {
    // Si el nombre de usuario no existe, se insertan los datos en la base de datos

    // Se inserta la imagen del usuario en la tabla img
    $query_imagen = "INSERT INTO img(nombre,imagen, Tipo) VALUES ('$nombreimg','$imagen','$extension')";
    mysqli_query($conexion, $query_imagen);
    $idimagen = mysqli_insert_id($conexion);

    // Se inserta la dirección del usuario en la tabla direccion
    $sql_direccion = "INSERT INTO direccion (pais, estado, ciudad, colonia, calle, numcalle, codigopostal) 
                    VALUES ('$pais', '$estado', '$ciudad', '$colonia', '$calle', '$numcalle', '$codigopostal')";
    mysqli_query($conexion, $sql_direccion);
    $iddireccion = mysqli_insert_id($conexion);

    // Se inserta el usuario en la tabla usuario
    $sql_usuario = "INSERT INTO usuario (idrol, nombreUsuario, password, nombre, apellidoMat, apellidoPat, telefono, email, iddireccion, id_imagen) 
                  VALUES ('$rol', '$nombreUsuario', '$password', '$nombre', '$apellidoMat', '$apellidoPat', '$telefono', '$email', '$iddireccion', '$idimagen')";
    $result = mysqli_query($conexion, $sql_usuario);

    if ($result && mysqli_affected_rows($conexion) == 1) { // Si la inserción es exitosa
      $idusuario = mysqli_insert_id($conexion);
      // Guardar el ID del usuario en una variable de sesión
      $_SESSION['idusuario'] = $idusuario;

      // Mostrar ventana de alerta
      echo "<script>alert('Usuario creado exitosamente');</script>";
      echo "<script>alert('Bienvenido " . $nombreUsuario . "');</script>";

      // Redirigir al usuario a index.php después de mostrar la alerta
      echo "<script>location.href='index.php';</script>";
      exit();
    }
  }
}
?>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE-edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bootstrap 4, from LayoutIT!</title>
  <meta name="author" content="Source code generated using layout">
  <meta name="author" content="LayoutIt!">
  <link href="css/sesion.css" rel="stylesheet">

</head>

<body>
  <div class="grid">
    <div class="cube">
      <div class="item">
        <ul class="tabs">
          <li>
            <input id="tab1" type="radio" name="tabs" checked="checked" />
            <label class="nav" for="tab1">Sesión</label>
            <div class="tab-content">
              <?php if (isset($error)) : ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
              <?php endif; ?>
              <form action="" method="POST">
                <label class="frm">Nombre de usuario:</label>
                <input type="text" placeholder="usuario" name="username" required="required" />
                <label class="frm">Contraseña: </label>
                <input type="password" placeholder="contraseña" name="password" required="required" />
                <button id="loginBtn" type="submit" name="login">Iniciar sesión</button>

                <!-- Agregar el enlace para recuperar contraseña -->
                <a href="recuperarDatos.php">Recuperar contraseña</a>

              </form>
            </div>
          </li>

          <li>
            <input id="tab2" type="radio" name="tabs" />
            <label class="nav" for="tab2">Registro</label>
            <div class="tab-content">
              <form action="" method="POST" enctype="multipart/form-data">
                <label class="frm">Nombre de usuario:</label>
                <input type="text" minlength="6" maxlength="25" placeholder="usuario" name="nombreUsuario" required="required">

                <label class="frm">Password:</label>
                <input type="password" minlength="8" maxlength="25" placeholder="contraseña" name="password" required="required">

                <Br>
                <Br>

                <label class="frm">Nombre completo:</label>
                <input type="text" minlength="10" maxlength="128" placeholder="Heidi Lucy Butista Sanjuan" name="nombre" id="nombre" required="required" onkeypress="return soloLetras(event)">

                <!-- 
                <label class="frm">Apellido paterno:</label>
                <input type="text" name="apellidoPat" required="required">

                <label class="frm">Apellido materno:</label>
                <input type="text" name="apellidoMat" required="required">
                -->

                <label class="frm">Teléfono:</label>
                <input type="number" placeholder="953 123 45 67" name="telefono" required="required" minlength="10" maxlength="10" onkeypress="return soloNumeros(event)">

                <label class="frm">E-mail:</label>
                <input type="email" placeholder="usuario@gmail.com" name="email" required="required">

                <!-- 
                <label class="frm">Pais:</label>
                <input type="text" name="pais" required="required">
                -->

                <label>Estado:</label>
                <select id="estado" name="estado" required>
                  <!-- Opciones de estado se cargarán dinámicamente -->
                </select>

                <label>Municipio:</label>
                <select id="municipio" name="ciudad" required>
                  <!-- Opciones de ciudad se cargarán dinámicamente -->
                </select>

                <label class="frm">Colonia:</label>
                <input type="text" placeholder="Colonia Falsa" minlength="6" maxlength="128" name="colonia" required="required" onkeypress="return soloLetras(event)">

                <label class="frm">Calle:</label>
                <input type="text" placeholder="Calle Falsa 123" minlength="6" maxlength="128" name="calle" required="required">

                <!--
                <label class="frm">Número:</label>
                <input type="number" name="numcalle" required="required">
                -->

                <label class="frm">Código postal:</label>
                <input type="number" minlength="5" maxlength="5" placeholder="69000" name="codigopostal" required="required" maxlength="5" onkeypress="return soloNumeros(event)">

                <label>Rol:</label>
                <select name="roles">
                  <option value="1">Usuario Cliente</option>
                  <option value="2">Usuario Proveedor</option>
                </select>

                <br>

                <label>Foto:</label>
                <input type="file" name="foto_" id="foto" required="required" accept="image/png, image/jpeg, image/jpg">

                <input id="loginBtn" onclick="validar(event)" type="submit" value="Registrar usuario" name="crear">
              </form>
            </div>
          </li>
        </ul>
      </div>
      <script src="js/jquery.min.js"></script>

      <!--Selecctor de municipio y ciudad -->
      <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
      <script type="text/javascript" src="js/municipios.js"></script>
      <script type="text/javascript" src="js/select_estados.js"></script>
      <script type="text/javascript">
        $(document).ready(function() {
          $('select').material_select();
        });
      </script>

      <script>
        function validar(evt) {
          var exp = /^[A-Za-z]+\s[A-Za-z]+\s[A-Za-z]+$/;
          var nombre = document.getElementById("nombre").value.trim(); // Elimina espacios en blanco al principio y al final

          if (!exp.test(nombre)) {
            alert("Nombre inválido. Por favor, ingresa un nombre con al menos un nombre y dos apellidos.");
            evt.preventDefault(); // Solo evita el envío del formulario si el nombre es inválido
          }
        }
      </script>

      <script src="js/validacion.js"></script>

</body>

</html>