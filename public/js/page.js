$(document).ajaxStart(function () {
    $('#loading').show();
    $('#succes').hide();
});

$(document).ajaxStop(function () {
    $('#loading').hide();
});

// Observateurs d'evenements jQuery pour la calculette : les $.getJSON() vont chercher les pages getDataPaliers, et récupère les données de paliers pour les transmettre aux fonctions de calcul
$(document).ready(function () {
    $('#loading').hide();
    $("#succes").hide();
    $("#detailPages").hide();
    $("#uploadPDF").change(function () {
        var nomFichier = $('#uploadPDF').prop('files')[0].name;
        var fichier = $('#uploadPDF').prop('files')[0];
        if (fichier != undefined) {
            var form_data = new FormData();
            form_data.append('file', fichier);
            $.ajax({
                type: 'POST',
                url: 'models/Traitement_Donnees_Compte_Pages.php',
                contentType: false,
                processData: false,
                data: form_data,
                success: function (reponse) {
                    if (reponse == 'failure' || reponse == 'notPDF' || reponse == 'tooHeavy') {
                        $("#detailPages").hide();
                        $("#succes").hide();
                        if (reponse == 'failure') {
                            alert('Le traitement du fichier à échoué');
                        } else if (reponse == 'notPDF') {
                            alert('Le fichier n\'est pas un PDF.');
                        } else if (reponse == 'tooHeavy') {
                            alert('Le fichier envoyé est trop lourd.');
                        }
                        var zero = "0";
                        $("#uploadPDF").replaceWith($("#uploadPDF").val('').clone(true)); //Reset valeur de l'upload
                        $("#nbPages").html(zero); //Reset toute les valeurs à 0
                        $("#nbPagesC").attr("value", "0").attr("placeholder", "0");
                        $("#nbPagesNB").attr("value", "0").attr("placeholder", "0");
                    } else {
                        var obj = JSON.parse(reponse);
                        if (obj.NbPagesNB == obj.NbPages) {
                            var paragInfo = "Ce document comporte " + obj.NbPages + " pages, toutes en noir et blanc. <br>";
                        } else if (obj.NbPagesC == obj.NbPages) {
                            var paragInfo = "Ce document comporte " + obj.NbPages + " pages, toutes en couleur.<br>";
                        } else {
                            var paragInfo = "Ce document comporte " + obj.NbPages + " pages, dont " + obj.NbPagesC + " en couleurs et " + obj.NbPagesNB + " en noir & blanc.<br>";
                        }
                        $("#detailPages").show();
                        $("#succes").show().html(paragInfo);
                        $("#nomFichier").html(nomFichier);
                        $("#nbPages").html(obj.NbPages);
                        $("#nbPagesC").html(obj.NbPagesC);
                        $("#nbPagesNB").html(obj.NbPagesNB);
                    }
                }
            });
            $("#erreur").hide();
        }
    })

    $("#nbcopie, #couleur1, #ExemplaireT, #reset, #photocopies, #reliures, #rodoid, #rectoverso, #couvertures, #autre1, #autre2, #autre3, #autre4, #autre5, #remiseEtudiant, #TVA").on('click keyup', function () {
        $.getJSON(
            "getDataPaliersNB.php",
            function (dataNB) {
                $.getJSON(
                    "getDataPaliersC.php",
                    function (dataC) {
                        calculs(dataNB, dataC);
                    }
                )
            }
        );
    })
    $("#reset").on("click", function () {
        reset();
        reglement();
    });
    $("#devfac").on("click", function () {
        reglement();
    });
    $("#imprime").on("click", function () {
        imprimer();
    });
    $("body").keypress(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
        }
    })
})

//Fonction de calcul automatique pour le calcul express
function calculs(dataNB, dataC) {
    calculCopies(dataNB);
    CalculCouleur(dataC);
    CalculPhoto();
    calculReliure();
    calculCouverture();
    calculAutres();
    CalculTCopies();
}

// Calcule le prix des copies noir&blanc
function calculCopies(data) {
    var nbcopie = window.document.getElementById("nbcopie").value;
    var ExemplaireT = window.document.getElementById("ExemplaireT").value;
    var prixf;
    var copienb = nbcopie * ExemplaireT;

    var DataPaliersNB = data;

    if (!data) {
        DataPaliersNB = 0;
    } else {
        DataPaliersNB = data;
    }

    if (copienb < 0) {
        prixf = 0;
    }

    // Palier 1 n&b
    else if (copienb <= DataPaliersNB[0]['max_NB']) {
        prixf = copienb * DataPaliersNB[0]["prix_NB"];
    }

    // Palier 2 n&b
    else if (copienb <= DataPaliersNB[1]['max_NB']) {
        prixf = copienb * DataPaliersNB[1]["prix_NB"];
    }

    // Palier 3 n&b
    else if (copienb <= DataPaliersNB[2]['max_NB']) {
        prixf = copienb * DataPaliersNB[2]["prix_NB"];
    }

    // Palier 4 n&b
    else if (copienb <= DataPaliersNB[3]['max_NB']) {
        prixf = copienb * DataPaliersNB[3]["prix_NB"];
    }

    // Palier 5 n&b
    else if (copienb <= DataPaliersNB[4]['max_NB']) {
        prixf = copienb * DataPaliersNB[4]["prix_NB"];
    }

    // Palier 6 n&b
    else if (copienb <= DataPaliersNB[5]['max_NB']) {
        prixf = copienb * DataPaliersNB[5]["prix_NB"];
    } else if (copienb <= DataPaliersNB[6]['max_NB']) {
        prixf = copienb * DataPaliersNB[6]["prix_NB"];
    } else if (copienb > DataPaliersNB[6]['max_NB']) {
        prixf = copienb * DataPaliersNB[6]["prix_NB"];
    } else {
        // Sinon, pas normal
        prixfc = 0;
    }

    window.document.getElementById("zone1").innerHTML = prixf.toFixed(2);
}

// Calcul le prix des copies couleur
function CalculCouleur(dataC) {
    var couleur1 = window.document.getElementById("couleur1").value;
    var ExemplaireT = window.document.getElementById("ExemplaireT").value;

    var DataPaliersC = dataC;

    var prixfc;

    var couleur = couleur1 * ExemplaireT;

    //Si couleur inférieur à 0
    if (couleur < 0) {
        prixfc = 0;
    }

    // Palier 1 Couleur
    else if (couleur <= DataPaliersC[0]["max_C"]) {
        prixfc = couleur * DataPaliersC[0]["prix_C"];
    }

    // Palier 2 Couleur
    else if (couleur <= DataPaliersC[1]["max_C"]) {
        prixfc = couleur * DataPaliersC[1]["prix_C"];
    }

    // Palier 3 Couleur
    else if (couleur <= DataPaliersC[2]["max_C"]) {
        prixfc = couleur * DataPaliersC[2]["prix_C"];
    }

    // Palier 4 Couleur
    else if (couleur <= DataPaliersC[3]["max_C"]) {
        prixfc = couleur * DataPaliersC[3]["prix_C"];
    }

    // Palier 5 Couleur
    else if (couleur <= DataPaliersC[4]["max_C"]) {
        prixfc = couleur * DataPaliersC[4]["prix_C"];
    }

    // Palier 6 Couleur
    else if (couleur <= DataPaliersC[5]["max_C"]) {
        prixfc = couleur * DataPaliersC[5]["prix_C"];
    }

    // Palier 10 Couleur
    else if (couleur > DataPaliersC[5]["max_C"]) {
        prixfc = couleur * DataPaliersC[5]["prix_C"];
    } else {
        // Sinon, pas normal
        prixfc = 0;
    }

    window.document.getElementById("zone2").innerHTML = prixfc.toFixed(2);
}

// Calcul le prix des photocopies
function CalculPhoto() {
    var photocopies = window.document.getElementById("photocopies").value;
    var ExemplaireT = window.document.getElementById("ExemplaireT").value;
    var prixfp;

    var photocopies1 = photocopies * ExemplaireT;

    if (photocopies1 >= 0) {
        prixfp = photocopies1 * 0.06;
    }

    window.document.getElementById("zone3").innerHTML = prixfp.toFixed(2);
}

//Calcul du prix des reliures
function calculReliure() {
    var ExemplaireT = window.document.getElementById("ExemplaireT").value;
    var reliures = window.document.getElementById("reliures").value;
    var nbcopie = window.document.getElementById("nbcopie").value;
    var couleur1 = window.document.getElementById("couleur1").value;
    var photocopies = window.document.getElementById("photocopies").value;
    var prixfr = 0;

    var pagesParExemplaires = parseFloat(nbcopie) + parseFloat(couleur1) + parseFloat(photocopies);

    //Recto verso qui divise par 2 le nombre de pages reliées
    if (document.getElementById("rectoverso").checked == true) {
        var pagesParExemplaires = Math.round(pagesParExemplaires / 2);
    }

    //Sans reliure
    if (reliures == 1) {
        prixfr = 0;
    }

    //Reliures plastiques
    else if (reliures == 2) {
        if (ExemplaireT <= 30) {
            if (pagesParExemplaires <= 45) {
                prixfr = ExemplaireT * 2.10;
            } else if (pagesParExemplaires <= 95) {
                prixfr = ExemplaireT * 2.60;
            } else if (pagesParExemplaires <= 145) {
                prixfr = ExemplaireT * 3.10;
            } else if (pagesParExemplaires <= 240) {
                prixfr = ExemplaireT * 3.60;
            } else if (pagesParExemplaires <= 300) {
                prixfr = ExemplaireT * 4.60;
            } else if (pagesParExemplaires >= 301) {
                prixfr = ExemplaireT * 5.10;
            }
        } else if (ExemplaireT > 30) {
            if (pagesParExemplaires <= 45) {
                prixfr = ExemplaireT * 1.90;
            } else if (pagesParExemplaires <= 95) {
                prixfr = ExemplaireT * 2.40;
            } else if (pagesParExemplaires <= 145) {
                prixfr = ExemplaireT * 2.90;
            } else if (pagesParExemplaires <= 240) {
                prixfr = ExemplaireT * 3.40;
            } else if (pagesParExemplaires <= 300) {
                prixfr = ExemplaireT * 4.40;
            } else if (pagesParExemplaires >= 301) {
                prixfr = ExemplaireT * 4.60;
            }
        }
    }

    //Reliures métal
    if (reliures == 3) {
        if (pagesParExemplaires <= 32) {
            prixfr = ExemplaireT * 3.90;
        } else if (pagesParExemplaires <= 64) {
            prixfr = ExemplaireT * 4.40;
        } else if (pagesParExemplaires <= 79) {
            prixfr = ExemplaireT * 4.90;
        } else if (pagesParExemplaires <= 110) {
            prixfr = ExemplaireT * 5.40;
        }
    }

    //Reliures thermo
    else if (reliures == 4) {
        prixfr = ExemplaireT * 4;
    }
    //Avec ou sans Rodoid
    if (document.getElementById("rodoid").checked == true && reliures != 1 && reliures != 4) {
        var prixfr = prixfr - 1.10;
    }
    if (document.getElementById("rodoid").checked == true && reliures == 4) {
        var prixfr = prixfr - 1.00;
    }
    window.document.getElementById("zone4").innerHTML = prixfr.toFixed(2);
}

//Calcul des couvertures et quatrièmes de couverture.
function calculCouverture() {
    var ExemplaireT = window.document.getElementById("ExemplaireT").value;
    var couvertures = document.getElementById("couvertures").value;
    var prixcouv;

    if (couvertures == 1) {
        prixcouv = 0;
    } else if (couvertures == 2) {
        prixcouv = ExemplaireT * 0.35;
    } else if (couvertures == 3) {
        prixcouv = ExemplaireT * (0.35 * 2);
    }

    window.document.getElementById("zone5").innerHTML = prixcouv.toFixed(2);
}

//Calcul Autres
function calculAutres() {
    var autre1 = window.document.getElementById("autre1").value;
    var prixaut1 = autre1 * 1;
    if (prixaut1 < 0) {
        prixaut1 = 0;
    }
    var autre2 = window.document.getElementById("autre2").value;
    var prixaut2 = autre2 * 1;
    if (prixaut2 < 0) {
        prixaut2 = 0;
    }
    var autre3 = window.document.getElementById("autre3").value;
    var prixaut3 = autre3 * 1;
    if (prixaut3 < 0) {
        prixaut3 = 0;
    }
    var autre4 = window.document.getElementById("autre4").value;
    var prixaut4 = autre4 * 1;
    if (prixaut4 < 0) {
        prixaut4 = 0;
    }
    var autre5 = window.document.getElementById("autre5").value;
    var prixaut5 = autre5 * 1;
    if (prixaut5 < 0) {
        prixaut5 = 0;
    }

    window.document.getElementById("zone8").innerHTML = prixaut1.toFixed(2);
    window.document.getElementById("zone9").innerHTML = prixaut2.toFixed(2);
    window.document.getElementById("zone10").innerHTML = prixaut3.toFixed(2);
    window.document.getElementById("zone11").innerHTML = prixaut4.toFixed(2);
    window.document.getElementById("zone12").innerHTML = prixaut5.toFixed(2);
}

/*    // Calcul autre
    function CalculAutre() {
        var autre = window.document.getElementById("autre").value;
        var prixfa;

        if (autre >= 0) {
            prixfa = autre * 0.01;
        }

        window.document.getElementById("zone5").innerHTML = prixfa.toFixed(2);
    }
*/

// Calcule le prix total des copies noir&blanc, couleurs, photocopies, reliures et couvertures
function CalculTCopies() {
    let zone1 = document.getElementById("zone1").innerHTML;
    let zone2 = document.getElementById("zone2").innerHTML;
    let zone3 = document.getElementById("zone3").innerHTML;
    let zone4 = document.getElementById("zone4").innerHTML;
    let zone5 = document.getElementById("zone5").innerHTML;
    let zone8 = document.getElementById("zone8").innerHTML;
    let zone9 = document.getElementById("zone9").innerHTML;
    let zone10 = document.getElementById("zone10").innerHTML;
    let zone11 = document.getElementById("zone11").innerHTML;
    let zone12 = document.getElementById("zone12").innerHTML;

    // Calcul de la TVA
    let TVA = 0;
    if (document.getElementById("TVA").value != 1) {
        if (document.getElementById("TVA").value == 2) {
            TVA = 0.055;
        } else if (document.getElementById("TVA").value == 3) {
            TVA = 0.1;
        } else if (document.getElementById("TVA").value == 4) {
            TVA = 0.2;
        }
    }

    let totalZ = Number(parseFloat(zone1) + parseFloat(zone2) + parseFloat(zone3) + parseFloat(zone4) + parseFloat(zone5) + parseFloat(zone8) + parseFloat(zone9) + parseFloat(zone10) + parseFloat(zone11) + parseFloat(zone12));
    let totalY = totalZ;

    window.document.getElementById("zoneTVA").innerHTML = (totalZ * TVA).toFixed(2);

    if (totalZ) {
        window.document.getElementById("zone6").value = totalZ.toFixed(2) + "€";
    } else {
        window.document.getElementById("zone6").value = "0.00€";
    }
    window.document.getElementById("zoneRe").innerHTML = "Remise étudiante: - " + (totalY - totalZ).toFixed(2) + "€";

    //Remise étudiante 10%
    if (document.getElementById("remiseEtudiant").checked == true) {
        window.document.getElementById("zone6").value = (totalZ * 0.90).toFixed(2) + "€";
        window.document.getElementById("zoneRe").innerHTML = "Remise étudiante: - " + (totalY - totalZ * 0.90).toFixed(2) + "€";
    }
}

//Impression de la page
function imprimer() {
    document.getElementById('deconnexion').style.visibility = 'hidden';
    document.getElementById('imgcopifac').style.visibility = 'hidden';
    document.getElementById('imprime').style.visibility = 'hidden';
    document.getElementById('reset').style.visibility = 'hidden';
    document.getElementById('coordonnéesCalcul1').style.visibility = 'hidden';
    document.getElementById('coordonnéesCalcul2').style.visibility = 'hidden';
    document.getElementById('coordonnéesCalcul3').style.visibility = 'hidden';
    document.getElementById('zone6').style.boxShadow = 'inset 0 0 0 3px black';
    document.getElementById('CoordonneesImpressions').style.visibility = 'visible';
    window.print();
    document.getElementById('deconnexion').style.visibility = 'visible';
    document.getElementById('imgcopifac').style.visibility = 'visible';
    document.getElementById('imprime').style.visibility = 'visible';
    document.getElementById('reset').style.visibility = 'visible';
    document.getElementById('coordonnéesCalcul1').style.visibility = 'visible';
    document.getElementById('coordonnéesCalcul2').style.visibility = 'visible';
    document.getElementById('coordonnéesCalcul3').style.visibility = 'visible';
    document.getElementById('zone6').style.boxShadow = 'inset 0 0 0 3px #87CEEB';
    document.getElementById('CoordonneesImpressions').style.visibility = 'hidden';
}

//Fonction reset pour remettre à zéro le calculateur
function reset() {
    document.getElementById("rectoverso").checked = false;
    document.getElementById("remiseEtudiant").checked = false;
    document.getElementById("rodoid").checked = false;
    document.getElementById("cb").checked = false;
    document.getElementById("cheque").checked = false;
    document.getElementById("espece").checked = false;
    window.document.getElementById("zone1").innerHTML = (0).toFixed(2);
    window.document.getElementById("ExemplaireT").value = 1;
    window.document.getElementById("zone2").innerHTML = (0).toFixed(2);
    window.document.getElementById("nbcopie").value = 0;
    window.document.getElementById("zone3").innerHTML = (0).toFixed(2);
    window.document.getElementById("couleur1").value = 0;
    window.document.getElementById("zone4").innerHTML = (0).toFixed(2);
    window.document.getElementById("photocopies").value = 0;
    window.document.getElementById("zone5").innerHTML = (0).toFixed(2);
    window.document.getElementById("reliures").value = 1;
    window.document.getElementById("zone6").value = (0).toFixed(2) + "€";
    window.document.getElementById("couvertures").value = 1;
    window.document.getElementById("TVA").value = 1;
    window.document.getElementById("autres1").value = "";
    window.document.getElementById("autre1").value = 0;
    window.document.getElementById("zone8").innerHTML = (0).toFixed(2);
    window.document.getElementById("autres2").value = "";
    window.document.getElementById("autre2").value = 0;
    window.document.getElementById("zone9").innerHTML = (0).toFixed(2);
    window.document.getElementById("autres3").value = "";
    window.document.getElementById("autre3").value = 0;
    window.document.getElementById("zone10").innerHTML = (0).toFixed(2);
    window.document.getElementById("autres4").value = "";
    window.document.getElementById("autre4").value = 0;
    window.document.getElementById("zone11").innerHTML = (0).toFixed(2);
    window.document.getElementById("autres5").value = "";
    window.document.getElementById("autre5").value = 0;
    window.document.getElementById("zone12").innerHTML = (0).toFixed(2);
    window.document.getElementById("devfac").value = 1;
    window.document.getElementById("nomprenom").value = "";
}

//fonction pour cacher le moyen de paiement
function reglement() {
    var devis = window.document.getElementById("devfac").value;

    if (devis == 1) {
        document.getElementById('cache').style.visibility = 'hidden';
        document.getElementById('duplicata').style.visibility = 'hidden';
    }
    if (devis == 2) {
        document.getElementById('cache').style.visibility = 'visible';
        document.getElementById('duplicata').style.visibility = 'visible';
    }
}