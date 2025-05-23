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

// Procesar añadir al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!$usuarioLogueado) {
        header('Location: InicioSesion.html');
        exit();
    }
    
    // Validar y procesar el producto
    $producto_id = $_POST['product_id'] ?? null;
    $talla = $_POST['product_size'] ?? '';
    $cantidad = intval($_POST['product_quantity'] ?? 1);
    
    if ($producto_id && $talla && $cantidad > 0) {
        // Aquí iría la lógica para añadir al carrito
        // Por ahora solo mostramos un mensaje
        $mensaje = "Producto añadido al carrito: ID $producto_id, Talla $talla, Cantidad $cantidad";
    }
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
        .product-section {
            margin: 20px 0;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }
        
        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px 0;
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
            height: 200px;
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #f9f9f9;
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
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
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
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
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
            background-color: #2337a8;
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
            color: #333;
            margin-bottom: 20px;
            text-align: center;
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
                <a href="InicioSesion.html" class="button_LogIn">
                    <span class="text_Button">Log In</span>                
                </a>
                <a href="Registro.html" class="button_SignIn">
                    <span class="text_Button">Sign In</span>
                </a>
            </div>
        <?php else: ?>
            <div class="user_actions" id="userButtons">
                <a href="BolsaCompra.html" class="button_Buy">
                    <i class="fa-solid fa-bag-shopping"></i>
                </a>
                <a href="Perfil.html" class="button_User">
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
                    '<?php echo addslashes($producto['nombre']); ?>',
                    '<?php echo addslashes($producto['descripcion']); ?>',
                    <?php echo $producto['precio']; ?>,
                    '<?php echo $producto['imagen']; ?>',
                    <?php echo json_encode($tallasStock); ?>
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
                    '<?php echo addslashes($producto['nombre']); ?>',
                    '<?php echo addslashes($producto['descripcion']); ?>',
                    <?php echo $producto['precio']; ?>,
                    '<?php echo $producto['imagen']; ?>',
                    <?php echo json_encode($tallasStock); ?>
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
                    '<?php echo addslashes($producto['nombre']); ?>',
                    '<?php echo addslashes($producto['descripcion']); ?>',
                    <?php echo $producto['precio']; ?>,
                    '<?php echo $producto['imagen']; ?>',
                    <?php echo json_encode($tallasStock); ?>
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

    <!-- Modal de Producto -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <img id="modalProductImage" src="" alt="Producto" class="modal-image">
            <h2 id="modalProductName"></h2>
            <p id="modalProductDescription" style="margin-bottom: 15px;"></p>
            <p id="modalProductPrice" style="font-size: 20px; font-weight: bold; color: #3046CF; margin-bottom: 20px;"></p>
            
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
        // Mostrar modal del producto
        function showProductModal(id, name, description, price, image, sizes) {
            const modal = document.getElementById('productModal');
            const sizeSelect = document.getElementById('productSize');
            
            // Llenar información del producto
            document.getElementById('modalProductId').value = id;
            document.getElementById('modalProductName').textContent = name;
            document.getElementById('modalProductDescription').textContent = description;
            document.getElementById('modalProductPrice').textContent = '$' + price.toFixed(2) + ' MXN';
            document.getElementById('modalProductImage').src = image;
            
            // Llenar opciones de talla
            sizeSelect.innerHTML = '';
            sizes.forEach(size => {
                const option = document.createElement('option');
                option.value = size.talla;
                option.textContent = size.talla + (size.cantidad <= 0 ? ' (Agotado)' : '');
                option.disabled = size.cantidad <= 0;
                sizeSelect.appendChild(option);
            });
            
            // Actualizar información de stock
            updateStockInfo();
            
            // Mostrar modal
            modal.style.display = 'block';
        }
        
        // Actualizar información de stock
        function updateStockInfo() {
            const sizeSelect = document.getElementById('productSize');
            const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
            const stockInfo = document.getElementById('stockInfo');
            const quantityInput = document.getElementById('productQuantity');
            const addButton = document.querySelector('.btn-primary');
            
            // Obtener el stock de la opción seleccionada
            const sizeText = selectedOption.value;
            const isDisabled = selectedOption.disabled;
            
            if (isDisabled) {
                stockInfo.textContent = 'Agotado';
                stockInfo.className = 'stock-info out-of-stock';
                quantityInput.disabled = true;
                addButton.disabled = true;
                addButton.style.opacity = '0.6';
            } else {
                // Aquí deberías tener acceso al stock real desde los datos del producto
                // Por ahora mostramos un mensaje genérico
                stockInfo.textContent = 'Disponible';
                stockInfo.className = 'stock-info in-stock';
                quantityInput.disabled = false;
                addButton.disabled = false;
                addButton.style.opacity = '1';
                
                // Establecer máximo según disponibilidad
                // quantityInput.max = stockDisponible;
            }
        }
        
        // Cerrar modal
        function closeModal() {
            document.getElementById('productModal').style.display = 'none';
        }
        
        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            const modal = document.getElementById('productModal');
            if (event.target == modal) {
                closeModal();
            }
        };
    </script>
</body>
</html>