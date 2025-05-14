<?php


class M_obtenerAlumnos
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


  public function obtenerAlumnos()
  {
    try {


      $idClase = 1;
      $query = "SELECT idAlumno, nombre, sexo 
                FROM Alumnos
                WHERE idClase = :idClase";

      $stmt = $this->conexion->prepare($query);

      $stmt->bindParam(':idClase', $idClase, PDO::PARAM_INT);

      $stmt->execute();

      $pruebas = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $pruebasJson = json_encode($pruebas);

      return $pruebasJson;
    } catch (PDOException $e) {
      error_log("Error al obtener los alumnos: " . $e->getMessage());
      return json_encode(["error" => "Ocurrió un error al procesar la solicitud de obtener alumnos."]);
    }
  }
}
