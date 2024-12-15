<?php
// Configuración de conexión a la base de datos
$host = 'localhost';
$dbname = 'judi';
$username = 'root';
$password = '';

$mensaje = isset($_GET['mensaje']) ? htmlspecialchars($_GET['mensaje']) : ''; // Obtener el mensaje de la URL
$partidos = []; // Variable para almacenar los candidatos

try {
    // Conexión a la base de datos usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obtener los candidatos (partidos)
    $stmt = $pdo->query("SELECT id, nombre_partido FROM partidos");
    $partidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    Sistema de Gestión - Bienvenido
</header>

<!-- Barra de Navegación -->
<nav>
    <a href="admin.php">Inicio</a>
    <a href="registro.php">Registro</a>
    <a href="reportes.php">Reporte</a>
    <a href="logout.php">Cerrar Sesión</a>
</nav>

<h2 style="text-align: center; margin-top: 20px;">Registro</h2>

<form action="procesar_registro.php" method="POST" enctype="multipart/form-data" style="max-width: 500px; margin: auto;">
    <label for="nombre_completo">Nombre Completo:</label>
    <input type="text" id="nombre_completo" name="nombre_completo" required>

    <label for="colegio">Colegio:</label>
    <input type="text" id="colegio" name="colegio">

    <label for="distrito">Distrito:</label>
    <input type="text" id="distrito" name="distrito">

    <label for="ciudad_municipio">Ciudad o Municipio:</label>
    <input type="text" id="ciudad_municipio" name="ciudad_municipio" required>

    <label for="partido_id">Candidato:</label>
    <select id="partido_id" name="partido_id" required>
        <option value="">-- Seleccione un Candidato --</option>
        <?php foreach ($partidos as $partido): ?>
            <option value="<?= htmlspecialchars($partido['id']) ?>">
                <?= htmlspecialchars($partido['nombre_partido']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="foto">Foto:</label>
    <input type="file" id="foto" name="foto" accept="image/*">

    <button type="submit">Registrar</button>
</form>

<!-- Mostrar mensaje con JavaScript -->
<?php if (!empty($mensaje)): ?>
<script>
    alert("<?= $mensaje ?>"); // Mostrar mensaje como alerta
</script>
<?php endif; ?>

</body>
</html>

