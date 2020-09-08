<?php

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

require_once 'controllers/sendEmails.php';

$contact_email = '';
$contact_name = '';
$contact_message = '';
$errors = [];

if (isset($_POST['contact-btn'])) {
	$contact_email = trim($_POST['contact-email']);
	$contact_name = trim($_POST['contact-name']);
	$contact_message = trim($_POST['contact-message']);

    $validator = new EmailValidator();

    if (empty($contact_email)) {
        $errors[] = 'E-mail requis';
    }
	elseif (!$validator->isValid($contact_email, new RFCValidation())) {
		$errors[] = 'E-mail invalide';
    }
    if (empty($contact_name)) {
        $errors[] = 'Nom et prénom requis';
    }
    elseif (!preg_match('/^[a-z0-9!#$%&\'*+\/=?_-]{1,50}$/i', $contact_name)) {
        $errors[] = 'Votre pseudo contient des caractères invalides';
    }
    if (empty($contact_message)) {
        $errors[] = 'Message requis';
    }

}