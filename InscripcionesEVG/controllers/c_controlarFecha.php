<?php

class C_controlarFecha
{
  private $obj;

  public function __construct()
  {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/models/m_momentos.php';
  }

  public function extraerFecha()
  {
    $this->obj = new Mmomentos();
    $resultado = $this->obj->mMostrarMomentos();
    $resultado = json_encode($resultado);
    header('Content-Type: application/json');
    echo $resultado;
  }

  public function guardarMomentoActivo()
  {
    session_start();

    // Leer el JSON enviado desde JS
    $json = file_get_contents('php://input');
    $momento = json_decode($json, true);

    if ($momento) {
      $_SESSION['momento_actual'] = $momento;

      header('Content-Type: application/json');
      echo json_encode(['ok' => true]);
    } else {
      header('HTTP/1.1 400 Bad Request');
      echo json_encode(['error' => 'Datos inválidos']);
    }
    exit;
  }
}
