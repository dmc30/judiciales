<?php
require('fpdf/fpdf.php');

// Configuración de conexión a la base de datos
$host = 'localhost';
$dbname = 'judi';
$username = 'root';
$password = '';

try {
    // Desactivar los errores y advertencias antes de generar el PDF
    error_reporting(0);
    ini_set('display_errors', 0);

    // Conectar a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener los partidos seleccionados desde el formulario
    $partidos = $_POST['partidos'] ?? '';

    if (empty($partidos)) {
        die("No se seleccionó ningún partido.");
    }

    // Construir la consulta SQL para obtener los datos
    $query = "
        SELECT 
            personas.nombre_completo, 
            personas.colegio, 
            personas.distrito, 
            personas.ciudad_municipio, 
            partidos.nombre_partido, 
            personas.foto
        FROM personas
        LEFT JOIN partidos ON personas.partido_id = partidos.id
        WHERE partidos.id IN ($partidos)
    ";

    $stmt = $pdo->query($query);
    $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($datos)) {
        die("No hay datos disponibles para el partido seleccionado.");
    }

    // Crear el PDF
    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Reporte de Actas de Personas', 0, 1, 'C');
    $pdf->Ln(5);

    // Establecer el estilo de la tabla
    $pdf->SetFont('Arial', 'B', 12); // Negrita para los encabezados

    // Configurar variables para manejar el conteo de filas y el número de acta
    $pdf->SetFont('Arial', '', 12); // Fuente normal para los valores
    $counter = 0;
    $actaNumber = 1; // Número de acta

    foreach ($datos as $index => $fila) {
        // Numerar la acta
        $pdf->SetFont('Arial', 'B', 12); 
        $pdf->Cell(0, 10, "Acta No. " . $actaNumber, 0, 1, 'C');
        $actaNumber++;

        // Crear la tabla de dos columnas
        // Columna 1 (Encabezados en negrita)
        $pdf->SetFont('Arial', 'B', 12); 
        $pdf->Cell(90, 10, 'Nombre Completo', 1, 0, 'L');
        $pdf->SetFont('Arial', '', 12); // Fuente normal para los valores
        $pdf->Cell(90, 10, utf8_encode($fila['nombre_completo']), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 12); // Negrita para el encabezado
        $pdf->Cell(90, 10, 'Colegio', 1, 0, 'L');
        $pdf->SetFont('Arial', '', 12); // Fuente normal para los valores
        $pdf->Cell(90, 10, utf8_encode($fila['colegio'] ?? 'N/A'), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 12); // Negrita para el encabezado
        $pdf->Cell(90, 10, 'Distrito', 1, 0, 'L');
        $pdf->SetFont('Arial', '', 12); // Fuente normal para los valores
        $pdf->Cell(90, 10, utf8_encode($fila['distrito'] ?? 'N/A'), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 12); // Negrita para el encabezado
        $pdf->Cell(90, 10, 'Ciudad/Municipio', 1, 0, 'L');
        $pdf->SetFont('Arial', '', 12); // Fuente normal para los valores
        $pdf->Cell(90, 10, utf8_encode($fila['ciudad_municipio'] ?? 'N/A'), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 12); // Negrita para el encabezado
        $pdf->Cell(90, 10, 'Partido', 1, 0, 'L');
        $pdf->SetFont('Arial', '', 12); // Fuente normal para los valores
        $pdf->Cell(90, 10, utf8_encode($fila['nombre_partido']), 1, 1, 'L');

        // Volver a la fuente normal para la foto
        $pdf->SetFont('Arial', '', 12);

        // Añadir una fila adicional para la foto
        $pdf->Ln(5); // Espacio para separar los datos de la foto

        // Foto debajo de los datos
        if (!empty($fila['foto'])) {
            $imagenDecodificada = $fila['foto'];
            $infoImagen = getimagesizefromstring($imagenDecodificada);
            $tipoMime = $infoImagen['mime'] ?? '';

            // Crear imagen temporal
            if ($tipoMime === 'image/jpeg') {
                $tempImg = 'temp_' . uniqid() . '.jpg';
            } elseif ($tipoMime === 'image/png') {
                $tempImg = 'temp_' . uniqid() . '.png';
            } else {
                $pdf->Cell(60, 60, 'Formato Inválido', 1, 0, 'C');
                continue;
            }

            file_put_contents($tempImg, $imagenDecodificada);

            // Insertar imagen centrada sin cuadro (directamente)
            $xImg = ($pdf->GetPageWidth() - 50) / 2; // Centrar la imagen horizontalmente
            $yImg = $pdf->GetY(); // Obtener la posición vertical actual
            $pdf->Image($tempImg, $xImg, $yImg, 50, 50); // Tamaño ajustado a 50x50 mm
            unlink($tempImg);

            // Línea divisora debajo de la foto
            
            $pdf->Line(10, $yImg + 55, $pdf->GetPageWidth() - 10, $yImg + 55); // Línea justo debajo de la foto // Línea justo debajo de la foto
        } else {
            $pdf->Cell(60, 60, 'Sin Foto', 1, 0, 'C');
            // Línea divisora en caso de que no haya foto
            $pdf->Line($pdf->GetX() - 60, $pdf->GetY() + 60, $pdf->GetX(), $pdf->GetY() + 60);
        }

        // Incrementar el contador
        $counter++;

        // Salto de línea después de cada persona (para separar los registros)
        $pdf->Ln(70); // Aumentamos el salto de línea para separar mejor las entradas
    }

    // Salida del PDF
    $pdf->Output();
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
