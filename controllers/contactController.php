<?php

// TODO regrouper les use dans la classe Validate en instanciant la classe EmailValidator
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
        $errors[] = 'Prénom Nom requis';
    }
	elseif (!preg_match('/^(\w+[\' -]?)+\w+$/i', $contact_name)) {
        $errors[] = 'Votre nom contient des caractères invalides';
    }
    if (empty($contact_message)) {
        $errors[] = 'Message requis';
    }
	elseif (!preg_match('/^([^<>#{}])*$/i', $contact_message)) {
        $errors[] = 'Votre message contient des caractères invalides';
    }
    if (strcmp($_SESSION['csrf_token'], $_POST['csrf_token']) !== 0) {
        $errors[] = 'Jeton de sécurité invalide, veuillez réessayer';
    }

    if (empty($errors)) {
        // Send email.
        $emailSent = sendMail('contact.html', [
            '{contact_email}' => $contact_email,
            '{contact_name}' => $contact_name,
            '{contact_message}' => nl2br($contact_message),
            '{contact_date}' => date('d-m-Y à H:i'),
        ], 'Demande d\'information sur ' . $settings['site_name'], $settings['mail-contact-form'], NULL, $contact_email);

        if (!$emailSent) {
            $_SESSION['message_error'][] = 'L\'envoi de votre message a échoué, veuillez réessayer ultérieurement.';
        } else {
            $_SESSION['message_status'][] = 'Votre message a bien été envoyé. Nous vous répondrons dans les meilleurs délais.';
            header('location: /contact');
            exit;
        }
    }
}
