<?php
require __DIR__ . '/generarExcelActividades.php';

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

  $nombreArchivo = generarExcelActividades($grupoDeAlumnos);
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
$rutaZIP = __DIR__ . '/' . $nombreZIP;

$zip = new ZipArchive();

if ($zip->open($rutaZIP, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
  http_response_code(500);
  echo "No se pudo crear el archivo ZIP.";
  exit;
}

foreach ($archivosExcel as $archivo) {
  $zip->addFile($archivo, basename($archivo));
}
$zip->close();

if (!file_exists($rutaZIP) || filesize($rutaZIP) === 0) {
  http_response_code(500);
  echo "Archivo ZIP no se creó correctamente.";
  exit;
}

// Enviar cabeceras y archivo ZIP
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . $nombreZIP . '"');
header('Content-Length: ' . filesize($rutaZIP));
header('Pragma: public');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');

ob_clean();
flush();
readfile($rutaZIP);

// Limpieza de archivos temporales
foreach ($archivosExcel as $archivo) {
  unlink($archivo);
}
unlink($rutaZIP);
exit;
