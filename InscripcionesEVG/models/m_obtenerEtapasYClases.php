<?php

class M_obtenerEtapasYClases
{
  private $conexion;

  public function __construct()
  {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/config/configDB.php';

    try {
      $this->conexion = new PDO(DSN, USUARIO, PASSWORD);
      $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      die('Error de conexión: ' . $e->getMessage());
    }
  }

  public function obtenerEtapasYClases()
  {
    try {
      // Traigo todas las etapas
      $queryEtapas = "SELECT idEtapa, nombreEtapa FROM Etapas";
      $stmtEtapas = $this->conexion->prepare($queryEtapas);
      $stmtEtapas->execute();
      $etapas = $stmtEtapas->fetchAll(PDO::FETCH_ASSOC);

      // Por cada etapa busco sus clases
      foreach ($etapas as &$etapa) {
        $queryClases = "SELECT idClase, nombre FROM Clases WHERE idEtapa = :idEtapa";
        $stmtClases = $this->conexion->prepare($queryClases);
        $stmtClases->execute([':idEtapa' => $etapa['idEtapa']]);
        $clases = $stmtClases->fetchAll(PDO::FETCH_ASSOC);

        // Añadimos las clases a la etapa
        $etapa['clases'] = $clases;
      }
      unset($etapa); // romper la referencia
      $etapas = json_encode($etapas, JSON_UNESCAPED_UNICODE);

      return $etapas;
    } catch (PDOException $e) {
      error_log("Error al obtener las etapas y clases: " . $e->getMessage());
      return ["error" => "Ocurrió un error al procesar la solicitud."];
    }
  }
}
