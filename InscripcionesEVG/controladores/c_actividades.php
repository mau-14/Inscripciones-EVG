<?php
Class Cactividades {
    private $objactividades;
    public $vista;

    public function __construct() {
        require_once("modelos/m_actividades.php");
        $this->objactividades = new Mactividades();
    }

    public function cMostrarActividadesporIdMomento() {
        $this->vista = 'mostrarActividades';
        $idMomento = isset($_POST['momento']) ? $_POST['momento'] : $_GET['momento'];
        $resultado = $this->objactividades->mMostrarActividadesporIdMomento($idMomento);
        if (is_array($resultado)) {
            return $resultado;
        }
    }

    public function cInsertarActividad() {
        $this->vista = 'mostrarActividades';
        $nombre = $_POST['nombre'];
        $maxParticipantes = $_POST['maxParticipantes'];
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];
        $idMomento = $_POST['idMomento'];
        $resultado = $this->objactividades->mInsertarActividad($nombre, $maxParticipantes, $fecha, $hora, $idMomento);
        if ($resultado) {
            header("Location: ./index.php?controlador=actividades&accion=cMostrarActividadesporIdMomento&momento=" . $idMomento);
            exit();
        }
    }

    public function cEliminarActividad() {
        $this->vista = 'mostrarActividades';
        $idActividad = $_GET['idActividad'];
        $idMomento = $_GET['idMomento'];
        $resultado = $this->objactividades->mEliminarActividad($idActividad);
        if ($resultado) {
            header("Location: ./index.php?controlador=actividades&accion=cMostrarActividadesporIdMomento&momento=" . $idMomento);
            exit();
        }
    }

    public function cEditarActividad() {
        $this->vista = 'mostrarActividades';
        $idActividad = $_POST['idActividad'];
        $nombre = $_POST['editarNombre'];
        $maxParticipantes = $_POST['editarMaxParticipantes'];
        $fecha = $_POST['editarFecha'];
        $hora = $_POST['editarHora'];
        $idMomento = $_POST['idMomento'];
        
        $resultado = $this->objactividades->mEditarActividad($idActividad, $nombre, $maxParticipantes, $fecha, $hora);
        if ($resultado) {
            header("Location: ./index.php?controlador=actividades&accion=cMostrarActividadesporIdMomento&momento=" . $idMomento);
            exit();
        }
    }
}
?>