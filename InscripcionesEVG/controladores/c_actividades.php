<?php
session_start();

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
            // Separar el string en partes
            list($idMomento, $fechaInicio, $fechaFin) = explode('|', $_POST['momento']);
    
            // Guardar en sesión si quieres usarlas después
            $_SESSION['idMomento'] = $idMomento;
            $_SESSION['fechaInicioMomento'] = $fechaInicio;
            $_SESSION['fechaFinMomento'] = $fechaFin;
    
        } else if (isset($_GET['momento'])) {
            $_SESSION['idMomento'] = $_GET['momento'];
        }
    
        if (!isset($_SESSION['idMomento'])) {
            $msg = urlencode("No se ha especificado el momento.");
            header("Location: ./index.php?controlador=momentos&accion=cMostrarMomentos&errorMsg=" . $msg);
            exit();
        }
    
        $resultado = $this->objactividades->mMostrarActividadesporIdMomento($_SESSION['idMomento']);
        if (is_array($resultado)) {
            return $resultado;
        }
    }
    

    public function cInsertarActividad() {
        ob_clean(); // Limpia cualquier salida anterior
        header('Content-Type: application/json');
        $this->vista = 'mostrarActividades';
    
        // Validación de campos obligatorios
        if (empty($_POST['nombre']) || empty($_POST['maxParticipantes']) || empty($_POST['tipo'])) {
            echo json_encode(['success' => false, 'error' => 'Faltan campos obligatorios para crear la actividad.']);
            exit();
        }
    
        // Validación de tipo
        if ($_POST['tipo'] !== 'V' && $_POST['tipo'] !== 'C') {
            echo json_encode(['success' => false, 'error' => 'El tipo de actividad no es válido.']);
            exit();
        }
    
        // Validación de maxParticipantes
        if (!is_numeric($_POST['maxParticipantes']) || $_POST['maxParticipantes'] <= 0) {
            echo json_encode(['success' => false, 'error' => 'El número máximo de participantes debe ser un número positivo.']);
            exit();
        }
        
        // Validación de fechas
        if (!empty($_POST['fecha'])) {
            $fechaActividad = new DateTime($_POST['fecha']);
            $fechaInicio = new DateTime($_SESSION['fechaInicioMomento']);
            $fechaFin = new DateTime($_SESSION['fechaFinMomento']);
            
            if ($fechaActividad < $fechaInicio || $fechaActividad > $fechaFin) {
                echo json_encode([
                    'success' => false, 
                    'error' => 'La fecha de la actividad debe estar entre ' . $fechaInicio->format('d/m/Y') . ' y ' . $fechaFin->format('d/m/Y')
                ]);
                exit();
            }
        }
    
        // El resto de campos pueden ser null (fecha, hora, bases)
        $nombre = $_POST['nombre'];
        $maxParticipantes = $_POST['maxParticipantes'];
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];
        $tipo = $_POST['tipo'];
        $bases = $_POST['bases'];
    
        $resultado = $this->objactividades->mInsertarActividad($nombre, $maxParticipantes, $fecha, $hora, $_SESSION['idMomento'], $tipo, $bases);
        if ($resultado) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al insertar la actividad.']);
        }
        exit();
    }

    public function cEliminarActividad() {
        ob_clean(); // Limpia cualquier salida anterior
        header('Content-Type: application/json');
        $this->vista = 'mostrarActividades';
        
        if (!isset($_GET['idActividad'])) {
            echo json_encode(['success' => false, 'error' => 'No se ha podido identificar la actividad a eliminar.']);
            exit();
        }

        $idActividad = $_GET['idActividad'];
        $resultado = $this->objactividades->mEliminarActividad($idActividad);
        
        if ($resultado) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al eliminar la actividad.']);
        }
        exit();
    }

    public function cEditarActividad() {
        ob_clean(); // Limpia cualquier salida anterior
        header('Content-Type: application/json');
        $this->vista = 'mostrarActividades';
        
        if (!isset($_POST['idActividad'])) {
            echo json_encode(['success' => false, 'error' => 'Falta el identificador de la actividad.']);
            exit();
        }

        if (!isset($_POST['editarNombre'])) {
            echo json_encode(['success' => false, 'error' => 'Falta el nombre de la actividad.']);
            exit();
        }

        if (!isset($_POST['editarMaxParticipantes'])) {
            echo json_encode(['success' => false, 'error' => 'Falta el número máximo de participantes.']);
            exit();
        }

        $idActividad = $_POST['idActividad'];
        $nombre = $_POST['editarNombre'];
        $maxParticipantes = $_POST['editarMaxParticipantes'];

        if (empty($idActividad) || empty($nombre) || empty($maxParticipantes)) {
            echo json_encode(['success' => false, 'error' => 'Faltan campos obligatorios para editar la actividad.']);
            exit();
        }

        if (!is_numeric($maxParticipantes) || $maxParticipantes <= 0) {
            echo json_encode(['success' => false, 'error' => 'El número máximo de participantes debe ser un número positivo.']);
            exit();
        }
        
        // Validación de fechas
        if (!empty($_POST['editarFecha'])) {
            $fechaActividad = new DateTime($_POST['editarFecha']);
            $fechaInicio = new DateTime($_SESSION['fechaInicioMomento']);
            $fechaFin = new DateTime($_SESSION['fechaFinMomento']);
            
            if ($fechaActividad < $fechaInicio || $fechaActividad > $fechaFin) {
                echo json_encode([
                    'success' => false, 
                    'error' => 'La fecha de la actividad debe estar entre ' . $fechaInicio->format('d/m/Y') . ' y ' . $fechaFin->format('d/m/Y')
                ]);
                exit();
            }
        }

        $fecha = $_POST['editarFecha'];
        $hora = $_POST['editarHora'];
        $bases = $_POST['editarBases'];
        
        $resultado = $this->objactividades->mEditarActividad($idActividad, $nombre, $maxParticipantes, $fecha, $hora, $bases);
        if ($resultado) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al editar la actividad.']);
        }
        exit();
    }
}
?>