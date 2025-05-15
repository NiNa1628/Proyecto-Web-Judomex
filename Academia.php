<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Judomex - Ubicación</title>
    <link rel="website icon" type="png" href="assets/logo.png">
    <link rel="stylesheet" href="Academia.css" type="text/css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* Estilos mejorados para el mapa */
        #map {
            height: 300px;
            width: 100%;
            margin: 10px 0;
            border-radius: 12px;
            border: 2px solid #e0e0e0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        #map:hover {
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }
        
        .location-container {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            top: 19vh;
            width: 90%;
            left: 5%;
            position: absolute;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .location-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            color: #2c3e50;
        }
        
        .location-header i {
            font-size: 24px;
            margin-right: 12px;
            color: #FE0000;
        }
        
        .location-info {
            background-color: white;
            padding: 5px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .info-item {
            margin: 10px 0;
            display: flex;
            align-items: center;
            font-size: 15px;
        }
        
        .info-item i {
            width: 24px;
            text-align: center;
            margin-right: 10px;
            color: #3046CF;
        }
        
        .btn-location {
            background: linear-gradient(135deg, #3046CF 0%, #2980b9 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-location:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            background: linear-gradient(135deg, #3046CF 0%, #3046CF 100%);
        }
        
        .btn-location i {
            margin-right: 8px;
        }
        
        .btn-reload {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            margin-left: 12px;
        }
        
        .btn-reload:hover {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
        }
        
        /* Estilo para el marcador personalizado */
        .custom-marker {
            background-color: #e74c3c;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }
        
        /* Animación de carga */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .spin {
            animation: spin 1s linear infinite;
        }
    </style>
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
            <div class="select_Button">
                <span class="seleccionado">Academia</span>
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

    <section class="location-container">
        
        <div class="location-info">
            <div class="info-item">
                <i class="fas fa-info-circle"></i>
                <span id="location-status">Presiona el botón para obtener tu ubicación</span>
            </div>
            <div class="info-item" id="address-info" style="display:none;">
                <i class="fas fa-map-pin"></i>
                <span id="address">-</span>
            </div>
            <div class="info-item" id="coords-info" style="display:none;">
                <i class="fas fa-globe"></i>
                <span id="coordinates">-</span>
            </div>
            <div class="info-item" id="accuracy-info" style="display:none;">
                <i class="fas fa-bullseye"></i>
                <span id="accuracy">-</span>
            </div>
        </div>
        
        <div id="map"></div>
        
        <div style="display: flex; justify-content: center;">
            <button id="get-location" class="btn-location">
                <i class="fas fa-location-arrow"></i> Obtener mi ubicación
            </button>
            <button id="reload-location" class="btn-location btn-reload" style="display:none;">
                <i class="fas fa-sync-alt"></i> Actualizar ubicación
            </button>
        </div>
    </section>

    <div class="container2">
        
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Variables globales optimizadas
        const mapConfig = {
            tileLayer: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            zoom: 17,
            highAccuracy: true,
            timeout: 8000,
            maximumAge: 30000
        };
        
        let map, userMarker, lastPosition;
        
        // Icono personalizado para el marcador
        const customIcon = L.divIcon({className: 'custom-marker',iconSize: [24, 24]});
        
        // Inicializar el mapa de forma optimizada
        function initMap(lat, lng) {
            if (!map) {
                map = L.map('map', {
                    preferCanvas: true, // Mejor rendimiento para muchos marcadores
                    zoomControl: false // Desactivamos para añadir el nuestro después
                }).setView([lat, lng], mapConfig.zoom);
                
                // Añadir control de zoom con mejor posición
                L.control.zoom({
                    position: 'topright'
                }).addTo(map);
                
                // Capa base con caché
                L.tileLayer(mapConfig.tileLayer, {
                    attribution: mapConfig.attribution,
                    detectRetina: true, // Mejor visualización en pantallas retina
                    maxZoom: 19
                }).addTo(map);
            } else {
                map.setView([lat, lng], mapConfig.zoom);
            }
            
            // Eliminar marcador anterior de forma eficiente
            if (userMarker) map.removeLayer(userMarker);
            
            // Añadir nuevo marcador con icono personalizado
            userMarker = L.marker([lat, lng], {
                icon: customIcon,
                title: 'Tu ubicación actual',
                alt: 'Marcador de ubicación actual',
                riseOnHover: true
            }).addTo(map)
            .bindPopup("<b>Estás aquí</b><br>Lat: " + lat.toFixed(6) + "<br>Lng: " + lng.toFixed(6))
            .openPopup();
            
            // Ajustar el zoom según la precisión
            if (lastPosition && lastPosition.coords.accuracy) {
                const accuracy = lastPosition.coords.accuracy;
                map.setView([lat, lng], Math.min(mapConfig.zoom, Math.round(16 - Math.log2(accuracy))));
            }
        }
        
        // Función optimizada para obtener dirección
        async function getAddress(lat, lng) {
            try {
                // Usamos un servicio de geocodificación más rápido
                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`);
                
                if (!response.ok) throw new Error('Error en la respuesta del servidor');
                
                const data = await response.json();
                
                if (data.error) throw new Error(data.error);
                
                if (data.address) {
                    const addressParts = [];
                    if (data.address.road) addressParts.push(data.address.road);
                    if (data.address.neighbourhood) addressParts.push(data.address.neighbourhood);
                    if (data.address.suburb) addressParts.push(data.address.suburb);
                    if (data.address.city) addressParts.push(data.address.city);
                    if (data.address.state) addressParts.push(data.address.state);
                    
                    return addressParts.join(', ') || 'Dirección no disponible';
                }
                return 'Dirección no disponible';
            } catch (error) {
                console.warn('Error al obtener dirección:', error.message);
                return 'Ubicación aproximada';
            }
        }
        
        // Mostrar ubicación optimizada
        async function showPosition(position) {
            lastPosition = position;
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const accuracy = position.coords.accuracy;
            
            // Mostrar información básica inmediatamente
            document.getElementById('coordinates').textContent = 
                `Latitud: ${lat.toFixed(6)}, Longitud: ${lng.toFixed(6)}`;
            document.getElementById('accuracy').textContent = 
                `Precisión: ~${Math.round(accuracy)} metros`;
            document.getElementById('coords-info').style.display = 'flex';
            document.getElementById('accuracy-info').style.display = 'flex';
            
            // Inicializar mapa rápidamente
            initMap(lat, lng);
            
            // Obtener dirección en segundo plano sin bloquear la UI
            document.getElementById('location-status').textContent = 'Obteniendo dirección...';
            getAddress(lat, lng).then(address => {
                document.getElementById('address').textContent = address;
                document.getElementById('address-info').style.display = 'flex';
                document.getElementById('location-status').textContent = 'Ubicación actualizada';
            }).catch(() => {
                document.getElementById('location-status').textContent = 'Ubicación obtenida (dirección no disponible)';
            });
            
            // Mostrar botón de recargar
            document.getElementById('reload-location').style.display = 'inline-block';
            
            // Restaurar botón
            const button = document.getElementById('get-location');
            button.innerHTML = '<i class="fas fa-location-arrow"></i> Obtener mi ubicación';
            button.disabled = false;
        }
        
        // Manejo de errores mejorado
        function handleError(error) {
            let errorMessage;
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    errorMessage = "Permiso denegado. Por favor habilita la geolocalización en tu navegador.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMessage = "Ubicación no disponible. Verifica tu conexión a internet.";
                    break;
                case error.TIMEOUT:
                    errorMessage = "Tiempo de espera agotado. Intenta nuevamente.";
                    break;
                default:
                    errorMessage = "Error al obtener la ubicación: " + error.message;
            }
            
            document.getElementById('location-status').textContent = errorMessage;
            document.getElementById('location-status').style.color = "#e74c3c";
            
            // Restaurar botón
            const button = document.getElementById('get-location');
            button.innerHTML = '<i class="fas fa-location-arrow"></i> Intentar nuevamente';
            button.disabled = false;
        }
        
        // Obtener ubicación con caché
        function getLocation() {
            const button = document.getElementById('get-location');
            button.innerHTML = '<i class="fas fa-spinner spin"></i> Localizando...';
            button.disabled = true;
            
            document.getElementById('location-status').textContent = "Detectando tu ubicación...";
            document.getElementById('location-status').style.color = "";
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    showPosition,
                    handleError,
                    {
                        enableHighAccuracy: mapConfig.highAccuracy,
                        timeout: mapConfig.timeout,
                        maximumAge: mapConfig.maximumAge
                    }
                );
            } else {
                document.getElementById('location-status').textContent = 
                    "Tu navegador no soporta geolocalización";
                button.innerHTML = '<i class="fas fa-location-arrow"></i> Obtener mi ubicación';
                button.disabled = false;
            }
        }
        
        // Event listeners optimizados
        document.getElementById('get-location').addEventListener('click', getLocation);
        
        document.getElementById('reload-location').addEventListener('click', function() {
            const button = this;
            button.innerHTML = '<i class="fas fa-spinner spin"></i> Actualizando...';
            button.disabled = true;
            
            document.getElementById('location-status').textContent = 'Actualizando ubicación...';
            document.getElementById('location-status').style.color = "";
            
            // Forzar nueva lectura ignorando la caché
            navigator.geolocation.getCurrentPosition(
                showPosition,
                handleError,
                {
                    enableHighAccuracy: mapConfig.highAccuracy,
                    timeout: mapConfig.timeout,
                    maximumAge: 0 // Ignorar caché
                }
            );
        });
        
        // Intenta obtener la ubicación automáticamente si el usuario ya dio permisos antes
        if (navigator.permissions) {
            navigator.permissions.query({name: 'geolocation'}).then(function(permissionStatus) {
                if (permissionStatus.state === 'granted') {
                    getLocation();
                }
            });
        }

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