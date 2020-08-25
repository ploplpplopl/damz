<?php

// Imports
require_once 'config/config.php';
require_once 'models/functions.php';
require 'controllers/authController.php';

// Initialisation des variables

$tParam = parse_ini_file("config/param.ini");
$file = $tParam['chemin'] . $tParam['fichierClient'];
$DEBUG = $tParam['debug'];

// debug($_GET, $DEBUG);
// if (isset($_SESSION['pseudo']) && ($_SESSION['pseudo'] == 'printer')) $action = 'admprint';
// if (isset($_SESSION['pseudo']) && ($_SESSION['pseudo'] == 'admin')) $action = 'admAccueil';  // TODO page avec lien vers admprint et admin

$action = isset($_GET['action']) ? $_GET['action'] : 'accueil';

// debug('action: '.$action,$DEBUG);
// Etapes et traitements
switch ($action) {
	case 'home':
		$sTitre = 'Impression de documents à Caen';
		require('views/home.php');
		break;

	case 'accueil':
		$sTitre = 'Imprimez votre document';
		require('views/dossier.php');
		break;

	case 'contact':
		$sTitre = 'Contactez-nous';
		require('views/contact.php');
		break;

	case 'login':
		$sTitre = 'Connexion';
		require('views/login.php');
		break;

	case 'signup':
		$sTitre = 'Créez votre compte';
		require('views/signup.php');
		break;

	case 'forgotPassword':
		$sTitre = 'Mot de passe oublié';
		require('views/forgotPassword.php');
		break;

	case 'resetPassword':
		$sTitre = 'Réinitialisation de mon mot de passe';
		require('views/resetPassword.php');
		break;

	case 'logout':
		$sTitre = 'Déconnexion';
		require('views/login.php');
		break;

	case 'verifyUser':
		require('controllers/verifyEmail.php');
		break;

	case 'resendConfirmationMail':
		require('controllers/resendConfirmationMail.php');
		break;

	case 'account':
		$sTitre = 'Mon compte';
		require('views/account.php');
		break;
	case 'accountAddresses':
		$sTitre = 'Mes adresses';
		require('views/accountAddresses.php');
		break;

	// ADMIN
	case 'admin':
		$sTitre = 'Administration';
		require('views/admin.php');
		break;
	case 'adminUsers':
		$sTitre = 'Administration';
		require('views/adminUsers.php');
		break;
	case 'adminOrders':
		$sTitre = 'Administration';
		require('views/adminOrders.php');
		break;
	case 'adminOrdersPast':
		$sTitre = 'Administration';
		require('views/adminOrdersPast.php');
		break;
	case 'adminPaliersNB':
		$sTitre = 'Administration';
		require('views/adminPaliersNB.php');
		break;
	case 'adminPaliersC':
		$sTitre = 'Administration';
		require('views/adminPaliersC.php');
		break;
	case 'adminCouleurs':
		$sTitre = 'Administration';
		require('views/adminCouleurs.php');
		break;


		// default:
		// 	require('views/head.php');
		// 	require('views/dossier.php');
		// 	require('views/footer.php');
		// 	break;
}
