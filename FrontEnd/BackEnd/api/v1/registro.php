<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once("conexion.php");

// Respuesta JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'registrar') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $activo = 1;

    // Crear conexión
    $conexion = new Conexion();
    $db = $conexion->getConnection();

    if (!$db) {
        echo json_encode(['success' => false, 'error' => 'No se pudo conectar a la base de datos']);
        exit;
    }

    // Verificar que el username no exista
    $checkUser = "SELECT id FROM usuario WHERE username = ?";
    $stmtCheck = mysqli_prepare($db, $checkUser);
    mysqli_stmt_bind_param($stmtCheck, "s", $username);
    mysqli_stmt_execute($stmtCheck);
    $resultadoCheck = mysqli_stmt_get_result($stmtCheck);

    if (mysqli_num_rows($resultadoCheck) > 0) {
        echo json_encode(['success' => false, 'error' => 'El nombre de usuario ya existe']);
        exit;
    }

    // 1) Insertar en tabla persona
    $sqlPersona = "INSERT INTO persona (fecha_nacimiento, activo) VALUES (?, ?)";
    $stmt = mysqli_prepare($db, $sqlPersona);
    mysqli_stmt_bind_param($stmt, "si", $fecha_nacimiento, $activo);
    if (!mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => false, 'error' => 'Error al registrar persona']);
        exit;
    }

    $persona_id = mysqli_insert_id($db);

    if ($persona_id <= 0) {
        echo json_encode(['success' => false, 'error' => 'Error al obtener ID de persona']);
        exit;
    }

    // 2) Insertar en tabla usuario
    $sqlUsuario = "INSERT INTO usuario (username, password, persona_id, activo) VALUES (?, ?, ?, ?)";
    $stmt2 = mysqli_prepare($db, $sqlUsuario);
    mysqli_stmt_bind_param($stmt2, "ssii", $username, $password, $persona_id, $activo);

    if (mysqli_stmt_execute($stmt2) && mysqli_stmt_affected_rows($stmt2) > 0) {
        // Iniciar sesión automáticamente
        $_SESSION['usuario'] = ['id' => mysqli_insert_id($db), 'username' => $username];
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al registrar usuario']);
    }

    $conexion->closeConnection();
    exit;
}
?>
