<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] === 'vendedor') {
    header("Location: /Promotian1/mi_cuenta");
    exit();
}

require_once 'includes/conexion.php';
$categorias = $conexion->query("SELECT id, nombre FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <base href="/Promotian1/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicación Rápida - Promotian</title>
    <style>
        
        body, html { margin: 0; font-family: 'Segoe UI', Roboto, sans-serif; background-color: #f0f2f5; color: #333; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; padding: 20px 50px; background-color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .btn-regresar { text-decoration: none; color: #20314b; font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .logo img { height: 40px; }
        .form-container { max-width: 600px; margin: 40px auto; background-color: white; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .form-container h1 { color: #d32f2f; margin-top: 0; font-size: 28px; }
        .form-group { margin-bottom: 20px; }
        .form-row { display: flex; gap: 20px; }
        .form-row .form-group { flex: 1; }
        label { display: block; font-size: 14px; font-weight: 600; color: #20314b; margin-bottom: 8px; }
        input[type="text"], input[type="number"], textarea, select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 15px; }
        input:focus, textarea:focus, select:focus { outline: none; border-color: #d32f2f; }
        .btn-submit { width: 100%; background-color: #d32f2f; color: white; border: none; padding: 15px; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 10px; transition: 0.3s; }
        .btn-submit:hover { background-color: #b72525; }
        .file-upload { border: 2px dashed #ccc; padding: 20px; text-align: center; border-radius: 8px; cursor: pointer; background-color: #fafafa; }
        .alerta-error { background-color: #fee2e2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
    </style>
</head>
<body>

    <div class="top-bar">
        <a href="mi_cuenta" class="btn-regresar"><span>&larr;</span> Cancelar</a>
        <div class="logo"><img src="assets/img/logo.png" alt="Promotian"></div>
    </div>

    <div class="form-container">
        <h1>Venta Rápida</h1>
        <p>Publica tu artículo o servicio inmediatamente.</p>

        <?php
        if (isset($_GET['error'])) {
            echo '<div class="alerta-error">Hubo un problema. Revisa la imagen o intenta de nuevo.</div>';
        }
        ?>

        <form action="procesos/procesar_rapido.php" method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
                <label>¿Qué estás ofreciendo?</label>
                <input type="text" name="titulo" placeholder="Ej. Bicicleta de montaña, Servicio de plomería..." required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Precio ($ MXN)</label>
                    <input type="number" name="precio" placeholder="0.00" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Categoría</label>
                    <select name="categoria_id" required>
                        <option value="">Selecciona...</option>
                        <?php foreach($categorias as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nombre']); ?></option>
                        <?php endforeach; ?>
                        
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Ubicación (Ciudad)</label>
                <select name="ciudad" required>
                    <option value="">Selecciona tu ubicación...</option>
                    <option value="Chilchotla">Chilchotla</option>
                    <option value="Quimixtla">Quimixtla</option>
                    <option value="Gpe Victoria">Guadalupe Victoria</option>
                </select>
            </div>

            <div class="form-group">
                <label>Descripción detallada</label>
                <textarea name="descripcion" placeholder="Menciona el estado del artículo, detalles del servicio, horarios, etc." required></textarea>
            </div>

            <div class="form-group">
                <label>Foto del producto/servicio</label>
                <div class="file-upload" onclick="document.getElementById('imagen_producto').click();">
                    ? Haz clic para subir una foto (Obligatorio)
                </div>
                <input type="file" name="imagen_producto" id="imagen_producto" accept="image/jpeg, image/png, image/webp" required style="display: none;">
            </div>

            <button type="submit" class="btn-submit">Publicar Artículo</button>
        </form>
    </div>

</body>
</html>
