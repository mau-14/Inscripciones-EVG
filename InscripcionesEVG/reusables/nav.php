<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/config/entorno/variables.php';
session_start();

if (!isset($_SESSION['usuario'])) {
  header("Location: /InscripcionesEVG/index.php?controlador=auth&accion=login");
  exit;
}
$usuarioTipo = $_SESSION['usuario'] ?? '';
?>



<!-- TODO SACAR ESTO A UN SCRIPT APARTE -->

<script type="module">
  import {
    ModalConfirmacion
  } from "/InscripcionesEVG/assets/js/utils/modalConfirmacion.js";

  function cerrarSesion() {
    new ModalConfirmacion({
      titulo: "Cerrar sesión",
      mensaje: "¿Seguro que quieres cerrar sesión?",
      onAceptar: () => {
        sessionStorage.clear();
        fetch('/InscripcionesEVG/index.php?controlador=auth&accion=logout', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'accion=logout'
          })
          .then(response => response.text())
          .then(() => {
            window.location.href = '/InscripcionesEVG/index.php?controlador=auth&accion=login';
          })
          .catch(err => {
            alert('Error al cerrar sesión.');
            console.error(err);
          });
      },
      onCancelar: () => {}
    });
  }

  window.addEventListener('DOMContentLoaded', () => {
    const btnCerrarSesion = document.getElementById('cerrar-sesion');
    if (btnCerrarSesion) {
      btnCerrarSesion.addEventListener('click', cerrarSesion);
    }
  });
</script>
<nav>
  <div class="navdiv">
    <ul class="main-menu">
      <div class="logo">
        <img src="/InscripcionesEVG/assets/img/logotipo.png" alt="Logo" />
      </div>

      <li><a href="<?= $inicioHref ?>">Inicio</a></li>

      <?php if ($usuarioTipo === 'Coordinador' || $usuarioTipo === 'Profesor' || $usuarioTipo === 'Tutor'): ?>
        <li class="has-submenu">
          <a href="<?= $momentoHref ?>">Momento</a>
          <ul class="submenu">
            <li><a href="<?= $consultaActividadesHref ?>">Consulta de Actividades</a></li>
            <?php if ($usuarioTipo === 'Coordinador' || $usuarioTipo === 'Tutor'): ?>
              <li><a href="<?= $inscripcionActividadesHref ?>">Inscripción Actividades</a></li>
            <?php endif; ?>
          </ul>
        </li>
      <?php endif; ?>

      <?php if ($usuarioTipo === 'Coordinador' || $usuarioTipo === 'Profesor' || $usuarioTipo === 'Tutor'): ?>
        <li class="has-submenu">
          <a href="<?= $torneoOlimpicoHref ?>">Torneo Olímpico</a>
          <ul class="submenu">
            <li><a href="<?= $consultaPruebasHref ?>">Consulta de Pruebas</a></li>

            <?php if ($usuarioTipo === 'Coordinador' || $usuarioTipo === 'Tutor'): ?>
              <li><a href="<?= $inscripcionTorneoHref ?>">Inscripción Torneo</a></li>
            <?php endif; ?>

            <?php if ($usuarioTipo === 'Coordinador'): ?>
              <li><a href="<?= $gestionPruebasHref ?>">Gestión de Pruebas</a></li>
            <?php endif; ?>
          </ul>
        </li>
      <?php endif; ?>

    </ul>

    <button id="cerrar-sesion"><i class="fas fa-user"></i> <?= htmlspecialchars($usuarioTipo) ?> - Cerrar sesión</button>
  </div>
</nav>
