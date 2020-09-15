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
				$.get('controllers/ajaxCheck.php', {
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
				$.get('controllers/ajaxCheck.php', {
						email: email
					})
					.done(function (data) {
						$("#message_email_doublon").html(data);
					});
			}
		}, 500));


	// Password setting validation
	items = $("input[type='password'][name='passwordConf']");
	if (typeof items != 'undefined' && items != null && items.length != 0) {
		var password = $("input[type='password'][name='password']")[0];
		var passwordConf = $("input[type='password'][name='passwordConf']")[0];
		var letter = $("#letter")[0];
		var capital = $("#capital")[0];
		var number = $("#number")[0];
		var specialchar = $("#specialchar")[0];
		var length = $("#length")[0];

		var submit = $("input[type='submit']")[0];

		// When the user clicks on the password field, show the message box
		password.onfocus = function () {
			$("#message").css("display", "block").show();
		}

		// When the user clicks outside of the password field, hide the message box
		password.onblur = function () {
			$("#message").hide();
		}

		// When the user starts to type something inside the password field
		"keyup change input mouseenter".split(" ").forEach(function (e) {
			password.addEventListener(e, function () {
				// Validate lowercase letters
				var lowerCaseLetters = /[a-z]/g;
				if (password.value.match(lowerCaseLetters)) {
					letter.classList.remove("invalid");
					letter.classList.add("valid");
				} else {
					letter.classList.remove("valid");
					letter.classList.add("invalid");
				}

				// Validate capital letters
				var upperCaseLetters = /[A-Z]/g;
				if (password.value.match(upperCaseLetters)) {
					capital.classList.remove("invalid");
					capital.classList.add("valid");
				} else {
					capital.classList.remove("valid");
					capital.classList.add("invalid");
				}

				// Validate numbers
				var numbers = /[0-9]/g;
				if (password.value.match(numbers)) {
					number.classList.remove("invalid");
					number.classList.add("valid");
				} else {
					number.classList.remove("valid");
					number.classList.add("invalid");
				}

				// Validate special char
				var specialchars = /\W|_/g;
				if (password.value.match(specialchars)) {
					specialchar.classList.remove("invalid");
					specialchar.classList.add("valid");
				} else {
					specialchar.classList.remove("valid");
					specialchar.classList.add("invalid");
				}

				// Validate length
				if (password.value.length >= 8) {
					length.classList.remove("invalid");
					length.classList.add("valid");
				} else {
					length.classList.remove("valid");
					length.classList.add("invalid");
				}
				// validatePassword;
			}, false);
		});
		// "keyup change input mouseenter".split(" ").forEach(function (e) {
		// 	passwordConf.addEventListener(e, function () {
		// 		validatePassword;
		// 	}, false);
		function validatePassword() {
			if (password.value != passwordConf.value) {
				passwordConf.checkValidity("Les mots de passe ne sont pas identiques");
				submit.click();
			} else {
				passwordConf.setCustomValidity('');
			}
		}
// TODO ne fonctionne plus
		// password.onchange = validatePassword;
		passwordConf.onkeyup = validatePassword();
	}
});