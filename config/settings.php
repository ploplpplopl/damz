<?php

global $settings;

// Environement : 'dev' ou 'prod'.
$settings['environment'] = 'dev';

// Nom du site.
$settings['site_name'] = 'dossier-rapide.fr';

// URL du site.
$settings['site_url'] = 'dev' == $settings['environment'] ?
	$_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] : 'https://dossier-rapide.fr';

// E-mail : from.
$settings['from'] = 'contact@dossier-rapide.fr';
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

$settings['document'] = [
	'dossier' => [
		'couv_ft' => TRUE,
		'couv_fc' => FALSE,
		'couv_fc_type' => FALSE,
		'dos_ft' => FALSE,
		'dos_fc' => TRUE,
		'dos_fc_type' => 'unprintable',
		'reliure_type' => [
			'thermo',
			'spiplast',
			'spimetal',
		],
	],
	'memoire' => [
		'couv_ft' => TRUE,
		'couv_fc' => TRUE,
		'couv_fc_type' => 'printable',
		'dos_ft' => FALSE,
		'dos_fc' => TRUE,
		'dos_fc_type' => 'unprintable',
		'reliure_type' => [
			'thermo',
			'spiplast',
			'spimetal',
		],
	],
	'these' => [
		'couv_ft' => '?',
		'couv_fc' => TRUE,
		'couv_fc_type' => 'printable',
		'dos_ft' => '?',
		'dos_fc' => TRUE,
		'dos_fc_type' => 'printable',
		'reliure_type' => [
			'thermo',
		],
	],
	'perso' => [
		'couv_ft' => '?',
		'couv_fc' => '?',
		'couv_fc_type' => '?',
		'dos_ft' => '?',
		'dos_fc' => '?',
		'dos_fc_type' => '?',
		'reliure_type' => [
			'thermo',
			'spiplast',
			'spimetal',
		],
	],
];

