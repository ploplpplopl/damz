<?php

require_once 'controllers/sendEmails.php';

$emailSent = sendMail('signup.html', [
	'{link_confirm}' => $settings['site_url'] . '/email-verification?token=' . $_GET['token'] . '&email=' . $_GET['email'],
], 'Inscription sur ' . $settings['site_name'], $_GET['email']);

$_SESSION['message_status'][] = 'Un lien de confirmation vous a été adressé à <em>' . $_GET['email'] . '</em> pour finaliser votre inscription';

header('location: /connexion');
exit;
