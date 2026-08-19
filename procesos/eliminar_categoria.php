<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: /Promotian1/"); exit();
}

require_once '../includes/conexion.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);
    
    try {
        $stmt = $conexion->prepare("DELETE FROM categorias WHERE id = :id");
        $stmt->execute([':id' => $id]);
        header("Location: /Promotian1/admin_categorias?exito=eliminada");
    } catch(PDOException $e) {
        header("Location: /Promotian1/admin_categorias?error=bd");
    }
} else {
    header("Location: /Promotian1/admin_categorias");
}
?>