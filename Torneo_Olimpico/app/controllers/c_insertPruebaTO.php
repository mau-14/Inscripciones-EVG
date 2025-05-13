<?php


class C_insertPruebaTO
{

  private $obj;
  private $dato;

  public function __construct($datos)
  {

    require_once $_SERVER['DOCUMENT_ROOT'] . '/Torneo_Olimpico/app/models/m_crudPruebasTO.php';
    $this->dato = $datos;
  }


  public function insertarInscripcion()
  {

    $this->obj = new M_crudPruebasTO();
    return $this->obj->inscribir($this->dato);
  }
}


$datos = json_decode(file_get_contents("php://input"), true);

if ($datos !== null) {
  $obj = new C_insertPruebaTO($datos);
  $resultado = $obj->insertarInscripcion();
  echo $resultado;
} else {
  http_response_code(400);
  echo json_encode(["status" => "error", "message" => "Datos JSON inválidos"]);
}
