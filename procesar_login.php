<?php
session_start();

$host = 'localhost';
$dbname = 'judi';
$username = 'root';
$password = '';

try {
    // Conexión a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recibir datos del formulario
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Buscar el usuario con su rol
        $stmt = $pdo->prepare("
            SELECT usuarios.*, roles.nombre_rol 
            FROM usuarios 
            INNER JOIN roles ON usuarios.rol_id = roles.id 
            WHERE usuarios.email = ?
        ");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar contraseña
        if ($usuario && password_verify($password, $usuario['password'])) {
            // Guardar datos del usuario en la sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre_completo'] = $usuario['nombre_completo'];
            $_SESSION['rol_id'] = $usuario['rol_id'];
            $_SESSION['nombre_rol'] = $usuario['nombre_rol'];

            // Redirigir según el rol
            switch ($usuario['rol_id']) {
                case 1: // Administrador
                    header("Location: admin.php");
                    break;
                case 2: // Usuario
                    header("Location: usuario.php");
                    break;
                default: 
                    header("Location: index.php?error=Rol no definido");
                    break;
            }
            exit();
        } else {
            // Redirigir con mensaje de error
            header("Location: login.php?error=Correo o contraseña incorrectos");
            exit();
        }
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

