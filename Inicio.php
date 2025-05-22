<?php
session_start();

// Verificar sesión - FORMA CORRECTA
$usuarioLogueado = isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] != '';
$nombreUsuario = $_SESSION['usuario_nombre'] ?? '';

// Para depuración (puedes quitarlo después)
error_log("Datos de sesión: " . print_r($_SESSION, true));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JUDOMEX</title>
    <link rel="website icon" type="png" href="assets/logo.png">
    <link rel="stylesheet" href="Inicio.css" type="text/css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
            <div class="select_Button">
                <span class="seleccionado">Inicio</span>
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
            <div class="noSelect_Button">
                <span class="noSeleccionado">Competencia</span>
            </div>
        </a>
    </section>

    <!-- Parte del título de JUDOMEX -->
    <section class="titulo">
        <div>
            <img src="assets/titulo_JUDOMEX.png" alt="titulo">
        </div>
    </section>

    <!-- Sección de bienvenida -->
    <section class="container_Welcome">
        <span class="bienvenida">Bienvenidos al Mundo del Judo</span>
        <span class="historia" id="abrirHistoria" role="button" tabindex="0">Conoce su historia...</span>    
       
        <div class="window-notice" id="window-notice-historia">
            <div class="content">
                <div class="content-text">
                    <p>
                        El <strong>JUDO</strong> es un arte marcial japonés creado por Jigoro Kano a finales del siglo XIX. La palabra <strong>«judo»</strong> 
                        significa <strong>«el camino de la flexibilidad»</strong> en japonés. Esto refleja la idea de que este arte marcial puede 
                        ayudar a las personas a desarrollar su flexibilidad física y mental.
                        <br>
                        La historia del judo está inextricablemente ligada a la de otras artes marciales tradicionales japonesas, 
                        como el jujitsu. El jujitsu es un conjunto de técnicas de lucha enseñadas a los samuráis del periodo Edo 
                        (1600-1868). El objetivo de esta disciplina era entrenarlos para defenderse con las manos desnudas cuando 
                        se encontraran desarmados.
                        <br>
                        Jigoro Kano estudió varios estilos de jujitsu antes de crear el judo. Se dio cuenta de que las técnicas 
                        del ju-jitsu se habían vuelto inadecuadas para los tiempos modernos. Algunos de ellos eran peligrosos para 
                        aprender y la mayoría de los profesores no eran pedagógicos o enseñaban de forma arcaica.
                        <br>
                        Así que, inspirándose en la gimnasia occidental, Kanō decidió eliminar los movimientos peligrosos del 
                        ju-jitsu y codificar las técnicas restantes en forma de kata para que fueran más fáciles de enseñar. El 
                        objetivo era crear un nuevo arte marcial educativo, despojado de su vocación bélica, que ya no sería el 
                        ju-jitsu sino que se llamaría judo.
                        <br>
                        Las primeras clases de Jigoro Kano se impartieron en el Kodokan de Tokio. Este lugar legendario ha inspirado 
                        a campeones de todo el mundo.
                    </p>
                    <a href="#" class="read-more">Leer más</a>
                    <div class="extended-description">
                        <p>
                            El judo se introdujo en Europa a principios del siglo XX y rápidamente se popularizó en todo el mundo. Los 
                            maestros japoneses han viajado a Occidente para compartir sus conocimientos. Jigoro Kano siempre quiso que 
                            su arte marcial participara en grandes competiciones internacionales.
                            <br>
                            En 1964, el judo se convirtió en deporte olímpico en los Juegos Olímpicos de Tokio. Gracias a este acontecimiento, 
                            el judo se está desarrollando más rápidamente en todo el mundo. Aunque en un principio el judo estaba reservado 
                            a los hombres en las competiciones internacionales, en 1980 se coronó en Nueva York la primera campeona mundial
                             de judo, Jane Bridge, actualmente 8ª dan.
                            <br>
                            Por último, el judo se ha convertido en un deporte clave en los Juegos Olímpicos, practicado en casi todos los 
                            países del mundo. Para algunos, este arte marcial se ha convertido en una forma de vida que va más allá de una 
                            simple actividad física.
                        </p>
                    </div>
                </div>
                <div class="content-buttons"><a href="#" id="close-button">Aceptar</a></div>
            </div>
        </div>
    
        <div class="judo_action">
            <img src="assets/judo_action.png" alt="judo_action">
        </div>
    </section>

    <!-- Anuncio de Wolf Defense -->
    <section class="container_add">
        <div class="wolf_defense">
            <img src="assets/wolf_defense_logo.png" alt="wolf_defense">
            <span class="add1">¡Entrena con nosotros!</span>
            <span class="add2">Wolf Defense, ubicado en San Luis Potosí, es un destacado centro deportivo 
                especializado en artes marciales como judo y taekwondo. Los usuarios elogian la calidad y 
                profesionalismo de sus instructores, quienes demuestran una gran pasión por la enseñanza y el deporte.</span>
        </div>
        <div class="world_judo_day">
            <img src="assets/world_judo_day.png" alt="world_judo_day">
        </div>
    </section>
    
    <!-- Anuncios extras -->
    <section class="bar_containers">
        <div class="card">
            <img src="assets/add1.jpg" class="card-img-top" alt="Anuncio 1">
        </div>
        <div class="card">
            <img src="assets/add2.jpg" class="card-img-top" alt="Anuncio 2">
        </div>
        <div class="card">
            <img src="assets/add3.jpg" class="card-img-top" alt="Anuncio 3">
        </div>
        <div class="card">
            <img src="assets/add4.jpg" class="card-img-top" alt="Anuncio 4">
        </div>
        <div class="card">
            <img src="assets/add5.jpg" class="card-img-top" alt="Anuncio 5">
        </div>
    </section>

    <!-- Por si pongo créditos -->
    <div class="container2"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configuración del modal de historia
            const extendedDesc = document.querySelector('#window-notice-historia .extended-description');
            if (extendedDesc) extendedDesc.style.display = 'none';
            
            const historiaLink = document.getElementById('abrirHistoria');
            const historiaWindow = document.getElementById('window-notice-historia');
            
            if (historiaLink && historiaWindow) {
                historiaLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    historiaWindow.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                });
            }
        
            // 3. Manejo del botón "Leer más"
            const readMoreLink = document.querySelector('#window-notice-historia .read-more');
            if (readMoreLink && extendedDesc) {
                readMoreLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    if (extendedDesc.style.display === 'block') {
                        extendedDesc.style.display = 'none';
                        this.textContent = 'Leer más';
                    } else {
                        extendedDesc.style.display = 'block';
                        this.textContent = 'Leer menos';
                    }
                });
            }
        
            // 4. Manejo del botón "Aceptar"
            const closeButton = document.querySelector('#window-notice-historia .content-buttons a');
            if (closeButton) {
                closeButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    historiaWindow.style.display = 'none';
                    document.body.style.overflow = '';
                });
            }
        
            // 5. Cerrar al hacer clic fuera del contenido
            if (historiaWindow) {
                historiaWindow.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.style.display = 'none';
                        document.body.style.overflow = '';
                    }
                });
            }
        });
    </script>
</body>
</html>