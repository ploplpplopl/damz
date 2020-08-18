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
            //header('location: index.php?action=signup');
			//exit;
        }
		else {
            // Send confirmation email to user.

			$emailSent = sendMail('signup.html', [
				'{token}' => $token,
			], 'Inscription sur Company.com', $email);
			
            if (!$emailSent) {
				// TODO renvoyer l'e-mail.
                $_SESSION['message_status'][] = 'Votre inscription est prise en compte';
                $_SESSION['message_error'][] = 'L\'envoi de l\'e-mail de confirmation a échoué, <a href="#">renvoyer l\'e-mail</a>';
				
                header('location: index.php?action=login');
				exit;
            }
			else {
				$_SESSION['message_status'][] = 'Votre inscription est presque terminée, veuillez vérifier vos e-mails pour confirmer votre inscription';

                header('location: index.php?action=login');
				exit;
            }
        }
    }
}

// --------------- LOGIN ---------------
if (isset($_POST['login-btn'])) {
    if (empty($_POST['pseudo'])) {
        $errors['pseudo'] = 'Pseudo requis';
    }
    if (empty($_POST['password'])) {
        $errors['password'] = 'Mot de passe requis';
    }

    if (empty($errors)) {
        if (AuthMgr::checkLogin($_POST['pseudo'], $_POST['password'])) {
            if ($_POST['pseudo'] == 'printer') {
                header('location: index.php?action=admprint');
				exit;
            }
			elseif ($_POST['pseudo'] == 'admin') {
                header('location: index.php?action=admin');
				exit;
            }
			else {
                // redirection sur la page où les messages ($_SESSION['message']) seront affichés
                header('location: index.php?action=accueil');
				exit;
            }
        }
		else { // if password does not match
            $errors['login_fail'] = "Wrong pseudo / password";
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
