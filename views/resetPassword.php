<?php

require_once 'controllers/authController.php';

require_once 'views/head.php';
?>

<div class="row">
	<div class="col-12">
		<h1>Réinitialisation de mot de passe</h1>
		<?php echo displayMessage($errors); ?>
		<form action="" method="post">
			<div class="form-group">
				<label for="signup-password">Mot de passe</label>
				<input type="password" id="signup-password" name="password" class="form-control" required="required">
				<span id="span-signup-password"></span>
			</div>
			<div class="form-group">
				<label for="signup-passwordC">Confirmation du mot de passe</label>
				<input type="password" id="signup-passwordC" name="passwordConf" class="form-control" required="required">
				<span id="span-signup-passwordC"></span>
			</div>
			<button type="submit" name="reset-password-btn" class="btn btn-primary">Réinitialiser</button>
		</form>
	</div>
</div>

<?php
require_once 'views/footer.php';
