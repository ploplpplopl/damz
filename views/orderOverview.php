<?php

if (empty($_SESSION['user']) || empty($_SESSION['tunnel'])) {
	header('location: /impression');
	exit;
}

require_once _ROOT_DIR_ . '/controllers/orderOverviewController.php';

$fileToPrint = $_SESSION['file_to_print'];
$docType = $_SESSION['file_to_print']['doc_type'];

require_once 'views/head.php';
?>

<div class="row">
	<div class="col-12">
		<h1>Résumé de ma commande</h1>
		<?php echo displayMessage($errors); ?>
		<h2>Fichier à imprimer</h2>
		<table class="table table-sm table-bordered mt-3">
			<tr>
				<th scope="row">Nom du fichier</th>
				<td><a href="/files/uploads/<?php echo $fileToPrint['nom_fichier']; ?>" target="_blank"><?php echo $fileToPrint['nom_fichier_client']; ?></a></td>
			</tr>
			<tr>
				<th scope="row">Nombre de pages total</th>
				<td><?php echo $fileToPrint['nb_page']; ?></td>
			</tr>
			<tr>
				<th scope="row">Nombre de pages noir et blanc</th>
				<td><?php echo $fileToPrint['nb_page_nb']; ?></td>
			</tr>
			<tr>
				<th scope="row">Nombre de pages couleur</th>
				<td><?php echo $fileToPrint['nb_page_c']; ?></td>
			</tr>
			<tr>
				<th scope="row">Type de document</th>
				<td><?php echo $settings['mapping'][$docType]; ?></td>
			</tr>
			<tr>
				<th scope="row">Description</th>
				<td>
					<strong>Couverture</strong>&nbsp;:<br>
					Feuillet transparent&nbsp;: <?php echo (!empty($fileToPrint['couv_ft']) ? 'oui' : 'non'); ?><br>
					Feuille cartonnée&nbsp;: <?php echo (empty($fileToPrint['couv_fc']) ? 'non' : $settings['mapping'][$fileToPrint['couv_fc_type']] . ', couleur&nbsp;: ' . mb_strtolower($mappingColors[$fileToPrint['couv_fc_color']])); ?><br>
					<strong>Dos</strong>&nbsp;:<br>
					Feuillet transparent&nbsp;: <?php echo (!empty($fileToPrint['dos_ft']) ? 'oui' : 'non'); ?><br>
					Feuille cartonnée&nbsp;: <?php echo (empty($fileToPrint['dos_fc']) ? 'non' : $settings['mapping'][$fileToPrint['dos_fc_type']] . ', couleur&nbsp;: ' . mb_strtolower($mappingColors[$fileToPrint['dos_fc_color']])); ?><br>
					<strong>Reliure</strong>&nbsp;:<br>
					<?php echo $settings['mapping'][$fileToPrint['reliure_type']] . ', couleur&nbsp;: ' . mb_strtolower($fileToPrint['reliure_color']); ?><br>
				</td>
			</tr>
			<tr>
				<th scope="row">Options supplémentaires</th>
				<td>
					Nombre d'exemplaires&nbsp;: <?php echo $fileToPrint['quantity']; ?><br>
					Impression recto-verso&nbsp;: <?php echo ($fileToPrint['rectoverso'] ? 'oui' : 'non'); ?><br>
				</td>
			</tr>
			<!--tr>
				<th scope="row"><abbr title="Taxe sur la valeur ajoutée">TVA</abbr></th>
				<td><?php echo str_replace(' ', '&nbsp;', number_format($fileToPrint['tva'], 2, ',', ' ')); ?>&nbsp;€</td>
			</tr-->
			<tr>
				<th scope="row">Prix total <abbr title="Toutes taxes comprises">TTC</abbr></th>
				<td><?php echo str_replace(' ', '&nbsp;', number_format($fileToPrint['total'], 2, ',', ' ')); ?>&nbsp;€ (dont <abbr title="Taxe sur la valeur ajoutée">TVA</abbr> incluse&nbsp;: <?php echo str_replace(' ', '&nbsp;', number_format($fileToPrint['tva'], 2, ',', ' ')); ?>&nbsp;€)</td>
			</tr>
		</table>
	</div>
</div>
<form method="post" action="">
	<div class="row">
		<div class="col-md-4">
			<h2>Adresse de livraison</h2>
			<?php if (empty($userAddresses)) : ?>
				<p>Aucune adresse, en <a href="/mon-compte/mes-adresses">ajouter une</a>.</p>
			<?php else : ?>
				<?php foreach ($userAddresses as $userAddress) : ?>
					<div class="form-group">
						<label for="address_<?php echo $userAddress['id_address']; ?>">
							<input type="radio" name="address" id="address_<?php echo $userAddress['id_address']; ?>" value="<?php echo $userAddress['id_address']; ?>" <?php echo ($address == $userAddress['id_address'] ? ' checked' : ''); ?>>
							<strong><?php echo htmlentities($userAddress['addr_label'], ENT_QUOTES); ?></strong><br>
							<?php
							echo htmlentities($userAddress['addr_name'], ENT_QUOTES) . '<br>';
							echo htmlentities($userAddress['address'], ENT_QUOTES) . '<br>';
							echo (!empty($userAddress['address2']) ? htmlentities($userAddress['address2'], ENT_QUOTES) . '<br>' : '');
							echo htmlentities($userAddress['zip_code'] . ' ' . $userAddress['city'], ENT_QUOTES) . '<br>';
							echo htmlentities($userAddress['country_name'], ENT_QUOTES) . '<br>';
							?>
						</label>
					</div>
				<?php endforeach; ?>
				<p class="mt-3"><a href="/mon-compte/mes-adresses">Gérer mes adresses</a></p>
			<?php endif; ?>
			<h2>Téléphone</h2>
			<?php if (empty($userPhone)) : ?>
				<p>Aucun numéro de téléphone, en <a href="/mon-compte">ajouter un</a>.<br>
					<small class="text-muted">Pour vous joindre en cas de problème avec votre commande.</small></p>
			<?php else : ?>
				<p>Mon numéro de téléphone&nbsp;: <?php echo htmlentities($userPhone, ENT_QUOTES); ?><br>
					<small class="text-muted">Pour vous joindre en cas de problème avec votre commande.</small></p>
				<p class="mt-3"><a href="/mon-compte">Modifier</a></p>
			<?php endif; ?>
		</div>
		<div class="col-md-4">
			<h2>Mode de livraison</h2>
		</div>
		<div class="col-md-4">
			<h2>Moyen de paiement</h2>
			<div class="form-group">
				<label for="payment_paypal">
					<input type="radio" name="payment" id="payment_paypal" value="paypal" <?php echo ('paypal' == $payment ? ' checked' : ''); ?>>
					PayPal<br>
					<img class="mt-2" src="/public/img/paypal.png" alt="PayPal"></label>
			</div>
			<div class="form-group">
				<label for="payment_stripe">
					<input type="radio" name="payment" id="payment_stripe" value="stripe" <?php echo ('stripe' == $payment ? ' checked' : ''); ?>>
					Stripe<br>
					<img class="mt-2" src="/public/img/stripe.png" alt="Stripe"></label>
			</div>
		</div>
	</div>
	<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
	<div class="row">
		<div class="col-12">
			<p class="text-right mt-5"><button type="submit" name="order-btn" class="btn btn-primary">Passer commande</button></p>
		</div>
	</div>
</form>

<?php
require_once 'views/footer.php';
