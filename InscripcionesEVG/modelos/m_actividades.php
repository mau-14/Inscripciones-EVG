<?php
Class Mactividades {
    private $conexion;

    public function __construct() {
        require_once("config/configDB.php");
        $this->conexion = new mysqli(SERVIDOR, USUARIO, PASSWORD, BBDD);

        // Verifica la conexión
        if ($this->conexion->connect_error) {
            die("Conexión fallida: " . $this->conexion->connect_error);
        }
    }

    public function mMostrarActividadesporIdMomento($idMomento) {
        $SQL = "SELECT 
                a.idActividad,
                a.nombre,
                a.maxParticipantes,
                a.fecha,
                a.hora,
                m.nombre as nombre_momento,
                a.idMomento,
                a.tipo
                FROM Actividades a
                INNER JOIN Actividades_varias av ON a.idActividad = av.idActividad
                INNER JOIN Momentos m ON a.idMomento = m.idMomento
                WHERE a.idMomento = ?";
        $stmt = $this->conexion->prepare($SQL);
        $stmt->bind_param("i", $idMomento);
        $stmt->execute();
        $datos = $stmt->get_result();

        $resultado = [];
        while ($fila = $datos->fetch_assoc()) {
            $resultado[] = [
                "idActividad" => $fila['idActividad'],
                "nombre" => $fila['nombre'],
                "nombre_momento" => $fila['nombre_momento'],
                "maxParticipantes" => $fila['maxParticipantes'],
                "fecha" => $fila['fecha'],
                "hora" => $fila['hora'],
                "idMomento" => $fila['idMomento'],
                "tipo" => $fila['tipo'],
            ];
        }
        return $resultado;
    }

}
?>