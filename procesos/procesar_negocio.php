<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /Promotian1/login");
    exit();
}

require_once '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre_negocio = strip_tags(trim($_POST['nombre_negocio']));
    $ciudad = strip_tags(trim($_POST['ciudad']));
    $descripcion = strip_tags(trim($_POST['descripcion']));
    $usuario_id = $_SESSION['usuario_id'];
    
    $ruta_logo = null; 

    
    if (isset($_FILES['logo_negocio']) && $_FILES['logo_negocio']['error'] === 0) {
        $archivo = $_FILES['logo_negocio'];
        $extensiones = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        
        if (in_array($ext, $extensiones) && $archivo['size'] <= 2097152) { 
            $dir = '../assets/img/negocios/';
            if (!file_exists($dir)) mkdir($dir, 0755, true); 
            
            $nombre_img = 'logo_' . $usuario_id . '_' . time() . '.' . $ext;
            if (move_uploaded_file($archivo['tmp_name'], $dir . $nombre_img)) {
                $ruta_logo = 'assets/img/negocios/' . $nombre_img;
            }
        } else {
            header("Location: /Promotian1/crear_negocio?error=imagen");
            exit();
        }
    }

    try {
        
        $sql = "INSERT INTO negocios (usuario_id, nombre, descripcion, ciudad, url_logo) 
                VALUES (:usuario_id, :nombre, :descripcion, :ciudad, :url_logo)";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ':usuario_id' => $usuario_id,
            ':nombre' => $nombre_negocio,
            ':descripcion' => $descripcion,
            ':ciudad' => $ciudad,
            ':url_logo' => $ruta_logo
        ]);

        
        $update = $conexion->prepare("UPDATE usuarios SET rol = 'vendedor' WHERE id = :id");
        $update->execute([':id' => $usuario_id]);
        
        $_SESSION['rol'] = 'vendedor';

        header("Location: /Promotian1/panel_negocio?exito=bienvenido");
        exit();

    } catch(PDOException $e) {
        header("Location: /Promotian1/crear_negocio?error=bd");
        exit();
    }
} else {
    header("Location: /Promotian1/crear_negocio");
    exit();
}
?>