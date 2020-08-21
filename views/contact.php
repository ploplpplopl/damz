<?php

require_once 'views/head.php';
?>

<div class="row">
	<div class="col-12">
		<h1>Contact</h1>
	</div>
</div>
<div class="row">
	<div class="col-6">
		<p>Coordonnées</p>
	</div>
	<div class="col-6">
		<p>Formulaire</p>
	</div>
</div>

<?php

$javascript = <<<PHP_JS
<script src="/public/js/compte_pages.js"></script>

PHP_JS;

require_once 'views/footer.php';
