<?php


class M_crudPruebasTO
{


  private $conexion;


  public function __construct()
  {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Torneo_Olimpico/app/config/configDB.php';

    try {
      DSN . SERVIDOR . ';dbname=' . BBDD . ';charset=utf8';
      $this->conexion = new PDO(DSN, USUARIO, PASSWORD);

      // Hace que lance los errores si se producen y así pillarlos con el try/catch
      $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {

      die('Error de conexión: ' . $e->getMessage());
    }
  }


  public function inscribir($datos)
  {
    try {
      $categoria = 'M';
      $tipo = 'P';

      $sql = "INSERT INTO Torneo_Olimpico (nombre, bases, maxParticipantes, fecha, hora, categoria, tipo)
            VALUES (:nombre, :bases, :max_participantes, :fecha, :hora, :categoria, :tipo)";

      $stmt = $this->conexion->prepare($sql);

      $stmt->bindParam(':nombre', $datos['nombre']);
      $stmt->bindParam(':bases', $datos['bases']);
      $stmt->bindParam(':max_participantes', $datos['maxParticipantes']);
      $stmt->bindParam(':fecha', $datos['fecha']);
      $stmt->bindParam(':hora', $datos['hora']);
      $stmt->bindParam(':categoria', $categoria);
      $stmt->bindParam(':tipo', $tipo);

      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        return json_encode(["success" => "Prueba insertada correctamente."]);
      } else {
        return json_encode(["error" => "No se insertó ninguna fila."]);
      }
    } catch (PDOException $e) {
      error_log("Error al inscribir: " . $e->getMessage());
      return json_encode(["error" => "Error al insertar prueba."]);
    }
  }


  public function borrar($datos)
  {
    try {
      $sql = "DELETE FROM Torneo_Olimpico WHERE idPrueba = :idPrueba";
      $stmt = $this->conexion->prepare($sql);

      $stmt->bindParam(':idPrueba', $datos, PDO::PARAM_INT);

      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        return json_encode(["success" => "Prueba eliminada correctamente."]);
      } else {
        return json_encode(["error" => "No se eliminó ninguna fila."]);
      }
    } catch (PDOException $e) {
      error_log("Error al eliminar: " . $e->getMessage());
      return json_encode(["error" => "Error al eliminar prueba."]);
    }
  }
}
