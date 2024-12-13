<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = 'judi'; // Cambia por el nombre de tu base de datos
$username = 'root'; // Cambia por tu usuario de MySQL
$password = '';

// Conectar a la base de datos
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener todas las filas de la tabla personas
    $stmt = $pdo->query("SELECT * FROM personas");
    $personas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
    <style>
        /* Estilo general de la página */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Contenedor principal */
        .container {
            width: 80%;
            margin: 40px auto;
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Encabezado */
        h1 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        /* Subtítulo */
        p {
            text-align: center;
            font-size: 1.2em;
            color: #7f8c8d;
        }

        /* Estilo del formulario */
        form {
            margin-bottom: 40px;
            text-align: center;
        }

        /* Estilo de los checkboxes */
        label {
            font-size: 1em;
            margin-right: 20px;
            display: inline-block;
            color: #34495e;
        }

        input[type="checkbox"] {
            margin-right: 8px;
            cursor: pointer;
        }

        button {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

        /* Estilo de la tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th {
            background-color: #3498db;
            color: white;
            padding: 12px;
            font-size: 1.1em;
        }

        td {
            padding: 10px;
            text-align: left;
            font-size: 1em;
        }

        /* Estilo de las celdas */
        td img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
        }

        /* Estilo de las filas */
        tr:nth-child(even) {
            background-color: #ecf0f1;
        }

        tr:hover {
            background-color: #f1c40f;
            cursor: pointer;
        }

        /* Estilo de las celdas con datos vacíos */
        td:empty {
            color: #95a5a6;
        }

        /* Paginación y responsive */
        @media (max-width: 768px) {
            .container {
                width: 95%;
            }

            table {
                font-size: 0.9em;
            }
        }
    </style>v
</head>
<body>
    <h1>Reportes</h1>
    <p>Selecciona las columnas que deseas incluir en el reporte:</p>
    <form action="generar_reporte.php" method="POST">
        <label><input type="checkbox" name="columnas[]" value="nombre_completo" checked> Nombre Completo</label><br>
        <label><input type="checkbox" name="columnas[]" value="colegio"> Colegio</label><br>
        <label><input type="checkbox" name="columnas[]" value="distrito"> Distrito</label><br>
        <label><input type="checkbox" name="columnas[]" value="mesa"> Mesa</label><br>
        <label><input type="checkbox" name="columnas[]" value="foto"> Foto</label><br>
        <button type="submit">Generar PDF</button>
    </form>

    <h2>Listado Actual</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Colegio</th>
                <th>Distrito</th>
                <th>Mesa</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($personas as $persona): ?>
                <tr>
                    <td><?= htmlspecialchars($persona['id']) ?></td>
                    <td><?= htmlspecialchars($persona['nombre_completo']) ?></td>
                    <td><?= htmlspecialchars($persona['colegio'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($persona['distrito'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($persona['mesa'] ?? 'N/A') ?></td>
                    <td>
                        <?php if ($persona['foto']): ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($persona['foto']) ?>" alt="Foto" style="width:50px;height:50px;">
                        <?php else: ?>
                            Sin Foto
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>