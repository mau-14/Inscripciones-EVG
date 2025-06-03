<?php
    class MinscripcionesActividades {
        private $conexion;

        public function __construct() {
            require_once("config/configDB.php");
            $this->conexion = new mysqli(SERVIDOR, USUARIO, PASSWORD, BBDD);

            // Verifica la conexión
            if ($this->conexion->connect_error) {
                die("Conexión fallida: " . $this->conexion->connect_error);
            }
        }

        public function mMostrarActividades() {
            $SQL = "SELECT 
                a.idActividad,
                a.nombre,
                a.maxParticipantes,
                a.fecha,
                a.hora,
                a.tipo,
                a.bases
                FROM Actividades a
                    JOIN Momentos m ON a.idMomento = m.idMomento
                    WHERE ? BETWEEN m.fecha_inicio AND m.fecha_fin";
        
        $stmt = $this->conexion->prepare($SQL);
        
            // Usar la fecha actual del sistema en formato YYYY-MM-DD
            $fechaActual = date('Y-m-d');
            $stmt->bind_param("s", $fechaActual);
        
        $stmt->execute();
        $datos = $stmt->get_result();

        $resultado = [];
        while ($fila = $datos->fetch_assoc()) {
            $resultado[] = [
                "idActividad" => $fila['idActividad'],
                "nombre" => $fila['nombre'],
                "maxParticipantes" => $fila['maxParticipantes'],
                "fecha" => $fila['fecha'],
                "hora" => $fila['hora'],
                "tipo" => $fila['tipo'],
                "bases" => $fila['bases']
            ];
        }
        return $resultado;
        }
        public function mMostrarAlumnosaInscribir(){
            $SQL = "SELECT * from Alumnos where idClase = ?";
            $stmt = $this->conexion->prepare($SQL);
            $stmt->bind_param("i", $idClase);
            $idClase = 1;
            $stmt->execute();
            $datos = $stmt->get_result();

            $resultado = [];
            while ($fila = $datos->fetch_assoc()) {
                $resultado[] = [
                    "idAlumno" => $fila['idAlumno'],
                    "nombre" => $fila['nombre'],
                    "sexo" => $fila['sexo'],
                    "idClase" => $fila['idClase']
                ];
            }
            return $resultado;
        }
        public function mInscribirAlumnos($alumnos, $idActividad){
            $SQL = "INSERT INTO Participan (idAlumno, idActividad, fecha_inscripcion) VALUES (?, ?, NOW())";
            $stmt = $this->conexion->prepare($SQL);
            $stmt->bind_param("ii", $alumno, $idActividad);
            foreach ($alumnos as $alumno) {
                $stmt->execute();
            }
            return true;
        }
    public function mMostrarClases(){
        $SQL = "SELECT * from Clases";
        $stmt = $this->conexion->prepare($SQL);
        $stmt->execute();
        $datos = $stmt->get_result();

        $resultado = [];
        while ($fila = $datos->fetch_assoc()) {
            $resultado[] = [
                "idClase" => $fila['idClase'],
                "nombre" => $fila['nombre'],
                "idEtapa" => $fila['idEtapa'],
                "idTutor" => $fila['idTutor']
            ];
        }
        return $resultado;
    }
    public function mInscribirClase($idClase, $idActividad){
        $SQL = "INSERT INTO Se_inscriben (idActividad, idClase, fecha_inscripcion) VALUES (?, ?, NOW())";
        $stmt = $this->conexion->prepare($SQL);
        $stmt->bind_param("ii", $idActividad, $idClase);
        $stmt->execute();
        return true;
    }
}
?>