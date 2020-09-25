$(function () {
	
	$('#dossier').click(myfunc);
	function myfunc(){alert('ok')};

    // Single call to the database to store values in a json variable.
    var jsonData = {};
    $.ajax({
        url: 'models/DossierDataMgr.php',
        async: false,
        dataType: 'json',
        success: function (json) {
            jsonData = json;
        }
    });

    function resetOptions() {
        $('#btnFTCouv, #btnFCCouv, #couvCouleurFC :radio, #btnFTDos, #btnFCDos, #dosCouleurFC :radio, #thermo, #spiplast, #spimetal, #reliureNoire, #reliureBlanche, #rectoverso').prop('checked', false).prop('disabled', false);
        $('#quantity').val('1');
        $('div.dos-color, div.couv-color').show();
        $('[id^=error-]').css('display', 'none');
        $('[class^=error-]').css('display', 'none');
    }
    resetOptions();
	
	function scrollToElement(elmnt) {
		$("html, body").animate({scrollTop:$(elmnt).offset().top}, 200, "linear");
	}

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
                url: 'models/TraitementDonneesDossierFormulaire.php',
                contentType: false,
                processData: false,
                data: form_data,
                success: function (reponse) {
                    if (reponse == 'failure' || reponse == 'notPDF' || reponse == 'tooHeavy' || reponse == 'dwldErr') {
                        $("#detailPages").hide();
                        $("#file_description").hide();
                        if (reponse == 'failure') {
                            alert('Le traitement du fichier à échoué.');
                        } else if (reponse == 'notPDF') {
                            alert('Le fichier n\'est pas un PDF.');
                        } else if (reponse == 'tooHeavy') {
                            alert('Le fichier envoyé est trop lourd.');
                        } else if (reponse == 'dwldErr') {
                            alert('Erreur de téléchargement, veuillez réessayer.');
                        }
                        $("#uploadPDF").replaceWith($("#uploadPDF").val('').clone(true)); //Reset valeur de l'upload
                        $("#nbPages").html("0"); //Reset toute les valeurs à 0
                        $("#nbPagesC").attr("value", "0");
                        $("#nbPagesNB").attr("value", "0");
                    } else {
                        var obj = JSON.parse(reponse);
                        if (obj.NbPagesNB == obj.NbPages) {
                            var paragInfo = "Ce document comporte " + obj.NbPages + " pages, toutes en noir et blanc.";
                        } else if (obj.NbPagesC == obj.NbPages) {
                            var paragInfo = "Ce document comporte " + obj.NbPages + " pages, toutes en couleur.";
                        } else {
                            var paragInfo = "Ce document comporte " + obj.NbPages + " pages, dont " + obj.NbPagesNB + " en noir et blanc et " + obj.NbPagesC + " en couleurs.";
                        }
                        $("#detailPages").show();
                        $("#file_description").show().html(paragInfo);
                        $("#nomFichier").html(obj.filename_client);
                        $("input[name='nomFichier']").val(obj.filename);
                        $("input[name='nomFichier_client']").val(obj.filename_client);
                        $("#nbPages").html(obj.NbPages);
                        $("input[name='nbPages']").val(obj.NbPages);
                        $("#nbPagesC").html(obj.NbPagesC);
                        $("input[name='nbPagesC']").val(obj.NbPagesC);
                        $("#nbPagesNB").html(obj.NbPagesNB);
                        $("input[name='nbPagesNB']").val(obj.NbPagesNB);
                        $('#loading').empty();

                        calculDevis(jsonData);
                    }
                },
                beforeSend: function () {
                    $('#loading').html('<p></p><div class="text-center"><img src="/public/img/ajax-loader.gif" alt="Chargement…"></div>');
                    $('#file_description').hide();
                    $('#detailPages').hide();
                },
                complete: function () {
                    $('#loading').empty();
                }
            });
        }
    });



    // ---------------------------- REMOVE ALERT MESSAGES ----------------------------
    // Remove the related alert message on any button click
    $('#uploadPDF, #dossier, #memoire, #these, #perso, #btnFTCouv, #btnFCCouv, #couvCouleurFC :radio, #btnFTDos, #btnFCDos, #dosCouleurFC :radio, #thermo, #spiplast, #spimetal, #reliureNoire, #reliureBlanche, #rectoverso, #quantity').click(function () {
        // Remove alert on upload PDF
        if (
            ($(this)[0] == $('#uploadPDF')[0]) &&
            ($('span#error-upload').hasClass("bg-danger"))
        ) {
            $('span#error-upload').removeClass("d-block p-2 bg-danger text-white").css('display', 'none');
        }
        // Remove alert on doc type
        if (
            ($(this)[0] == $('#dossier')[0] ||
                $(this)[0] == $('#memoire')[0] ||
                $(this)[0] == $('#these')[0] ||
                $(this)[0] == $('#perso')[0]) &&
            $('.error-doctype').hasClass("bg-danger")
        ) {
            $('.error-doctype').removeClass('d-block p-2 bg-danger text-white').css('display', 'none');
        }
        // Remove alert on "imprimable / non imprimable"
        if (
            ($(this)[0] == $('input[name=couv-impr]')[0] ||
                $(this)[0] == $('input[name=couv-impr]')[1]) &&
            $('#error-couv-print').hasClass("bg-danger")
        ) {
            $('#error-couv-print').removeClass('d-block d-inline p-2 bg-danger text-white').css('display', 'none');
        }
        // Remove alert on colors
        if ($(this)[0].name == 'couv_color' &&
            $('#error-couv-color').hasClass("bg-danger")
        ) {
            $('#error-couv-color').removeClass('d-block d-inline p-2 bg-danger text-white').css('display', 'none');
        }
        // Remove alert on "imprimable / non imprimable"
        if (($(this)[0] == $('input[name=dos-impr]')[0] ||
                $(this)[0] == $('input[name=dos-impr]')[1]) &&
            $('#error-dos-print').hasClass("bg-danger")
        ) {
            $('#error-dos-print').removeClass('d-block d-inline p-2 bg-danger text-white').css('display', 'none');
        }
        // Remove alert on colors
        if ($(this)[0].name == 'dos_color' &&
            $('#error-dos-color').hasClass("bg-danger")
        ) {
            $('#error-dos-color').removeClass('d-block d-inline p-2 bg-danger text-white').css('display', 'none');
        }
        // Remove alert on "Reliure" type
        if (($(this)[0] == $('input[name=btnReliure]')[0] ||
                $(this)[0] == $('input[name=btnReliure]')[1] ||
                $(this)[0] == $('input[name=btnReliure]')[2]) &&
            $('#error-reliure').hasClass("bg-danger")
        ) {
            $('#error-reliure').removeClass('d-block d-inline p-2 bg-danger text-white').css('display', 'none');
        }
        // Remove alert on "Reliure" color
        if ($(this)[0].name == 'btnCoulReliure' &&
            $('#error-color-reliure').hasClass("bg-danger")
        ) {
            $('#error-color-reliure').removeClass('d-block d-inline p-2 bg-danger text-white').css('display', 'none');
        }
    });


    // ---------------------------- FORCE SELECTED OPTIONS according to the document type ----------------------------
    $("#dossier").click(function () {
        // Reset form options and alert messages
        resetOptions();
        if (!$('span#error-upload').hasClass("bg-danger")) {
            $('[id^=error-]').removeClass('d-block d-inline p-2 bg-danger text-white');
            $('[class^=error-]').removeClass('d-block d-inline p-2 bg-danger text-white');
        }
        // Forced options
        $('#btnFTCouv, #btnFCDos').prop('checked', true).prop('disabled', true);
        $('#btnFCCouv, #btnFTDos').prop('disabled', true);
        $('#dos_printable, #couv_printable, #couv_unprintable').prop('disabled', true);
        $('#dos_unprintable').prop('checked', true).prop('disabled', true);
        $('div.couv-color :radio, label[id^=label_couv_color_]').prop('disabled', true);
        // Hide  non unprintable colors
        $('div.dos-unprintable-0').hide();
    });
    $("#memoire").click(function () {
        // Reset form options and alert messages
        resetOptions();
        if (!$('span#error-upload').hasClass("bg-danger")) {
            $('[id^=error-]').removeClass('d-block d-inline p-2 bg-danger text-white');
            $('[class^=error-]').removeClass('d-block d-inline p-2 bg-danger text-white');
        }
        // Forced options
        $('#btnFTCouv, #btnFCCouv, #btnFCDos').prop('checked', true).prop('disabled', true);
        $('#btnFTDos').prop('disabled', true);
        $('#couv_printable').prop('checked', true).prop('disabled', true);
        $('#couv_unprintable').prop('disabled', true);
        $('div.couv-printable-0').hide();
        $('#dos_printable').prop('disabled', true);
        $('#dos_unprintable').prop('checked', true).prop('disabled', true);
        $('div.dos-unprintable-0').hide();
    });
    $("#these").click(function () {
        // Reset form options and alert messages
        resetOptions();
        if (!$('span#error-upload').hasClass("bg-danger")) {
            $('[id^=error-]').removeClass('d-block d-inline p-2 bg-danger text-white');
            $('[class^=error-]').removeClass('d-block d-inline p-2 bg-danger text-white');
        }
        // Forced options
        $('#btnFCCouv, #btnFCDos').prop('checked', true).prop('disabled', true);
        $('#couv_printable, #dos_printable').prop('checked', true).prop('disabled', true);
        $('#couv_unprintable, #dos_unprintable').prop('disabled', true);
        $('div.couv-printable-0, div.dos-printable-0').hide();
        $('#thermo').prop('checked', true).prop('disabled', true);
        $('#spiplast, #spimetal').prop('disabled', true);
    });
    $("#perso").click(function () {
        // Reset form options and alert messages
        resetOptions();
        if (!$('span#error-upload').hasClass("bg-danger")) {
            $('[id^=error-]').removeClass('d-block d-inline p-2 bg-danger text-white');
            $('[class^=error-]').removeClass('d-block d-inline p-2 bg-danger text-white');
        }
        // Options logic
        $('#btnFCCouv').click(function () {
            if ($('#btnFCCouv').is(":not(:checked)")) {
                $('#couv_printable, #couv_unprintable').prop('checked', false);
                $('div.couv-color').show();
                $('div.couv-color :radio').prop('checked', false);
            }
        });
        $('#couv_printable').click(function () {
            $('div.couv-color').show();
            $('div.couv-printable-0').hide();
            $('div.couv-color :radio').prop('checked', false);
        });
        $('#couv_unprintable').click(function () {
            $('div.couv-color').show();
            $('div.couv-unprintable-0').hide();
            $('div.couv-color :radio').prop('checked', false);
        });
        $('#btnFCDos').on('click', function () {
            if ($(this).is(":not(:checked)")) {
                $('#dos_printable, #dos_unprintable').prop('checked', false);
                $('div.dos-color').show();
                $('div.dos-color :radio').prop('checked', false);
            }
        });
        $('#dos_printable').click(function () {
            $('div.dos-color').show();
            $('div.dos-printable-0').hide();
            $('div.dos-color :radio').prop('checked', false);
        });
        $('#dos_unprintable').click(function () {
            $('div.dos-color').show();
            $('div.dos-unprintable-0').hide();
            $('div.dos-color :radio').prop('checked', false);
        });
    });



    // ---------------------------- AUTO SELECTING OPTIONS ----------------------------
    // Force uploading PDF before choosing options
    $('#dossier, #memoire, #these, #perso, #btnFTCouv, #btnFTDos, #btnFCCouv, #btnFCDos, #thermo, #spiplast, #spimetal, #reliureNoire, #reliureBlanche, #quantity, #rectoverso, #couvCouleurFC :radio, #dosCouleurFC :radio, div.dos-color, div.couv-color').click(function () {
        items = $('#uploadPDF')[0].files;
        if (typeof items == 'undefined' || items == null || items.length == 0) {
            $('span#error-upload').addClass('d-block p-2 bg-danger text-white').click(function () {
                $(this).removeClass('d-block d-inline p-2 bg-danger text-white').css('display', 'none');
            });
			scrollToElement("#uploadPDF");
        }
    });
    // On any button click - other than uploadPDF and doctype -
    $('#btnFTCouv, #btnFTDos, #btnFCCouv, #btnFCDos, #thermo, #spiplast, #spimetal, #reliureNoire, #reliureBlanche, #quantity, #rectoverso, #couvCouleurFC :radio, #dosCouleurFC :radio, #couv_printable, #couv_unprintable, div.couv-color, #dos_printable, #dos_unprintable, div.dos-color').click(function () {
        // Set 'perso' by default if an option is selected and no doctype is selected
        if ($('#dossier').is(":not(:checked)") && $('#memoire').is(":not(:checked)") && $('#these').is(":not(:checked)") && $('#perso').is(":not(:checked)")) {
            $('#perso').prop('checked', true);
        }
    });
    // On click on FC options
    $('#couv_printable, #couv_unprintable, div.couv-color').click(function () {
        // select FC if it is not
        if ($('#btnFCCouv').is(":not(:checked)") && $('#dossier').is(":not(:checked)")) {
            $('#btnFCCouv').prop('checked', true);
        }
    });
    // On click on FC options
    $('#dos_printable, #dos_unprintable, div.dos-color').click(function () {
        // select FC if it is not
        if ($('#btnFCDos').is(":not(:checked)")) {
            $('#btnFCDos').prop('checked', true);
        }
    });
    // Option logic once $(#perso') is auto checked
    $('#btnFCCouv').click(function () {
        if ($("#perso").is(':checked') && $('#btnFCCouv').is(":not(:checked)")) {
            $('#couv_printable, #couv_unprintable').prop('checked', false);
            $('div.couv-color').show();
            $('div.couv-color :radio').prop('checked', false);
        }
    });
    $('#couv_printable').click(function () {
        if ($("#perso").is(':checked')) {
            $('div.couv-color').show();
            $('div.couv-printable-0').hide();
            $('div.couv-color :radio').prop('checked', false);
        }
    });
    $('#couv_unprintable').click(function () {
        if ($("#perso").is(':checked')) {
            $('div.couv-color').show();
            $('div.couv-unprintable-0').hide();
            $('div.couv-color :radio').prop('checked', false);
        }
    });
    $('#btnFCDos').on('click', function () {
        if ($("#perso").is(':checked') && $(this).is(":not(:checked)")) {
            $('#dos_printable, #dos_unprintable').prop('checked', false);
            $('div.dos-color').show();
            $('div.dos-color :radio').prop('checked', false);
        }
    });
    $('#dos_printable').click(function () {
        if ($("#perso").is(':checked')) {
            $('div.dos-color').show();
            $('div.dos-printable-0').hide();
            $('div.dos-color :radio').prop('checked', false);
        }
    });
    $('#dos_unprintable').click(function () {
        if ($("#perso").is(':checked')) {
            $('div.dos-color').show();
            $('div.dos-unprintable-0').hide();
            $('div.dos-color :radio').prop('checked', false);
        }
    });



    // ---------------------------- QUOTE CALCULATION ----------------------------
    // Reload the quote calculation on click
    $("#thermo, #spiplast, #spimetal, #btnFTCouv, #btnFTDos, #btnFCCouv, #btnFCDos, #quantity, #rectoverso, #perso, #these, #memoire, #dossier").click(function () {
        calculDevis(jsonData);
    });

    // Quote calculation
    function calculDevis(jsonData) {
        let quantity = $("#quantity").val();
        let totalNB = Number(calculPages('NB', jsonData['paliersNB'], quantity));
        let totalC = Number(calculPages('C', jsonData['paliersC'], quantity));
        let totalCouvFC = Number(calculCouvFC(jsonData['prixFC']));
        let totalCouvFT = Number(calculCouvFT(jsonData['prixFT']));
        let totalR = Number(calculReliure(jsonData['maxFeuillesPlast'], jsonData['maxFeuillesMetal'], jsonData['maxFeuillesThermo'], jsonData['paliersSpiplast'], jsonData['paliersSpimetal'], jsonData['paliersThermo']));
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
        let quantity = $("input[name='btnReliure']").is(":checked") ? $("#quantity").val() : 0;
        let nbFeuilles = Number($("#nbPages").text()); // pages N&B et Couleur SANS recto-verso
        let total = 0;
        let prixU = 0;
        let i = 0;

        if ($("#rectoverso").prop('checked')) {
            nbFeuilles = Math.round(nbFeuilles / 2); // pas besoin de modulo % car .5 est arrondi au-dessus
        }

        if ($('#spiplast').prop('checked')) {
            if (nbFeuilles > maxSpiplast) {
                alert("Les spirales plastiques ne sont disponibles que pour " + maxSpiplast + " pages maximum.\nAlternatives : recto-verso ou diviser le document en plusieurs parties.");
                $('#spiplast').prop('checked', false)
            }
            while (paliersSpiplast[i + 1] && (nbFeuilles > paliersSpiplast[i]['palier'])) {
                i++;
            }
            prixU = Number(paliersSpiplast[i]["prix"]).toFixed(2);
            total = Number(quantity * prixU).toFixed(2);
        }
        if ($('#spimetal').prop('checked')) {
            if (nbFeuilles > maxSpimetal) {
                alert("Les spirales métalliques ne sont disponibles que pour " + maxSpimetal + " pages maximum.\nAlternatives : recto-verso ou diviser le document en plusieurs parties.");
                $('#spimetal').prop('checked', false)
            }
            while (paliersSpimetal[i + 1] && (nbFeuilles > paliersSpimetal[i]['palier'])) {
                i++;
            }
            prixU = Number(paliersSpimetal[i]["prix"]).toFixed(2);
            total = Number(quantity * prixU).toFixed(2);
        }
        if ($('#thermo').prop('checked')) {
            if (nbFeuilles > maxThermo) {
                alert("La reliure thermocollée n'est disponible que pour " + maxThermo + " pages maximum.\nAlternatives : recto-verso ou diviser le document en plusieurs parties.");
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
        let prixU = Number(dataFC).toFixed(2);

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
        let prixU = Number(dataFT).toFixed(2);

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



    // ---------------------------- CHECK FORM VALIDITY ----------------------------
    $('#submit').click(function () {
        return validateForm();
    });

    function validateForm() {
        // check if PDF is uploaded
        items = $('#uploadPDF')[0].files;
        if (typeof items == 'undefined' || items == null || items.length == 0) {
            $('span#error-upload').addClass("d-block p-2 bg-danger text-white").click(function () {
                $(this).removeClass('d-block d-inline p-2 bg-danger text-white').css('display', 'none');
            });
			scrollToElement("#uploadPDF");
            return false;
        }
        // check if doc type is selected
        if (!$('#dossier').prop('checked') && !$('#memoire').prop('checked') && !$('#these').prop('checked') && !$('#perso').prop('checked')) {
            $('.error-doctype').addClass("d-block p-2 bg-danger text-white").click(function () {
                $('.error-doctype').removeClass('d-block d-inline p-2 bg-danger text-white').css('display', 'none');
            });
			scrollToElement("#legend_doctype");
            return false;
        }
        // FIRST PAGE
        // when FIRST page is selected, check if (un)printable option is set
        if ($('#btnFCCouv').prop('checked') && (!$('input[name=couv-impr]')[0].checked && !$('input[name=couv-impr]')[1].checked)) {
            $('#error-couv-print').addClass("d-inline p-2 bg-danger text-white").click(function () {
                $(this).removeClass('d-block d-inline p-2 bg-danger text-white').css('display', 'none');
            });
			scrollToElement("#couvCouleurFC");
            return false;
        }
        // when (un)printable option is set for the FIRST page of the document, check if color is selected
        if (($('input[name=couv-impr]')[0].checked || $('input[name=couv-impr]')[1].checked) && $('input[name="couv_color"]:checked').val() == null) {
            $('#error-couv-color').addClass("d-inline p-2 bg-danger text-white").click(function () {
                $(this).removeClass('d-block d-inline p-2 bg-danger text-white').css('display', 'none');
            });
			scrollToElement("#couvCouleurFC");
            return false;
        }

        // LAST PAGE
        // when LAST page is selected, check if (un)printable option is set
        if ($('#btnFCDos').prop('checked') && (!$('input[name=dos-impr]')[0].checked && !$('input[name=dos-impr]')[1].checked)) {
            $('#error-dos-print').addClass("d-block p-2 bg-danger text-white").click(function () {
                $(this).removeClass('d-block d-inline p-2 bg-danger text-white').css('display', 'none');
            });
			scrollToElement("#dosCouleurFC");
            return false;
        }
        // when (un)printable option is set for the LAST page of the document, check if color is selected
        if (($('input[name=dos-impr]')[0].checked || $('input[name=dos-impr]')[1].checked) && $('input[name="dos_color"]:checked').val() == null) {
            $('#error-dos-color').addClass("d-inline p-2 bg-danger text-white").click(function () {
                $(this).removeClass('d-block d-inline p-2 bg-danger text-white').css('display', 'none');
            });
			scrollToElement("#dosCouleurFC");
            return false;
        }

        // RELIURE
        // check if reliure type is selected
        if (!$('input[name=btnReliure]')[0].checked && !$('input[name=btnReliure]')[1].checked && !$('input[name=btnReliure]')[2].checked) {
            $('#error-reliure').addClass("d-block p-2 bg-danger text-white").click(function () {
                $(this).removeClass('d-block d-inline p-2 bg-danger text-white').css('display', 'none');
            });
			scrollToElement("#type_reliure");
            return false;
        }
        // when type of reliure is selected, check if color is selected
        if (($('input[name=btnReliure]')[0].checked || $('input[name=btnReliure]')[1].checked || $('input[name=btnReliure]')[2].checked) && $('input[name="btnCoulReliure"]:checked').val() == null) {
            $('#error-color-reliure').addClass("d-block p-2 bg-danger text-white").click(function () {
                $(this).removeClass('d-block d-inline p-2 bg-danger text-white').css('display', 'none');
            });
			scrollToElement("#type_reliure");
            return false;
        }

        return true;
    }

    // Prevent form validation
    $("body").keypress(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
        }
    });

});