<?php

class c_borrarInscripciones
{
  private $obj;

  public function __construct()
  {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/models/m_crudPruebasTO.php';
  }

  public function borrarTodas()
  {
    $this->obj = new M_borrarInscripciones();
    $resultado = $this->obj->borrarTodas();

    header('Content-Type: application/json');
    echo $resultado;
  }
}
