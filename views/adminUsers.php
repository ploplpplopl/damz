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
?>

<div class="row">
	<div class="col-12">
		<h1>Utilisateurs</h1>
		<?php echo displayMessage(); ?>
	</div>
</div>

<?php

require_once 'views/footer.php';
