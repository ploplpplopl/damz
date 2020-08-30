<?php

require_once _ROOT_DIR_ . '/models/dao/DbConnection.class.php';
require_once _ROOT_DIR_ . '/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

if (empty($_GET['id'])) {
	exit;
}

$id = (int) $_GET['id'];
/*
$query = '
	SELECT o.*, u.first_name, u.last_name, u.email, u.phone,
	a.address, a.address2, a.zip_code, a.city, c.name AS country_name
	FROM orders AS o
	LEFT JOIN user AS u ON o.id_user = u.id_user
	LEFT JOIN address AS a ON u.id_user = a.id_user
	LEFT JOIN country AS c ON a.id_country = c.id_country
	WHERE o.id_address = a.id_address
	AND o.archive = \'0\'
	AND id_orders = :id
';
$dbh = DbConnection::getConnection('administrateur');
$stmt = $dbh->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();
DbConnection::disconnect();
*/
// Download PDF.
ob_start();

?>
<style type="text/css">
page{line-height:1.2;}
</style>
<page style="font-size:16px;">
    <page_footer>
        <table style="width:100%;">
            <tr>
                <td style="width:50%;text-align:left;">AMT<br> 14/16 Voie de Montavas<br> 91320 Wissous</td>
                <td style="width:50%;text-align:right;">Tél : +33 1 69 19 47 47<br> Fax : +33 1 69 19 47 48<br> E-mail : administratif@amt-france.com</td>
            </tr>
        </table>
    </page_footer>
<p style="text-align:center;"><br><br><img src="http://damz/public/img/stripe.png" alt="AMT"><br><br><br></p>
<h1 style="text-align:center;font-size:34px;">RÉFÉRENCES DE MARCHÉS</h1>
<p style="text-align:center;"><br><br><br>Tous secteurs d'activité</p>
<p style="text-align:center;">Type de chantier : Tous types de chantiers</p>
</page>
<page style="font-size:16px;">
<br><br><hr style="color:#ccc;">
<nobreak>
<h2 style="font-size:24px;letter-spacing:-1px;">Titre</h2>
<table><tr><td style="width:92mm;background:#eee;padding:1mm;">
	activity
</td><td style="width:5mm;background:#fff;">
</td><td style="width:92mm;background:#eee;padding:1mm;">
	project
</td></tr></table>
<p>kind_work</p>
<table><tr><td style="width:95mm;background:#fff;">
<b>Adresse</b><br>
	address1<br>
	address2<br>
	zip_code city<br><br>
<b>Maître d\'ouvrage</b><br>
project_owner<br>
po_address<br>
</td><td style="width:5mm;background:#fff;">
</td><td style="width:95mm;background:#fff;" valign="top">
	<img src="http://damz/public/img/paypal.png" alt="" style="width:95mm;"><br><br>
Montant des travaux : <b>amount amount_per_year k€ / an</b><br>
Début de réalisation : <b>'.$d['year_start'].'</b><br>
Fin de réalisation : <b>'.$d['year_end'].'</b>
</td></tr></table>
</nobreak>
</page>

<?php

$sPdf = ob_get_contents();
ob_end_clean();

$html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', array(5,5,5,10));

//$html2pdf->addFont('leaguegothic', '', 'leaguegothic.php');
//$html2pdf->addFont('sourcesanspro', '', 'sourcesanspro.php');
//$html2pdf->pdf->SetFont('leaguegothic','',32);
//$html2pdf->pdf->SetFont('sourcesanspro','',32);

//$html2pdf->setModeDebug();
$html2pdf->pdf->SetAuthor('AMT');
$html2pdf->pdf->SetTitle('AMT Réalisations');
$html2pdf->pdf->SetSubject('AMT Réalisations');
$html2pdf->pdf->SetDisplayMode('real', 'SinglePage', 'UseThumbs');
$html2pdf->setDefaultFont('arial');
$html2pdf->writeHTML($sPdf);
$html2pdf->Output(/*'AMT-realisations.pdf', 'D'*/);


