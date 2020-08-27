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

require_once 'controllers/adminOrders.php';
$orders = getOrders();

$css = '<link rel="stylesheet" href="/public/css/admin.css">';

require_once 'views/head.php';
?>

<div class="row">
	<div class="col-12">
		<h1>Commandes</h1>
		<?php echo displayMessage(); ?>
	</div>
</div>

<ul class="nav nav-tabs">
	<li class="nav-item"><a class="nav-link active" href="/index.php?action=adminOrders">Commandes</a></li>
	<li class="nav-item"><a class="nav-link" href="/index.php?action=adminOrdersPast">Historique</a></li>
</ul>

<div class="row">
	<div class="col-12">
		<table class="table table-striped table-bordered table-hover table-sm table-responsive mt-3">
			<thead class="thead-light">
				<tr>
					<th class="align-top">Date</th>
					<th class="align-top">Prénom&nbsp;/ nom</th>
					<th class="align-top">Adresse e-mail&nbsp;/ Téléphone</th>
					<th class="align-top">Adresse</th>
					<th class="align-top">Commande</th>
					<th class="align-top">Actions</th>
				</tr>
			</thead>
			<tbody>
<?php if (empty($orders)): ?>
				<tr><td colspan="6">Aucune commande</td></tr>
<?php else: ?>
	<?php foreach ($orders as $order): ?>
				<tr>
					<td><?php echo date('d-m-Y H:i', strtotime($order['date_add'])); ?></td>
					<td>
						<?php echo $order['first_name']; ?><br>
						<?php echo $order['last_name']; ?><br>
					</td>
					<td>
						<?php echo $order['email']; ?><br>
						<?php echo $order['phone']; ?><br>
					</td>
					<td>
						<?php echo $order['address']; ?><br>
						<?php echo (!empty($order['address2']) ? $order['address2'] . '<br>' : ''); ?>
						<?php echo $order['zip_code'] . ' ' . $order['city']; ?><br>
						<?php echo $order['country_name']; ?><br>
					</td>
					<td>
						<?php echo $settings['mapping'][$order['doc_type']]; ?><br>
						<?php echo $order['nb_page']; ?> pages<br>
						<?php echo $order['quantity'], ' ', ($order['quantity'] > 1 ? 'exemplaires' : 'exemplaire'); ?><br>
						<?php echo ($order['rectoverso'] ? 'recto-verso' : 'recto'); ?><br>
					</td>
					<td>
						<a href="#" title="Facture">▢</a>
						<a href="#" title="PDF">▢</a>
						<a href="#" title="Étiquette">▢</a>
						<a href="#" title="Archiver">▢</a>
					</td>
				</tr>
	<?php endforeach; ?>
<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<?php

require_once 'views/footer.php';
