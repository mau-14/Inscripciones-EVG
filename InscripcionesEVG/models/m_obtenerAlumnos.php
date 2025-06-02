<?php


class M_obtenerAlumnos
{


  private $conexion;
  private $id;


  public function __construct($id)
  {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/config/configDB.php';

    $this->id = $id;
    try {
      DSN . SERVIDOR . ';dbname=' . BBDD . ';charset=utf8';
      $this->conexion = new PDO(DSN, USUARIO, PASSWORD);

      // Hace que lance los errores si se producen y así pillarlos con el try/catch
      $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {

      die('Error de conexión: ' . $e->getMessage());
    }
  }


  public function obtenerAlumnos()
  {
    try {
      error_log($this->id['idClase']);

      $query = "SELECT idAlumno, nombre, sexo 
                FROM Alumnos
                WHERE idClase = :idClase";

      $stmt = $this->conexion->prepare($query);

      $stmt->bindParam(':idClase', $this->id['idClase'], PDO::PARAM_INT);

      $stmt->execute();

      $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $alumnosJson = json_encode($alumnos);

      return $alumnosJson;
    } catch (PDOException $e) {
      error_log("Error al obtener los alumnos: " . $e->getMessage());
      return json_encode(["error" => "Ocurrió un error al procesar la solicitud de obtener alumnos."]);
    }
  }


  public function obtenerInscripcionesAlumnosTO()
  {

    try {

      $query = "
              SELECT 
                  a.idAlumno,
                  a.nombre AS nombreAlumno,
                  a.sexo,
                  c.nombre AS nombreClase,
                  e.nombreEtapa,
                  t.nombre AS nombrePrueba,
                  t.categoria
              FROM 
                  Alumnos a
              JOIN 
                  Clases c ON a.idClase = c.idClase
              JOIN 
                  Etapas e ON c.idEtapa = e.idEtapa
              JOIN 
                  (
                      SELECT idPrueba, nombre, categoria FROM Torneo_Olimpico
                      WHERE idPrueba = :idPruebaM
                  ) t ON 1=1
              LEFT JOIN
                  Pruebas_olimpicas_alumnos poa ON poa.idAlumno = a.idAlumno AND poa.idPrueba = t.idPrueba
              LEFT JOIN
                  4x100_Alumnos c4x ON (a.idAlumno IN (c4x.idAlumno1, c4x.idAlumno2, c4x.idAlumno3, c4x.idAlumno4) AND c4x.idPrueba = t.idPrueba)
              WHERE
                  (poa.idPrueba IS NOT NULL OR c4x.idPrueba IS NOT NULL)
              
              UNION ALL
              
              SELECT 
                  a.idAlumno,
                  a.nombre AS nombreAlumno,
                  a.sexo,
                  c.nombre AS nombreClase,
                  e.nombreEtapa,
                  t.nombre AS nombrePrueba,
                  t.categoria
              FROM 
                  Alumnos a
              JOIN 
                  Clases c ON a.idClase = c.idClase
              JOIN 
                  Etapas e ON c.idEtapa = e.idEtapa
              JOIN 
                  (
                      SELECT idPrueba, nombre, categoria FROM Torneo_Olimpico
                      WHERE idPrueba = :idPruebaF
                  ) t ON 1=1
              LEFT JOIN
                  Pruebas_olimpicas_alumnos poa ON poa.idAlumno = a.idAlumno AND poa.idPrueba = t.idPrueba
              LEFT JOIN
                  4x100_Alumnos c4x ON (a.idAlumno IN (c4x.idAlumno1, c4x.idAlumno2, c4x.idAlumno3, c4x.idAlumno4) AND c4x.idPrueba = t.idPrueba)
              WHERE
                  (poa.idPrueba IS NOT NULL OR c4x.idPrueba IS NOT NULL)
              
              ORDER BY
                  nombreEtapa,
                  sexo,
                  nombreAlumno;
              ";

      $stmt = $this->conexion->prepare($query);

      $stmt->bindParam(':idPruebaM', $this->id['idPruebaM'], PDO::PARAM_INT);
      $stmt->bindParam(':idPruebaF', $this->id['idPruebaF'], PDO::PARAM_INT);


      $stmt->execute();

      $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $alumnosJson = json_encode($alumnos);

      return $alumnosJson;
    } catch (PDOException $e) {
      error_log("Error al obtener los alumnos y sus inscripciones: " . $e->getMessage());
      return json_encode(["error" => "Ocurrió un error al procesar la solicitud de obtener alumnos y sus inscripciones."]);
    }
  }
}
