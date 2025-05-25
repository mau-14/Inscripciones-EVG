<?php
Class Mmomentos {
    private $conexion;

    public function __construct() {
        require_once("config/configDB.php");
        $this->conexion = new mysqli(SERVIDOR, USUARIO, PASSWORD, BBDD);

        // Verifica la conexión
        if ($this->conexion->connect_error) {
            die("Conexión fallida: " . $this->conexion->connect_error);
        }
    }

    public function mMostrarMomentos() {
        $SQL = "SELECT * FROM Momentos";
        $stmt = $this->conexion->prepare($SQL);
        $stmt->execute();
        $datos = $stmt->get_result();

        $resultado = [];
        while ($fila = $datos->fetch_assoc()) {
            $resultado[] = [
                "idMomento" => $fila['idMomento'],
                "nombre" => $fila['nombre'],
                "fecha_inicio" => $fila['fecha_inicio'],
                "fecha_fin" => $fila['fecha_fin']
            ];
        }
        return $resultado;
    }
    public function mInsertarMomento($nombre, $fecha_inicio, $fecha_fin) {
        $SQL = "INSERT INTO Momentos (nombre, fecha_inicio, fecha_fin) VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($SQL);
        $stmt->bind_param("sss", $nombre, $fecha_inicio, $fecha_fin);
        try{
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    public function mEliminarMomento($idMomento) {
        $SQL = "DELETE FROM Momentos WHERE idMomento = ?";
        $stmt = $this->conexion->prepare($SQL);
        $stmt->bind_param("i", $idMomento);
        $stmt->execute();
        return true;
    }
    public function mEditarMomento($idMomento, $nombre, $fecha_inicio, $fecha_fin) {
        $SQL = "UPDATE Momentos SET nombre = ?, fecha_inicio = ?, fecha_fin = ? WHERE idMomento = ?";
        $stmt = $this->conexion->prepare($SQL);
        $stmt->bind_param("sssi", $nombre, $fecha_inicio, $fecha_fin, $idMomento);
        $stmt->execute();
        return true;
    }
}
?>