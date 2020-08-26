<?php

require_once _ROOT_DIR_ . '/models/dao/DbConnection.class.php';

/**
 * Récupération des couleurs des cartons imprimables / non imprimables.
 *
 * @return array Le jeu de résultats.
 */
function getAllColors() {
	$query = 'SELECT * FROM dossier_color ORDER BY position DESC';
	$sth = DbConnection::getConnection('administrateur')->query($query);
	$sth->execute();
	return $sth->fetchAll(PDO::FETCH_ASSOC);
}
