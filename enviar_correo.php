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
$asunto = $_POST['asunto'];
$mensaje = $_POST['mensaje'];

// Detalles del correo electrónico
$mail->setFrom('bautistalucy157@gmail.com', 'RopaTipica');
$mail->addAddress('bautistalucy157@gmail.com'); //correo de la persona
$mail->Subject = $asunto;
$mail->Body    = $mensaje;

?>
<?php

session_start(); // Iniciar sesión

if (!isset($_SESSION['idusuario'])) { // Si no hay una sesión iniciada
    // Redirigir al usuario a la página de inicio de sesión
    header("Location: sesion.php");
    exit(); // Salir del script
}
require("php_con/db.php"); // Incluir el archivo que contiene la función de conexión 
$conexion = conexion(); // Crear la conexión a la base de datos
?>

<?php include_once "encabezado.php" ?>
<!-- Page Header Start -->
<div class="container-fluid page-header mb-4 position-relative overlay-bottom">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
        <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">Estatus de su coreo:</h1>

    </div>
</div>
</div>
<!-- Page Header End -->

<div class="container-fluid pt-5">
    <div class="container">
        <div class="col-lg-6 mb-5">
            <div class="row align-items-center">
                    <?php
                    // Envío del correo electrónico
                    if ($mail->send()) {
                    ?>
                        <h2 >El correo electrónico se ha enviado correctamente.</h2>

                    <?php
                    } else {
                    ?>
                        <h2>Error al enviar el correo electrónico.</h2>
                    <?php
                        //echo 'Error al enviar el correo electrónico: ' . $mail->ErrorInfo;
                    }
                    ?>
            </div>
        </div>
    </div>
</div>
<?php include_once "pie.php" ?>