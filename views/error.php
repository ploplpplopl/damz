<?php

switch ($_GET['e']) {
	case 404:
		header('HTTP/1.0 404 Not Found');
		$sTitre .= ' 404';
		$h1 = 'Page non trouvée';
		break;
	case 403:
		$sTitre .= ' 403';
		$h1 = 'Accès refusé';
		break;
	case 500:
		$sTitre .= ' 500';
		$h1 = 'Erreur serveur';
		break;
}

require_once 'views/head.php';
?>

<div class="row">
	<div class="col-12">
		<h1><?php echo $h1; ?></h1>
		<h2>😕</h2>
		<p>Retour à la <a href="/">page d'accueil</a>.</p>
	</div>
</div>

<?php
require_once 'views/footer.php';
