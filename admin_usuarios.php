<?php
session_start();


if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: /Promotian1/");
    exit();
}

require_once 'includes/conexion.php';

try {
    
    $stmt = $conexion->query("SELECT id, nombre, apellido, correo, ciudad, rol, fecha_creacion FROM usuarios ORDER BY fecha_creacion DESC");
    $lista_usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error al cargar la base de datos.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <base href="/Promotian1/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Admin Promotian</title>
    <style>
        
        body, html { margin: 0; padding: 0; font-family: 'Segoe UI', Roboto, sans-serif; background-color: #f4f6f9; display: flex; height: 100vh; }
        
        .sidebar { width: 250px; background-color: #20314b; color: white; display: flex; flex-direction: column; }
        .sidebar-header { padding: 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-header img { height: 35px; filter: brightness(0) invert(1); margin-bottom: 10px; }
        .sidebar-header span { display: block; font-size: 12px; color: #d32f2f; font-weight: bold; text-transform: uppercase; }
        
        .sidebar-menu { list-style: none; padding: 0; margin: 20px 0; }
        .sidebar-menu li a { display: block; color: #cfd8dc; padding: 15px 25px; text-decoration: none; font-size: 15px; border-left: 4px solid transparent; transition: 0.3s; }
        .sidebar-menu li a:hover, .sidebar-menu li a.activo { background-color: rgba(255,255,255,0.05); color: white; border-left-color: #d32f2f; }
        .sidebar-footer { margin-top: auto; padding: 20px; }
        .sidebar-footer a { color: #ff5252; text-decoration: none; font-size: 14px; }

        .main-content { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .top-navbar { background-color: white; padding: 15px 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; }
        .top-navbar h2 { margin: 0; font-size: 20px; color: #333; }
        
        .dashboard-content { padding: 30px; }
        
        
        .tabla-contenedor { background: white; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background-color: #f8f9fa; padding: 15px 20px; font-size: 14px; color: #666; text-transform: uppercase; border-bottom: 2px solid #eee; }
        td { padding: 15px 20px; border-bottom: 1px solid #eee; font-size: 15px; color: #333; }
        tr:hover { background-color: #fcfcfc; }
        
        
        .badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .badge-comprador { background-color: #e3f2fd; color: #1976d2; }
        .badge-vendedor { background-color: #e8f5e9; color: #2e7d32; }
        .badge-admin { background-color: #ffebee; color: #c62828; }

        .btn-eliminar { background-color: #fee2e2; color: #991b1b; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: bold; transition: 0.3s; border: none; cursor: pointer; }
        .btn-eliminar:hover { background-color: #fca5a5; }

        .alerta { padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: bold; }
        .alerta-exito { background-color: #dcfce7; color: #166534; }
        .alerta-error { background-color: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>

    
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="assets/img/logo.png" alt="Logo">
            <span>Panel de Control</span>
        </div>
        <ul class="sidebar-menu">
            <li><a href="admin">Resumen</a></li>
            <li><a href="admin_usuarios" class="activo">Usuarios</a></li>
            
            <li><a href="admin_negocios">Negocios</a></li>
            <li><a href="admin_categorias"> Categorías</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="/Promotian1/">? Ir al sitio público</a>
        </div>
    </div>

    
    <div class="main-content">
        <div class="top-navbar">
            <h2>Gestión de Usuarios</h2>
            <div class="user-info">Administrador <?php echo htmlspecialchars($_SESSION['nombre']); ?></div>
        </div>

        <div class="dashboard-content">
            
            <?php 
            if(isset($_GET['exito'])) echo '<div class="alerta alerta-exito">El usuario y todos sus datos fueron eliminados correctamente.</div>';
            if(isset($_GET['error']) && $_GET['error'] == 'mismo_usuario') echo '<div class="alerta alerta-error">No puedes eliminar tu propia cuenta de administrador.</div>';
            ?>

            <div class="tabla-contenedor">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Ciudad</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($lista_usuarios as $usr): ?>
                        <tr>
                            <td>#<?php echo $usr['id']; ?></td>
                            <td><?php echo htmlspecialchars($usr['nombre'] . ' ' . $usr['apellido']); ?></td>
                            <td><?php echo htmlspecialchars($usr['correo']); ?></td>
                            <td>
                                <?php 
                                    if($usr['rol'] == 'administrador') echo '<span class="badge badge-admin">Admin</span>';
                                    elseif($usr['rol'] == 'vendedor') echo '<span class="badge badge-vendedor">Vendedor</span>';
                                    else echo '<span class="badge badge-comprador">Comprador</span>';
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($usr['ciudad']); ?></td>
                            <td>
                                
                                <?php if($usr['id'] !== $_SESSION['usuario_id']): ?>
                                    <a href="procesos/eliminar_usuario_admin.php?id=<?php echo $usr['id']; ?>" class="btn-eliminar" onclick="return confirm('ATENCIÓN: Eliminar a este usuario también borrará su negocio y todos sus productos. ¿Estás absolutamente seguro?');">Eliminar</a>
                                <?php else: ?>
                                    <span style="color: #999; font-size: 12px;">Tu cuenta</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>
</html>
