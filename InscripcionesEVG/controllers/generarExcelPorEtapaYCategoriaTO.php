<?php

require realpath(__DIR__ . '/../../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function generarExcelPorEtapaYCategoria(array $datos, string $nombreArchivo = 'torneo.xlsx')
{

  error_log('DATOS: ' . print_r($datos, true));
  $spreadsheet = new Spreadsheet();

  // Agrupar datos por nombreEtapa y categoria
  $grupos = [];
  foreach ($datos as $fila) {
    $etapa = $fila['nombreEtapa'] ?? '';
    $categoria = $fila['categoria'] ?? '';
    $key = $etapa . '|' . $categoria;
    if (!isset($grupos[$key])) {
      $grupos[$key] = [];
    }
    $grupos[$key][] = $fila;
  }

  $primerHoja = true;
  static $nombresUsados = [];

  foreach ($grupos as $key => $filas) {
    if (empty($filas)) {
      continue; // Saltar grupos sin datos
    }

    [$etapa, $categoria] = explode('|', $key);

    // Crear o seleccionar hoja
    if ($primerHoja) {
      $sheet = $spreadsheet->getActiveSheet();
      $primerHoja = false;
    } else {
      $sheet = $spreadsheet->createSheet();
    }

    // Nombrar hoja (limitar a 31 caracteres y quitar caracteres inválidos)
    $nombreHoja = substr(preg_replace('/[\\\\\/\?\*\[\]:]/', '_', $etapa . ' - ' . $categoria), 0, 31);

    // Evitar nombres duplicados
    $original = $nombreHoja;
    $sufijo = 1;
    while (in_array($nombreHoja, $nombresUsados)) {
      $nombreHoja = substr($original, 0, 28) . '_' . $sufijo++;
    }
    $nombresUsados[] = $nombreHoja;

    $sheet->setTitle($nombreHoja);

    // Líneas fijas con formato
    $sheet->setCellValue('A1', 'INSCRIPCIONES TORNEO OLÍMPICO');
    $sheet->mergeCells('A1:D1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getRowDimension(1)->setRowHeight(30);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $prueba = $filas[0]['nombrePrueba'] ?? '';
    $categoriaTexto = strtoupper($categoria) === 'F' ? 'FEMENINA' : 'MASCULINA';

    $sheet->setCellValue('A3', $prueba . '    ' . $categoriaTexto);
    $sheet->mergeCells('A3:D3');
    $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(12);
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $sheet->setCellValue('A4', $etapa);
    $sheet->mergeCells('A4:D4');
    $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(12);
    $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Cabecera tabla (fila 6)
    $sheet->setCellValue('A6', 'Apellidos, Nombre');
    $sheet->setCellValue('B6', 'Clase');
    $sheet->getStyle('A6:B6')->getFont()->setBold(true);

    // Datos desde fila 7
    $filaExcel = 7;
    foreach ($filas as $alumno) {
      $nombreCompleto = $alumno['nombreAlumno'] ?? '';
      $partes = explode(' ', $nombreCompleto);
      if (count($partes) > 1) {
        $nombre = array_pop($partes);
        $apellidos = implode(' ', $partes);
        $nombreFormateado = $apellidos . ', ' . $nombre;
      } else {
        $nombreFormateado = $nombreCompleto;
      }

      $sheet->setCellValue('A' . $filaExcel, $nombreFormateado);
      $sheet->setCellValue('B' . $filaExcel, $alumno['nombreClase'] ?? '');
      $filaExcel++;
    }

    // Ajustar ancho columnas
    $sheet->getColumnDimension('A')->setWidth(40);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(15);
  }
  error_log("Datos escritos en hoja $nombreHoja, filas: " . ($filaExcel - 7));
  // Guardar archivo
  $writer = new Xlsx($spreadsheet);
  $writer->save($nombreArchivo);

  return $nombreArchivo;
}
