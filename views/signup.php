<?php

require_once 'controllers/authController.php';

require_once 'views/head.php';
?>

<div class="row">
	<div class="col-12 form-wrapper auth">
		<h1>Créez votre compte</h1>
		<?php echo displayMessage($errors); ?>
		<form id="signup-form" action="" method="post">
			<div class="form-group">
				<label for="signup-email">Adresse e-mail</label>
				<input type="email" id="signup-email" name="email" class="form-control" value="<?php echo htmlentities($email, ENT_QUOTES); ?>" required="required" pattern="[a-zA-Z0-9](\w\.?)*[a-zA-Z0-9]@[a-zA-Z0-9]+\.[a-zA-Z]{2,6}">
			</div>
			<div class="form-group">
				<label for="signup-pseudo">Pseudo (pour la connexion)</label>
				<input type="text" id="signup-pseudo" name="pseudo" class="form-control" value="<?php echo htmlentities($pseudo, ENT_QUOTES); ?>" required="required">
			</div>
			<div class="form-group">
				<label for="signup-password">Mot de passe</label>
				<input type="password" id="signup-password" name="password" class="form-control" required="required">
			</div>
			<div class="form-group">
				<label for="signup-passwordC">Confirmation du mot de passe</label>
				<input type="password" id="signup-passwordC" name="passwordConf" class="form-control" required="required">
			</div>
			<button type="submit" id="signup-btn" name="signup-btn" class="btn btn-primary">Inscription</button>
		</form>
		<p class="mt-5">Vous avez déjà un compte&nbsp;? <a href="index.php?action=login">Connectez-vous</a></p>
	</div>
</div>

<?php

// TODO validation jQuery
$javascript = '';

require_once 'views/footer.php';
