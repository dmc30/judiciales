<?php

$host = 'localhost';
$dbname = 'judi'; // Cambia por el nombre de tu base de datos
$username = 'root'; // Cambia por tu usuario de MySQL
$password = ''; // Cambia por tu contraseña de MySQL

try {
    // Conexión a la base de datos usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Validar si el formulario fue enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener datos del formulario
        $nombre_completo = $_POST['nombre_completo'] ?? null;
        $colegio = !empty($_POST['colegio']) ? $_POST['colegio'] : null;
        $distrito = !empty($_POST['distrito']) ? $_POST['distrito'] : null;
        $mesa = !empty($_POST['mesa']) ? $_POST['mesa'] : null;
        $foto = $_FILES['foto'] ?? null;

        // Validar que el campo obligatorio no esté vacío
        if (empty($nombre_completo)) {
            die("El campo 'Nombre Completo' es obligatorio.");
        }

        // Procesar el archivo de imagen si se cargó
        $fotoData = null;
        if ($foto && $foto['tmp_name']) {
            // Leer el contenido del archivo
            $fotoData = file_get_contents($foto['tmp_name']);
        }

        // Insertar los datos en la base de datos
        $sql = "INSERT INTO personas (nombre_completo, colegio, distrito, mesa, foto) 
                VALUES (:nombre_completo, :colegio, :distrito, :mesa, :foto)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':nombre_completo', $nombre_completo);
        $stmt->bindParam(':colegio', $colegio);
        $stmt->bindParam(':distrito', $distrito);
        $stmt->bindParam(':mesa', $mesa);
        $stmt->bindParam(':foto', $fotoData, PDO::PARAM_LOB);

        $stmt->execute();

        echo "Registro guardado exitosamente.";
    } else {
        echo "Método no permitido.";
    }
} catch (PDOException $e) {
    echo "Error en la conexión a la base de datos: " . $e->getMessage();
}
?>

