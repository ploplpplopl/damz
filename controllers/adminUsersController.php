<?php

require_once _ROOT_DIR_ . '/models/AdminGestionMgr.class.php';
require_once _ROOT_DIR_ . '/models/Pagination.class.php';

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

	// TODO Add controls.
	
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

$users = AdminGestionMgr::getUsers($params, $where, $order, $way);
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

$users = AdminGestionMgr::getUsers($params, $where, $order, $way, $limitFrom, NUM_PER_PAGE);

// Edit user.
$id = '';
$user_email = '';
$user_pseudo = '';
$user_user_type = '';
$user_subscr_confirmed = '';
$errors = [];

$addUpd = 'add';
if (!empty($_GET['edit']) && is_numeric($_GET['edit'])) {
	$addUpd = 'upd';
	$stmt = DbConnection::getConnection('administrateur')->prepare('SELECT * FROM user WHERE id_user = :id');
	$stmt->bindParam(':id', $_GET['edit']);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	$id = $result['id_user'];
	//$unprintable = $result['unprintable'];
	$stmt->closeCursor();
	DbConnection::disconnect();
}
