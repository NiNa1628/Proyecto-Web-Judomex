<?php
header('Content-Type: application/json');

// Configuración de la base de datos
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'judomex';

try {
    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception('Error de conexión: ' . $conn->connect_error);
    }
    
    $conn->set_charset('utf8mb4');

    // Obtener datos JSON
    $json = file_get_contents('php://input');
    if (empty($json)) {
        throw new Exception('No se recibieron datos');
    }
    
    $data = json_decode($json, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Error en el JSON: ' . json_last_error_msg());
    }

    // Validar campos requeridos
    $requiredFields = [
        'nombre', 'apellidos', 'email', 'telefono', 'fechaNacimiento',
        'genero', 'calle', 'noExt', 'colonia', 'cp', 'pais', 'estado', 'contrasena'
    ];
    
    $missingFields = [];
    foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            $missingFields[] = $field;
        }
    }
    
    if (!empty($missingFields)) {
        throw new Exception('Campos obligatorios: ' . implode(', ', $missingFields));
    }

    // Validaciones
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Email inválido');
    }

    if (!preg_match('/^[0-9]{10}$/', $data['telefono'])) {
        throw new Exception('Teléfono debe tener 10 dígitos');
    }

    if (strlen($data['contrasena']) < 8) {
        throw new Exception('Contraseña muy corta (mínimo 8 caracteres)');
    }

    // Verificar email existente
    $sql = "SELECT id_usuario FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Error al preparar consulta: ' . $conn->error);
    }
    
    $stmt->bind_param('s', $data['email']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        throw new Exception('Email ya registrado');
    }
    $stmt->close();

    // Encriptar contraseña
    $hashedPassword = password_hash($data['contrasena'], PASSWORD_BCRYPT);
    if ($hashedPassword === false) {
        throw new Exception('Error al encriptar contraseña');
    }

    // Insertar usuario
    $sql = "INSERT INTO usuarios (
        nombre, apellidos, email, telefono, fecha_nacimiento, genero,
        calle, no_ext, no_int, colonia, cp, pais, estado, password
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Error al preparar inserción: ' . $conn->error);
    }

    $noInt = !empty($data['noInt']) ? $data['noInt'] : null;

    $stmt->bind_param(
        'ssssssssssssss',
        $data['nombre'],
        $data['apellidos'],
        $data['email'],
        $data['telefono'],
        $data['fechaNacimiento'],
        $data['genero'],
        $data['calle'],
        $data['noExt'],
        $noInt,
        $data['colonia'],
        $data['cp'],
        $data['pais'],
        $data['estado'],
        $hashedPassword
    );

    if (!$stmt->execute()) {
        throw new Exception('Error al registrar: ' . $stmt->error);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Registro exitoso',
        'userId' => $stmt->insert_id
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Judomex</title>
        <link rel="website icon" type="png" href="assets/logo.png">
        <link rel="stylesheet" href="Registro.css" type="text/css"/>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    </head>
<body>
    <!-- Contiene donde se van a capturar todos los datos -->
    <section class="container">
        <!-- Título JUDOMEX -->
        <section class="container_Judomex">
            <div class="texto_Judomex">JUDOMEX</div>
        </section>

        <form id="registroForm">
            <!-- Agregar el texto del nombre -->
            <section class="container_Nombre">
                <div class="texto_Instruccion">Nombre:</div>
                <section class="container_NombreTexto">
                    <input type="text" id="nombre" class="texto_Asignacion" placeholder="Escribe tu nombre" required>
                </section>
            </section>

            <!-- Agregar el texto de los apellidos -->
            <section class="container_Apellido">
                <div class="texto_Instruccion">Apellidos:</div>
                <section class="container_NombreTexto">
                    <input type="text" id="apellidos" class="texto_Asignacion" placeholder="Escribe tu(s) apellido(s)" required>
                </section>
            </section>

            <!-- Agrega el texto del email -->
            <section class="container_Email">
                <div class="texto_Instruccion">Correo electrónico:</div>
                <section class="container_NombreTexto">
                    <input type="text" id="email" class="texto_Asignacion" placeholder="Escribe tu correo electrónico" required>
                </section>
            </section>

            <!-- Agrega el texto del teléfono -->
            <section class="container_Telefono">
                <div class="texto_Instruccion">Teléfono:</div>
                <section class="container_NombreTexto">
                    <input type="text" id="telefono" class="texto_Asignacion" placeholder="Escribe tu número de teléfono" required>
                </section>
            </section>

             <!-- Elige en el calendiario para el cumpleaños -->
             <section class="container_Calendario">
                <div class="texto_Instruccion">Fecha de nacimiento:</div>
                <input type="date" id="fechaNacimiento" required>
            </section>

            <!-- Elige en el calendiario para el cumpleaños -->
            <section class="container_Sexo">
                <div class="texto_Instruccion">Género:</div>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle custom-dropdown" type="button" id="dropdownGenero" data-bs-toggle="dropdown" aria-expanded="false">
                        Selecciona tu género
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownGenero">
                        <li><a class="dropdown-item" href="#" onclick="selectGenero('Hombre')">Hombre</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectGenero('Mujer')">Mujer</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectGenero('Otro')">Otro</a></li>
                    </ul>
                    <input type="hidden" id="genero" name="genero" required>
                </div>
            </section>

            <!-- Agrega el texto de la calle -->
            <section class="container_Calle">
                <div class="texto_Instruccion">Calle:</div>
                    <section class="container_TextoCalle">
                        <input type="text" id="calle" class="texto_Asignacion" placeholder="Escribe tu calle" required>
                    </section>
            </section>

            <!-- Agrega el texto del número exterior -->
            <section class="container_Numero">
                <div class="texto_Instruccion">No. Ext.:</div>
                    <section class="container_TextoNum">
                        <input type="text" id="noExt" class="texto_Asignacion" placeholder="No. Ext." required>
                    </section>
            </section>

            <!-- Agrega el texto del número interior -->
            <section class="container_Interior">
                <div class="texto_Instruccion">No. Int.:</div>
                    <section class="container_TextoNum">
                        <input type="text" id="noInt" class="texto_Asignacion" placeholder="No. Int.">
                    </section>
            </section>

            <!-- Agrega el texto de la colonia -->
            <section class="container_Colonia">
                <div class="texto_Instruccion">Colonia:</div>
                    <section class="container_TextoColonia">
                        <input type="text" id="colonia" class="texto_Asignacion" placeholder="Escribe tu colonia" required>
                    </section>
            </section>

            <!-- Agrega el texto del código postal -->
            <section class="container_CP">
                <div class="texto_Instruccion">Código Postal:</div>
                    <section class="container_TextoCP">
                        <input type="text" id="cp" class="texto_Asignacion" placeholder="Código Postal" required>
                    </section>
            </section>

            <!-- Agrega el texto del País -->
            <section class="container_Pais">
                <div class="texto_Instruccion">País:</div>
                    <section class="container_TextoCalle">
                        <input type="text" id="pais" class="texto_Asignacion" placeholder="Agrega tu país" required>
                    </section>
            </section>

            <!-- Agrega el texto del estado -->
            <section class="container_Estado">
                <div class="texto_Instruccion">Estado:</div>
                    <section class="container_TextoEstado">
                        <input type="text" id="estado" class="texto_Asignacion" placeholder="Agrega tu estado" required>
                    </section>
            </section>

            <!-- Agrega el texto de la contraseña -->
            <section class="container_Contraseña">
                <div class="texto_Instruccion">Contraseña:</div>
                    <section class="container_TextoContraseña">
                        <input type="password" id="contrasena" class="texto_Asignacion" placeholder="Agrega tu contraseña" required>
                    </section>
            </section>

            <!-- Agrega el texto de la confirmar contraseña -->
            <section class="container_ConfContraseña">
                <div class="texto_Instruccion">Confirmar contraseña:</div>
                    <section class="container_TextoContraseña">
                        <input type="password" id="confirmarContrasena" class="texto_Asignacion" placeholder="Confirma tu contraseña" required>
                    </section>
            </section>

            <!-- Aceptar términos y condiciones -->
            <section class="container_TeryCond">
                <div class="button_aceptar" onclick="toggleIcon(this)">
                    <i class="fa-regular fa-square"></i>
                </div>
                <input type="checkbox" id="terminosAceptados" style="display: none;" required>
                <div class="texto_Terminos"><u>Términos y Condiciones</u></div>
            </section>

            <!-- Botón de crear cuenta - Modificado -->
            <button type="submit" id="submitBtn" class="button" style="border: transparent; background: none; width: 100%;">
                <section class="container_Button">
                    <section class="button">
                        <div class="texto_Button">Registrarse</div>
                    </section>
                </section>
            </button>
        </form>
    </section>

    <script>
    // Función para cambiar el icono de términos y condiciones
    function toggleIcon(element) {
        let icon = element.querySelector("i");
        const checkbox = document.getElementById('terminosAceptados');
        
        if (icon.classList.contains("fa-regular")) {
            icon.classList.remove("fa-regular", "fa-square");
            icon.classList.add("fa-solid", "fa-square-check");
            checkbox.checked = true;
        } else {
            icon.classList.remove("fa-solid", "fa-square-check");
            icon.classList.add("fa-regular", "fa-square");
            checkbox.checked = false;
        }
    }
    
    // Función para seleccionar género
    function selectGenero(genero) {
        document.getElementById('genero').value = genero;
        document.querySelector('#dropdownGenero').textContent = genero;
    }

    // Manejar el envío del formulario
    document.getElementById('registroForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Obtener el botón correctamente
        const submitBtn = document.getElementById('submitBtn');
        const originalContent = submitBtn.innerHTML;
        
        // Validar contraseñas
        const contrasena = document.getElementById('contrasena').value;
        const confirmacion = document.getElementById('confirmarContrasena').value;
        
        if (contrasena !== confirmacion) {
            alert('Las contraseñas no coinciden');
            return;
        }
        
        // Validar términos
        if (!document.getElementById('terminosAceptados').checked) {
            alert('Debes aceptar los términos y condiciones');
            return;
        }

        // Mostrar estado de carga
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Registrando...';

        try {
            const formData = {
                nombre: document.getElementById('nombre').value,
                apellidos: document.getElementById('apellidos').value,
                email: document.getElementById('email').value,
                telefono: document.getElementById('telefono').value,
                fechaNacimiento: document.getElementById('fechaNacimiento').value,
                genero: document.getElementById('genero').value,
                calle: document.getElementById('calle').value,
                noExt: document.getElementById('noExt').value,
                noInt: document.getElementById('noInt').value || null,
                colonia: document.getElementById('colonia').value,
                cp: document.getElementById('cp').value,
                pais: document.getElementById('pais').value,
                estado: document.getElementById('estado').value,
                contrasena: contrasena
            };

            const response = await fetch('registro.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.message || 'Error en el registro');
            }

            // Mostrar mensaje de éxito
            alert('¡Registro exitoso! Serás redirigido al inicio de sesión.');
            
            // Redirección después de 1.5 segundos
            setTimeout(() => {
                window.location.href = 'InicioSesion.php'; // Asegúrate que este archivo existe
            }, 1500);
            
        } catch (error) {
            console.error('Error en el registro:', error);
            alert('Error: ' + error.message);
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalContent;
        }
    });

    // Inicializar dropdowns de Bootstrap
    document.addEventListener('DOMContentLoaded', function() {
        new bootstrap.Dropdown(document.getElementById('dropdownGenero'));
    });
</script>

    <!-- Bootstrap 5 JS Bundle con Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body> 
</html>