<?php

global $settings;

// Environement : 'dev' ou 'prod'.
$settings['environment'] = 'dev';

// Nom du site.
$settings['site_name'] = 'dossier-rapide.fr';

// URL du site.
// TODO verifier url en prod et supprimer condition ternaire
$settings['site_url'] = 'dev' == $settings['environment'] ?
	$_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] : 'https://dossier-rapide.fr';

// E-mail : from.
$settings['from'] = 'info@dossier-rapide.fr';
// E-mail : reply-to.
$settings['reply-to'] = 'no-reply@dossier-rapide.fr';
// E-mail : smtp.
$settings['smtp'] = 'smtp.gmail.com';
// E-mail : port.
$settings['port'] = 465;
// E-mail : ssl.
$settings['ssl'] = 'ssl';
// E-mail : username.
$settings['mail-username'] = 'copifac.test@gmail.com';
// E-mail : password.
$settings['mail-password'] = 'AzE-46KmL7_PtG';

// Adresse e-mail où seront envoyés les messages du formulaire de contact
// TODO modifier en prod, ex: contact@dossier-rapide.fr. 
$settings['mail-contact-form'] = 'copifac.test@gmail.com';

// Types de compte.
$settings['accounts'] = [
	'admin' => 'Administrateur',
	'admprinter' => 'Imprimeur',
	'user' => 'Utilisateur',
];

// Commande : mapping des valeurs du formulaire en français.
$settings['mapping'] = [
	'dossier' => 'Dossier',
	'memoire' => 'Mémoire',
	'these' => 'Thèse',
	'perso' => 'Personnalisé',
	'printable' => 'imprimable',
	'unprintable' => 'non imprimable',
	'thermo' => 'thermocollée',
	'spiplast' => 'spirales plastique',
	'spimetal' => 'spirales métalliques',
];

