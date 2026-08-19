<?php
session_start();


if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: /Promotian1/");
    exit();
}

require_once 'includes/conexion.php';

try {
    
    $stmt = $conexion->query("SELECT * FROM categorias ORDER BY nombre ASC");
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error al cargar las categorías.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <base href="/Promotian1/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categorías - Admin</title>
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
        
        .dashboard-content { padding: 30px; display: grid; grid-template-columns: 1fr 2fr; gap: 30px; }
        
        
        .card { background: white; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 25px; }
        .card h3 { margin-top: 0; color: #20314b; margin-bottom: 20px; }
        
        .form-group { margin-bottom: 15px; }
        label { display: block; font-size: 14px; font-weight: 600; color: #333; margin-bottom: 8px; }
        input[type="text"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        .btn-submit { background-color: #20314b; color: white; border: none; padding: 12px; width: 100%; border-radius: 6px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .btn-submit:hover { background-color: #152238; }

        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background-color: #f8f9fa; padding: 12px 15px; font-size: 14px; color: #666; border-bottom: 2px solid #eee; }
        td { padding: 12px 15px; border-bottom: 1px solid #eee; font-size: 15px; }
        
        .btn-eliminar { color: #991b1b; background-color: #fee2e2; padding: 5px 10px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: bold; }
        .btn-eliminar:hover { background-color: #fca5a5; }

        .alerta { padding: 12px; border-radius: 6px; margin-bottom: 20px; grid-column: 1 / -1; font-weight: bold; text-align: center; }
        .alerta-exito { background-color: #dcfce7; color: #166534; }
        
        @media (max-width: 768px) { .dashboard-content { grid-template-columns: 1fr; } }
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
            <li><a href="admin_usuarios">Usuarios</a></li>
            <li><a href="admin_negocios">Negocios</a></li>
            <li><a href="admin_categorias" class="activo">Categorías</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="/Promotian1/">? Ir al sitio público</a>
        </div>
    </div>

    <div class="main-content">
        <div class="top-navbar">
            <h2>Gestión de Categorías</h2>
        </div>

        <div class="dashboard-content">
            
            <?php if(isset($_GET['exito']) && $_GET['exito'] == 'agregada'): ?>
                <div class="alerta alerta-exito">La categoría se agregó correctamente.</div>
            <?php endif; ?>
            <?php if(isset($_GET['exito']) && $_GET['exito'] == 'eliminada'): ?>
                <div class="alerta alerta-exito">La categoría fue eliminada.</div>
            <?php endif; ?>

            
            <div class="card" style="height: fit-content;">
                <h3>Agregar Nueva</h3>
                <form action="procesos/agregar_categoria.php" method="POST">
                    <div class="form-group">
                        <label>Nombre de la Categoría</label>
                        <input type="text" name="nombre_categoria" placeholder="Ej. Mascotas, Deportes..." required>
                    </div>
                    <button type="submit" class="btn-submit">+ Guardar Categoría</button>
                </form>
            </div>

            
            <div class="card">
                <h3>Categorías Actuales</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($categorias) > 0): ?>
                            <?php foreach($categorias as $cat): ?>
                            <tr>
                                <td>#<?php echo $cat['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($cat['nombre']); ?></strong></td>
                                <td>
                                    <a href="procesos/eliminar_categoria.php?id=<?php echo $cat['id']; ?>" class="btn-eliminar" onclick="return confirm('¿Seguro que deseas eliminar esta categoría?');">Eliminar</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" style="text-align:center;">No hay categorías registradas.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>
</html>
