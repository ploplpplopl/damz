<?php

require_once 'models/AuthMgr.class.php';

if (!isset($_GET['token'])) {
	$_SESSION['message_error'][] = 'Aucun token fourni';
}
else {
    switch (AuthMgr::verifyEmail($_GET['token'])) {
        case 'already_confirmed':
			$_SESSION['message_warning'][] = 'Votre compte est déjà activé, vous pouvez vous connecter';
			break;
        case 'confirmed':
			$_SESSION['message_status'][] = 'Votre compte est activé, vous pouvez vous connecter';
			break;
        case 'error':
		default:
			$_SESSION['message_error'][] = 'Une erreur est survenue, vérifiez le lien fourni dans l\'e-mail de confirmation';
			break;
    }
	
	header('location: index.php?action=login');
	exit;
}
