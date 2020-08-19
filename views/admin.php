<?php

if (empty($_SESSION['user']['id_user'])) {
    header('location: index.php?action=login');
	exit;
}

// protège accès direct à http://localhost/views/admin.php (views devra etre interdit avec htaccess)
if (!empty($_SESSION['user']['user_type']) && 'admin' != $_SESSION['user']['user_type']) {
    header('location: /index.php?action=logout');
	exit;
}

$css = '<link rel="stylesheet" href="/public/css/admin.css">';

require_once 'views/head.php';
require_once 'views/menuAdmin.php';
?>

<div class="row">
	<div class="col-12">
		<h1>Récap. des trucs sur le site</h1>
		<?php echo displayMessage(); ?>
	</div>
</div>

<div class="row">
	<div class="col-4">
		<p><strong>21 utilisateurs enregistrés</strong><br>
		<a href="">Gérer les utilisateurs</a></p>
	</div>
	<div class="col-4">
		<p><strong>5 commandes en cours</strong><br>
		<a href="">Gérer les commandes</a></p>
	</div>
	<div class="col-4">
		<p><strong>42 commandes archivées</strong><br>
		<a href="">Voir les archives</a></p>
	</div>
</div>

<?php

require_once 'views/footer.php';
