<?php

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

require_once _ROOT_DIR_ . '/models/AdminGestionMgr.class.php';
require_once _ROOT_DIR_ . '/models/Pagination.class.php';
require_once _ROOT_DIR_ . '/models/AuthMgr.class.php';
require_once _ROOT_DIR_ . '/controllers/sendEmails.php';

// -------------- AFFICHAGE DES UTILISATEURS --------------
// Récupération des commandes.
$tab_order = [
	1 => 'u.`date_add`',
	2 => 'u.`first_name`',
	3 => 'u.`last_name`',
	4 => 'u.`email`',
	5 => 'u.`phone`',
	6 => 'u.`subscr_confirmed`',
	7 => 'u.`user_type`',
];
$tab_way = [
	1 => 'ASC',
	2 => 'DESC',
];

$sort_order = !empty($_GET['sort_order']) ? $_GET['sort_order'] : 1;
$sort_way = !empty($_GET['sort_way']) ? $_GET['sort_way'] : 2;

$order = ($sort_order && array_key_exists($sort_order, $tab_order) ? $tab_order[$sort_order] : $tab_order[1]);
$way = ($sort_way && array_key_exists($sort_way, $tab_way) ? $tab_way[$sort_way] : $tab_way[1]);

$date_from = '';
$date_from_fr = '';
$date_to = '';
$date_to_fr = '';
$firstname = '';
$lastname = '';
$email = '';
$phone = '';
$confirmed = '';
$userType = [];
$numOrders = '';

$params = [];
$where = '';

if (isset($_GET['filter'])) {
	$date_from_fr = !empty($_GET['date_from']) ? $_GET['date_from'] : '';
	$date_from = !empty($date_from_fr) ? date('Y-m-d', strtotime($date_from_fr)) : '';
	$date_to_fr = !empty($_GET['date_to']) ? $_GET['date_to'] : '';
	$date_to = !empty($date_to_fr) ? date('Y-m-d', strtotime($date_to_fr)) : '';
	$firstname = !empty($_GET['first_name']) ? $_GET['first_name'] : '';
	$lastname = !empty($_GET['last_name']) ? $_GET['last_name'] : '';
	$email = !empty($_GET['email']) ? $_GET['email'] : '';
	$phone = !empty($_GET['phone']) ? $_GET['phone'] : '';
	$confirmed = !empty($_GET['confirmed']) || (isset($_GET['confirmed']) && $_GET['confirmed'] === '0') ? $_GET['confirmed'] : '';
	$userType = !empty($_GET['user_type']) ? $_GET['user_type'] : [];
	$numOrders = !empty($_GET['num_orders']) || (isset($_GET['num_orders']) && $_GET['num_orders'] === '0') ? $_GET['num_orders'] : '';

	if (!empty($date_from)) {
		$where .= ' AND u.`date_add` > :date_from';
		$params[':date_from'] = $date_from;
	}
	if (!empty($date_to)) {
		$where .= ' AND u.`date_add` < :date_to';
		$params[':date_to'] = $date_to . ' 23:59:59';
	}
	if (!empty($firstname)) {
		$where .= ' AND u.`first_name` LIKE :firstname';
		$params[':firstname'] = '%' . $firstname . '%';
	}
	if (!empty($lastname)) {
		$where .= ' AND u.`last_name` LIKE :lastname';
		$params[':lastname'] = '%' . $lastname . '%';
	}
	if (!empty($email)) {
		$where .= ' AND u.`email` LIKE :email';
		$params[':email'] = '%' . $email . '%';
	}
	if (!empty($phone)) {
		$where .= ' AND u.`phone` LIKE :phone';
		$params[':phone'] = '%' . $phone . '%';
	}
	if (!empty($confirmed) || $confirmed === '0') {
		$where .= ' AND u.`subscr_confirmed` = :confirmed';
		$params[':confirmed'] = $confirmed;
	}
	if (!empty($userType)) {
		$where .= ' AND (';
		$i = 0;
		foreach ($userType as $k => $v) {
			if ($i) {
				$where .= ' OR ';
			}
			$where .= 'u.`user_type` = :user_type' . $k;
			$params[':user_type' . $k] = $v;
			$i++;
		}
		$where .= ')';
	}
	if (!empty($numOrders) || $numOrders === '0') {
		$where .= ' AND (
			SELECT COUNT(id_orders)
			FROM orders AS o
			WHERE o.id_user = u.id_user
		) = :num_orders';
		$params[':num_orders'] = $numOrders;
	}
}

// Comptage du nombre d'utilisateurs (pour la pagination et l'affichage sur page d'accueil admin)
$users = AdminGestionMgr::getUsersWithOrders($params, $where, $order, $way);
$numUsers = count($users);

// Pagination.
define('NUM_PER_PAGE', 10);
$pagination = new Pagination('page');
// Redéfinition des attributs.
$pagination
	->setGoFirst('«')
	->setGoPrevious('‹')
	->setGoNext('›')
	->setGoLast('»')
	//->setPaginationWrapper('<nav aria-label="Page navigation"><ul class="pagination">%s</ul></nav>')
	//->setAvoidDuplicateContent(FALSE)
	->setItemsPerPage(NUM_PER_PAGE)
	->setTotalRows($numUsers);
$paginationPages = $pagination->process();
$limitFrom = $pagination->limitFrom();
$limitTo = $limitFrom + NUM_PER_PAGE;
if ($limitTo > $numUsers) {
	$limitTo = $numUsers;
}
// Requete pour l'affichage de la liste paginée des utilisateurs 
$users = AdminGestionMgr::getUsersWithOrders($params, $where, $order, $way, $limitFrom, NUM_PER_PAGE);



// -------------- EDIT USER --------------
$id = '';
$user_user_type = '';
$user_email = '';
$user_pseudo = '';
$user_password = '';
$user_passwordConf = '';
$errors = [];

// Add user
if (isset($_POST['add-user-btn'])) {
	$user_user_type = !empty($_POST['user_type']) ? $_POST['user_type'] : '';
	$user_email = trim($_POST['email']);
	$user_pseudo = trim($_POST['pseudo']);
	$user_password = trim($_POST['password']);
	$user_passwordConf = trim($_POST['passwordConf']);
	$token = bin2hex(random_bytes(50));

	$sc = uniqid(mt_rand(), true);
	file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/files/tmp/tmp_' . $token . '.dat', $sc);

	$validator = new EmailValidator();

	$pwLength = mb_strlen($user_password) >= 8;
	$pwLowercase = preg_match('/[a-z]/', $user_password);
	$pwUppercase = preg_match('/[A-Z]/', $user_password);
	$pwNumber = preg_match('/[0-9]/', $user_password);
	$pwSpecialchar = preg_match('/[' . preg_quote('-_"%\'*;<>?^`{|}~/\\#=&', '/') . ']/', $user_password);

	if (empty($user_user_type)) {
		$errors[] = 'Type de compte requis';
	}
	if (empty($user_email)) {
		$errors[] = 'E-mail requis';
	} elseif (!$validator->isValid($user_email, new RFCValidation())) {
		$errors[] = 'E-mail invalide';
	} elseif (AuthMgr::emailExists($user_email)) {
		$errors[] = 'Un compte avec cette adresse e-mail existe déjà';
	}
	if (empty($user_pseudo)) {
		$errors[] = 'Pseudo requis';
	} elseif (!preg_match('/^[a-z0-9!#$%&\'*+\/=?_-]{1,50}$/i', $user_pseudo)) {
		$errors[] = 'Le pseudo contient des caractères invalides';
	} elseif (AuthMgr::pseudoExists($user_pseudo)) {
		$errors[] = 'Un compte avec ce pseudo existe déjà';
	}
	if (empty($user_password)) {
		$errors[] = 'Mot de passe requis';
	} elseif (!$pwLength || !$pwLowercase || !$pwUppercase || !$pwNumber || !$pwSpecialchar) {
		$errors[] = 'Le mot de passe ne satisfait pas les conditions (8 caractères et AU MOINS 1 minuscule, 1 majuscule, 1 chiffre, 1 caractère spécial)';
	} elseif (empty($user_passwordConf)) {
		$errors[] = 'Confirmation du mot de passe requise';
	} elseif (strcmp($user_password, $user_passwordConf) !== 0) {
		$errors[] = 'Les mots de passe ne correspondent pas';
	}

	if (empty($errors)) {
		$result = AuthMgr::addUser($user_email, $user_pseudo, $user_password, $token, $user_user_type);

		if (!$result) {
			$_SESSION['message_error'][] = 'L\'inscription a échoué, veuillez réessayer ultérieurement';
		} else {
			// Send confirmation email to user.
			$emailSent = sendMail('user-add.html', [
				'{link_confirm}' => $settings['site_url'] . '/reinitialiser-mot-de-passe?token=' . $token . '&amp;email=' . $user_email . '&amp;sc=' . $sc . '&amp;back=' . urlencode('/mot-de-passe-oublie'),
			], 'Inscription sur ' . $settings['site_name'], $user_email);

			if (!$emailSent) {
				$_SESSION['message_status'][] = 'L\'inscription de l\'utilisateur est prise en compte';
				$_SESSION['message_error'][] = 'L\'envoi de l\'e-mail de confirmation a échoué, veuillez recommencer.';

				header('location: /connexion');
				exit;
			} else {
				$_SESSION['message_status'][] = 'Un lien de confirmation a été adressé à <em>' . $user_email . '</em> pour finaliser son inscription';

				header('location: index.php?action=adminUsers');
				exit;
			}
		}
	}
}

// Update user
if (!empty($_GET['edit']) && is_numeric($_GET['edit'])) {
	$result = AuthMgr::getUserByID($_GET['edit']);

	if (!$result) {
		header('location: /index.php?action=adminUsers');
		exit;
	}
	$id = $result['id_user'];
	$user_email = $result['email'];
	$user_user_type = $result['user_type'];
	$user_first_name = $result['first_name'];
	$user_last_name = $result['last_name'];
	$user_phone = $result['phone'];
}

if (isset($_POST['upd-user-btn'])) {
	$user_user_type = !empty($_POST['user_type']) ? $_POST['user_type'] : '';
	$user_first_name = trim($_POST['first_name']);
	$user_last_name = trim($_POST['last_name']);
	$user_phone = trim($_POST['phone']);
	$id = intval($_POST['id_user']);

	if (empty($user_user_type)) {
		$errors[] = 'Type de compte requis';
	}
	if (!empty($user_first_name) && !preg_match('/^(\w+[\' -]?)+\w+$/i', $user_first_name)) {
		$errors[] = 'Le prénom contient des caractères invalides';
	}
	if (!empty($user_last_name) && !preg_match('/^(\w+[\' -]?)+\w+$/i', $user_last_name)) {
		$errors[] = 'Le nom contient des caractères invalides';
	}
	if (!empty($user_phone) && !preg_match('/^[+0-9. ()-]*$/i', $user_phone)) {
		$errors[] = 'Le numéro de téléphone n\'est pas valide';
	}

	if (empty($errors)) {
		$result = AuthMgr::updateUser(['user_type' => $user_user_type, 'first_name' => $user_first_name, 'last_name' => $user_last_name, 'phone' => $user_phone], $id);

		if (!$result) {
			$_SESSION['message_error'][] = 'La mise à jour a échoué, veuillez réessayer ultérieurement';
		} else {
			// Send confirmation email to user.
			$emailSent = sendMail('user-upd.html', [
				'{user_type}' => $settings['accounts'][$user_user_type],
				'{first_name}' => $user_first_name,
				'{last_name}' => $user_last_name,
				'{phone}' => $user_phone,
			], 'Modification de vos informations personnelles sur ' . $settings['site_name'], $user_email);

			$_SESSION['message_status'][] = 'Le compte <em>' . $user_email . '</em> a été mis à jour';

			header('location: index.php?action=adminUsers');
			exit;
		}
	}
}

// Resend confirmation link to unconfirmed users (action icone)
if (!empty($_GET['resend-confirmation-link'])) {
	$id = intval($_GET['resend-confirmation-link']);
	$tUser = AuthMgr::getUserByID($id);

	if (!$tUser) {
		header('location: /index.php?action=adminUsers');
		exit;
	}

	sendMail('signup.html', [
		'{link_confirm}' => $settings['site_url'] . '/email-verification?token=' . $tUser['secure_key'] . '&email=' . $tUser['email'],
	], 'Confirmation de votre compte sur ' . $settings['site_name'], $tUser['email']);

	$_SESSION['message_status'][] = 'E-mail de confirmation envoyé à l\'adresse <em>' . $tUser['email'] . '</em>';

	header('location: /index.php?action=adminUsers');
	exit;
}

// Delete user
if (!empty($_GET['del'])) {
	AuthMgr::deleteUser(intval($_GET['del']));
	$_SESSION['message_status'][] = 'Utilisateur supprimé';
	header('location: /index.php?action=adminUsers');
	exit;
}
