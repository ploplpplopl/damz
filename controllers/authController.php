<?php
session_start();


require_once 'models/AuthMgr.class.php';   // TODO verifier les chemins (relatif, absolu?)
require_once 'controllers/sendEmails.php';

$firstname = "";
$lastname = "";
$email = "";
$phone = "";
$pseudo = "";
$errors = [];

// --------------- SIGN UP USER ---------------
if (isset($_POST['signup-btn'])) {
    if (empty(trim($_POST['firstname']))) {
        $errors['firstname'] = 'Prénom requis';
    }
    if (empty(trim($_POST['lastname']))) {
        $errors['lastname'] = 'Nom requis';
    }
    if (empty(trim($_POST['email']))) {
        $errors['email'] = 'Email requis';
    }
    if (empty(trim($_POST['phone']))) {
        $errors['phone'] = 'Téléphone requis';
    }
    if (empty(trim($_POST['pseudo']))) {
        $errors['pseudo'] = 'Pseudo requis';
    }
    if (empty($_POST['password'])) {
        $errors['password'] = 'Mot de passe requis';
    }
    if (isset($_POST['password']) && $_POST['password'] !== $_POST['passwordConf']) {
        $errors['passwordConf'] = 'Les mots de passe ne sont pas identiques';
    }

    // TODO ajouter des tests de validité sur les champs (email, tel, complexité mdp...) en PHP et JS

    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $pseudo = htmlspecialchars(trim($_POST['pseudo']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); //encrypt password
    $token = bin2hex(random_bytes(50)); // generate unique token

    // Check if email already exists in DB
    $result = AuthMgr::emailExists($email);
    if ($result) {
        $errors['email'] = "Email already exists";
        // TODO : verif que ca s'arrete bien là ($_SESSION)
    }

    // Check if pseudo already exists in DB
    $result = AuthMgr::pseudoExists($pseudo);
    if ($result) {
        $errors['pseudo'] = "Pseudo already exists";
        // TODO : AJAX pour vérifier avant validation du form (onkeyup with throttle)
    }

    // insert user into DB
    if (count($errors) === 0) {
        $result = AuthMgr::signup($firstname, $lastname, $email, $phone, $pseudo, $password, $token);

        if ($result) {
            // send verification email to user
            if (sendVerificationEmail($email, $token)) {
                $errors = [];
                // TODO redirection sur la page où les messages ($_SESSION['message']) seront affichés
                header('location: index.php?action=accueil');
            } else {
                $errors = ['impossible d\'envoyer le mail'];   // TODO supprimer le if et les log d erreur
                $_SESSION['message'] = "impossible d\'envoyer le mail";
                header('location: index.php?action=accueil');
            }
        } else {
            $_SESSION['error_msg'] = "Database error: Could not register user";
        }
    }
}

// --------------- LOGIN ---------------
if (isset($_POST['login-btn'])) {
    // print_r($_POST);
    if (empty($_POST['pseudo'])) {
        $errors['pseudo'] = 'Pseudo requis';
    }
    if (empty($_POST['password'])) {
        $errors['password'] = 'Mot de passe requis';
    }
    $pseudo = htmlspecialchars($_POST['pseudo']);  // sécurisation du pseudo
    $password = $_POST['password'];


    if (count($errors) === 0) {
        if (AuthMgr::checkLogin($pseudo, $password)) {
            // redirection sur la page où les messages ($_SESSION['message']) seront affichés
            header('location: /dossier-rapide/index.php?action=accueil');
        } else { // if password does not match
            $errors['login_fail'] = "Wrong pseudo / password";
        }
    }
}

// --------------- LOGOUT ---------------
if (isset($_GET['action']) && $_GET['action'] == 'logout') {

    AuthMgr::disconnectUser();
    header('location: index.php?action=accueil');

    // exit();
}

// TODO vider les enregistrements (token) non confirmés après 15 minutes