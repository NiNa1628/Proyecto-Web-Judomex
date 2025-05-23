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

// Obtener información del usuario
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Usuario no encontrado");
}

// Procesar cambio de foto de perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_foto'])) {
    $nuevaFoto = $_POST['foto_seleccionada'] ?? '';
    $fotosPermitidas = ['assets/hombre.png', 'assets/hombre2.png', 'assets/mujer1.png', 'assets/mujer2.png'];
    
    if (in_array($nuevaFoto, $fotosPermitidas)) {
        // Actualizar en la base de datos (asumiendo que tienes un campo 'foto_perfil')
        $stmt = $pdo->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id_usuario = ?");
        $stmt->execute([$nuevaFoto, $_SESSION['usuario_id']]);
        
        // Actualizar en la sesión
        $_SESSION['foto_perfil'] = $nuevaFoto;
        $usuario['foto_perfil'] = $nuevaFoto;
        
        // Redirigir para evitar reenvío del formulario
        header("Location: Perfil.php");
        exit();
    }
}

// Procesar edición de perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_perfil'])) {
    $datosActualizados = [
        'nombre' => $_POST['nombre'] ?? $usuario['nombre'],
        'apellidos' => $_POST['apellidos'] ?? $usuario['apellidos'],
        'email' => $_POST['email'] ?? $usuario['email'],
        'telefono' => $_POST['telefono'] ?? $usuario['telefono'],
        'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? $usuario['fecha_nacimiento'],
        'genero' => $_POST['genero'] ?? $usuario['genero'],
        'calle' => $_POST['calle'] ?? $usuario['calle'],
        'no_ext' => $_POST['no_ext'] ?? $usuario['no_ext'],
        'no_int' => $_POST['no_int'] ?? $usuario['no_int'],
        'colonia' => $_POST['colonia'] ?? $usuario['colonia'],
        'cp' => $_POST['cp'] ?? $usuario['cp'],
        'pais' => $_POST['pais'] ?? $usuario['pais'],
        'estado' => $_POST['estado'] ?? $usuario['estado'],
        'id_usuario' => $_SESSION['usuario_id']
    ];
    
    try {
        $stmt = $pdo->prepare("UPDATE usuarios SET 
            nombre = :nombre,
            apellidos = :apellidos,
            email = :email,
            telefono = :telefono,
            fecha_nacimiento = :fecha_nacimiento,
            genero = :genero,
            calle = :calle,
            no_ext = :no_ext,
            no_int = :no_int,
            colonia = :colonia,
            cp = :cp,
            pais = :pais,
            estado = :estado
            WHERE id_usuario = :id_usuario");
        
        $stmt->execute($datosActualizados);
        
        // Actualizar datos en sesión
        $_SESSION['usuario_nombre'] = $datosActualizados['nombre'];
        
        // Redirigir para evitar reenvío del formulario
        header("Location: Perfil.php");
        exit();
        
    } catch (PDOException $e) {
        $errorEdicion = "Error al actualizar el perfil: " . $e->getMessage();
    }
}

// Función para formatear fecha
function formatFecha($fecha) {
    if (empty($fecha)) return 'No especificada';
    
    $meses = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];
    
    $fechaObj = new DateTime($fecha);
    $dia = $fechaObj->format('d');
    $mes = $meses[(int)$fechaObj->format('m') - 1];
    $anio = $fechaObj->format('Y');
    
    return "$dia de $mes, $anio";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Judomex</title>
    <link rel="website icon" type="png" href="assets/logo.png">
    <link rel="stylesheet" href="Equipo.css" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .profile-container {
            position: absolute;
            display: flex;
            width: 90%;
            left: 5%;
            top: 14vh;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        .profile-picture {
            width: 300px;
            padding-right: 30px;
            border-right: 1px solid #eee;
            text-align: center;
        }
        
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #3046CF;
        }
        
        .upload-btn {
            background: #3046CF;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            margin-top: 15px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .upload-btn:hover {
            background: #2338a7;
        }
        
        .profile-info {
            flex: 1;
            padding-left: 30px;
        }
        
        .section-title {
            color: #3046CF;
            border-bottom: 2px solid #3046CF;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .info-item {
            margin-bottom: 15px;
        }
        
        .full-width {
            grid-column: span 2;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }
        
        .info-value {
            padding: 8px;
            background: #f9f9f9;
            border-radius: 4px;
            border-left: 3px solid #3046CF;
        }
        
        .action-buttons {
            margin-top: 30px;
            display: flex;
            gap: 15px;
        }
        
        .edit-btn, .logout-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .edit-btn {
            background: #3046CF;
            color: white;
        }
        
        .edit-btn:hover {
            background: #2338a7;
        }
        
        .logout-btn {
            background: #dc3545;
            color: white;
        }
        
        .logout-btn:hover {
            background: #bb2d3b;
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
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover {
            color: black;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .photo-options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }
        
        .photo-option {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .photo-option:hover {
            border-color: #3046CF;
        }
        
        .photo-option.selected {
            border-color: #3046CF;
            box-shadow: 0 0 10px rgba(48, 70, 207, 0.5);
        }
        
        .photo-option img {
            width:55%;
            height: 100%;
            object-fit: contain;
        }
    </style>
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

        <?php if ($usuarioLogueado): ?>
            <div class="user_actions" id="userButtons">
                <a href="BolsaCompra.php" class="button_Buy">
                    <i class="fa-solid fa-bag-shopping"></i>
                </a>
            </div>
        <?php endif; ?>
    </header>

    <div class="profile-container">
        <!-- Sección de foto de perfil -->
        <div class="profile-picture">
            <img src="assets/<?php echo htmlspecialchars($usuario['foto_perfil'] ?? (strtolower($usuario['genero']) === 'mujer' ? 'mujer.png' : 'hombre.png')); ?>" alt="Foto de perfil" class="profile-img">
            <button class="upload-btn" id="openPhotoModal">Cambiar foto</button>
            
            <div style="margin-top: 30px;">
                <h3 style="color: #3046CF; margin-bottom: 10px;">Miembro desde:</h3>
                <p id="member-since"><?php echo formatFecha($usuario['fecha_registro']); ?></p>
            </div>
        </div>

        <!-- Sección de información -->
        <div class="profile-info">
            <h2 class="section-title">Información Personal</h2>
            
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Nombre:</span>
                    <div class="info-value" id="profile-name"><?php echo htmlspecialchars($usuario['nombre']); ?></div>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Apellidos:</span>
                    <div class="info-value" id="profile-lastname"><?php echo htmlspecialchars($usuario['apellidos']); ?></div>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Fecha de Nacimiento:</span>
                    <div class="info-value" id="profile-birthdate"><?php echo formatFecha($usuario['fecha_nacimiento']); ?></div>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Género:</span>
                    <div class="info-value" id="profile-gender"><?php echo htmlspecialchars($usuario['genero']); ?></div>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Correo Electrónico:</span>
                    <div class="info-value" id="profile-email"><?php echo htmlspecialchars($usuario['email']); ?></div>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Teléfono:</span>
                    <div class="info-value" id="profile-phone"><?php echo htmlspecialchars($usuario['telefono'] ?? 'No especificado'); ?></div>
                </div>
            </div>
            
            <h2 class="section-title" style="margin-top: 30px;">Dirección</h2>
            
            <div class="info-grid">
                <div class="info-item full-width">
                    <span class="info-label">Calle:</span>
                    <div class="info-value" id="profile-street">
                        <?php 
                        $direccion = htmlspecialchars($usuario['calle']);
                        if (!empty($usuario['no_ext'])) {
                            $direccion .= ' #' . htmlspecialchars($usuario['no_ext']);
                        }
                        if (!empty($usuario['no_int'])) {
                            $direccion .= ' Int. ' . htmlspecialchars($usuario['no_int']);
                        }
                        echo $direccion;
                        ?>
                    </div>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Colonia:</span>
                    <div class="info-value" id="profile-neighborhood"><?php echo htmlspecialchars($usuario['colonia'] ?? 'No especificada'); ?></div>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Código Postal:</span>
                    <div class="info-value" id="profile-zip"><?php echo htmlspecialchars($usuario['cp'] ?? 'No especificado'); ?></div>
                </div>
                
                <div class="info-item">
                    <span class="info-label">País:</span>
                    <div class="info-value" id="profile-country"><?php echo htmlspecialchars($usuario['pais'] ?? 'No especificado'); ?></div>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Estado:</span>
                    <div class="info-value" id="profile-state"><?php echo htmlspecialchars($usuario['estado'] ?? 'No especificado'); ?></div>
                </div>
            </div>
            
            <div class="action-buttons">
                <button class="edit-btn" id="openEditModal">
                    <i class="fas fa-edit"></i> Editar Perfil
                </button>
                <button class="logout-btn" onclick="location.href='InicioSesion.php'">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </button>
            </div>
        </div>
    </div>

    <!-- Modal para cambiar foto -->
    <div id="photoModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Selecciona una nueva foto de perfil</h2>
            <form method="post" action="Perfil.php">
                <div class="photo-options">
                    <label class="photo-option">
                        <input type="radio" name="foto_seleccionada" value=".png" <?php echo ($usuario['foto_perfil'] ?? 'hombre.png') === 'hombre.png' ? 'checked' : ''; ?>>
                        <img src="assets/hombre.png" alt="Hombre 1">
                    </label>
                    <label class="photo-option">
                        <input type="radio" name="foto_seleccionada" value="hombre2.png" <?php echo ($usuario['foto_perfil'] ?? 'hombre.png') === 'hombre2.png' ? 'checked' : ''; ?>>
                        <img src="assets/hombre2.png" alt="Hombre 2">
                    </label>
                    <label class="photo-option">
                        <input type="radio" name="foto_seleccionada" value="mujer.png" <?php echo ($usuario['foto_perfil'] ?? 'hombre.png') === 'mujer.png' ? 'checked' : ''; ?>>
                        <img src="assets/mujer.png" alt="Mujer 1">
                    </label>
                    <label class="photo-option">
                        <input type="radio" name="foto_seleccionada" value="mujer2.png" <?php echo ($usuario['foto_perfil'] ?? 'hombre.png') === 'mujer2.png' ? 'checked' : ''; ?>>
                        <img src="assets/mujer2.png" alt="Mujer 2">
                    </label>
                </div>
                <button type="submit" name="cambiar_foto" class="btn btn-primary">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <!-- Modal para editar perfil -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Editar Perfil</h2>
            <?php if (isset($errorEdicion)): ?>
                <div class="alert alert-danger"><?php echo $errorEdicion; ?></div>
            <?php endif; ?>
            
            <form method="post" action="Perfil.php">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($usuario['apellidos']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($usuario['fecha_nacimiento']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="genero">Género:</label>
                    <select id="genero" name="genero">
                        <option value="Hombre" <?php echo $usuario['genero'] === 'Hombre' ? 'selected' : ''; ?>>Hombre</option>
                        <option value="Mujer" <?php echo $usuario['genero'] === 'Mujer' ? 'selected' : ''; ?>>Mujer</option>
                        <option value="Otro" <?php echo $usuario['genero'] === 'Otro' ? 'selected' : ''; ?>>Otro</option>
                    </select>
                </div>
                
                <h3 style="margin-top: 20px;">Dirección</h3>
                
                <div class="form-group">
                    <label for="calle">Calle:</label>
                    <input type="text" id="calle" name="calle" value="<?php echo htmlspecialchars($usuario['calle']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="no_ext">Número Exterior:</label>
                    <input type="text" id="no_ext" name="no_ext" value="<?php echo htmlspecialchars($usuario['no_ext']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="no_int">Número Interior:</label>
                    <input type="text" id="no_int" name="no_int" value="<?php echo htmlspecialchars($usuario['no_int']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="colonia">Colonia:</label>
                    <input type="text" id="colonia" name="colonia" value="<?php echo htmlspecialchars($usuario['colonia']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="cp">Código Postal:</label>
                    <input type="text" id="cp" name="cp" value="<?php echo htmlspecialchars($usuario['cp']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="pais">País:</label>
                    <input type="text" id="pais" name="pais" value="<?php echo htmlspecialchars($usuario['pais']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <input type="text" id="estado" name="estado" value="<?php echo htmlspecialchars($usuario['estado']); ?>">
                </div>
                
                <button type="submit" name="actualizar_perfil" class="btn btn-primary">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <script>
        // Manejo de los modales
        const photoModal = document.getElementById('photoModal');
        const editModal = document.getElementById('editModal');
        const openPhotoBtn = document.getElementById('openPhotoModal');
        const openEditBtn = document.getElementById('openEditModal');
        const closeBtns = document.getElementsByClassName('close');
        
        // Abrir modal de foto
        openPhotoBtn.onclick = function() {
            photoModal.style.display = 'block';
        }
        
        // Abrir modal de edición
        openEditBtn.onclick = function() {
            editModal.style.display = 'block';
        }
        
        // Cerrar modales al hacer clic en la X
        for (let i = 0; i < closeBtns.length; i++) {
            closeBtns[i].onclick = function() {
                photoModal.style.display = 'none';
                editModal.style.display = 'none';
            }
        }
        
        // Cerrar modales al hacer clic fuera del contenido
        window.onclick = function(event) {
            if (event.target == photoModal) {
                photoModal.style.display = 'none';
            }
            if (event.target == editModal) {
                editModal.style.display = 'none';
            }
        }
        
        // Resaltar foto seleccionada
        const photoOptions = document.querySelectorAll('.photo-option');
        photoOptions.forEach(option => {
            const radio = option.querySelector('input[type="radio"]');
            if (radio.checked) {
                option.classList.add('selected');
            }
            
            option.addEventListener('click', () => {
                photoOptions.forEach(opt => opt.classList.remove('selected'));
                option.classList.add('selected');
                radio.checked = true;
            });
        });
    </script>
</body>
</html>