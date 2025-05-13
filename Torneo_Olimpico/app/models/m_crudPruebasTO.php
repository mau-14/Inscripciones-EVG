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
      $tipo = $datos['tipo'];  // Usar el tipo recibido en los datos

      // Insertar en la tabla Torneo_Olimpico
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

        // Obtener el ID de la última inserción de Torneo_Olimpico
        $idTorneo = $this->conexion->lastInsertId();

        // Dependiendo del tipo, insertar en la tabla correspondiente
        if ($tipo === 'C') {
          // Insertar en la tabla 4X100
          $sql_4x100 = "INSERT INTO 4X100 (idPrueba) VALUES (:idPrueba)";
          $stmt_4x100 = $this->conexion->prepare($sql_4x100);
          $stmt_4x100->bindParam(':idPrueba', $idTorneo);
          $stmt_4x100->execute();
        } elseif ($tipo === 'P') {
          // Insertar en la tabla Pruebas_Olimpicas
          $sql_pruebas = "INSERT INTO Pruebas_Olimpicas (idPrueba) VALUES (:idPrueba)";
          $stmt_pruebas = $this->conexion->prepare($sql_pruebas);
          $stmt_pruebas->bindParam(':idPrueba', $idTorneo);
          $stmt_pruebas->execute();
        }
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
      $this->conexion->beginTransaction(); // INICIO TRANSACCIÓN

      // Borrar el registro en la tabla padre (Torneo_Olimpico)

      $sql_delete = "DELETE FROM Torneo_Olimpico WHERE idPrueba = :idPruebaM OR idPrueba = :idPruebaF";
      $stmt_delete = $this->conexion->prepare($sql_delete);
      $stmt_delete->bindParam(':idPruebaM', $datos['idPruebaM'], PDO::PARAM_INT);
      $stmt_delete->bindParam(':idPruebaF', $datos['idPruebaF'], PDO::PARAM_INT);

      $stmt_delete->execute();

      $categorias = ['M', 'F'];
      $tipo = $datos['tipo'];

      $sql_insert = "INSERT INTO Torneo_Olimpico (nombre, bases, maxParticipantes, fecha, hora, categoria, tipo)
            VALUES (:nombre, :bases, :max_participantes, :fecha, :hora, :categoria, :tipo)";

      $stmt = $this->conexion->prepare($sql_insert);

      foreach ($categorias as $categoria) {
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':bases', $datos['bases']);
        $stmt->bindParam(':max_participantes', $datos['maxParticipantes']);
        $stmt->bindParam(':fecha', $datos['fecha']);
        $stmt->bindParam(':hora', $datos['hora']);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->execute();

        // Obtener el ID de la última inserción de Torneo_Olimpico
        $idTorneo = $this->conexion->lastInsertId();

        // Dependiendo del tipo, insertar en la tabla correspondiente
        if ($tipo === 'C') {
          // Insertar en la tabla 4X100
          $sql_4x100 = "INSERT INTO 4X100 (idPrueba) VALUES (:idPrueba)";
          $stmt_4x100 = $this->conexion->prepare($sql_4x100);
          $stmt_4x100->bindParam(':idPrueba', $idTorneo);
          $stmt_4x100->execute();
        } elseif ($tipo === 'P') {
          // Insertar en la tabla Pruebas_Olimpicas
          $sql_pruebas = "INSERT INTO Pruebas_Olimpicas (idPrueba) VALUES (:idPrueba)";
          $stmt_pruebas = $this->conexion->prepare($sql_pruebas);
          $stmt_pruebas->bindParam(':idPrueba', $idTorneo);
          $stmt_pruebas->execute();
        }
      }

      $this->conexion->commit(); // FIN TRANSACCIÓN EXITOSA
      return json_encode(["success" => "Prueba modificada correctamente."]);
    } catch (PDOException $e) {
      $this->conexion->rollBack(); // DESHACER TODO SI FALLA
      error_log("Error al modificar: " . $e->getMessage());
      return json_encode(["error" => "Error al modificar prueba."]);
    }
  }
}
