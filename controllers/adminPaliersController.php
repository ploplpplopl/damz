<?php

require_once _ROOT_DIR_ . '/models/AdminGestionMgr.class.php';

$id = '';
$palier = '';
$prix = '';
$errors = [];

// Récupération des paliers des spirales plastiques.
$paliers = AdminGestionMgr::getPaliers('paliers_' . $_page);



$addUpd = 'add';
if (!empty($_GET['edit']) && is_numeric($_GET['edit'])) {
    $addUpd = 'upd';
    $result = AdminGestionMgr::getPalierById('paliers_' . $_page, $_GET['edit']);
    $id = $result['id'];
    $palier = $result['palier'];
    $prix = $result['prix'];
}

if (isset($_POST['edit-btn'])) {
    $palier = trim($_POST['palier']);
    $prix = str_replace(',', '.', trim($_POST['prix']));

    if (empty($palier)) {
        $errors[] = 'Palier requis';
    } elseif (!is_numeric($palier)) {
        $errors[] = 'Le palier doit être un nombre entier';
    }
    if (empty($prix)) {
        $errors[] = 'Prix requis';
    } elseif (!is_numeric($prix)) {
        $errors[] = 'Le prix doit être un nombre décimal';
    }

    if (empty($errors)) {
        if ('add' == $addUpd) {
            $max = AdminGestionMgr::getPalierPositionMax('paliers_' . $_page);
            $result = AdminGestionMgr::setNewPalier('paliers_' . $_page, $palier, $prix, $max['pos']);
        } else {  // update : $addUpd = 'upd'
            $result = AdminGestionMgr::updatePalier('paliers_' . $_page, $palier, $prix, $id);
        }
        if ($result) {
            $_SESSION['message_status'][] = 'add' == $addUpd ? 'Palier ajouté' : 'Palier modifié';
        }
        header('location: index.php?action=admin' . ucfirst($_page));
        exit;
    }
}

if (!empty($_GET['del']) && is_numeric($_GET['del'])) {
    AdminGestionMgr::delPalier('paliers_' . $_page, intval($_GET['del']));
    $_SESSION['message_status'][] = 'Palier supprimé';
    header('location: index.php?action=admin' . ucfirst($_page));
    exit;
}
