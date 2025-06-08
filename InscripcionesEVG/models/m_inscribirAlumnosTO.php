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

      $alumnosP = [];
      $alumnosC_por_prueba = [];

      // PRIMERA PASADA: recolectamos alumnos por tipo
      foreach (['M', 'F'] as $categoria) {
        if (!isset($datosJSON[$categoria])) continue;

        // Tipo P: individuales
        if (isset($datosJSON[$categoria]['P'])) {
          foreach ($datosJSON[$categoria]['P'] as $idPrueba => $alumnos) {
            foreach ($alumnos as $idAlumno) {
              $alumnosP[] = $idAlumno;
            }
          }
        }

        // Tipo C: relevos 4x100
        if (isset($datosJSON[$categoria]['C'])) {
          foreach ($datosJSON[$categoria]['C'] as $idPrueba => $alumnos) {
            if (!isset($alumnosC_por_prueba[$idPrueba])) {
              $alumnosC_por_prueba[$idPrueba] = [];
            }
            $alumnosC_por_prueba[$idPrueba] = array_merge($alumnosC_por_prueba[$idPrueba], $alumnos);
          }
        }
      }

      // ELIMINAMOS inscripciones anteriores en pruebas individuales
      if (!empty($alumnosP)) {
        $placeholders = implode(',', array_fill(0, count($alumnosP), '?'));
        $sqlDeletePrevias = "DELETE FROM Pruebas_olimpicas_alumnos WHERE idAlumno IN ($placeholders)";
        $stmt = $this->conexion->prepare($sqlDeletePrevias);
        $stmt->execute($alumnosP);
      }

      // ELIMINAMOS inscripciones anteriores en pruebas de relevos, por prueba y alumnos
      foreach ($alumnosC_por_prueba as $idPrueba => $alumnosC) {
        if (!empty($alumnosC)) {
          $placeholders = implode(',', array_fill(0, count($alumnosC), '?'));
          $sql = "DELETE FROM 4x100_Alumnos
                WHERE idPrueba = ?
                  AND (
                    idAlumno1 IN ($placeholders) OR
                    idAlumno2 IN ($placeholders) OR
                    idAlumno3 IN ($placeholders) OR
                    idAlumno4 IN ($placeholders)
                  )";
          $stmt = $this->conexion->prepare($sql);
          $params = array_merge([$idPrueba], $alumnosC, $alumnosC, $alumnosC, $alumnosC);
          $stmt->execute($params);
        }
      }

      // SEGUNDA PASADA: INSERTAMOS nuevos datos
      foreach (['M', 'F'] as $categoria) {
        if (!isset($datosJSON[$categoria])) continue;

        foreach (['P', 'C'] as $tipo) {
          if (!isset($datosJSON[$categoria][$tipo])) continue;

          foreach ($datosJSON[$categoria][$tipo] as $idPrueba => $alumnos) {

            if ($tipo === 'P') {
              $sqlInsert = "INSERT INTO Pruebas_olimpicas_alumnos (idAlumno, idPrueba) VALUES (:idAlumno, :idPrueba)";
              $stmt = $this->conexion->prepare($sqlInsert);
              foreach ($alumnos as $idAlumno) {
                $stmt->bindValue(':idAlumno', $idAlumno, PDO::PARAM_INT);
                $stmt->bindValue(':idPrueba', $idPrueba, PDO::PARAM_INT);
                $stmt->execute();
              }
            } elseif ($tipo === 'C') {
              if (count($alumnos) % 4 !== 0) {
                throw new Exception("Número inválido de alumnos en la prueba 4x100 ID $idPrueba. Debe ser múltiplo de 4.");
              }

              $sqlInsert = "INSERT INTO 4x100_Alumnos (idAlumno1, idAlumno2, idAlumno3, idAlumno4, idPrueba)
                          VALUES (:id1, :id2, :id3, :id4, :idPrueba)";
              $stmt = $this->conexion->prepare($sqlInsert);

              for ($i = 0; $i < count($alumnos); $i += 4) {
                $stmt->bindValue(':id1', $alumnos[$i], PDO::PARAM_INT);
                $stmt->bindValue(':id2', $alumnos[$i + 1], PDO::PARAM_INT);
                $stmt->bindValue(':id3', $alumnos[$i + 2], PDO::PARAM_INT);
                $stmt->bindValue(':id4', $alumnos[$i + 3], PDO::PARAM_INT);
                $stmt->bindValue(':idPrueba', $idPrueba, PDO::PARAM_INT);
                $stmt->execute();
              }
            }
          }
        }
      }

      $this->conexion->commit(); // TODO CORRECTO
      return json_encode(["success" => "Inscripciones actualizadas correctamente."]);
    } catch (PDOException $e) {
      $this->conexion->rollBack();
      error_log("Error SQL al actualizar inscripciones: " . $e->getMessage());
      return json_encode(["error" => "Error SQL al actualizar inscripciones."]);
    } catch (Exception $e) {
      $this->conexion->rollBack();
      error_log("Error lógico al actualizar inscripciones: " . $e->getMessage());
      return json_encode(["error" => $e->getMessage()]);
    }
  }
}
