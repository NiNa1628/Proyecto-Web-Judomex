<?php
session_start();

// Configuración de la base de datos
$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "judomex";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$base_datos", $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Verificar sesión
$usuarioLogueado = isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] != '';
$nombreUsuario = $_SESSION['usuario_nombre'] ?? '';

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Función para obtener productos por categoría
function obtenerProductosPorCategoria($pdo, $categoria) {
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE categoria = ?");
    $stmt->execute([$categoria]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener las tallas disponibles y stock de un producto
function obtenerTallasStock($pdo, $producto_id) {
    $stmt = $pdo->prepare("
        SELECT t.talla, s.cantidad 
        FROM stock s
        JOIN tallas t ON s.talla_id = t.id
        WHERE s.producto_id = ?
        ORDER BY t.id
    ");
    $stmt->execute([$producto_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener detalles de un producto
function obtenerProducto($pdo, $producto_id) {
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->execute([$producto_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Procesar añadir al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!$usuarioLogueado) {
        header('Location: InicioSesion.html');
        exit();
    }
    
    $producto_id = $_POST['product_id'] ?? null;
    $talla = $_POST['product_size'] ?? '';
    $cantidad = intval($_POST['product_quantity'] ?? 1);
    
    if ($producto_id && $talla && $cantidad > 0) {
        $producto = obtenerProducto($pdo, $producto_id);
        
        // Verificar stock disponible (implementación básica)
        $tallasStock = obtenerTallasStock($pdo, $producto_id);
        $stockDisponible = 0;
        foreach ($tallasStock as $ts) {
            if ($ts['talla'] == $talla) {
                $stockDisponible = $ts['cantidad'];
                break;
            }
        }
        
        if ($cantidad <= $stockDisponible) {
            // Crear item del carrito
            $item = [
                'producto_id' => $producto_id,
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'imagen' => $producto['imagen'],
                'talla' => $talla,
                'cantidad' => $cantidad,
                'subtotal' => $producto['precio'] * $cantidad
            ];
            
            // Verificar si el producto ya está en el carrito
            $encontrado = false;
            foreach ($_SESSION['carrito'] as &$productoCarrito) {
                if ($productoCarrito['producto_id'] == $producto_id && $productoCarrito['talla'] == $talla) {
                    $productoCarrito['cantidad'] += $cantidad;
                    $productoCarrito['subtotal'] = $productoCarrito['precio'] * $productoCarrito['cantidad'];
                    $encontrado = true;
                    break;
                }
            }
            
            if (!$encontrado) {
                $_SESSION['carrito'][] = $item;
            }
            
            $mensaje = "Producto añadido al carrito correctamente";
        } else {
            $mensaje = "No hay suficiente stock disponible";
        }
    }
}

// Procesar eliminar del carrito
if (isset($_GET['eliminar'])) {
    $indice = $_GET['eliminar'];
    if (isset($_SESSION['carrito'][$indice])) {
        unset($_SESSION['carrito'][$indice]);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Reindexar array
        $mensaje = "Producto eliminado del carrito";
    }
}

// Calcular total del carrito
$totalCarrito = 0;
foreach ($_SESSION['carrito'] as $item) {
    $totalCarrito += $item['subtotal'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Judomex - Equipo</title>
    <link rel="website icon" type="png" href="assets/logo.png">
    <link rel="stylesheet" href="Equipo.css" type="text/css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Estilos CSS mejorados */
        .stock{
            position: absolute;
            width: 90%;
            left: 5%;
            top: 24vh;
            height:auto;
        }

        .product-section {
            position: relative;
            bottom: 20px;
        }
        
        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 10px 0;
        }
        
        .product-card {
            width: 250px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .product-image {
            height: 300px;
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #FFFFFF;
        }
        
        .product-info {
            padding: 15px;
            text-align: center;
        }
        
        .product-name {
            font-size: 16px;
            margin: 10px 0;
            color: #333;
        }
        
        .product-price {
            font-weight: bold;
            color: #3046CF;
            font-size: 18px;
        }
        
        /* Modal styles */
        .modal {
            display: none;
            position: absolute;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: auto;
            background-color: rgba(0,0,0,0.7);
        }
        
        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 25px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover {
            color: #333;
        }
        
        .modal-image {
            max-width: 100%;
            height: 250px;
            object-fit: contain;
            margin-bottom: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        
        
        .form-group label {
            display: block;
            font-weight: bold;
        }
        
        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .btn-primary {
            background-color: #3046CF;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }
        
        .btn-primary:hover {
            background-color: #3046CF;
        }
        
        .stock-info {
            font-size: 14px;
            margin-top: 5px;
        }
        
        .in-stock {
            color: #28a745;
        }
        
        .out-of-stock {
            color: #dc3545;
        }
        
        .section-title {
            font-size: 24px;
            color: #3046CF;
            text-align: start;
            font-weight: bold;
        }
    </style>
</head>
<body class="<?php echo $usuarioLogueado ? 'logged-in' : ''; ?>">
    <!-- Encabezado -->
    <header class="header">
        <div class="logo">
            <img src="assets/logo.png" alt="Logo">
        </div>
        
        <div class="judomex_titulo">
            <judomex_titulo>JUDOMEX</judomex_titulo>
        </div>

        <section class="search_bar">
            <input type="text" class="search_text" placeholder="Busca aquí...">
            <div class="button_Search">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
        </section>

        <?php if (!$usuarioLogueado): ?>
            <div class="auth_buttons" id="sessionButtons">
                <a href="InicioSesion.php" class="button_LogIn">
                    <span class="text_Button">Log In</span>                
                </a>
                <a href="Registro.php" class="button_SignIn">
                    <span class="text_Button">Sign In</span>
                </a>
            </div>
        <?php else: ?>
            <div class="user_actions" id="userButtons">
                <a href="BolsaCompra.php" class="button_Buy">
                    <i class="fa-solid fa-bag-shopping"></i>
                </a>
                <a href="Perfil.php" class="button_User">
                    <i class="fa-solid fa-user"></i>
                </a>
            </div>
        <?php endif; ?>
    </header>

    <!-- Barra de navegación -->
    <section class="bar_buttons">
        <a href="Inicio.php" class="nav-link">
            <div class="noSelect_Button">
                <span class="noSeleccionado">Inicio</span>
            </div>
        </a>
        <a href="Equipo.php" class="nav-link">
            <div class="select_Button">
                <span class="seleccionado">Equipo</span>
            </div>
        </a>
        <a href="Academia.php" class="nav-link">
            <div class="noSelect_Button">
                <span class="noSeleccionado">Academia</span>
            </div>
        </a>
        <a href="Entrenamiento.php" class="nav-link">
            <div class="noSelect_Button">
                <span class="noSeleccionado">Entrenamiento</span>
            </div>
        </a>
        <a href="Competencia.php" class="nav-link">
            <div class="noSelect_Button">
                <span class="noSeleccionado">Competencia</span>
            </div>
        </a>
    </section>

    <!-- Mensaje de confirmación -->
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-success" style="margin: 20px auto; max-width: 500px; text-align: center;">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <section class="stock">
        <!-- Sección de Judogis -->
        <section class="product-section">
            <h2 class="section-title">Judogis</h2>
            <div class="product-container">
                <?php
                $judogis = obtenerProductosPorCategoria($pdo, 'judogi');
                foreach ($judogis as $producto): 
                    $tallasStock = obtenerTallasStock($pdo, $producto['id']);
                ?>
                    <div class="product-card" onclick="showProductModal(
                        <?php echo $producto['id']; ?>,
                        '<?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES); ?>',
                        '<?php echo htmlspecialchars($producto['descripcion'], ENT_QUOTES); ?>',
                        <?php echo $producto['precio']; ?>,
                        '<?php echo $producto['imagen']; ?>',
                        <?php echo htmlspecialchars(json_encode($tallasStock), ENT_QUOTES); ?>
                    )">
                        <div class="product-image" style="background-image: url('<?php echo $producto['imagen']; ?>')"></div>
                        <div class="product-info">
                            <h3 class="product-name"><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                            <p class="product-price">$<?php echo number_format($producto['precio'], 2); ?> MXN</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    
        <!-- Sección de Cintas -->
        <section class="product-section">
            <h2 class="section-title">Cintas</h2>
            <div class="product-container">
                <?php
                $cintas = obtenerProductosPorCategoria($pdo, 'cinta');
                foreach ($cintas as $producto): 
                    $tallasStock = obtenerTallasStock($pdo, $producto['id']);
                ?>
                    <div class="product-card" onclick="showProductModal(
                        <?php echo $producto['id']; ?>,
                        '<?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES); ?>',
                        '<?php echo htmlspecialchars($producto['descripcion'], ENT_QUOTES); ?>',
                        <?php echo $producto['precio']; ?>,
                        '<?php echo $producto['imagen']; ?>',
                        <?php echo htmlspecialchars(json_encode($tallasStock), ENT_QUOTES); ?>
                    )">
                        <div class="product-image" style="background-image: url('<?php echo $producto['imagen']; ?>')"></div>
                        <div class="product-info">
                            <h3 class="product-name"><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                            <p class="product-price">$<?php echo number_format($producto['precio'], 2); ?> MXN</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Sección de Rodilleras -->
        <section class="product-section">
            <h2 class="section-title">Rodilleras</h2>
            <div class="product-container">
                <?php
                $rodilleras = obtenerProductosPorCategoria($pdo, 'rodillera');
                foreach ($rodilleras as $producto): 
                    $tallasStock = obtenerTallasStock($pdo, $producto['id']);
                ?>
                    <div class="product-card" onclick="showProductModal(
                    <?php echo $producto['id']; ?>,
                    '<?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES); ?>',
                    '<?php echo htmlspecialchars($producto['descripcion'], ENT_QUOTES); ?>',
                    <?php echo $producto['precio']; ?>,
                    '<?php echo $producto['imagen']; ?>',
                    <?php echo htmlspecialchars(json_encode($tallasStock), ENT_QUOTES); ?>
                )">
                        <div class="product-image" style="background-image: url('<?php echo $producto['imagen']; ?>')"></div>
                        <div class="product-info">
                            <h3 class="product-name"><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                            <p class="product-price">$<?php echo number_format($producto['precio'], 2); ?> MXN</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </section>

    <!-- Modal de Producto -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <img id="modalProductImage" src="" alt="Producto" class="modal-image">
            <h5 id="modalProductName"></h5>
            <p id="modalProductDescription" style="margin-bottom: 12px;"></p>
            <p id="modalProductPrice" style="font-size: 16px; font-weight: bold; color: #3046CF; margin-bottom: 20px;"></p>
            
            <form method="post" id="productForm">
                <input type="hidden" name="product_id" id="modalProductId">
                
                <div class="form-group">
                    <label for="productSize">Talla:</label>
                    <select class="form-control" id="productSize" name="product_size" required onchange="updateStockInfo()">
                        <!-- Las opciones se llenarán con JavaScript -->
                    </select>
                    <p id="stockInfo" class="stock-info"></p>
                </div>
                
                <div class="form-group">
                    <label for="productQuantity">Cantidad:</label>
                    <input type="number" class="form-control" id="productQuantity" name="product_quantity" min="1" value="1" required>
                </div>
                
                <button type="submit" name="add_to_cart" class="btn-primary">
                    <i class="fas fa-shopping-cart"></i> Añadir al carrito
                </button>
            </form>
        </div>
    </div>

    <div class="container2"></div>

    <script>
    // Debug: Verificar que el script se carga
    console.log("Script de modal cargado - Versión corregida");
    
    // Función para mostrar el modal
    window.showProductModal = function(id, name, description, price, image, sizes) {
        console.log("Función showProductModal llamada con:", {id, name});
        
        const modal = document.getElementById('productModal');
        if (!modal) {
            console.error("No se encontró el elemento modal");
            return;
        }
        
        // Llenar información del producto
        document.getElementById('modalProductId').value = id;
        document.getElementById('modalProductName').textContent = name;
        document.getElementById('modalProductDescription').textContent = description;
        document.getElementById('modalProductPrice').textContent = '$' + parseFloat(price).toFixed(2) + ' MXN';
        document.getElementById('modalProductImage').src = image;
        
        // Llenar opciones de talla
        const sizeSelect = document.getElementById('productSize');
        sizeSelect.innerHTML = '';
        
        try {
            // Asegurarse de que sizes es un array
            if (typeof sizes === 'string') {
                sizes = JSON.parse(sizes);
            }
            
            sizes.forEach(size => {
                const option = document.createElement('option');
                option.value = size.talla;
                option.textContent = size.talla + (size.cantidad <= 0 ? ' (Agotado)' : '');
                option.disabled = size.cantidad <= 0;
                sizeSelect.appendChild(option);
            });
        } catch (e) {
            console.error("Error procesando tallas:", e);
        }
        
        updateStockInfo();
        modal.style.display = 'block';
    };
    
    function updateStockInfo() {
        const sizeSelect = document.getElementById('productSize');
        if (!sizeSelect || sizeSelect.options.length === 0) return;
        
        const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
        const stockInfo = document.getElementById('stockInfo');
        const quantityInput = document.getElementById('productQuantity');
        const addButton = document.querySelector('button[name="add_to_cart"]');
        
        if (selectedOption.disabled) {
            stockInfo.textContent = 'Agotado';
            stockInfo.className = 'stock-info out-of-stock';
            if (quantityInput) quantityInput.disabled = true;
            if (addButton) {
                addButton.disabled = true;
                addButton.style.opacity = '0.6';
            }
        } else {
            stockInfo.textContent = 'Disponible';
            stockInfo.className = 'stock-info in-stock';
            if (quantityInput) quantityInput.disabled = false;
            if (addButton) {
                addButton.disabled = false;
                addButton.style.opacity = '1';
            }
        }
    }
    
    window.closeModal = function() {
        document.getElementById('productModal').style.display = 'none';
    };
    
    // Event listeners mejorados
    document.addEventListener('DOMContentLoaded', function() {
        console.log("DOM completamente cargado");
        
        // Cerrar al hacer clic en la X
        const closeBtn = document.querySelector('.modal .close');
        if (closeBtn) {
            closeBtn.addEventListener('click', closeModal);
        }
        
        // Cerrar al hacer clic fuera del modal
        const modal = document.getElementById('productModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });
        }
        
        // Actualizar stock al cambiar talla
        const sizeSelect = document.getElementById('productSize');
        if (sizeSelect) {
            sizeSelect.addEventListener('change', updateStockInfo);
        }
    });
</script>
</body>
</html>