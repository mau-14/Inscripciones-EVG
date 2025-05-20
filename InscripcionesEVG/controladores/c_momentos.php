<?php
Class Cmomentos {
    private $objmomentos;
    public $vista;

    public function __construct() {
        require_once("modelos/m_momentos.php");
        $this->objmomentos = new Mmomentos();
    }

    public function cMostrarMomentos() {
        $this->vista = 'mostrarMomentos';
        $resultado = $this->objmomentos->mMostrarMomentos();
        if (is_array($resultado)) {
            return $resultado;
        }
    }
    public function cMostrarMomentosActividades() {
        $this->vista = 'elegirMomento';
        $resultado = $this->objmomentos->mMostrarMomentos();
        if (is_array($resultado)) {
            return $resultado;
        }
    }
    public function cInsertarMomento() {
        $this->vista = 'mostrarMomentos';
        $nombre = $_POST['nombre'];
        $fechaInicio = $_POST['fechaInicio'];
        $fechaFin = $_POST['fechaFin'];
        $resultado = $this->objmomentos->mInsertarMomento($nombre, $fechaInicio, $fechaFin);
        if ($resultado) {
            header("Location: ./index.php?controlador=momentos&accion=cMostrarMomentos");
            exit();
        }
    }
    public function cEliminarMomento() {
        $this->vista = 'mostrarMomentos';
        $idMomento = $_GET['idMomento'];
        $resultado = $this->objmomentos->mEliminarMomento($idMomento);
        if ($resultado) {
            header("Location: ./index.php?controlador=momentos&accion=cMostrarMomentos");
            exit();
        }
    }
    public function cEditarMomento() {
        $this->vista = 'mostrarMomentos';
        $idMomento = $_POST['idMomento'];
        $nombre = $_POST['nombre'];
        $fechaInicio = $_POST['fechaInicio'];
        $fechaFin = $_POST['fechaFin'];
        $resultado = $this->objmomentos->mEditarMomento($idMomento, $nombre, $fechaInicio, $fechaFin);
        if ($resultado) {
            header("Location: ./index.php?controlador=momentos&accion=cMostrarMomentos");
            exit();
        }
    }
    
}
?>