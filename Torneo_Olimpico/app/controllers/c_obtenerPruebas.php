<?php

class C_obtenerPruebas
{
  public function __construct()
  {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Torneo_Olimpico/app/models/m_obtenerPruebas.php';
  }

  public function obtenerPruebas()
  {
    $obtenerPruebas = new M_obtenerPruebas();
    $pruebasJson = $obtenerPruebas->obtenerPruebas();

    header('Content-Type: application/json');
    echo $pruebasJson;
  }
}

$obj = new C_obtenerPruebas();
$obj->obtenerPruebas();
