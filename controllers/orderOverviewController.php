<?php

require_once _ROOT_DIR_ . '/models/dao/DbConnection.class.php';

/**
 * Récupération des couleurs des cartons imprimables / non imprimables.
 *
 * @return array Un tableau dont les entrées sont : 'id' => 'couleur'.
 */
function getMappingColors() {
	$sth = DbConnection::getConnection('administrateur')->query('SELECT id_dossier_color, text FROM dossier_color');
	$sth->execute();
	$allColors = $sth->fetchAll(PDO::FETCH_ASSOC);
	$sth->closeCursor();
	DbConnection::disconnect();
	$mappingColors = [];
	foreach ($allColors as $color) {
		$mappingColors[$color['id_dossier_color']] = $color['text'];
	}
	return $mappingColors;
}

/**
 * Récupération des adresses de l'utilisateur.
 *
 * @return array
 */
function getUserAddresses() {
	$sth = DbConnection::getConnection('administrateur')->prepare('
		SELECT a.*, c.name AS country_name
		FROM address AS a
		INNER JOIN country AS c
		ON a.id_country = c.id_country
		WHERE a.id_user = :id_user
	');
	$sth->bindParam(':id_user', $_SESSION['user']['id_user'], PDO::PARAM_INT);
	$sth->execute();
	$addresses = $sth->fetchAll(PDO::FETCH_ASSOC);
	$sth->closeCursor();
	DbConnection::disconnect();
	return $addresses;
}

/**
 * Récupération du numéro de téléphone de l'utilisateur.
 *
 * @return string Le numéro de téléphone.
 */
function getUserPhone() {
	$sth = DbConnection::getConnection('administrateur')->prepare('
		SELECT phone
		FROM user
		WHERE id_user = :id_user
	');
	$sth->bindParam(':id_user', $_SESSION['user']['id_user'], PDO::PARAM_INT);
	$sth->execute();
	$phone = $sth->fetch(PDO::FETCH_ASSOC);
	$sth->closeCursor();
	DbConnection::disconnect();
	return $phone['phone'];
}

$address = '';
$payment = '';
$errors = [];

if (isset($_POST['order-btn'])) {
	$address = !empty($_POST['address']) ? $_POST['address'] : FALSE;
	$payment = !empty($_POST['payment']) ? $_POST['payment'] : FALSE;
	
	if (empty($address)) {
		$errors[] = 'Veuillez indiquer une adresse de livraison';
	}
	if (empty($payment)) {
		$errors[] = 'Veuillez indiquer un moyen de paiement';
	}
	
	if (empty($errors)) {
		// Enregistrement de la commande en BDD.
		$dbh = DbConnection::getConnection('administrateur');
		$stmt = $dbh->prepare('INSERT INTO orders (date_add, id_user, id_address, nom_fichier, nb_page, nb_page_nb, nb_page_c, doc_type, couv_ft, couv_fc, couv_fc_type, couv_fc_color, dos_ft, dos_fc, dos_fc_type, dos_fc_color, reliure_type, reliure_color, quantity, rectoverso, tva, total) VALUES (:date_add, :id_user, :id_address, :nom_fichier, :nb_page, :nb_page_nb, :nb_page_c, :doc_type, :couv_ft, :couv_fc, :couv_fc_type, :couv_fc_color, :dos_ft, :dos_fc, :dos_fc_type, :dos_fc_color, :reliure_type, :reliure_color, :quantity, :rectoverso, :tva, :total)');
		$date_add = date('Y-m-d H:i:s');
		$stmt->bindParam(':date_add', $date_add, PDO::PARAM_STR);
		$stmt->bindParam(':id_user', $_SESSION['user']['id_user'], PDO::PARAM_INT);
		$stmt->bindParam(':id_address', $address, PDO::PARAM_INT);
		$stmt->bindParam(':nom_fichier', $_SESSION['file_to_print']['nom_fichier'], PDO::PARAM_STR);
		$stmt->bindParam(':nb_page', $_SESSION['file_to_print']['nb_page'], PDO::PARAM_INT);
		$stmt->bindParam(':nb_page_nb', $_SESSION['file_to_print']['nb_page_nb'], PDO::PARAM_INT);
		$stmt->bindParam(':nb_page_c', $_SESSION['file_to_print']['nb_page_c'], PDO::PARAM_INT);
		$stmt->bindParam(':doc_type', $_SESSION['file_to_print']['doc_type'], PDO::PARAM_STR);
		$stmt->bindParam(':couv_ft', $_SESSION['file_to_print']['couv_ft'], PDO::PARAM_INT);
		$stmt->bindParam(':couv_fc', $_SESSION['file_to_print']['couv_fc'], PDO::PARAM_INT);
		$stmt->bindParam(':couv_fc_type', $_SESSION['file_to_print']['couv_fc_type'], PDO::PARAM_STR);
		$stmt->bindParam(':couv_fc_color', $_SESSION['file_to_print']['couv_fc_color'], PDO::PARAM_INT);
		$stmt->bindParam(':dos_ft', $_SESSION['file_to_print']['dos_ft'], PDO::PARAM_INT);
		$stmt->bindParam(':dos_fc', $_SESSION['file_to_print']['dos_fc'], PDO::PARAM_INT);
		$stmt->bindParam(':dos_fc_type', $_SESSION['file_to_print']['dos_fc_type'], PDO::PARAM_STR);
		$stmt->bindParam(':dos_fc_color', $_SESSION['file_to_print']['dos_fc_color'], PDO::PARAM_INT);
		$stmt->bindParam(':reliure_type', $_SESSION['file_to_print']['reliure_type'], PDO::PARAM_STR);
		$stmt->bindParam(':reliure_color', $_SESSION['file_to_print']['reliure_color'], PDO::PARAM_STR);
		$stmt->bindParam(':quantity', $_SESSION['file_to_print']['quantity'], PDO::PARAM_INT);
		$stmt->bindParam(':rectoverso', $_SESSION['file_to_print']['rectoverso'], PDO::PARAM_INT);
		$stmt->bindParam(':tva', $_SESSION['file_to_print']['tva'], PDO::PARAM_STR);
		$stmt->bindParam(':total', $_SESSION['file_to_print']['total'], PDO::PARAM_STR);
		$result = $stmt->execute();
		$stmt->closeCursor();
		DbConnection::disconnect();
		
		// Effectuer le paiement.
		// Après le paiement, ne pas oublier : unset($_SESSION['file_to_print'], $_SESSION['tunnel']);
	}
}
