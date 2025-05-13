<?php

class C_deleteInscripciones
{
  private $obj;
  private $dato;

  public function __construct($datos)
  {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Torneo_Olimpico/app/models/m_crudInscripciones.php';
    $this->dato = $datos;
  }

  public function deleteInscripcion()
  {
    $this->obj = new M_crudInscripciones();
    // Captura y devuelve el resultado de borrar()
    return $this->obj->borrar($this->dato);
  }
}

// Recibir el JSON desde fetch
$datos = json_decode(file_get_contents("php://input"), true);

// Verificar que llegaron datos válidos
if ($datos !== null) {
  $obj = new C_deleteInscripciones($datos);
  $resultado = $obj->deleteInscripcion(); // ← ejecutamos y guardamos el resultado
  echo $resultado; // ← devolvemos la respuesta al JS
} else {
  http_response_code(400);
  echo json_encode([
    "status" => "error",
    "message" => "Datos JSON inválidos"
  ]);
}
