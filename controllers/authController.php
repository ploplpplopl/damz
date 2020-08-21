<?php

require_once 'models/AuthMgr.class.php'; // TODO verifier les chemins (relatif, absolu).
require_once 'controllers/sendEmails.php';

$firstname = '';
$lastname = '';
$email = '';
$phone = '';
$pseudo = '';
$password = '';
$passwordConf = '';
$errors = [];

// --------------- SIGN UP USER ---------------
if (isset($_POST['signup-btn'])) {
	$firstname = trim($_POST['firstname']);
	$lastname = trim($_POST['lastname']);
	$email = trim($_POST['email']);
	$phone = trim($_POST['phone']);
	$pseudo = trim($_POST['pseudo']);
	$password = trim($_POST['password']);
	$passwordConf = trim($_POST['passwordConf']);
    $token = bin2hex(random_bytes(50));
	
	$pwLength = mb_strlen($password) >= 8;
	$pwLowercase = preg_match('/[a-z]/', $password);
	$pwUppercase = preg_match('/[A-Z]/', $password);
	$pwNumber = preg_match('/[0-9]/', $password);
	$pwSpecialchar = preg_match('/[' . preg_quote('-_"%\'*;<>?^`{|}~/\\#=&', '/') . ']/', $password);
	
    // TODO : ajouter des tests de validité sur les champs (email, tel, complexité mdp...) en JS
    // TODO : ajouter toutes ces vérifications y compris checkPassword comme dans signup_controls.js
    if (empty($firstname)) {
        $errors[] = 'Prénom requis';
    }
    if (empty($lastname)) {
        $errors[] = 'Nom requis';
    }
    if (empty($email)) {
        $errors[] = 'E-mail requis';
    }
	elseif (AuthMgr::emailExists($email)) {
		$errors[] = 'Un compte avec cette adresse e-mail existe déjà';
	}
    if (empty($phone)) {
        $errors[] = 'Téléphone requis';
    }
    if (empty($pseudo)) {
        $errors[] = 'Pseudo requis';
    }
	elseif (AuthMgr::pseudoExists($pseudo)) {
		// TODO : AJAX pour vérifier avant validation du form (onkeyup with debounce/throttle)
		$errors[] = 'Un compte avec ce pseudo existe déjà';
	}
    if (empty($password)) {
        $errors[] = 'Mot de passe requis';
    }
    elseif (!$pwLength || !$pwLowercase || !$pwUppercase || !$pwNumber || !$pwSpecialchar) {
        $errors[] = 'Le mot de passe ne satisfait pas les conditions (8 caractères et AU MOINS 1 minuscule, 1 majuscule, 1 chiffre, 1 caractère spécial)';
    }
    elseif (empty($passwordConf)) {
        $errors[] = 'Confirmation du mot de passe requise';
    }
    elseif (strcmp($password, $passwordConf) !== 0) {
        $errors[] = 'Les mots de passe ne correspondent pas';
    }

    // Insert user into DB
    if (empty($errors)) {
        if (!AuthMgr::signup($firstname, $lastname, $email, $phone, $pseudo, $password, $token)) {
            $_SESSION['message_error'][] = 'L\'inscription a échoué, veuillez réessayer ultérieurement';
        }
		else {
            // Send confirmation email to user.
			$emailSent = sendMail('signup.html', [
				'{site_url}' => $settings['site_url'],
				'{token}' => $token,
			], 'Inscription sur ' . $settings['site_name'], $email);
			
            if (!$emailSent) {
                $_SESSION['message_status'][] = 'Votre inscription est prise en compte';
                $_SESSION['message_error'][] = 'L\'envoi de l\'e-mail de confirmation a échoué, <a href="/index.php?action=verifyUser&amp;token=' . $token . '">renvoyer l\'e-mail</a>';
				
                header('location: index.php?action=login');
				exit;
            }
			else {
				$_SESSION['message_status'][] = 'Un lien de confirmation vous a été adressé à <em>' . $email . '</em> pour finaliser votre inscription';

                header('location: index.php?action=login');
				exit;
            }
        }
    }
}

// --------------- LOGIN ---------------
if (isset($_POST['login-btn'])) {
    if (empty($_POST['pseudo'])) {
        $errors[] = 'Pseudo/e-mail requis';
    }
    if (empty($_POST['password'])) {
        $errors[] = 'Mot de passe requis';
    }

    if (empty($errors)) {
		$checkLogin = AuthMgr::checkLogin($_POST['pseudo'], $_POST['password']);
		
        switch ($checkLogin['status']) {
			case 'error':
				$errors[] = 'Mauvais pseudo ou mot de passe';
				break;
				
			case 'not_confirmed':
				$errors[] = 'Veuillez confirmer votre compte, si vous n\'avez pas reçu d\'e-mail de confirmation <a href="/index.php?action=resendConfirmationMail&amp;token=' . $checkLogin['user']['secure_key'] . '&amp;email=' . $checkLogin['user']['email'] . '">cliquez ici</a>';
				break;
				
			case 'ok':
				switch ($checkLogin['user']['user_type']) {
					case 'admin':
					case 'admprinter':
						$action = 'admin';
						break;
						
					case 'user':
					default:
						$action = 'accueil';
						// TODO : mise en place de la redirection si l'inscription se fait durant le process d'achat.
						//if (!empty($_SESSION['process_dossier'])) {
							//$action = 'overview';
						//}
						break;
				}
				
                // storing the user's data in the session generates his connection
				$_SESSION['user'] = $checkLogin['user'];
				
				header('location: index.php?action=' . $action);
				exit;
				break;
        }
    }
}

// --------------- FORGOT PASSWORD ---------------
if (isset($_POST['forgot-password-btn'])) {
    if (empty($_POST['email'])) {
        $errors[] = 'Adresse e-mail requise';
    }

    if (empty($errors)) {
		$checkAuth = AuthMgr::forgotPassword($_POST['email']);
		
        if (!$checkAuth) {
			$_SESSION['message_error'][] = 'Adresse e-mail introuvable';
		}
		else {
			$emailSent = sendMail('forgot-password.html', [
				'{site_url}' => $settings['site_url'],
				'{token}' => $checkAuth['secure_key'],
				'{email}' => $_POST['email'],
			], 'Récupération de mot de passe sur ' . $settings['site_name'], $_POST['email']);
			
            if (!$emailSent) {
                $_SESSION['message_error'][] = 'L\'envoi de l\'e-mail de récupération de mot de passe a échoué, veuillez réessayer ultérieurement';
            }
			else {
				$_SESSION['message_status'][] = 'Un lien de récupération de mot de passe vous a été envoyé. Cliquez dessus pour réinitialiser votre mot de passe.';
            }
			header('location: index.php?action=forgotPassword');
			exit;
        }
    }
}

// --------------- RESET PASSWORD ---------------
if (isset($_POST['reset-password-btn'])) {
	$password = trim($_POST['password']);
	$passwordConf = trim($_POST['passwordConf']);
	
	$pwLength = mb_strlen($password) >= 8;
	$pwLowercase = preg_match('/[a-z]/', $password);
	$pwUppercase = preg_match('/[A-Z]/', $password);
	$pwNumber = preg_match('/[0-9]/', $password);
	$pwSpecialchar = preg_match('/[' . preg_quote('-_"%\'*;<>?^`{|}~/\\#=&', '/') . ']/', $password);
	
    if (empty($password)) {
        $errors[] = 'Mot de passe requis';
    }
    elseif (!$pwLength || !$pwLowercase || !$pwUppercase || !$pwNumber || !$pwSpecialchar) {
        $errors[] = 'Le mot de passe ne satisfait pas les conditions (8 caractères et AU MOINS 1 minuscule, 1 majuscule, 1 chiffre, 1 caractère spécial)';
    }
    elseif (empty($passwordConf)) {
        $errors[] = 'Confirmation du mot de passe requise';
    }
    elseif (strcmp($password, $passwordConf) !== 0) {
        $errors[] = 'Les mots de passe ne correspondent pas';
    }

    if (empty($errors)) {
		$checkAuth = AuthMgr::resetPassword($password, $_GET['token'], $_GET['email']);
		
		switch ($checkAuth) {
			case 'db_connection_failed':
				$errors[] = 'La connexion a échoué, veuillez réessayer ultérieurement';
				break;
				
			case 'user_not_found':
				$errors[] = 'L\'utilisateur est introuvable, veuillez vérifier le lien contenu dans l\'e-mail de récupération de mot de passe';
				break;
				
			case 'password_updated':
				$_SESSION['message_status'][] = 'Votre mot de passe a été modifié, vous pouvez vous connecter';
				header('location: index.php?action=login');
				exit;
				break;
		}
    }
}

// --------------- LOGOUT ---------------
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    AuthMgr::disconnectUser();
    header('location: index.php?action=login');
    exit;
}

// TODO vider les enregistrements (token) non confirmés après 15 minutes (cronjob).
