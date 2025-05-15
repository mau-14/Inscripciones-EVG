<?php
require_once 'config/config.php';



if (!isset($_GET["controlador"])) {
  $_GET["controlador"] = DEFAULT_CONTROLADOR;
}
if (!isset($_GET["accion"])) {
  $_GET["accion"] = DEFAULT_ACCION;
}

$rutaControlador = CONTROLADORES . 'c_' . $_GET["controlador"] . '.php';

if (!file_exists($rutaControlador)) {
  $rutaControlador = CONTROLADORES . 'c_' . DEFAULT_CONTROLADOR . '.php';
}

require_once $rutaControlador;

$nombreControlador = 'C_' . $_GET["controlador"];

//TODO poner aqui lo de recibir datos 
$controlador = new $nombreControlador();

if (method_exists($controlador, $_GET["accion"])) {
  $dataToView["data"] = $controlador->{$_GET["accion"]}();
}

if (isset($controlador->vista) && !empty($controlador->vista)) {
  include 'reusables/nav.php';
  require_once 'views/' . $controlador->vista . '.php';
}
