<?php

require_once _ROOT_DIR_ . '/models/dao/DbConnection.class.php';

/**
 * Récupération des couleurs correspondant à 1 ou tous les types de document.
 *
 * @param array $type Le type de doc : Dossier|Mémoire|Thèse
 * @return array Le jeu de résultats.
 */
function getDossierColors($type = NULL) {
	$query = 'SELECT * FROM dossier_color';
	if (!empty($type)) {
		$query .= ' WHERE type LIKE \'%' . $type . '%\'';
	}
	$query .= ' ORDER BY text ASC';
	$sth = DbConnection::getConnection('administrateur')->query($query);
	$sth->execute();
	return $sth->fetchAll(PDO::FETCH_ASSOC);
}
