<?php
// include("connexion.php");
// $sql = "SELECT * FROM paliers_c;";
// $paliers_max_c = mysqli_query($connexion, $sql) or die('Erreur SQL : <br />' . $sql);
// //Transforme le retour de mysqli_query en tableau
// while ($ligne = mysqli_fetch_array($paliers_max_c, MYSQLI_ASSOC)) {
//      $tab_paliers_C[] = $ligne;
// }
// //Renvoie le tableau, encodé en JSON, vers l'AJAX de page.js
// echo json_encode($tab_paliers_C);


require_once("dao/DbConnection.class.php");
$sth = DbConnection::getConnection('dev')->prepare("SELECT palier, prix FROM paliers_couleur");
$sth->execute();

/* Fetch all of the remaining rows in the result variable */
$result = $sth->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result);
