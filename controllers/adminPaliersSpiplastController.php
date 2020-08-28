<?php

require_once _ROOT_DIR_ . '/models/AdminGestionMgr.class.php';

$paliersSpiplast = [];

// Récupération des paliers des spirales plastiques.
$paliersSpiplast = AdminGestionMgr::getPaliers('paliers_spiplast');


$id = '';
$palier = '';
$prix = '';
$errors = [];

$addUpd = 'add';
if (!empty($_GET['edit']) && is_numeric($_GET['edit'])) {
	$addUpd = 'upd';
	$result = AdminGestionMgr::getPalierById('paliers_spiplast', $_GET['edit']);
	$id = $result['id'];
	$palier = $result['palier'];
	$prix = $result['prix'];
}

if (isset($_POST['edit-btn'])) {
	$palier = trim($_POST['palier']);
	$prix = str_replace(',', '.', trim($_POST['prix']));

    if (empty($palier)) {
        $errors[] = 'Palier requis';
    }
	elseif (!is_numeric($palier)) {
        $errors[] = 'Le palier doit être un nombre entier';
	}
    if (empty($prix)) {
        $errors[] = 'Prix requis';
    }
	elseif (!is_numeric($prix)) {
        $errors[] = 'Le prix doit être un nombre décimal';
	}
	
	if (empty($errors)) {
		if ('add' == $addUpd) {
			$max = AdminGestionMgr::getPalierPositionMax('paliers_spiplast');
            $result = AdminGestionMgr::setNewPalier('paliers_spiplast', $palier, $prix, $max['pos']);
		}
		else {  // update : $addUpd = 'upd'
            $result = AdminGestionMgr::updatePalier('paliers_spiplast', $palier, $prix, $id);
		}
		if ($result) {
			$_SESSION['message_status'][] = 'add' == $addUpd ? 'Palier ajouté' : 'Palier modifié';
		}
		header('location: index.php?action=adminSpiplast');
		exit;
	}
}

if (!empty($_GET['del']) && is_numeric($_GET['del'])) {
    AdminGestionMgr::delPalier('paliers_spiplast', $_GET['del']);
	$_SESSION['message_status'][] = 'Palier supprimé';
	header('location: index.php?action=adminSpiplast');
	exit;
}
