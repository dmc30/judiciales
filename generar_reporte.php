<?php
require('fpdf/fpdf.php');

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'judi';
$username = 'root';
$password = '';

try {
    // Conexión a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener las columnas seleccionadas
    $columnas = $_POST['columnas'] ?? [];
    if (empty($columnas)) {
        die("No se seleccionaron columnas.");
    }

    // Generar consulta dinámica
    $columnas_str = implode(", ", $columnas);
    $stmt = $pdo->query("SELECT $columnas_str FROM personas");
    $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Crear PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Título del reporte
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Reporte de Personas', 0, 1, 'C');
    $pdf->Ln(10);

    // Configuración de la fuente para la tabla
    $pdf->SetFont('Arial', '', 10);

    // Encabezados de la tabla
    $anchos = [10, 60, 60, 60]; // Anchos proporcionales de las columnas
    $pdf->SetFillColor(50, 50, 50);
    $pdf->SetTextColor(255, 255, 255);

    // Encabezados
    $pdf->Cell($anchos[0], 10, 'No', 1, 0, 'C', true);
    foreach ($columnas as $i => $columna) {
        $pdf->Cell($anchos[$i + 1], 10, ucfirst(str_replace("_", " ", $columna)), 1, 0, 'C', true);
    }
    $pdf->Ln();

    // Restaurar color de texto
    $pdf->SetTextColor(0, 0, 0);

    // Filas de datos
    $numero = 1;
    foreach ($datos as $fila) {
        $pdf->Cell($anchos[0], 10, $numero++, 1, 0, 'C'); // Columna de numeración
        foreach ($columnas as $i => $columna) {
            $valor = $fila[$columna] ?? 'N/A';
            $pdf->Cell($anchos[$i + 1], 10, mb_convert_encoding($valor, 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
        }
        $pdf->Ln();
    }

    // Salida del PDF
    $pdf->Output('D', 'reporte.pdf');
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}



?>
