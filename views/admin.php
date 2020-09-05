<?php

if (empty($_SESSION['user']['id_user'])) {
    header('location: /connexion');
	exit;
}

// protège accès direct à http://localhost/views/admin.php (views devra etre interdit avec htaccess)
if (
	!empty($_SESSION['user']['user_type']) &&
	!in_array($_SESSION['user']['user_type'], ['admin', 'admprinter'])
) {
    header('location: /deconnexion');
	exit;
}

$css = '<link rel="stylesheet" href="/public/css/admin.css">';

require_once 'views/head.php';
?>

<div class="row">
	<div class="col-12">
		<h1>Administration</h1>
		<?php echo displayMessage(); ?>
	</div>
</div>

<div class="row">
	<div class="col-md-4 mt-3">
		<p><strong><span class="display-1">21</span><br> utilisateurs enregistrés</strong><br>
		<a href="/index.php?action=adminUsers">Gérer les utilisateurs</a></p>
	</div>
	<div class="col-md-4 mt-3">
		<p><strong><span class="display-1">5</span><br> commandes en cours</strong><br>
		<a href="/index.php?action=adminOrders">Gérer les commandes</a></p>
	</div>
	<div class="col-md-4 mt-3">
		<p><strong><span class="display-1">42</span><br> commandes archivées</strong><br>
		<a href="/index.php?action=adminOrdersPast">Voir les archives</a></p>
	</div>
</div>

<?php

require_once 'views/footer.php';
