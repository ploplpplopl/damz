<?php

// TODO regrouper les use dans la classe Validate en instanciant la classe EmailValidator
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

require_once 'controllers/sendEmails.php';

$contact_email = '';
$contact_name = '';
$contact_message = '';
$email_to = $settings['mail-contact-form'];
// $from = 'contact@dossier-rapide.fr';
// $from = 'from@copifac.fr';
// $replyTo = 'replyTo@copifac.fr';
// date_default_timezone_set('Europe/Paris');
// $date = date("d-m-Y à H:i");
$errors = [];

if (isset($_POST['contact-btn'])) {
    $contact_email = trim($_POST['contact-email']);
    $contact_name = trim($_POST['contact-name']);
    $contact_message = trim($_POST['contact-message']);

    $validator = new EmailValidator();

    if (empty($contact_email)) {
        $errors[] = 'E-mail requis';
    } elseif (!$validator->isValid($contact_email, new RFCValidation())) {
        $errors[] = 'E-mail invalide';
    }
    if (empty($contact_name)) {
        $errors[] = 'Nom et prénom requis';
    } elseif (!valid_donnees($contact_name)) {
        $errors[] = 'Votre nom contient des caractères invalides';
    }
    if (empty($contact_message)) {
        $errors[] = 'Message requis';
    } elseif (!valid_donnees($contact_message)) {
        // TODO essai avec balise <script> : c'est supprimé du message mais ca ne déclanche pas d'erreur
        $errors[] = 'Votre message contient des caractères invalides';
    }

    if (empty($errors)) {
        
        // Send email.
        $emailSent = sendMail('contact.html', [
            '{site_url}' => $settings['site_url'],
            '{contact_email}' => $contact_email,
            '{contact_name}' => $contact_name,
            '{contact_message}' => nl2br($contact_message),
            '{contact_date}' => date("d-m-Y à H:i"),
        ], 'Demande d\'information sur ' . $settings['site_name'], $email_to, NULL, $contact_email);

        if (!$emailSent) {
            $_SESSION['message_error'][] = 'L\'envoi de votre message a échoué. Vous pouvez essayer d\'envoyer un message à <a href="mailto:' . $email_to . '">' . $email_to . '</a>';
        } else {
            $_SESSION['message_status'][] = 'Votre message a bien été envoyé. Nous vous répondrons dans les meilleurs délais';
            header('location: /contact');
            exit;
        }
    }
}

// TODO en faire une classe Validate::isEmail($contact_email)
function valid_donnees($donnees){
    $donnees = trim($donnees);
    $donnees = stripslashes($donnees);
    $donnees = htmlspecialchars($donnees);
    return $donnees;
}