<?php
session_start();

// Verificar sesión - FORMA CORRECTA
$usuarioLogueado = isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] != '';
$nombreUsuario = $_SESSION['usuario_nombre'] ?? '';

// Para depuración (puedes quitarlo después)
error_log("Datos de sesión: " . print_r($_SESSION, true));

// Procesar inscripción si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['competencia_id'])) {
    $competenciaId = $_POST['competencia_id'];
    
    try {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "judomex";
        
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        if ($conn->connect_error) {
            throw new Exception("Error de conexión: " . $conn->connect_error);
        }
        
        // Verificar si ya está inscrito
        $stmt = $conn->prepare("SELECT id_inscripcion FROM inscripciones_competencia 
                               WHERE id_usuario = ? AND id_competencia = ?");
        $stmt->bind_param("ii", $_SESSION['usuario_id'], $competenciaId);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $mensaje = "Ya estás inscrito en esta competencia";
        } else {
            // Registrar la inscripción
            $stmt = $conn->prepare("INSERT INTO inscripciones_competencia 
                                  (id_usuario, id_competencia, nombre_completo, categoria_peso, cinta, tipo_competencia) 
                                  VALUES (?, ?, ?, ?, ?, ?)");
            
            $stmt->bind_param("iissss", 
                $_SESSION['usuario_id'], 
                $competenciaId, 
                $_POST['nombre_completo'],
                $_POST['categoria_peso'], 
                $_POST['cinta'],
                $_POST['tipo_competencia']
            );
            
            if ($stmt->execute()) {
                $mensaje = "¡Inscripción exitosa! Recibirás un correo de confirmación.";
            } else {
                throw new Exception("Error al registrar la inscripción: " . $stmt->error);
            }
        }
        
        $conn->close();
    } catch (Exception $e) {
        $mensaje = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Judomex</title>
    <link rel="website icon" type="png" href="assets/logo.png">
    <link rel="stylesheet" href="Competencia.css" type="text/css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* CONTENEDOR PRINCIPAL DE CARDS */
        .containerCard {
            position: relative;
            top: 22vh;
            left: 5%;
            width: 90%;
            height: auto;
        }

        /* ESTILOS GENERALES DE LAS CARDS */
        .card {
            height: auto;
            border: none;
            width: 90%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            background: #FFFFFF;
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
        }

        /* IMAGEN DE LA CARD */
        .card-img-top {
            height: 350px;
            object-fit: contain;
            border-bottom: 3px solid #FE0000;
        }

        /* CUERPO DE LA CARD */
        .card-body {
            padding: 20px;
            height: auto;
            text-align: center;
        }

        .card-title {
            color: #000000;
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .card-text {
            color: #000000;
            font-size: 14px;
            margin-bottom: 20px;
        }

        /* BOTONES DE LAS CARDS */
        .btn-primary {
            background-color: #3046CF; 
            border: none;
            border-radius: 10px;
            padding: 10px 25px;
            font-weight: 600;
            font-size: 16px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2337a8;
            transform: translateY(-2px);
        }

        /* MODAL DE INSCRIPCIÓN */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-container {
            background-color: white;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            animation: modalFadeIn 0.3s ease-out;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-header {
            background-color: #3046CF;
            color: white;
            padding: 20px;
            position: relative;
        }

        .modal-title {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .close-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 1.5rem;
            color: white;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .close-modal:hover {
            transform: rotate(90deg);
            color: #f8f9fa;
        }

        .modal-body {
            padding: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #495057;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #3046CF;
            outline: none;
            box-shadow: 0 0 0 3px rgba(48, 70, 207, 0.1);
        }

        select.form-control {
            height: 45px;
        }

        .btn-submit {
            background-color: #3046CF;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: #2337a8;
            transform: translateY(-2px);
        }

        /* MODAL DE MENSAJE */
        .modal-mensaje {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-mensaje-content {
            background-color: white;
            border-radius: 12px;
            width: 90%;
            max-width: 400px;
            padding: 30px;
            text-align: center;
            animation: modalFadeIn 0.3s ease-out;
        }

        .modal-mensaje h3 {
            margin-top: 0;
            color: #3046CF;
        }

        .modal-mensaje button {
            background-color: #3046CF;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 20px;
        }

        /* Por si pongo créditos */
        .container2 {
            position: absolute;
            width: 100%;
            height: 33px;
            top: 98vh;
            background: #3046CF;
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
            <?php if (!$usuarioLogueado): ?>
            <!-- Botones de sesión -->
            <div class="auth_buttons" id="sessionButtons">
                <a href="InicioSesion.html" class="button_LogIn">
                    <span class="text_Button">Log In</span>                
                </a>
                <a href="Registro.html" class="button_SignIn">
                    <span class="text_Button">Sign In</span>
                </a>
            </div>
        <?php else: ?>
            <!-- Botones de usuario -->
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

    <!-- La barra de navegación -->
    <section class="bar_buttons">
        <!-- Botón de Inicio -->
        <a href="Inicio.php" class="nav-link">
            <div class="noSelect_Button">
                <span class="noSeleccionado">Inicio</span>
            </div>
        </a>
        <!-- Botón de Equipo -->
        <a href="Equipo.php" class="nav-link">
            <div class="noSelect_Button">
                <span class="noSeleccionado">Equipo</span>
            </div>
        </a>
        <!-- Botón de Academia -->
        <a href="Academia.php" class="nav-link">
            <div class="noSelect_Button">
                <span class="noSeleccionado">Academia</span>
            </div>
        </a>
        <!-- Botón de Entrenamiento -->
        <a href="Entrenamiento.php" class="nav-link">
            <div class="noSelect_Button">
                <span class="noSeleccionado">Entrenamiento</span>
            </div>
        </a>
        <!-- Botón de Competencia -->
        <a href="Competencia.php" class="nav-link">
            <div class="select_Button">
                <span class="seleccionado">Competencia</span>
            </div>
        </a>
    </section>

    <!-- Sección de Cards de Competencias -->
    <div class="containerCard">
        <div class="row">
            <!-- Card 1: Jornada municipal -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="assets/mun.png" class="card-img-top" alt="Jornada Municipal de Judo">
                    <div class="card-body">
                        <div class="card-title">Jornada de Judo</div>
                        <p class="card-text">
                            <strong>Fecha:</strong> 30 Abril 2025<br>
                            <strong>Lugar:</strong> San Luis Potosí<br>
                        </p>
                        <button class="btn btn-primary" onclick="mostrarModalInscripcion(1)">Inscríbete ahora</button>
                    </div>
                </div>
            </div>
            
            <!-- Card 2: Campeonato Nacional -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="assets/nac.png" class="card-img-top" alt="Campeonato Nacional de Judo">
                    <div class="card-body">
                        <div class="card-title">Campeonato Nacional de Judo</div>
                        <p class="card-text">
                            <strong>Fecha:</strong> 5-8 Septiembre 2025<br>
                            <strong>Lugar:</strong> Guadalajara<br>
                        </p>
                        <button class="btn btn-primary" onclick="mostrarModalInscripcion(2)">Inscríbete ahora</button>
                    </div>
                </div>
            </div>
            
            <!-- Card 3: Torneo Internacional -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="assets/inter.jpeg" class="card-img-top" alt="Torneo Internacional de Judo">
                    <div class="card-body">
                        <div class="card-title">Torneo International de Judo</div>
                        <p class="card-text">
                            <strong>Fecha:</strong> 4-5 Abril 2025<br>
                            <strong>Lugar:</strong> París, Francia<br>
                        </p>
                        <button class="btn btn-primary" onclick="mostrarModalInscripcion(3)">Inscríbete ahora</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Inscripción -->
    <div id="modalInscripcion" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h2 class="modal-title">Formulario de Inscripción</h2>
                <span class="close-modal" onclick="cerrarModal('modalInscripcion')">&times;</span>
            </div>
            <div class="modal-body">
                <form id="formInscripcion" method="post" action="Competencia.php">
                    <input type="hidden" id="competencia_id" name="competencia_id" value="">
                    
                    <div class="form-group">
                        <label for="nombre_completo">Nombre completo:</label>
                        <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required
                               value="<?php echo htmlspecialchars($nombreUsuario); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="categoria_peso">Categoría de peso:</label>
                        <select class="form-control" id="categoria_peso" name="categoria_peso" required>
                            <option value="">Seleccione su categoría</option>
                            <option value="-60kg">-60 kg</option>
                            <option value="-66kg">-66 kg</option>
                            <option value="-73kg">-73 kg</option>
                            <option value="-81kg">-81 kg</option>
                            <option value="-90kg">-90 kg</option>
                            <option value="-100kg">-100 kg</option>
                            <option value="+100kg">+100 kg</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="cinta">Cinta (Grado):</label>
                        <select class="form-control" id="cinta" name="cinta" required>
                            <option value="">Seleccione su cinta</option>
                            <option value="Blanca">Blanca</option>
                            <option value="Amarilla">Amarilla</option>
                            <option value="Naranja">Naranja</option>
                            <option value="Verde">Verde</option>
                            <option value="Azul">Azul</option>
                            <option value="Marrón">Marrón</option>
                            <option value="Negra">Negra</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="tipo_competencia">Tipo de competencia:</label>
                        <select class="form-control" id="tipo_competencia" name="tipo_competencia" required>
                            <option value="">Seleccione categoría</option>
                            <option value="Infantil">Infantil (8-12 años)</option>
                            <option value="Juvenil">Juvenil (13-17 años)</option>
                            <option value="Adulto">Adulto (18-35 años)</option>
                            <option value="Veterano">Veterano (+35 años)</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-submit">Confirmar Inscripción</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Mensaje -->
    <div id="mensajeModal" class="modal-mensaje" style="<?php echo !empty($mensaje) ? 'display: flex;' : ''; ?>">
        <div class="modal-mensaje-content">
            <h3><?php echo $mensaje; ?></h3>
            <button onclick="cerrarModal('mensajeModal')">Aceptar</button>
        </div>
    </div>

    <!-- Por si pongo créditos -->
    <div class="container2"></div>
    
    <script>
        // Mostrar modal de inscripción
        function mostrarModalInscripcion(competenciaId) {
            document.getElementById('competencia_id').value = competenciaId;
            document.getElementById('modalInscripcion').style.display = 'flex';
        }
        
        // Cerrar modales
        function cerrarModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        
        // Cerrar al hacer clic fuera del modal
        window.onclick = function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                document.getElementById('modalInscripcion').style.display = 'none';
            }
            if (event.target.classList.contains('modal-mensaje')) {
                document.getElementById('mensajeModal').style.display = 'none';
            }
        }
        
        // Mostrar modal de mensaje si hay mensaje
        <?php if (!empty($mensaje)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('mensajeModal').style.display = 'flex';
        });
        <?php endif; ?>
    </script>
</body>
</html>