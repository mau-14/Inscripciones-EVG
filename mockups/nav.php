<?php include 'entorno/variables.php' ?>
<?php
$usuarioTipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'Invitado';
?>
<nav>
  <div class="navdiv">
    <ul class="main-menu">
      <div class="logo">
        <img src="imagenes/logotipo.png" alt="Logo" />
      </div>
      <li><a href="<?= $inicioHref ?>">Inicio</a></li>
      <li class="has-submenu">
        <a href="<?= $momentoHref ?>">Momento</a>
        <ul class="submenu">
          <li><a href="<?= $consultaActividadesHref ?>">Consulta de Actividades</a></li>
          <li><a href="<?= $inscripcionActividadesHref ?>">Inscripción Actividades</a></li>
        </ul>
      </li>
      <li class="has-submenu">
        <a href="<?= $torneoOlimpicoHref ?>">Torneo Olímpico</a>
        <ul class="submenu">
          <li><a href="<?= $consultaPruebasHref ?>">Consulta de Pruebas</a></li>
          <li><a href="<?= $inscripcionTorneoHref ?>">Inscripción Torneo</a></li>
        </ul>
      </li>
    </ul>
    <button><i class="fas fa-user"></i> <?= $usuarioTipo ?></button>
  </div>
</nav>
