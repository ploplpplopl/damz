<?php
// TODO factoriser ce script et getDataPalierC.php : ce sont les memes. et en faire une methode?

require_once("dao/DbConnection.class.php");

$sth = $dbh->prepare("SELECT palier, prix FROM paliers_NB");
$sth->execute();

/* Fetch all of the remaining rows in the result variable */
$result = $sth->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result);
