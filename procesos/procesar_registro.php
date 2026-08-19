<?php
session_start(); 
require_once '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    
    $nombre = strip_tags(trim($_POST['nombre']));
    $apellido = strip_tags(trim($_POST['apellido']));
    $correo = filter_var(trim($_POST['correo']), FILTER_SANITIZE_EMAIL);
    $telefono = strip_tags(trim($_POST['telefono']));
    $ciudad = strip_tags(trim($_POST['ciudad']));
    $contrasena = trim($_POST['contrasena']);
    $confirmar = trim($_POST['confirmar_contrasena']);
    
    
    $rol = 'comprador'; 

    
    if ($contrasena !== $confirmar) {
        header("Location: ../registro.php?error=pass_no_coincide");
        exit();
    }

    try {
        
        $verificar = $conexion->prepare("SELECT id FROM usuarios WHERE correo = :correo");
        $verificar->execute([':correo' => $correo]);
        
        if ($verificar->rowCount() > 0) {
            header("Location: ../registro.php?error=correo_existe");
            exit();
        }

        
        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

        
        $sql = "INSERT INTO usuarios (nombre, apellido, correo, telefono, ciudad, contrasena_hash, rol) 
                VALUES (:nombre, :apellido, :correo, :telefono, :ciudad, :contrasena_hash, :rol)";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':correo' => $correo,
            ':telefono' => $telefono,
            ':ciudad' => $ciudad,
            ':contrasena_hash' => $contrasena_hash,
            ':rol' => $rol
        ]);

        header("Location: ../login.php?exito=registro_completado");
        exit();

    } catch(PDOException $e) {
        header("Location: ../registro.php?error=bd");
        exit();
    }
} else {
    header("Location: ../registro.php");
    exit();
}
?>