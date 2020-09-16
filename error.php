<?php

$e = (int) $_GET['e'];
$css = $javascript = '';

switch ($e) {
	case 404:
		$sTitre = 'Erreur 404';
		header('HTTP/1.0 404 Not Found');
		require('views/404.php');
		break;
		
	case 403:
		$sTitre = 'Erreur 403';
		require('views/403.php');
		break;
		
	default:
		$sTitre = 'Erreur';
		require_once 'views/head.php';
		echo '
<div class="row">
	<div class="col-12">
		<h1>Erreur inconnnue</h1>
		<h2>😕</h2>
		<p>Retour à la <a href="/">page d\'accueil</a>.</p>
	</div>
</div>
';
		require_once 'views/footer.php';
		break;
}