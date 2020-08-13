<?php
// session_start();
require_once 'models/AuthMgr.class.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $result = AuthMgr::verifyEmail($token);  // TODO mettre un try catch

    if ($result) {
            header('location: index.php');
    } else {
        echo "User not found!";
    }
} else {
    echo "No token provided!";
}
