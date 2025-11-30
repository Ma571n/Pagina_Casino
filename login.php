<?php
session_start();
require_once "../BackEnd/api/v1/conexion.php";

header('Content-Type: application/json');

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $conexion = new Conexion();
    $db = $conexion->getConnection();

    $sql = "SELECT u.id, u.username, u.password 
            FROM usuario u
            JOIN persona p ON u.persona_id = p.id
            WHERE u.username=? AND u.activo=1 AND p.activo=1";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($resultado && mysqli_num_rows($resultado) === 1) {
        $user = mysqli_fetch_assoc($resultado);

        if ($password === $user['password']) {
            $_SESSION['usuario'] = ['id'=>$user['id'],'username'=>$user['username']];
            echo json_encode(['success'=>true]);
        } else {
            echo json_encode(['success'=>false,'error'=>'Contraseña incorrecta']);
        }
    } else {
        echo json_encode(['success'=>false,'error'=>'Usuario no encontrado']);
    }

    $conexion->closeConnection();
    exit;
}
?>




