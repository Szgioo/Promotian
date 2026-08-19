<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /Promotian1/login");
    exit();
}


if ($_SESSION['rol'] === 'vendedor') {
    header("Location: /Promotian1/panel_negocio");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <base href="/Promotian1/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Negocio - Promotian</title>
    <style>
        body, html {
            margin: 0; padding: 0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f0f2f5;
            color: #333;
        }

        .top-bar {
            display: flex; justify-content: space-between; align-items: center;
            padding: 20px 50px; background-color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .btn-regresar {
            text-decoration: none; color: #20314b; font-weight: 600;
            display: flex; align-items: center; gap: 8px;
        }
        
        .logo img { height: 40px; display: block; }

        .form-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }

        .form-container h1 { color: #20314b; margin-top: 0; font-size: 28px; }
        .form-container p { color: #666; margin-bottom: 30px; }

        .form-group { margin-bottom: 20px; }
        label { display: block; font-size: 14px; font-weight: 600; color: #20314b; margin-bottom: 8px; }
        
        input[type="text"], textarea, select {
            width: 100%; padding: 12px; border: 1px solid #ddd;
            border-radius: 8px; box-sizing: border-box; font-size: 15px; font-family: inherit;
        }
        
        textarea { resize: vertical; min-height: 100px; }
        input:focus, textarea:focus, select:focus { outline: none; border-color: #d32f2f; }

        .btn-submit {
            width: 100%; background-color: #d32f2f; color: white; border: none;
            padding: 15px; border-radius: 8px; font-size: 16px; font-weight: bold;
            cursor: pointer; margin-top: 10px; transition: 0.3s;
        }
        .btn-submit:hover { background-color: #b72525; }

        
        .file-upload {
            border: 2px dashed #ccc; padding: 20px; text-align: center;
            border-radius: 8px; cursor: pointer; background-color: #fafafa;
        }
        .file-upload:hover { border-color: #20314b; }

        .alerta-error {
            background-color: #fee2e2; color: #991b1b; padding: 12px;
            border-radius: 8px; margin-bottom: 20px; text-align: center; font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="top-bar">
        <a href="mi_cuenta" class="btn-regresar"><span>&larr;</span> Cancelar</a>
        <div class="logo"><img src="assets/img/logo.png" alt="Promotian"></div>
    </div>

    <div class="form-container">
        <h1>Registra tu Negocio</h1>
        <p>Estás a un paso de llegar a más clientes. Completa los datos de tu comercio.</p>

        <?php
        if (isset($_GET['error'])) {
            if ($_GET['error'] == 'bd') echo '<div class="alerta-error">Ocurrió un error. Inténtalo de nuevo.</div>';
            if ($_GET['error'] == 'imagen') echo '<div class="alerta-error">El logo debe ser JPG o PNG y pesar menos de 2MB.</div>';
        }
        ?>

        
        <form action="procesos/procesar_negocio.php" method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
                <label>Nombre del Negocio / Tienda</label>
                <input type="text" name="nombre_negocio" placeholder="Ej. Abarrotes Doña Mari" required>
            </div>

            <div class="form-group">
                <label>¿En qué ciudad te encuentras?</label>
                <select name="ciudad" required>
                    <option value="">Selecciona tu ubicación...</option>
                    <option value="Chilchotla">Chilchotla</option>
                    <option value="Quimixtla">Quimixtla</option>
                    <option value="Gpe Victoria">Guadalupe Victoria</option>
                    <option value="Chichiquila">Chichiquila</option>
                    <option value="Toecelo">Toecelo</option>
                    <option value="Otro">Otro municipio cercano</option>
                </select>
            </div>

            <div class="form-group">
                <label>Descripción corta</label>
                <textarea name="descripcion" placeholder="¿Qué vendes o qué servicios ofreces? Convence a tus clientes en un par de líneas." required></textarea>
            </div>

            <div class="form-group">
                <label>Logo del Negocio (Opcional)</label>
                <div class="file-upload" onclick="document.getElementById('logo_negocio').click();">
                    ? Haz clic aquí para subir tu logo (JPG o PNG)
                </div>
                <input type="file" name="logo_negocio" id="logo_negocio" accept="image/jpeg, image/png, image/webp" style="display: none;">
            </div>

            <button type="submit" class="btn-submit">Crear Negocio Ahora</button>
        </form>
    </div>

</body>
</html>
