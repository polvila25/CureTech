<?php

function connectaDB() {
    // Crear conexión
    $servername = "127.0.0.1";
    $username = "root";
    $password = ""; // Por defecto en XAMPP, el usuario root no tiene contraseña
    $dbname = "hackathonsalud"; // Nombre de la base de datos

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    return $conn;
}
?>