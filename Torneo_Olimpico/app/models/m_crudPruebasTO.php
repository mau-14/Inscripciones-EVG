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
      $this->conexion->beginTransaction(); // INICIO TRANSACCIÓN

      $categorias = ['M', 'F'];
      $tipo = 'P';

      $sql = "INSERT INTO Torneo_Olimpico (nombre, bases, maxParticipantes, fecha, hora, categoria, tipo)
            VALUES (:nombre, :bases, :max_participantes, :fecha, :hora, :categoria, :tipo)";

      $stmt = $this->conexion->prepare($sql);

      foreach ($categorias as $categoria) {
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':bases', $datos['bases']);
        $stmt->bindParam(':max_participantes', $datos['maxParticipantes']);
        $stmt->bindParam(':fecha', $datos['fecha']);
        $stmt->bindParam(':hora', $datos['hora']);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->execute();
      }

      $this->conexion->commit(); // FIN TRANSACCIÓN EXITOSA
      return json_encode(["success" => "Pruebas insertadas correctamente."]);
    } catch (PDOException $e) {
      $this->conexion->rollBack(); // DESHACER TODO SI FALLA
      error_log("Error al inscribir: " . $e->getMessage());
      return json_encode(["error" => "Error al insertar pruebas."]);
    }
  }


  public function borrar($datos)
  {
    try {
      // Verificar si las claves 'idPruebaM' e 'idPruebaF' están presentes
      if (!isset($datos['idPruebaM']) || !isset($datos['idPruebaF'])) {
        return json_encode(["error" => "Faltan los identificadores para eliminar la prueba."]);
      }

      $sql = "DELETE FROM Torneo_Olimpico WHERE idPrueba = :idPruebaM OR idPrueba = :idPruebaF";
      $stmt = $this->conexion->prepare($sql);

      // Vincular los parámetros con los valores de los datos
      $stmt->bindParam(':idPruebaM', $datos['idPruebaM'], PDO::PARAM_INT);
      $stmt->bindParam(':idPruebaF', $datos['idPruebaF'], PDO::PARAM_INT);

      // Ejecutar la consulta
      $stmt->execute();

      // Verificar si se eliminó al menos una fila
      if ($stmt->rowCount() > 0) {
        return json_encode(["success" => "Prueba eliminada correctamente."]);
      } else {
        return json_encode(["error" => "No se eliminó ninguna fila."]);
      }
    } catch (PDOException $e) {
      // Log de error para depuración
      error_log("Error al eliminar: " . $e->getMessage());
      return json_encode(["error" => "Error al eliminar prueba."]);
    }
  }


  public function modificar($datos)
  {
    try {
      // Modificamos la consulta SQL para actualizar tanto idPruebaM como idPruebaF
      $sql = "UPDATE Torneo_Olimpico
                SET nombre = :nombre,
                    bases = :bases,
                    maxParticipantes = :max_participantes,
                    fecha = :fecha,
                    hora = :hora
                WHERE idPrueba = :idPruebaM OR idPrueba = :idPruebaF";

      // Preparamos la consulta
      $stmt = $this->conexion->prepare($sql);

      // Vinculamos los parámetros
      $stmt->bindParam(':nombre', $datos['nombre']);
      $stmt->bindParam(':bases', $datos['bases']);
      $stmt->bindParam(':max_participantes', $datos['maxParticipantes']);
      $stmt->bindParam(':fecha', $datos['fecha']);
      $stmt->bindParam(':hora', $datos['hora']);
      $stmt->bindParam(':idPruebaM', $datos['idPruebaM'], PDO::PARAM_INT); // idPruebaM
      $stmt->bindParam(':idPruebaF', $datos['idPruebaF'], PDO::PARAM_INT); // idPruebaF

      // Ejecutamos la consulta
      $stmt->execute();

      // Comprobamos si se modificaron filas
      if ($stmt->rowCount() > 0) {
        return json_encode(["success" => "Prueba modificadas correctamente."]);
      } else {
        return json_encode(["error" => "No se modificó ninguna prueba."]);
      }
    } catch (PDOException $e) {
      // Manejamos errores
      error_log("Error al modificar: " . $e->getMessage());
      return json_encode(["error" => "Error al modificar prueba."]);
    }
  }
}
