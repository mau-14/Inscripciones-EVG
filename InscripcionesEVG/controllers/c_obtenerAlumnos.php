<?php

class C_obtenerAlumnos
{

  private $id;
  public function __construct($id)
  {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/models/m_obtenerAlumnos.php';
    $this->id = $id;
  }

  public function obtenerAlumnos()
  {
    $obtenerAlumnos = new M_obtenerAlumnos($this->id);
    $alumnosJson = $obtenerAlumnos->obtenerAlumnos();

    header('Content-Type: application/json');
    echo $alumnosJson;
  }


  public function obtenerInscripcionesAlumnosTO()
  {
    $obtenerAlumnos = new M_obtenerAlumnos($this->id);
    $inscripcionesAlumnosTO = $obtenerAlumnos->obtenerInscripcionesAlumnosTO();

    header('Content-Type: application/json');
    echo $inscripcionesAlumnosTO;
  }
}
