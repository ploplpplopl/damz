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
	<div class="col-8">
		<form id="contact-form" action="/contact" method="post">
			<div class="form-group">
				<label for="contact-email">Adresse e-mail (pour vous répondre)</label>
				<input type="email" id="contact-email" name="contact-email" class="form-control" value="<?php echo htmlentities($contact_email, ENT_QUOTES); ?>" required="required" pattern="[a-zA-Z0-9](\w\.?)*[a-zA-Z0-9]@[a-zA-Z0-9]+\.[a-zA-Z]{2,6}">
			</div>
			<div class="form-group">
				<label for="contact-name">Prénom Nom</label>
				<input type="text" id="contact-name" name="contact-name" class="form-control" value="<?php echo htmlentities($contact_name, ENT_QUOTES); ?>" required="required">
			</div>
			<div class="form-group">
				<label for="contact-message">Message</label>
				<textarea id="contact-message" name="contact-message" class="form-control" rows="6" cols="25" required="required"><?php echo htmlentities($contact_message, ENT_QUOTES); ?></textarea>
			</div>
			<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
			<button type="submit" id="contact-btn" name="contact-btn" class="btn btn-primary">Envoyer</button>
		</form>
	</div>
	<div class="col-4">
		<p>
			<span class="h4">Copifac</span><br>
			116 et 106 rue de Geôle<br>
			14000 Caen<br>
		</p>
		<p><i class="fas fa-phone"></i> 02 31 38 98 66</p>
		<iframe style="width:100%;height:300px;border:0;" src="https://www.openstreetmap.org/export/embed.html?bbox=-0.3682029247283936%2C49.18579883109656%2C-0.3640186786651612%2C49.188579191519565&amp;layer=mapnik&amp;marker=49.18719078390223%2C-0.36611080169677734"></iframe>
	</div>
</div>

<?php
/*
$javascript = <<<PHP_JS
<script src="/public/js/contact.js"></script>

PHP_JS;
*/

require_once 'views/footer.php';
