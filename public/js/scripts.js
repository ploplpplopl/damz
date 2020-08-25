$(function () {
    // Closing notifications.
    $(".alert-success, .alert-warning, .alert-danger").css({
        "cursor": "pointer",
        "title": "Fermer"
    }).click(function (e) {
        e.preventDefault();
        $(this).slideUp(1000);
    });

    // Delete account.
	$("a#delete-account").click(function(){
		return confirm('Vous voulez déjà nous quitter ?\n\
Pour confirmer la suppression de votre compte, veuillez entrer votre mot de passe\n\
Vous allez recevoir un e-mail de validation finale de votre demande.\n\
Vérifier que l\'adresse e-mail de votre compte ci-dessus est valide, sinon votre compte ne pourra pas être effacé.\n\
La suppression de votre compte entraînera la perte définitive de toutes vos commandes.');
	});
	
});