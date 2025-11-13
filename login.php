<?php
session_start();

// Procesar login - ACEPTA CUALQUIER COSA
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = $_POST['email'] ?? 'usuario@ejemplo.com';
    $password = $_POST['password'] ?? 'password';
    
    // Siempre devuelve éxito, sin validación
    $nombre = "Usuario";
    if (!empty($email)) {
        $nombreParts = explode('@', $email);
        $nombre = ucfirst($nombreParts[0]);
    }
    
    $_SESSION['usuario'] = [
        'id' => rand(1, 1000),
        'email' => $email,
        'nombre' => $nombre
    ];
    
    echo json_encode(['success' => true, 'message' => 'Login exitoso']);
    exit;
}

// Procesar logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: index.php');
    exit;
}

// Si se accede directamente, redirigir
header('Location: index.php');
exit;
?>