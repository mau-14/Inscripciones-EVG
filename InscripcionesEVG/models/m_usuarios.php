<?php
class M_usuarios
{
  // Aquí la lógica de consulta, o puede ser fija para pruebas
  public function validarUsuario($usuario, $pass)
  {
    // Ejemplo simple:
    $usuarios = [
      'Coordinador' => 'isa',
      'Profesor' => '1234',
      'Tutor' => '1234'
    ];

    return isset($usuarios[$usuario]) && $usuarios[$usuario] === $pass;
  }
}
