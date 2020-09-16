<?php

if (empty($_SESSION['user']['id_user'])) {
    header('location: /connexion');
	exit;
}

if (!empty($_SESSION['user']['user_type']) && 'admin' != $_SESSION['user']['user_type']) {
    header('location: /');
	exit;
}

require_once _ROOT_DIR_ . '/models/AuthMgr.class.php';
require_once _ROOT_DIR_ . '/models/AdminGestionMgr.class.php';

$users = AuthMgr::getAllUsers();
$orders = AdminGestionMgr::getOrders([], 0, '', 'id_orders', 'ASC');
$archives = AdminGestionMgr::getOrders([], 1, '', 'id_orders', 'ASC');

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
	<?php
	// if (!empty($_SESSION['user']['user_type']) && 'admin' == $_SESSION['user']['user_type']) {
	if ('admin' == $_SESSION['user']['user_type']) {
	?>
		<div class="col-md-4 mt-3">
			<p><strong><span class="display-1"><?php echo count($users); ?></span><br> utilisateurs enregistrés</strong><br>
				<a href="/index.php?action=adminUsers">Gérer les utilisateurs</a></p>
		</div>
	<?php
	}
	?>
	<div class="col-md-4 mt-3">
		<p><strong><span class="display-1"><?php echo count($orders); ?></span><br> commandes en cours</strong><br>
			<a href="/index.php?action=adminOrders">Gérer les commandes</a></p>
	</div>
	<div class="col-md-4 mt-3">
		<p><strong><span class="display-1"><?php echo count($archives); ?></span><br> commandes archivées</strong><br>
			<a href="/index.php?action=adminOrdersPast">Voir les archives</a></p>
	</div>
</div>

<?php

require_once 'views/footer.php';
