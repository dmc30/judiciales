<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuarios</title>
    <style>
        /* Estilo General */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            color: #333;
        }

        /* Encabezado */
        header {
            background-color: #0078D7; /* Azul vibrante */
            color: #fff;
            padding: 15px 0;
            text-align: center;
            font-size: 1.8em;
            font-weight: bold;
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
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

        /* Contenedor Principal */
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            text-align: center;
        }

        h2 {
            color: #0078D7;
            margin-bottom: 20px;
            font-size: 1.8em;
        }

        /* Campos del Formulario */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            text-align: left;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input, select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            transition: all 0.3s ease;
            width: 100%;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #0078D7;
            box-shadow: 0 0 5px rgba(0, 120, 215, 0.5);
        }

        /* Botón */
        button {
            padding: 10px;
            background-color: #0078D7;
            color: #fff;
            font-size: 1.1em;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        button:hover {
            background-color: #005a9e;
        }

        /* Alertas */
        .alert {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
            padding: 10px;
            margin: 20px auto;
            border-radius: 5px;
            max-width: 500px;
            text-align: center;
        }

    </style>
    <script>
        // Función para mostrar alerta
        function mostrarAlerta(mensaje) {
            if (mensaje) {
                alert(mensaje);
            }
        }
    </script>
</head>
<body>
    <!-- Encabezado -->
    <header>
        Sistema de Gestión - Registro de Usuarios
    </header>

    <!-- Barra de Navegación -->
    <nav>
        <a href="admin.php">Inicio</a>
        <a href="registro.php">Registro</a>
        <a href="logout.php">Cerrar Sesión</a>
    </nav>

    <!-- Contenedor del Formulario -->
    <div class="container">
        <h2>Formulario de Registro</h2>

        <!-- Formulario -->
        <form action="" method="POST">
            <label for="nombre_completo">Nombre Completo:</label>
            <input type="text" id="nombre_completo" name="nombre_completo" placeholder="Ingrese su nombre completo" required>

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" placeholder="Ingrese su correo electrónico" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" placeholder="Cree una contraseña segura" required>

            <label for="rol_id">Rol:</label>
            <select id="rol_id" name="rol_id" required>
                <option value="1">Administrador</option>
                <option value="2">Usuario</option>
            </select>

            <button type="submit">Registrar</button>
        </form>
    </div>

    <!-- Mostrar alerta si hay mensaje -->
    <script>
        mostrarAlerta("<?php echo htmlspecialchars($mensaje); ?>");
    </script>
</body>
</html>


