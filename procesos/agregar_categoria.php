<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: /Promotian1/"); exit();
}

require_once '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['nombre_categoria'])) {
    $nombre = strip_tags(trim($_POST['nombre_categoria']));
    
    try {
        $stmt = $conexion->prepare("INSERT INTO categorias (nombre) VALUES (:nombre)");
        $stmt->execute([':nombre' => $nombre]);
        header("Location: /Promotian1/admin_categorias?exito=agregada");
    } catch(PDOException $e) {
        header("Location: /Promotian1/admin_categorias?error=bd");
    }
} else {
    header("Location: /Promotian1/admin_categorias");
}
?>