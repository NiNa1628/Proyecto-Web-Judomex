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

// Procesar confirmación de pago (para AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_pago'])) {
    // Verificar que el usuario esté logueado
    if (!$usuarioLogueado) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Debes iniciar sesión para realizar el pago']);
        exit();
    }

    // Verificar que el carrito no esté vacío
    if (empty($_SESSION['carrito'])) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'El carrito está vacío']);
        exit();
    }

    try {
        $pdo->beginTransaction();

        // Calcular total del carrito
        $totalCarrito = 0;
        foreach ($_SESSION['carrito'] as $item) {
            $totalCarrito += $item['precio'] * $item['cantidad'];
        }

        // 1. Crear la orden
        $stmt = $pdo->prepare("INSERT INTO ordenes (usuario_id, total, fecha_creacion) VALUES (?, ?, NOW())");
        $stmt->execute([$_SESSION['usuario_id'], $totalCarrito]);
        $ordenId = $pdo->lastInsertId();

        // 2. Insertar los items de la orden
        $stmt = $pdo->prepare("INSERT INTO orden_items (orden_id, producto_id, cantidad, precio_unitario, subtotal) 
                              VALUES (?, ?, ?, ?, ?)");

        foreach ($_SESSION['carrito'] as $item) {
            $subtotal = $item['precio'] * $item['cantidad'];
            $stmt->execute([
                $ordenId,
                $item['id'] ?? 0, // Asegúrate de que cada item del carrito tenga un 'id'
                $item['cantidad'],
                $item['precio'],
                $subtotal
            ]);
        }

        // 3. Vaciar el carrito
        unset($_SESSION['carrito']);

        $pdo->commit();

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Error al procesar el pago: ' . $e->getMessage()]);
        exit();
    }
}

// Procesar actualización de cantidades
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_carrito'])) {
    foreach ($_POST['cantidades'] as $indice => $cantidad) {
        if (isset($_SESSION['carrito'][$indice])) {
            $cantidad = intval($cantidad);
            if ($cantidad > 0) {
                $_SESSION['carrito'][$indice]['cantidad'] = $cantidad;
                $_SESSION['carrito'][$indice]['subtotal'] = $_SESSION['carrito'][$indice]['precio'] * $cantidad;
            } else {
                unset($_SESSION['carrito'][$indice]);
            }
        }
    }
    $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Reindexar array
    
    // Si es una petición AJAX, devolver JSON y terminar
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $totalCarrito = 0;
        foreach ($_SESSION['carrito'] as $item) {
            $totalCarrito += $item['subtotal'];
        }
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'total' => number_format($totalCarrito, 2)
        ]);
        exit();
    }
}

// Procesar eliminar producto
if (isset($_GET['eliminar'])) {
    $indice = $_GET['eliminar'];
    if (isset($_SESSION['carrito'][$indice])) {
        unset($_SESSION['carrito'][$indice]);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    }
}

// Calcular total
$totalCarrito = 0;
if (!empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $item) {
        $totalCarrito += $item['subtotal'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Judomex - Carrito de Compras</title>
    <link rel="website icon" type="png" href="assets/logo.png">
    <link rel="stylesheet" href="BolsaCompra.css" type="text/css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>       
        .cart-container {
            position: relative;
            width: 90%;
            margin: 100px auto 30px;
        }

        .cart-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
            display: flex;
            align-items: center;
        }
        
        .cart-item-img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            margin-right: 20px;
        }
        
        .quantity-input {
            width: 60px;
            text-align: center;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        
        .quantity-input:focus {
            border-color: #3046CF;
            box-shadow: 0 0 0 0.2rem rgba(48, 70, 207, 0.25);
            outline: none;
        }
        
        .summary-card {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        
        .btn-primary {
            background: #3046CF;
            border: 1px solid #3046CF;
            padding: 8px 15px;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .btn-primary:hover {
            background: #2338a7;
        }
        
        .btn-outline-secondary {
            background: #FFFFFF;
            border: 2px solid #FE0000;
            padding: 8px 15px;
            width: auto;
            color: black;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
            margin-left: 10px;
        }
        
        .btn-outline-secondary:hover {
            background: #FE0000;
            color: white;
            width: auto;
        }
        
        .cart-container h4 {
            color: #3046CF;
            margin-bottom: 20px;
        }
        
        .item-subtotal {
            font-weight: bold;
            color: #3046CF;
            transition: all 0.3s ease;
        }
        
        .text-danger {
            color: #dc3545 !important;
            text-decoration: none;
            display: inline-block;
            margin-top: 5px;
        }
        
        .text-danger:hover {
            text-decoration: underline;
        }
        
        .alert-info {
            margin-top: 20px;
        }

        /* Modal de confirmación */
        .confirmation-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        
        .confirmation-content {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .confirmation-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .confirmation-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .confirm-btn {
            background-color: #28a745;
            color: white;
        }
        
        .cancel-btn {
            background-color: #dc3545;
            color: white;
        }
        
        /* Modal de éxito */
        .success-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        
        .success-content {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .success-icon {
            color: #28a745;
            font-size: 50px;
            margin-bottom: 20px;
        }
        
        .success-btn {
            background-color: #3046CF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 20px;
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
    
    <div class="cart-container">
        <h4><strong>Tu Carrito de Compras</strong></h4>
        
        <?php if (empty($_SESSION['carrito'])): ?>
            <div class="alert alert-info">
                Tu carrito está vacío. <a href="Equipo.php" style="color: #3046CF;">¡Sigue comprando!</a>
            </div>
        <?php else: ?>
            <form method="post" action="BolsaCompra.php" id="cart-form">
                <div class="row">
                    <div class="col-md-8">
                        <?php foreach ($_SESSION['carrito'] as $indice => $item): ?>
                            <div class="cart-item">
                                <img src="<?= $item['imagen'] ?>" alt="<?= $item['nombre'] ?>" class="cart-item-img">
                                <div style="flex-grow: 1;">
                                    <h5><?= $item['nombre'] ?></h5>
                                    <p>Talla: <?= $item['talla'] ?></p>
                                    <p>Precio unitario: $<?= number_format($item['precio'], 2) ?></p>
                                </div>
                                <div style="margin-right: 20px;">
                                    <input type="number" name="cantidades[<?= $indice ?>]" 
                                           value="<?= $item['cantidad'] ?>" min="1" 
                                           class="form-control quantity-input" data-index="<?= $indice ?>"
                                           data-price="<?= $item['precio'] ?>">
                                </div>
                                <div style="text-align: right;">
                                    <p class="item-subtotal" id="subtotal-<?= $indice ?>">$<?= number_format($item['subtotal'], 2) ?></p>
                                    <a href="BolsaCompra.php?eliminar=<?= $indice ?>" class="text-danger">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <div style="margin-top: 20px;">
                            <button type="submit" name="actualizar_carrito" class="btn btn-primary">
                                <i class="fas fa-sync-alt"></i> Actualizar Carrito
                            </button>
                            <a href="Equipo.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Seguir Comprando
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="summary-card">
                            <h4>Resumen de Compra</h4>
                            <hr>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span>Subtotal:</span>
                                <span id="cart-subtotal">$<?= number_format($totalCarrito, 2) ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span>Envío:</span>
                                <span>$0.00</span>
                            </div>
                            <hr>
                            <div style="display: flex; justify-content: space-between; font-weight: bold;">
                                <span>Total:</span>
                                <span id="cart-total">$<?= number_format($totalCarrito, 2) ?></span>
                            </div>
                            <button type="button" id="proceedToCheckout" class="btn btn-success btn-block mt-3" style="padding: 10px;">
                                <i class="fas fa-credit-card"></i> Proceder al Pago
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <!-- Modal de Confirmación -->
    <div id="confirmationModal" class="confirmation-modal">
        <div class="confirmation-content">
            <h4>¿Estás seguro que deseas proceder con el pago?</h4>
            <p>Total a pagar: <span id="modalTotal">$<?= number_format($totalCarrito, 2) ?></span></p>
            <div class="confirmation-buttons">
                <button class="confirm-btn" id="confirmPayment">Sí, pagar ahora</button>
                <button class="cancel-btn" id="cancelPayment">Cancelar</button>
            </div>
        </div>
    </div>
    
    <!-- Modal de Éxito -->
    <div id="successModal" class="success-modal">
        <div class="success-content">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h4>¡Pago realizado con éxito!</h4>
            <p>Tu pedido ha sido procesado correctamente.</p>
            <p>Recibirás un correo con los detalles de tu compra.</p>
            <button class="success-btn" id="closeSuccessModal">Aceptar</button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Función para actualizar los totales
        function updateCartTotals() {
            let subtotal = 0;
            
            $('.quantity-input').each(function() {
                const index = $(this).data('index');
                const price = parseFloat($(this).data('price'));
                const quantity = parseInt($(this).val());
                const itemSubtotal = price * quantity;
                
                // Actualizar subtotal del item
                $(`#subtotal-${index}`).text(`$${itemSubtotal.toFixed(2)}`);
                subtotal += itemSubtotal;
            });
            
            // Actualizar subtotal y total del carrito
            $('#cart-subtotal, #cart-total, #modalTotal').text(`$${subtotal.toFixed(2)}`);
        }
        
        // Actualizar cuando cambia la cantidad
        $('.quantity-input').on('change', function() {
            updateCartTotals();
        });
        
        // Enviar formulario con AJAX para actualizar carrito
        $('#cart-form').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                type: 'POST',
                url: 'BolsaCompra.php',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Actualizar totales desde la respuesta del servidor
                        $('#cart-subtotal, #cart-total, #modalTotal').text(`$${response.total}`);
                    }
                },
                error: function() {
                    alert('Error al actualizar el carrito');
                    location.reload();
                }
            });
        });
        
        // Mostrar modal de confirmación al hacer clic en "Proceder al pago"
        $('#proceedToCheckout').on('click', function(e) {
            e.preventDefault();
            
            $('#confirmationModal').css('display', 'flex');
            
            // Cancelar pago
            $('#cancelPayment').on('click', function() {
                $('#confirmationModal').hide();
            });
            
            // Confirmar pago
            $('#confirmPayment').on('click', function() {
                // Mostrar carga
                $(this).html('<i class="fas fa-spinner fa-spin"></i> Procesando...');
                
                // Enviar pago
                $.ajax({
                    type: 'POST',
                    url: 'BolsaCompra.php',
                    data: { confirmar_pago: true },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#confirmationModal').hide();
                            
                            // Mostrar modal de éxito
                            $('#successModal').css('display', 'flex');
                            
                            // Cerrar modal de éxito
                            $('#closeSuccessModal').on('click', function() {
                                $('#successModal').hide();
                                window.location.href = 'Inicio.php';
                            });
                            
                            // Redireccionar después de 3 segundos
                            setTimeout(function() {
                                window.location.href = 'Inicio.php';
                            }, 3000);
                        } else {
                            // Mostrar error
                            alert(response.error || 'Error al procesar el pago');
                            $('#confirmPayment').text('Sí, pagar ahora');
                        }
                    },
                    error: function() {
                        alert('Error de conexión al procesar el pago');
                        $('#confirmPayment').text('Sí, pagar ahora');
                    }
                });
            });
        });
        
        // Cerrar modales al hacer clic fuera
        $(document).on('click', function(e) {
            if ($(e.target).hasClass('confirmation-modal')) {
                $('#confirmationModal').hide();
            }
            if ($(e.target).hasClass('success-modal')) {
                $('#successModal').hide();
                window.location.href = 'Inicio.php';
            }
        });
        
        // Actualizar al cargar la página
        updateCartTotals();
    });
    </script>
</body>
</html>