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
    <title>Perfil de Usuario</title>
    <link rel="website icon" type="png" href="assets/logo.png">
    <link rel="stylesheet" href="Equipo.css" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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

        <!-- Botones de sesión (cuando NO hay usuario logueado) -->
            <?php if ($usuarioLogueado): ?>
            <!-- Botones de usuario -->
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
            <img src="assets/<?php echo strtolower($usuario['genero']) === 'mujer' ? 'mujer.png' : 'hombre.png'; ?>" alt="Foto de perfil" class="profile-img">
            <button class="upload-btn">Cambiar foto</button>
            
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
                <button class="edit-btn" onclick="location.href='editar-perfil.php'">
                    <i class="fas fa-edit"></i> Editar Perfil
                </button>
                <button class="logout-btn" onclick="location.href='logout.php'">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </button>
            </div>
        </div>
    </div>
</body>
</html>