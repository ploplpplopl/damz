<?php

require_once _ROOT_DIR_ . '/models/dao/DbConnection.class.php';

/**
 * Récupération des couleurs des cartons imprimables / non imprimables.
 *
 * @param bool $printable FALSE pour exclure les couleurs imprimables. Default TRUE.
 * @param bool $unprintable FALSE pour exclure les couleurs non imprimables. Default TRUE.
 * @return array Le jeu de résultats.
 */
function getDossierColors(bool $printable = TRUE, bool $unprintable = TRUE) {
	if (!$printable && !$unprintable) {
		$query = 'SELECT * FROM dossier_color';
	}
	$query = 'SELECT * FROM dossier_color WHERE printable = 1 ORDER BY position DESC';
	$sth = DbConnection::getConnection('administrateur')->query($query);
	$sth->execute();
	return $sth->fetchAll(PDO::FETCH_ASSOC);
}
// ↑↑ test foireux, à revoir.


function getAllColors() {
	$query = 'SELECT * FROM dossier_color ORDER BY position DESC';
	$sth = DbConnection::getConnection('administrateur')->query($query);
	$sth->execute();
	return $sth->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupération des couleurs des cartons imprimables
 *
 * @return array Le jeu de résultats.
 */
function getPrintableColors() {
	$query = 'SELECT * FROM dossier_color WHERE printable=1 ORDER BY position DESC';
	$sth = DbConnection::getConnection('administrateur')->query($query);
	$sth->execute();
	return $sth->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupération des couleurs des cartons NON imprimables
 *
 * @return array Le jeu de résultats.
 */
function getUnprintableColors() {
	$query = 'SELECT * FROM dossier_color WHERE unprintable=1 ORDER BY position DESC';
	$sth = DbConnection::getConnection('administrateur')->query($query);
	$sth->execute();
	return $sth->fetchAll(PDO::FETCH_ASSOC);
}
