<?php

class C_obtenerAlumnos
{
  public function __construct()
  {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/app/models/m_obtenerAlumnos.php';
  }

  public function obtenerAlumnos()
  {
    $obtenerAlumnos = new M_obtenerAlumnos();
    $alumnosJson = $obtenerAlumnos->obtenerAlumnos();

    header('Content-Type: application/json');
    echo $alumnosJson;
  }
}

$obj = new C_obtenerAlumnos();
$obj->obtenerAlumnos();
