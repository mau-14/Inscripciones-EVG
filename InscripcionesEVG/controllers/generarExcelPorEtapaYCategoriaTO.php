<?php

require realpath(__DIR__ . '/../../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

function generarExcelPorEtapaYCategoria(array $datos, string $nombreArchivo = 'torneo.xlsx')
{

  $spreadsheet = new Spreadsheet();
  $grupos = [];
  foreach ($datos as $fila) {
    $etapa = $fila['nombreEtapa'] ?? '';
    $categoria = $fila['categoria'] ?? '';
    $key = $etapa . '|' . $categoria;
    $grupos[$key][] = $fila;
  }

  $primerHoja = true;
  static $nombresUsados = [];

  foreach ($grupos as $key => $filas) {
    if (empty($filas)) continue;

    [$etapa, $categoria] = explode('|', $key);
    $prueba = $filas[0]['nombrePrueba'] ?? '';

    $nombreHoja = substr(preg_replace('/[\\\\\/\?\*\[\]:]/', '_', $etapa . ' - ' . $categoria . ' - ' . $prueba), 0, 31);
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

    // Título principal
    $sheet->setCellValue('A1', 'INSCRIPCIONES TORNEO OLÍMPICO');
    $sheet->mergeCells('A1:D1');
    $sheet->getStyle('A1')->applyFromArray([
      'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
      'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
      'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0070C0']],
    ]);
    $sheet->getRowDimension(1)->setRowHeight(30);

    // Subtítulos
    $categoriaTexto = strtoupper($categoria) === 'F' ? 'FEMENINA' : 'MASCULINA';

    $sheet->setCellValue('A3', $prueba . '    ' . $categoriaTexto);
    $sheet->mergeCells('A3:D3');
    $sheet->getStyle('A3')->applyFromArray([
      'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '0070C0']],
      'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ]);

    $sheet->setCellValue('A4', $etapa);
    $sheet->mergeCells('A4:D4');
    $sheet->getStyle('A4')->applyFromArray([
      'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '0070C0']],
      'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ]);

    // Cabecera tabla
    $sheet->setCellValue('A6', 'Nombre, Apellidos');
    $sheet->setCellValue('B6', 'Clase');
    $sheet->getStyle('A6:B6')->applyFromArray([
      'font' => ['bold' => true],
      'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCCCCC']],
    ]);

    // Filas de alumnos
    $filaExcel = 7;
    $zebraColors = ['FFFFFF', 'F2F2F2'];
    foreach ($filas as $i => $alumno) {
      $nombreCompleto = $alumno['nombreAlumno'] ?? '';
      $partes = explode(' ', $nombreCompleto);
      if (count($partes) > 1) {
        $nombre = array_pop($partes);
        $apellidos = implode(' ', $partes);
        $nombreFormateado = "$apellidos, $nombre";
      } else {
        $nombreFormateado = $nombreCompleto;
      }

      $colorFondo = $zebraColors[$i % 2];

      $sheet->setCellValue("A$filaExcel", $nombreFormateado);
      $sheet->setCellValue("B$filaExcel", $alumno['nombreClase'] ?? '');
      $sheet->getStyle("A$filaExcel:B$filaExcel")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($colorFondo);

      $filaExcel++;
    }

    // Ajustar ancho
    $sheet->getColumnDimension('A')->setWidth(40);
    $sheet->getColumnDimension('B')->setWidth(20);
  }

  // Crear nombre de archivo a partir del nombre de la primera prueba
  $nombrePrueba = $datos[0]['nombrePrueba'] ?? 'torneo';
  $nombreArchivo = 'excel_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($nombrePrueba)) . '.xlsx';

  $writer = new Xlsx($spreadsheet);
  $writer->save($nombreArchivo);

  return $nombreArchivo;
}
