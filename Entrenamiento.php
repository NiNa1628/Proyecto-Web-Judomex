<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Judomex</title>
        <link rel="website icon" type="png" href="assets/logo.png">
        <link rel="stylesheet" href="Entrenamiento.css" type="text/css"/>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    </head>
<body>
    <!-- Es la parte de la cabecera -->
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
        <div class="auth_buttons" id="sessionButtons">
            <a href="InicioSesion.html" class="button_LogIn">
                <span class="text_Button">Log In</span>                
            </a>
            <a href="Registro.html" class="button_SignIn">
                <span class="text_Button">Sign In</span>
            </a>
        </div>

        <!-- Botones de usuario (cuando SÍ hay usuario logueado) -->
        <div class="user_actions" id="userButtons" style="display: none;">
            <a href="BolsaCompra.html" class="button_Buy">
                <i class="fa-solid fa-bag-shopping"></i>
            </a>
            <a href="Perfil.html" class="button_User">
                <i class="fa-solid fa-user"></i>
            </a>
        </div>
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
            <div class="select_Button">
                <span class="seleccionado">Entrenamiento</span>
            </div>
        </a>
        <!-- Botón de Competencia -->
        <a href="Competencia.php" class="nav-link">
            <div class="noSelect_Button">
                <span class="noSeleccionado">Competencia</span>
            </div>
        </a>
    </section>

    <!-- Contenedor de proyección y extrangulamiento -->
    <section class="container_Arriba">
        <!-- Contenedor principal flexible -->
        <div class="carruseles-container">
            <!-- Primer carrusel - Técnicas de Proyección -->
            <section class="carrusel-seccion">
                <div class="texto_Seccion">Técnicas de Proyección</div>
                <div class="bar_proyeccion">
                    <div id="carouselProyecciones" class="carousel slide" data-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselProyecciones" data-bs-slide-to="0" class="active"></button>
                            <button type="button" data-bs-target="#carouselProyecciones" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#carouselProyecciones" data-bs-slide-to="2"></button>
                            <button type="button" data-bs-target="#carouselProyecciones" data-bs-slide-to="3"></button>
                          </div>
                        <div class="carousel-inner">
                            <!-- Items del carrusel como cards -->
                            <div class="carousel-item active">
                                <div class="card">
                                    <div class="card_content">
                                        <div class="imgen"><img src="assets/proy1.gif" alt=""></div>
                                        <div class="text_content">
                                            <div class="texto_Parte">1 - IPPON-SEOI-NAGE</div>
                                            <h6>Proyección por encima del hombro con una mano 1ª Hombro</h6>
                                        </div>
                                    </div>
                                    
                                    <p>
                                        Es una técnica de judo, también conocida como "lanzamiento con un brazo por encima de 
                                        la espalda" o "lanzamiento por un hombro". Es una proyección manual (te-waza) que se clasifica como 
                                        una de las 19 técnicas oficiales del Kodokan. 
                                    </p>
                                    
                                    <div class="button_container">
                                        <button class="btn-ver-mas" data-target="window-notice-1">Ver más</button>
                                    </div>

                                    <div class="window-notice" id="window-notice-1">
                                        <div class="content">
                                            <div class="content-text">
                                                <p>
                                                    Posición natural Shizentai, pero la forma de coger (kumi-Kata) el judogi de Uke es particular, 
                                                    Tori tiene que coger con la mano izquierda la manga derecha de Uke por encima del codo, un poco 
                                                    en el interior, Tori desequilibra a Uke hacia adelante, al mismo tiempo, Tori adelanta su pie 
                                                    derecho delante del pie derecho de Uke ligeramente hacia el interior. 
                                                    <br>
                                                    Simultáneamente, Tori flexiona las rodillas, suelta la solapa de su mano derecha y penetra su 
                                                    brazo por debajo de la axila derecha de Uke, ejerciendo una fuerte tracción de la mano izquierda 
                                                    sobre la manga derecha. 
                                                </p>
                                                <a href="#" class="read-more">Leer más</a>
                                                <div class="extended-description">
                                                    <p>
                                                        El contado con la espalda esta perfectamente establecido, Tori carga a Uke y le proyecta hacia 
                                                        adelante por acción de las manos y del movimiento de bascula de las caderas, rodillas en extensión.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="content-buttons"><a href="#" id="close-button">Aceptar</a></div>
                                        </div>
                                    </div>

                                </div> 
                            </div>

                            <div class="carousel-item">
                                <div class="card">
                                    <div class="card_content">
                                        <div class="imgen"><img src="assets/proy2.gif" alt=""></div>
                                        <div class="text_content">
                                            <div class="texto_Parte">2 – MOROTE-SEOI-NAGE</div>
                                            <h6>Proyección por encima del hombro con dos mano 2ª Hombro</h6>
                                        </div>
                                    </div>
                                    
                                    <p>
                                        Es una técnica de judo que consiste en proyectar al oponente (Uke) hacia adelante, utilizando una agarre 
                                        doble para controlar su hombro y su brazo. Es una técnica que requiere un buen equilibrio y coordinación para ejecutarla 
                                        correctamente.  
                                    </p>
                                    
                                    <div class="button_container">
                                        <button class="btn-ver-mas" data-target="window-notice-2">Ver más</button>
                                    </div>

                                    <div class="window-notice" id="window-notice-2">
                                        <div class="content">
                                            <div class="content-text">
                                                <p>
                                                    Tori introduce su pie derecho en el interior y delante del pie derecho de Uke. Sin soltar la solapa izquierda, 
                                                    con su mano derecha coloca su codo por debajo de la axila derecha de su adversario girando hacia la izquierda 
                                                    y retrocediendo el pie izquierdo dentro de los pies de Uke, Tori flexiona girando las caderas de forma que 
                                                    salga al exterior.
                                                    <br>
                                                    La ejecución de este movimiento es fuente de numerosas faltas, que el practicante sufrirá las consecuencias con 
                                                    el paso del tiempo. Si no agacha lo suficiente su codo estará colocado en mala posición y la tracción será 
                                                    imposible y dolorosa.
                                                </p>
                                                <a href="#" class="read-more">Leer más</a>
                                                <div class="extended-description">
                                                    <p>
                                                        Además, las caderas deben desbordar al exterior para que la columna vertebral sea oblicua y no vertical, pues 
                                                        en este ultimo caso las resistencia de Uke tendrá como fin el poner esta en extensión haciendo imposible la 
                                                        proyección y doloroso el ataque.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="content-buttons"><a href="#" id="close-button">Aceptar</a></div>
                                        </div>
                                    </div>
                                    
                                </div> 
                            </div>

                            <div class="carousel-item">
                                <div class="card">
                                    <div class="card_content">
                                        <div class="imgen"><img src="assets/proy3.gif" alt=""></div>
                                        <div class="text_content">
                                            <div class="texto_Parte">3 – KATA GURUMA</div>
                                            <h6>Rueda por los Hombros 3ª Hombro</h6>
                                        </div>
                                    </div>
                                    
                                    <p>
                                        Es una técnica de judo que involucra levantar al oponente sobre los hombros, como un "fireman's carry". Es una 
                                        proyección, una de las 40 técnicas reconocidas por Jigoro Kano, y forma parte del Gokyo no waza del Kodokan Judo. 
                                    </p>
                                    
                                        <div class="button_container">
                                            <button class="btn-ver-mas" data-target="window-notice-3">Ver más</button>
                                        </div>
    
                                        <div class="window-notice" id="window-notice-3">
                                            <div class="content">
                                                <div class="content-text">
                                                    <p>
                                                        Se desequilibra a Uke hacia adelante y a la derecha/izquierda diagonalmente, al tiempo que rodeando por dentro con 
                                                        la mano derecha/izquierda el muslo derecho/izquierdo de Uke, cargándolo sobre los hombros (la parte posterior del 
                                                        cuello debe quedar a la altura lateral de su cinturón) efectuando una amplia proyección en dirección del propio 
                                                        desequilibrio.
                                                    </p>
                                                    <a href="#" class="read-more">Leer más</a>
                                                    <div class="extended-description">
                                                        <p>
                                                            <strong>¿Por qué se prohibió Kata Guruma?</strong>
                                                            <br>
                                                            El tradicional Kata guruma ahora está prohibido debido a que se toca la pierna.
                                                            <br>
                                                            Pero en esta colección revelamos cómo el Kata Guruma ha sido modificado y aún puede resultar muy exitoso. Se 
                                                            incluyen variantes legales e ilegales de esta técnica.

                                                            El mejor lugar para comenzar es con el campeón mundial de Mongolia, Khashbaatar, quien muestra cómo su Kata 
                                                            guruma ha evolucionado para adaptarse a los cambios de reglas.
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="content-buttons"><a href="#" id="close-button">Aceptar</a></div>
                                            </div>
                                        </div>
                                        
                                </div>  
                            </div>

                            <div class="carousel-item">
                                <div class="card">
                                    <div class="card_content">
                                        <div class="imgen"><img src="assets/proy4.gif" alt=""></div>
                                        <div class="text_content">
                                            <div class="texto_Parte">4 - TAI-OTOSHI</div>
                                            <h6>Derribo del cuerpo 1ª Brazo</h6>
                                        </div>
                                    </div>
                                    
                                    <p>
                                        Es una técnica de judo que significa "caída del cuerpo" y pertenece al segundo grupo de las técnicas del Kodokan 
                                        Judo. Es una técnica de mano (Te-waza) en la que se utiliza la fuerza de los brazos para desequilibrar al oponente y hacer 
                                        que caiga.  
                                    </p>
                                    
                                    <div class="button_container">
                                        <button class="btn-ver-mas" data-target="window-notice-3">Ver más</button>
                                    </div>
    
                                    <div class="window-notice" id="window-notice-3">
                                        <div class="content">
                                            <div class="content-text">
                                                <p>
                                                    Tori retrocede el pie izquierdo y tira de la mano izquierda girando el cuerpo, pone su pie izquierdo delante y al 
                                                    exterior del pie izquierdo de Uke.
                                                    <br>
                                                    Sin interrumpir la tracción de su mano izquierda, Tori empuja a Uke con su mano derecha y coloca su pie derecho en 
                                                    el exterior y a la derecha del pie derecho de Uke, la pantorrilla por debajo de la tibia de su adversario. Tori 
                                                    tiene la pierna izquierda flexionada, la derecha, natural.
                                                </p>
                                                <a href="#" class="read-more">Leer más</a>
                                                <div class="extended-description">
                                                    <p>
                                                        Tai-Otoshi es uno de los principales movimientos de competición, casi el primero, muy difícil de esquivar y 
                                                        de centrar.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="content-buttons"><a href="#" id="close-button">Aceptar</a></div>
                                        </div>
                                    </div>

                                </div> 
                            </div>

                        </div>
                    </div>
                </div>
            </section>
    
            <!-- Segundo carrusel - Técnicas de Estrangulamiento -->
            <section class="carrusel-seccion">
                <div class="texto_Seccion">Técnicas de Estrangulamiento</div>
                <div class="bar_proyeccion">
                    <div id="carouselEstrangulamientos" class="carousel slide" data-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselEstrangulamientos" data-bs-slide-to="0" class="active"></button>
                            <button type="button" data-bs-target="#carouselEstrangulamientos" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#carouselEstrangulamientos" data-bs-slide-to="2"></button>
                            <button type="button" data-bs-target="#carouselEstrangulamientos" data-bs-slide-to="3"></button>
                          </div>
                        <div class="carousel-inner">
                            <!-- Items del carrusel como cards -->
                            <div class="carousel-item active">
                                <div class="card">
                                    <div class="card_content">
                                        <div class="imgen"><img src="assets/extr1.gif" alt=""></div>
                                        <div class="text_content">
                                            <div class="texto_Parte">1 - KATA-JUJI-JIME</div>
                                            <h6>Estrangulación en cruz, manos opuestas</h6>
                                        </div>
                                    </div>
                                    
                                    <p>
                                        Es una técnica de (medio estrangulamiento cruzado) un Shime waza (técnica de estrangulamiento) en el que Tori (jugador
                                        que ejecuta la técnica) agarra el collar de Uke (jugador que recibe el ataque del oponente) con ambas manos cruzadas (agarre 
                                        normal y agarre invertido). 
                                    </p>
                                    
                                    <div class="button_container">
                                        <button class="btn-ver-mas" data-target="window-notice-4">Ver más</button>
                                    </div>

                                    <div class="window-notice" id="window-notice-4">
                                        <div class="content">
                                            <div class="content-text">
                                                <p>
                                                    Tori penetra muy profundamente los cuatro dedos de su mano izquierda y el pulgar de la mano derecha en el 
                                                    interior del cuello del judogui de Uke cruzando los antebrazos.
                                                    Tori aprieta fuertemente las manos y abriendo los codos se acerca lo mas bajo posible.
                                                </p>
                                                <a href="#" class="read-more">Leer más</a>
                                                <div class="extended-description">
                                                    <p>
                                                        Es una de las doce técnicas de constricción del Kodokan Judo en el Lista shime-waza. En El Canon del 
                                                        Judo, se llama Katate-Juji-Jime.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="content-buttons"><a href="#" id="close-button">Aceptar</a></div>
                                        </div>
                                    </div>

                                </div> 
                            </div>

                            <div class="carousel-item">
                                <div class="card">
                                    <div class="card_content">
                                        <div class="imgen"><img src="assets/extr2.gif" alt=""></div>
                                        <div class="text_content">
                                            <div class="texto_Parte">2 – GYAKU-JUJI-JIME</div>
                                            <h6>Estrangulación en cruz con manos vueltas</h6>
                                        </div>
                                    </div>
                                    
                                    <p>
                                        Es una técnica de estrangulación en Judo, clasificada dentro de las Shime-waza donde busca someter al 
                                        oponente mediante la presión sobre las arterias carótidas utilizando un agarre específico con las manos cruzadas 
                                        y las palmas hacia arriba.
                                    </p>
                                    
                                    <div class="button_container">
                                        <button class="btn-ver-mas" data-target="window-notice-5">Ver más</button>
                                    </div>

                                    <div class="window-notice" id="window-notice-5">
                                        <div class="content">
                                            <div class="content-text">
                                                <p>
                                                    Tori sujeta las solapas o la parte posterior del cuello del oponente con los brazos cruzados y le estrangula 
                                                    haciendo presión con las manos.
                                                    <br>
                                                    Es importante el control del cuerpo del oponente mientras se ejecuta la técnica. Cualquiera de los dos brazos 
                                                    puede ir por encima, pero es siempre el brazo que pasa por encima el que hace el agarre normal.
                                                </p>
                                                <a href="#" class="read-more">Leer más</a>
                                                <div class="extended-description">
                                                    <p>
                                                        La técnica Gyaku Juji Jime se puede realizar desde arriba, de lateral o sobre la espalda de Uke.
                                                        <br>
                                                        Es una de las técnicas de shime-waza <strong>requeridas para el examen de 1er Dan</strong>.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="content-buttons"><a href="#" id="close-button">Aceptar</a></div>
                                        </div>
                                    </div>
                                    
                                </div> 
                            </div>

                            <div class="carousel-item">
                                <div class="card">
                                    <div class="card_content">
                                        <div class="imgen"><img src="assets/extr3.png" alt=""></div>
                                        <div class="text_content">
                                            <div class="texto_Parte">3 – ASHI-GATAME-JIME</div>
                                            <h6>Luxación por la Pierna</h6>
                                        </div>
                                    </div>
                                    
                                    <p>
                                        Es una técnica de Judo que combina elementos de Kansetsu-waza (técnicas de luxación articular) y Shime-waza 
                                        (técnicas de estrangulación) en la que se utiliza una o ambas piernas. Es una técnica compleja que requiere precisión en el 
                                        posicionamiento.
                                    </p>
                                    
                                        <div class="button_container">
                                            <button class="btn-ver-mas" data-target="window-notice-6">Ver más</button>
                                        </div>
    
                                        <div class="window-notice" id="window-notice-6">
                                            <div class="content">
                                                <div class="content-text">
                                                    <p>
                                                        Luxación de codo mediante el bloqueo con 1 o 2  piernas de Tori. Existen muchas formas de realizar esta técnica, 
                                                        ya sea en tendido prono como supino.
                                                        <br>
                                                        Tori, situado a un lado de Uke, que esta tendido boca abajo, engancha con una de sus piernas el brazo de este y, 
                                                        extendiéndolo o torciendolo, le luxa.
                                                    </p>
                                                    <a href="#" class="read-more">Leer más</a>
                                                    <div class="extended-description">
                                                        <p>
                                                            Aunque el nombre incluye "jime" (estrangulación), la esencia de esta técnica radica en usar la pierna para 
                                                            inmovilizar y crear palanca sobre el brazo del oponente, específicamente la articulación del codo, lo que a 
                                                            menudo conduce a una luxación.
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="content-buttons"><a href="#" id="close-button">Aceptar</a></div>
                                            </div>
                                        </div>
                                        
                                </div>  
                            </div>

                            <div class="carousel-item">
                                <div class="card">
                                    <div class="card_content">
                                        <div class="imgen"><img src="assets/extr4.gif" alt=""></div>
                                        <div class="text_content">
                                            <div class="texto_Parte">4 - HADAKA-JIME</div>
                                            <h6>Estrangulación directa sobre el cuello desnudo con el antebrazo</h6>
                                        </div>
                                    </div>
                                    
                                    <p>
                                        Es una técnica de estrangulación o que significa «estrangulación desnuda» es una tećnica muy conocida en Jiu Jitsu y Judo, y por 
                                        supuesto en Jiu Jitsu Brasileño y como técnica de defensa personal, donde también se la conoce como «Mataleón» con su variante 
                                        de la ejecución original. 
                                    </p>
                                    
                                    <div class="button_container">
                                        <button class="btn-ver-mas" data-target="window-notice-7">Ver más</button>
                                    </div>
    
                                    <div class="window-notice" id="window-notice-7">
                                        <div class="content">
                                            <div class="content-text">
                                                <p>
                                                    Tori aplica el borde radial de su muñeca derecha contra la garganta de Uke y acerca su hombro derecho lo mas 
                                                    próximo posible de la nuca de este.
                                                    <br>
                                                    Retrocediendo su pie izquierdo, bascula la cabeza de Uke hacia adelante por el empuje de su hombro y aprieta 
                                                    fuertemente hacia el.
                                                </p>
                                                <a href="#" class="read-more">Leer más</a>
                                                <div class="extended-description">
                                                    <p>
                                                        Hadaka Jime o el «mataleón» es una estrangulación que puede ser muy peligrosa. De hecho esta técnica puede 
                                                        llegar a encadenarse con una lesión cervical o rutura  directamente de cuello causando la muerte por hipoxia.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="content-buttons"><a href="#" id="close-button">Aceptar</a></div>
                                        </div>
                                    </div>

                                </div> 
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>

    <!-- Contenedor de inmovilización y ukemis -->
    <section class="container_Abajo">
        <!-- Contenedor principal flexible -->
        <div class="carruseles-container">
            <!-- Tercer carrusel - Técnicas de Inmovilización -->
            <section class="carrusel-seccion">
                <div class="texto_Seccion">Técnicas de Inmovilización</div>
                <div class="bar_proyeccion">
                    <div id="carouselInmovilizaciones" class="carousel slide" data-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselInmovilizaciones" data-bs-slide-to="0" class="active"></button>
                            <button type="button" data-bs-target="#carouselInmovilizaciones" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#carouselInmovilizaciones" data-bs-slide-to="2"></button>
                            <button type="button" data-bs-target="#carouselInmovilizaciones" data-bs-slide-to="3"></button>
                          </div>
                        <div class="carousel-inner">
                            <!-- Items del carrusel como cards -->
                            <div class="carousel-item active">
                                <div class="card">
                                    <div class="card_content">
                                        <div class="imgen2"><img src="assets/inm1.gif" alt=""></div>
                                        <div class="text_content">
                                            <div class="texto_Parte">1 - KATA-GATAME</div>
                                            <h6>Control por el hombro 1ª Inmovilización</h6>
                                        </div>
                                    </div>
                                    
                                    <p>
                                        Es una técnica de inmovilización o estrangulación que se utiliza en diversas artes marciales, incluyendo judo y 
                                        jiu-jitsu. Se caracteriza por la presión en el hombro y cuello del oponente, creando un bloqueo que dificulta 
                                        la movilidad o puede causar estrangulación. 
                                    </p>
                                    
                                    <div class="button_container">
                                        <button class="btn-ver-mas" data-target="window-notice-1">Ver más</button>
                                    </div>

                                    <div class="window-notice" id="window-notice-1">
                                        <div class="content">
                                            <div class="content-text">
                                                <p>
                                                    Pertenece al primer grupo de inmovilizaciones Kesa-Gatame.
                                                    <br>
                                                    Tori a la derecha de Uke, rodilla derecha al suelo y con la pierna izquierda estirada, empuja el 
                                                    brazo derecho de Uke hacia arriba, con su brazo derecho rodea la cabeza y el brazo derecho de Uke, 
                                                    aprieta luego sus dos manos, la una contra la otra, y empuja con la cabeza.
                                                </p>
                                                <a href="#" class="read-more">Leer más</a>
                                                <div class="extended-description">
                                                    <p>
                                                        <strong>PUNTO ESENCIAL.-</strong> Tori aprieta fuertemente sus manos colocando su cabeza lo mas abajo posible, 
                                                        la rodilla apoyada contra el cinturón.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="content-buttons"><a href="#" id="close-button">Aceptar</a></div>
                                        </div>
                                    </div>

                                </div> 
                            </div>

                            <div class="carousel-item">
                                <div class="card">
                                    <div class="card_content">
                                        <div class="imgen2"><img src="assets/inm2.gif" alt=""></div>
                                        <div class="text_content">
                                            <div class="texto_Parte">2 – AMI-SHIHO-GATAME</div>
                                            <h6>Control superior sobre cuatro puntos 2ª Inmovilización</h6>
                                        </div>
                                    </div>
                                    
                                    <p>
                                        Es una técnica de inmovilización o "osae waza" donde el judoca controla la parte superior del cuerpo de 
                                        su oponente, generalmente agarrándolo por el cinturón y presionando sus brazos hacia abajo. Se considera una técnica de inmovilización 
                                        de la parte superior del cuerpo.
                                    </p>
                                    
                                    <div class="button_container">
                                        <button class="btn-ver-mas" data-target="window-notice-2">Ver más</button>
                                    </div>

                                    <div class="window-notice" id="window-notice-2">
                                        <div class="content">
                                            <div class="content-text">
                                                <p>
                                                    Esta dentro del segundo principio de inmovilización.
                                                    <br>
                                                    Control por las cuatro esquinas, las piernas flexionadas, las rodillas a la altura de los hombres de Uke, las nalgas 
                                                    se apoyan sobre los talones, en esta posición los pies están en extensión.
                                                    <br>
                                                    Tori, según las reacciones, puede extender las piernas. Tori coge el cinturón de Uke de cada lado por debajo de los 
                                                    brazos.
                                                </p>
                                                <a href="#" class="read-more">Leer más</a>
                                                <div class="extended-description">
                                                    <p>
                                                        En ciertos casos, cuando no ha tenido tiempo de pasar por debajo de los hombros, coge entonces el cinturón de cada 
                                                        lado procurando tener los codos en el suelo, bien en contacto con las axilas, quedando libres los brazos de Uke.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="content-buttons"><a href="#" id="close-button">Aceptar</a></div>
                                        </div>
                                    </div>
                                    
                                </div> 
                            </div>

                            <div class="carousel-item">
                                <div class="card">
                                    <div class="card_content">
                                        <div class="imgen2"><img src="assets/inm3.png" alt=""></div>
                                        <div class="text_content">
                                            <div class="texto_Parte">3 – KUZURE-KAMI-SHIHO-GATAME</div>
                                            <h6>Variante del control superior sobre cuatro puntos 3ª Inmovilización</h6>
                                        </div>
                                    </div>
                                    
                                    <p>
                                        Es una técnica de inmovilización en judo que se traduce como "sostén de las cuatro esquinas superiores rota". Es una variante del Kami-Shiho-Gatame, 
                                        donde se modifica la forma de sujetar al oponente, rompiendo la posición original para crear una nueva inmovilización.  
                                    </p>
                                    
                                        <div class="button_container">
                                            <button class="btn-ver-mas" data-target="window-notice-3">Ver más</button>
                                        </div>
    
                                        <div class="window-notice" id="window-notice-3">
                                            <div class="content">
                                                <div class="content-text">
                                                    <p>
                                                        Esta dentro del segundo principio de inmovilización. Control por las cuatro esquinas, las piernas flexionadas, las 
                                                        rodillas a la altura de los hombres de Uke, las nalgas se apoyan sobre los talones, en esta posición los pies están en extensión.
                                                        <br>
                                                        Tori, según las reacciones, puede extender las piernas. Tori coge el cinturón de Uke de cada lado por debajo de los brazos, en 
                                                        ciertos casos, cuando no ha tenido tiempo de pasar por debajo de los hombros, coge entonces el cinturón de cada lado procurando 
                                                        tener los codos en el suelo, bien en contacto con las axilas, quedando libres los brazos de Uke.
                                                    </p>
                                                    <a href="#" class="read-more">Leer más</a>
                                                    <div class="extended-description">
                                                        <p>
                                                            La palabra "Kuzure" en japonés significa "roto", "derrumbarse" o "variación". 
                                                            <br>
                                                            En el contexto de las técnicas de Judo, a menudo indica una variante o una forma modificada de una técnica base.
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="content-buttons"><a href="#" id="close-button">Aceptar</a></div>
                                            </div>
                                        </div>
                                        
                                </div>  
                            </div>

                            <div class="carousel-item">
                                <div class="card">
                                    <div class="card_content">
                                        <div class="imgen2"><img src="assets/inm4.gif" alt=""></div>
                                        <div class="text_content">
                                            <div class="texto_Parte">4 - USHIRO-KESA-GATAME</div>
                                            <h6>Control del brazo-parte superior del cuerpo hacia atrás 4ª Inmovilización</h6>
                                        </div>
                                    </div>
                                    
                                    <p>
                                        Es una técnica de inmovilización en Judo, también conocida como "Sujeción de la bufanda trasera". Es una variación de la técnica 
                                        Kesa-Gatame, donde el judoka se posiciona de manera que su cuerpo esté de espaldas al oponente, en lugar de estar en posición lateral.   
                                    </p>
                                    
                                    <div class="button_container">
                                        <button class="btn-ver-mas" data-target="window-notice-3">Ver más</button>
                                    </div>
    
                                    <div class="window-notice" id="window-notice-3">
                                        <div class="content">
                                            <div class="content-text">
                                                <p>
                                                    En posición inversa a Hong-Kesa-Gatame, Tori sentado al lado derecho de Uke, las piernas extendidas y abiertas 
                                                    en la misma dirección de Uke.
                                                    <br>
                                                    Tori pasa su brazo derecho entre el cuerpo y el brazo derecho de Uke y va a coger lo más lejos posible el cuello 
                                                    del judogui de su contrincante, bloqueando así la axila de Uke; con su mano izquierda va a coger el cinturón de 
                                                    Uke, pasando su brazo izquierdo por debajo del brazo izquierdo de Uke, directamente por encima del hombro y brazo 
                                                    izquierdo de éste.
                                                </p>
                                                <a href="#" class="read-more">Leer más</a>
                                                <div class="extended-description">
                                                    <p>
                                                        Aunque es una técnica de inmovilización en sí misma, el Ushiro-kesa-gatame puede surgir de diversas situaciones 
                                                        de Ne-waza (trabajo en el suelo) y puede ser un punto de transición hacia otras inmovilizaciones, estrangulaciones 
                                                        o luxaciones.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="content-buttons"><a href="#" id="close-button">Aceptar</a></div>
                                        </div>
                                    </div>

                                </div> 
                            </div>

                        </div>
                    </div>
                </div>
            </section>
    
            <!-- Cuarto carrusel - Técnicas de Ukemis -->
            <section class="carrusel-seccion">
                <div class="texto_Seccion">Técnicas de Ukemis</div>
                <div class="bar_proyeccion">
                    <div id="carouselUkemis" class="carousel slide" data-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselUkemis" data-bs-slide-to="0" class="active"></button>
                            <button type="button" data-bs-target="#carouselUkemis" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#carouselUkemis" data-bs-slide-to="2"></button>
                            <button type="button" data-bs-target="#carouselUkemis" data-bs-slide-to="3"></button>
                          </div>
                        <div class="carousel-inner">
                            <!-- Items del carrusel como cards -->
                            <div class="carousel-item active">
                                <div class="card">
                                    <div class="card_content">
                                        <div class="imgen2"><img src="assets/uke1.jpg" alt=""></div>
                                        <div class="text_content">
                                            <div class="texto_Parte">1 - USHIRO-UKEMI</div>
                                            <h6>Caída sobre una superficie 1ª Ukemi</h6>
                                        </div>
                                    </div>
                                    
                                    <p>
                                        Es una de las técnicas fundamentales de caída o recepción (Ukemi-waza) en Judo. Su nombre se traduce como "caída hacia 
                                        atrás" o "recepción hacia atrás". Es crucial para aprender a caer de forma segura y evitar lesiones al ser proyectado o desequilibrado hacia atrás. 
                                    </p>
                                    
                                    <div class="button_container">
                                        <button class="btn-ver-mas" data-target="window-notice-4">Ver más</button>
                                    </div>

                                    <div class="window-notice" id="window-notice-4">
                                        <div class="content">
                                            <div class="content-text">
                                                <p>
                                                    Es la técnica de caída hacia atrás en Judo, donde se protege la cabeza metiendo el mentón al pecho y se distribuye el impacto golpeando el suelo simultáneamente 
                                                    con la parte posterior de los antebrazos, manteniendo el cuerpo redondeado. Es una habilidad fundamental para la seguridad de todo judoka.
                                                </p>
                                                <a href="#" class="read-more">Leer más</a>
                                                <div class="extended-description">
                                                    <p>
                                                        <strong>Importancia:</strong> Dominar el Ushiro Ukemi es esencial para la seguridad en la práctica del Judo. Permite a los practicantes recibir proyecciones
                                                        y desequilibrios hacia atrás sin riesgo de lesiones graves, lo que a su vez fomenta una práctica más segura y confiada.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="content-buttons"><a href="#" id="close-button">Aceptar</a></div>
                                        </div>
                                    </div>

                                </div> 
                            </div>

                            <div class="carousel-item">
                                <div class="card">
                                    <div class="card_content">
                                        <div class="imgen2"><img src="assets/uke2.jpg" alt=""></div>
                                        <div class="text_content">
                                            <div class="texto_Parte">2 – ZEMPO-KAITEN-UKEMI</div>
                                            <h6>Caída rodando hacia adelante 2ª Ukemi</h6>
                                        </div>
                                    </div>
                                    
                                    <p>
                                        Es una técnica de caída o recepción (Ukemi-waza) en Judo que se traduce como "caída rodando hacia adelante". A veces 
                                        también se le conoce como Mae Mawari Ukemi donde a diferencia del Mae Ukemi, implica una rodada controlada para disipar 
                                        la energía del impacto al caer hacia adelante.
                                    </p>
                                    
                                    <div class="button_container">
                                        <button class="btn-ver-mas" data-target="window-notice-5">Ver más</button>
                                    </div>

                                    <div class="window-notice" id="window-notice-5">
                                        <div class="content">
                                            <div class="content-text">
                                                <p>
                                                    Transformar la energía lineal de la caída hacia adelante en energía rotacional, permitiendo una recepción 
                                                    más suave y controlada, y facilitando una recuperación más rápida para poder continuar la acción.
                                                    <br>
                                                    Es una habilidad esencial para la seguridad y la fluidez en la práctica del Judo.
                                                </p>
                                                <a href="#" class="read-more">Leer más</a>
                                                <div class="extended-description">
                                                    <p>
                                                        El Zenpo-kaiten-ukemi es una habilidad valiosa en Judo, especialmente al enfrentarse a proyecciones 
                                                        frontales con mayor impulso. Permite al Uke (quien recibe la técnica) evitar caer bruscamente y 
                                                        potencialmente mantener la capacidad de reaccionar o contraatacar. 
                                                        <br>
                                                        También es una técnica fundamental en otras artes marciales y puede ser útil en situaciones cotidianas 
                                                        para evitar lesiones por caídas hacia adelante.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="content-buttons"><a href="#" id="close-button">Aceptar</a></div>
                                        </div>
                                    </div>
                                    
                                </div> 
                            </div>

                            <div class="carousel-item">
                                <div class="card">
                                    <div class="card_content">
                                        <div class="imgen2"><img src="assets/uke3.jpg" alt=""></div>
                                        <div class="text_content">
                                            <div class="texto_Parte">3 – YOKO-UKEMI</div>
                                            <h6>Caída lateral 3ª Ukemi</h6>
                                        </div>
                                    </div>
                                    
                                    <p>
                                        Es una de las técnicas fundamentales de caída o recepción (Ukemi-waza) en Judo. Su nombre se traduce como 
                                        "caída lateral" o "recepción lateral". Es esencial para aprender a caer de forma segura cuando se es proyectado o desequilibrado 
                                        hacia un lado.
                                    </p>
                                    
                                        <div class="button_container">
                                            <button class="btn-ver-mas" data-target="window-notice-6">Ver más</button>
                                        </div>
    
                                        <div class="window-notice" id="window-notice-6">
                                            <div class="content">
                                                <div class="content-text">
                                                    <p>
                                                        Disipar la fuerza del impacto de la caída lateral sobre una superficie más grande del cuerpo y proteger la cabeza, 
                                                        la columna vertebral y las articulaciones laterales.
                                                        <br>
                                                        Es una habilidad fundamental para la seguridad de todo judoka. Es esencial aprender a realizar el Yoko Ukemi tanto 
                                                        hacia la derecha como hacia la izquierda.
                                                    </p>
                                                    <a href="#" class="read-more">Leer más</a>
                                                    <div class="extended-description">
                                                        <p>
                                                            Es vital para la seguridad en Judo, ya que muchas proyecciones y desequilibrios ocurren lateralmente. Una buena 
                                                            ejecución del Yoko Ukemi permite a los practicantes caer de lado sin riesgo de lesiones significativas, lo que 
                                                            les permite entrenar con mayor confianza y seguridad.
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="content-buttons"><a href="#" id="close-button">Aceptar</a></div>
                                            </div>
                                        </div>
                                        
                                </div>  
                            </div>

                            <div class="carousel-item">
                                <div class="card">
                                    <div class="card_content">
                                        <div class="imgen2"><img src="assets/uke4.PNG" alt=""></div>
                                        <div class="text_content">
                                            <div class="texto_Parte">4 - MAE-UKEMI</div>
                                            <h6>Caída hacia adelante 4ª Ukemi</h6>
                                        </div>
                                    </div>
                                    
                                    <p>
                                        Es una de las técnicas fundamentales de caída o recepción (Ukemi-waza) en Judo. Su nombre se traduce como "caída hacia adelante".
                                        Es la forma básica de aprender a caer hacia adelante de manera segura, tradicionalmente implica una caída más plana sobre la parte frontal del cuerpo.
                                    </p>
                                    
                                    <div class="button_container">
                                        <button class="btn-ver-mas" data-target="window-notice-7">Ver más</button>
                                    </div>
    
                                    <div class="window-notice" id="window-notice-7">
                                        <div class="content">
                                            <div class="content-text">
                                                <p>
                                                    Es la técnica de evitar golpear la cara y distribuir el impacto. Sin embargo, es importante notar que existen variaciones 
                                                    y a menudo se enseña una forma ligeramente modificada que incluye un pequeño golpe con las manos para una mayor seguridad.
                                                    <br>
                                                    Distribuyendo el impacto de la caída sobre una superficie más grande del frente del cuerpo y evitar golpear la cara.
                                                </p>
                                                <a href="#" class="read-more">Leer más</a>
                                                <div class="extended-description">
                                                    <p>
                                                        El Mae Ukemi es una de las primeras ukemi que se enseña en Judo. Es esencial para la seguridad al practicar proyecciones 
                                                        frontales y desequilibrios hacia adelante. Aunque la forma tradicional es importante conocerla, la variación con el golpe 
                                                        de manos a menudo se considera más segura para los principiantes.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="content-buttons"><a href="#" id="close-button">Aceptar</a></div>
                                        </div>
                                    </div>

                                </div> 
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>

     <!-- Por si pongo créditos -->
    <div class="container2">

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
        // 1. Verificación de usuario
        const usuarioRegistrado = JSON.parse(localStorage.getItem('usuarioRegistrado'));
        const sessionButtons = document.getElementById('sessionButtons');
        const userButtons = document.getElementById('userButtons');
        
        if (usuarioRegistrado) {
            sessionButtons.style.display = 'none';
            userButtons.style.display = 'flex';
        }

        // 2. Inicialización de los carruseles
        const carouselProyecciones = new bootstrap.Carousel('#carouselProyecciones', {
            interval: 5000,
            wrap: true
        });
        
        const carouselEstrangulamientos = new bootstrap.Carousel('#carouselEstrangulamientos', {
            interval: 5000,
            wrap: true
        });

        const carouselInmovilizaciones = new bootstrap.Carousel('#carouselInmovilizaciones', {
            interval: 5000,
            wrap: true
        });
        
        const carouselUkemis = new bootstrap.Carousel('#carouselUkemis', {
            interval: 5000,
            wrap: true
        });

        // 3. Configuración inicial - Ocultar todos los extended-description
        document.querySelectorAll('.extended-description').forEach(desc => {
            desc.style.display = 'none';
        });

        // 4. Manejo de los botones "Ver más" para abrir ventanas
        document.querySelectorAll('.btn-ver-mas').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const card = this.closest('.card');
                const windowNotice = card.querySelector('.window-notice');
                
                if (windowNotice) {
                    windowNotice.style.display = 'flex';
                    
                    // Asegurar que el contenido extendido esté cerrado al abrir
                    const extendedDesc = windowNotice.querySelector('.extended-description');
                    if (extendedDesc) {
                        extendedDesc.style.display = 'none';
                        const readMoreLink = windowNotice.querySelector('.read-more');
                        if (readMoreLink) {
                            readMoreLink.textContent = 'Leer más';
                        }
                    }
                }
            });
        });

        // 5. Manejo de los botones "Leer más" dentro de las ventanas
        document.querySelectorAll('.read-more').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const extendedDesc = this.nextElementSibling;
                
                if (extendedDesc.style.display === 'block') {
                    extendedDesc.style.display = 'none';
                    this.textContent = 'Leer más';
                } else {
                    extendedDesc.style.display = 'block';
                    this.textContent = 'Leer menos';
                }
            });
        });

        // 6. Manejo de los botones "Aceptar" para cerrar ventanas
        document.querySelectorAll('.window-notice .content-buttons a').forEach(closeButton => {
            closeButton.addEventListener('click', function(e) {
                e.preventDefault();
                const windowNotice = this.closest('.window-notice');
                windowNotice.style.display = 'none';
            });
        });

        // 7. Cerrar al hacer clic fuera del contenido
        document.querySelectorAll('.window-notice').forEach(notice => {
            notice.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.style.display = 'none';
                }
            });
        });
    });

    </script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Bootstrap 5 JS Bundle con Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar si hay un usuario logueado (ejemplo con localStorage)
        const usuarioLogueado = localStorage.getItem('usuarioLogueado');
        
        const sessionButtons = document.getElementById('sessionButtons');
        const userButtons = document.getElementById('userButtons');
        
        if (usuarioLogueado) {
            // Ocultar botones de sesión y mostrar botones de usuario
            sessionButtons.style.display = 'none';
            userButtons.style.display = 'flex';
        } else {
            // Asegurarse que los botones de usuario están ocultos
            userButtons.style.display = 'none';
            sessionButtons.style.display = 'flex';
        }
    });
</script>
</body>
</html>
