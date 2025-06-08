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
        ob_clean(); // Limpia cualquier salida anterior
        header('Content-Type: application/json');
        $this->vista = 'mostrarMomentos';

        if (empty($_POST['nombre']) || empty($_POST['fechaInicio']) || empty($_POST['fechaFin'])) {
            echo json_encode(['success' => false, 'error' => 'Faltan campos obligatorios para crear el momento.']);
            exit();
        }

        $fechaInicio = strtotime($_POST['fechaInicio']);
        $fechaFin = strtotime($_POST['fechaFin']);

        if ($fechaInicio === false || $fechaFin === false) {
            echo json_encode(['success' => false, 'error' => 'El formato de las fechas no es válido.']);
            exit();
        }

        if ($fechaInicio >= $fechaFin) {
            echo json_encode(['success' => false, 'error' => 'La fecha de inicio debe ser anterior a la fecha de fin.']);
            exit();
        }

        $nombre = $_POST['nombre'];
        $fechaInicio = $_POST['fechaInicio'];
        $fechaFin = $_POST['fechaFin'];

        $resultado = $this->objmomentos->mInsertarMomento($nombre, $fechaInicio, $fechaFin);
        if ($resultado) {
            echo json_encode(['success' => true]);
        }else{
            echo json_encode(['success' => false, 'error' => 'Error al insertar el momento.']);
        }
        exit();
    }

    public function cEliminarMomento() {
        ob_clean(); // Limpia cualquier salida anterior
        header('Content-Type: application/json');
        $this->vista = 'mostrarMomentos';

        if (!isset($_GET['idMomento']) || empty($_GET['idMomento'])) {
            echo json_encode(['success' => false, 'error' => 'No se ha podido identificar el momento a eliminar.']);
            exit();
        }

        $idMomento = $_GET['idMomento'];
        $resultado = $this->objmomentos->mEliminarMomento($idMomento);
        if ($resultado) {
            echo json_encode(['success' => true]);
        }else{
            echo json_encode(['success' => false, 'error' => 'No se puede eliminar el momento tiene actividades asociadas.']);
        }
        exit();
    }

    public function cEditarMomento() {
        ob_clean(); // Limpia cualquier salida anterior
        header('Content-Type: application/json');
        $this->vista = 'mostrarMomentos';

        if (empty($_POST['idMomento']) || empty($_POST['nombre']) || empty($_POST['fechaInicio']) || empty($_POST['fechaFin'])) {
            echo json_encode(['success' => false, 'error' => 'Faltan campos obligatorios para editar el momento.']);
            exit();
        }

        $fechaInicio = strtotime($_POST['fechaInicio']);
        $fechaFin = strtotime($_POST['fechaFin']);

        if ($fechaInicio === false || $fechaFin === false) {
            echo json_encode(['success' => false, 'error' => 'El formato de las fechas no es válido.']);
            exit();
        }

        if ($fechaInicio >= $fechaFin) {
            echo json_encode(['success' => false, 'error' => 'La fecha de inicio debe ser anterior a la fecha de fin.']);
            exit();
        }

        $idMomento = $_POST['idMomento'];
        $nombre = $_POST['nombre'];
        $fechaInicio = $_POST['fechaInicio'];
        $fechaFin = $_POST['fechaFin'];

        $resultado = $this->objmomentos->mEditarMomento($idMomento, $nombre, $fechaInicio, $fechaFin);
        if ($resultado) {
            echo json_encode(['success' => true]);
        }else{
            echo json_encode(['success' => false, 'error' => 'Error al editar el momento.']);
        }
        exit();
    }
}
?>
