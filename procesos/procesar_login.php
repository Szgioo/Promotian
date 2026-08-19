<?php
session_start();
require_once '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $correo = trim($_POST['correo']);
    $contrasena = trim($_POST['contrasena']);

    if (empty($correo) || empty($contrasena)) {
        header("Location: ../login.php?error=vacios");
        exit();
    }

    try {
        
        $sql = "SELECT id, nombre, contrasena_hash, rol FROM usuarios WHERE correo = :correo";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([':correo' => $correo]);
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        
        
        if ($usuario && password_verify($contrasena, $usuario['contrasena_hash'])) {
            
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];
            
            header("Location: ../index.php");
            exit();

        } else {
            
            header("Location: ../login.php?error=incorrectos");
            exit();
        }

    } catch(PDOException $e) {
        header("Location: ../login.php?error=bd");
        exit();
    }
} else {
    header("Location: ../login.php");
    exit();
}
?>