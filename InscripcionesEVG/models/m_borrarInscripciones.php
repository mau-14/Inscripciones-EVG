<?php
class M_borrarInscripciones
{
  private $conexion;

  public function __construct()
  {
    require_once("config/configDB.php");
    $this->conexion = new mysqli(SERVIDOR, USUARIO, PASSWORD, BBDD);

    if ($this->conexion->connect_error) {
      die("Conexión fallida: " . $this->conexion->connect_error);
    }
  }

  public function borrarTodo4x100Alumnos()
  {
    $SQL = "DELETE FROM 4x100_ALUMNOS";
    return $this->conexion->query($SQL);
  }

  public function borrarTodoPruebasOlimpicasAlumnos()
  {
    $SQL = "DELETE FROM Pruebas_olimpicas_alumnos";
    return $this->conexion->query($SQL);
  }

  public function borrarTodoParticipan()
  {
    $SQL = "DELETE FROM Participan";
    return $this->conexion->query($SQL);
  }

  public function borrarTodoSeInscriben()
  {
    $SQL = "DELETE FROM Se_inscriben";
    return $this->conexion->query($SQL);
  }

  // Opcional: método para borrar todas las tablas a la vez
  public function borrarTodas()
  {
    $this->conexion->begin_transaction();

    try {
      $this->borrarTodo4x100Alumnos();
      $this->borrarTodoPruebasOlimpicasAlumnos();
      $this->borrarTodoParticipan();
      $this->borrarTodoSeInscriben();

      $this->conexion->commit();
      return true;
    } catch (Exception $e) {
      $this->conexion->rollback();
      return false;
    }
  }
}
