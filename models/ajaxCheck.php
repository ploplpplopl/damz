<?php

require_once __DIR__ .'/AuthMgr.class.php';

$response = '';
if (!empty($_GET['pseudo'])) {
    if (AuthMgr::pseudoExists($_GET['pseudo'])) {
        $response = "<span id='pseudo_span' style='color: red;'>Pseudo non disponible</span>";
    }
    echo $response;
    exit;
}
if (!empty($_GET['email'])) {
    if (AuthMgr::emailExists($_GET['email'])) {
        $response = "<span id='email_span' style='color: red;'>Adresse e-mail non disponible</span>";
    }
    echo $response;
    exit;
}
