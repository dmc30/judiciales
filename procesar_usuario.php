<?php
$host = 'localhost';
$dbname = 'judi';
$username = 'root';
$password = '';
$mensaje = '';

// Conexión a la base de datos
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Procesar el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre_completo = $_POST['nombre_completo'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptar contraseña
        $rol_id = $_POST['rol_id'];

        // Insertar usuario en la base de datos
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre_completo, email, password, rol_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre_completo, $email, $password, $rol_id]);

        // Redirigir con mensaje en URL
        header("Location: registro.php?mensaje=Usuario registrado exitosamente");
        exit();
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Capturar mensaje de la URL
$mensaje = isset($_GET['mensaje']) ? htmlspecialchars($_GET['mensaje']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuarios</title>
    <style>
        /* Estilos */
        body {
            font-family: Arial, sans-serif; background-color: #f0f2f5; margin: 0; padding: 0;
        }
        header {
            background-color: #0078D7; color: #fff; text-align: center; padding: 15px; font-size: 1.8em;
        }
        nav {
            background-color: #005a9e; padding: 10px; text-align: center;
        }
        nav a {
            color: #fff; text-decoration: none; margin: 0 15px; font-weight: bold;
        }
        .container {
            max-width: 600px; margin: 40px auto; background-color: #fff; padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); border-radius: 10px; text-align: center;
        }
        label { font-weight: bold; }
        input, select {
            width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px;
        }
        button {
            background-color: #0078D7; color: white; padding: 10px; border: none; border-radius: 5px;
            font-size: 1.1em; cursor: pointer; font-weight: bold;
        }
        button:hover {
            background-color: #005a9e;
        }
    </style>
    <script>
        // Mostrar alerta con el mensaje
        function mostrarAlerta(mensaje) {
            if (mensaje) {
                alert(mensaje);
            }
        }
    </script>
</head>
<body>
    <!-- Encabezado -->
    <header>Sistema de Gestión - Registro de Usuarios</header>

    <!-- Barra de Navegación -->
    <nav>
        <a href="admin.php">Inicio</a>
        <a href="registro.php">Registro</a>
        <a href="logout.php">Cerrar Sesión</a>
    </nav>

    <!-- Contenedor -->
    <div class="container">
        <h2>Formulario de Registro</h2>

        <!-- Formulario -->
        <form action="registro_usurio.php" method="POST">
            <label for="nombre_completo">Nombre Completo:</label>
            <input type="text" id="nombre_completo" name="nombre_completo" required>

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <label for="rol_id">Rol:</label>
            <select id="rol_id" name="rol_id" required>
                <option value="1">Administrador</option>
                <option value="2">Usuario</option>
            </select>

            <button type="submit">Registrar</button>
        </form>
    </div>

    <!-- Script para mostrar mensaje de JavaScript -->
    <script>
        mostrarAlerta("<?php echo $mensaje; ?>");
    </script>
</body>
</html>



