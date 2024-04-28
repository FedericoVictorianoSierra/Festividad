<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'correo_electronico/vendor/autoload.php';

// Configuración de Gmail 
$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->SMTPAuth   = true;
$mail->SMTPSecure = 'ssl';
$mail->Host       = 'smtp.gmail.com';
$mail->Port       = 465;
$mail->Username   = 'bautistalucy157@gmail.com';
$mail->Password   = 'yylxiwmkumeyilqy';

// Obtener los datos del formulario
if (isset($_POST['correo'])) {
    $correo = $_POST['correo'];

    // Verificar si el correo electrónico existe en la base de datos
    require("php_con/db.php"); // Incluir la conexión a la base de datos
    $conexion = conexion(); // Crear la conexión

    $query = "SELECT * FROM usuario WHERE email='$correo'";
    $result = mysqli_query($conexion, $query);

    if (mysqli_num_rows($result) == 1) { // Si el correo electrónico existe
        $row = mysqli_fetch_assoc($result); // Obtener los datos del usuario

        // Detalles del correo electrónico
        $mail->setFrom('bautistalucy157@gmail.com', 'RopaTipica');
        $mail->addAddress($correo); // Correo electrónico del destinatario
        $mail->Subject = "Recuperar Password";
        $mail->Body    = "Tu usuario es: " . $row['nombreUsuario'] . ",           Tu contraseña es: " . $row['password'];

        // Envío del correo electrónico
        if ($mail->send()) {
            echo "<script>alert('Se ha enviado un correo electrónico con tu contraseña.');</script>";
        } else {
            echo "<script>alert('Hubo un error al enviar el correo electrónico.');</script>";
        }
    } else {
        echo "<script>alert('No se encontró ningún usuario con este correo electrónico.');</script>";
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
                        <label class="nav" for="tab1">Contraseña</label>
                        <div class="tab-content">
                            <form action="" method="POST">
                                <label class="frm">Correo Electrónico:</label>
                                <input type="email" placeholder="correo@example.com" name="correo" required="required" />
                                <button id="loginBtn" type="submit" name="recuperar">Recuperar contraseña</button>
                            </form>
                        </div>
                    </li>

                    <li>
                        <input id="tab2" type="radio" name="tabs" />
                        <label class="nav" for="tab2" id="sesionLabel">Sesión</label>
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

            <script src="js/validacion.js"></script>

            <script>
                // Obtener la etiqueta de la sesión por su ID
                var sesionLabel = document.getElementById("sesionLabel");

                // Agregar un evento de clic a la etiqueta de sesión
                sesionLabel.addEventListener("click", function() {
                    // Redirigir al usuario a sesion.php
                    window.location.href = "sesion.php";
                });
            </script>

        </div>
    </div>
</body>

</html>
