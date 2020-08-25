<?php

require_once("dao/DbConnection.class.php");

$sth = DbConnection::getConnection('administrateur')->query("SELECT palier, prix FROM paliers_spiplast");
$sth->execute();
$result = $sth->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result);
