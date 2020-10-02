<?php

require_once _ROOT_DIR_ . '/models/AdminGestionMgr.class.php';

$id = '';
$palier = '';
$prix = '';
$errors = [];

// Récupération des paliers.
$paliers = AdminGestionMgr::getLevels('paliers_' . strtolower($_page));

$addUpd = 'add';
// Update palier
if (!empty($_GET['edit']) && is_numeric($_GET['edit'])) {
    $addUpd = 'upd';
    $result = AdminGestionMgr::getLevelById('paliers_' . strtolower($_page), $_GET['edit']);
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
        if ('add' == $addUpd) {  // add
            $max = AdminGestionMgr::getLevelPositionMax('paliers_' . strtolower($_page));
            $result = AdminGestionMgr::addLevel('paliers_' . strtolower($_page), $palier, $prix, $max['pos']);
        } else {  // update
            $result = AdminGestionMgr::updateLevel('paliers_' . strtolower($_page), $palier, $prix, $id);
        }
        if ($result) {
            $_SESSION['message_status'][] = 'add' == $addUpd ? 'Palier ajouté' : 'Palier modifié';
        }
        header('location: index.php?action=adminPaliers' . ucfirst($_page));
        exit;
    }
}

if (!empty($_GET['del']) && is_numeric($_GET['del'])) {
    AdminGestionMgr::deleteLevel('paliers_' . strtolower($_page), intval($_GET['del']));
    $_SESSION['message_status'][] = 'Palier supprimé';
    header('location: index.php?action=adminPaliers' . ucfirst($_page));
    exit;
}
