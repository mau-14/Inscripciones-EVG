<?php
require __DIR__ . '/generarExcelPorEtapaYCategoriaTO.php';

$contenidoJSON = file_get_contents('php://input');
$datos = json_decode($contenidoJSON, true);

if (!is_array($datos)) {
  http_response_code(400);
  echo "Formato de datos inválido.";
  exit;
}

$archivosExcel = [];

foreach ($datos as $grupoDeAlumnos) {
  if (!is_array($grupoDeAlumnos) || count($grupoDeAlumnos) === 0) {
    continue;
  }

  $nombreArchivo = generarExcelPorEtapaYCategoria($grupoDeAlumnos); // ← Espera array de alumnos
  $rutaCompleta = __DIR__ . '/' . $nombreArchivo;

  if (file_exists($rutaCompleta)) {
    $archivosExcel[] = $rutaCompleta;
  }
}

if (empty($archivosExcel)) {
  http_response_code(500);
  echo "No se generaron archivos Excel.";
  exit;
}

$nombreZIP = 'excels_' . time() . '.zip';
$zip = new ZipArchive();
$rutaZIP = __DIR__ . '/' . $nombreZIP;

if ($zip->open($rutaZIP, ZipArchive::CREATE) !== true) {
  http_response_code(500);
  echo "No se pudo crear el archivo ZIP.";
  exit;
}

foreach ($archivosExcel as $archivo) {
  $zip->addFile($archivo, basename($archivo));
}
$zip->close();

header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . $nombreZIP . '"');
header('Content-Length: ' . filesize($rutaZIP));
readfile($rutaZIP);

// Limpieza
foreach ($archivosExcel as $archivo) {
  unlink($archivo);
}
unlink($rutaZIP);
