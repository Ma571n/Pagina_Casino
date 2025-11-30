<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once("conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'registrar') {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $activo = 1;

    // Crear instancia de conexión
    $conexion = new Conexion();
    $db = $conexion->getConnection(); // ESTA es la conexión mysqli

    if (!$db) {
        die("Error: No se pudo conectar a la base de datos");
    }

    // 1) Insertar en tabla persona
    $sqlPersona = "INSERT INTO persona (fecha_nacimiento, activo) VALUES (?, ?)";
    $stmt = mysqli_prepare($db, $sqlPersona);
    mysqli_stmt_bind_param($stmt, "si", $fecha_nacimiento, $activo);
    mysqli_stmt_execute($stmt);

    $persona_id = mysqli_insert_id($db);

    if ($persona_id <= 0) {
        die("Error al insertar persona");
    }

    // 2) Insertar en tabla usuario
    $sqlUsuario = "INSERT INTO usuario (username, password, persona_id, activo) VALUES (?, ?, ?, ?)";
    $stmt2 = mysqli_prepare($db, $sqlUsuario);
    mysqli_stmt_bind_param($stmt2, "ssii", $username, $password, $persona_id, $activo);
    mysqli_stmt_execute($stmt2);

    if (mysqli_stmt_affected_rows($stmt2) > 0) {
        echo "Registro exitoso";
    } else {
        echo "Error al registrar usuario";
    }

    $conexion->closeConnection();
}
