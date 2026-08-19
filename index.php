<?php
session_start();

require_once 'includes/conexion.php';

try {
    
    $sql_productos = "SELECT p.id, p.titulo, p.precio, p.url_imagen, n.nombre AS negocio_nombre, n.ciudad 
                      FROM productos p 
                      INNER JOIN negocios n ON p.negocio_id = n.id 
                      ORDER BY p.id DESC 
                      LIMIT 8";
                      
    $ultimos_productos = $conexion->query($sql_productos)->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $ultimos_productos = []; 
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <base href="/Promotian1/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promotian - El Marketplace Local</title>
    <style>
        
        :root { --azul-oscuro: #20314b; --rojo-marca: #d32f2f; --gris-fondo: #f8f9fa; }
        
        html { scroll-behavior: smooth; }
        body, html { margin: 0; padding: 0; font-family: 'Segoe UI', Roboto, sans-serif; color: #333; background-color: #fff; }
        a { text-decoration: none; color: inherit; }

        
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 10px 50px; background-color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.05); min-height: 70px; box-sizing: border-box; }
        .logo { display: flex; align-items: center; }
        .logo img { height: 45px; width: auto; display: block; }
        .nav-links { display: flex; gap: 25px; font-weight: 500; }
        .nav-links a:hover { color: var(--rojo-marca); }
        .nav-buttons { display: flex; gap: 15px; }
        
        .btn-outline { border: 2px solid var(--azul-oscuro); color: var(--azul-oscuro); padding: 8px 20px; border-radius: 6px; font-weight: bold; transition: 0.3s; }
        .btn-outline:hover { background-color: var(--azul-oscuro); color: white; }
        .btn-solid { background-color: var(--rojo-marca); color: white; padding: 10px 20px; border-radius: 6px; font-weight: bold; transition: 0.3s; border: none; cursor: pointer; }
        .btn-solid:hover { background-color: #b72525; }

        
        .hero-section {
            position: relative;
            background-image: url('assets/img/fondo-inicio.jpg');
            background-size: cover;
            background-position: center;
            padding: 100px 20px;
            text-align: center;
            color: white;
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(18, 30, 45, 0.7);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 900px;
            width: 100%;
        }

        .hero-content h1 { font-size: 3.2rem; font-weight: 800; margin-bottom: 15px; }
        .hero-content p { font-size: 1.1rem; margin-bottom: 40px; color: #e2e8f0; }

        .search-box {
            display: flex;
            max-width: 700px;
            margin: 0 auto 30px auto;
            background: white;
            border-radius: 50px;
            padding: 6px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .search-box input {
            flex: 1;
            border: none;
            padding: 15px 25px;
            font-size: 16px;
            border-radius: 50px 0 0 50px;
            outline: none;
            color: #333;
        }

        .search-box button {
            background-color: #f59e0b;
            color: white;
            border: none;
            padding: 15px 35px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 50px;
            cursor: pointer;
            transition: 0.3s;
        }
        .search-box button:hover { background-color: #d97706; }

        .quick-categories {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
            margin-bottom: 50px;
        }
        .quick-categories a {
            color: white;
            text-decoration: none;
            padding: 8px 20px;
            border: 1px solid rgba(255,255,255,0.4);
            border-radius: 30px;
            font-size: 14px;
            transition: 0.3s;
        }
        .quick-categories a:hover {
            background: rgba(255,255,255,0.2);
            border-color: white;
        }

        .value-propositions {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .value-box {
            flex: 1;
            min-width: 220px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 25px 20px;
            border-radius: 16px;
            transition: transform 0.3s;
        }
        .value-box:hover { transform: translateY(-5px); background: rgba(255, 255, 255, 0.15); }
        .value-box h3 { margin: 0 0 10px 0; font-size: 1.3rem; color: #f8fafc; }
        .value-box p { margin: 0; font-size: 0.9rem; color: #cbd5e1; line-height: 1.5; }

        
        .seccion-padding { padding: 60px 50px; }
        .seccion-header h2 { color: var(--azul-oscuro); margin-bottom: 5px; font-size: 28px; }
        .seccion-header p { color: #666; margin-top: 0; margin-bottom: 40px; }

        
        .productos-section { background-color: #f0f2f5; }
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

        
        .como-funciona-section { border-top: 1px solid #eee; }
        .steps-grid { display: flex; justify-content: space-between; gap: 30px; margin-top: 40px; }
        .step-card { flex: 1; text-align: center; }
        .step-number { width: 55px; height: 55px; background-color: var(--azul-oscuro); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: bold; margin: 0 auto 20px auto; }
        .step-card h3 { color: var(--azul-oscuro); font-size: 18px; margin-bottom: 10px; }
        .step-card p { color: #666; font-size: 14px; line-height: 1.5; }

        .cta-banner { background-color: var(--rojo-marca); color: white; text-align: center; padding: 70px 20px; }
        .cta-banner h2 { font-size: 32px; margin: 0 0 15px 0; }
        .btn-blanco { background-color: white; color: var(--rojo-marca); padding: 14px 35px; border-radius: 8px; font-weight: bold; font-size: 16px; display: inline-block; transition: 0.3s; }
        .btn-blanco:hover { background-color: #f0f0f0; transform: translateY(-2px); }

        
        .footer { background-color: var(--azul-oscuro); color: white; padding: 60px 50px 20px 50px; }
        .footer-grid { display: flex; justify-content: space-between; gap: 40px; margin-bottom: 40px; }
        .footer-logo-col { flex: 2; }
        .footer-logo-col img { height: 35px; filter: brightness(0) invert(1); margin-bottom: 15px; }
        .footer-links-col { flex: 1; }
        .footer-links-col h4 { font-size: 16px; margin: 0 0 20px 0; }
        .footer-links-col ul { list-style: none; padding: 0; margin: 0; }
        .footer-links-col li { margin-bottom: 12px; }
        .footer-links-col a { color: rgba(255,255,255,0.7); font-size: 14px; }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; text-align: center; color: rgba(255,255,255,0.5); font-size: 13px; }

        
        @media (max-width: 768px) {
            .navbar { flex-direction: column; gap: 15px; padding: 15px; } 
            .nav-links { display: none; }
            .seccion-padding { padding: 40px 20px; }
            .steps-grid { flex-direction: column; gap: 40px; }
            .footer-grid { flex-direction: column; }
            
            .hero-content h1 { font-size: 2.2rem; }
            .value-propositions { flex-direction: column; }
            .search-box { flex-direction: column; border-radius: 15px; padding: 10px; background: transparent; box-shadow: none; }
            .search-box input { border-radius: 10px; margin-bottom: 10px; padding: 15px; }
            .search-box button { border-radius: 10px; width: 100%; }
        }
    </style>
</head>
<body>

    <header class="navbar">
        <a href="Promotian1/" class="logo"><img src="assets/img/logo.png" alt="Logo"></a>
        <nav class="nav-links">
            <a href="/Promotian1/">Inicio</a>
            <a href="#zona-productos">Explorar</a>
            <a href="publicacion_rapida">Publicar</a>
        </nav>
        <div class="nav-buttons">
            <?php if(isset($_SESSION['usuario_id'])) { ?>
                <a href="mi_cuenta" class="btn-outline">Mi Cuenta</a>
                <a href="procesos/cerrar_sesion.php" class="btn-solid" style="background-color: #666;">Salir</a>
            <?php } else { ?>
                <a href="login" class="btn-outline">Iniciar sesión</a>
                <a href="registro" class="btn-solid">Registrarse</a>
            <?php } ?>
        </div>
    </header>

    <section class="hero-section">
        <div class="hero-overlay"></div> 
        
        <div class="hero-content">
            <h1>Tu negocio local, al alcance de todos</h1>
            <p>Promotian conecta a emprendedores, negocios y clientes en un solo lugar. Publica, promociona y vende tus productos o servicios de forma sencilla y accesible.</p>

            <form action="/buscar" method="GET" class="search-box">
                <input type="text" name="q" placeholder="¿Qué estás buscando? Ej. repostería, ropa, servicios..." required>
                <button type="submit">Buscar</button>
            </form>

            <div class="quick-categories">
                <a href="buscar?q=Moda">Moda</a>
                <a href="buscar?q=Electrónica">Electrónica</a>
                <a href="buscar?q=Alimentos">Alimentos</a>
                <a href="buscar?q=Servicios">Servicios</a>
                <a href="buscar?q=Hogar">Hogar</a>
                <a href="buscar?q=Autos">Autos</a>
                <a href="buscar?q=Educación">Educación</a>
                <a href="buscar?q=Belleza">Belleza</a>
                <a href="buscar?q=Artesanías">Artesanías</a>
                <a href="buscar?q=Tecnología">Tecnología</a>
            </div>

            <div class="value-propositions">
                <div class="value-box">
                    <h3>Trato Directo</h3>
                    <p>Sin intermediarios ni comisiones, negocia directo por WhatsApp.</p>
                </div>
                <div class="value-box">
                    <h3>Tienda Propia</h3>
                    <p>Crea tu catálogo virtual y panel de control en minutos.</p>
                </div>
                <div class="value-box">
                    <h3>100% Local</h3>
                    <p>Descubre y apoya a los mejores emprendedores de tu comunidad.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="zona-productos" class="seccion-padding productos-section">
        <div class="seccion-header">
            <h2>Recién agregados en tu zona</h2>
            <p>Descubre lo último que tu comunidad tiene para ofrecer</p>
        </div>
        
        <div class="productos-grid">
            <?php if(count($ultimos_productos) > 0): ?>
                
                <?php foreach($ultimos_productos as $prod): ?>
                
                <a href="producto/<?php echo $prod['id']; ?>" class="prod-card">
                    <div class="prod-img">
                        <?php if(!empty($prod['url_imagen'])): ?>
                            <img src="<?php echo htmlspecialchars($prod['url_imagen']); ?>" alt="Producto">
                        <?php else: ?>
                            <span>📷</span>
                        <?php endif; ?>
                    </div>
                    <div class="prod-info">
                        <h3 class="prod-titulo"><?php echo htmlspecialchars($prod['titulo']); ?></h3>
                        <div class="prod-precio">$<?php echo number_format($prod['precio'], 2); ?></div>
                        <div class="prod-meta">
                            <p>🏪 <?php echo htmlspecialchars($prod['negocio_nombre']); ?></p>
                            <p>📍 <?php echo htmlspecialchars($prod['ciudad']); ?></p>
                        </div>
                    </div>
                </a>

                <?php endforeach; ?>

            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: white; border-radius: 12px; color: #666;">
                    <h3>Aún no hay publicaciones</h3>
                    <p>¡Sé el primero en vender algo en Promotian!</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="seccion-padding como-funciona-section">
        <div class="seccion-header">
            <h2>¿Cómo funciona?</h2>
            <p>Simple, rápido y sin complicaciones</p>
        </div>
        <div class="steps-grid">
            <div class="step-card"><div class="step-number">1</div><h3>Crea tu cuenta</h3><p>Regístrate gratis en minutos.</p></div>
            <div class="step-card"><div class="step-number">2</div><h3>Publica tu negocio</h3><p>Sube fotos, descripción y precios.</p></div>
            <div class="step-card"><div class="step-number">3</div><h3>Conecta y vende</h3><p>Clientes de tu zona te encuentran.</p></div>
            <div class="step-card"><div class="step-number">4</div><h3>Haz crecer tu negocio</h3><p>Gana visibilidad y expande tu alcance.</p></div>
        </div>
    </section>

    <section class="cta-banner">
        <h2>¿Tienes un negocio local?</h2>
        <p>Únete a Promotian y llega a miles de clientes en tu comunidad, ¡gratis!</p>
        <a href="registro" class="btn-blanco">Publicar mi negocio</a>
    </section>

    <footer class="footer">
        <div class="footer-grid">
            <div class="footer-logo-col">
                <img src="assets/img/logo.png" alt="Logo">
                <p>El marketplace local que conecta negocios y comunidades.</p>
            </div>
            <div class="footer-links-col">
                <h4>Cuenta</h4>
                <ul>
                    <li><a href="login">Iniciar sesión</a></li>
                    <li><a href="registro">Registrarse</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">&copy; 2026 Promotian. Todos los derechos reservados.</div>
    </footer>

</body>
</html>