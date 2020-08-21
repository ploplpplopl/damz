<?php

if (empty($_SESSION['user']['id_user'])) {
    header('location: index.php?action=login');
	exit;
}

// protège accès direct à http://localhost/views/admin.php (views devra etre interdit avec htaccess)
if (
	!empty($_SESSION['user']['user_type']) &&
	!in_array($_SESSION['user']['user_type'], ['admin', 'admprinter'])
) {
    header('location: /index.php?action=logout');
	exit;
}
$menu = 'admprinter' == $_SESSION['user']['user_type'] ? 'menuAdmPrinter' : 'menuAdmin';

$css = '<link rel="stylesheet" href="/public/css/admin.css">';

require_once 'views/head.php';
require_once 'views/' . $menu . '.php';
?>

<div class="row">
	<div class="col-12">
		<h1>Historique des commandes</h1>
		<?php echo displayMessage(); ?>
	</div>
</div>

<ul class="nav nav-tabs">
	<li class="nav-item"><a class="nav-link" href="/index.php?action=adminOrders">Commandes</a></li>
	<li class="nav-item"><a class="nav-link active" href="/index.php?action=adminOrdersPast">Historique</a></li>
</ul>

<div class="row">
	<div class="col-12">
		<table class="table table-striped table-bordered table-hover table-sm table-responsive">
			<thead class="thead-light">
				<tr>
					<th>Date</th>
					<th>Nom</th>
					<th>Prénom</th>
					<th>Coordonnées</th>
					<th>Commande</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>26/07/2020</td>
					<td>Weiner</td>
					<td>Anthony</td>
					<td>13 rue Laptop - Caen<br> anthony@weiner.com<br> 06 12 34 57 89</td>
					<td>Dossier 16 pages</td>
					<td class="center">Facture PDF Étiquette Suppr</td>
				</tr>
				<tr>
					<td>26/04/2020</td>
					<td>Dupuis</td>
					<td>Léonard</td>
					<td>24 rue des buissons - Caen<br> leonard@dupuis.com<br> 06 12 34 57 89</td>
					<td>Thèse 16 pages</td>
					<td class="center">Facture PDF Étiquette Suppr</td>
				</tr>
				<tr>
					<td>26/12/2019</td>
					<td>Rosenberg</td>
					<td>Marshall</td>
					<td>1 rue de la CNV - Caen<br> marshall@rosenberg.com<br> 06 12 34 57 89</td>
					<td>Mémoire 53 pages</td>
					<td class="center">Facture PDF Étiquette Suppr</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<?php

require_once 'views/footer.php';
