<?php

class C_alumnosSeleccionados
{
  private $obj;
  private $idClase;

  public function __construct($datos)
  {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/models/m_alumnosSeleccionados.php';
    $this->idClase = $datos;
  }

  public function comprobar()
  {
    $this->obj = new M_alumnosSeleccionados();
    $resultado = $this->obj->comprobar($this->idClase);
    header('Content-Type: application/json');
    echo $resultado;
  }

  public function extraer()
  {
    $this->obj = new M_alumnosSeleccionados();
    $resultado = $this->obj->extraer($this->idClase);
    header('Content-Type: application/json');
    echo $resultado;
  }
}
