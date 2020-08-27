<?php

require_once _ROOT_DIR_ . '/models/dao/DbConnection.class.php';

/**
 * Récupération des couleurs des cartons imprimables / non imprimables.
 *
 * @return array Le jeu de résultats.
 */
function getAllColors() {
	$query = 'SELECT * FROM dossier_color ORDER BY position DESC';
	$sth = DbConnection::getConnection('administrateur')->query($query);
	$sth->execute();
	return $sth->fetchAll(PDO::FETCH_ASSOC);
}

$nomFichier = '';
$nbPagesNB = '';
$nbPagesC = '';
$nbPages = '';

$docType = '';

$btnFTCouv = '';
$btnFCCouv = '';
$couvImpr = '';
$couvColor = '';

$btnFTDos = '';
$btnFCDos = '';
$dosImpr = '';
$dosColor = '';

$btnReliure = '';
$btnCoulReliure = '';

$quantity = '';
$rectoverso = '';

$tva = '';
$total = '';

$errors = [];

if (isset($_POST['dossier-btn'])) {
	$nomFichier = $_POST['nomFichier'];
	$nbPagesNB = $_POST['nbPagesNB'];
	$nbPagesC = $_POST['nbPagesC'];
	$nbPages = $_POST['nbPages'];
	
	$docType = (!empty($_POST['docType']) ? $_POST['docType'] : FALSE);
	
	$btnFTCouv = (!empty($_POST['btnFTCouv']) ? 1 : 0);
	$btnFCCouv = (!empty($_POST['btnFCCouv']) ? 1 : 0);
	$couvImpr = (!empty($_POST['couv-impr']) ? $_POST['couv-impr'] : FALSE);
	$couvColor = (!empty($_POST['couv_color']) ? $_POST['couv_color'] : FALSE);
	
	$btnFTDos = (!empty($_POST['btnFTDos']) ? 1 : 0);
	$btnFCDos = (!empty($_POST['btnFCDos']) ? 1 : 0);
	$dosImpr = (!empty($_POST['dos-impr']) ? $_POST['dos-impr'] : FALSE);
	$dosColor = (!empty($_POST['dos_color']) ? $_POST['dos_color'] : FALSE);
	
	$btnReliure = (!empty($_POST['btnReliure']) ? $_POST['btnReliure'] : FALSE);
	$btnCoulReliure = (!empty($_POST['btnCoulReliure']) ? $_POST['btnCoulReliure'] : FALSE);

	$quantity = (!empty($_POST['quantity']) ? $_POST['quantity'] : FALSE);
	$rectoverso = (!empty($_POST['rectoverso']) ? 1 : 0);

	$tva = $_POST['tva'];
	$total = $_POST['total'];

	if (empty($nomFichier)) {
		$errors[] = 'Veuillez sélectionner un PDF';
	}
	if (empty($docType)) {
		$errors[] = 'Veuillez sélectionner un type de document à imprimer';
	}
	switch ($docType) {
		case 'dossier':
			if (empty($dosColor)) {
				$errors[] = 'Veuillez choisir une couleur de feuille cartonnée (dos)';
			}
			if (empty($btnReliure)) {
				$errors[] = 'Veuillez choisir un type de reliure';
			}
			if (empty($btnCoulReliure)) {
				$errors[] = 'Veuillez choisir une couleur de reliure';
			}
			break;
			
		case 'memoire':
			if (empty($couvColor)) {
				$errors[] = 'Veuillez choisir une couleur de feuille cartonnée (couverture)';
			}
			if (empty($dosColor)) {
				$errors[] = 'Veuillez choisir une couleur de feuille cartonnée (dos)';
			}
			if (empty($btnReliure)) {
				$errors[] = 'Veuillez choisir un type de reliure';
			}
			if (empty($btnCoulReliure)) {
				$errors[] = 'Veuillez choisir une couleur de reliure';
			}
			break;
			
		case 'these':
			if (empty($couvColor)) {
				$errors[] = 'Veuillez choisir une couleur de feuille cartonnée (couverture)';
			}
			if (empty($dosColor)) {
				$errors[] = 'Veuillez choisir une couleur de feuille cartonnée (dos)';
			}
			if (empty($btnCoulReliure)) {
				$errors[] = 'Veuillez choisir une couleur de reliure';
			}
			break;
			
		case 'perso':
			if ($btnFCCouv) {
				if (empty($couvImpr)) {
					$errors[] = 'Veuillez choisir un type de feuille cartonnée (couverture)&nbsp;: imprimable ou non imprimable';
				}
				else {
					if (empty($couvColor)) {
						$errors[] = 'Veuillez choisir une couleur de feuille cartonnée (couverture)';
					}
				}
			}
			if ($btnFCDos) {
				if (empty($dosImpr)) {
					$errors[] = 'Veuillez choisir un type de feuille cartonnée (dos)&nbsp;: imprimable ou non imprimable';
				}
				else {
					if (empty($dosColor)) {
						$errors[] = 'Veuillez choisir une couleur de feuille cartonnée (dos)';
					}
				}
			}
			if (empty($btnReliure)) {
				$errors[] = 'Veuillez choisir un type de reliure';
			}
			if (empty($btnCoulReliure)) {
				$errors[] = 'Veuillez choisir une couleur de reliure';
			}
			break;
			
	}
	if (empty($errors)) {
		// Mise en seession des caractéristiques du PDF à imprimer.
		$_SESSION['file_to_print'] = [
			'nom_fichier' => $nomFichier,
			'nb_page' => $nbPages,
			'nb_page_nb' => $nbPagesNB,
			'nb_page_c' => $nbPagesC,
			'doc_type' => $docType,
			'couv_ft' => $btnFTCouv,
			'couv_fc' => $btnFCCouv,
			'couv_fc_type' => $couvImpr,
			'couv_fc_color' => $couvColor,
			'dos_ft' => $btnFTDos,
			'dos_fc' => $btnFCDos,
			'dos_fc_type' => $dosImpr,
			'dos_fc_color' => $dosColor,
			'reliure_type' => $btnReliure,
			'reliure_color' => $btnCoulReliure,
			'quantity' => $quantity,
			'rectoverso' => $rectoverso,
			'tva' => $tva,
			'total' => $total,
		];
		
		/*
il veut une phrase qui reprend toutes les options choisies et le prix total. c'est tout.
sinon ya aussi le field "options suiplémentaires" . il veut que ca s'appelle "Impression"
dedans : un radio avec 'impression à l'identique du doc' / 'N&B intégral'
et un radio : 'recto' / 'recto-verso'
et le champ "nb d'exemplaires"
		*/
		
		// Mise en session de l'étape du tunnel de paiement.
		$_SESSION['tunnel'] = 'orderOverview';
		
		// Détection de la connexion de l'utilisateur.
		if (empty($_SESSION['user'])) {
			$_SESSION['message_warning'][] = 'Veuillez vous identifier ou <a href="/index.php?action=signup">vous inscrire</a> pour poursuivre votre commande';
			header('location: index.php?action=login');
			exit;
		}
		
		header('location: index.php?action=orderOverview');
		exit;
	}
}
