$(function () {
	// Closing notifications.
	$(".close")
		.css("cursor", "pointer")
		.attr("title", "Fermer")
		.click(function (e) {
			e.preventDefault();
			$(".alert-success, .alert-warning, .alert-danger").slideUp(500);
		});

	// Delete user address.
	$(".del-address").click(function () {
		return confirm('Voulez-vous vraiment supprimer cette adresse ?');
	});

	// Prevent form validation
	$("body").keypress(function (e) {
		if (e.keyCode == 13) {
			e.preventDefault();
		}
	});

});