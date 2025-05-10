<?php



// PHP 

$root = $_SERVER['DOCUMENT_ROOT'];
$vistas = '/Torneo_Olimpico/app/views/';
$controladores = '/Torneo_Olimpico/app/controllers/';

$inicioHref = "index.php";
$momentoHref = "#sec2";
$consultaActividadesHref = "#sub1";
$gestionPruebasHref = $vistas . "gestionPruebasTO.php";
$inscripcionActividadesHref = "#sub1";
$torneoOlimpicoHref = $vistas . "torneoOlimpico.php?tipo=Profesorado";
$consultaPruebasHref = "#sub1";
$inscripcionTorneoHref = $vistas . "inscripcionesTO.php?tipo=Tutor";


// FOOTER

$parrafoFooter = 'COPYRIGHT © 2019 FUNDACIÓN LOYOLA. TODOS LOS DERECHOS RESERVADOS.';
//RUTAS

$reusables = $root . '/Torneo_Olimpico/app/reusables';


$navBar = $reusables . '/nav.php';
$footer = $reusables . '/footer.php';
