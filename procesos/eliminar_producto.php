<?php
session_start();


if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'vendedor') {
    header("Location: /Promotian1/login.php");
    exit();
}

require_once '../includes/conexion.php';


if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    $producto_id = intval($_GET['id']);
    $usuario_id = $_SESSION['usuario_id'];

    try {
        
        $sql_verificar = "SELECT p.id, p.url_imagen 
                          FROM productos p 
                          INNER JOIN negocios n ON p.negocio_id = n.id 
                          WHERE p.id = :producto_id AND n.usuario_id = :usuario_id";
                          
        $stmt = $conexion->prepare($sql_verificar);
        $stmt->execute([
            ':producto_id' => $producto_id, 
            ':usuario_id' => $usuario_id
        ]);
        
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        
        if ($producto) {
            
            
            if (!empty($producto['url_imagen'])) {
                $ruta_fisica_imagen = '../' . $producto['url_imagen'];
                if (file_exists($ruta_fisica_imagen)) {
                    unlink($ruta_fisica_imagen); 
                }
            }

            
            $sql_eliminar = "DELETE FROM productos WHERE id = :id";
            $del = $conexion->prepare($sql_eliminar);
            $del->execute([':id' => $producto_id]);

            
            header("Location: ../panel_negocio.php?exito=eliminado");
            exit();
            
        } else {
            
            header("Location: ../panel_negocio.php?error=permiso_denegado");
            exit();
        }

    } catch(PDOException $e) {
        header("Location: ../panel_negocio.php?error=bd");
        exit();
    }
} else {
    
    header("Location: ../panel_negocio.php");
    exit();
}
?>