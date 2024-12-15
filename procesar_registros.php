<?php
$host = 'localhost';
$dbname = 'judi';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre_completo = $_POST['nombre_completo'] ?? null;
        $colegio = !empty($_POST['colegio']) ? $_POST['colegio'] : null;
        $distrito = !empty($_POST['distrito']) ? $_POST['distrito'] : null;
        $ciudad_municipio = !empty($_POST['ciudad_municipio']) ? $_POST['ciudad_municipio'] : null;
        $partido_id = !empty($_POST['partido_id']) ? $_POST['partido_id'] : null;
        $foto = $_FILES['foto'] ?? null;

        if (empty($nombre_completo) || empty($ciudad_municipio) || empty($partido_id)) {
            $mensaje = "Los campos 'Nombre Completo', 'Ciudad/Municipio' y 'Partido' son obligatorios.";
        } else {
            $fotoData = null;
            if ($foto && $foto['tmp_name']) {
                $fotoData = file_get_contents($foto['tmp_name']);
            }

            $sql = "INSERT INTO personas (nombre_completo, colegio, distrito, ciudad_municipio, partido_id, foto)
                    VALUES (:nombre_completo, :colegio, :distrito, :ciudad_municipio, :partido_id, :foto)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':nombre_completo', $nombre_completo);
            $stmt->bindParam(':colegio', $colegio);
            $stmt->bindParam(':distrito', $distrito);
            $stmt->bindParam(':ciudad_municipio', $ciudad_municipio);
            $stmt->bindParam(':partido_id', $partido_id, PDO::PARAM_INT);
            $stmt->bindParam(':foto', $fotoData, PDO::PARAM_LOB);

            $stmt->execute();
            $mensaje = "Registro guardado exitosamente.";
        }
    } else {
        $mensaje = "Método no permitido.";
    }

    header("Location: registros.php?mensaje=" . urlencode($mensaje));
    exit;
} catch (PDOException $e) {
    $mensaje = "Error en la conexión a la base de datos: " . $e->getMessage();
    header("Location: formulario_registro.php?mensaje=" . urlencode($mensaje));
    exit;
}
?>
