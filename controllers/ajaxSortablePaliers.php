<?php

require_once '../config/config.php';
require_once '../models/dao/DbConnection.class.php';


if (!isset($_GET['listItem'])) exit;
$listItem = $_GET['listItem'];
if (!is_array($listItem)) exit;

$cnt = count($listItem);
$db = '';

// Extraction de la valeur de la bdd passée en paramètre.
for ($i = 0; $i < $cnt; $i++) {
	if (strpos($_GET['listItem'][$i], ';')) {
		$arr = explode(';', $_GET['listItem'][$i]);
		$_GET['listItem'][$i] = $arr[0];
		$db = array_pop($arr);
	}
}

for ($i = 1, $j = $cnt; $i <= $cnt; $i++, $j--){
	DbConnection::getConnection('administrateur')->query('UPDATE ' . $db . ' SET `position` = \''.$j.'\' WHERE `id` = \''.$listItem[$i-1].'\' ;');
}
echo '<p>Élements ré-ordonnés</p><script>$(function(){$("#info").fadeIn(1000).delay(2000).fadeOut("slow");});</script>';

exit;
