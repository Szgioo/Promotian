
async function cargarProductos() {
    try {
        
        const respuesta = await fetch('https://tudominio.com/api/productos.php');
        const productos = await respuesta.json();
        
        const contenedor = document.getElementById('contenedor-productos');
        contenedor.innerHTML = ''; 
        
        productos.forEach(producto => {
            const tarjeta = `
                <div class="producto-card">
                    <img src="${producto.image_url}" alt="${producto.title}">
                    <h3>${producto.title}</h3>
                    <p>$${producto.price}</p>
                    <button>Ver más</button>
                </div>
            `;
            contenedor.innerHTML += tarjeta;
        });
    } catch (error) {
        console.error("Error al cargar los productos:", error);
    }
}


document.addEventListener('DOMContentLoaded', cargarProductos);