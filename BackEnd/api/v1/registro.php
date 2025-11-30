<?php
session_start();
require_once("conexion.php"); // OK, porque registro.php y conexion.php están juntos en /v1/

if ($_SERVER["REQUEST_METHOD"] === "POST" && $_POST['action'] === "registrar") {

    $username = trim($_POST['username']);
    $fecha = $_POST['fecha_nacimiento'];
    $password = $_POST['password'];
    $confirm = $_POST['confirmPassword'];

    if ($password !== $confirm) {
        $_SESSION['error'] = "Las contraseñas no coinciden.";
        header("Location: ../../../FrontEnd/index.php");
        exit;
    }

    // Verificar si usuario ya existe
    $stmt = $pdo->prepare("SELECT id FROM usuario WHERE username = ?");
    $stmt->execute([$username]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = "El nombre de usuario ya está en uso.";
        header("Location: ../../../FrontEnd/index.php");
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Crear persona
        $stmtP = $pdo->prepare("INSERT INTO persona (fecha_nacimiento, activo) VALUES (?, 1)");
        $stmtP->execute([$fecha]);
        $persona_id = $pdo->lastInsertId();

        // Crear usuario
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmtU = $pdo->prepare(
            "INSERT INTO usuario (username, password, persona_id, activo) VALUES (?, ?, ?, 1)"
        );
        $stmtU->execute([$username, $hash, $persona_id]);

        $pdo->commit();

        $_SESSION['success'] = "Cuenta creada exitosamente.";
        header("Location: ../../../FrontEnd/index.php");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: ../../../FrontEnd/index.php");
        exit;
    }
}

header("Location: ../../../FrontEnd/index.php");
exit;
