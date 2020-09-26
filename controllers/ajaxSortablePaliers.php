<?php

require_once '../config/config.php';
require_once '../models/AdminGestionMgr.class.php';


if (!isset($_GET['listItem'])) exit;
$listItem = $_GET['listItem'];
if (!is_array($listItem)) exit;


// Extraction de la valeur de la table passée en paramètre.
$lastItem = array_pop($listItem);
list($id, $table) = explode(';', $lastItem);
// depuis PHP 7.1 : [$id, $db] = explode(';', $lastItem);
$listItem[] = $id;

if (AdminGestionMgr::reorderLevels($listItem, $table)) {
	echo '<p>Élements ré-ordonnés</p>';
}
else {
	echo '<p>Une erreur est survenue</p>';
}
echo '<script>$(function(){$("#info").fadeIn(1000).delay(2000).fadeOut("slow");});</script>';

exit;
