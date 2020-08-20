<?php

// Imports
require_once 'config/settings.php';
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
	case 'accueil':
		$sTitre = 'dossier-rapide';
		require('views/dossier.php');
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

	// ADMPRINT
	case 'admprint':
		$sTitre = 'À imprimer';
		require('views/admprint.php');
		break;

	// ADMIN
	case 'admin':
		$sTitre = 'Administration';
		require('views/admin.php');
		break;
	case 'adminPaliersNB':
		$sTitre = 'Administration';
		require('views/adminPaliersNB.php');
		break;
	case 'adminPaliersC':
		$sTitre = 'Administration';
		require('views/adminPaliersC.php');
		break;


		// default:
		// 	require('views/head.php');
		// 	require('views/dossier.php');
		// 	require('views/footer.php');
		// 	break;
}
