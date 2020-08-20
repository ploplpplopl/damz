<?php

if (empty($_SESSION['user']['id_user'])) {
    header('location: index.php?action=login');
	exit;
}

// protège accès direct à http://localhost/views/admin.php (views devra etre interdit avec htaccess)
if (!empty($_SESSION['user']['user_type']) && 'admprinter' != $_SESSION['user']['user_type']) {
    header('location: /index.php?action=logout');
	exit;
}

$css = '<link rel="stylesheet" href="/public/css/admin.css">';

require_once 'views/head.php';
require_once 'views/menuAdmPrinter.php';
?>

<div class="row">
	<div class="col-12">
		<h1>Récap. des trucs sur le site</h1>
		<?php echo displayMessage(); ?>
	</div>
</div>

<div class="row">
	<div class="col-md-4 offset-md-4 home-wrapper">
		<?php echo displayMessage($errors); ?>
	</div>

	<!-- Tab links -->
	<div class="tab">
		<button id="linkTabToPrint" class="tablinks">À IMPRIMER</button>
		<button id="linkTabHisto" class="tablinks">HISTORIQUE</button>
	</div>

	<!-- Tab content -->
	<div id="tabToPrint" class="tabcontent">
		<!-- 2 onglets : "à imprimer" et "historique". 
		Une fois la ligne traitée (supprimée de "à imprimer"), elle est envoyée dans l'"historique", 
		TODO dont le nombre de lignes est réglé dans l'interface d'admin -->
		<div class="responsiveTable">
			<table id="tableToPrint">
				<tr>
					<th>Suppr</th>
					<th>Facture</th>
					<th>__PDF__</th>
					<th>Étiquette <br> Transport</th>
					<th>Nom / Prénom</th>
					<th>Adresse</th>
					<th>Téléphone</th>
				</tr>
				<tr>
					<td class="center">btn</td>
					<td class="center">pdf</td>
					<td class="center">PDF</td>
					<td class="center">pdf</td>
					<td>Nom / Prénom</td>
					<td>Adresse</td>
					<td>06 06 06 06 06</td>
				</tr>
				<tr>
					<td class="center">btn</td>
					<td class="center">pdf</td>
					<td class="center">PDF</td>
					<td class="center">pdf</td>
					<td>Nom / Prénom</td>
					<td>5 rue de la prévenderie, 14000 caen</td>
					<td>Téléphone</td>
				</tr>
				<tr>
					<td class="center">btn</td>
					<td class="center">pdf</td>
					<td class="center">PDF</td>
					<td class="center">pdf</td>
					<td>Nom / Prénom</td>
					<td>Adresse</td>
					<td>Téléphone</td>
				</tr>
			</table>
		</div>
	</div>
	<div id="tabHisto" class="tabcontent">
		<div class="responsiveTable">
			<table id="tableHisto">
				<tr>
					<th>Suppr</th>
					<th>Facture</th>
					<th>__PDF__</th>
					<th>Étiquette <br> Transport</th>
					<th>Nom / Prénom</th>
					<th>Adresse</th>
					<th>Téléphone</th>
				</tr>
				<tr>
					<td class="center">btn</td>
					<td class="center">pdf</td>
					<td class="center">PDF</td>
					<td class="center">pdf</td>
					<td>Nom / Prénom</td>
					<td>Adresse</td>
					<td>06 06 06 06 06</td>
				</tr>
				<tr>
					<td class="center">btn</td>
					<td class="center">pdf</td>
					<td class="center">PDF</td>
					<td class="center">pdf</td>
					<td>Nom / Prénom</td>
					<td>5 rue de la puterie, 14000 caen</td>
					<td>Téléphone</td>
				</tr>
				<tr>
					<td class="center">btn</td>
					<td class="center">pdf</td>
					<td class="center">PDF</td>
					<td class="center">pdf</td>
					<td>Nom / Prénom</td>
					<td>Adresse</td>
					<td>Téléphone</td>
				</tr>
			</table>
		</div>
	</div>
</div>

<?php

require_once 'views/footer.php';
