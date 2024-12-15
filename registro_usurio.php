<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = 'judi';
$username = 'root';
$password = '';

try {
    // Conexión a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si el formulario fue enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Capturar datos del formulario
        $nombre_completo = $_POST['nombre_completo'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptar la contraseña
        $rol_id = $_POST['rol_id'];

        // Validar si el correo ya existe
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $existe = $stmt->fetchColumn();

        if ($existe) {
            // Si el correo ya existe, redirigir con un mensaje
            header("Location: procesar_usuario.php?mensaje=El correo ya está registrado");
            exit();
        }

        // Insertar datos en la base de datos
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre_completo, email, password, rol_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre_completo, $email, $password, $rol_id]);

        // Redirigir a registro.php con un mensaje de éxito
        header("Location: procesar_usuario.php?mensaje=Usuario registrado exitosamente");
        exit();
    } else {
        // Redirigir si se accede directamente sin enviar datos
        header("Location: procesar_usuario.php?mensaje=Acceso no autorizado");
        exit();
    }
} catch (PDOException $e) {
    // En caso de error, redirigir con un mensaje de error
    header("Location: procesar_usuario.php?mensaje=Error: " . $e->getMessage());
    exit();
}
?>
