<?php

if (empty($_SESSION['user']['id_user'])) {
    header('location: /connexion');
	exit;
}

if (!empty($_SESSION['user']['user_type']) && 'admin' != $_SESSION['user']['user_type']) {
    header('location: /');
	exit;
}


require_once 'controllers/adminPaliersController.php';

$css = '<link rel="stylesheet" href="/public/css/admin.css">';

require_once 'views/head.php';
?>

<div class="row">
    <div class="col-12">
        <?php
        switch ($_page) {
            case 'NB':
                echo '<h1>Paliers N&B</h1>';
                echo '<input type="hidden" id="db" value="paliers_nb">';
                break;
            case 'Couleur':
                echo '<h1>Paliers couleur</h1>';
                echo '<input type="hidden" id="db" value="paliers_couleur">';
                break;
            case 'spiplast':
                echo '<h1>Paliers spirales plastique</h1>';
                echo '<input type="hidden" id="db" value="paliers_spiplast">';
                break;
            case 'spimetal':
                echo '<h1>Paliers spirales métalliques</h1>';
                echo '<input type="hidden" id="db" value="paliers_spimetal">';
                break;
            case 'thermo':
                echo '<h1>Paliers reliures thermocollées</h1>';
                echo '<input type="hidden" id="db" value="paliers_thermo">';
                break;
        }
        ?>
        <?php echo displayMessage($errors); ?>
    </div>
</div>

<?php if (isset($_GET['edit'])) { // add/upd 
?>
    <div class="row">
        <div class="col-12">
            <p><a href="?action=adminPaliers<?php echo ucfirst($_page); ?>"><i class="fas fa-long-arrow-alt-left"></i> Annuler</a></p>
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
            <p><a href="?action=adminPaliers<?php echo ucfirst($_page); ?>&amp;edit"><i class="fas fa-plus-circle"></i> Ajouter un palier</a></p>
        </div>
    </div>
<?php } ?>

<div class="row">
    <div class="col-12">
        <?php
        if (empty($paliers)) {
        ?>
            <p>Aucun palier</p>
        <?php
        } else {
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
                            <span class="section"><?php echo $data['prix']; ?>&nbsp;€</span>
                            <div class="actions">
                                <a href="?action=adminPaliers<?php echo ucfirst($_page); ?>&amp;edit=<?php echo $data['id']; ?>" title="Modifier"><i class="fas fa-pen"></i></a>
                                <a href="?action=adminPaliers<?php echo ucfirst($_page); ?>&amp;del=<?php echo $data['id']; ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet élément ?')" title="Supprimer"><i class="fas fa-trash"></i></a>
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
            var db = ';' + $("input#db").val();
			$("#info").load("../controllers/ajaxSortablePaliers.php?" + order + db);
		}
	}).disableSelection();
});
</script>

PHP_JS;

require_once 'views/footer.php';
