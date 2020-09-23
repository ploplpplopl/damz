<?php

/*sleep(2);
echo json_encode([
	'filename' => md5('{D@mZ-T0K€N}' . uniqid(mt_rand(), true)) . '.pdf',
	'filename_client' => 'plop.pdf',
	'NbPages' => 334,
	'NbPagesC' => 303,
	'NbPagesNB' => 31,
	'TabPages' => [1,15,34],
]);
exit;*/

// Vérification que le fichier a bien été téléchargé dans le repertoire temporaire
if(empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])){
	echo 'dwldErr';
	exit;
}
//Vérification du type et de la taille du fichier envoyé
if ($_FILES['file']['type'] != 'application/pdf') {
	echo 'notPDF';
	exit;
	// } else if ($_FILES['file']['size'] > ini_get('upload_max_filesize')) {
} else if ($_FILES['file']['size'] > 70000000) {
	echo 'tooHeavy';
	exit;
}


//Si dossier d'upload n'existe pas, le crée; déplace le fichier uploadé vers le dossier d'uploads
if (!file_exists('../files/uploads')) {
	mkdir('../files/uploads', 0777);
}
$parts = explode('.', $_FILES['file']['name']);
$extension = end($parts);
$filename = md5('{D@mZ-T0K€N}' . uniqid(mt_rand(), true)) . '.' . $extension;
$filename_client = $_FILES['file']['name'];
// chmod($_SERVER['DOCUMENT_ROOT'] . '/files/uploads', 0777);
move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/files/uploads/' . $filename);
chmod($_SERVER['DOCUMENT_ROOT'] . '/files/uploads/' . $filename, 0777);

//Ligne de commande interrogeant GhostScript, récupérant un tableau de sortie de commande ($outputs), et un code d'execution de commande ($retour), où 0 est bien, et tout autre chiffre indique problème
exec("../vendor/Ghostscript/gs-950 -o - -sDEVICE=inkcov ../files/uploads/$filename 2>&1", $outputs, $retour);

//Nettoyage du tableau
$ProfilsColosPagesTemp = [];
foreach ($outputs as $output) {
	if (substr($output, -1) == "K") { //Ne prend que les éléments se terminant par "K" (donc tout ceux avec les profils colorimétriques de la page)
		array_push($ProfilsColosPagesTemp, $output);
	}
}

//Création tableau de tableau pour parcours ultérieur
$ProfilsColosPages = [];
foreach ($ProfilsColosPagesTemp as $ProfilTemp) {
	$ProfilTemp = explode(" ", $ProfilTemp);
	$Profil = [];
	//Suppression des éléments vides
	for ($i = 0; $i < count($ProfilTemp); $i++) {
		if (!empty($ProfilTemp[$i])) {
			array_push($Profil, $ProfilTemp[$i]);
		}
	}
	array_push($ProfilsColosPages, $Profil);
}

//Tests couleurs, compte du nombre de pages couleurs et peuplement d'un tableau $tabPages avec numéros des pages couleurs
$tabPagesCouleurs = [];
$i = 1;
$nbPages = count($ProfilsColosPages); //Nombre de pages total
foreach ($ProfilsColosPages as $Page) {
	if (($Page[0] > 0 || $Page[1] > 0 || $Page[2] > 0) && (($Page[0] != $Page[1]) || ($Page[2] != $Page[1]))) {
		array_push($tabPagesCouleurs, $i);
	}
	$i++;
}

//Nombre de pages noir et blanc
$nbPagesNB = $nbPages - count($tabPagesCouleurs);

//Création du JSON renvoyé au dossier_formulaire.js de dossier.php pour affichage
echo json_encode([
	'filename' => $filename,
	'filename_client' => $filename_client,
	'NbPages' => $nbPages,
	'NbPagesC' => count($tabPagesCouleurs),
	'NbPagesNB' => $nbPagesNB,
	'TabPages' => $tabPagesCouleurs,
]);
