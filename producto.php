<?php
session_start();
require_once 'includes/conexion.php';


if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: /Promotian1/"); 
    exit();
}

$producto_id = intval($_GET['id']);

try {
    
    $sql = "SELECT p.*, n.nombre AS negocio_nombre, n.ciudad, n.url_logo, u.telefono 
            FROM productos p 
            INNER JOIN negocios n ON p.negocio_id = n.id 
            INNER JOIN usuarios u ON n.usuario_id = u.id
            WHERE p.id = :id LIMIT 1";
            
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id' => $producto_id]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        die("El producto que buscas no existe o fue eliminado.");
    }

} catch(PDOException $e) {
    die("Error al cargar el producto.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <base href="/Promotian1/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($producto['titulo']); ?> - Promotian</title>
    <style>
        
        body, html { margin: 0; padding: 0; font-family: 'Segoe UI', Roboto, sans-serif; background-color: #f8f9fa; color: #333; }
        
        
        .top-bar { display: flex; justify-content: space-between; align-items: center; padding: 15px 50px; background-color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .logo img { height: 45px; display: block; }
        .btn-regresar { text-decoration: none; color: #20314b; font-weight: 600; }

        
        .producto-container { max-width: 1000px; margin: 40px auto; display: grid; grid-template-columns: 1.2fr 1fr; gap: 40px; padding: 0 20px; }
        
        
        .imagen-wrapper { background-color: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: center; min-height: 400px; }
        .imagen-wrapper img { width: 100%; height: 100%; object-fit: cover; }
        .sin-imagen { font-size: 50px; color: #ccc; }

        
        .info-wrapper { display: flex; flex-direction: column; gap: 20px; }
        .info-wrapper h1 { margin: 0; font-size: 32px; color: #20314b; line-height: 1.2; }
        .precio { font-size: 36px; font-weight: bold; color: #d32f2f; margin: 0; }
        .descripcion { font-size: 16px; color: #555; line-height: 1.6; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); }

        
        .vendedor-card { background-color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 4px solid #20314b; }
        .vendedor-header { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
        .vendedor-logo { width: 60px; height: 60px; background-color: #f0f2f5; border-radius: 50%; display: flex; align-items: center; justify-content: center; overflow: hidden; font-size: 24px; }
        .vendedor-logo img { width: 100%; height: 100%; object-fit: cover; }
        .vendedor-info h3 { margin: 0 0 5px 0; color: #20314b; font-size: 18px; }
        .vendedor-info p { margin: 0; color: #666; font-size: 14px; }

        
        .btn-whatsapp { display: block; width: 100%; background-color: #25D366; color: white; text-align: center; padding: 15px; border-radius: 8px; text-decoration: none; font-size: 18px; font-weight: bold; box-sizing: border-box; transition: 0.3s; }
        .btn-whatsapp:hover { background-color: #1ebe57; }

        @media (max-width: 768px) {
            .producto-container { grid-template-columns: 1fr; }
            .top-bar { padding: 15px 20px; }
        }
    </style>
</head>
<body>

    <div class="top-bar">
        <a href="/Promotian1/" class="logo"><img src="assets/img/logo.png" alt="Promotian"></a>
        <a href="/Promotian1/" class="btn-regresar">← Volver al inicio</a>
    </div>

    <div class="producto-container">
        
        
        <div class="imagen-wrapper">
            <?php if(!empty($producto['url_imagen'])): ?>
                <img src="<?php echo htmlspecialchars($producto['url_imagen']); ?>" alt="Producto">
            <?php else: ?>
                <span class="sin-imagen">📷</span>
            <?php endif; ?>
        </div>

        
        <div class="info-wrapper">
            <h1><?php echo htmlspecialchars($producto['titulo']); ?></h1>
            <div class="precio">$<?php echo number_format($producto['precio'], 2); ?></div>
            
            <div class="vendedor-card">
                <div class="vendedor-header">
                    <div class="vendedor-logo">
                        <?php if(!empty($producto['url_logo'])): ?>
                            <img src="<?php echo htmlspecialchars($producto['url_logo']); ?>" alt="Logo">
                        <?php else: ?>
                            🏪
                        <?php endif; ?>
                    </div>
                    <div class="vendedor-info">
                        <h3><?php echo htmlspecialchars($producto['negocio_nombre']); ?></h3>
                        <p>📍 <?php echo htmlspecialchars($producto['ciudad']); ?></p>
                    </div>
                </div>
                
                <?php 
                
                $mensaje = urlencode("Hola, vi tu anuncio de '" . $producto['titulo'] . "' en Promotian y me interesa.");
                
                $telefono = preg_replace('/[^0-9]/', '', $producto['telefono']);
                ?>
                
                <a href="https://wa.me/<?php echo $telefono; ?>?text=<?php echo $mensaje; ?>" target="_blank" class="btn-whatsapp">
                    Contactar por WhatsApp
                </a>
            </div>

            <div class="descripcion">
                <h3 style="margin-top: 0; color: #20314b;">Descripción</h3>
                <?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?>
            </div>
        </div>
    </div>

</body>
</html>
