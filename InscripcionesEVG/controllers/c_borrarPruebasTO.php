<?php

class C_borrarPruebasTO
{
  private $obj;
  private $dato;

  public function __construct($datos)
  {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/app/models/m_crudPruebasTO.php';
    $this->dato = $datos;
  }

  public function deleteInscripcion()
  {
    $this->obj = new M_crudPruebasTO();
    // Captura y devuelve el resultado de borrar()
    return $this->obj->borrar($this->dato);
  }
}

// Recibir el JSON desde fetch
$datos = json_decode(file_get_contents("php://input"), true);

// Verificar que llegaron datos válidos
if ($datos !== null) {
  $obj = new C_borrarPruebasTO($datos);
  $resultado = $obj->deleteInscripcion(); // ← ejecutamos y guardamos el resultado
  echo $resultado; // ← devolvemos la respuesta al JS
} else {
  http_response_code(400);
  echo json_encode([
    "status" => "error",
    "message" => "Datos JSON inválidos"
  ]);
}
