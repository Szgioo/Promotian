<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /Promotian1/login");
    exit();
}

require_once 'includes/conexion.php';

try {
    
    $stmt = $conexion->prepare("SELECT nombre, apellido, correo, telefono, ciudad, rol, fecha_creacion, foto_perfil FROM usuarios WHERE id = :id");
    $stmt->execute([':id' => $_SESSION['usuario_id']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
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
    <title>Mi Cuenta - Promotian</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 50px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .logo img { height: 45px; width: auto; object-fit: contain; display: block; }
        .nav-links a { text-decoration: none; color: #666; font-weight: 500; margin-left: 20px; transition: color 0.3s; }
        .nav-links a:hover { color: #d32f2f; }

        .dashboard-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
        }

        .card-perfil {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            text-align: center;
        }

        .avatar-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 15px auto;
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            background-color: #20314b;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .btn-cambiar-foto {
            display: inline-block;
            margin-top: 10px;
            font-size: 13px;
            color: #1976d2;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-cambiar-foto:hover {
            text-decoration: underline;
        }

        .card-perfil h2 { margin: 15px 0 5px 0; color: #20314b; }
        .card-perfil p { margin: 0 0 20px 0; color: #666; font-size: 14px; }
        
        .badge-rol {
            display: inline-block;
            padding: 5px 12px;
            background-color: #e3f2fd;
            color: #1976d2;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .main-content { display: flex; flex-direction: column; gap: 30px; }
        .card-info, .card-accion { background-color: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .card-info h3 { margin: 0 0 20px 0; color: #20314b; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .info-item label { display: block; font-size: 12px; color: #888; margin-bottom: 4px; text-transform: uppercase; }
        .info-item span { font-size: 15px; font-weight: 500; color: #333; }

        .card-accion { background-color: #20314b; color: white; text-align: center; }
        .card-accion h3 { color: white; border: none; margin-bottom: 10px; font-size: 24px; }
        .card-accion p { opacity: 0.8; margin-bottom: 25px; }
        .btn-rojo { background-color: #d32f2f; color: white; text-decoration: none; padding: 12px 30px; border-radius: 6px; font-weight: bold; display: inline-block; transition: 0.3s; }
        .btn-rojo:hover { background-color: #b72525; }

        .alerta { padding: 10px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; text-align: center; }
        .alerta-exito { background-color: #dcfce7; color: #166534; }
        .alerta-error { background-color: #fee2e2; color: #991b1b; }

        @media (max-width: 768px) { .dashboard-container { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

    <div class="top-bar">
        <a href="/Promotian1/" class="logo">
            <img src="assets/img/logo.png" alt="Logo Promotian">
        </a>
        <div class="nav-links">
            <a href="/Promotian1/">Ir al inicio</a>
            <a href="procesos/cerrar_sesion.php" style="color: #d32f2f; font-weight: bold;">Cerrar sesión</a>
        </div>
    </div>

    <div class="dashboard-container">
        
        <div class="card-perfil">
            
            <?php
            if(isset($_GET['exito']) && $_GET['exito'] == 'foto') echo '<div class="alerta alerta-exito">Foto actualizada</div>';
            if(isset($_GET['error'])) {
                if($_GET['error'] == 'peso') echo '<div class="alerta alerta-error">La foto pesa más de 2MB</div>';
                if($_GET['error'] == 'formato') echo '<div class="alerta alerta-error">Usa formato JPG o PNG</div>';
            }
            ?>

            <div class="avatar-container">
                <?php if(!empty($usuario['foto_perfil'])): ?>
                    <img src="<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" alt="Perfil" class="avatar-img">
                <?php else: ?>
                    <div class="avatar-img"><?php echo strtoupper(substr($usuario['nombre'], 0, 1)); ?></div>
                <?php endif; ?>
            </div>

            
            <form action="procesos/subir_foto.php" method="POST" enctype="multipart/form-data" id="formFoto">
                <label for="foto_perfil" class="btn-cambiar-foto">📷 Cambiar foto</label>
                <input type="file" name="foto_perfil" id="foto_perfil" accept="image/jpeg, image/png, image/webp" style="display: none;" onchange="document.getElementById('formFoto').submit();">
            </form>

            <h2><?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?></h2>
            <p>Miembro desde <?php echo date("M Y", strtotime($usuario['fecha_creacion'])); ?></p>
            <span class="badge-rol"><?php echo htmlspecialchars($usuario['rol']); ?></span>
        </div>

        <div class="main-content">
            <div class="card-info">
                <h3>Mis Datos</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Correo Electrónico</label>
                        <span><?php echo htmlspecialchars($usuario['correo']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Teléfono</label>
                        <span><?php echo htmlspecialchars($usuario['telefono']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Ciudad</label>
                        <span><?php echo htmlspecialchars($usuario['ciudad']); ?></span>
                    </div>
                </div>
            </div>

            <?php if($usuario['rol'] == 'comprador') { ?>
            <div class="info-grid">
                <div class="card-accion" style="background-color: #20314b;">
                    <h3 style="font-size: 20px;">Crear Tienda / Negocio</h3>
                    <p style="font-size: 13px; margin-bottom: 20px;">Ideal para locales, restaurantes o empresas con marca propia.</p>
                    <a href="crear_negocio" class="btn-rojo" style="width: 80%; text-align: center; box-sizing: border-box;">Perfil de Negocio</a>
                </div>

                <div class="card-accion" style="background-color: #fff; border: 2px solid #d32f2f; color: #333;">
                    <h3 style="font-size: 20px; color: #d32f2f; border-bottom: none;">Venta Rápida</h3>
                    <p style="font-size: 13px; margin-bottom: 20px; color: #666;">Publica un solo producto o servicio al instante con tu propio nombre.</p>
                    <a href="publicacion_rapida" class="btn-rojo" style="width: 80%; text-align: center; box-sizing: border-box;">Publicar ahora</a>
                </div>
            </div>
            
            <?php } else { ?>
            <div class="card-accion" style="background-color: #f8f9fa; border: 2px dashed #20314b; color: #20314b;">
                <h3 style="color: #20314b; border-bottom: none;">Gestión de Tienda</h3>
                <p style="color: #666;">Administra tus productos, ofertas y perfil comercial.</p>
                <a href="panel_negocio" class="btn-rojo" style="background-color: #20314b;">Ir a mi Panel de Negocio</a>
            </div>
            <?php } ?>
        </div>
    </div>

</body>
</html>
