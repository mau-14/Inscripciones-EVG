<?php
require __DIR__ . '/generarExcelActividades.php';


$contenidoJSON = file_get_contents('php://input');
$datos = json_decode($contenidoJSON, true);

if (!is_array($datos)) {
  http_response_code(400);
  echo "Datos inválidos";
  exit;
}


$nombreArchivo = generarExcelActividades($datos);
error_log($nombreArchivo);
$rutaArchivo = __DIR__ . '/' . $nombreArchivo;
error_log($rutaArchivo);
if (!file_exists($rutaArchivo)) {
  error_log("Archivo no encontrado: $rutaArchivo");
  http_response_code(500);
  echo "Error: archivo no encontrado";
  exit;
}
// Enviar el archivo como descarga
header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($rutaArchivo));
readfile($rutaArchivo);

unlink($rutaArchivo);

exit;
