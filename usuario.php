<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 2) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Usuario</title>
</head>
<style>
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

        /* Contenido Principal */
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            text-align: center;
        }

        h1 {
            color: #0078D7;
        }

        p {
            font-size: 1.2em;
            margin: 15px 0;
        }

    </style>

    <header>
        Sistema de Gestión - Bienvenido
    </header>

    <!-- Barra de Navegación -->
    <nav>
        <a href="usuario.php">Inicio</a>
        <a href="registros.php">Registro</a>
        <a href="logout.php">Cerrar Sesión</a>
    </nav>
    <div class="container">
    <h1>Bienvenido Usuario, <?= htmlspecialchars($_SESSION['nombre_completo']) ?></h1>
    </div>
</body>
</html>
