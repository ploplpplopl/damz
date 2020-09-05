<?php

require_once 'models/AuthMgr.class.php';

if (!isset($_GET['token'])) {
	$_SESSION['message_error'][] = 'Aucun token fourni';
}
else {
    switch (AuthMgr::verifyEmail($_GET['token'])) {
        case 'already_confirmed':
			$_SESSION['message_warning'][] = (!empty($_GET['back']) && 'forgotPassword' == $_GET['back']) ? 'Votre compte est déjà activé, vous pouvez réinitialiser votre mot de passe' : 'Votre compte est déjà activé, vous pouvez vous connecter';
			break;
        case 'confirmed':
			$_SESSION['message_status'][] = (!empty($_GET['back']) && 'forgotPassword' == $_GET['back']) ? 'Votre compte est activé, vous pouvez réinitialiser votre mot de passe' : 'Votre compte est activé, vous pouvez vous connecter';
			break;
        case 'error':
		default:
			$_SESSION['message_error'][] = 'Une erreur est survenue, vérifiez le lien fourni dans l\'e-mail de confirmation';
			break;
    }
	
	if (!empty($_GET['back'])) {
		header('location: index.php?action=' . $_GET['back']);
		exit;
	}
	
	header('location: /connexion');
	exit;
}
