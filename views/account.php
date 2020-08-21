<?php

require_once 'views/head.php';
?>

<div class="row">
	<div class="col-12">
		<h1>Mon compte</h1>
	</div>
</div>
<div class="row">
	<div class="col-8">
		<p>Infos</p>
	</div>
	<div class="col-4">
		<p><a href="#">Modifier mes infos</a></p>
		<p><a href="#">Supprimer mon compte</a> (un peu caché qd mm, faut pas inciter les users)</p>
	</div>
</div>

<?php

$javascript = <<<PHP_JS
<script src="/public/js/compte_pages.js"></script>

PHP_JS;

require_once 'views/footer.php';
