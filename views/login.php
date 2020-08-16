<!-- // TODO vérifier require_once. a été mis en place car message warning: deja un start_session -->
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
            <div class="col-md-4 offset-md-4 form-wrapper auth login">
                <h3 class="text-center form-title">Connexion</h3>
                <!-- the $errors array is displayed in this area -->

                <!-- <?php // TODO : a supprimer
                print_r($errors); ?>
                <?php // TODO : a supprimer
                print_r($_SESSION); ?> -->
                <?php if (count($errors) > 0) : ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error) : ?>
                            <li>
                                <?php echo $error; ?>
                            </li>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <!-- form (with action="?php echo $_SERVER['PHP_SELF']; ?" it is very easy to inject malicious data) -->
                <form action="" method="post">
                    <div class="form-group">
                        <label>Pseudo ou Email</label>
                        <input type="text" name="pseudo" class="form-control form-control-lg" value="<?php echo $pseudo; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Mot de passe</label>
                        <input type="password" name="password" class="form-control form-control-lg" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="login-btn" class="btn btn-lg btn-block">Valider</button>
                    </div>
                </form>
                <p>Vous n'avez pas de compte ? <a href="index.php?action=signup">Enregistrez-vous</a></p>
            </div>
        </div>

    </div>
</body>

</html>