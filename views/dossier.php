<?php

require_once _ROOT_DIR_ . '/controllers/dossierController.php';

require_once 'views/head.php';
?>

<div class="row">
	<div class="col-12 home-wrapper">
		<h1>Imprimez votre document</h1>
		<?php echo displayMessage($errors); ?>

		<form id="formDossier" method="post" action="">
			<fieldset>
				<!-- upload du document et détection du nombre de pages couleurs/N&B via le script dossier_formulaire.js-->
				<legend>Téléchargez le fichier PDF à imprimer</legend>
				<div class="custom-file">
					<input type="file" class="custom-file-input" id="uploadPDF" accept=".pdf,application/pdf">
					<label class="custom-file-label" for="uploadPDF">Sélectionner un PDF</label><span id='error-upload'>Sélectionnez un fichier</span>
				</div>
				<div id="loading"></div>
				<p class="mt-5" id="file_description"></p>
				<ul id="detailPages">
					<li>Nom du fichier&nbsp;: <strong><span id="nomFichier"></span></strong></li>
					<li>Pages en noir et blanc&nbsp;: <strong><span id="nbPagesNB"></span></strong></li>
					<li>Pages en couleur&nbsp;: <strong><span id="nbPagesC"></span></strong></li>
					<li>Total des pages&nbsp;: <strong><span id="nbPages"></span></strong></li>
				</ul>
				<input type="hidden" name="nomFichier" value="<?php echo htmlentities($nomFichier, ENT_QUOTES); ?>">
				<input type="hidden" name="nomFichier_client" value="<?php echo htmlentities($nomFichier_client, ENT_QUOTES); ?>">
				<input type="hidden" name="nbPagesNB" value="<?php echo htmlentities($nbPagesNB, ENT_QUOTES); ?>">
				<input type="hidden" name="nbPagesC" value="<?php echo htmlentities($nbPagesC, ENT_QUOTES); ?>">
				<input type="hidden" name="nbPages" value="<?php echo htmlentities($nbPages, ENT_QUOTES); ?>">
			</fieldset>

			<fieldset>
				<!-- Type de document à imprimer -->
				<legend id='legend_doctype'>Type de document à imprimer</legend>
				<div class="row">
					<!-- Dossier -->
					<div class="col-md-6" id="docTypeDossier">
						<label for="dossier">
							<div class="topBarDocType_old">
								<span class='error-doctype'>Sélectionnez un type de document</span>
								<input type="radio" name="docType" value="dossier" id="dossier" <?php echo ('dossier' == $docType ? ' checked' : ''); ?>><strong> Dossier</strong>

							</div>
							<div class="bottomDocType_old" style="height:250px;overflow-y:auto;">
								<p><small><strong>Couverture et dos</strong>&nbsp;:<br>
										Feuillet transparent de protection avant.<br>
										Feuille cartonnée colorée à l'arrière (non imprimable).</small></p>
								<p><small><strong>Reliure</strong>&nbsp;:<br>
										Spirale plastique, spirale métallique ou thermocollée, de couleur blanche ou noire au choix.</small></p>
							</div>
						</label>
					</div>
					<!-- Mémoire -->
					<div class="col-md-6" id="docTypeMemoire">
						<label for="memoire">
							<div class="topBarDocType_old">
								<span class='error-doctype'>Sélectionnez un type de document</span>
								<input type="radio" name="docType" value="memoire" id="memoire" <?php echo ('memoire' == $docType ? ' checked' : ''); ?>><strong> Mémoire</strong>
							</div>
							<div class="bottomDocType_old" style="height:250px;overflow-y:auto;">
								<p><small><strong>Couverture et dos</strong>&nbsp;:<br>
										Feuillet transparent de protection avant.<br>
										Feuille cartonnée colorée et imprimable en 1ère de couverture (première page du document).<br>
										Feuille cartonnée colorée non imprimable en 4ème de couverture (dernière page du document).</small></p>
								<p><small><strong>Reliure</strong>&nbsp;:<br>
										Spirale plastique, spirale métallique ou thermocollée, de couleur blanche ou noire au choix.</small></p>
							</div>
						</label>
					</div>
				</div>
				<div class="row">
					<!-- Thèse -->
					<div class="col-md-6" id="docTypeThese">
						<label for="these">
							<div class="topBarDocType_old">
								<span class='error-doctype'>Sélectionnez un type de document</span>
								<input type="radio" name="docType" value="these" id="these" <?php echo ('these' == $docType ? ' checked' : ''); ?>><strong> Thèse</strong>
							</div>
							<div class="bottomDocType_old" style="height:250px;overflow-y:auto;">
								<p><small><strong>Couverture et dos</strong>&nbsp;:<br>
										Feuillet transparent de protection avant et arrière au choix. <br>
										Feuille cartonnée colorée et imprimable en 1ère et 4ème de couverture (première page du document et résumé de la thèse).</small></p>
								<p><small><strong>Reliure</strong>&nbsp;:<br>
										Thermocollée blanche ou noire au choix.</small></p>
							</div>
						</label>
					</div>
					<!-- Personnalisé -->
					<div class="col-md-6" id="docTypePerso">
						<label for="perso">
							<div class="topBarDocType_old">
								<span class='error-doctype'>Sélectionnez un type de document</span>
								<input type="radio" name="docType" value="perso" id="perso" <?php echo ('perso' == $docType ? ' checked' : ''); ?>><strong> Personnalisé</strong>
							</div>
							<div class="bottomDocType_old" style="height:250px;overflow-y:auto;">
								<p><small><strong>Couverture et dos</strong>&nbsp;:<br>
										Personnalisez toutes vos options selon vos besoins&nbsp;!</small></p>
								<p><small><strong>Reliure</strong>&nbsp;:<br>
										Spirale plastique, spirale métallique ou thermocollée, de couleur blanche ou noire au choix.</small></p>
							</div>
						</label>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-lg-4">
						<!-- couverture -->
						<p class="legend">Couverture</p>
						<p>
							<input type="checkbox" name="btnFTCouv" id="btnFTCouv" value="1" <?php echo (1 == $btnFTCouv ? ' checked' : ''); ?>>
							<label for="btnFTCouv">Feuillet transparent</label><br>
							<input type="checkbox" name="btnFCCouv" id="btnFCCouv" value="1" <?php echo (1 == $btnFCCouv ? ' checked' : ''); ?>>
							<label for="btnFCCouv">Feuille cartonnée</label><br>
						</p>
						<div id="couvCouleurFC">
							<p><strong>Couleur de la feuille cartonnée</strong></p>
							<p>
								<span id='error-couv-print'>Sélectionnez un type de feuille cartonnée</span>
								<input type="radio" name="couv-impr" id="couv_printable" value="printable" <?php echo ('printable' == $couvImpr ? ' checked' : ''); ?>>
								<label for="couv_printable">Imprimable</label><br>
								<input type="radio" name="couv-impr" id="couv_unprintable" value="unprintable" <?php echo ('unprintable' == $couvImpr ? ' checked' : ''); ?>>
								<label for="couv_unprintable">Non imprimable</label><br>
								<span id='error-couv-color'>Sélectionnez une couleur</span>
							</p>
							<div id="couvCouleurFC_colors" class="color-selector">
								<?php foreach ($allColors as $data) : ?>
									<div class="couv-color couv-printable-<?php echo ($data['printable'] ? '1' : '0'); ?> couv-unprintable-<?php echo ($data['unprintable'] ? '1' : '0'); ?>">
										<input type="radio" id="couv_color_<?php echo $data['id_dossier_color']; ?>" name="couv_color" value="<?php echo $data['id_dossier_color']; ?>" data-printable="<?php echo (htmlentities($data['text'], ENT_QUOTES) ? '1' : '0'); ?>" data-unprintable="<?php echo ($data['unprintable'] ? '1' : '0'); ?>" <?php echo ($data['id_dossier_color'] == $couvColor ? ' checked' : ''); ?>>
										<label id="label_couv_color_<?php echo $data['id_dossier_color']; ?>" for="couv_color_<?php echo $data['id_dossier_color']; ?>"><span class="color-square" style="background:#<?php echo $data['hex']; ?>"></span> <?php echo htmlentities($data['text'], ENT_QUOTES); ?></label><br>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<!-- dos -->
						<p class="legend">Dos</p>
						<p>
							<input type="checkbox" name="btnFTDos" id="btnFTDos" value="1" <?php echo (1 == $btnFTDos ? ' checked' : ''); ?>>
							<label for="btnFTDos">Feuillet transparent</label><br>
							<input type="checkbox" name="btnFCDos" id="btnFCDos" value="1" <?php echo (1 == $btnFCDos ? ' checked' : ''); ?>>
							<label for="btnFCDos">Feuille cartonnée</label><br>
						</p>
						<div id="dosCouleurFC">
							<p><strong>Couleur de la feuille cartonnée</strong></p>
							<p>
								<span id='error-dos-print'>Sélectionnez un type de feuille cartonnée</span>
								<input type="radio" name="dos-impr" id="dos_printable" value="printable" <?php echo ('printable' == $dosImpr ? ' checked' : ''); ?>>
								<label for="dos_printable">Imprimable</label><br>
								<input type="radio" name="dos-impr" id="dos_unprintable" value="unprintable" <?php echo ('unprintable' == $dosImpr ? ' checked' : ''); ?>>
								<label for="dos_unprintable">Non imprimable</label><br>
								<span id='error-dos-color'>Sélectionnez une couleur</span>
							</p>
							<div id="dosCouleurFC_colors" class="color-selector">
								<?php foreach ($allColors as $data) : ?>
									<div class="dos-color dos-printable-<?php echo ($data['printable'] ? '1' : '0'); ?> dos-unprintable-<?php echo ($data['unprintable'] ? '1' : '0'); ?>">
										<input type="radio" id="dos_color_<?php echo $data['id_dossier_color']; ?>" name="dos_color" value="<?php echo $data['id_dossier_color']; ?>" data-printable="<?php echo ($data['printable'] ? '1' : '0'); ?>" data-unprintable="<?php echo ($data['unprintable'] ? '1' : '0'); ?>" <?php echo ($data['id_dossier_color'] == $dosColor ? ' checked' : ''); ?>>
										<label id="label_dos_color_<?php echo $data['id_dossier_color']; ?>" for="dos_color_<?php echo $data['id_dossier_color']; ?>"><span class="color-square" style="background:#<?php echo $data['hex']; ?>"></span> <?php echo htmlentities($data['text'], ENT_QUOTES); ?></label><br>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<!-- Reliure -->
						<p class="legend">Reliure</p>
						<p id="type_reliure"><strong>Type de reliure</strong></p>
						<p>
							<span id='error-reliure'>Sélectionnez une reliure</span>
							<input type="radio" name="btnReliure" id="thermo" value="thermo" <?php echo ('thermo' == $btnReliure ? ' checked' : ''); ?>>
							<label for="thermo">Thermocollée</label><br>
							<input type="radio" name="btnReliure" id="spiplast" value="spiplast" <?php echo ('spiplast' == $btnReliure ? ' checked' : ''); ?>>
							<label for="spiplast">Spirales plastique</label><br>
							<input type="radio" name="btnReliure" id="spimetal" value="spimetal" <?php echo ('spimetal' == $btnReliure ? ' checked' : ''); ?>>
							<label for="spimetal">Spirales métalliques</label><br>
						</p>
						<p><strong>Couleur de la reliure</strong></p>
						<span id='error-color-reliure'>Sélectionnez une couleur de reliure</span>
						<div id="reliure_colors" class="color-selector-reliure">
							<div>
								<input type="radio" id="reliureBlanche" name="btnCoulReliure" value="Blanche" <?php echo ('Blanche' == $btnCoulReliure ? ' checked' : ''); ?>>
								<label for="reliureBlanche"><span class="color-square" style="background:#fff;"></span> Blanc</label>
							</div>
							<div>
								<input type="radio" id="reliureNoire" name="btnCoulReliure" value="Noire" <?php echo ('Noire' == $btnCoulReliure ? ' checked' : ''); ?>>
								<label for="reliureNoire"><span class="color-square" style="background:#000;"></span> Noir</label>
							</div>
						</div>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<!-- options -->
				<legend class="titre1">Impression</legend>
				<!-- <div class="NBIntegral">
					<label for="NBIntegral">Imprimer toutes les pages en N&B</label>
					<input type="checkbox" name="NBIntegral" id="NBIntegral" value="1" <?php //echo (!empty($NBIntegral) ? ' checked' : ''); 
																						?>>
				</div> -->
				<div class="rectoverso">
					<label for="rectoverso">Recto-Verso</label>
					<input type="checkbox" name="rectoverso" id="rectoverso" value="1" <?php echo (!empty($rectoverso) ? ' checked' : ''); ?>>
				</div>
				<div class="nbExmplaires">
					<label for="quantity">Nombre d'exemplaires</label>
					<input type="number" id="quantity" name="quantity" min="1" value="<?php echo (!empty($quantity) ? $quantity : '1'); ?>">
				</div>
				<div>
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
				<input type="hidden" name="tva" value="<?php echo htmlentities($tva, ENT_QUOTES); ?>">
				<input type="hidden" name="total" value="<?php echo htmlentities($total, ENT_QUOTES); ?>">
			</fieldset>

			<!-- bouton valider -->
			<input type="submit" id="submit" name="dossier-btn" class="btn btn-primary mt-2" value="Valider">
		</form>
	</div>
</div>

<?php

$javascript .= '
<script src="/public/js/dossier_formulaire.js"></script>
';

require_once 'views/footer.php';
