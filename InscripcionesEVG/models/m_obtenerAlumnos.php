<?php


class M_obtenerAlumnos
{


  private $conexion;
  private $idClase;


  public function __construct($idClase)
  {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/config/configDB.php';

    $this->idClase = $idClase;
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
      error_log($this->idClase['idClase']);

      $query = "SELECT idAlumno, nombre, sexo 
                FROM Alumnos
                WHERE idClase = :idClase";

      $stmt = $this->conexion->prepare($query);

      $stmt->bindParam(':idClase', $this->idClase['idClase'], PDO::PARAM_INT);

      $stmt->execute();

      $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $alumnosJson = json_encode($alumnos);

      return $alumnosJson;
    } catch (PDOException $e) {
      error_log("Error al obtener los alumnos: " . $e->getMessage());
      return json_encode(["error" => "Ocurrió un error al procesar la solicitud de obtener alumnos."]);
    }
  }
}
