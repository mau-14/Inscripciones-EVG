<?php
class C_momentos
{
  private $objmomentos;
  public $vista;

  public function __construct()
  {
    require_once("models/m_momentos.php");
    $this->objmomentos = new Mmomentos();
  }

  public function cMostrarMomentos()
  {
    $this->vista = 'mostrarMomentos';
    $resultado = $this->objmomentos->mMostrarMomentos();
    if (is_array($resultado)) {
      return $resultado;
    }
  }
  public function cMostrarMomentosActividades()
  {
    $this->vista = 'elegirMomento';
    $resultado = $this->objmomentos->mMostrarMomentos();
    if (is_array($resultado)) {
      return $resultado;
    }
  }
  public function cInsertarMomento()
  {
    $this->vista = 'mostrarMomentos';

    // Validación de campos obligatorios
    if (empty($_POST['nombre']) || empty($_POST['fechaInicio']) || empty($_POST['fechaFin'])) {
      $msg = urlencode("Faltan campos obligatorios para crear el momento.");
      header("Location: ./index.php?controlador=momentos&accion=cMostrarMomentos&errorMsg=" . $msg);
      exit();
    }

    // Validación de fechas
    $fechaInicio = strtotime($_POST['fechaInicio']);
    $fechaFin = strtotime($_POST['fechaFin']);

    if ($fechaInicio === false || $fechaFin === false) {
      $msg = urlencode("El formato de las fechas no es válido.");
      header("Location: ./index.php?controlador=momentos&accion=cMostrarMomentos&errorMsg=" . $msg);
      exit();
    }

    if ($fechaInicio >= $fechaFin) {
      $msg = urlencode("La fecha de inicio debe ser anterior a la fecha de fin.");
      header("Location: ./index.php?controlador=momentos&accion=cMostrarMomentos&errorMsg=" . $msg);
      exit();
    }

    $nombre = $_POST['nombre'];
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];

    $resultado = $this->objmomentos->mInsertarMomento($nombre, $fechaInicio, $fechaFin);
    if (!$resultado) {
      $msg = urlencode("Error al insertar el momento.");
      header("Location: ./index.php?controlador=momentos&accion=cMostrarMomentos&errorMsg=" . $msg);
      exit();
    }

    return $this->objmomentos->mMostrarMomentos();
  }
  public function cEliminarMomento()
  {
    $this->vista = 'mostrarMomentos';

    if (!isset($_GET['idMomento']) || empty($_GET['idMomento'])) {
      $msg = urlencode("No se ha podido identificar el momento a eliminar.");
      header("Location: ./index.php?controlador=momentos&accion=cMostrarMomentos&errorMsg=" . $msg);
      exit();
    }

    $idMomento = $_GET['idMomento'];
    $resultado = $this->objmomentos->mEliminarMomento($idMomento);
    if (!$resultado) {
      $msg = urlencode("Error al eliminar el momento.");
      header("Location: ./index.php?controlador=momentos&accion=cMostrarMomentos&errorMsg=" . $msg);
      exit();
    }
    return $this->objmomentos->mMostrarMomentos();
  }
  public function cEditarMomento()
  {
    $this->vista = 'mostrarMomentos';

    // Validación de campos obligatorios
    if (empty($_POST['idMomento']) || empty($_POST['nombre']) || empty($_POST['fechaInicio']) || empty($_POST['fechaFin'])) {
      $msg = urlencode("Faltan campos obligatorios para editar el momento.");
      header("Location: ./index.php?controlador=momentos&accion=cMostrarMomentos&errorMsg=" . $msg);
      exit();
    }

    // Validación de fechas
    $fechaInicio = strtotime($_POST['fechaInicio']);
    $fechaFin = strtotime($_POST['fechaFin']);

    if ($fechaInicio === false || $fechaFin === false) {
      $msg = urlencode("El formato de las fechas no es válido.");
      header("Location: ./index.php?controlador=momentos&accion=cMostrarMomentos&errorMsg=" . $msg);
      exit();
    }

    if ($fechaInicio >= $fechaFin) {
      $msg = urlencode("La fecha de inicio debe ser anterior a la fecha de fin.");
      header("Location: ./index.php?controlador=momentos&accion=cMostrarMomentos&errorMsg=" . $msg);
      exit();
    }

    $idMomento = $_POST['idMomento'];
    $nombre = $_POST['nombre'];
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];

    $resultado = $this->objmomentos->mEditarMomento($idMomento, $nombre, $fechaInicio, $fechaFin);
    if (!$resultado) {
      $msg = urlencode("Error al editar el momento.");
      header("Location: ./index.php?controlador=momentos&accion=cMostrarMomentos&errorMsg=" . $msg);
      exit();
    }
    return $this->objmomentos->mMostrarMomentos();
  }
}
