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
                <h3 class="text-center form-title">Connexion</h3>
                <?php echo displayMessage($errors); ?>
				<form action="" method="post">
                    <div class="form-group">
                        <label>Pseudo ou adresse e-mail</label>
                        <input type="text" name="pseudo" class="form-control form-control-lg" value="<?php echo htmlentities($pseudo, ENT_QUOTES); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Mot de passe</label>
                        <input type="password" name="password" class="form-control form-control-lg" required>
						<p class="text-right"><a href="/index.php?action=forgotPassword">Mot de passe oublié&nbsp;?</a></p>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="login-btn" class="btn btn-lg btn-block">Connexion</button>
                    </div>
                </form>
                <p>Vous n'avez pas de compte&nbsp;? <a href="/index.php?action=signup">Enregistrez-vous</a></p>
            </div>
        </div>
    </div>
</body>

</html>
