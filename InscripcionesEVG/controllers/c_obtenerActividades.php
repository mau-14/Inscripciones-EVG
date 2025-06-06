<?php

class C_obtenerActividades
{
  private $id;

  public function __construct($id = null)
  {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/models/m_obtenerActividades.php';
    $this->id = $id;
  }

  public function obtenerActividades()
  {
    $obtenerActividades = new M_obtenerActividades();
    $actividadesJson = $obtenerActividades->obtenerActividades();

    header('Content-Type: application/json');
    echo $actividadesJson;
  }

  public function obtenerInscripcionesAlumnosActividad()
  {
    $obtenerAlumnos = new M_obtenerActividades();
    // Aquí podrías validar que $this->id no sea null antes de llamar al método
    if ($this->id === null) {
      // manejar error o devolver mensaje adecuado
      header('Content-Type: application/json');
      echo json_encode(['error' => 'No se proporcionó ID de actividad']);
      return;
    }

    $inscripcionesAlumnosActividad = $obtenerAlumnos->obtenerInscripcionesAlumnosActividad($this->id);

    header('Content-Type: application/json');
    echo $inscripcionesAlumnosActividad;
  }
}
