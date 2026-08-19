<?php
session_start();


if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: /Promotian1/");
    exit();
}

require_once '../includes/conexion.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    $negocio_id = intval($_GET['id']);

    try {

        $stmt_dueno = $conexion->prepare("SELECT usuario_id FROM negocios WHERE id = :id");
        $stmt_dueno->execute([':id' => $negocio_id]);
        $dueno = $stmt_dueno->fetch(PDO::FETCH_ASSOC);


        $conexion->beginTransaction();
        

        $stmt_prod = $conexion->prepare("DELETE FROM productos WHERE negocio_id = :id");
        $stmt_prod->execute([':id' => $negocio_id]);


        $stmt_neg = $conexion->prepare("DELETE FROM negocios WHERE id = :id");
        $stmt_neg->execute([':id' => $negocio_id]);


        if ($dueno) {
            $stmt_usr = $conexion->prepare("UPDATE usuarios SET rol = 'comprador' WHERE id = :uid");
            $stmt_usr->execute([':uid' => $dueno['usuario_id']]);
        }


        $conexion->commit();
        
        header("Location: /Promotian1/admin_negocios?exito=eliminado");
        exit();

    } catch(PDOException $e) {
        $conexion->rollBack();
        header("Location: /Promotian1/admin_negocios?error=bd");
        exit();
    }
} else {
    header("Location: /Promotian1/admin_negocios");
    exit();
}
?>