<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'vendedor') {
    header("Location: ../login.php"); exit();
}
require_once '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = strip_tags(trim($_POST['titulo']));
    $precio = floatval($_POST['precio']);
    $categoria_id = intval($_POST['categoria_id']);
    $descripcion = strip_tags(trim($_POST['descripcion']));
    $usuario_id = $_SESSION['usuario_id'];

    try {
        
        $stmt_negocio = $conexion->prepare("SELECT id FROM negocios WHERE usuario_id = :uid LIMIT 1");
        $stmt_negocio->execute([':uid' => $usuario_id]);
        $negocio = $stmt_negocio->fetch(PDO::FETCH_ASSOC);

        if ($negocio) {
            $ruta_imagen = "";
            
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
                $nombre_img = 'prod_' . time() . '.' . $ext;
                $dir = '../assets/img/productos/';
                if (!file_exists($dir)) mkdir($dir, 0755, true);
                
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $dir . $nombre_img)) {
                    $ruta_imagen = 'assets/img/productos/' . $nombre_img;
                }
            }

            
            $sql = "INSERT INTO productos (negocio_id, categoria_id, titulo, descripcion, precio, url_imagen) 
                    VALUES (:nid, :cid, :titulo, :desc, :precio, :img)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ':nid' => $negocio['id'],
                ':cid' => $categoria_id,
                ':titulo' => $titulo,
                ':desc' => $descripcion,
                ':precio' => $precio,
                ':img' => $ruta_imagen
            ]);

            header("Location: ../panel_negocio.php?exito=producto_rapido");
            exit();
        }
    } catch(PDOException $e) {
        die("Error al guardar: " . $e->getMessage());
    }
}
?>