$(function () {
    // hide buttons that are not meant to be selectable
    $('#dossier, #memoire, #these, #perso, #couvCouleurFC :radio, #dosCouleurFC :radio, #btnFTCouv, #btnFTDos, #btnFCCouv, #btnFCDos, #thermo, #spiplast, #spimetal, #reliureNoire, #reliureBlanche, #quantity, #rectoverso').prop('checked', false).prop('disabled', true);

    // AJAX call to calculate the number of black and white or colored pages
    $("#formDossier")[0].reset(); // reset the form for firefox
    $("#file_description").hide();
    $("#detailPages").hide();
    // First step : upload PDF
    $("#uploadPDF").change(function () {
        let fichier = $('#uploadPDF').prop('files')[0];
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
                        $("#file_description").hide();
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
                            var paragInfo = "Ce document comporte " + obj.NbPages + " pages, dont " + obj.NbPagesC + " en couleurs et " + obj.NbPagesNB + " en noir et blanc.<br>";
                        }
                        $("#detailPages").show();
                        $("#file_description").show().html(paragInfo);
                        $("#nomFichier").html(fichier.name);
                        $("#nbPages").html(obj.NbPages);
                        $("#nbPagesC").html(obj.NbPagesC);
                        $("#nbPagesNB").html(obj.NbPagesNB);
						$('#loading').empty();
                        // Make type of document available to choose when pdf is loaded.
                        $('#dossier, #memoire, #these, #perso').prop('checked', false).prop('disabled', false);
                        calculDevis(jsonData);
                    }
                },
                beforeSend: function () {
					$('#loading').html('<img src="/public/img/spinner.gif" alt="Chargement…">');
                    $('#file_description').hide();
                },
                complete: function () {
                    $('#loading').empty();
                }
            });
            //$("#erreur").hide();
        }
    });

    // Single call to the database and storage of values in the jsonData variable.
    var jsonData = null;
    $.getJSON("models/getDataPaliersNB.php", function (paliersNB) {
        $.getJSON("models/getDataPaliersC.php", function (paliersC) {
            $.getJSON("models/getDataFC.php", function (paliersFC) {
                $.getJSON("models/getDataFT.php", function (paliersFT) {
                    $.getJSON("models/getMaxSpiplast.php", function (maxSpiplast) {
                        $.getJSON("models/getMaxSpimetal.php", function (maxSpimetal) {
                            $.getJSON("models/getMaxThermo.php", function (maxThermo) {
                                $.getJSON("models/getPaliersSpiplast.php", function (paliersSpiplast) {
                                    $.getJSON("models/getPaliersSpimetal.php", function (paliersSpimetal) {
                                        $.getJSON("models/getPaliersThermo.php", function (paliersThermo) {
                                            jsonData = {
                                                'paliersNB': paliersNB,
                                                'paliersC': paliersC,
                                                'paliersFC': paliersFC,
                                                'paliersFT': paliersFT,
                                                'maxSpiplast': maxSpiplast,
                                                'maxSpimetal': maxSpimetal,
                                                'maxThermo': maxThermo,
                                                'paliersSpiplast': paliersSpiplast,
                                                'paliersSpimetal': paliersSpimetal,
                                                'paliersThermo': paliersThermo
                                            };
                                        })
                                    })
                                })
                            })
                        })
                    })
                })
            })
        })
    });

    //  and force the selection of options according to the type of document
    $("#dossier").on('click', function () {
        $('#couvCouleurFC :radio, #thermo, #spiplast, #spimetal, #reliureNoire, #reliureBlanche, #quantity, #rectoverso').prop('checked', false).prop('disabled', true);
        $('#btnFTCouv').prop('checked', true).prop('disabled', true);
        $('#btnFCDos').prop('checked', true).prop('disabled', true);
        $('#dosCouleurFC :radio').prop('checked', false).prop('disabled', false);
        $('#dosCouleurFC :radio').on('click', function () {
            $('#thermo, #spiplast, #spimetal').prop('disabled', false);
        });
        $('#thermo, #spiplast, #spimetal').on('click', function () {
            $('#reliureNoire, #reliureBlanche').prop('disabled', false);
        });
        $('#reliureNoire, #reliureBlanche').on('click', function () {
            $('#quantity, #rectoverso').prop('disabled', false);
        });
    });
    $("#memoire").on('click', function () {
        $('#dosCouleurFC :radio, #btnFTDos, #thermo, #spiplast, #spimetal, #reliureNoire, #reliureBlanche, #quantity, #rectoverso').prop('checked', false).prop('disabled', true);
        $('#btnFTCouv').prop('checked', true).prop('disabled', true);
        $('#btnFCCouv').prop('checked', true).prop('disabled', true);
        $('#btnFCDos').prop('checked', true).prop('disabled', true);
        $('#couvCouleurFC :radio').prop('checked', false).prop('disabled', false);
        $('#couvCouleurFC :radio').on('click', function () {
            $('#dosCouleurFC :radio').prop('disabled', false);
        });
        $('#dosCouleurFC :radio').on('click', function () {
            $('#thermo, #spiplast, #spimetal').prop('disabled', false);
        });
        $('#thermo, #spiplast, #spimetal').on('click', function () {
            $('#reliureNoire, #reliureBlanche').prop('disabled', false);
        });
        $('#reliureNoire, #reliureBlanche').on('click', function () {
            $('#quantity, #rectoverso').prop('disabled', false);
        });
    });
    $("#these").on('click', function () {
        $('#dosCouleurFC :radio, #spiplast, #spimetal, #reliureNoire, #reliureBlanche, #quantity, #rectoverso').prop('checked', false).prop('disabled', true);
        $('#btnFTCouv').prop('checked', true).prop('disabled', false);
        $('#btnFTDos').prop('checked', true).prop('disabled', false);
        $('#btnFCCouv').prop('checked', true).prop('disabled', true);
        $('#btnFCDos').prop('checked', true).prop('disabled', true);
        $('#thermo').prop('checked', true).prop('disabled', true);
        $('#couvCouleurFC :radio').prop('checked', false).prop('disabled', false);
        $('#couvCouleurFC :radio').on('click', function () {
            $('#dosCouleurFC :radio').prop('disabled', false);
        });
        $('#dosCouleurFC :radio').on('click', function () {
            $('#thermo').prop('checked', true).prop('disabled', true);
            $('#spiplast, #spimetal').prop('disabled', true);
            $('#reliureNoire, #reliureBlanche').prop('disabled', false);
        });
        $('#reliureNoire, #reliureBlanche').on('click', function () {
            $('#quantity, #rectoverso').prop('disabled', false);
        });
    });
    $("#perso").on('click', function () {
        $('#couvCouleurFC :radio, #dosCouleurFC :radio, #thermo, #spiplast, #spimetal, #reliureNoire, #reliureBlanche, #quantity, #rectoverso').prop('checked', false).prop('disabled', true);
        $('#btnFTCouv').prop('checked', false).prop('disabled', false);
        $('#btnFTDos').prop('checked', false).prop('disabled', false);
        $('#btnFCCouv').prop('checked', false).prop('disabled', false);
        $('#btnFCDos').prop('checked', false).prop('disabled', false);
        $('#btnFCCouv').on('click', function () {
            $('#couvCouleurFC :radio').prop('disabled', false);
        });
        $('#btnFCDos').on('click', function () {
            $('#dosCouleurFC :radio').prop('disabled', false);
        });
        $('#thermo, #spiplast, #spimetal').prop('checked', false).prop('disabled', false);
        $('#thermo, #spiplast, #spimetal').on('click', function () {
            $('#reliureNoire, #reliureBlanche').prop('disabled', false);
        });
        $('#reliureNoire, #reliureBlanche').on('click', function () {
            $('#quantity, #rectoverso').prop('disabled', false);
        });
    });

    // Reload the quote calculation on click
    $("#thermo, #spiplast, #spimetal, #btnFTCouv, #btnFTDos, #btnFCCouv, #btnFCDos, #quantity, #rectoverso, #perso, #these, #memoire, #dossier").on('click', function () {
        calculDevis(jsonData);
    });

    // Prevent form validation
    $("body").keypress(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
        }
    });
});

// Quote calculation
function calculDevis(jsonData) {
    let quantity = $("#quantity").val();
    let totalNB = Number(calculPages('NB', jsonData['paliersNB'], quantity));
    let totalC = Number(calculPages('C', jsonData['paliersC'], quantity));
    let totalCouvFC = Number(calculCouvFC(jsonData['paliersFC']));
    let totalCouvFT = Number(calculCouvFT(jsonData['paliersFT']));
    let totalR = Number(calculReliure(jsonData['maxSpiplast'], jsonData['maxSpimetal'], jsonData['maxThermo'], jsonData['paliersSpiplast'], jsonData['paliersSpimetal'], jsonData['paliersThermo']));
    let total = Number(totalNB + totalC + totalR + totalCouvFC + totalCouvFT).toFixed(2);
    calculTVA(total);
    $("#devisTotal").html(total);
}

// Calculate the price of black and white or colored pages
function calculPages(type, paliers, quantity) {
    let nbPages = 0;
    let zone = '';
    let total = 0;
    let nbTotPages = 0;
    let prixU = 0;
    let i = 0;

    if (type === 'NB') {
        nbPages = $("#nbPagesNB").text();
        zone = "#devisPagesNB";
    } else if (type === 'C') {
        nbPages = $("#nbPagesC").text();
        zone = "#devisPagesC";
    }

    if (quantity < 1) {
        quantity = 1;
        alert('Veuillez indiquer 1 exemplaire minimum.');
    }

    nbTotPages = nbPages * quantity;

    while (paliers[i + 1] && (nbTotPages > paliers[i]['palier'])) {
        i++;
    }
    prixU = Number(paliers[i]["prix"]).toFixed(2);
    total = Number(nbTotPages * prixU).toFixed(2);

    $(zone + "Quant").html(nbTotPages);
    $(zone + "PrixU").html(prixU);
    $(zone + "Total").html(total);
    return total;
}

// Calcul du prix des reliures
function calculReliure(maxSpiplast, maxSpimetal, maxThermo, paliersSpiplast, paliersSpimetal, paliersThermo) {
    let zone = '#devisReliure';
    let quantity = $("#quantity").val();
    let nbFeuilles = Number($("#nbPages").text()); // pages N&B et Couleur SANS recto-verso
    let maxFeuillesThermo = maxThermo[0]['sValue'];
    let maxFeuillesPlast = maxSpiplast[0]['sValue'];
    let maxFeuillesMetal = maxSpimetal[0]['sValue'];
    let total = 0;
    let prixU = 0;
    let i = 0;

    if ($("#rectoverso").prop('checked')) {
        nbFeuilles = Math.round(nbFeuilles / 2); // pas besoin de modulo % car .5 est arrondi au-dessus
    }

    if ($('#spiplast').prop('checked')) {
        if (nbFeuilles > maxFeuillesPlast) {
            alert("Les spirales plastiques ne sont disponibles que pour " + maxFeuillesPlast + " pages maximum.\nAlternatives : recto-verso ou diviser le document en plusieurs parties.");
            $('#spiplast').prop('checked', false)
        }
        while (paliersSpiplast[i + 1] && (nbFeuilles > paliersSpiplast[i]['palier'])) {
            i++;
        }
        prixU = Number(paliersSpiplast[i]["prix"]).toFixed(2);
        total = Number(quantity * prixU).toFixed(2);
    }
    if ($('#spimetal').prop('checked')) {
        if (nbFeuilles > maxFeuillesMetal) {
            alert("Les spirales métalliques ne sont disponibles que pour " + maxFeuillesMetal + " pages maximum.\nAlternatives : recto-verso ou diviser le document en plusieurs parties.");
            $('#spimetal').prop('checked', false)
        }
        while (paliersSpimetal[i + 1] && (nbFeuilles > paliersSpimetal[i]['palier'])) {
            i++;
        }
        prixU = Number(paliersSpimetal[i]["prix"]).toFixed(2);
        total = Number(quantity * prixU).toFixed(2);
    }
    if ($('#thermo').prop('checked')) {
        if (nbFeuilles > maxFeuillesThermo) {
            alert("La reliure thermocollée n'est disponible que pour " + maxFeuillesThermo + " pages maximum.\nAlternatives : recto-verso ou diviser le document en plusieurs parties.");
            $('#thermo').prop('checked', false)
        }
        while (paliersThermo[i + 1] && (nbFeuilles > paliersThermo[i]['palier'])) {
            i++;
        }
        prixU = Number(paliersThermo[i]["prix"]).toFixed(2);
        total = Number(quantity * prixU).toFixed(2);
    }

    $(zone + "Quant").html(quantity);
    $(zone + "PrixU").html(prixU);
    $(zone + "Total").html(total);

    return total;
}

//Calcul des Feuilles Cartonnées en première et quatrièmes de couverture.
function calculCouvFC(dataFC) {
    let quantity = $("#quantity").val();
    let nbFC = 0;
    let total = 0;
    let zone = "#devisFC";
    let prixU = Number(dataFC[0]["sValue"]).toFixed(2);

    if ($('#btnFCCouv').prop('checked')) {
        nbFC++;
    }
    if ($('#btnFCDos').prop('checked')) {
        nbFC++;
    }
    if (quantity > 1) {
        nbFC *= quantity;
    }

    total = Number(prixU * nbFC).toFixed(2);

    $(zone + "Quant").html(nbFC);
    $(zone + "PrixU").html(prixU);
    $(zone + "Total").html(total);

    return total;
}

//Calcul des Feuillets Transparents en première et quatrièmes de couverture.
function calculCouvFT(dataFT) {
    let quantity = $("#quantity").val();
    let nbFT = 0;
    let total = 0;
    let zone = "#devisFT";
    let prixU = Number(dataFT[0]["sValue"]).toFixed(2);

    if ($('#btnFTCouv').prop('checked')) {
        nbFT++;
    }
    if ($('#btnFTDos').prop('checked')) {
        nbFT++;
    }
    if (quantity > 1) {
        nbFT *= quantity;
    }

    total = Number(prixU * nbFT).toFixed(2);

    $(zone + "Quant").html(nbFT);
    $(zone + "PrixU").html(prixU);
    $(zone + "Total").html(total);

    return total;
}

// Calcul de la TVA
function calculTVA(totalTTC) {
    let TVA = 0;
    let tauxTVA = 0;

    if ($('#dossier').prop('checked') || $('#perso').prop('checked')) {
        tauxTVA = 0.2;
    }
    if ($('#memoire').prop('checked') || $('#these').prop('checked')) {
        tauxTVA = 0.1;
    }

    TVA = tauxTVA * totalTTC;

    $("#devisTVA").html(TVA.toFixed(2));
}
