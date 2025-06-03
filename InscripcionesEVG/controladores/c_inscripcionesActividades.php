<?php
    class CinscripcionesActividades {
        public $vista;
        private $objinscripcionesActividades;

        public function __construct() {
            require_once("modelos/m_inscripcionesActividades.php");
            $this->objinscripcionesActividades = new MinscripcionesActividades();
        }

        public function cMostrarActividades() {
            $this->vista = 'inscripcionesActividades';
            $resultado = $this->objinscripcionesActividades->mMostrarActividades();
            if (is_array($resultado)) {
                return $resultado;
            }
            
        }

        public function cInscripcionesAlumnos(){
            $this->vista = 'inscripcionesAlumnos';
            $resultado = $this->objinscripcionesActividades->mMostrarAlumnosaInscribir();
            if (is_array($resultado)) {
                return $resultado;
            }

        }
        public function cInscribirAlumnos(){
            $this->vista = 'inscripcionesActividades';
            $idActividad = $_POST['idActividad'];
            $alumnos = $_POST['alumnos'];
            $resultado = $this->objinscripcionesActividades->mInscribirAlumnos($alumnos, $idActividad);
            if ($resultado) {
                header("Location: index.php?controlador=inscripcionesActividades&accion=cMostrarActividades");
                exit(); 
            }
        }
        public function cInscripcionesClase(){
            $this->vista = 'inscripcionesClase';
            $resultado = $this->objinscripcionesActividades->mMostrarClases();
            if (is_array($resultado)) {
                return $resultado;
            }
        }
        public function cInscribirClase(){
            $this->vista = 'inscripcionesClase';
            $idClase = $_POST['idClase'];
            $idActividad = $_POST['idActividad'];
            $resultado = $this->objinscripcionesActividades->mInscribirClase($idClase, $idActividad);
            if ($resultado) {
                header("Location: index.php?controlador=inscripcionesActividades&accion=cMostrarActividades");
                exit(); 
            }
        }
    }
?>