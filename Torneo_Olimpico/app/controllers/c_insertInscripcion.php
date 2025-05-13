<?php


class C_insertInscripcion
{

  private $obj;
  private $dato;

  public function __construct($datos)
  {

    require_once $_SERVER['DOCUMENT_ROOT'] . '/Torneo_Olimpico/app/models/m_crudInscripciones.php';
    $this->dato = $datos;
  }


  public function insertarInscripcion()
  {

    $this->obj = new M_crudInscripciones();
    return $this->obj->inscribir($this->dato);
  }
}


$datos = json_decode(file_get_contents("php://input"), true);

if ($datos !== null) {
  $obj = new C_insertInscripcion($datos);
  $resultado = $obj->insertarInscripcion();
  echo $resultado;
} else {
  http_response_code(400);
  echo json_encode(["status" => "error", "message" => "Datos JSON inválidos"]);
}
