<?php


class M_alumnosSeleccionados
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


  public function comprobar($idClase)
  {
    try {
      $this->conexion->beginTransaction();
      // ¿Hay alumnos de esta clase en pruebas individuales?
      $sql_individuales = "SELECT 1
                       FROM Pruebas_olimpicas_alumnos poa
                       JOIN Alumnos a ON poa.idAlumno = a.idAlumno
                       WHERE a.idClase = :idClase
                       LIMIT 1";
      $stmt_individuales = $this->conexion->prepare($sql_individuales);
      $stmt_individuales->bindParam(':idClase', $idClase['idClase']);
      $stmt_individuales->execute();
      $hay_individuales = $stmt_individuales->fetch() !== false;

      // ¿Hay alumnos de esta clase en 4x100?
      $sql_4x100 = "SELECT 1
                FROM 4x100_Alumnos rel
                JOIN Alumnos a ON 
                    rel.idAlumno1 = a.idAlumno OR
                    rel.idAlumno2 = a.idAlumno OR
                    rel.idAlumno3 = a.idAlumno OR
                    rel.idAlumno4 = a.idAlumno
                WHERE a.idClase = :idClase
                LIMIT 1";
      $stmt_4x100 = $this->conexion->prepare($sql_4x100);
      $stmt_4x100->bindParam(':idClase', $idClase);
      $stmt_4x100->execute();
      $hay_4x100 = $stmt_4x100->fetch() !== false;

      $this->conexion->commit();

      error_log("Individuales: " . ($hay_individuales ? 'true' : 'false') . " - 4x100: " . ($hay_4x100 ? 'true' : 'false'));

      if ($hay_individuales || $hay_4x100) {
        return json_encode(["success" => true, "mensaje" => "La clase tiene inscripciones."]);
      } else {
        return json_encode(["success" => false, "mensaje" => "La clase no tiene inscripciones."]);
      }
    } catch (PDOException $e) {
      $this->conexion->rollBack();
      error_log("Error al comprobar inscripciones: " . $e->getMessage());
      return json_encode(["error" => "Error al comprobar inscripciones."]);
    }
  }

  public function extraer($idClase)
  {
    try {
      $this->conexion->beginTransaction(); // INICIO TRANSACCIÓN

      $resultado = [
        'M' => ['P' => [], 'C' => []],
        'F' => ['P' => [], 'C' => []],
      ];

      $sql_individuales = "SELECT a.sexo, poa.idPrueba, a.idAlumno
                         FROM Pruebas_olimpicas_alumnos poa
                        JOIN Alumnos a ON poa.idAlumno = a.idAlumno
                        WHERE a.idClase = :idClase";

      $stmt_ind = $this->conexion->prepare($sql_individuales);
      $stmt_ind->bindParam(':idClase', $idClase['idClase']);
      $stmt_ind->execute();

      while ($row = $stmt_ind->fetch(PDO::FETCH_ASSOC)) {
        $sexo = $row['sexo'];
        $idPrueba = $row['idPrueba'];
        $idAlumno = $row['idAlumno'];

        if (!isset($resultado[$sexo]['P'][$idPrueba])) {
          $resultado[$sexo]['P'][$idPrueba] = [];
        }

        $resultado[$sexo]['P'][$idPrueba][] = (int)$idAlumno;
      }

      // Pruebas 4x100 (tipo C)
      $sql_4x100 = "SELECT r.idPrueba, a1.idAlumno AS a1, a1.sexo AS sexo
                  FROM 4x100_Alumnos r
                  JOIN Alumnos a1 ON r.idAlumno1 = a1.idAlumno
                  JOIN Alumnos a2 ON r.idAlumno2 = a2.idAlumno
                  JOIN Alumnos a3 ON r.idAlumno3 = a3.idAlumno
                  JOIN Alumnos a4 ON r.idAlumno4 = a4.idAlumno
                  WHERE a1.idClase = :idClase";

      $stmt_4x100 = $this->conexion->prepare($sql_4x100);
      $stmt_4x100->bindParam(':idClase', $idClase);
      $stmt_4x100->execute();

      while ($row = $stmt_4x100->fetch(PDO::FETCH_ASSOC)) {
        $sexo = $row['sexo'];
        $idPrueba = $row['idPrueba'];

        if (!isset($resultado[$sexo]['C'][$idPrueba])) {
          $resultado[$sexo]['C'][$idPrueba] = [];
        }

        $resultado[$sexo]['C'][$idPrueba][] = (int)$row['a1'];
        $resultado[$sexo]['C'][$idPrueba][] = (int)$row['a2'];
        $resultado[$sexo]['C'][$idPrueba][] = (int)$row['a3'];
        $resultado[$sexo]['C'][$idPrueba][] = (int)$row['a4'];
      }

      $this->conexion->commit(); // FIN TRANSACCIÓN EXITOSA

      return json_encode($resultado, JSON_PRETTY_PRINT); // bonito para leer
    } catch (PDOException $e) {
      $this->conexion->rollBack(); // DESHACER TODO SI FALLA
      error_log("Error al extraer pruebas: " . $e->getMessage());
      return json_encode(["error" => "Error al extraer pruebas."]);
    }
  }
}
