<?php


class M_obtenerActividades
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


  public function obtenerActividades()
  {
    try {
      $query = "SELECT *
          FROM Actividades";

      $stmt = $this->conexion->prepare($query);

      $stmt->execute();

      $actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $actividadesJson = json_encode($actividades);

      return $actividadesJson;
    } catch (PDOException $e) {
      error_log("Error al obtener las actividades: " . $e->getMessage());
      return json_encode(["error" => "Ocurrió un error al procesar la solicitud."]);
    }
  }

  public function obtenerInscripcionesAlumnosActividad($idActividad)
  {

    try {

      $query = "
             SELECT 
        a.idAlumno,
        a.nombre AS nombreAlumno,
        a.sexo,
        c.nombre AS nombreClase,
        e.nombreEtapa,
        act.nombre AS nombreActividad
    FROM 
        Alumnos a
    JOIN 
        Clases c ON a.idClase = c.idClase
    JOIN 
        Etapas e ON c.idEtapa = e.idEtapa
    JOIN 
        Participan p ON p.idAlumno = a.idAlumno
    JOIN 
        Actividades act ON act.idActividad = p.idActividad
    WHERE
        p.idActividad = :idActividad
    ORDER BY
        e.nombreEtapa,
        a.sexo,
      a.nombre;              
      ";

      $stmt = $this->conexion->prepare($query);

      $stmt->bindParam(':idActividad', $idActividad['idActividad'], PDO::PARAM_INT);


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
