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
}
?>