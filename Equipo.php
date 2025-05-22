<?php
session_start();

// Conexión a la base de datos
$host = 'localhost';
$dbname = 'judomex';
$username = 'tu_usuario';
$password = 'tu_contraseña';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Verificar sesión
$usuarioLogueado = isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] != '';
$nombreUsuario = $_SESSION['usuario_nombre'] ?? '';

// Obtener productos por categoría
function obtenerProductosPorCategoria($pdo, $categoria) {
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE categoria = ?");
    $stmt->execute([$categoria]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Obtener tallas y stock para un producto
function obtenerTallasStock($pdo, $producto_id) {
    $stmt = $pdo->prepare("
        SELECT t.talla, s.cantidad 
        FROM stock s
        JOIN tallas t ON s.talla_id = t.id
        WHERE s.producto_id = ?
    ");
    $stmt->execute([$producto_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Procesar añadir al carrito
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    if (!$usuarioLogueado) {
        header("Location: InicioSesion.html");
        exit;
    }
    
    // Aquí iría la lógica para añadir al carrito
    // ...
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
        /* Estilos para el modal */
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
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .modal-image-container {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .modal-image {
            max-width: 100%;
            max-height: 300px;
            border-radius: 5px;
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
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group select, 
        .form-group input[type="number"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .add-to-cart {
            background-color: #3046CF;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        
        .add-to-cart:hover {
            background-color: #2337a8;
        }
        
        .stock-info {
            margin-top: 5px;
            font-size: 14px;
            color: #28a745;
        }
        
        .stock-info.out-of-stock {
            color: #dc3545;
        }
    </style>
    <script>
        // Objeto global para almacenar stock
        const productsDB = {};
    </script>
</head>
<body class="<?php echo $usuarioLogueado ? 'logged-in' : ''; ?>">
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

        <!-- Botones de sesión -->
        <div class="auth_buttons" id="sessionButtons" style="<?php echo $usuarioLogueado ? 'display:none' : 'display:flex' ?>">
            <a href="InicioSesion.html" class="button_LogIn">
                <span class="text_Button">Log In</span>                
            </a>
            <a href="Registro.html" class="button_SignIn">
                <span class="text_Button">Sign In</span>
            </a>
        </div>

        <!-- Botones de usuario -->
        <div class="user_actions" id="userButtons" style="<?php echo $usuarioLogueado ? 'display:flex' : 'display:none' ?>">
            <a href="BolsaCompra.html" class="button_Buy">
                <i class="fa-solid fa-bag-shopping"></i>
            </a>
            <a href="Perfil.html" class="button_User">
                <i class="fa-solid fa-user"></i>
            </a>
        </div>
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

    <!-- Sección de Judogis -->
    <section class="bar_judogi">
        <div class="container_texto">
            <span class="texto_Seccion">Judogi</span>
        </div>
        <?php
        $judogis = obtenerProductosPorCategoria($pdo, 'judogi');
        foreach ($judogis as $producto): 
            $tallasStock = obtenerTallasStock($pdo, $producto['id']);
            $stockData = [];
            foreach ($tallasStock as $ts) {
                $stockData[$ts['talla']] = $ts['cantidad'];
            }
        ?>
            <script>
                productsDB['<?= $producto['nombre'] ?>'] = {
                    sizes: <?= json_encode(array_column($tallasStock, 'talla')) ?>,
                    stock: <?= json_encode($stockData) ?>
                };
            </script>
            <div class="product-card" onclick="showProductDetail(
                '<?= $producto['nombre'] ?>', 
                '<?= $producto['precio'] ?>', 
                '<?= $producto['imagen'] ?>', 
                '<?= addslashes($producto['descripcion']) ?>'
            )">
                <div class="product-image" style="background-image: url('<?= $producto['imagen'] ?>')"></div>
                <div class="product-info">
                    <h3 class="product-name"><?= str_replace(' ', '<br>', $producto['nombre']) ?></h3>
                    <p class="product-price">$<?= number_format($producto['precio'], 2) ?> mxn</p>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <!-- Sección de Cintas -->
    <section class="bar_cintas">
        <div class="container_texto">
            <span class="texto_Seccion">Cintas</span>
        </div>
        <?php
        $cintas = obtenerProductosPorCategoria($pdo, 'cinta');
        foreach ($cintas as $producto): 
            $tallasStock = obtenerTallasStock($pdo, $producto['id']);
            $stockData = [];
            foreach ($tallasStock as $ts) {
                $stockData[$ts['talla']] = $ts['cantidad'];
            }
        ?>
            <script>
                productsDB['<?= $producto['nombre'] ?>'] = {
                    sizes: <?= json_encode(array_column($tallasStock, 'talla')) ?>,
                    stock: <?= json_encode($stockData) ?>
                };
            </script>
            <div class="product-card" onclick="showProductDetail(
                '<?= $producto['nombre'] ?>', 
                '<?= $producto['precio'] ?>', 
                '<?= $producto['imagen'] ?>', 
                '<?= addslashes($producto['descripcion']) ?>'
            )">
                <div class="product-image" style="background-image: url('<?= $producto['imagen'] ?>')"></div>
                <div class="product-info">
                    <h3 class="product-name"><?= str_replace(' ', '<br>', $producto['nombre']) ?></h3>
                    <p class="product-price">$<?= number_format($producto['precio'], 2) ?> mxn</p>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <!-- Sección de Rodilleras -->
    <section class="bar_rodilleras">
        <div class="container_texto">
            <span class="texto_Seccion">Rodilleras</span>
        </div>
        <?php
        $rodilleras = obtenerProductosPorCategoria($pdo, 'rodillera');
        foreach ($rodilleras as $producto): 
            $tallasStock = obtenerTallasStock($pdo, $producto['id']);
            $stockData = [];
            foreach ($tallasStock as $ts) {
                $stockData[$ts['talla']] = $ts['cantidad'];
            }
        ?>
            <script>
                productsDB['<?= $producto['nombre'] ?>'] = {
                    sizes: <?= json_encode(array_column($tallasStock, 'talla')) ?>,
                    stock: <?= json_encode($stockData) ?>
                };
            </script>
            <div class="product-card" onclick="showProductDetail(
                '<?= $producto['nombre'] ?>', 
                '<?= $producto['precio'] ?>', 
                '<?= $producto['imagen'] ?>', 
                '<?= addslashes($producto['descripcion']) ?>'
            )">
                <div class="product-image" style="background-image: url('<?= $producto['imagen'] ?>')"></div>
                <div class="product-info">
                    <h3 class="product-name"><?= str_replace(' ', '<br>', $producto['nombre']) ?></h3>
                    <p class="product-price">$<?= number_format($producto['precio'], 2) ?> mxn</p>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <div class="container2"></div>

    <script>
        // Función para mostrar el modal de producto
        function showProductDetail(name, price, image, description) {
            // Cerrar cualquier modal existente primero
            const existingModal = document.getElementById('productModal');
            if (existingModal) {
                existingModal.remove();
            }
            
            // Obtener información del producto
            const product = productsDB[name] || {
                sizes: ['Única'],
                stock: {'Única': 10}
            };
            
            // Crear opciones de talla
            let sizeOptions = '';
            for (const size of product.sizes) {
                const stock = product.stock[size] || 0;
                sizeOptions += `<option value="${size}" ${stock <= 0 ? 'disabled' : ''}>${size} ${stock <= 0 ? '(Agotado)' : ''}</option>`;
            }
            
            // Crear el modal
            const modal = document.createElement('div');
            modal.id = 'productModal';
            modal.className = 'modal';
            
            // Contenido del modal
            modal.innerHTML = `
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <div class="modal-image-container">
                        <img src="${image}" alt="${name}" class="modal-image">
                    </div>
                    <h4>${name}</h4>
                    <p class="price">$${price} mxn</p>
                    <p class="description">${description}</p>
                    <form method="post">
                        <input type="hidden" name="product_name" value="${name}">
                        <input type="hidden" name="product_price" value="${price}">
                        <input type="hidden" name="product_image" value="${image}">
                        <input type="hidden" name="product_description" value="${description}">
                        
                        <div class="form-group">
                            <label for="product_size">Talla:</label>
                            <select name="product_size" id="product_size" required onchange="updateStockInfo('${name}', this.value)">
                                ${sizeOptions}
                            </select>
                            <div id="stockInfo" class="stock-info"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="product_quantity">Cantidad:</label>
                            <input type="number" name="product_quantity" id="product_quantity" min="1" value="1" required>
                        </div>
                        
                        <button type="submit" name="add_to_cart" class="add-to-cart">
                            <i class="fas fa-shopping-cart"></i> Añadir al carrito
                        </button>
                    </form>
                </div>
            `;
            
            document.body.appendChild(modal);
            modal.style.display = 'block';
            
            // Actualizar información de stock al cargar
            const initialSize = document.getElementById('product_size').value;
            updateStockInfo(name, initialSize);
            
            // Configurar el evento para cerrar al hacer clic fuera
            modal.onclick = function(event) {
                if (event.target === modal) {
                    closeModal();
                }
            };
        }
        
        // Actualizar información de stock
        function updateStockInfo(productName, size) {
            const product = productsDB[productName];
            const stockInfo = document.getElementById('stockInfo');
            
            if (product && product.stock && product.stock[size] !== undefined) {
                const stock = product.stock[size];
                if (stock > 0) {
                    stockInfo.textContent = `Disponibles: ${stock}`;
                    stockInfo.className = 'stock-info';
                    
                    // Ajustar cantidad máxima
                    const quantityInput = document.getElementById('product_quantity');
                    quantityInput.max = stock;
                    if (quantityInput.value > stock) {
                        quantityInput.value = stock;
                    }
                    
                    // Habilitar botón si estaba deshabilitado
                    const addButton = document.querySelector('.add-to-cart');
                    addButton.disabled = false;
                    addButton.style.opacity = '1';
                } else {
                    stockInfo.textContent = 'Agotado';
                    stockInfo.className = 'stock-info out-of-stock';
                    
                    // Deshabilitar el botón de añadir al carrito
                    const addButton = document.querySelector('.add-to-cart');
                    addButton.disabled = true;
                    addButton.style.opacity = '0.6';
                }
            } else {
                stockInfo.textContent = '';
            }
        }

        // Cerrar modal
        function closeModal() {
            const modal = document.getElementById('productModal');
            if (modal) {
                modal.style.display = 'none';
                modal.remove();
            }
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