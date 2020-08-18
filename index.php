<?php

// Imports
require 'controllers/authController.php';
require 'models/debug.php';

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
		// if ($DEBUG) echo '<script>console.log("action : '.$action.'")</script>';
		// echo "SESSION: <br>";
		// print_r($_SESSION);
		$sTitre = 'dossier-rapide';
		// require('views/head.php');
		require('views/dossier.php');
		// require('views/footer.htm');
		// require('views/bootstrap.htm');
		// echo '<script>console.table($("#uploadPDF").prop("files")[0].name)</script>';
		break;

	case 'login':
		$sTitre = 'Connexion';
		echo "SESSION: <br>";
		print_r($_SESSION);
		require('views/login.php');
		break;
		// TODO si le login foire : pas de message d'erreur : $errors

	case 'signup':
		$sTitre = 'Créez votre compte';
		require('views/signup.php');
		break;

	case 'logout':
		$sTitre = 'Déconnexion';
		require('views/login.php');
		break;

	case 'verifyUser':
		require('controllers/verifyEmail.php');
		break;

	case 'admprint':
		$sTitre = 'À imprimer';
		if (isset($_SESSION['pseudo']) && ($_SESSION['pseudo'] == 'printer')) {
			require('views/admPrint.php');
		} else {
			$errors['access denied'] = 'Vous n\'avez pas les droits pour accéder à la page d\'admin'; // TODO erreur ne s'affiche pas car "$errors=[]" dans authController.php
			// require('views/dossier.php');
			header("location: index.php?action=login");
		}
		break;

	case 'admin':
		$sTitre = 'Administration';
		if (isset($_SESSION['pseudo']) && ($_SESSION['pseudo'] == 'admin')) {
			require('views/admin.php');
		} else {
			$errors['access denied'] = 'Vous n\'avez pas les droits pour accéder à la page d\'admin'; // TODO erreur ne s'affiche pas car "$errors=[]" dans authController.php
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
