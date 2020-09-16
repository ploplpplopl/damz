<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title><?php echo $sTitre; ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?php echo $sTitre; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/public/css/fa.css">
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="icon" href="/public/img/logoicone.ico">
	<?php echo $css; ?>
</head>

<body>
    <div class="wrapper">
        <header>
			<div class="bandeau">
				<a href="/">
                    <img src="/public/img/logo.png" alt="Copifac" id="imgcopifac">
				</a>
			</div>
        </header>
<?php
require_once 'views/menu.php';
if (!empty($_SESSION['user']['user_type'])) {
	if ('admin' == $_SESSION['user']['user_type']) {
		require_once 'views/menuAdmin.php';
	}
	elseif ('admprinter' == $_SESSION['user']['user_type']) {
		require_once 'views/menuAdmPrinter.php';
	}
}
?>

		<div class="content">
