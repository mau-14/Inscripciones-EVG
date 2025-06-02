<?php
require __DIR__ . '/vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function generarExcelPorEtapaYCategoria(array $datos, string $nombreArchivo = 'torneo.xlsx')
{
  $spreadsheet = new Spreadsheet();

  // Agrupar datos por nombreEtapa y categoria
  $grupos = [];
  foreach ($datos as $fila) {
    $etapa = $fila['nombreEtapa'];
    $categoria = $fila['categoria'];
    $key = $etapa . '|' . $categoria;
    if (!isset($grupos[$key])) {
      $grupos[$key] = [];
    }
    $grupos[$key][] = $fila;
  }

  $primerHoja = true;
  foreach ($grupos as $key => $filas) {
    [$etapa, $categoria] = explode('|', $key);

    // Crear o seleccionar hoja
    if ($primerHoja) {
      $sheet = $spreadsheet->getActiveSheet();
      $primerHoja = false;
    } else {
      $sheet = $spreadsheet->createSheet();
    }
    // Nombrar hoja (limitar a 31 caracteres y quitar caracteres inválidos)
    $nombreHoja = substr(preg_replace('/[\\\/\?\*\[\]:]/', '_', $etapa . ' - ' . $categoria), 0, 31);
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

    // Cabecera tabla (2 filas más abajo de la tabla, la tabla empieza en fila 6)
    $sheet->setCellValue('A6', 'Apellidos, Nombre');
    $sheet->setCellValue('B6', 'Clase');
    $sheet->getStyle('A6:B6')->getFont()->setBold(true);

    // Datos desde fila 7
    $filaExcel = 7;
    foreach ($filas as $index => $alumno) {
      // Formatear nombre "Apellidos, Nombre"
      $nombreCompleto = $alumno['nombreAlumno'];
      // Aquí se puede mejorar la separación apellido/nombre si se quiere,
      // pero usaré lo que hay, invirtiendo el orden separado por espacio
      $partes = explode(' ', $nombreCompleto);
      if (count($partes) > 1) {
        $nombre = array_pop($partes);
        $apellidos = implode(' ', $partes);
        $nombreFormateado = $apellidos . ', ' . $nombre;
      } else {
        $nombreFormateado = $nombreCompleto;
      }

      // Añadir índice (columna C), Nombre y Clase (D)
      $sheet->setCellValue('A' . $filaExcel, $nombreFormateado);
      $sheet->setCellValue('B' . $filaExcel, $alumno['nombreClase']);
      $filaExcel++;
    }

    // Añadir columna a la derecha vacía (C)
    $sheet->getColumnDimension('C')->setWidth(15);

    // Dejar 2 filas vacías debajo (ya quedan al final)

    // Ajustar ancho de columnas para que quede bien
    $sheet->getColumnDimension('A')->setWidth(40);
    $sheet->getColumnDimension('B')->setWidth(15);
  }

  // Guardar archivo
  $writer = new Xlsx($spreadsheet);
  $writer->save($nombreArchivo);

  return $nombreArchivo;
}
