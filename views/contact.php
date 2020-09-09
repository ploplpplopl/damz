<?php

require_once 'controllers/contactController.php';

require_once 'views/head.php';
?>

<div class="row">
	<div class="col-12">
		<h1>Contact</h1>
		<?php echo displayMessage($errors); ?>
	</div>
</div>
<div class="row">
	<div class="col-12 form-wrapper auth">
		<form id="contact-form" action="" method="post">
			<div class="form-group">
				<label for="contact-email">Adresse e-mail (pour vous répondre)</label>
				<input type="email" id="contact-email" name="contact-email" class="form-control" value="<?php echo htmlentities($contact_email, ENT_QUOTES); ?>" required="required" pattern="[a-zA-Z0-9](\w\.?)*[a-zA-Z0-9]@[a-zA-Z0-9]+\.[a-zA-Z]{2,6}">
				<!-- <input type="email" id="contact-email" name="contact-email" class="form-control" > -->
			</div>
			<div class="form-group">
				<label for="contact-name">Nom prénom</label>
				<input type="text" id="contact-name" name="contact-name" class="form-control" value="<?php echo htmlentities($contact_name, ENT_QUOTES); ?>" required="required">
				<!-- <input type="text" id="contact-name" name="contact-name" class="form-control"> -->
			</div>
			<div class="form-group">
				<label for="contact-message">Message</label>
				<textarea id="contact-message" name="contact-message" class="form-control" rows="6" cols="25" required="required" placeholder="Votre message"><?php echo htmlentities($contact_message, ENT_QUOTES); ?></textarea><br />
				<!-- <textarea id="contact-message" name="contact-message" class="form-control" placeholder="Votre message"></textarea><br /> -->
			</div>
			<button type="submit" id="contact-btn" name="contact-btn" class="btn btn-primary">Envoyer</button>
		</form>
	</div>
</div>

<?php
/*
$javascript = <<<PHP_JS
<script src="/public/js/contact.js"></script>

PHP_JS;
*/

require_once 'views/footer.php';
