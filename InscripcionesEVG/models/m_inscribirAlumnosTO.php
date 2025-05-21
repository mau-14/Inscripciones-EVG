<?php


class M_inscribirAlumnosTO
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


  public function actualizarInscripciones($datosJSON)
  {
    try {
      $this->conexion->beginTransaction(); // INICIO TRANSACCIÓN

      foreach (['M', 'F'] as $categoria) {
        if (!isset($datosJSON[$categoria])) continue;

        foreach (['P', 'C'] as $tipo) {
          if (!isset($datosJSON[$categoria][$tipo])) continue;

          foreach ($datosJSON[$categoria][$tipo] as $idPrueba => $alumnos) {
            // Primero, eliminamos registros anteriores
            if ($tipo === 'P') {
              // Borrar inscripciones anteriores en pruebas individuales
              $sqlDelete = "DELETE FROM Pruebas_olimpicas_alumnos WHERE idPrueba = :idPrueba";
              $stmt = $this->conexion->prepare($sqlDelete);
              $stmt->bindParam(':idPrueba', $idPrueba, PDO::PARAM_INT);
              $stmt->execute();

              // Insertar nuevas inscripciones
              $sqlInsert = "INSERT INTO Pruebas_olimpicas_alumnos (idAlumno, idPrueba) VALUES (:idAlumno, :idPrueba)";
              $stmt = $this->conexion->prepare($sqlInsert);
              foreach ($alumnos as $idAlumno) {
                $stmt->bindParam(':idAlumno', $idAlumno, PDO::PARAM_INT);
                $stmt->bindParam(':idPrueba', $idPrueba, PDO::PARAM_INT);
                $stmt->execute();
              }
            } elseif ($tipo === 'C') {
              // Borrar inscripciones anteriores en pruebas de 4x100
              $sqlDelete = "DELETE FROM 4x100_Alumnos WHERE idPrueba = :idPrueba";
              $stmt = $this->conexion->prepare($sqlDelete);
              $stmt->bindParam(':idPrueba', $idPrueba, PDO::PARAM_INT);
              $stmt->execute();

              // Insertar nuevas inscripciones
              // Se insertan en grupos de 4
              $sqlInsert = "INSERT INTO 4x100_Alumnos (idAlumno1, idAlumno2, idAlumno3, idAlumno4, idPrueba)
                                      VALUES (:id1, :id2, :id3, :id4, :idPrueba)";
              $stmt = $this->conexion->prepare($sqlInsert);

              // Validación: deben ser múltiplos de 4
              if (count($alumnos) % 4 !== 0) {
                throw new Exception("Número inválido de alumnos en la prueba 4x100 ID $idPrueba. Debe ser múltiplo de 4.");
              }

              for ($i = 0; $i < count($alumnos); $i += 4) {
                $stmt->bindParam(':id1', $alumnos[$i], PDO::PARAM_INT);
                $stmt->bindParam(':id2', $alumnos[$i + 1], PDO::PARAM_INT);
                $stmt->bindParam(':id3', $alumnos[$i + 2], PDO::PARAM_INT);
                $stmt->bindParam(':id4', $alumnos[$i + 3], PDO::PARAM_INT);
                $stmt->bindParam(':idPrueba', $idPrueba, PDO::PARAM_INT);
                $stmt->execute();
              }
            }
          }
        }
      }

      $this->conexion->commit(); // FIN TRANSACCIÓN EXITOSA
      return json_encode(["success" => "Inscripciones actualizadas correctamente."]);
    } catch (PDOException $e) {
      $this->conexion->rollBack(); // DESHACER SI HAY ERROR SQL
      error_log("Error SQL al actualizar inscripciones: " . $e->getMessage());
      return json_encode(["error" => "Error SQL al actualizar inscripciones."]);
    } catch (Exception $e) {
      $this->conexion->rollBack(); // DESHACER SI FALLA POR LÓGICA (ej. grupos no múltiplos de 4)
      error_log("Error lógico al actualizar inscripciones: " . $e->getMessage());
      return json_encode(["error" => $e->getMessage()]);
    }
  }
}
