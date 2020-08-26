<?php

require_once _ROOT_DIR_ . '/controllers/dossierController.php';
require_once 'views/head.php';
$allColors = getAllColors();
$printableColors = getPrintableColors();
$unprintableColors = getUnprintableColors();

?>

<div class="row">
	<div class="col-12 home-wrapper">
		<h1>Imprimez votre document</h1>
		<?php echo displayMessage($errors); ?>

		<form id="formDossier" method="post" action="traitement.php">
			<fieldset>
				<!-- upload du document et détection du nombre de pages couleurs/N&B via le script compte_pages.js-->
				<legend>Téléchargez le fichier PDF à imprimer</legend>
				<div class="custom-file">
					<input type="file" class="custom-file-input" id="uploadPDF" accept=".pdf,application/pdf">
					<label class="custom-file-label" for="uploadPDF">Sélectionner un PDF</label>
				</div>
				<div id="loading"></div>
				<p class="mt-5" id="file_description"></p>
				<ul id="detailPages">
					<li>Nom du fichier&nbsp;: <strong><span id="nomFichier"></span></strong></li>
					<li>Pages en noir &amp; blanc&nbsp;: <strong><span id="nbPagesNB"></span></strong></li>
					<li>Pages en couleur&nbsp;: <strong><span id="nbPagesC"></span></strong></li>
					<li>Total des pages&nbsp;: <strong><span id="nbPages"></span></strong></li>
				</ul>
			</fieldset>

			<fieldset>
				<!-- Type de document à imprimer -->
				<legend>Type de document à imprimer</legend>
				<div class="row">
					<!-- Dossier -->
					<div class="col-md-6" id="docTypeDossier">
						<div class="topBarDocType_old">
							<input type="radio" name="docType" value="dossier" id="dossier" />
							<label for="dossier"><strong>Dossier</strong></label>
						</div>
						<div class="bottomDocType_old" style="height:250px;overflow-y:auto;">
							<p><small><strong>Couverture et dos</strong>&nbsp;:<br>
							Feuillet transparent de protection avant.<br>
							Feuille cartonnée colorée à l'arrière (non imprimable).</small></p>
							<p><small><strong>Reliure</strong>&nbsp;:<br>
							Spirale plastique, spirale métallique ou thermocollée, de couleur blanche ou noire au choix.</small></p>
						</div>
					</div>
					<!-- Mémoire -->
					<div class="col-md-6" id="docTypeMemoire">
						<div class="topBarDocType_old">
							<input type="radio" name="docType" value="memoire" id="memoire" />
							<label for="memoire"><strong>Mémoire</strong></label>
						</div>
						<div class="bottomDocType_old" style="height:250px;overflow-y:auto;">
							<p><small><strong>Couverture et dos</strong>&nbsp;:<br>
							Feuillet transparent de protection avant.<br>
							Feuille cartonnée colorée et imprimable en 1ère de couverture (première page du document).<br>
							Feuille cartonnée colorée non imprimable en 4ème de couverture (dernière page du document).</small></p>
							<p><small><strong>Reliure</strong>&nbsp;:<br>
							Spirale plastique, spirale métallique ou thermocollée, de couleur blanche ou noire au choix.</small></p>
						</div>
					</div>
				</div>
				<div class="row">
					<!-- Thèse -->
					<div class="col-md-6" id="docTypeThese">
						<div class="topBarDocType_old">
							<input type="radio" name="docType" value="these" id="these" />
							<label for="these"><strong>Thèse</strong></label>
						</div>
						<div class="bottomDocType_old" style="height:250px;overflow-y:auto;">
							<p><small><strong>Couverture et dos</strong>&nbsp;:<br>
							Feuillet transparent de protection avant et arrière au choix. <br>
							Feuille cartonnée colorée et imprimable en 1ère et 4ème de couverture (première page du document et résumé de la thèse).</small></p>
							<p><small><strong>Reliure</strong>&nbsp;:<br>
							Thermocollée blanche ou noire au choix.</small></p>
						</div>
					</div>
					<!-- Personnalisé -->
					<div class="col-md-6" id="docTypePerso">
						<div class="topBarDocType_old">
							<input type="radio" name="docType" value="perso" id="perso" />
							<label for="perso"><strong>Personnalisé</strong></label>
						</div>
						<div class="bottomDocType_old" style="height:250px;overflow-y:auto;">
							<p><small><strong>Couverture et dos</strong>&nbsp;:<br>
							Personnalisez toutes vos options selon vos besoins&nbsp;!</small></p>
							<p><small><strong>Reliure</strong>&nbsp;:<br>
							Spirale plastique, spirale métallique ou thermocollée, de couleur blanche ou noire au choix.</small></p>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-lg-4">
						<!-- couverture -->
						<p class="legend">Couverture</p>
						<p>
							<input type="checkbox" name="btnFTCouv" id="btnFTCouv">
							<label for="btnFTCouv">Feuillet transparent</label><br>
							<input type="checkbox" name="btnFCCouv" id="btnFCCouv">
							<label for="btnFCCouv">Feuille cartonnée</label><br>
						</p>
						<div id="couvCouleurFC">
							<p><strong>Couleur de la feuille cartonnée</strong></p>
							<p>
								<input type="radio" name="couv-impr" id="couv-printable" value="printable">
								<label for="">Feuille imprimable</label><br>
								<input type="radio" name="couv-impr" id="couv-unprintable" value="unprintable">
								<label for="">Feuille non imprimable</label><br>
							</p>
							<div style="height:300px;overflow-y:auto;">
<?php foreach ($allColors as $data) : ?>
								<div class="couv-color-<?php echo ($data['printable'] ? 'printable' : 'unprintable'); ?>">
									<input type="radio" id="color_<?php echo $data['id_dossier_color']; ?>" name="color_<?php echo $data['id_dossier_color']; ?>" value="<?php echo htmlentities($data['text'], ENT_QUOTES); ?>" data-printable="<?php echo ($data['printable'] ? '1' : '0'); ?>" data-unprintable="<?php echo ($data['unprintable'] ? '1' : '0'); ?>">
									<label for="color_<?php echo $data['id_dossier_color']; ?>"><span style="display:inline-block;width:16px;height:16px;border:1px solid #000;background:#<?php echo $data['hex']; ?>"></span> <?php echo htmlentities($data['text'], ENT_QUOTES); ?></label><br>
								</div>
<?php endforeach; ?>
							</div>
<!--
								<div class="col-6">
									<input type="radio" id="FCCouvBlanche" name="btnCoulFCCouv" value="FCCouvBlanche">
									<label for="FCCouvBlanche">Blanche</label>
								</div>
								<div class="col-6">
									<input type="radio" id="FCCouvNoire" name="btnCoulFCCouv" value="FCCouvNoire">
									<label for="FCCouvNoire">Noire</label>
								</div>
								<div class="col-6">
									<input type="radio" id="FCCouvVerte" name="btnCoulFCCouv" value="FCCouvVerte">
									<label for="FCCouvVerte">Verte</label>
								</div>
								<div class="col-6">
									<input type="radio" id="FCCouvJaune" name="btnCoulFCCouv" value="FCCouvJaune">
									<label for="FCCouvJaune">Jaune</label>
								</div>
								<div class="col-6">
									<input type="radio" id="FCCouvRouge" name="btnCoulFCCouv" value="FCCouvRouge">
									<label for="FCCouvRouge">Rouge</label>
								</div>
-->
						</div>
					</div>
					<div class="col-lg-4">
						<!-- dos -->
						<p class="legend">Dos</p>
						<p>
							<input type="checkbox" name="btnFTDos" id="btnFTDos">
							<label for="btnFTDos">Feuillet transparent</label><br>
							<input type="checkbox" name="btnFCDos" id="btnFCDos">
							<label for="btnFCDos">Feuille cartonnée</label><br>
						</p>
						<div id="dosCouleurFC">
							<p><strong>Couleur de la feuille cartonnée</strong></p>
							<p>
								<input type="radio" name="couv-impr" id="couv-printable" value="printable">
								<label for="">Feuille imprimable</label><br>
								<input type="radio" name="couv-impr" id="couv-unprintable" value="unprintable">
								<label for="">Feuille non imprimable</label><br>
							</p>
							<div style="height:300px;overflow-y:auto;">
<?php foreach ($allColors as $data) : ?>
								<div class="couv-color-<?php echo ($data['printable'] ? 'printable' : 'unprintable'); ?>">
									<input type="radio" id="color_<?php echo $data['id_dossier_color']; ?>" name="color_<?php echo $data['id_dossier_color']; ?>" value="<?php echo htmlentities($data['text'], ENT_QUOTES); ?>" data-printable="<?php echo ($data['printable'] ? '1' : '0'); ?>" data-unprintable="<?php echo ($data['unprintable'] ? '1' : '0'); ?>">
									<label for="color_<?php echo $data['id_dossier_color']; ?>"><span style="display:inline-block;width:16px;height:16px;border:1px solid #000;background:#<?php echo $data['hex']; ?>"></span> <?php echo htmlentities($data['text'], ENT_QUOTES); ?></label><br>
								</div>
<?php endforeach; ?>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<!-- Reliure -->
						<p class="legend">Reliure</p>
						<p><strong>Type de reliure</strong></p>
						<p>
							<input type="radio" name="btnReliure" id="thermo">
							<label for="thermo">Thermocollée</label><br>
							<input type="radio" name="btnReliure" id="spiplast">
							<label for="spiplast">Spirales plastique</label><br>
							<input type="radio" name="btnReliure" id="spimetal">
							<label for="spimetal">Spirales métalliques</label><br>
						</p>
						<p><strong>Couleur de la reliure</strong></p>
						<div class="row">
							<div class="col-6">
								<input type="radio" id="reliureBlanche" name="btnCoulReliure" value="reliureBlanche">
								<label for="reliureBlanche">Blanche</label>
							</div>
							<div class="col-6">
								<input type="radio" id="reliureNoire" name="btnCoulReliure" value="reliureNoire">
								<label for="reliureNoire">Noire</label>
							</div>
						</div>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<!-- options -->
				<legend class="titre1">Options supplémentaires</legend>
				<div class="options">
					<div class="nbExmplaires">
						<label for="quantity">Nombre d'exemplaires</label>
						<input type="number" id="quantity" name="quantity" min="1" value="1">
					</div>
					<div>
						<label for="rectoverso">Recto-Verso</label>
						<input type="checkbox" name="btnFTDos" id="rectoverso">
					</div>
				</div>
			</fieldset>

			<fieldset>
				<!-- devis -->
				<legend>Devis</legend>

				<table class="table table-bordered">
					<tr>
						<th id="devisColonneDescr" scope="col">Description</th>
						<th id="devisColonneQuant" scope="col">Quantité</th>
						<th id="devisColonnePrixU" scope="col">Prix unitaire</th>
						<th id="devisColonneTotal" scope="col">Total</th>
					</tr>
					<tr>
						<th scope="row">Page(s) N&amp;B</th>
						<td id="devisPagesNBQuant"></td>
						<td id="devisPagesNBPrixU"></td>
						<td id="devisPagesNBTotal"></td>
					</tr>
					<tr>
						<th scope="row">Page(s) couleur</th>
						<td id="devisPagesCQuant"></td>
						<td id="devisPagesCPrixU"></td>
						<td id="devisPagesCTotal"></td>
					</tr>
					<tr>
						<th scope="row">Feuillet(s) transparent(s)</th>
						<td id="devisFTQuant"></td>
						<td id="devisFTPrixU"></td>
						<td id="devisFTTotal"></td>
					</tr>
					<tr>
						<th scope="row">Feuille(s) cartonnée(s)</th>
						<td id="devisFCQuant"></td>
						<td id="devisFCPrixU"></td>
						<td id="devisFCTotal"></td>
					</tr>
					<tr>
						<th scope="row">Reliure(s)</th>
						<td id="devisReliureQuant"></td>
						<td id="devisReliurePrixU"></td>
						<td id="devisReliureTotal"></td>
					</tr>
					<tr>
						<th scope="row">TVA</th>
						<td></td>
						<td></td>
						<td id="devisTVA"></td>
					</tr>
					<tr>
						<th scope="row">Total</th>
						<td></td>
						<td></td>
						<td id="devisTotal"></td>
					</tr>
				</table>
			</fieldset>

			<!-- bouton valider -->
			<input type="submit" id="submit" value="Valider">
		</form>
	</div>
</div>

<?php

$javascript = <<<PHP_JS
<script src="/public/js/compte_pages.js"></script>

PHP_JS;

require_once 'views/footer.php';
