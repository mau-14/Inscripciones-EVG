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

  public function guardarMomentosActivos()
  {
    session_start();

    $json = file_get_contents('php://input');
    $datos = json_decode($json, true);

    if ($datos) {
      if (isset($datos['momentoActual'])) {
        $_SESSION['momento_actual'] = $datos['momentoActual'];
      }

      if (isset($datos['momentoTorneoOlimpico'])) {
        $_SESSION['momento_torneo'] = $datos['momentoTorneoOlimpico'];
      }

      header('Content-Type: application/json');
      echo json_encode(['ok' => true]);
    } else {
      header('HTTP/1.1 400 Bad Request');
      echo json_encode(['error' => 'Datos inválidos']);
    }
    exit;
  }
}
