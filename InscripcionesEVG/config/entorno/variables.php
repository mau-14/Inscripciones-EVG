<?php



// PHP 

$root = $_SERVER['DOCUMENT_ROOT'];
$vistas = '/InscripcionesEVG/views/';
$controladores = '/InscripcionesEVG/controllers/';

$inicioHref = "/InscripcionesEVG/index.php";
$momentoHref = "#sec2";
$actividadesHref = "/InscripcionesEVG/index.php";
$consultaActividadesHref = "#sub1";
$gestionPruebasHref = $vistas . "gestionPruebasTO.php";
$inscripcionActividadesHref = "#sub1";
$torneoOlimpicoHref = $vistas . "torneoOlimpico.php?tipo=Profesorado";
$consultaPruebasHref = "#sub1";
$inscripcionTorneoHref = $vistas . "inscripcionesTO.php";
$MOMENTO_TORNEO_ID = 41;

// FOOTER

$parrafoFooter = 'COPYRIGHT © 2019 FUNDACIÓN LOYOLA. TODOS LOS DERECHOS RESERVADOS.';
//RUTAS

$reusables = $root . '/InscripcionesEVG/reusables';


$navBar = $reusables . '/nav.php';
$footer = $reusables . '/footer.php';
