<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /Promotian1/login");
    exit();
}

require_once '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto_perfil'])) {
    $archivo = $_FILES['foto_perfil'];
    
    if ($archivo['error'] === 0) {
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'webp'];
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        
        if (in_array($extension, $extensiones_permitidas)) {
            if ($archivo['size'] <= 2097152) {
                
                $nombre_nuevo = 'perfil_' . $_SESSION['usuario_id'] . '_' . time() . '.' . $extension;
                
                
                $directorio = '../assets/img/perfiles/';
                if (!file_exists($directorio)) {
                    
                    mkdir($directorio, 0755, true); 
                }
                
                $ruta_destino = $directorio . $nombre_nuevo;
                
                if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
                    $ruta_bd = 'assets/img/perfiles/' . $nombre_nuevo;
                    
                    $stmt = $conexion->prepare("UPDATE usuarios SET foto_perfil = :foto WHERE id = :id");
                    $stmt->execute([
                        ':foto' => $ruta_bd,
                        ':id' => $_SESSION['usuario_id']
                    ]);
                    
                    header("Location: /Promotian1/mi_cuenta?exito=foto");
                    exit();
                } else {
                    
                    header("Location: /Promotian1/mi_cuenta?error=servidor");
                    exit();
                }
            } else {
                header("Location: /Promotian1/mi_cuenta?error=peso");
                exit();
            }
        } else {
            header("Location: /Promotian1/mi_cuenta?error=formato");
            exit();
        }
    }
}
header("Location: /Promotian1/mi_cuenta?error=general");
exit();
?>