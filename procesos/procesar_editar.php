<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'vendedor') {
    header("Location: /Promotian1/login");
    exit();
}

require_once '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    
    $producto_id = intval($_POST['producto_id']);
    $titulo = strip_tags(trim($_POST['titulo']));
    $precio = floatval($_POST['precio']);
    $categoria_id = intval($_POST['categoria_id']);
    $descripcion = strip_tags(trim($_POST['descripcion']));
    $usuario_id = $_SESSION['usuario_id'];

    try {
        
        $sql_verificar = "SELECT p.id, p.url_imagen FROM productos p 
                          INNER JOIN negocios n ON p.negocio_id = n.id 
                          WHERE p.id = :pid AND n.usuario_id = :uid";
        $stmt_verificar = $conexion->prepare($sql_verificar);
        $stmt_verificar->execute([':pid' => $producto_id, ':uid' => $usuario_id]);
        $producto_actual = $stmt_verificar->fetch(PDO::FETCH_ASSOC);

        if (!$producto_actual) {
            header("Location: /Promotian1/panel_negocio?error=permiso_denegado");
            exit();
        }

        $ruta_imagen_nueva = $producto_actual['url_imagen']; 

        
        if (isset($_FILES['imagen_producto']) && $_FILES['imagen_producto']['error'] === 0) {
            $archivo = $_FILES['imagen_producto'];
            $extensiones = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
            
            if (in_array($ext, $extensiones) && $archivo['size'] <= 3145728) {
                
                $dir = '../assets/img/productos/';
                if (!file_exists($dir)) mkdir($dir, 0755, true);
                
                $nombre_img = 'prod_' . $usuario_id . '_' . time() . '.' . $ext;
                
                if (move_uploaded_file($archivo['tmp_name'], $dir . $nombre_img)) {
                    
                    if (!empty($producto_actual['url_imagen']) && file_exists('../' . $producto_actual['url_imagen'])) {
                        unlink('../' . $producto_actual['url_imagen']);
                    }
                    $ruta_imagen_nueva = 'assets/img/productos/' . $nombre_img;
                }
            } else {
                header("Location: /Promotian1/editar_producto?id=$producto_id&error=imagen");
                exit();
            }
        }

        
        $sql_update = "UPDATE productos 
                       SET titulo = :titulo, precio = :precio, descripcion = :descripcion, 
                           categoria_id = :categoria_id, url_imagen = :url_imagen 
                       WHERE id = :id";
                       
        $update = $conexion->prepare($sql_update);
        $update->execute([
            ':titulo' => $titulo,
            ':precio' => $precio,
            ':descripcion' => $descripcion,
            ':categoria_id' => $categoria_id,
            ':url_imagen' => $ruta_imagen_nueva,
            ':id' => $producto_id
        ]);

        header("Location: /Promotian1/panel_negocio?exito=editado");
        exit();

    } catch(PDOException $e) {
        header("Location: /Promotian1/editar_producto?id=$producto_id&error=bd");
        exit();
    }
} else {
    header("Location: /Promotian1/panel_negocio");
    exit();
}
?>