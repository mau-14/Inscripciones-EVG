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
        if (isset($_POST['momento'])) {
            $idMomento = $_POST['momento'];
        } else if (isset($_GET['momento'])) {
            $idMomento = $_GET['momento'];
        } else {
            $msg = urlencode("No se ha especificado el momento.");
            header("Location: ./index.php?controlador=momentos&accion=cMostrarMomentos&errorMsg=" . $msg);
            exit();
        }
        if (empty($idMomento)) {
            $msg = urlencode("No se ha especificado el momento.");
            header("Location: ./index.php?controlador=momentos&accion=cMostrarMomentos&errorMsg=" . $msg);
            exit();
        }
        $resultado = $this->objactividades->mMostrarActividadesporIdMomento($idMomento);
        if (is_array($resultado)) {
            return $resultado;
        }
    }

    public function cInsertarActividad() {
        $this->vista = 'mostrarActividades';
    
        // Validación de campos obligatorios
        if (empty($_POST['nombre']) || empty($_POST['maxParticipantes']) || empty($_POST['idMomento']) || empty($_POST['tipo'])) {
            $msg = urlencode("Faltan campos obligatorios para crear la actividad.");
            header("Location: ./index.php?controlador=actividades&accion=cMostrarActividadesporIdMomento&momento=" . ($_POST['idMomento'] ?? '') . "&errorMsg=$msg");
            exit();
        }
    
        // Validación de tipo
        if ($_POST['tipo'] !== 'V' && $_POST['tipo'] !== 'C') {
            $msg = urlencode("El tipo de actividad no es válido.");
            header("Location: ./index.php?controlador=actividades&accion=cMostrarActividadesporIdMomento&momento=" . $_POST['idMomento'] . "&errorMsg=$msg");
            exit();
        }
    
        // Validación de maxParticipantes
        if (!is_numeric($_POST['maxParticipantes']) || $_POST['maxParticipantes'] <= 0) {
            $msg = urlencode("El número máximo de participantes debe ser un número positivo.");
            header("Location: ./index.php?controlador=actividades&accion=cMostrarActividadesporIdMomento&momento=" . $_POST['idMomento'] . "&errorMsg=$msg");
            exit();
        }
    
        // El resto de campos pueden ser null (fecha, hora, bases)
        $nombre = $_POST['nombre'];
        $maxParticipantes = $_POST['maxParticipantes'];
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];
        $idMomento = $_POST['idMomento'];
        $tipo = $_POST['tipo'];
        $bases = $_POST['bases'];
    
        $resultado = $this->objactividades->mInsertarActividad($nombre, $maxParticipantes, $fecha, $hora, $idMomento, $tipo, $bases);
        if ($resultado) {
            header("Location: ./index.php?controlador=actividades&accion=cMostrarActividadesporIdMomento&momento=" . $idMomento);
            exit();
        } else {
            $msg = urlencode("Error al insertar la actividad.");
            header("Location: ./index.php?controlador=actividades&accion=cMostrarActividadesporIdMomento&momento=" . $idMomento . "&errorMsg=$msg");
            exit();
        }
    }

    public function cEliminarActividad() {
        $this->vista = 'mostrarActividades';
        if (isset($_GET['idActividad'])) {
            $idActividad = $_GET['idActividad'];
        } else {
            $msg = urlencode("No se ha podido identificar la actividad a eliminar.");
            header("Location: ./index.php?controlador=momentos&accion=cMostrarMomentos&errorMsg=" . $msg);
            exit();
        }
        if (isset($_GET['idMomento'])) {
            $idMomento = $_GET['idMomento'];
        } else {
            $msg = urlencode("No se ha podido identificar el momento a eliminar.");
            header("Location: ./index.php?controlador=momentos&accion=cMostrarMomentos&errorMsg=" . $msg);
            exit();
        }
        if (empty($idActividad) || empty($idMomento)) {
            $msg = urlencode("No se ha podido identificar la actividad o el momento a eliminar.");
            header("Location: ./index.php?controlador=momentos&accion=cMostrarMomentos&errorMsg=" . $msg);
            exit();
        }
        $resultado = $this->objactividades->mEliminarActividad($idActividad);
        if ($resultado) {
            header("Location: ./index.php?controlador=actividades&accion=cMostrarActividadesporIdMomento&momento=" . $idMomento);
            exit();
        } else {
            $msg = urlencode("Error al eliminar la actividad.");
            header("Location: ./index.php?controlador=actividades&accion=cMostrarActividadesporIdMomento&momento=" . $idMomento . "&errorMsg=" . $msg);
            exit();
        }
    }

    public function cEditarActividad() {
        $this->vista = 'mostrarActividades';
        if (isset($_POST['idActividad'])) {
            $idActividad = $_POST['idActividad'];
        } else {
            $msg = urlencode("Falta el identificador de la actividad.");
            header("Location: ./index.php?controlador=momentos&accion=cMostrarMomentos&errorMsg=" . $msg);
            exit();
        }
        if (isset($_POST['editarNombre'])) {
            $nombre = $_POST['editarNombre'];
        } else {
            $msg = urlencode("Falta el nombre de la actividad.");
            header("Location: ./index.php?controlador=momentos&accion=cMostrarMomentos&errorMsg=" . $msg);
            exit();
        }
        if (isset($_POST['editarMaxParticipantes'])) {
            $maxParticipantes = $_POST['editarMaxParticipantes'];
        } else {
            $msg = urlencode("Falta el número máximo de participantes.");
            header("Location: ./index.php?controlador=momentos&accion=cMostrarMomentos&errorMsg=" . $msg);
            exit();
        }
        if (isset($_POST['idMomento'])) {
            $idMomento = $_POST['idMomento'];
        } else {
            $msg = urlencode("Falta el identificador del momento.");
            header("Location: ./index.php?controlador=momentos&accion=cMostrarMomentos&errorMsg=" . $msg);
            exit();
        }
        if (empty($idActividad) || empty($nombre) || empty($maxParticipantes) || empty($idMomento)) {
            $msg = urlencode("Faltan campos obligatorios para editar la actividad.");
            header("Location: ./index.php?controlador=actividades&accion=cMostrarActividadesporIdMomento&momento=" . $idMomento . "&errorMsg=" . $msg);
            exit();
        }
        if (!is_numeric($maxParticipantes) || $maxParticipantes <= 0) {
            $msg = urlencode("El número máximo de participantes debe ser un número positivo.");
            header("Location: ./index.php?controlador=actividades&accion=cMostrarActividadesporIdMomento&momento=" . $idMomento . "&errorMsg=" . $msg);
            exit();
        }
        $fecha = $_POST['editarFecha'];
        $hora = $_POST['editarHora'];
        $bases = $_POST['editarBases'];
        $resultado = $this->objactividades->mEditarActividad($idActividad, $nombre, $maxParticipantes, $fecha, $hora, $bases);
        if ($resultado) {
            header("Location: ./index.php?controlador=actividades&accion=cMostrarActividadesporIdMomento&momento=" . $idMomento);
            exit();
        } else {
            $msg = urlencode("Error al editar la actividad.");
            header("Location: ./index.php?controlador=actividades&accion=cMostrarActividadesporIdMomento&momento=" . $idMomento . "&errorMsg=" . $msg);
            exit();
        }
    }
}
?>