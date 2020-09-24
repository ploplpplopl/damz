<?php

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

require_once _ROOT_DIR_ . '/models/AuthMgr.class.php';

// Affichage des adresses de l'utilisateur.
$addresses = AuthMgr::getUserAddresses($_SESSION['user']['id_user']);
// Récupération de la liste des pays.
$countries = AuthMgr::getCountries();



// Default user info.
$id = $_SESSION['user']['id_user'];
$firstname = $_SESSION['user']['first_name'];
$lastname = $_SESSION['user']['last_name'];
$email = $_SESSION['user']['email'];
$phone = $_SESSION['user']['phone'];
$pseudo = $_SESSION['user']['pseudo'];
$errors = [];

if (isset($_POST['user-info-btn'])) {
	$email = trim($_POST['email']);
	$pseudo = trim($_POST['pseudo']);
	$firstname = trim($_POST['firstname']);
	$lastname = trim($_POST['lastname']);
	$phone = trim($_POST['phone']);

	$validator = new EmailValidator();

	if (empty($email)) {
		$errors[] = 'E-mail requis';
	} elseif (!$validator->isValid($email, new RFCValidation())) {
		$errors[] = 'E-mail invalide';
	} elseif (AuthMgr::emailExists($email, $_SESSION['user']['id_user'])) {
		$errors[] = 'Un compte avec cette adresse e-mail existe déjà';
	}

	if (empty($pseudo)) {
		$errors[] = 'Pseudo requis';
	} elseif (!preg_match('/^[a-z0-9!#$%&\'*+\/=?_-]{1,50}$/i', $pseudo)) {
		$errors[] = 'Votre pseudo contient des caractères invalides';
	} elseif (AuthMgr::pseudoExists($pseudo, $_SESSION['user']['id_user'])) {
		$errors[] = 'Un compte avec ce pseudo existe déjà';
	}

	if (empty($firstname)) {
		$errors[] = 'Prénom requis';
	} elseif (!preg_match('/^(\w+[\' -]?)+\w+$/i', $firstname)) {
		$errors[] = 'Votre prénom contient des caractères invalides';
	}

	if (empty($lastname)) {
		$errors[] = 'Nom requis';
	} elseif (!preg_match('/^(\w+[\' -]?)+\w+$/i', $lastname)) {
		$errors[] = 'Votre nom contient des caractères invalides';
	}

	if (!empty($phone) && !preg_match('/^[+0-9. ()-]*$/i', $phone)) {
		$errors[] = 'Votre numéro de téléphone n\'est pas valide';
	}

	if (strcmp($_SESSION['csrf_token'], $_POST['csrf_token']) !== 0) {
		$errors[] = 'Jeton de sécurité invalide';
	}
	vd($_SESSION['csrf_token'], $_POST['csrf_token']);exit;

	if (empty($errors)) {
		$result = AuthMgr::updateUser(['email' => $email, 'pseudo' => $pseudo, 'first_name' => $firstname, 'last_name' => $lastname, 'phone' => $phone], $id);

		if ($result) {
			$_SESSION['user']['email'] = $email;
			$_SESSION['user']['pseudo'] = $pseudo;
			$_SESSION['user']['first_name'] = $firstname;
			$_SESSION['user']['last_name'] = $lastname;
			$_SESSION['user']['phone'] = $phone;

			$_SESSION['message_status'][] = 'Vos informations personnelles sont mises à jour';
		}
		header('location: /mon-compte');
		exit;
	}
}

// Password form.
$password = '';
$passwordConf = '';
$errorsPassword = [];

if (isset($_POST['user-password-btn'])) {
	$password = trim($_POST['password']);
	$passwordConf = trim($_POST['passwordConf']);

	$pwLength = mb_strlen($password) >= 8;
	$pwLowercase = preg_match('/[a-z]/', $password);
	$pwUppercase = preg_match('/[A-Z]/', $password);
	$pwNumber = preg_match('/[0-9]/', $password);
	$pwSpecialchar = preg_match('/[' . preg_quote('-_"%\'*;<>?^`{|}~/\\#=&', '/') . ']/', $password);

	if (empty($password)) {
		$errorsPassword[] = 'Mot de passe requis';
	} elseif (!$pwLength || !$pwLowercase || !$pwUppercase || !$pwNumber || !$pwSpecialchar) {
		$errorsPassword[] = 'Le mot de passe ne satisfait pas les conditions (8 caractères et AU MOINS 1 minuscule, 1 majuscule, 1 chiffre, 1 caractère spécial)';
	} elseif (empty($passwordConf)) {
		$errorsPassword[] = 'Confirmation du mot de passe requise';
	} elseif (strcmp($password, $passwordConf) !== 0) {
		$errorsPassword[] = 'Les mots de passe ne correspondent pas';
	}

	if (strcmp($_SESSION['csrf_token'], $_POST['csrf_token']) !== 0) {
		$errorsPassword[] = 'Jeton de sécurité invalide';
	}

	if (empty($errorsPassword)) {
		$result = AuthMgr::updateUser(['password' => $password], $id);

		if ($result) {
			$_SESSION['message_status'][] = 'Votre mot de passe est mis à jour';
		}
		header('location: /mon-compte');
		exit;
	}
}

// TODO Delete account.
// 1- Vérifier validité du mot de passe.
$deleteAccountPassword = '';
$errorsDelete = [];
// vérifier $_SESSION['csrf_token'] avec 'accountDeleted'
if (isset($_POST['delete-account-btn'])) {
	$deleteAccountPassword = $_POST[''];
	/*$dbh = DbConnection::getConnection('administrateur');
	$stmt = $dbh->prepare('UPDATE user SET deleted=1 WHERE id_user = :id');
	$stmt->bindParam(':id', $_SESSION['user']['id_user'], PDO::PARAM_INT);
	$stmt->execute();
	$stmt->closeCursor();
	DbConnection::disconnect();
	$_SESSION['message_status'][] = 'Votre compte est supprimé';
	header('location: /');
	exit;*/
}

// User address.
$addrName = '';
$address = '';
$address2 = '';
$zipcode = '';
$city = '';
$country = '';
$addrLabel = '';
$errorsAddress = [];

// Variable ajout/modification (defaut : ajout d'une adresse)
$addUpd = 'add';

// Affichage de l'adresse à modifier
if (!empty($_GET['edit']) && is_numeric($_GET['edit'])) {
	$addUpd = 'upd';
	$result = AuthMgr::getAddress($_GET['edit'], $_SESSION['user']['id_user']);

	$id_address = $result['id_address'];
	$addrName = $result['addr_name'];
	$address = $result['address'];
	$address2 = $result['address2'];
	$zipcode = $result['zip_code'];
	$city = $result['city'];
	$country = $result['id_country'];
	$addrLabel = $result['addr_label'];
}

// Modification ou ajout d'une adresse.
if (isset($_POST['user-address-btn'])) {
	$addrName = trim($_POST['addr_name']);
	$address = trim($_POST['address']);
	$address2 = trim($_POST['address2']);
	$zipcode = trim($_POST['zipcode']);
	$city = trim($_POST['city']);
	$country = trim($_POST['country']);
	$addrLabel = trim($_POST['addr_label']);

	if (empty($addrName)) {
		$errorsAddress[] = 'Prénom/nom requis';
	} elseif (!preg_match('/^[^<>;=#{}]*$/i', $addrName)) {
		$errorsAddress[] = 'Prénom/nom contient des caractères invalides';
	}
	if (empty($address)) {
		$errorsAddress[] = 'Adresse requise';
	} elseif (!preg_match('/^[^!<>?=+@{}_$%]*$/i', $address)) {
		$errorsAddress[] = 'L\'adresse contient des caractères invalides';
	}
	if (!empty($address2) && !preg_match('/^[^!<>?=+@{}_$%]*$/i', $address2)) {
		$errorsAddress[] = 'Le complément d\'adresse contient des caractères invalides';
	}
	if (empty($zipcode)) {
		$errorsAddress[] = 'Code postal requis';
	} elseif (!preg_match('/^[a-z 0-9-]+$/i', $zipcode)) {
		$errorsAddress[] = 'Code postal invalide';
	}
	if (empty($city)) {
		$errorsAddress[] = 'Ville requise';
	} elseif (!preg_match('/^[^!<>;?=+@#"°{}_$%0-9]*$/i', $city)) {
		$errorsAddress[] = 'La ville contient des caractères invalides';
	}
	if (empty($country)) {
		$errorsAddress[] = 'Pays requis';
	} elseif (!in_array($country, array_column($countries, 'id_country'))) {
		$errorsAddress[] = 'Pays invalide';
	}
	if (empty($addrLabel)) {
		$errorsAddress[] = 'Nom de l\'adresse requis';
	} elseif (!preg_match('/^[^<>;=#{}]*$/i', $addrLabel)) {
		$errorsAddress[] = 'Le nom de l\'adresse contient des caractères invalides';
	}

	if (strcmp($_SESSION['csrf_token'], $_POST['csrf_token']) !== 0) {
		$errorsAddress[] = 'Jeton de sécurité invalide';
	}

	if (empty($errorsAddress)) {
		$params = [
			'id_user' => $_SESSION['user']['id_user'],
			'addr_label' => $addrLabel,
			'addr_name' => $addrName,
			'address' => $address,
			'address2' => $address2,
			'zip_code' => $zipcode,
			'city' => $city,
			'id_country' => $country,
		];
		if ('add' == $addUpd) {
			$result = AuthMgr::addAddress($params);
		} else {  // 'upd' == $addUpd
			$result = AuthMgr::updateAddress($params, $id_address, 'AND id_user = ' . $_SESSION['user']['id_user']);
		}

		if ($result) {
			$_SESSION['message_status'][] = 'add' == $addUpd ? 'Adresse ajoutée' : 'Adresse modifiée';
		}
		header('location: /mon-compte/mes-adresses');
		exit;
	}
}

// Suppression d'une adresse.
if (!empty($_GET['del']) && is_numeric($_GET['del'])) {
	$country = "";
	foreach ($addresses as $addr) {
		if ($addr["id_address"] == $_GET['del']) {
			$country = $addr["id_country"];
		}
	}
	
	$params = [
		'id_user' => $_SESSION['user']['id_user'],
		'id_country' => $country,
		'deleted' => 1,
	];
	AuthMgr::updateAddress($params, $_GET['del']);

	$_SESSION['message_status'][] = 'Adresse supprimée';
	header('location: /mon-compte/mes-adresses');
	exit;
}
