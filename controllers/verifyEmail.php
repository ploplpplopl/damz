<?php

require_once 'models/AuthMgr.class.php';

if (!isset($_GET['token'])) {
    echo 'No token provided!';
}
else {
    if (!AuthMgr::verifyEmail($_GET['token'])) {
        echo 'User not found!';
    }
	else {
        header('location: index.php');
		exit;
    }
}
