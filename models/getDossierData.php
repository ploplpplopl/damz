<?php

require_once("dao/DbConnection.class.php");

$result = [];
$db = DbConnection::getConnection('administrateur');

// Paliers de prix des pages N&B
$sth = $db->query("SELECT palier, prix FROM paliers_nb");
$sth->execute();
$result['paliersNB'] = $sth->fetchAll(PDO::FETCH_ASSOC);

// Paliers de prix des pages Couleur
$sth = $db->query("SELECT palier, prix FROM paliers_couleur");
$sth->execute();
$result['paliersC'] = $sth->fetchAll(PDO::FETCH_ASSOC);

// Paliers de prix des Feuilles Cartonnées
$sth = $db->query("SELECT sKey, sValue FROM key_value WHERE sKey='prixFC'");
$sth->execute();
$result['paliersFC'] = $sth->fetchAll(PDO::FETCH_ASSOC);

// Paliers de prix des Feuillets Transparents
$sth = $db->query("SELECT sKey, sValue FROM key_value WHERE sKey='prixFT'");
$sth->execute();
$result['paliersFT'] = $sth->fetchAll(PDO::FETCH_ASSOC);

// Nb max de feuilles pour spirale plastique
$sth = $db->query("SELECT sKey, sValue FROM key_value WHERE sKey='maxFeuillesPlast'");
$sth->execute();
$result['maxSpiplast'] = $sth->fetchAll(PDO::FETCH_ASSOC);

// Nb max de feuilles pour spirale métallique
$sth = $db->query("SELECT sKey, sValue FROM key_value WHERE sKey='maxFeuillesMetal'");
$sth->execute();
$result['maxSpimetal'] = $sth->fetchAll(PDO::FETCH_ASSOC);

// Nb max de feuilles pour reliure thermocollée
$sth = $db->query("SELECT sKey, sValue FROM key_value WHERE sKey='maxFeuillesThermo'");
$sth->execute();
$result['maxThermo'] = $sth->fetchAll(PDO::FETCH_ASSOC);

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

// TVA pour les dossiers
$sth = $db->query("SELECT sValue FROM key_value WHERE sKey = 'tvaDossier'");
$sth->execute();
$result['tvaDossier'] = $sth->fetch(PDO::FETCH_ASSOC)['sValue'];

// TVA pour les mémoires
$sth = $db->query("SELECT sValue FROM key_value WHERE sKey = 'tvaMemoire'");
$sth->execute();
$result['tvaMemoire'] = $sth->fetch(PDO::FETCH_ASSOC)['sValue'];

// TVA pour les thèses
$sth = $db->query("SELECT sValue FROM key_value WHERE sKey = 'tvaThese'");
$sth->execute();
$result['tvaThese'] = $sth->fetch(PDO::FETCH_ASSOC)['sValue'];

// TVA pour les documents personnalisés
$sth = $db->query("SELECT sValue FROM key_value WHERE sKey = 'tvaPerso'");
$sth->execute();
$result['tvaPerso'] = $sth->fetch(PDO::FETCH_ASSOC)['sValue'];

echo json_encode($result);
