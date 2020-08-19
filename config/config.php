<?php

session_start();

// Gestion d'affichage des erreurs PHP.
error_reporting(E_ALL);
if ('prod' == $settings['environment']) {
	ini_set('display_errors', 'off');
}


ini_set('upload_max_filesize', '100M');
ini_set('default_charset', 'utf-8');
ini_set('memory_limit', '64M');

header('Content-Type: text/html; charset=utf-8');
header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');


// Constantes.
define ('_ROOT_DIR_', __dir__ . '/..');


// Ajout de CSS / JS à la volée.
$css = NULL;
$javascript = NULL;

