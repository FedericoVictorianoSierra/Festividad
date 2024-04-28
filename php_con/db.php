<?php


global $enlace;
function conexion(){
        $enlace = mysqli_connect('localhost', 'root', '','festividad');
        if(!$enlace)
        {
            echo "Error: No se puede conectar MYSQL". PHP_EOL;
            echo "Error de depuracion" . mysqli_connect_errno() . PHP_EOL;
            echo "Error de depuracion" . mysqli_connect_error() . PHP_EOL;
            exit;
        }
        return $enlace;
}
/*
global $enlace;

function conexion() {
    $host = 'sql301.infinityfree.com';
    $username = 'if0_36053438';
    $password = '7K97X6c6yUMFw';
    $database = 'if0_36053438_ropatipica';

    $enlace = mysqli_connect($host, $username, $password, $database);

    if (!$enlace) {
        echo "Error: No se puede conectar a MySQL" . PHP_EOL;
        echo "Error de depuración: " . mysqli_connect_errno() . PHP_EOL;
        echo "Error de depuración: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }

    return $enlace;
}
*/
?>