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
        <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">Ingrese los datos que se solicitan</h1>

    </div>
</div>
</div>
<!-- Page Header End -->

<div class="container-fluid pt-5">
    <div class="container">
        <div class="col-lg-6 mb-5">
            <div class="row align-items-center">
                <div class="col-sm-5">
                    <form action="enviar_correo.php" method="post">
                        <label for="asunto">Asunto:</label>
                        <input class="class1" type="text" id="asunto" name="asunto" required>

                        <label for="mensaje">Mensaje:</label>
                        <textarea style="width: 400px; height: 60px;" id="mensaje" name="mensaje" rows="5" required></textarea><br><br>

                        <button class="btn btn-primary btn-lg px-4 me-sm-3" type="submit" name="enviar_formulario" id="enviar">
                        Enviar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once "pie.php" ?>