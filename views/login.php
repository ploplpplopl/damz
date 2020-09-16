<?php

require_once 'controllers/authController.php';

AuthMgr::disconnectUser();

if (!empty($_GET['email'])) {
	$tUser = AuthMgr::getUserByEmail($_GET['email']);
	$pseudo = $tUser['pseudo'];
}

require_once 'views/head.php';
?>

<div class="row">
	<div class="col-12 form-wrapper auth login">
		<h1>Connexion</h1>
		<?php echo displayMessage($errors); ?>
		<form action="" method="post">
			<div class="form-group">
				<label>Pseudo ou adresse e-mail</label>
				<input type="text" name="pseudo" class="form-control form-control-lg" value="<?php echo htmlentities($pseudo, ENT_QUOTES); ?>" required>
			</div>
			<div class="form-group">
				<label>Mot de passe</label>
				<input type="password" name="password" class="form-control form-control-lg" required>
				<p class="text-right"><a href="/mot-de-passe-oublie">Mot de passe oublié&nbsp;?</a></p>
			</div>
			<button type="submit" name="login-btn" class="btn btn-primary">Connexion</button>
		</form>
		<p class="mt-5">Vous n'avez pas de compte&nbsp;? <a href="/inscription">Enregistrez-vous</a></p>
	</div>
</div>

<?php

require_once 'views/footer.php';
