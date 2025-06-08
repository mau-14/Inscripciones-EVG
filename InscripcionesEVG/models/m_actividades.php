<?php
class M_actividades
{
  private $conexion;

  public function __construct()
  {
    require_once("config/configDB.php");
    $this->conexion = new mysqli(SERVIDOR, USUARIO, PASSWORD, BBDD);

    // Verifica la conexión
    if ($this->conexion->connect_error) {
      die("Conexión fallida: " . $this->conexion->connect_error);
    }
  }

  public function mMostrarActividadesporIdMomento($idMomento)
  {
    $SQL = "SELECT 
                a.idActividad,
                a.nombre,
                a.maxParticipantes,
                a.fecha,
                a.hora,
                m.nombre as nombre_momento,
                a.idMomento,
                a.tipo,
                a.bases
                FROM Actividades a
                LEFT JOIN Actividades_Alumnos aa ON a.idActividad = aa.idActividad
                LEFT JOIN Actividades_clase ac ON a.idActividad = ac.idActividad
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
        "bases" => $fila['bases']
      ];
    }
    return $resultado;
  }

  public function mInsertarActividad($nombre, $maxParticipantes, $fecha, $hora, $idMomento, $tipo, $bases)
  {
    // 1. Insertar en Actividades
    $SQL = "INSERT INTO Actividades (nombre, maxParticipantes, fecha, hora, bases, idMomento, tipo) 
                VALUES (?, ?, NULLIF(?, ''), NULLIF(?, ''), ?, ?, ?)";
    $stmt = $this->conexion->prepare($SQL);
    $stmt->bind_param("sississ", $nombre, $maxParticipantes, $fecha, $hora, $bases, $idMomento, $tipo);
    $stmt->execute();

    // 2. Obtener el idActividad insertado
    $idActividad = $this->conexion->insert_id;

    // 3. Insertar en la tabla correspondiente según el tipo
    if ($tipo === 'V') {
      $SQL2 = "INSERT INTO Actividades_Alumnos (idActividad) VALUES (?)";
    } else if ($tipo === 'C') {
      $SQL2 = "INSERT INTO Actividades_clase (idActividad) VALUES (?)";
    }

    $stmt2 = $this->conexion->prepare($SQL2);
    $stmt2->bind_param("i", $idActividad);
    $stmt2->execute();

    return true;
  }

  public function mEliminarActividad($idActividad)
  {
    // 1. Eliminar de Actividades_Alumnos
    $SQL1 = "DELETE FROM Actividades_Alumnos WHERE idActividad = ?";
    $stmt1 = $this->conexion->prepare($SQL1);
    $stmt1->bind_param("i", $idActividad);
    $stmt1->execute();

    // 2. Eliminar de Actividades
    $SQL2 = "DELETE FROM Actividades WHERE idActividad = ?";
    $stmt2 = $this->conexion->prepare($SQL2);
    $stmt2->bind_param("i", $idActividad);
    $stmt2->execute();

    return true;
  }

  public function mEditarActividad($idActividad, $nombre, $maxParticipantes, $fecha, $hora, $bases)
  {
    $SQL = "UPDATE Actividades SET 
                nombre = ?, 
                maxParticipantes = ?, 
                fecha = ?, 
                hora = ?,
                bases = ?
                WHERE idActividad = ?";
    $stmt = $this->conexion->prepare($SQL);
    $stmt->bind_param("sisssi", $nombre, $maxParticipantes, $fecha, $hora, $bases, $idActividad);
    $stmt->execute();

    return true;
  }
  public function mMostrarActividades()
  {
    $SQL = "SELECT 
                a.idActividad,
                a.nombre,
                a.maxParticipantes,
                a.fecha,
                a.hora,
                m.nombre as nombre_momento,
                a.idMomento,
                a.tipo,
                a.bases
                FROM Actividades a
                LEFT JOIN Actividades_Alumnos aa ON a.idActividad = aa.idActividad
                LEFT JOIN Actividades_clase ac ON a.idActividad = ac.idActividad
                INNER JOIN Momentos m ON a.idMomento = m.idMomento";
    $stmt = $this->conexion->prepare($SQL);
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
        "bases" => $fila['bases']
      ];
    }
    return $resultado;
  }
}
