<?php
class C_inscripcionesActividades
{
  public $vista;
  private $objinscripcionesActividades;

  public function __construct()
  {
    require_once("models/m_inscripcionesActividades.php");
    $this->objinscripcionesActividades = new MinscripcionesActividades();
  }

  public function cMostrarActividades()
  {
    $this->vista = 'inscripcionesActividades';
    $resultado = $this->objinscripcionesActividades->mMostrarActividades();
    if (is_array($resultado)) {
      return $resultado;
    }
  }

  public function cInscripcionesAlumnos()
  {
    $this->vista = 'inscripcionesAlumnos';
    if (!isset($_GET['id'])) {
      header("Location: index.php?controlador=inscripcionesActividades&accion=cMostrarActividades&error=No se ha seleccionado una actividad");
      exit();
    }
    $idActividad = $_GET['id'];
    $resultado = $this->objinscripcionesActividades->mMostrarAlumnosaInscribir($idActividad);
    if (is_array($resultado)) {
      return $resultado;
    }
  }
  public function cInscribirAlumnos()
  {
    $this->vista = 'inscripcionesActividades';
    $idActividad = $_POST['idActividad'];
    $alumnos = $_POST['alumnos'];
    if (!isset($idActividad)) {
      header("Location: index.php?controlador=inscripcionesActividades&accion=cMostrarActividades&error=No se ha seleccionado una actividad");
      exit();
    }
    $resultado = $this->objinscripcionesActividades->mInscribirAlumnos($alumnos, $idActividad);
    if (!$resultado) {
      header("Location: index.php?controlador=inscripcionesActividades&accion=cMostrarActividades&error=No se ha podido inscribir a los alumnos");
      exit();
    }
    header("Location: index.php?controlador=inscripcionesActividades&accion=cMostrarActividades");
    exit();
  }
  public function cInscripcionesClase()
  {
    $this->vista = 'inscripcionesClase';
    $resultado = $this->objinscripcionesActividades->mMostrarClases();
    if (is_array($resultado)) {
      return $resultado;
    }
  }
  public function cMostrarClasesElegir()
  {
    $this->vista = 'elegirClase';
    $resultado = $this->objinscripcionesActividades->mMostrarClases();
    if (is_array($resultado)) {
      return $resultado;
    }
  }
  public function cInscripcionesAlumnosCoordinador()
  {
    $this->vista = 'inscripcionesAlumnos';
    if (!isset($_POST['idActividad']) || !isset($_POST['clase'])) {
      header("Location: index.php?controlador=inscripcionesActividades&accion=cMostrarActividades&error=No se ha seleccionado una actividad o clase");
      exit();
    }
    $idActividad = $_POST['idActividad'];
    $idClase = $_POST['clase'];  // Cambiado de $_POST['idClase'] a $_POST['clase']
    $resultado = $this->objinscripcionesActividades->mMostrarAlumnosaInscribirCoordinador($idActividad, $idClase);
    if (is_array($resultado)) {
      return $resultado;
    }
  }
  public function cInscribirClase()
  {
    $this->vista = 'inscripcionesClase';
    $idClase = $_POST['idClase'];
    $idActividad = $_POST['idActividad'];
    if (!isset($idClase) || !isset($idActividad)) {
      header("Location: index.php?controlador=inscripcionesActividades&accion=cMostrarActividades&error=No se ha seleccionado una clase o una actividad");
      exit();
    }
    $resultado = $this->objinscripcionesActividades->mInscribirClase($idClase, $idActividad);
    if (!$resultado) {
      header("Location: index.php?controlador=inscripcionesActividades&accion=cMostrarActividades&error=Clase ya inscrita");
      exit();
    }
    header("Location: index.php?controlador=inscripcionesActividades&accion=cMostrarActividades");
    exit();
  }
  public function cInscribirClaseTutor()
  {
    $this->vista = 'inscripcionesActividades';
    $idClase = $_GET['idClase'];
    $idActividad = $_GET['idActividad'];
    if (!isset($idClase) || !isset($idActividad)) {
      header("Location: index.php?controlador=inscripcionesActividades&accion=cMostrarActividades&error=No se ha seleccionado una clase o una actividad");
      exit();
    }
    $resultado = $this->objinscripcionesActividades->mInscribirClase($idClase, $idActividad);
    if (!$resultado) {
      header("Location: index.php?controlador=inscripcionesActividades&accion=cMostrarActividades&error=Clase ya inscrita");
      exit();
    }
    header("Location: index.php?controlador=inscripcionesActividades&accion=cMostrarActividades");
    exit();
  }
  public function cCancelarInscripcionClaseTutor()
  {
    $this->vista = 'inscripcionesActividades';
    $idClase = $_GET['idClase'];
    $idActividad = $_GET['idActividad'];
    if (!isset($idClase) || !isset($idActividad)) {
      header("Location: index.php?controlador=inscripcionesActividades&accion=cMostrarActividades&error=No se ha seleccionado una clase o una actividad");
      exit();
    }
    $resultado = $this->objinscripcionesActividades->mCancelarInscripcionClase($idClase, $idActividad);
    if (!$resultado) {
      header("Location: index.php?controlador=inscripcionesActividades&accion=cMostrarActividades&error=No se ha podido cancelar la inscripción de la clase");
      exit();
    }
    header("Location: index.php?controlador=inscripcionesActividades&accion=cMostrarActividades");
    exit();
  }
}