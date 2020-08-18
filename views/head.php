<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?php echo $sTitre; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="icon" href="public/img/logoicone.ico">
    <title><?php echo $sTitre; ?></title>


</head>

<body>
    <div class="content">
        <header>
            <a href="index.php?action=accueil">
                <div class="bandeau">
                    <img src="public/img/copifac.png" id="imgcopifac" width="30%" />
                </div>
            </a>
            <?php 
            if (!empty($_SESSION['user']['id_user'])){
                echo '<a href="index.php?action=logout">--logout--</a><br>';
                echo "pseudo : " . $_SESSION['user']['pseudo'];
            } else {
                echo '<a href="index.php?action=login">--login--</a>';
            }
            ?>
        </header>