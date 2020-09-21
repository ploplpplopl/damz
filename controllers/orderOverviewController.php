<?php

require_once _ROOT_DIR_ . '/models/AdminGestionMgr.class.php';


// Récupération des couleurs des feuilles cartonnées.
$allColors = $allColors = AdminGestionMgr::getMappingColors();
$mappingColors = [];
foreach ($allColors as $color) {
	$mappingColors[$color['id_dossier_color']] = $color['text'];
}

$userAddresses = AuthMgr::getUserAddresses($_SESSION['user']['id_user']);
$userPhone = AuthMgr::getUserPhone($_SESSION['user']['id_user']);

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
		// TODO ajouter des colonnes dans la table "orders" pour enregistrer les infos du user à l'instant T (adresse de livraison, etc) pour que les factures restent identiques en cas de modif des infos du user.
		// TODO à transférer après le paiement
		AdminGestionMgr::addOrder(
			date('Y-m-d H:i:s'),
			$_SESSION['user']['id_user'],
			$address,
			$_SESSION['file_to_print']['nom_fichier'],
			$_SESSION['file_to_print']['nb_page'],
			$_SESSION['file_to_print']['nb_page_nb'],
			$_SESSION['file_to_print']['nb_page_c'],
			$_SESSION['file_to_print']['doc_type'],
			$_SESSION['file_to_print']['couv_ft'],
			$_SESSION['file_to_print']['couv_fc'],
			$_SESSION['file_to_print']['couv_fc_type'],
			$_SESSION['file_to_print']['couv_fc_color'],
			$_SESSION['file_to_print']['dos_ft'],
			$_SESSION['file_to_print']['dos_fc'],
			$_SESSION['file_to_print']['dos_fc_type'],
			$_SESSION['file_to_print']['dos_fc_color'],
			$_SESSION['file_to_print']['reliure_type'],
			$_SESSION['file_to_print']['reliure_color'],
			$_SESSION['file_to_print']['quantity'],
			$_SESSION['file_to_print']['rectoverso'],
			$_SESSION['file_to_print']['tva'],
			$_SESSION['file_to_print']['total']
		);
	}

	// TODO Effectuer le paiement.
	// Après le paiement, ne pas oublier : unset($_SESSION['file_to_print'], $_SESSION['tunnel']);
}
