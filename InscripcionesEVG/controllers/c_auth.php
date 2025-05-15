<?php
session_start();

class C_auth
{
  private $userModel;

  public function __construct()
  {
    require_once 'models/m_usuarios.php'; // Modelo con usuarios (puedes crear)
    $this->userModel = new M_usuarios();
  }

  public function login()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $usuario = $_POST['usuario'] ?? '';
      $pass = $_POST['password'] ?? '';

      if ($this->userModel->validarUsuario($usuario, $pass)) {
        $_SESSION['usuario'] = $usuario;
        header("Location: index.php");
        exit;
      } else {
        $error = "Usuario o contraseña incorrectos";
      }
    }

    include 'views/login.php'; // formulario login simple
  }

  public function logout()
  {
    session_destroy();
    header("Location: index.php?controlador=auth&accion=login");
    exit;
  }
}
