<?php

class C_obtenerAlumnos
{

  private $idClase;
  public function __construct($idClase)
  {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/models/m_obtenerAlumnos.php';
    $this->idClase = $idClase;
  }

  public function obtenerAlumnos()
  {
    $obtenerAlumnos = new M_obtenerAlumnos($this->idClase);
    $alumnosJson = $obtenerAlumnos->obtenerAlumnos();

    header('Content-Type: application/json');
    echo $alumnosJson;
  }
}
