<?php


class C_insertPruebasTO
{

  private $obj;
  private $dato;

  public function __construct($datos)
  {

    require_once $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/models/m_crudPruebasTO.php';
    $this->dato = $datos;
  }


  public function insertarInscripcion()
  {

    $this->obj = new M_crudPruebasTO();
    $resultado = $this->obj->inscribir($this->dato);
    header('Content-Type: application/json');
    echo $resultado;
  }
}
