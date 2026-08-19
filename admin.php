<?php
session_start();


if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: /Promotian1/");
    exit();
}

require_once 'includes/conexion.php';

try {
    
    $total_usuarios = $conexion->query("SELECT COUNT(id) FROM usuarios")->fetchColumn();
    
    
} catch(PDOException $e) {
    $total_usuarios = "Error";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <base href="/Promotian1/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Promotian</title>
    <style>
        body, html {
            margin: 0; padding: 0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f6f9;
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #20314b;
            color: white;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header img {
            height: 35px;
            filter: brightness(0) invert(1);
            margin-bottom: 10px;
        }

        .sidebar-header span {
            display: block;
            font-size: 12px;
            color: #d32f2f;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }

        .sidebar-menu li a {
            display: block;
            color: #cfd8dc;
            padding: 15px 25px;
            text-decoration: none;
            font-size: 15px;
            border-left: 4px solid transparent;
            transition: 0.3s;
        }

        .sidebar-menu li a:hover, .sidebar-menu li a.activo {
            background-color: rgba(255,255,255,0.05);
            color: white;
            border-left-color: #d32f2f;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 20px;
        }

        .sidebar-footer a {
            color: #ff5252;
            text-decoration: none;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .top-navbar {
            background-color: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-navbar h2 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }

        .user-info {
            font-weight: 500;
            color: #666;
        }

        .dashboard-content {
            padding: 30px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-bottom: 4px solid #20314b;
        }

        .stat-card.rojo { border-bottom-color: #d32f2f; }
        
        .stat-card h3 {
            margin: 0 0 10px 0;
            font-size: 32px;
            color: #20314b;
        }

        .stat-card p {
            margin: 0;
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <img src="assets/img/logo.png" alt="Logo">
            <span>Panel de Control</span>
        </div>
        
        <ul class="sidebar-menu">
            <li><a href="admin" class="activo">Resumen</a></li>
            <li><a href="admin_usuarios">Usuarios</a></li>
            <li><a href="admin_negocios">Negocios</a></li>
            <li><a href="admin_categorias">Categorías</a></li>
  
        </ul>

        <div class="sidebar-footer">
            <a href="/Promotian1/">Ir al sitio público</a>
        </div>
    </div>

    <div class="main-content">
        
        <div class="top-navbar">
            <h2>Dashboard</h2>
            <div class="user-info">
                Hola, Administrador <?php echo htmlspecialchars($_SESSION['nombre']); ?>
            </div>
        </div>

        <div class="dashboard-content">
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h3><?php echo $total_usuarios; ?></h3>
                    <p>Usuarios Registrados</p>
                </div>
                
                <div class="stat-card rojo">
                    <h3>0</h3>
                    <p>Negocios Activos</p>
                </div>
                
                <div class="stat-card">
                    <h3>0</h3>
                    <p>Productos Publicados</p>
                </div>
                
                <div class="stat-card rojo">
                    <h3>8</h3>
                    <p>Categorías</p>
                </div>
            </div>

            <div style="background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <h3 style="margin-top: 0; color: #20314b;">Bienvenido al centro de mando</h3>
                <p style="color: #666; line-height: 1.6;">
                    Desde aquí podrás gestionar todo lo que ocurre en Promotian. En los próximos pasos daremos vida a los menús de la izquierda para que puedas suspender usuarios, aprobar nuevos comercios locales y editar las categorías del inicio.
                </p>
            </div>

        </div>
    </div>

</body>
</html>
