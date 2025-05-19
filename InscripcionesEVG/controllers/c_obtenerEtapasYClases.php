<?php

class C_obtenerEtapasYClases
{
  public function __construct()
  {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/models/m_obtenerEtapasYClases.php';
  }

  public function obtenerEtapasYClases()
  {
    $info = new M_obtenerEtapasYClases();
    $etapasClases = $info->obtenerEtapasYClases();

    header('Content-Type: application/json');
    echo $etapasClases;
  }
}
