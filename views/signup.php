<!-- where the form data is being submitted to : -->
<?php require_once 'controllers/authController.php' ?>

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
            <div class="col-lg-4 offset-lg-4 form-wrapper auth">
                <h3 class="text-center form-title">Créez votre compte</h3>
                <?php echo displayMessage($errors); ?>
                <form id="signup-form" action="" method="post">
                    <div class="form-group">
                        <label for="signup-firstname">Prénom</label>
                        <input type="text" id="signup-firstname" name="firstname" class="form-control form-control-lg" value="<?php echo htmlentities($firstname, ENT_QUOTES); ?>" required="required" />
                        <span id="span-signup-firstname"></span>
                    </div>
                    <div class="form-group">
                        <label for="signup-lastname">Nom</label>
                        <input type="text" id="signup-lastname" name="lastname" class="form-control form-control-lg" value="<?php echo htmlentities($lastname, ENT_QUOTES); ?>" required="required" />
                        <span id="span-signup-lastname"></span>
                    </div>
                    <div class="form-group">
                        <label for="signup-email">Email</label>
                        <input type="text" id="signup-email" name="email" class="form-control form-control-lg" value="<?php echo htmlentities($email, ENT_QUOTES); ?>" required="required" pattern="[a-zA-Z0-9](\w\.?)*[a-zA-Z0-9]@[a-zA-Z0-9]+\.[a-zA-Z]{2,6}" />
                        <span id="span-signup-email"></span>
                    </div>
                    <div class="form-group">
                        <label for="signup-phone">Téléphone</label>
                        <input type="text" id="signup-phone" name="phone" class="form-control form-control-lg" value="<?php echo htmlentities($phone, ENT_QUOTES); ?>" required="required" pattern="[0-9+]*(\d(\s)?){9,}\d" />
                        <span id="span-signup-phone"></span>
                    </div>
                    <div class="form-group">
                        <label for="signup-pseudo">Pseudo (pour la connexion)</label>
                        <input type="text" id="signup-pseudo" name="pseudo" class="form-control form-control-lg" value="<?php echo htmlentities($pseudo, ENT_QUOTES); ?>" required="required" />
                        <span id="span-signup-pseudo"></span>
                    </div>
                    <div class="form-group">
                        <label for="signup-password">Mot de passe</label>
                        <input type="password" id="signup-password" name="password" class="form-control form-control-lg" required="required" />
                        <span id="span-signup-password"></span>
                    </div>
                    <div class="form-group">
                        <label for="signup-passwordC">Confirmation du mot de passe</label>
                        <input type="password" id="signup-passwordC" name="passwordConf" class="form-control form-control-lg" required="required" />
                        <span id="span-signup-passwordC"></span>
                    </div>
                    <div class="form-group">
                        <button type="submit" id="signup-btn" name="signup-btn" class="btn btn-lg btn-block">Inscription</button>
                    </div>
                </form>
                <p>Vous avez déjà un compte ? <a href="index.php?action=login">Login</a></p>
            </div>
        </div>
    </div>
    <script src="public/js/signup_controls.js"></script>
</body>

</html>