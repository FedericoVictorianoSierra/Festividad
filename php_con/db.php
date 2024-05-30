<?php

/*
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
        
}*/

global $enlace;

function conexion() {
    /*$host = 'sql203.infinityfree.com';
    $username = 'if0_36641767';
    $password = 'r9WGs9UENU8';
    $database = 'if0_36641767_festividad';*/

    $host = '35.184.106.188';
    $username = 'joseantonio';
    $password = '12345';
    $database = 'festividad';

    $enlace = mysqli_connect($host, $username, $password, $database);

    if (!$enlace) {
        echo "Error: No se puede conectar a MySQL" . PHP_EOL;
        echo "Error de depuración: " . mysqli_connect_errno() . PHP_EOL;
        echo "Error de depuración: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }

    return $enlace;
}

?>