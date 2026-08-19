<?php
session_start();


if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'vendedor') {
    header("Location: /Promotian1/mi_cuenta");
    exit();
}

require_once 'includes/conexion.php';
$usuario_id = $_SESSION['usuario_id'];

try {
    
    $stmt_negocio = $conexion->prepare("SELECT * FROM negocios WHERE usuario_id = :uid LIMIT 1");
    $stmt_negocio->execute([':uid' => $usuario_id]);
    $negocio = $stmt_negocio->fetch(PDO::FETCH_ASSOC);

    if (!$negocio) {
        die("Ocurrió un error: No encontramos los datos de tu negocio.");
    }

    
    $stmt_prod = $conexion->prepare("SELECT * FROM productos WHERE negocio_id = :nid ORDER BY fecha_creacion DESC");
    $stmt_prod->execute([':nid' => $negocio['id']]);
    $productos = $stmt_prod->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <base href="/Promotian1/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Negocio - Promotian</title>
    <style>
        body, html {
            margin: 0; padding: 0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f6f9; color: #333;
        }

        
        .top-bar {
            display: flex; justify-content: space-between; align-items: center;
            padding: 15px 50px; background-color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .logo img { height: 40px; display: block; }
        .btn-regresar { text-decoration: none; color: #20314b; font-weight: 600; font-size: 14px; }

        
        .panel-container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }
        
        .header-negocio {
            display: flex; justify-content: space-between; align-items: center;
            background-color: #20314b; color: white; padding: 30px; border-radius: 12px;
            margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .info-negocio { display: flex; align-items: center; gap: 20px; }
        .logo-negocio {
            width: 80px; height: 80px; background-color: white; border-radius: 10px;
            display: flex; align-items: center; justify-content: center; overflow: hidden;
            color: #20314b; font-size: 24px; font-weight: bold;
        }
        .logo-negocio img { width: 100%; height: 100%; object-fit: cover; }
        
        .header-negocio h1 { margin: 0 0 5px 0; font-size: 24px; }
        .header-negocio p { margin: 0; opacity: 0.8; font-size: 14px; }

        .btn-publicar {
            background-color: #d32f2f; color: white; text-decoration: none;
            padding: 12px 25px; border-radius: 8px; font-weight: bold; transition: 0.3s;
        }
        .btn-publicar:hover { background-color: #b72525; }

        
        .seccion-titulo { font-size: 20px; color: #20314b; margin-bottom: 20px; border-bottom: 2px solid #ddd; padding-bottom: 10px; }
        
        .productos-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;
        }

        .producto-card {
            background: white; border-radius: 10px; overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: transform 0.3s;
        }
        .producto-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        
        .producto-img { width: 100%; height: 160px; background-color: #eee; display: flex; align-items: center; justify-content: center; color: #999; }
        .producto-img img { width: 100%; height: 100%; object-fit: cover; }
        
        .producto-info { padding: 15px; }
        .producto-info h3 { margin: 0 0 10px 0; font-size: 16px; color: #333; }
        .producto-precio { color: #d32f2f; font-weight: bold; font-size: 18px; margin: 0 0 15px 0; }
        
        .producto-acciones { display: flex; gap: 10px; }
        .btn-accion { flex: 1; text-align: center; padding: 8px; border-radius: 5px; text-decoration: none; font-size: 13px; font-weight: bold; }
        .btn-editar { background-color: #f0f2f5; color: #333; }
        .btn-eliminar { background-color: #fee2e2; color: #991b1b; }

        .sin-productos { grid-column: 1 / -1; background: white; padding: 40px; text-align: center; border-radius: 10px; color: #666; }
    </style>
</head>
<body>

    <div class="top-bar">
        <div class="logo"><img src="assets/img/logo.png" alt="Promotian"></div>
        <a href="mi_cuenta" class="btn-regresar">? Volver a Mi Cuenta</a>
    </div>

    <div class="panel-container">
        
        <?php if(isset($_GET['exito']) && $_GET['exito'] == 'producto_rapido'): ?>
            <div style="background-color: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                ¡Tu producto se publicó correctamente!
            </div>
        <?php endif; ?>

        
        <div class="header-negocio">
            <div class="info-negocio">
                <div class="logo-negocio">
                    <?php if(!empty($negocio['url_logo'])): ?>
                        <img src="<?php echo htmlspecialchars($negocio['url_logo']); ?>" alt="Logo">
                    <?php else: ?>
                        ?
                    <?php endif; ?>
                </div>
                <div>
                    <h1><?php echo htmlspecialchars($negocio['nombre']); ?></h1>
                    <p>? <?php echo htmlspecialchars($negocio['ciudad']); ?> | Miembro desde <?php echo date("Y", strtotime($negocio['fecha_creacion'] ?? 'now')); ?></p>
                </div>
            </div>
            <a href="publicar_producto.php" class="btn-publicar">+ Agregar Producto</a>
        </div>

        <h2 class="seccion-titulo">Tus Publicaciones</h2>

        <div class="productos-grid">
            <?php if(count($productos) > 0): ?>
                
                <?php foreach($productos as $prod): ?>
                <div class="producto-card">
                    <div class="producto-img">
                        <?php if(!empty($prod['url_imagen'])): ?>
                            <img src="<?php echo htmlspecialchars($prod['url_imagen']); ?>" alt="Producto">
                        <?php else: ?>
                            ? Sin imagen
                        <?php endif; ?>
                    </div>
                    <div class="producto-info">
                        <h3><?php echo htmlspecialchars($prod['titulo']); ?></h3>
                        <p class="producto-precio">$<?php echo number_format($prod['precio'], 2); ?></p>
      <div class="producto-acciones">
    <a href="editar_producto.php?id=<?php echo $prod['id']; ?>" class="btn-accion btn-editar">Editar</a>
    <a href="procesos/eliminar_producto.php?id=<?php echo $prod['id']; ?>" class="btn-accion btn-eliminar" onclick="return confirm('¿Estás seguro de eliminar este producto?');">Eliminar</a>
</div>
                    </div>
                </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="sin-productos">
                    <h3>Aún no tienes productos publicados</h3>
                    <p>Empieza a agregar tu catálogo para que los clientes te encuentren.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>
