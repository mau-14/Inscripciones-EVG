<?php
require __DIR__ . '/generarExcelPorEtapaYCategoriaTO.php';


$contenidoJSON = file_get_contents('php://input');
$datos = json_decode($contenidoJSON, true);

if (!is_array($datos)) {
  http_response_code(400);
  echo "Datos inválidos";
  exit;
}

// Generar Excel
$nombreArchivo = 'torneo.xlsx';
$rutaArchivo = __DIR__ . '/' . $nombreArchivo;

generarExcelPorEtapaYCategoria($datos, $rutaArchivo);
if (!file_exists($rutaArchivo)) {
  error_log("Archivo no encontrado: $rutaArchivo");
  http_response_code(500);
  echo "Error: archivo no encontrado";
  exit;
}
// Enviar el archivo como descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
readfile($rutaArchivo);

exit;
