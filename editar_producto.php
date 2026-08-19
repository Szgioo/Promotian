<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'vendedor') {
    header("Location: /Promotian1/login");
    exit();
}

require_once 'includes/conexion.php';


if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: /Promotian1/panel_negocio");
    exit();
}

$producto_id = intval($_GET['id']);
$usuario_id = $_SESSION['usuario_id'];

try {
    
    $sql = "SELECT p.* FROM productos p 
            INNER JOIN negocios n ON p.negocio_id = n.id 
            WHERE p.id = :pid AND n.usuario_id = :uid LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':pid' => $producto_id, ':uid' => $usuario_id]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        header("Location: /Promotian1/panel_negocio?error=no_encontrado");
        exit();
    }

    
    $categorias = $conexion->query("SELECT id, nombre FROM categorias")->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    die("Error al cargar los datos.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <base href="/Promotian1/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto - Promotian</title>
    <style>
        
        body, html { margin: 0; font-family: 'Segoe UI', Roboto, sans-serif; background-color: #f0f2f5; color: #333; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; padding: 20px 50px; background-color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .btn-regresar { text-decoration: none; color: #20314b; font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .logo img { height: 40px; }
        
        .form-container { max-width: 600px; margin: 40px auto; background-color: white; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .form-container h1 { color: #20314b; margin-top: 0; font-size: 28px; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        
        .form-group { margin-bottom: 20px; }
        .form-row { display: flex; gap: 20px; }
        .form-row .form-group { flex: 1; }
        label { display: block; font-size: 14px; font-weight: 600; color: #20314b; margin-bottom: 8px; }
        
        input[type="text"], input[type="number"], textarea, select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 15px; }
        input:focus, textarea:focus, select:focus { outline: none; border-color: #d32f2f; }
        textarea { min-height: 120px; resize: vertical; }
        
        .btn-submit { width: 100%; background-color: #d32f2f; color: white; border: none; padding: 15px; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 10px; transition: 0.3s; }
        .btn-submit:hover { background-color: #b72525; }
        
        .file-upload { border: 2px dashed #ccc; padding: 20px; text-align: center; border-radius: 8px; cursor: pointer; background-color: #fafafa; margin-bottom: 10px; }
        .file-upload:hover { border-color: #20314b; }
        
        .img-actual { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; margin-top: 10px; border: 1px solid #ddd; }
        
        .alerta-error { background-color: #fee2e2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-size: 14px; }
    </style>
</head>
<body>

    <div class="top-bar">
        <a href="panel_negocio" class="btn-regresar"><span>&larr;</span> Cancelar</a>
        <div class="logo"><img src="assets/img/logo.png" alt="Promotian"></div>
    </div>

    <div class="form-container">
        <h1>Editar Publicación</h1>
        
        <?php if (isset($_GET['error'])) echo '<div class="alerta-error">Error al actualizar. Revisa la imagen o intenta de nuevo.</div>'; ?>

        <form action="procesos/procesar_editar.php" method="POST" enctype="multipart/form-data">
            
            
            <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">

            <div class="form-group">
                <label>Título del producto o servicio</label>
                
                <input type="text" name="titulo" value="<?php echo htmlspecialchars($producto['titulo']); ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Precio ($ MXN)</label>
                    <input type="number" name="precio" value="<?php echo htmlspecialchars($producto['precio']); ?>" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Categoría</label>
                    <select name="categoria_id" required>
                        <?php foreach($categorias as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo ($producto['categoria_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Descripción detallada</label>
                
                <textarea name="descripcion" required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Actualizar Foto (Opcional)</label>
                <div class="file-upload" onclick="document.getElementById('imagen_producto').click();">
                    ? Clic aquí solo si deseas cambiar la foto actual
                </div>
                <input type="file" name="imagen_producto" id="imagen_producto" accept="image/jpeg, image/png, image/webp" style="display: none;">
                
                <?php if(!empty($producto['url_imagen'])): ?>
                    <p style="font-size: 12px; color: #666; margin-bottom: 5px;">Imagen actual:</p>
                    <img src="<?php echo htmlspecialchars($producto['url_imagen']); ?>" class="img-actual" alt="Actual">
                <?php endif; ?>
            </div>

            <button type="submit" class="btn-submit">Guardar Cambios</button>
        </form>
    </div>

</body>
</html>
