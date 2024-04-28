<?php
//consultas.php

// Función para obtener todas las categorías
function obtenerTodasLasCategorias($conexion) {
    $query = "SELECT * FROM categoria";
    $resultado = mysqli_query($conexion, $query);
    return $resultado;
}

// Función para obtener los artículos de una categoría
function obtenerArticulosPorCategoria($conexion, $idCategoria) {
    $query = "SELECT * FROM articulo WHERE idcategoria = $idCategoria";
    $resultado = mysqli_query($conexion, $query);
    return $resultado;
}

// Función para obtener las tallas disponibles de un artículo
function obtenerTallasDisponibles($conexion, $idArticulo) {
    $query = "SELECT t.idtalla, t.nombre, e.existencia 
              FROM talla t 
              INNER JOIN existencia e ON t.idtalla = e.id_talla 
              WHERE e.id_articulo = $idArticulo";

    $resultado = mysqli_query($conexion, $query);
    return $resultado;
}