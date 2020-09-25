<?php

require_once("dao/DbConnection.class.php");

$result = [];
$db = DbConnection::getConnection('administrateur');

// Paliers de prix des Feuilles Cartonnées / Feuillets Transparents
// Nb max de feuilles pour spirale plastique / métallique / thermocollée
// TVA pour les dossiers / mémoires / thèses / documents personnalisés
$sth = $db->query("
SELECT sKey, sValue
FROM key_value
WHERE sKey IN('prixFC', 'prixFT', 'maxFeuillesPlast', 'maxFeuillesMetal', 'maxFeuillesThermo', 'tvaDossier', 'tvaMemoire', 'tvaThese', 'tvaPerso')");
$sth->execute();
$res = $sth->fetchAll(PDO::FETCH_ASSOC);
$result = array_combine(array_column($res, 'sKey'), array_column($res, 'sValue'));

// Paliers de prix des pages N&B
$sth = $db->query("SELECT palier, prix FROM paliers_nb");
$sth->execute();
$result['paliersNB'] = $sth->fetchAll(PDO::FETCH_ASSOC);

// Paliers de prix des pages Couleur
$sth = $db->query("SELECT palier, prix FROM paliers_couleur");
$sth->execute();
$result['paliersC'] = $sth->fetchAll(PDO::FETCH_ASSOC);

// Paliers de prix des spirales plastiques
$sth = $db->query("SELECT palier, prix FROM paliers_spiplast");
$sth->execute();
$result['paliersSpiplast'] = $sth->fetchAll(PDO::FETCH_ASSOC);

// Paliers de prix des spirales metalliques
$sth = $db->query("SELECT palier, prix FROM paliers_spimetal");
$sth->execute();
$result['paliersSpimetal'] = $sth->fetchAll(PDO::FETCH_ASSOC);

// Paliers de prix des reliures thermocollées
$sth = $db->query("SELECT palier, prix FROM paliers_thermo");
$sth->execute();
$result['paliersThermo'] = $sth->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result);
