<?php

if (empty($_SESSION['user']['id_user'])) {
    header('location: /connexion');
	exit;
}

require_once 'controllers/accountController.php';

require_once 'views/head.php';
?>

<div class="row">
	<div class="col-12">
		<h1>Mon compte</h1>
		<p>Retour à la page <a href="/mon-compte">Mon compte</a>.</p>
		<?php echo displayMessage($errorsAddress); ?>
	</div>
</div>
<div class="row">
	<div class="col-sm-6">
		<h2>Mes adresses</h2>
		<?php if (empty($addresses)) : ?>
			<p>Aucune adresse.</p>
		<?php else : ?>
			<?php foreach ($addresses as $addr) : ?>
				<p>
					<strong><?php echo $addr['addr_label']; ?></strong>&nbsp;:<br>
					<?php echo $addr['addr_name']; ?><br>
					<?php echo $addr['address']; ?><br>
					<?php echo (!empty($addr['address2']) ? $addr['address2'] . '<br>' : ''); ?>
					<?php echo $addr['zip_code']; ?> <?php echo $addr['city']; ?><br>
					<?php echo $addr['country_name']; ?><br>
				</p>
				<p class="mt-0"><small><a href="?action=accountAddresses&amp;edit=<?php echo $addr['id_address']; ?>">Modifier</a>&nbsp;- <a href="?action=accountAddresses&amp;del=<?php echo $addr['id_address']; ?>" class="del-address">Supprimer</a></small></p>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (!empty($_SESSION['tunnel'])) : ?>
			<p class="mt-5"><a class="btn btn-primary" href="<?php echo $_SESSION['tunnel']; ?>">Poursuivre ma commande</a></p>
		<?php endif; ?>
	</div>
	<div class="col-sm-6">
		<h2><?php echo ('upd' == $addUpd ? 'Modifier une adresse' : 'Ajouter une adresse'); ?></h2>
		<form action="" method="post" id="form_adress">
			<div class="form-group">
				<label for="addr_name">Destinataire</label>
				<input type="text" id="addr_name" name="addr_name" class="form-control" value="<?php echo htmlentities($addrName, ENT_QUOTES); ?>" required="required" placeholder="Prénom et nom">
			</div>
			<div class="form-group">
				<label for="address">Adresse</label>
				<input type="text" id="address" name="address" class="form-control" value="<?php echo htmlentities($address, ENT_QUOTES); ?>" required="required" placeholder="№ de voie, rue…">
			</div>
			<div class="form-group">
				<label for="address2">Complément d'adresse (facultatif)</label>
				<input type="text" id="address2" name="address2" class="form-control" value="<?php echo htmlentities($address2, ENT_QUOTES); ?>" placeholder="Résidence, bâtiment, lieu-dit…">
			</div>
			<div class="form-group">
				<label for="zipcode">Code postal</label>
				<input type="text" id="zipcode" name="zipcode" class="form-control" value="<?php echo htmlentities($zipcode, ENT_QUOTES); ?>" required="required">
			</div>
			<div class="form-group">
				<label for="city">Ville</label>
				<input type="text" id="city" name="city" class="form-control" value="<?php echo htmlentities($city, ENT_QUOTES); ?>" required="required">
			</div>
			<div class="form-group">
				<label for="country">Pays</label>
				<select id="country" name="country" class="form-control" required="required">
					<option value="">-- Sélectionner --</option>
					<?php foreach ($countries as $cntry) : ?>
						<option value="<?php echo $cntry['id_country']; ?>" <?php echo ($country == $cntry['id_country'] ? ' selected' : ''); ?>><?php echo $cntry['name']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="form-group">
				<label for="addr_label">Donnez un nom à cette adresse</label>
				<input type="text" id="addr_label" name="addr_label" class="form-control" value="<?php echo htmlentities($addrLabel, ENT_QUOTES); ?>" required="required" placeholder="Domicile, travail…">
			</div>
			<button type="submit" name="user-address-btn" class="btn btn-primary">Enregistrer</button>
		</form>
	</div>
</div>

<?php
require_once 'views/footer.php';
