<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'vendedor') {
    header("Location: login.php"); exit();
}
require_once 'includes/conexion.php';

try {
    $categorias = $conexion->query("SELECT * FROM categorias ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error al cargar categorías.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <base href="/Promotian1/">
    <meta charset="UTF-8">
    <title>Agregar Producto - Promotian</title>
    <style>
        body { font-family: sans-serif; background: #f0f2f5; padding: 40px; }
        .form-container { max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 10px; }
        input, textarea, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn { background: #d32f2f; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-weight: bold; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Nuevo Producto o Servicio</h2>
        <form action="procesos/guardar_producto.php" method="POST" enctype="multipart/form-data">
            <label>Título</label>
            <input type="text" name="titulo" required>
            
            <label>Precio ($)</label>
            <input type="number" name="precio" step="0.01" required>
            
            <label>Categoría</label>
            <select name="categoria_id" required>
                <?php foreach($categorias as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nombre']); ?></option>
                <?php endforeach; ?>
            </select>
            
            <label>Descripción</label>
            <textarea name="descripcion" required rows="4"></textarea>
            
            <label>Foto del producto</label>
            <input type="file" name="imagen" accept="image/*" required>
            
            <button type="submit" class="btn">Publicar</button>
            <a href="panel_negocio.php" style="display:block; text-align:center; margin-top:15px; color:#666;">Cancelar</a>
        </form>
    </div>
</body>
</html>