<?php

// Imports
require_once 'config/config.php';
require_once 'config/settings.php';
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
		require('views/forgot-password.php');
		break;

	case 'resetPassword':
		$sTitre = 'Réinitialisation de mon mot de passe';
		require('views/reset-password.php');
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

	case 'admprint':
		$sTitre = 'À imprimer';
		if (isset($_SESSION['user']['user_type']) && ($_SESSION['user']['user_type'] == 'printer')) {
			require('views/admPrint.php');
		} else {
			$errors[] = 'Vous n\'avez pas les droits pour accéder à cette page';
			// TODO erreur ne s'affiche pas car "$errors=[]" dans authController.php
			// require('views/dossier.php');
			header("location: index.php?action=login");
		}
		break;

	case 'admin':
		$sTitre = 'Administration';
		if (isset($_SESSION['user']['user_type']) && ($_SESSION['user']['user_type'] == 'admin')) {
			require('views/admin.php');
		} else {
			$errors[] = 'Vous n\'avez pas les droits pour accéder cette page';
			// TODO erreur ne s'affiche pas car "$errors=[]" dans authController.php
			// require('views/dossier.php');
			header("location: index.php?action=login");
		}
		break;


		// default:
		// 	require('views/head.php');
		// 	require('views/dossier.php');
		// 	require('views/footer.htm');
		// 	break;
}
