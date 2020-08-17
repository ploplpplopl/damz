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
                        calculDevis();
                    }
                }
            });
            $("#erreur").hide();
        }
    })

    function calculDevis() {
        alert("ne rentre pas dans la suite de la fonction. \ncompte_pages.js ligne 67")
        $.getJSON(
            "models/getDataPaliersNB.php",
            function (dataNB) {
                $.getJSON(
                    "models/getDataPaliersC.php",
                    function (dataC) {
                        calculs(dataNB, dataC);
                    }
                )
            }
        );
    }

    // TODO ajouter fonction pour modifier le package en fonction du docType
    $("#dossier, #memoire, #these").on('change', function () {});

    $("#thermo, #spiplast, #spimetal, #btnFTCouv, #btnFCCouv, #btnFTDos, #quantity, #rectoverso").on('click', function () {
        calculDevis();
    })

    // Prevent form validation
    $("body").keypress(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
        }
    })
})

//Fonction de calcul automatique pour le calcul express
function calculs(dataNB, dataC) {
    alert("coucou");

    let quantity = $("#quantity").value;
    let rectoVerso = 0;
    if ($("#rectoverso").checked) {
        rectoVerso = 1
    }; // TODO verifier les valeurs de rectoVerso
    let totalNB = calculPages('NB', dataNB, quantity, rectoVerso);
    let totalC = calculPages('C', dataC, quantity, rectoVerso);
    let totalR = calculReliure();
    let totalCouv = calculCouverture();
    // calculTCopies();
    let total = totalNB + totalC + totalR + totalCouv;
    $("#total").html(total.toFixed(2));
    // return total;
}

// Calcule le prix des pages noir&blanc ou couleur
function calculPages(type, data, quantity, rectoVerso) {
    // arguments[0]
    if (type == 'NB') {
        var nbPages = $("#nbPagesNB").text();
        var $zone = "#zone1";
    }
    if (type == 'C') {
        var nbPages = $("#nbPagesC").text();
        var $zone = "#zone2";
    }
    let total = 0;
    if (quantity < 1) {
        quantity = 1; // TODO mettre une alerte à la place
    }
    var nbTotPages = nbPages * quantity;
    if (rectoVerso == 1) { // TODO verifier les valeurs de rectoVerso
        if (nbTotPages % 2 == 0) {
            nbTotPages = nbTotPages / 2;
        } else {
            nbTotPages = Math.floor(nbTotPages / 2) + 1;
        }
    }
    if (nbTotPages < 0) { // ? dans quel cas ?
        total = 0; // TODO mettre une alerte à la place
    }

    let i = 0;
    while (data[i + 1] && (nbTotPages > data[i]['palier'])) {
        i++;
    }
    total = nbTotPages * data[i]["prix"];

    $($zone).html(total.toFixed(2));
    return total;
}



//Calcul du prix des reliures
function calculReliure() {
    var ExemplaireT = window.document.getElementById("ExemplaireT").value;
    var reliures = window.document.getElementById("reliures").value;
    var nbcopie = window.document.getElementById("nbcopie").value;
    var couleur1 = window.document.getElementById("couleur1").value;
    var photocopies = window.document.getElementById("photocopies").value;
    var totalr = 0;

    var pagesParExemplaires = parseFloat(nbcopie) + parseFloat(couleur1) + parseFloat(photocopies);

    //Recto verso qui divise par 2 le nombre de pages reliées
    if (document.getElementById("rectoverso").checked == true) {
        var pagesParExemplaires = Math.round(pagesParExemplaires / 2);
    }

    //Sans reliure
    if (reliures == 1) {
        totalr = 0;
    }

    //Reliures plastiques
    else if (reliures == 2) {
        if (ExemplaireT <= 30) {
            if (pagesParExemplaires <= 45) {
                totalr = ExemplaireT * 2.10;
            } else if (pagesParExemplaires <= 95) {
                totalr = ExemplaireT * 2.60;
            } else if (pagesParExemplaires <= 145) {
                totalr = ExemplaireT * 3.10;
            } else if (pagesParExemplaires <= 240) {
                totalr = ExemplaireT * 3.60;
            } else if (pagesParExemplaires <= 300) {
                totalr = ExemplaireT * 4.60;
            } else if (pagesParExemplaires >= 301) {
                totalr = ExemplaireT * 5.10;
            }
        } else if (ExemplaireT > 30) {
            if (pagesParExemplaires <= 45) {
                totalr = ExemplaireT * 1.90;
            } else if (pagesParExemplaires <= 95) {
                totalr = ExemplaireT * 2.40;
            } else if (pagesParExemplaires <= 145) {
                totalr = ExemplaireT * 2.90;
            } else if (pagesParExemplaires <= 240) {
                totalr = ExemplaireT * 3.40;
            } else if (pagesParExemplaires <= 300) {
                totalr = ExemplaireT * 4.40;
            } else if (pagesParExemplaires >= 301) {
                totalr = ExemplaireT * 4.60;
            }
        }
    }

    //Reliures métal
    if (reliures == 3) {
        if (pagesParExemplaires <= 32) {
            totalr = ExemplaireT * 3.90;
        } else if (pagesParExemplaires <= 64) {
            totalr = ExemplaireT * 4.40;
        } else if (pagesParExemplaires <= 79) {
            totalr = ExemplaireT * 4.90;
        } else if (pagesParExemplaires <= 110) {
            totalr = ExemplaireT * 5.40;
        }
    }

    //Reliures thermo
    else if (reliures == 4) {
        totalr = ExemplaireT * 4;
    }
    //Avec ou sans Rodoid
    if (document.getElementById("rodoid").checked == true && reliures != 1 && reliures != 4) {
        var totalr = totalr - 1.10;
    }
    if (document.getElementById("rodoid").checked == true && reliures == 4) {
        var totalr = totalr - 1.00;
    }
    window.document.getElementById("zone4").innerHTML = totalr.toFixed(2);
    return totalr;
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
    return prixcouv;
}

/*    // Calcul autre
    function CalculAutre() {
        var autre = window.document.getElementById("autre").value;
        var totala;

        if (autre >= 0) {
            totala = autre * 0.01;
        }

        window.document.getElementById("zone5").innerHTML = totala.toFixed(2);
    }
*/

// Calcule le prix total des copies noir&blanc, couleurs, photocopies, reliures et couvertures
function calculTCopies() {
    let zone1 = document.getElementById("zone1").innerHTML;
    let zone2 = document.getElementById("zone2").innerHTML;
    // let zone3 = document.getElementById("zone3").innerHTML;
    let zone4 = document.getElementById("zone4").innerHTML;
    let zone5 = document.getElementById("zone5").innerHTML;
    // let zone8 = document.getElementById("zone8").innerHTML;
    // let zone9 = document.getElementById("zone9").innerHTML;
    // let zone10 = document.getElementById("zone10").innerHTML;
    // let zone11 = document.getElementById("zone11").innerHTML;
    // let zone12 = document.getElementById("zone12").innerHTML;

    // // Calcul de la TVA
    // let TVA = 0;
    // if (document.getElementById("TVA").value != 1) {
    //     if (document.getElementById("TVA").value == 2) {
    //         TVA = 0.055;
    //     } else if (document.getElementById("TVA").value == 3) {
    //         TVA = 0.1;
    //     } else if (document.getElementById("TVA").value == 4) {
    //         TVA = 0.2;
    //     }
    // }

    let totalZ = Number(parseFloat(zone1) + parseFloat(zone2) + parseFloat(zone3) + parseFloat(zone4) + parseFloat(zone5) + parseFloat(zone8) + parseFloat(zone9) + parseFloat(zone10) + parseFloat(zone11) + parseFloat(zone12));
    // let totalY = totalZ;

    // window.document.getElementById("zoneTVA").innerHTML = (totalZ * TVA).toFixed(2);

    // if (totalZ) {
    //     window.document.getElementById("zone6").value = totalZ.toFixed(2) + "€";
    // } else {
    //     window.document.getElementById("zone6").value = "0.00€";
    // }
    // window.document.getElementById("zoneRe").innerHTML = "Remise étudiante: - " + (totalY - totalZ).toFixed(2) + "€";

    // //Remise étudiante 10%
    // if (document.getElementById("remiseEtudiant").checked == true) {
    //     window.document.getElementById("zone6").value = (totalZ * 0.90).toFixed(2) + "€";
    //     window.document.getElementById("zoneRe").innerHTML = "Remise étudiante: - " + (totalY - totalZ * 0.90).toFixed(2) + "€";
    // }
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