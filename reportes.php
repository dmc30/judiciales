<?php
$host = 'localhost';
$dbname = 'judi'; // Cambia por el nombre de tu base de datos
$username = 'root'; // Cambia por tu usuario de MySQL
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener todos los partidos para los checkboxes
    $stmtPartidos = $pdo->query("SELECT id, nombre_partido FROM partidos");
    $partidos = $stmtPartidos->fetchAll(PDO::FETCH_ASSOC);

    // Obtener todas las personas por defecto o filtradas
    if (isset($_POST['partidos'])) {
        $partido_ids = implode(',', array_map('intval', $_POST['partidos'])); // Filtrar por partidos seleccionados
        $stmtPersonas = $pdo->query("
            SELECT personas.*, partidos.nombre_partido 
            FROM personas
            LEFT JOIN partidos ON personas.partido_id = partidos.id
            WHERE partidos.id IN ($partido_ids)
        ");
    } else {
        // Mostrar todas las personas por defecto
        $stmtPersonas = $pdo->query("
            SELECT personas.*, partidos.nombre_partido 
            FROM personas
            LEFT JOIN partidos ON personas.partido_id = partidos.id
        ");
    }

    $personas = $stmtPersonas->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes por Partido</title>
    <style>
        /* Estilo general */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 40px auto;
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
            color: #2c3e50;
        }

        form {
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            font-size: 1em;
            display: inline-block;
            margin-right: 10px;
        }

        input[type="checkbox"] {
            margin-right: 5px;
        }

        button {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #ecf0f1;
        }

        tr:hover {
            background-color: #f1c40f;
        }

                /* Estilo General */
                body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }

        /* Estilo del Encabezado */
        header {
            background-color: #0078D7; /* Azul moderno */
            color: #fff;
            padding: 15px 20px;
            text-align: center;
            font-size: 1.5em;
        }

        /* Barra de Navegación */
        nav {
            background-color: #005a9e;
            padding: 10px;
            text-align: center;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-size: 1.1em;
        }

        nav a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>

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


<div class="container">
    <h1>Reportes por Partido</h1>
    <form method="POST" action="">
        <p>Selecciona los partidos que deseas incluir en el reporte:</p>
        <?php foreach ($partidos as $partido): ?>
            <label>
                <input type="checkbox" name="partidos[]" value="<?= htmlspecialchars($partido['id']) ?>">
                <?= htmlspecialchars($partido['nombre_partido']) ?>
            </label><br>
        <?php endforeach; ?>
        <br>
        <button type="submit">Filtrar Personas</button>
    </form>

    <?php if (!empty($personas)): ?>
        <form method="POST" action="generar_reporte.php">
    <!-- Enviar los partidos seleccionados al generar el reporte -->
    <input type="hidden" name="partidos" value="<?= isset($_POST['partidos']) ? implode(',', array_map('intval', $_POST['partidos'])) : '' ?>">
    <button type="submit">Generar PDF</button>
</form>

        <h2>Listado Actual</h2>
        <table>
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Nombre Completo</th>
                    <th>Colegio</th>
                    <th>Distrito</th>
                    <th>Ciudad o Municipio</th>
                    <th>Candidato</th>
                    <th>Foto</th>
                </tr>
            </thead>
            <tbody>
                <?php $numero = 1; ?>
                <?php foreach ($personas as $persona): ?>
                    <tr>
                        <td><?= $numero++ ?></td>
                        <td><?= htmlspecialchars($persona['nombre_completo']) ?></td>
                        <td><?= htmlspecialchars($persona['colegio'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($persona['distrito'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($persona['ciudad_municipio'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($persona['nombre_partido'] ?? 'Sin Partido') ?></td>
                        <td>
                            <?php if ($persona['foto']): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($persona['foto']) ?>" alt="Foto" style="width: 50px; height: 50px; border-radius: 50%;">
                            <?php else: ?>
                                Sin Foto
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay personas registradas.</p>
    <?php endif; ?>
</div>
</body>
</html>




