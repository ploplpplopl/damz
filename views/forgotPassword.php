<?php

require_once 'controllers/authController.php';

AuthMgr::disconnectUser();

require_once 'views/head.php';
?>

<div class="row">
	<div class="col-12">
		<h1>Réinitialisation de mot de passe</h1>
		<?php echo displayMessage($errors); ?>
		<form action="" method="post">
			<div class="form-group">
				<label for="email">Adresse e-mail</label>
				<input type="email" name="email" id="email" class="form-control" value="<?php echo htmlentities($email, ENT_QUOTES); ?>" required="required" pattern="[a-zA-Z0-9](\w\.?)*[a-zA-Z0-9]@[a-zA-Z0-9]+\.[a-zA-Z]{2,6}">
			</div>
			<button type="submit" name="forgot-password-btn" class="btn btn-primary">Valider</button>
		</form>
	</div>
</div>

<?php
require_once 'views/footer.php';
