<?php

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

require_once _ROOT_DIR_ . '/models/dao/DbConnection.class.php';
require_once _ROOT_DIR_ . '/models/AuthMgr.class.php';

// Récupération des adresses de l'utilisateur.
function getUserAddresses() {
	$stmt = DbConnection::getConnection('administrateur')->prepare('
		SELECT a.*, c.name AS country_name
		FROM address AS a
		LEFT JOIN country AS c
		ON a.id_country = c.id_country
		WHERE a.id_user = :id
	');
	$stmt->bindParam(':id', $_SESSION['user']['id_user'], PDO::PARAM_INT);
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$stmt->closeCursor();
	DbConnection::disconnect();
	return $result;
}

// Récupération de la liste des pays.
function getCountries() {
	$stmt = DbConnection::getConnection('administrateur')->query('SELECT * FROM country ORDER BY name ASC');
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$stmt->closeCursor();
	DbConnection::disconnect();
	return $result;
}

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
    }
	elseif (!$validator->isValid($email, new RFCValidation())) {
		$errors[] = 'E-mail invalide';
	}
	elseif (AuthMgr::emailExists($email, $_SESSION['user']['id_user'])) {
		$errors[] = 'Un compte avec cette adresse e-mail existe déjà';
	}
    if (empty($pseudo)) {
        $errors[] = 'Pseudo requis';
    }
	elseif (!preg_match('/^[a-z0-9!#$%&\'*+\/=?_-]{1,50}$/i', $pseudo)) {
        $errors[] = 'Votre pseudo contient des caractères invalides';
    }
	elseif (AuthMgr::pseudoExists($pseudo, $_SESSION['user']['id_user'])) {
		$errors[] = 'Un compte avec ce pseudo existe déjà';
	}
    if (empty($firstname)) {
        $errors[] = 'Prénom requis';
    }
	elseif (!preg_match('/^(\w+[\' -]?)+\w+$/i', $firstname)) {
        $errors[] = 'Votre prénom contient des caractères invalides';
    }
    if (empty($lastname)) {
        $errors[] = 'Nom requis';
    }
	elseif (!preg_match('/^(\w+[\' -]?)+\w+$/i', $lastname)) {
        $errors[] = 'Votre nom contient des caractères invalides';
    }
    if (empty($phone)) {
        $errors[] = 'Téléphone requis';
    }
	elseif (!preg_match('/^[+0-9. ()-]*$/i', $phone)) {
        $errors[] = 'Votre numéro de téléphone n\'est pas valide';
    }
	
	if (empty($errors)) {
		$dbh = DbConnection::getConnection('administrateur');
		$stmt = $dbh->prepare('UPDATE user SET email = :email, pseudo = :pseudo, first_name = :firstname, last_name = :lastname, phone = :phone WHERE id_user = :id');
		$stmt->bindParam(':email', $email, PDO::PARAM_STR);
		$stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
		$stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
		$stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
		$stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$result = $stmt->execute();
		$stmt->closeCursor();
		DbConnection::disconnect();
		if ($result) {
			$_SESSION['user']['email'] = $email;
			$_SESSION['user']['pseudo'] = $pseudo;
			$_SESSION['user']['first_name'] = $firstname;
			$_SESSION['user']['last_name'] = $lastname;
			$_SESSION['user']['phone'] = $phone;
			
			$_SESSION['message_status'][] = 'Vos informations personnelles sont mises à jour';
		}
		header('location: index.php?action=account');
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
    }
    elseif (!$pwLength || !$pwLowercase || !$pwUppercase || !$pwNumber || !$pwSpecialchar) {
        $errorsPassword[] = 'Le mot de passe ne satisfait pas les conditions (8 caractères et AU MOINS 1 minuscule, 1 majuscule, 1 chiffre, 1 caractère spécial)';
    }
    elseif (empty($passwordConf)) {
        $errorsPassword[] = 'Confirmation du mot de passe requise';
    }
    elseif (strcmp($password, $passwordConf) !== 0) {
        $errorsPassword[] = 'Les mots de passe ne correspondent pas';
    }
	
    if (empty($errorsPassword)) {
		$dbh = DbConnection::getConnection('administrateur');
		$stmt = $dbh->prepare('UPDATE user SET password = :password WHERE id_user = :id');
		$password = password_hash($password, PASSWORD_DEFAULT);
		$stmt->bindParam(':password', $password, PDO::PARAM_STR);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$result = $stmt->execute();
		$stmt->closeCursor();
		DbConnection::disconnect();
		if ($result) {
			$_SESSION['message_status'][] = 'Votre mot de passe est mis à jour';
		}
		header('location: index.php?action=account');
		exit;
	}
}

// TODO Delete account.
// 1- Vérifier validité du mot de passe.
$deleteAccountPassword = '';
$errorsDelete = [];
if (isset($_POST['delete-account-btn'])) {
	$deleteAccountPassword = $_POST[''];
	/*$dbh = DbConnection::getConnection('administrateur');
	$stmt = $dbh->prepare('DELETE FROM user WHERE id_user = :id');
	$stmt->bindParam(':id', $_SESSION['user']['id_user'], PDO::PARAM_INT);
	$stmt->execute();
	$stmt->closeCursor();
	DbConnection::disconnect();
	$_SESSION['message_status'][] = 'Votre compte est supprimé';
	header('location: index.php?action=home');
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

$addUpd = 'add';
if (!empty($_GET['edit']) && is_numeric($_GET['edit'])) {
	$addUpd = 'upd';
	$stmt = DbConnection::getConnection('administrateur')->prepare('SELECT * FROM address WHERE id_address = :id_address AND id_user = :id_user');
	$stmt->bindParam(':id_address', $_GET['edit']);
	$stmt->bindParam(':id_user', $_SESSION['user']['id_user']);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	$id_address = $result['id_address'];
	$addrName = $result['addr_name'];
	$address = $result['address'];
	$address2 = $result['address2'];
	$zipcode = $result['zip_code'];
	$city = $result['city'];
	$country = $result['id_country'];
	$addrLabel = $result['addr_label'];
	$stmt->closeCursor();
	DbConnection::disconnect();
}

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
	}
	elseif (!preg_match('/^[^<>;=#{}]*$/i', $addrName)) {
		$errorsAddress[] = 'Prénom/nom contient des caractères invalides';
	}
	if (empty($address)) {
		$errorsAddress[] = 'Adresse requise';
	}
	elseif (!preg_match('/^[^!<>?=+@{}_$%]*$/i', $address)) {
		$errorsAddress[] = 'L\'adresse contient des caractères invalides';
	}
	if (!empty($address2) && !preg_match('/^[^!<>?=+@{}_$%]*$/i', $address2)) {
		$errorsAddress[] = 'Le complément d\'adresse contient des caractères invalides';
	}
	if (empty($zipcode)) {
		$errorsAddress[] = 'Code postal requis';
	}
	elseif (!preg_match('/^[a-z 0-9-]+$/i', $zipcode)) {
		$errorsAddress[] = 'Code postal invalide';
	}
	if (empty($city)) {
		$errorsAddress[] = 'Ville requise';
	}
	elseif (!preg_match('/^[^!<>;?=+@#"°{}_$%0-9]*$/i', $city)) {
		$errorsAddress[] = 'La ville contient des caractères invalides';
	}
	if (empty($country)) {
		$errorsAddress[] = 'Pays requis';
	}
	elseif (!in_array($country, array_column(getCountries(), 'id_country'))) {
		$errorsAddress[] = 'Pays invalide';
	}
	if (empty($addrLabel)) {
		$errorsAddress[] = 'Nom de l\'adresse requis';
	}
	elseif (!preg_match('/^[^<>;=#{}]*$/i', $addrLabel)) {
		$errorsAddress[] = 'Le nom de l\'adresse contient des caractères invalides';
	}
	
	if (empty($errorsAddress)) {
		$dbh = DbConnection::getConnection('administrateur');
		if ('add' == $addUpd) {
			$stmt = $dbh->prepare('INSERT INTO address (id_user, addr_label, addr_name, address, address2, zip_code, city, id_country) VALUES (:id_user, :addr_label, :addr_name, :address, :address2, :zip_code, :city, :id_country)');
		}
		else {
			$stmt = $dbh->prepare('UPDATE address SET id_user = :id_user, addr_label = :addr_label, addr_name = :addr_name, address = :address, address2 = :address2, zip_code = :zip_code, city = :city, id_country = :id_country WHERE id_address = :id_address AND id_user = :id_user');
			$stmt->bindParam(':id_address', $id_address, PDO::PARAM_INT);
		}
		$stmt->bindParam(':id_user', $_SESSION['user']['id_user'], PDO::PARAM_INT);
		$stmt->bindParam(':addr_label', $addrLabel, PDO::PARAM_STR);
		$stmt->bindParam(':addr_name', $addrName, PDO::PARAM_STR);
		$stmt->bindParam(':address', $address, PDO::PARAM_STR);
		$stmt->bindParam(':address2', $address2, PDO::PARAM_STR);
		$stmt->bindParam(':zip_code', $zipcode, PDO::PARAM_STR);
		$stmt->bindParam(':city', $city, PDO::PARAM_STR);
		$stmt->bindParam(':id_country', $country, PDO::PARAM_INT);
		$result = $stmt->execute();
		$stmt->closeCursor();
		DbConnection::disconnect();
		if ($result) {
			$_SESSION['message_status'][] = 'add' == $addUpd ? 'Adresse ajoutée' : 'Adresse modifiée';
		}
		header('location: index.php?action=accountAddresses');
		exit;
	}
}

if (!empty($_GET['del']) && is_numeric($_GET['del'])) {
	$dbh = DbConnection::getConnection('administrateur');
	$stmt = $dbh->prepare('DELETE FROM address WHERE id_address = :id_address AND id_user = :id_user');
	$stmt->bindParam(':id_address', $_GET['del'], PDO::PARAM_INT);
	$stmt->bindParam(':id_user', $_SESSION['user']['id_user'], PDO::PARAM_INT);
	$stmt->execute();
	$stmt->closeCursor();
	DbConnection::disconnect();
	$_SESSION['message_status'][] = 'Adresse supprimée';
	header('location: index.php?action=accountAddresses');
	exit;
}
