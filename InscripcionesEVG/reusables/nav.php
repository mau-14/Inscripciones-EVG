<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/config/entorno/variables.php';
session_start();

if (!isset($_SESSION['usuario'])) {
  header("Location: /InscripcionesEVG/index.php?controlador=auth&accion=login");
  exit;
}
$usuarioTipo = $_SESSION['usuario'] ?? '';
$momentoActual = $_SESSION['momento_actual'] ?? null;
?>

<script type="module" src="/InscripcionesEVG/assets/js/controllers/c_controlarFecha.js">
</script>
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

      <!-- Consulta (Todos los usuarios) -->
      <li class="has-submenu">
        <a href="/InscripcionesEVG/index.php">Listados</a>
        <!-- <ul class="submenu"> -->
        <!--   <li><a href="<?= $consultaPruebasHref ?>">Consulta Torneo Olímpico</a></li> -->
        <!--   <li><a href="<?= $consultaActividadesHref ?>">Consulta de Actividades</a></li> -->
        <!-- </ul> -->
      </li>

      <!-- Inscripciones (Coordinador y Tutor) -->
      <?php if ($usuarioTipo === 'Coordinador' || $usuarioTipo === 'Tutor'): ?>
        <li class="has-submenu">
          <a href="/InscripcionesEVG/views/menuInscripciones.php">Inscripciones</a>
          <!-- <ul class="submenu"> -->
          <!--   <li><a href="<?= $inscripcionTorneoHref ?>">Inscripción a Torneo Olímpico</a></li> -->
          <!--   <li><a href="<?= $inscripcionActividadesHref ?>">Inscripción a Actividades</a></li> -->
          <!--   <li><a href="<?= $inscripcionClaseHref ?>">Inscripción a Actividades de Clase</a></li> -->
          <!-- </ul> -->
        </li>
      <?php endif; ?>

      <!-- Gestión (Solo Coordinador) -->
      <?php if ($usuarioTipo === 'Coordinador'): ?>
        <li class="has-submenu">
          <a href="/InscripcionesEVG/views/menuGestiones.php">Gestión</a>
          <!-- <ul class="submenu"> -->
          <!--   <li><a href="<?= $gestionPruebasHref ?>">Gestión Torneo Olímpico</a></li> -->
          <!--   <li><a href="<?= $gestionActividadesHref ?>">Gestión de Actividades</a></li> -->
          <!--   <li><a href="<?= $gestionMomentosHref ?>">Gestión de Momentos</a></li> -->
          <!-- </ul> -->
        </li>
      <?php endif; ?>
    </ul>

    <button id="cerrar-sesion">
      <i class="fas fa-user"></i>
      <?= htmlspecialchars($usuarioTipo) ?> - Cerrar sesión
    </button>
  </div>
</nav>
