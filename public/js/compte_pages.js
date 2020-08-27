$(function () {
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
                        $("#nomFichier").html(obj.filename);
                        $("input[name='nomFichier']").val(obj.filename);
                        $("#nbPages").html(obj.NbPages);
                        $("input[name='nbPages']").val(obj.NbPages);
                        $("#nbPagesC").html(obj.NbPagesC);
                        $("input[name='nbPagesC']").val(obj.NbPagesC);
                        $("#nbPagesNB").html(obj.NbPagesNB);
                        $("input[name='nbPagesNB']").val(obj.NbPagesNB);
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
    var jsonData = {};
    $.ajax({
        url: 'models/getDossierData.php',
        async: false,
        dataType: 'json',
        success: function (json) {
            jsonData = json;
        }
    });

    function resetOptions() {
        $('#couvCouleurFC :radio, #dosCouleurFC :radio, #btnFTCouv, #btnFTDos, #btnFCCouv, #btnFCDos, #thermo, #spiplast, #spimetal, #reliureNoire, #reliureBlanche, #quantity, #rectoverso, #couv_printable, #couv_unprintable, #dos_printable, #dos_unprintable, div.dos-color, div.couv-color').prop('checked', false).prop('disabled', true);
        $('div.dos-color, div.couv-color').show();
    }
    // hide buttons that are not meant to be selectable
	$('#dossier, #memoire, #these, #perso').prop('checked', false).prop('disabled', true);
	resetOptions();
    //  and force the selection of options according to the type of document
    $("#dossier").on('click', function () {
        resetOptions();
        // checked & disabled : FTCouv et FCDos
        $('#btnFTCouv, #btnFCDos').prop('checked', true); // .prop('disabled', true);

        $('#dos_unprintable').prop('checked', true); //.prop('disabled', true);

        $('div.dos-color :radio').prop('disabled', false);
        $('div.dos-unprintable-0').hide();
        // solution 1 : tout est clicable et alerte si clic sur autre chose que couleur, puis si clic sur autre chose que reliure, puis....
        /*$('#thermo, #spiplast, #spimetal').prop('disabled', false);
        $('#thermo, #spiplast, #spimetal').click(function (event) {
            event.preventDefault(); // empeche la selection du radio btn
            if ($('div.dos-color :radio').not(':checked')) {
                alert('Veuillez sélectionner une couleur pour le dos cartonné');
            }
            $('#reliureNoire, #reliureBlanche').prop('disabled', false);
        });*/
        // solution 2 : suivant selectionnable si précédent checked
        $('div.dos-color :radio').on('click', function () {
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
        resetOptions();
        $('#btnFTCouv, #btnFCCouv').prop('checked', true).prop('disabled', true);
        $('#couv_printable').prop('checked', true); //.prop('disabled', true);
        $('div.couv-color :radio').prop('disabled', false);
        $('div.couv-printable-0').hide();

        $('#btnFCDos').prop('checked', true).prop('disabled', true);
        $('div.dos-color :radio').prop('disabled', false);
        $('div.dos-unprintable-0').hide();
        $('#dos_unprintable').prop('checked', true); //.prop('disabled', true);

        $('#thermo, #spiplast, #spimetal').prop('disabled', false);
        $('#thermo, #spiplast, #spimetal').on('click', function () {
            $('#reliureNoire, #reliureBlanche').prop('disabled', false);
        });
        $('#reliureNoire, #reliureBlanche').on('click', function () {
            $('#quantity, #rectoverso').prop('disabled', false);
        });
    });
    $("#these").on('click', function () {
        resetOptions();
        $('#btnFTCouv ,#btnFTDos').prop('disabled', false); //prop('checked', false).

        $('#btnFCCouv, #btnFCDos').prop('checked', true).prop('disabled', true);
        $('#couv_printable, #dos_printable').prop('checked', true); //.prop('disabled', true);
        $('div.couv-color :radio, div.dos-color :radio').prop('disabled', false);
        $('div.couv-printable-0, div.dos-printable-0').hide();

        $('#thermo').prop('checked', true); //.prop('disabled', true);
        $('#reliureNoire, #reliureBlanche').prop('disabled', false);

        $('#reliureNoire, #reliureBlanche').on('click', function () {
            $('#quantity, #rectoverso').prop('disabled', false);
        });
    });
    $("#perso").on('click', function () {
        resetOptions();
        $('#btnFTCouv, #btnFTDos, #btnFCCouv, #btnFCDos, #thermo, #spiplast, #spimetal').prop('checked', false).prop('disabled', false);

        $('#btnFCCouv').on('click', function () {
            if ($(this).is(':checked')) {
                $('#couv_printable, #couv_unprintable').prop('checked', false).prop('disabled', false);
            } else {
                $('#couv_printable, #couv_unprintable').prop('checked', false).prop('disabled', true);
                $('div.couv-color').show();
                $('div.couv-color :radio').prop('checked', false).prop('disabled', true);
            }
        });
        $('#couv_printable').on('click', function () {
            $('div.couv-color').show();
            $('div.couv-printable-0').hide();
            $('div.couv-color :radio').prop('checked', false).prop('disabled', false);
        });
        $('#couv_unprintable').on('click', function () {
            $('div.couv-color').show();
            $('div.couv-unprintable-0').hide();
            $('div.couv-color :radio').prop('checked', false).prop('disabled', false);
        });

        $('#btnFCDos').on('click', function () {
            if ($(this).is(':checked')) {
                $('#dos_printable, #dos_unprintable').prop('checked', false).prop('disabled', false);
            } else {
                $('#dos_printable, #dos_unprintable').prop('checked', false).prop('disabled', true);
                $('div.dos-color').show();
                $('div.dos-color :radio').prop('checked', false).prop('disabled', true);
            }
        });
        $('#dos_printable').on('click', function () {
            $('div.dos-color').show();
            $('div.dos-printable-0').hide();
            $('div.dos-color :radio').prop('checked', false).prop('disabled', false);
        });
        $('#dos_unprintable').on('click', function () {
            $('div.dos-color').show();
            $('div.dos-unprintable-0').hide();
            $('div.dos-color :radio').prop('checked', false).prop('disabled', false);
        });

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
        $("input[name='total']").val(total);
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

        if ($('#dossier').prop('checked')) {
            tauxTVA = jsonData['tvaDossier'];
        }
        if ($('#perso').prop('checked')) {
            tauxTVA = jsonData['tvaDossier'];
        }
        if ($('#memoire').prop('checked')) {
            tauxTVA = jsonData['tvaMemoire'];
        }
        if ($('#these').prop('checked')) {
            tauxTVA = jsonData['tvaThese'];
        }

        TVA = Number(tauxTVA) * 0.01 * totalTTC;

        $("#devisTVA").html(TVA.toFixed(2));
        $("input[name='tva']").val(TVA.toFixed(2));
    }

	// Re-populate fields at page loading.
	/*let nomFichier = $("input[name='nomFichier']").val(),
		nbPages = $("input[name='nbPages']").val(),
		nbPagesC = $("input[name='nbPagesC']").val(),
		nbPagesNB = $("input[name='nbPagesNB']").val();
	if (nomFichier && nbPages && nbPagesC && nbPagesNB) {
		if (nbPagesNB == nbPages) {
			var paragInfo = "Ce document comporte " + nbPages + " pages, toutes en noir et blanc. <br>";
		} else if (nbPagesC == nbPages) {
			var paragInfo = "Ce document comporte " + nbPages + " pages, toutes en couleur.<br>";
		} else {
			var paragInfo = "Ce document comporte " + nbPages + " pages, dont " + nbPagesC + " en couleurs et " + nbPagesNB + " en noir et blanc.<br>";
		}
		$("#file_description, #detailPages").show();
		$("#file_description").html(paragInfo);
		$("#nomFichier").html(nomFichier);
		$("#nbPages").html(nbPages);
		$("#nbPagesC").html(nbPagesC);
		$("#nbPagesNB").html(nbPagesNB);
	}*/
	
});


















