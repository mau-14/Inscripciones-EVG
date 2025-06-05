<?php


class M_crudPruebasTO
{


  private $conexion;


  public function __construct()
  {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/config/configDB.php';

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
      $datos['fecha'] = empty($datos['fecha']) ? null : $datos['fecha'];
      $datos['hora'] = empty($datos['hora']) ? null : $datos['hora'];

      $validacion = $this->validarDatosPrueba($datos);
      if ($validacion !== true) {
        return json_encode($validacion);
      }

      // Insertar en la tabla Torneo_Olimpico
      $sql = "INSERT INTO Torneo_Olimpico (nombre, bases, maxParticipantes, fecha, hora, categoria, tipo)
                VALUES (:nombre, :bases, :max_participantes, :fecha, :hora, :categoria, :tipo)";

      $stmt = $this->conexion->prepare($sql);

      foreach ($categorias as $categoria) {
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':bases', $datos['bases']);
        $stmt->bindParam(':max_participantes', $datos['maxParticipantes']);
        $stmt->bindValue(':fecha', $datos['fecha'], is_null($datos['fecha']) ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':hora', $datos['hora'], is_null($datos['hora']) ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->execute();

        // Obtener el ID de la última inserción de Torneo_Olimpico
        $idTorneo = $this->conexion->lastInsertId();

        $sql_pruebas = "INSERT INTO Pruebas_Olimpicas (idPrueba) VALUES (:idPrueba)";
        $stmt_pruebas = $this->conexion->prepare($sql_pruebas);
        $stmt_pruebas->bindParam(':idPrueba', $idTorneo);
        $stmt_pruebas->execute();
      }

      $this->conexion->commit(); // FIN TRANSACCIÓN EXITOSA
      return json_encode(["success" => "Prueba insertada correctamente."]);
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
      $tipo = 'P';
      $datos['fecha'] = empty($datos['fecha']) ? null : $datos['fecha'];
      $datos['hora'] = empty($datos['hora']) ? null : $datos['hora'];


      $sql_update = "UPDATE Torneo_Olimpico
                    SET nombre = :nombre,
                        bases = :bases,
                        maxParticipantes = :max_participantes,
                        fecha = :fecha,
                        hora = :hora,
                        tipo = :tipo
                    WHERE idPrueba = :idPruebaM OR idPrueba = :idPruebaF;";

      $stmt = $this->conexion->prepare($sql_update);


      $stmt->bindParam(':nombre', $datos['nombre']);
      $stmt->bindParam(':bases', $datos['bases']);
      $stmt->bindParam(':max_participantes', $datos['maxParticipantes']);
      $stmt->bindParam(':fecha', $datos['fecha']);
      $stmt->bindParam(':hora', $datos['hora']);
      $stmt->bindParam(':idPruebaM', $datos['idPruebaM'], PDO::PARAM_INT);
      $stmt->bindParam(':idPruebaF', $datos['idPruebaF'], PDO::PARAM_INT);
      $stmt->bindParam(':tipo', $tipo);
      $stmt->execute();

      $this->conexion->commit(); // FIN TRANSACCIÓN EXITOSA
      return json_encode(["success" => "Prueba modificada correctamente."]);
    } catch (PDOException $e) {
      $this->conexion->rollBack(); // DESHACER TODO SI FALLA
      error_log("Error al modificar: " . $e->getMessage());
      return json_encode(["error" => "Error al modificar prueba."]);
    }
  }

  private function validarDatosPrueba(array $datos)
  {
    // Validar nombre
    if (empty($datos['nombre']) || !is_string($datos['nombre'])) {
      return ["error" => "El nombre es obligatorio y debe ser texto."];
    }

    // Validar bases
    if (empty($datos['bases']) || !is_string($datos['bases'])) {
      return ["error" => "Las bases son obligatorias y deben ser texto."];
    }

    // Validar maxParticipantes: entero positivo entre 1 y 1000 (por ejemplo)
    if (
      !isset($datos['maxParticipantes'])
      || !filter_var($datos['maxParticipantes'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 1000]])
    ) {
      return ["error" => "maxParticipantes debe ser un entero positivo entre 1 y 1000."];
    }

    // Validar fecha si existe
    if (!empty($datos['fecha'])) {
      // Validar formato YYYY-MM-DD
      $fecha = $datos['fecha'];
      $d = DateTime::createFromFormat('Y-m-d', $fecha);
      if (!($d && $d->format('Y-m-d') === $fecha)) {
        return ["error" => "La fecha debe tener el formato YYYY-MM-DD."];
      }
      // Validar que no sea una fecha pasada
      // $hoy = new DateTime('today');
      // if ($d < $hoy) {
      //   return ["error" => "La fecha no puede ser anterior a hoy."];
      // }
    }

    // Validar hora si existe
    if (!empty($datos['hora'])) {
      // Validar formato HH:MM (24h)
      $hora = $datos['hora'];
      if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $hora)) {
        return ["error" => "La hora debe tener formato HH:MM en 24 horas."];
      }
      // Validar hora dentro de rango permitido 09:00 - 15:00
      if ($hora < "09:00" || $hora > "15:00") {
        return ["error" => "La hora debe estar entre las 09:00 y las 15:00."];
      }
    }

    if (isset($datos['categoria'])) {
      $categoriasPermitidas = ['M', 'F'];
      if (!in_array($datos['categoria'], $categoriasPermitidas)) {
        return ["error" => "La categoría no es válida. Debe ser 'M' o 'F'."];
      }
    }


    // Si todo está bien
    return true;
  }
}
