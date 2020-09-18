<?php

$css = '<link rel="stylesheet" href="/public/css/password.css">';

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
				<label for="signup-password">Mot de passe</label>
				<input type="password" id="signup-password" name="password" class="form-control" required="required">
			</div>
			<div class="form-group">
				<label for="signup-passwordC">Confirmation du mot de passe</label>
				<input type="password" id="signup-passwordC" name="passwordConf" class="form-control" required="required">
			</div>
			<input type="hidden" name="sc" value="<?php echo (!empty($_GET['sc']) ? htmlentities($_GET['sc'], ENT_QUOTES) : ''); ?>">
			<div id="message">
				<p><b>Le mot de passe doit contenir&nbsp;:</b></p>
				<p id="letter" class="invalid">Une lettre <b>minuscule</b></p>
				<p id="capital" class="invalid">Une lettre <b>majuscule</b></p>
				<p id="number" class="invalid">Un <b>nombre</b></p>
				<p id="specialchar" class="invalid">Un <b>caractère spécial</b></p>
				<p id="length" class="invalid">Au moins <b>8 caractères</b></p>
			</div>
			<button type="submit" name="reset-password-btn" class="btn btn-primary">Réinitialiser</button>
		</form>
	</div>
</div>

<?php
require_once 'views/footer.php';
