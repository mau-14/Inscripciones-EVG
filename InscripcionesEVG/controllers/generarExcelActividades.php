<?php

require realpath(__DIR__ . '/../../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

function generarExcelActividades(array $datos, string $nombreArchivo = null)
{
  $spreadsheet = new Spreadsheet();

  if (empty($datos)) {
    $nombreActividad = "Actividad sin nombre";
  } else {
    $nombreActividad = $datos[0]['nombreActividad'] ?? "Actividad sin nombre";
  }

  // Generar nombre de archivo si no se pasa explícitamente
  if ($nombreArchivo === null) {
    // Limpiar nombreActividad para usar en archivo
    $nombreArchivo = preg_replace('/[^A-Za-z0-9_-]/', '_', $nombreActividad) . '.xlsx';
  }

  $grupos = [];

  foreach ($datos as $fila) {
    $categoria = $fila['nombreEtapa'] ?? 'Sin categoría';
    $grupos[$categoria][] = $fila;
  }

  $primerHoja = true;
  static $nombresUsados = [];

  foreach ($grupos as $categoria => $filas) {
    if (empty($filas)) continue;

    $nombreHoja = substr(preg_replace('/[\\\\\/\?\*\[\]:]/', '_', $categoria), 0, 31);
    if (in_array($nombreHoja, $nombresUsados)) {
      $sufijo = 1;
      do {
        $nombreHoja = substr($nombreHoja, 0, 28) . '_' . $sufijo++;
      } while (in_array($nombreHoja, $nombresUsados));
    }
    $nombresUsados[] = $nombreHoja;

    $sheet = $primerHoja ? $spreadsheet->getActiveSheet() : $spreadsheet->createSheet();
    $primerHoja = false;
    $sheet->setTitle($nombreHoja);

    // Título principal: nombreActividad (azul fondo, letras blancas)
    $sheet->setCellValue('A1', $nombreActividad);
    $sheet->mergeCells('A1:C1');
    $sheet->getStyle('A1')->applyFromArray([
      'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
      'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0070C0']],
      'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ]);
    $sheet->getRowDimension(1)->setRowHeight(30);

    // Subtítulo: Categoría (letras azules, sin fondo)
    $sheet->setCellValue('A2', 'CATEGORÍA: ' . strtoupper($categoria));
    $sheet->mergeCells('A2:C2');
    $sheet->getStyle('A2')->applyFromArray([
      'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '0000FF']],
      'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ]);
    $sheet->getRowDimension(2)->setRowHeight(20);

    // Cabecera tabla
    $filaInicio = 3;
    $sheet->setCellValue("A$filaInicio", 'Nombre');
    $sheet->setCellValue("B$filaInicio", 'Clase');
    $sheet->setCellValue("C$filaInicio", ''); // columna vacía

    $sheet->getStyle("A$filaInicio:C$filaInicio")->applyFromArray([
      'font' => ['bold' => true],
      'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCCCCC']],
      'borders' => [
        'allBorders' => [
          'borderStyle' => Border::BORDER_THIN,
          'color' => ['rgb' => '000000'],
        ],
      ],
      'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ]);

    // Datos
    $filaExcel = $filaInicio + 1;
    $zebraColors = ['FFFFFF', 'F2F2F2'];
    foreach ($filas as $i => $alumno) {
      $nombre = $alumno['nombreAlumno'] ?? '';
      $clase = $alumno['nombreClase'] ?? '';

      $colorFondo = $zebraColors[$i % 2];

      $sheet->setCellValue("A$filaExcel", $nombre);
      $sheet->setCellValue("B$filaExcel", $clase);
      $sheet->setCellValue("C$filaExcel", ''); // vacía

      $sheet->getStyle("A$filaExcel:C$filaExcel")->applyFromArray([
        'fill' => [
          'fillType' => Fill::FILL_SOLID,
          'startColor' => ['rgb' => $colorFondo],
        ],
        'borders' => [
          'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000'],
          ],
        ],
      ]);

      $filaExcel++;
    }

    // Ajustar anchos columnas
    $sheet->getColumnDimension('A')->setWidth(40);
    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(10);
  }

  $writer = new Xlsx($spreadsheet);
  $writer->save($nombreArchivo);

  return $nombreArchivo;
}
