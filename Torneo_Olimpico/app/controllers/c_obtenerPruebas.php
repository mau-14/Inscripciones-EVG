<?php


class C_obtenerPruebas
{

  public function __construct()
  {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Torneo_Olimpico/app/models/m_obtenerPruebas.php';
  }

  public function obtenerPruebas()
  {
    $obj = new M_obtenerPruebas();
  }
}


$obj = new C_obtenerPruebas();

$obj->obtenerPruebas();
