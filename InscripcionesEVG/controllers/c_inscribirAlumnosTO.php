<?php


class C_inscribirAlumnosTO
{

  private $obj;
  private $dato;

  public function __construct($datos)
  {

    require_once $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/models/m_inscribirAlumnosTO.php';
    $this->dato = $datos;
  }


  public function inscribirAlumnos()
  {

    $this->obj = new M_inscribirAlumnosTO();
    $resultado = $this->obj->actualizarInscripciones($this->dato);
    header('Content-Type: application/json');
    echo $resultado;
  }
}
