<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: /Promotian1/");
    exit();
}

require_once '../includes/conexion.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    $id_eliminar = intval($_GET['id']);
    
    if ($id_eliminar === $_SESSION['usuario_id']) {
        header("Location: /Promotian1/admin_usuarios?error=mismo_usuario");
        exit();
    }

    try {
        $conexion->beginTransaction();
        
        
        $stmt_prod = $conexion->prepare("DELETE FROM productos WHERE negocio_id IN (SELECT id FROM negocios WHERE usuario_id = :id)");
        $stmt_prod->execute([':id' => $id_eliminar]);

        $stmt_neg = $conexion->prepare("DELETE FROM negocios WHERE usuario_id = :id");
        $stmt_neg->execute([':id' => $id_eliminar]);

        $stmt_usr = $conexion->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt_usr->execute([':id' => $id_eliminar]);

        $conexion->commit();
        
        header("Location: /Promotian1/admin_usuarios?exito=eliminado");
        exit();

    } catch(PDOException $e) {
        $conexion->rollBack(); 
        header("Location: /Promotian1/admin_usuarios?error=bd");
        exit();
    }
} else {
    header("Location: /Promotian1/admin_usuarios");
    exit();
}
?>