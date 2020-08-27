<?php

require_once _ROOT_DIR_ . '/models/dao/DbConnection.class.php';
//require_once _ROOT_DIR_ . '/models/AuthMgr.class.php';

// Récupération des adresses de l'utilisateur.
function getOrders() {
	$stmt = DbConnection::getConnection('administrateur')->query('
		SELECT o.*, u.first_name, u.last_name, u.email, u.phone,
		a.address, a.address2, a.zip_code, a.city, c.name AS country_name
		FROM orders AS o
		LEFT JOIN user AS u ON o.id_user = u.id_user
		LEFT JOIN address AS a ON u.id_user = a.id_user
		LEFT JOIN country AS c ON a.id_country = c.id_country
		ORDER BY o.date_add ASC
	');
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$stmt->closeCursor();
	DbConnection::disconnect();
	return $result;
}
