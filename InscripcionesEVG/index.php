<?php
require_once 'config/config.php';

$controladorNombre = $_GET["controlador"] ?? DEFAULT_CONTROLADOR;
$accion = $_GET["accion"] ?? DEFAULT_ACCION;

$rutaControlador = CONTROLADORES . 'c_' . $controladorNombre . '.php';
if (!file_exists($rutaControlador)) {
  $controladorNombre = DEFAULT_CONTROLADOR;
  $rutaControlador = CONTROLADORES . 'c_' . $controladorNombre . '.php';
}

require_once $rutaControlador;
$nombreControlador = 'C_' . $controladorNombre;

$isJson = isset($_GET['j']);

if ($isJson) {
  $json = file_get_contents('php://input');
  $datos = json_decode($json, true) ?? [];

  $controlador = new $nombreControlador($datos);

  if (method_exists($controlador, $accion)) {
    $controlador->{$accion}();
    error_log('ENTRA AQUII');
  }
} else {
  $controlador = new $nombreControlador();

  if (method_exists($controlador, $accion)) {
    $dataToView["data"] = $controlador->{$accion}();
  }

  if (isset($controlador->vista) && !empty($controlador->vista)) {
    include 'reusables/nav.php';
    require_once 'views/' . $controlador->vista . '.php';
  }
}
