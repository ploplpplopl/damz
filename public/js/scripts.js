$(function () {
    // Closing notifications.
    $(".alert-success, .alert-warning, .alert-danger")
	.css("cursor", "pointer")
	.attr("title", "Fermer")
	.click(function (e) {
		e.preventDefault();
		$(this).slideUp(1000);
	});

	// Delete user address.
	$(".del-address").click(function(){
		return confirm('Voulez-vous vraiment supprimer cette adresse ?');
	});
});
