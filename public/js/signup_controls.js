// ------ VARIABLES ------
// id
var signupFirstname = document.getElementById("signup-firstname");
signupFirstname.addEventListener("keyup", checkFirstname, false);

var signupLastname = document.getElementById("signup-lastname");
signupLastname.addEventListener("keyup", checkLastname, false);

var signupEmail = document.getElementById("signup-email");
signupEmail.addEventListener("keyup", checkMail, false);

var signupPhone = document.getElementById("signup-phone");
signupPhone.addEventListener("keyup", checkPhone, false);

var signupPseudo = document.getElementById("signup-pseudo");
signupPseudo.addEventListener("keyup", checkPseudo, false);

var signupPassword = document.getElementById("signup-password");
signupPassword.addEventListener("keyup", checkPassword, false);

var signupPasswordC = document.getElementById("signup-passwordC");
signupPasswordC.addEventListener("keyup", checkPasswordC, false);

var signupForm = document.getElementById("signup-form");
signupForm.addEventListener("submit", checkForm, false);

// span
var spanSignupFirstname = document.getElementById("span-signup-firstname");
var spanSignupLastname = document.getElementById("span-signup-lastname");
var spanSignupEmail = document.getElementById("span-signup-email");
var spanSignupPhone = document.getElementById("span-signup-phone");
var spanSignupPseudo = document.getElementById("span-signup-pseudo");
var spanSignupPassword = document.getElementById("span-signup-password");
var spanSignupPasswordC = document.getElementById("span-signup-passwordC");





// ------- FUNCTIONS -------
function surligne(champ, erreur) {
    if (erreur)
        champ.style.backgroundColor = "#fba";
    else
        champ.style.backgroundColor = "";
}

function checkFirstname(e) {
    if (signupFirstname.value.length < 3 || signupFirstname.value.length > 25) {
        signupFirstname.focus();
        e.preventDefault();
        spanSignupFirstname.innerHTML = "3 à 25 caractères requis";
        surligne(signupFirstname, true);
        return false;
    } else {
        surligne(signupFirstname, false);
        spanSignupFirstname.innerHTML = "";
        return true;
    }
}

function checkLastname(e) {
    if (signupLastname.value.length < 3 || signupLastname.value.length > 25) {
        signupLastname.focus();
        e.preventDefault();
        spanSignupLastname.innerHTML = "3 à 25 caractères requis";
        surligne(signupLastname, true);
        return false;
    } else {
        surligne(signupLastname, false);
        spanSignupLastname.innerHTML = "";
        return true;
    }
}

function checkMail(e) {
    var regex = /^[a-zA-Z0-9._-]{2,}@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
    if (!regex.test(signupEmail.value)) {
        signupEmail.focus();
        e.preventDefault();
        spanSignupEmail.innerHTML = "Format d'email invalide";
        surligne(signupEmail, true);
        return false;
    } else {
        spanSignupEmail.innerHTML = "";
        surligne(signupEmail, false);
        spanSignupEmail.innerHTML = "";
        return true;
    }
}

function checkPhone(e) {
    // var regex = /^[0-9+()*#]+$/;
    var regex = /^[0-9+]*(\d(\s)?){9,}\d/;
    if (!regex.test(signupPhone.value)) {
        signupPhone.focus();
        e.preventDefault();
        spanSignupPhone.innerHTML = "Format de téléphone invalide";
        surligne(signupPhone, true);
        return false;
    } else {
        surligne(signupPhone, false);
        spanSignupPhone.innerHTML = "";
        return true;
    }
}

function checkPseudo(e) {
    if (signupPseudo.value.length < 3 || signupPseudo.value.length > 25) {
        signupPseudo.focus();
        e.preventDefault();
        spanSignupPseudo.innerHTML = "3 à 25 caractères requis";
        surligne(signupPseudo, true);
        return false;
    } else {
        surligne(signupPseudo, false);
        spanSignupPseudo.innerHTML = "";
        return true;
    }
}

function checkPassword(e) {
    var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$/;
    if (!regex.test(signupPassword.value)) {
        signupPassword.focus();
        e.preventDefault();
        spanSignupPassword.innerHTML = "Le mot de passe doit contenir au minimum 8 caractères <br>" +
            "et AU MOINS : 1 minuscule, 1 majuscule, 1 chiffre, 1 caractère spécial";
        surligne(signupPassword, true);
        return false;
    } else {
        surligne(signupPassword, false);
        spanSignupPassword.innerHTML = "";
        return true;
    }
}

function checkPasswordC(e) {
    if (signupPassword.value != signupPasswordC.value) {
        signupPasswordC.focus();
        e.preventDefault();
        spanSignupPasswordC.innerHTML = "Les mots de passe doivent être identiques";
        surligne(signupPasswordC, true);
        return false;
    } else {
        surligne(signupPasswordC, false);
        spanSignupPasswordC.innerHTML = "";
        return true;
    }
}

function checkForm(f) {
    var mailOk = checkMail(f.email);
    var pseudoOk = checkPseudo(f.pseudo);
    // var ageOk = checkAge(f.age);

    if (pseudoOk && mailOk)
        return true;
    else {
        alert("Veuillez remplir correctement tous les champs");
        return false;
    }
}