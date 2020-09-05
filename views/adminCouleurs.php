<?php

if (empty($_SESSION['user']['id_user'])) {
    header('location: /connexion');
	exit;
}
// protège accès direct à http://localhost/views/admin.php (views devra etre interdit avec htaccess)
if (!empty($_SESSION['user']['user_type']) && 'admin' != $_SESSION['user']['user_type']) {
    header('location: /deconnexion');
	exit;
}


require_once 'controllers/adminCouleursController.php';
// $colors = getCouleurs();

$css = '<link rel="stylesheet" href="/public/css/admin.css">';

require_once 'views/head.php';
?>

<div class="row">
	<div class="col-12">
		<h1>Couleurs des feuilles cartonnées</h1>
		<?php echo displayMessage($errors); ?>
	</div>
</div>

<?php if (isset($_GET['edit'])) { // add/upd ?>
<div class="row">
	<div class="col-12">
		<p><a href="?action=adminCouleurs"><i class="fas fa-long-arrow-alt-left"></i> Annuler</a></p>
	</div>
</div>
<form action="" method="post">
	<div class="row">
		<div class="col-md-3">
			<p>
				<label for="color">Couleur</label><br>
				<input type="text" name="text" id="color" value="<?php echo htmlentities($text, ENT_QUOTES); ?>">
			</p>
		</div>
		<div class="col-md-3">
			<p>
				<label for="hex">Code</label><br>
				<input type="text" name="hex" id="hex" value="<?php echo htmlentities($hex, ENT_QUOTES); ?>">
			</p>
		</div>
		<div class="col-md-2">
			<p>
				<label for="printable">Imprimable</label><br>
				<input type="checkbox" name="printable" id="printable" value="1"<?php echo ($printable == 1 ? ' checked' : ''); ?>>
			</p>
		</div>
		<div class="col-md-2">
			<p>
				<label for="unprintable">Non-impr.</label><br>
				<input type="checkbox" name="unprintable" id="unprintable" value="1"<?php echo ($unprintable == 1 ? ' checked' : ''); ?>>
			</p>
		</div>
		<div class="col-md-2">
			<p>
				<label class="d-none d-md-block">&nbsp;</label><br class="d-none d-md-block">
				<input class="full-width" type="submit" name="edit-btn" value="Enregistrer">
			</p>
		</div>
	</div>
</form>
<?php } else { ?>
<div class="row">
	<div class="col-12">
		<p><a href="?action=adminCouleurs&amp;edit"><i class="fas fa-plus-circle"></i> Ajouter une couleur</a></p>
	</div>
</div>
<?php } ?>

<div class="row">
	<div class="col-12">
<?php
if (empty($colors)){
?>
		<p>Aucune couleur</p>
<?php
}
else {
?>
		<div class="drag">
			<div id="info"></div>
			<ul id="draggable">
<?php
	foreach ($colors as $data) {
?>
				<li id="listItem_<?php echo $data['id_dossier_color']; ?>">
					<span class="handle">≡<i class="fas fa-arrows-alt-v"></i>≡</span>
					<span class="section-medium"><?php echo $data['text']; ?></span>
					<span class="section-medium"><span class="picked-color" style="background:#<?php echo $data['hex']; ?>"></span> <?php echo $data['hex']; ?></span>
					<span class="section-small"><span title="Imprimable">Impr.</span> <?php echo ($data['printable'] ? '✔️' : '❌'); ?></span>
					<span class="section-small"><span title="Non-imprimable">Non-impr.</span> <?php echo ($data['unprintable'] ? '✔️' : '❌'); ?></span>
					<div class="actions">
						<a href="?action=adminCouleurs&amp;edit=<?php echo $data['id_dossier_color']; ?>" title="Modifier"><i class="fas fa-pen"></i></a>
						<a href="?action=adminCouleurs&amp;del=<?php echo $data['id_dossier_color']; ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet élément ?')" title="Supprimer"><i class="fas fa-trash"></i></a>
					</div>
				</li>
<?php
	}
?>
			</ul>
			<div class="clear"></div>
		</div><!-- .drag -->
<?php
}
?>
	</div><!-- .col -->
</div><!-- .row -->

<?php

$javascript = <<<PHP_JS
<script src="/public/js/jquery-ui.min.js"></script>
<script src="/public/js/jqColorPicker.min.js"></script>
<script>
$(function(){
	$("#draggable").sortable({
		handle: ".handle",
		containment: ".drag",
		update: function(){
			var order = $("#draggable").sortable("serialize");
			$("#info").load("../controllers/ajaxSortableCouleurs.php?" + order);
		}
	}).disableSelection();
	$('#hex').colorPicker({
		opacity: false,
		renderCallback: function(\$elm, toggled) {
			\$elm.val('#' + this.color.colors.HEX);
		}
	});
});
</script>

PHP_JS;

require_once 'views/footer.php';
