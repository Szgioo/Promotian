<?php
session_start();
require_once 'includes/conexion.php';


$busqueda = isset($_GET['q']) ? trim($_GET['q']) : '';
$resultados = [];


if (!empty($busqueda)) {
    try {
        
        $termino_busqueda = '%' . $busqueda . '%';
        
        
        $sql = "SELECT p.id, p.titulo, p.precio, p.url_imagen, n.nombre AS negocio_nombre, n.ciudad 
                FROM productos p 
                INNER JOIN negocios n ON p.negocio_id = n.id 
                LEFT JOIN categorias c ON p.categoria_id = c.id
                WHERE p.titulo LIKE :busqueda 
                   OR p.descripcion LIKE :busqueda 
                   OR c.nombre LIKE :busqueda
                ORDER BY p.id DESC";
                
        $stmt = $conexion->prepare($sql);
        $stmt->execute([':busqueda' => $termino_busqueda]);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch(PDOException $e) {
        
        $resultados = [];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <base href="/Promotian1/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados para "<?php echo htmlspecialchars($busqueda); ?>" - Promotian</title>
    <style>
        
        :root { --azul-oscuro: #20314b; --rojo-marca: #d32f2f; --gris-fondo: #f8f9fa; }
        body, html { margin: 0; padding: 0; font-family: 'Segoe UI', Roboto, sans-serif; color: #333; background-color: var(--gris-fondo); }
        a { text-decoration: none; color: inherit; }

        
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 10px 50px; background-color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.05); min-height: 70px; box-sizing: border-box; }
        .logo { display: flex; align-items: center; }
        .logo img { height: 45px; display: block; }
        .nav-links { display: flex; gap: 25px; font-weight: 500; }
        .nav-links a:hover { color: var(--rojo-marca); }
        .nav-buttons { display: flex; gap: 15px; }
        .btn-outline { border: 2px solid var(--azul-oscuro); color: var(--azul-oscuro); padding: 8px 20px; border-radius: 6px; font-weight: bold; transition: 0.3s; }
        .btn-outline:hover { background-color: var(--azul-oscuro); color: white; }
        .btn-solid { background-color: var(--rojo-marca); color: white; padding: 10px 20px; border-radius: 6px; font-weight: bold; transition: 0.3s; border: none; cursor: pointer; }
        
        
        .search-bar-mini { background-color: var(--azul-oscuro); padding: 30px 20px; text-align: center; }
        .search-container { max-width: 600px; margin: 0 auto; display: flex; border-radius: 8px; overflow: hidden; }
        .search-container input { flex: 1; padding: 15px 20px; border: none; font-size: 16px; outline: none; }
        .search-container button { background-color: var(--rojo-marca); color: white; border: none; padding: 0 30px; font-size: 16px; font-weight: bold; cursor: pointer; }

        
        .resultados-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; min-height: 50vh; }
        .resultados-header { margin-bottom: 30px; border-bottom: 2px solid #ddd; padding-bottom: 15px; }
        .resultados-header h1 { font-size: 24px; color: var(--azul-oscuro); margin: 0 0 5px 0; }
        .resultados-header p { color: #666; margin: 0; }
        
        
        .productos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 25px; }
        .prod-card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); transition: 0.3s; display: flex; flex-direction: column; cursor: pointer; }
        .prod-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        .prod-img { height: 180px; background-color: #eee; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .prod-img img { width: 100%; height: 100%; object-fit: cover; }
        .prod-info { padding: 20px; display: flex; flex-direction: column; flex: 1; }
        .prod-titulo { font-size: 16px; margin: 0 0 10px 0; color: #333; line-height: 1.4; }
        .prod-precio { font-size: 20px; font-weight: bold; color: var(--rojo-marca); margin: 0 0 15px 0; }
        .prod-meta { margin-top: auto; border-top: 1px solid #eee; padding-top: 15px; font-size: 13px; color: #666; }
        .prod-meta p { margin: 0 0 5px 0; display: flex; align-items: center; gap: 5px; }

        .sin-resultados { text-align: center; padding: 50px 20px; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .sin-resultados h2 { color: var(--azul-oscuro); margin-bottom: 10px; }

        @media (max-width: 768px) {
            .navbar { flex-direction: column; gap: 15px; padding: 15px; }
            .nav-links { display: none; }
        }
    </style>
</head>
<body>

    
    <header class="navbar">
        <a href="/Promotian1/" class="logo"><img src="assets/img/logo.png" alt="Logo"></a>
        <nav class="nav-links">
            <a href="/Promotian1/">Inicio</a>
            <a href="publicacion_rapida">Publicar</a>
        </nav>
        <div class="nav-buttons">
            <?php if(isset($_SESSION['usuario_id'])) { ?>
                <a href="mi_cuenta" class="btn-outline">Mi Cuenta</a>
                <a href="procesos/cerrar_sesion.php" class="btn-solid" style="background-color: #666;">Salir</a>
            <?php } else { ?>
                <a href="login" class="btn-outline">Iniciar sesión</a>
            <?php } ?>
        </div>
    </header>

    
    <div class="search-bar-mini">
        <form action="buscar" method="GET" class="search-container">
            <input type="text" name="q" value="<?php echo htmlspecialchars($busqueda); ?>" placeholder="Buscar otro producto o servicio..." required>
            <button type="submit">Buscar de nuevo</button>
        </form>
    </div>

    
    <div class="resultados-container">
        
        <?php if(!empty($busqueda)): ?>
            <div class="resultados-header">
                <h1>Resultados para: "<?php echo htmlspecialchars($busqueda); ?>"</h1>
                <p>Encontramos <?php echo count($resultados); ?> resultado(s) en tu zona.</p>
            </div>
            
            <?php if(count($resultados) > 0): ?>
                <div class="productos-grid">
                    <?php foreach($resultados as $prod): ?>
                    <a href="producto?id=<?php echo $prod['id']; ?>" class="prod-card">
                        <div class="prod-img">
                            <?php if(!empty($prod['url_imagen'])): ?>
                                <img src="<?php echo htmlspecialchars($prod['url_imagen']); ?>" alt="Producto">
                            <?php else: ?>
                                <span>?</span>
                            <?php endif; ?>
                        </div>
                        <div class="prod-info">
                            <h3 class="prod-titulo"><?php echo htmlspecialchars($prod['titulo']); ?></h3>
                            <div class="prod-precio">$<?php echo number_format($prod['precio'], 2); ?></div>
                            <div class="prod-meta">
                                <p>? <?php echo htmlspecialchars($prod['negocio_nombre']); ?></p>
                                <p>? <?php echo htmlspecialchars($prod['ciudad']); ?></p>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="sin-resultados">
                    <h2>No encontramos lo que buscas ?</h2>
                    <p>Intenta con otras palabras clave o revisa la ortografía.</p>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="sin-resultados">
                <h2>¿Qué estás buscando hoy?</h2>
                <p>Escribe algo en la barra de búsqueda de arriba para comenzar.</p>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>
