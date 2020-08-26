<?php

require_once("dao/DbConnection.class.php");

$result = [];
$db = DbConnection::getConnection('administrateur');

// getDataPaliersNB
$sth = $db->query("SELECT palier, prix FROM paliers_nb");
$sth->execute();
$result['paliersNB'] = $sth->fetchAll(PDO::FETCH_ASSOC);

// getDataPaliersC
$sth = $db->query("SELECT palier, prix FROM paliers_couleur");
$sth->execute();
$result['paliersC'] = $sth->fetchAll(PDO::FETCH_ASSOC);

// getpaliersFC
$sth = $db->query("SELECT sKey, sValue FROM key_value WHERE sKey='prixFC'");
$sth->execute();
$result['paliersFC'] = $sth->fetchAll(PDO::FETCH_ASSOC);

// getpaliersFT
$sth = $db->query("SELECT sKey, sValue FROM key_value WHERE sKey='prixFT'");
$sth->execute();
$result['paliersFT'] = $sth->fetchAll(PDO::FETCH_ASSOC);

// getmaxSpiplast
$sth = $db->query("SELECT sKey, sValue FROM key_value WHERE sKey='maxFeuillesPlast'");
$sth->execute();
$result['maxSpiplast'] = $sth->fetchAll(PDO::FETCH_ASSOC);

// getmaxSpimetal
$sth = $db->query("SELECT sKey, sValue FROM key_value WHERE sKey='maxFeuillesMetal'");
$sth->execute();
$result['maxSpimetal'] = $sth->fetchAll(PDO::FETCH_ASSOC);

// getmaxThermo
$sth = $db->query("SELECT sKey, sValue FROM key_value WHERE sKey='maxFeuillesThermo'");
$sth->execute();
$result['maxThermo'] = $sth->fetchAll(PDO::FETCH_ASSOC);

// getpaliersSpiplast
$sth = $db->query("SELECT palier, prix FROM paliers_spiplast");
$sth->execute();
$result['paliersSpiplast'] = $sth->fetchAll(PDO::FETCH_ASSOC);

// getpaliersSpimetal
$sth = $db->query("SELECT palier, prix FROM paliers_spimetal");
$sth->execute();
$result['paliersSpimetal'] = $sth->fetchAll(PDO::FETCH_ASSOC);

// getpaliersThermo
$sth = $db->query("SELECT palier, prix FROM paliers_thermo");
$sth->execute();
$result['paliersThermo'] = $sth->fetchAll(PDO::FETCH_ASSOC);

// get TVA
$sth = $db->query("SELECT sValue FROM key_value WHERE sKey = 'tvaDossier'");
$sth->execute();
$result['tvaDossier'] = $sth->fetch(PDO::FETCH_ASSOC)['sValue'];

// get TVA
$sth = $db->query("SELECT sValue FROM key_value WHERE sKey = 'tvaMemoire'");
$sth->execute();
$result['tvaMemoire'] = $sth->fetch(PDO::FETCH_ASSOC)['sValue'];

// get TVA
$sth = $db->query("SELECT sValue FROM key_value WHERE sKey = 'tvaThese'");
$sth->execute();
$result['tvaThese'] = $sth->fetch(PDO::FETCH_ASSOC)['sValue'];

// get TVA
$sth = $db->query("SELECT sValue FROM key_value WHERE sKey = 'tvaPerso'");
$sth->execute();
$result['tvaPerso'] = $sth->fetch(PDO::FETCH_ASSOC)['sValue'];

echo json_encode($result);
