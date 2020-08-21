<?php

require_once 'controllers/authController.php';

require_once 'views/head.php';
?>

<div class="row">
	<div class="col-12 form-wrapper auth">
		<h1>Créez votre compte</h1>
		<?php echo displayMessage($errors); ?>
		<form id="signup-form" action="" method="post">
			<!--div class="form-group">
				<label for="signup-firstname">Prénom</label>
				<input type="text" id="signup-firstname" name="firstname" class="form-control form-control-lg" value="<?php echo htmlentities($firstname, ENT_QUOTES); ?>" required="required" />
				<span id="span-signup-firstname"></span>
			</div>
			<div class="form-group">
				<label for="signup-lastname">Nom</label>
				<input type="text" id="signup-lastname" name="lastname" class="form-control form-control-lg" value="<?php echo htmlentities($lastname, ENT_QUOTES); ?>" required="required" />
				<span id="span-signup-lastname"></span>
			</div-->
			<div class="form-group">
				<label for="signup-email">Adresse e-mail</label>
				<input type="email" id="signup-email" name="email" class="form-control form-control-lg" value="<?php echo htmlentities($email, ENT_QUOTES); ?>" required="required" pattern="[a-zA-Z0-9](\w\.?)*[a-zA-Z0-9]@[a-zA-Z0-9]+\.[a-zA-Z]{2,6}" />
				<span id="span-signup-email"></span>
			</div>
			<!--div class="form-group">
				<label for="signup-phone">Téléphone</label>
				<input type="text" id="signup-phone" name="phone" class="form-control form-control-lg" value="<?php echo htmlentities($phone, ENT_QUOTES); ?>" required="required" pattern="[0-9+]*(\d(\s)?){9,}\d" />
				<span id="span-signup-phone"></span>
			</div-->
			<div class="form-group">
				<label for="signup-pseudo">Pseudo (pour la connexion)</label>
				<input type="text" id="signup-pseudo" name="pseudo" class="form-control form-control-lg" value="<?php echo htmlentities($pseudo, ENT_QUOTES); ?>" required="required" />
				<span id="span-signup-pseudo"></span>
			</div>
			<div class="form-group">
				<label for="signup-password">Mot de passe</label>
				<input type="password" id="signup-password" name="password" class="form-control form-control-lg" required="required" />
				<span id="span-signup-password"></span>
			</div>
			<div class="form-group">
				<label for="signup-passwordC">Confirmation du mot de passe</label>
				<input type="password" id="signup-passwordC" name="passwordConf" class="form-control form-control-lg" required="required" />
				<span id="span-signup-passwordC"></span>
			</div>
			<div class="form-group">
				<button type="submit" id="signup-btn" name="signup-btn" class="btn">Inscription</button>
			</div>
		</form>
		<p>Vous avez déjà un compte&nbsp;? <a href="index.php?action=login">Connectez-vous</a></p>
	</div>
</div>

<?php

$javascript = '<script src="/public/js/signup_controls.js"></script>';

require_once 'views/footer.php';
