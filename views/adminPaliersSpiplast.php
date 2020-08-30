<?php

if (empty($_SESSION['user']['id_user'])) {
    header('location: index.php?action=login');
	exit;
}
// protège accès direct à http://localhost/views/xxx.php (views devra etre interdit avec htaccess)
if (!empty($_SESSION['user']['user_type']) && 'admin' != $_SESSION['user']['user_type']) {
    header('location: /index.php?action=logout');
	exit;
}


require_once 'controllers/adminPaliersSpiplastController.php';
$paliers = $paliersSpiplast;

$css = '<link rel="stylesheet" href="/public/css/admin.css">';

require_once 'views/head.php';
?>

<div class="row">
	<div class="col-12">
		<h1>Paliers spirales plastique</h1>
		<?php echo displayMessage($errors); ?>
	</div>
</div>

<?php if (isset($_GET['edit'])) { // add/upd ?>
<div class="row">
	<div class="col-12">
		<p><a href="?action=adminSpiplast"><i class="fas fa-long-arrow-alt-left"></i> Annuler</a></p>
	</div>
</div>
<form action="" method="post">
	<div class="row">
		<div class="col-md-5">
			<p>
				<label for="palier">Palier</label><br>
				<input type="text" name="palier" id="palier" value="<?php echo htmlentities($palier, ENT_QUOTES); ?>">
			</p>
		</div>
		<div class="col-md-5">
			<p>
				<label for="prix">Prix</label><br>
				<input type="text" name="prix" id="prix" value="<?php echo htmlentities($prix, ENT_QUOTES); ?>">
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
		<p><a href="?action=adminSpiplast&amp;edit"><i class="fas fa-plus-circle"></i> Ajouter un palier</a></p>
	</div>
</div>
<?php } ?>

<div class="row">
	<div class="col-12">
<?php
if (empty($paliers)){
?>
		<p>Aucun palier</p>
<?php
}
else {
?>
		<div class="drag">
			<div id="info"></div>
			<ul id="draggable">
<?php
	foreach ($paliers as $data) {
?>
				<li id="listItem_<?php echo $data['id']; ?>">
					<span class="handle">≡<i class="fas fa-arrows-alt-v"></i>≡</span>
					<span class="section"><?php echo $data['palier']; ?></span>
					<span class="section"><?php echo $data['prix']; ?></span>
					<div class="actions">
						<a href="?action=adminSpiplast&amp;edit=<?php echo $data['id']; ?>" title="Modifier"><i class="fas fa-pen"></i></a>
						<a href="?action=adminSpiplast&amp;del=<?php echo $data['id']; ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet élément ?')" title="Supprimer"><i class="fas fa-trash"></i></a>
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
<script>
$(function(){
	$("#draggable").sortable({
		handle: ".handle",
		containment: ".drag",
		update: function(){
			var order = $("#draggable").sortable("serialize");
			$("#info").load("../controllers/ajaxSortablePaliersNB.php?" + order);
		}
	}).disableSelection();
});
</script>

PHP_JS;

require_once 'views/footer.php';
