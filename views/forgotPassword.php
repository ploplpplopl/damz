<?php

require_once 'controllers/authController.php';

?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="<?php echo $sTitre; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="stylesheet" href="public/css/main.css">
    <link rel="icon" href="public/img/logoicone.ico">
    <title><?php echo $sTitre; ?></title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form-wrapper auth login">
                <h3 class="text-center form-title">Récupération de mot de passe</h3>
                <?php echo displayMessage($errors); ?>
				<form action="" method="post">
                    <div class="form-group">
                        <label>Adresse e-mail</label>
                        <input type="email" name="email" class="form-control form-control-lg" value="<?php echo htmlentities($email, ENT_QUOTES); ?>"  required="required" pattern="[a-zA-Z0-9](\w\.?)*[a-zA-Z0-9]@[a-zA-Z0-9]+\.[a-zA-Z]{2,6}">
                    </div>
                    <div class="form-group">
                        <button type="submit" name="forgot-password-btn" class="btn btn-lg btn-block">Valider</button>
                    </div>
                </form>
                <p><a href="index.php?action=login">Connexion</a>&nbsp;- <a href="/index.php?action=signup">Inscription</a></p>
            </div>
        </div>
    </div>
</body>

</html>
