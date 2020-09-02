<?php

require_once _ROOT_DIR_ . '/vendor/autoload.php';
require_once _ROOT_DIR_ . '/models/AdminGestionMgr.class.php';

use Spipu\Html2Pdf\Html2Pdf;

// TODO if (empty($_GET['id']) || empty($_GET['archive'])) {
if (empty($_GET['id'])) {
	exit;
}
$id = (int) $_GET['id'];
$archive = $_GET['archive'];
$where = '';
$params = [];

$where .= ' AND o.`id_orders` = :id_orders';
$params[':id_orders'] = $id;
$order = 'id_orders';
$way = 'ASC';

$orders = AdminGestionMgr::getOrders($params, $archive, $where, $order, $way);

$id_orders = $orders[0]["id_orders"]; // => "5"
$date_add = $orders[0]["date_add"]; // => "2020-08-31 17:06:31"
$id_user = $orders[0]["id_user"]; // => "56"
$id_address = $orders[0]["id_address"]; // => "2"
$nom_fichier = $orders[0]["nom_fichier"]; // => "c3a0c212fe80bc7ec01819b467b0910f.pdf"
$nb_page = $orders[0]["nb_page"]; // => "34"
$nb_page_nb = $orders[0]["nb_page_nb"]; // => "31"
$nb_page_c = $orders[0]["nb_page_c"]; // => "3"
$doc_type = $orders[0]["doc_type"]; // => "these"
$couv_ft = $orders[0]["couv_ft"]; // => "0"
$couv_fc = $orders[0]["couv_fc"]; // => "1"
$couv_fc_type = $orders[0]["couv_fc_type"]; // => "printable"
$couv_fc_color = $orders[0]["couv_fc_color"]; // => "15"
$dos_ft = $orders[0]["dos_ft"]; // => "0"
$dos_fc = $orders[0]["dos_fc"]; // => "1"
$dos_fc_type = $orders[0]["dos_fc_type"]; // => "printable"
$dos_fc_color = $orders[0]["dos_fc_color"]; // => "14"
$reliure_type = $orders[0]["reliure_type"]; // => "thermo"
$reliure_color = $orders[0]["reliure_color"]; // => "Blanche"
$quantity = $orders[0]["quantity"]; // => "1"
$rectoverso = $orders[0]["rectoverso"]; // => "0"
$tva = $orders[0]["tva"]; // => "0.89"
$total = $orders[0]["total"]; // => "8.88"
$archive = $orders[0]["archive"]; // => "0"
$first_name = $orders[0]["first_name"]; // => "dam"
$last_name = $orders[0]["last_name"]; // => "tho"
$email = $orders[0]["email"]; // => "damien@thoorens.fr"
$phone = $orders[0]["phone"]; // => ""
$address = $orders[0]["address"]; // => "8 rue ducu"
$address2 = $orders[0]["address2"]; // => ""
$zip_code = $orders[0]["zip_code"]; // => "45000"
$city = $orders[0]["city"]; // => "huit"
$country_name = $orders[0]["country_name"]; // => "France"



// Download PDF.
ob_start();

?>
<style type="text/css">
	page {
		line-height: 1.2;
	}
</style>
<page style="font-size:16px;">
	<page_footer>
		<table style="width:100%;">
			<tr>
				<td style="width:50%;text-align:left;">COPIFAC<br> 116 et 106 rue de Geôle<br> 14000 Caen</td>
				<td style="width:50%;text-align:right;">Tél : 02 31 38 98 66<br> E-mail : contact@copifac.fr</td>
			</tr>
		</table>
	</page_footer>
	<h1 style="text-align:center;font-size:34px;">Facture N° <?php echo $id_orders; ?></h1>
	<hr style="color:#ccc;">
	<nobreak>
		<h2 style="font-size:24px;letter-spacing:-1px;">Résumé de la commande</h2>
		<table>
			<tr>
				<td style="width:92mm;background:#eee;padding:1mm;">
					activity
				</td>
				<td style="width:5mm;background:#fff;">
				</td>
				<td style="width:92mm;background:#eee;padding:1mm;">
					project
				</td>
			</tr>
		</table>
		<p>Nom du fichier : <?php echo(str_replace(' ', '_', $id_orders . '_' . $doc_type) . '_' . date("Y-m-d_H-i", strtotime($date_add)) . '.pdf'); ?></p>
<p>Nombre de pages total : <?php echo($nb_page); ?></p>



		<table>
			<tr>
				<td style="width:95mm;background:#fff;">
					<b>Adresse</b><br>
					address1<br>
					address2<br>
					zip_code city<br><br>
					<b>Maître d\'ouvrage</b><br>
					project_owner<br>
					po_address<br>
				</td>
				<td style="width:5mm;background:#fff;">
				</td>
				<td style="width:95mm;background:#fff;" valign="top">
					<img src="<?php echo $settings['site_url']; ?>/public/img/paypal.png" alt="" style="width:35mm;"><br><br>
					<p style="text-align:center;"><br><br><img src="<?php echo $settings['site_url']; ?>/public/img/stripe.png" alt="AMT"><br><br><br></p>

					Montant des travaux : <b>amount amount_per_year k€ / an</b><br>
					Début de réalisation : <b>'.$d['year_start'].'</b><br>
					Fin de réalisation : <b>'.$d['year_end'].'</b>
				</td>
			</tr>
		</table>
	</nobreak>
</page>

<?php

$sPdf = ob_get_contents();
ob_end_clean();

$html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', array(5, 5, 5, 10));

//$html2pdf->addFont('leaguegothic', '', 'leaguegothic.php');
//$html2pdf->addFont('sourcesanspro', '', 'sourcesanspro.php');
//$html2pdf->pdf->SetFont('leaguegothic','',32);
//$html2pdf->pdf->SetFont('sourcesanspro','',32);

//$html2pdf->setModeDebug();
$html2pdf->pdf->SetAuthor('CopyFac');
$html2pdf->pdf->SetTitle('Dossier-rapide');
$html2pdf->pdf->SetSubject('Dossier-rapide');
$html2pdf->pdf->SetDisplayMode('real', 'SinglePage', 'UseThumbs');
$html2pdf->setDefaultFont('arial');
$html2pdf->writeHTML($sPdf);
$html2pdf->output(/*$id_orders . '_facture' . '.pdf', 'D'*/);
