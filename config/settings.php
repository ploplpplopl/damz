<?php

$settings = [];

// Environement : 'dev' ou 'prod'.
$settings['environment'] = 'dev';

// Nom du site.
$settings['site_name'] = 'Coprafi';

// URL du site.
$settings['site_url'] = 'dev' == $settings['environment'] ?
	$_SERVER['DOCUMENT_ROOT'] : 'https://coprafi.com';

// E-mail : from.
$settings['from'] = 'contact@coprafi.com';
// E-mail : reply-to.
$settings['reply-to'] = 'no-reply@coprafi.com';
// E-mail : smtp.
$settings['smtp'] = 'smtp.gmail.com';
// E-mail : port.
$settings['port'] = 465;
// E-mail : ssl.
$settings['ssl'] = 'ssl';
// E-mail : username.
$settings['mail-username'] = 'damien.thoorens@gmail.com';
// E-mail : password.
$settings['mail-password'] = 'ljkhsdfl354sd';




