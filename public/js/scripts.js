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

	// // Prevent form validation
	// $("form").keypress(function (e) {
	// 	if (e.keyCode == 13) {
	// 		e.preventDefault();
	// 	}
	// });

	// Limit DB calls (i.e: on keyup)
	function throttle(func, duration) {
		let shouldWait = false
		return function (...args) {
			if (!shouldWait) {
				func.apply(this, args)
				shouldWait = true
				setTimeout(function () {
					shouldWait = false
				}, duration)
			}
		}
	}

	// AJAX check for existing pseudo in DB (with throttle)
	$("#signup-pseudo")
		.bind("keyup change blur input mouseenter", throttle(function () {
			let pseudo = $(this).val().trim();
			if (pseudo != '') {
				$.get('models/ajaxCheck.php', {
						pseudo: pseudo
					})
					.done(function (data) {
						$("#message_pseudo_doublon").html(data);
					});
			}
		}, 500));

	// AJAX check for existing email in DB (with throttle)
	$("#signup-email")
		.bind("keyup change blur input mouseenter", throttle(function () {
			let email = $(this).val().trim();
			if (email != '') {
				$.get('models/ajaxCheck.php', {
						email: email
					})
					.done(function (data) {
						$("#message_email_doublon").html(data);
					});
			}
		}, 500));

});