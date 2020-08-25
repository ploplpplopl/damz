<?php

require_once '../config/config.php';
require_once '../models/dao/DbConnection.class.php';

if (!isset($_GET['listItem'])) exit;
$listItem = $_GET['listItem'];
if (!is_array($listItem)) exit;

$cnt = count($listItem);

for ($i = 1, $j = $cnt; $i <= $cnt; $i++, $j--){
	DbConnection::getConnection('administrateur')->query('UPDATE `paliers_couleur` SET `position` = \''.$j.'\' WHERE `id` = \''.$listItem[$i-1].'\' ;');
}
echo '<p>Élements ré-ordonnés</p><script>$(function(){$("#info").fadeIn(1000).delay(2000).fadeOut("slow");});</script>';

exit;
