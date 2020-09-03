<?php

require_once _ROOT_DIR_ . '/models/dao/DbConnection.class.php';
require_once _ROOT_DIR_ . '/models/AdminGestionMgr.class.php';


// Récupération des couleurs.
$colors = AdminGestionMgr::getColors();

$id = '';
$text = '';
$hex = '';
$printable = '';
$unprintable = '';
$errors = [];

$addUpd = 'add';
if (!empty($_GET['edit']) && is_numeric($_GET['edit'])) {
	$addUpd = 'upd';
	$result = AdminGestionMgr::getColorsByID($_GET['edit']);
	$id = $result['id_dossier_color'];
	$text = $result['text'];
	$hex = $result['hex'];
	$printable = $result['printable'];
	$unprintable = $result['unprintable'];
}

if (isset($_POST['edit-btn'])) {
	$text = trim($_POST['text']);
	$hex = str_replace('#', '', strtolower(trim($_POST['hex'])));
	$printable = !empty($_POST['printable']) ? 1 : 0;
	$unprintable = !empty($_POST['unprintable']) ? 1 : 0;

    if (empty($text)) {
        $errors[] = 'Couleur requise';
    }
    if (empty($hex)) {
        $errors[] = 'Code couleur requis';
    }
	elseif (!preg_match('/([a-f0-9]){6}/', $hex)) {
        $errors[] = 'Le code couleur doit être un nombre hexadécimal';
	}
	if (empty($printable) && empty($unprintable)) {
		$errors[] = 'Cocher au moins une case';
	}
	
	if (empty($errors)) {
		$dbh = DbConnection::getConnection('administrateur');
		if ('add' == $addUpd) {
			$stmt = $dbh->query('SELECT MAX(position) + 1 AS pos FROM dossier_color');
			$stmt->execute();
			$max = $stmt->fetch(PDO::FETCH_ASSOC);
			
			$stmt = $dbh->prepare('INSERT INTO dossier_color (text, hex, printable, unprintable, position) VALUES (:text, :hex, :printable, :unprintable, :position)');
			$stmt->bindParam(':text', $text, PDO::PARAM_STR);
			$stmt->bindParam(':hex', $hex, PDO::PARAM_STR);
			$stmt->bindParam(':printable', $printable, PDO::PARAM_INT);
			$stmt->bindParam(':unprintable', $unprintable, PDO::PARAM_INT);
			$stmt->bindParam(':position', $max['pos'], PDO::PARAM_INT);
		}
		else {
			$stmt = $dbh->prepare('UPDATE dossier_color SET text = :text, hex = :hex, printable = :printable, unprintable = :unprintable WHERE id_dossier_color = :id');
			$stmt->bindParam(':text', $text, PDO::PARAM_STR);
			$stmt->bindParam(':hex', $hex, PDO::PARAM_STR);
			$stmt->bindParam(':printable', $printable, PDO::PARAM_INT);
			$stmt->bindParam(':unprintable', $unprintable, PDO::PARAM_INT);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		}
		$result = $stmt->execute();
		$stmt->closeCursor();
		DbConnection::disconnect();
		if ($result) {
			$_SESSION['message_status'][] = 'add' == $addUpd ? 'Couleur ajoutée' : 'Couleur modifiée';
		}
		header('location: index.php?action=adminCouleurs');
		exit;
	}
}

if (!empty($_GET['del']) && is_numeric($_GET['del'])) {
	$dbh = DbConnection::getConnection('administrateur');
	$stmt = $dbh->prepare('DELETE FROM dossier_color WHERE id_dossier_color = :id');
	$stmt->bindParam(':id', $_GET['del'], PDO::PARAM_INT);
	$stmt->execute();
	$stmt->closeCursor();
	DbConnection::disconnect();
	$_SESSION['message_status'][] = 'Couleur supprimée';
	header('location: index.php?action=adminCouleurs');
	exit;
}
