<?php

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
		if ('add' == $addUpd) {
			$max = AdminGestionMgr::getLevelPositionMax('dossier_color');
			$result = AdminGestionMgr::addColor($text, $hex, $printable, $unprintable, $max['pos']);
		}
		else {
			$result = AdminGestionMgr::updateColor($text, $hex, $printable, $unprintable, $id);
		}
		if ($result) {
			$_SESSION['message_status'][] = 'add' == $addUpd ? 'Couleur ajoutée' : 'Couleur modifiée';
		}
		header('location: index.php?action=adminCouleurs');
		exit;
	}
}

if (!empty($_GET['del']) && is_numeric($_GET['del'])) {
	AdminGestionMgr::deleteColor($_GET['del']);
	$_SESSION['message_status'][] = 'Couleur supprimée';
	header('location: index.php?action=adminCouleurs');
	exit;
}
