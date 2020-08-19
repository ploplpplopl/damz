<?php

require_once 'controllers/sendEmails.php';

$emailSent = sendMail('signup.html', [
	'{site_url}' => $settings['site_url'],
	'{token}' => $_GET['token'],
], 'Inscription sur ' . $settings['site_name'], $_GET['email']);

$_SESSION['message_status'][] = 'Un lien de confirmation vous a été adressé à <em>' . $_GET['email'] . '</em> pour finaliser votre inscription';

header('location: index.php?action=login');
exit;
