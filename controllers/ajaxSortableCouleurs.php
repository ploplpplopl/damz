<?php

require_once '../config/config.php';
require_once '../models/AdminGestionMgr.class.php';


if (!isset($_GET['listItem'])) exit;
$listItem = $_GET['listItem'];
if (!is_array($listItem)) exit;

if (AdminGestionMgr::reorderLevels($listItem, 'dossier_color', 'id_dossier_color')) {
	echo '<p>Élements ré-ordonnés</p>';
}
else {
	echo '<p>Une erreur est survenue</p>';
}
echo '<script>$(function(){$("#info").fadeIn(1000).delay(2000).fadeOut("slow");});</script>';

exit;
