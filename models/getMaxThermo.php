<?php

require_once("dao/DbConnection.class.php");

$sth = DbConnection::getConnection('administrateur')->query("SELECT sKey, sValue FROM key_value WHERE sKey='maxFeuillesThermo'");
$sth->execute();

/* Fetch all of the remaining rows in the result variable */
$result = $sth->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result);