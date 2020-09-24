<?php

if (empty($_SESSION['user']['id_user'])) {
	header('location: /connexion');
	exit;
}

$css = '<link rel="stylesheet" href="/public/css/password.css">';

require_once 'controllers/accountController.php';

require_once 'views/head.php';
?>

<div class="row">
	<div class="col-12">
		<h1>Mon compte</h1>
	</div>
</div>
<div class="row">
	<div class="col-sm-6">
		<h2>Modifier mes informations personnelles</h2>
		<?php echo displayMessage($errors); ?>
		<form action="" method="post">
			<div class="form-group">
				<label for="email">Adresse e-mail</label>
				<input type="email" id="email" name="email" class="form-control" value="<?php echo htmlentities($email, ENT_QUOTES); ?>" required="required" pattern="[a-zA-Z0-9](\w\.?)*[a-zA-Z0-9]@[a-zA-Z0-9]+\.[a-zA-Z]{2,6}">
			</div>
			<div class="form-group">
				<label for="pseudo">Pseudo</label>
				<input type="text" id="pseudo" name="pseudo" class="form-control" value="<?php echo htmlentities($pseudo, ENT_QUOTES); ?>" required="required">
			</div>
			<div class="form-group">
				<label for="firstname">Prénom</label>
				<input type="text" id="firstname" name="firstname" class="form-control" value="<?php echo htmlentities($firstname, ENT_QUOTES); ?>" required="required">
			</div>
			<div class="form-group">
				<label for="lastname">Nom</label>
				<input type="text" id="lastname" name="lastname" class="form-control" value="<?php echo htmlentities($lastname, ENT_QUOTES); ?>" required="required">
			</div>
			<div class="form-group">
				<label for="phone">Téléphone</label>
				<input type="text" id="phone" name="phone" class="form-control" value="<?php echo htmlentities($phone, ENT_QUOTES); ?>" aria-describedby="phoneHelp">
				<small id="phoneHelp" class="form-text text-muted">Pour vous joindre en cas de problème avec votre commande.</small>
			</div>
			<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
			<button type="submit" name="user-info-btn" class="btn btn-primary">Enregistrer</button>
		</form>
		<?php if (!empty($_SESSION['tunnel'])) : ?>
			<p class="mt-5"><a class="btn btn-primary" href="<?php echo $_SESSION['tunnel']; ?>">Poursuivre ma commande</a></p>
		<?php endif; ?>
	</div>
	<div class="col-sm-6">
		<h2>Mes adresses</h2>
		<?php if (empty($addresses)) : ?>
			<p>Aucune adresse</p>
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
			<?php endforeach; ?>
		<?php endif; ?>
		<div class="text-right mb-4"><a href="/mon-compte/mes-adresses">Gérer mes adresses</a></div>
		<h2>Modifier mon mot de passe</h2>
		<?php echo displayMessage($errorsPassword); ?>
		<form action="" method="post">
			<div class="form-group">
				<label for="password">Mot de passe</label>
				<input type="password" id="password" name="password" class="form-control" value="" required="required">
			</div>
			<div class="form-group">
				<label for="passwordC">Confirmation du mot de passe</label>
				<input type="password" id="passwordC" name="passwordConf" class="form-control" value="" required="required">
			</div>
			<div id="message">
				<p><b>Le mot de passe doit contenir&nbsp;</b></p>
				<p id="letter" class="invalid">Une lettre <b>minuscule</b></p>
				<p id="capital" class="invalid">Une lettre <b>majuscule</b></p>
				<p id="number" class="invalid">Un <b>nombre</b></p>
				<p id="specialchar" class="invalid">Un <b>caractère spécial</b></p>
				<p id="length" class="invalid">Au moins <b>8 caractères</b></p>
			</div>
			<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
			<button type="submit" name="user-password-btn" class="btn btn-primary">Enregistrer</button>
		</form>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<?php
		/*

TODO

- Entrer son mot de passe
- Envoyer un e-mail
- Au clic sur le lien dans l'e-mail : suppression

Trouver une solution pour conserver l'association 'archives de commandes' et 'user'.

*/
		?>
		<p class="text-center mt-5"><a id="delete-account-link" href="#">Supprimer mon compte</a></p>
		<form class="form-inline" id="delete-account-form">
			<p class="h5 mt-5">Vous voulez déjà nous quitter&nbsp;?</p>
			<p><small>Pour confirmer la suppression de votre compte, veuillez entrer votre mot de passe ci-dessous. Vous allez recevoir un e-mail de validation finale de votre demande.<br>
					Vérifiez que l'adresse e-mail de votre compte ci-dessus est valide, sinon votre compte ne pourra pas être effacé.</small></p>
			<p><small class="font-weight-bold">La suppression de votre compte entraînera la perte définitive de toutes vos commandes.</small></p>
			<div class="form-group mt-2 mr-2">
				<label for="password-delete" class="sr-only">Mot de passe</label>
				<input type="password" id="password-delete" name="password-delete" class="form-control" value="" placeholder="Mot de passe" required="required">
			</div>
			<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
			<button type="submit" name="delete-account-btn" class="btn btn-danger mt-2">Supprimer</button>
		</form>
	</div>
</div>

<?php

if (empty($errorsDelete)) {
	$javascript .= '
<script>
$("#delete-account-form").hide();
</script>
';
}
$javascript .= '
<script>
$("#delete-account-link").click(function(e){
	e.preventDefault();
	$(this).closest("p").hide();
	$("#delete-account-form").slideDown();
});
</script>
';

require_once 'views/footer.php';
