<?php

require_once _ROOT_DIR_ . '/models/AdminGestionMgr.class.php';

// Récupération des couleurs des cartons imprimables / non imprimables.
$allColors = AdminGestionMgr::getColors();

$nomFichier = '';
$nomFichier_client = '';
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
	$nomFichier_client = $_POST['nomFichier_client'];
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
	if (strcmp($_SESSION['csrf_token'], $_POST['csrf_token']) !== 0) {
        $errors[] = 'Jeton de sécurité invalide';
    }

	if (empty($errors)) {
		// Mise en session des caractéristiques du PDF à imprimer.
		$_SESSION['file_to_print'] = [
			'nom_fichier' => $nomFichier,
			'nom_fichier_client' => $nomFichier_client,
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
		// Mise à jour des valeurs des champs disabled, non passés par POST.
		switch ($docType) {
			case 'dossier':
				$_SESSION['file_to_print']['couv_ft'] = '1';
				$_SESSION['file_to_print']['dos_fc'] = '1';
				$_SESSION['file_to_print']['dos_fc_type'] = 'unprintable';
				break;
				
			case 'memoire':
				$_SESSION['file_to_print']['couv_ft'] = '1';
				$_SESSION['file_to_print']['couv_fc'] = '1';
				$_SESSION['file_to_print']['couv_fc_type'] = 'printable';
				$_SESSION['file_to_print']['dos_fc'] = '1';
				$_SESSION['file_to_print']['dos_fc_type'] = 'unprintable';
				break;
				
			case 'these':
				$_SESSION['file_to_print']['couv_fc'] = '1';
				$_SESSION['file_to_print']['couv_fc_type'] = 'printable';
				$_SESSION['file_to_print']['dos_fc'] = '1';
				$_SESSION['file_to_print']['dos_fc_type'] = 'printable';
				$_SESSION['file_to_print']['reliure_type'] = 'thermo';
				break;
		}
		
		/*
TODO évolutions :
dans le label "Impression" : un radio avec 'impression à l'identique du doc' / 'N&B intégral'
et un radio : 'recto' / 'recto-verso'
et le champ "nb d'exemplaires".
à la place du devis, il veut une phrase qui reprend toutes les options choisies et le prix total. c'est tout.
		*/
		
		// Mise en session de l'étape du tunnel de paiement.
		$_SESSION['tunnel'] = '/commande/resume';
		
		// Détection de la connexion de l'utilisateur.
		if (empty($_SESSION['user'])) {
			$_SESSION['message_warning'][] = 'Veuillez vous identifier ou <a href="/inscription">vous inscrire</a> pour poursuivre votre commande';
			header('location: /connexion');
			exit;
		}
		
		header('location: /commande/resume');
		exit;
	}
}
