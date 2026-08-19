<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /Promotian1/login");
    exit();
}

require_once '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $titulo = strip_tags(trim($_POST['titulo']));
    $precio = floatval($_POST['precio']);
    $categoria_id = intval($_POST['categoria_id']);
    $ciudad = strip_tags(trim($_POST['ciudad']));
    $descripcion = strip_tags(trim($_POST['descripcion']));
    $usuario_id = $_SESSION['usuario_id'];
    $nombre_usuario = $_SESSION['nombre']; 

    $ruta_imagen = null;

    
    if (isset($_FILES['imagen_producto']) && $_FILES['imagen_producto']['error'] === 0) {
        $archivo = $_FILES['imagen_producto'];
        $extensiones = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        
        if (in_array($ext, $extensiones) && $archivo['size'] <= 3145728) { 
            $dir = '../assets/img/productos/';
            if (!file_exists($dir)) mkdir($dir, 0755, true);
            
            $nombre_img = 'prod_' . $usuario_id . '_' . time() . '.' . $ext;
            if (move_uploaded_file($archivo['tmp_name'], $dir . $nombre_img)) {
                $ruta_imagen = 'assets/img/productos/' . $nombre_img;
            } else {
                header("Location: /Promotian1/publicacion_rapida?error=upload"); exit();
            }
        } else {
            header("Location: /Promotian1/publicacion_rapida?error=formato"); exit();
        }
    } else {
        header("Location: /Promotian1/publicacion_rapida?error=no_img"); exit();
    }

    try {
        $conexion->beginTransaction();

        
        $nombre_negocio = "Vendedor local: " . $nombre_usuario;
        $desc_negocio = "Vendedor independiente en " . $ciudad;
        
        $sql_negocio = "INSERT INTO negocios (usuario_id, nombre, descripcion, ciudad) 
                        VALUES (:usuario_id, :nombre, :descripcion, :ciudad)";
        $stmt_negocio = $conexion->prepare($sql_negocio);
        $stmt_negocio->execute([
            ':usuario_id' => $usuario_id,
            ':nombre' => $nombre_negocio,
            ':descripcion' => $desc_negocio,
            ':ciudad' => $ciudad
        ]);
        

        
        $sql_prod = "INSERT INTO productos (negocio_id, categoria_id, titulo, descripcion, precio, url_imagen) 
                     VALUES (:negocio_id, :categoria_id, :titulo, :descripcion, :precio, :url_imagen)";
        $stmt_prod = $conexion->prepare($sql_prod);
        $stmt_prod->execute([
            ':negocio_id' => $negocio_id,
            ':categoria_id' => $categoria_id,
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':precio' => $precio,
            ':url_imagen' => $ruta_imagen
        ]);

        
        $conexion->query("UPDATE usuarios SET rol = 'vendedor' WHERE id = $usuario_id");
        $_SESSION['rol'] = 'vendedor';

        
        $conexion->commit();

        
        header("Location: /Promotian1/panel_negocio?exito=producto_rapido");
        exit();

    } catch(PDOException $e) {
        $conexion->rollBack(); 
        header("Location: /Promotian1/publicacion_rapida?error=bd");
        exit();
    }
} else {
    header("Location: /Promotian1/publicacion_rapida");
    exit();
}
?>