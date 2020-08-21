$(function () {

     $("#enregistrer_paliers_NB").click(function (e) {
          e.preventDefault();
          var formID = $("#form_paliers_NB");
          var inputs = formID.serializeArray();
          for (i = 0; i < 21; i += 3) {
               var formInputs = [];
               formInputs.push(inputs[i], inputs[i + 1], inputs[i + 2]);
               $.post(
                    "traitement_paliers_NB.php",
                    formInputs,
                    function (data) {
                         try {
                              var reponse = JSON.parse(data);
                              if (reponse.error == true) {
                                   alert(reponse.message);
                              }
                         } catch (err) {
                              alert("Erreur lors de l'actualisation de la base de données");
                         }
                    }
               );
          }
     })


     $("#enregistrer_paliers_C").click(function (e) {
          e.preventDefault();
          var formID = $("#form_paliers_C");
          var inputs = formID.serializeArray();
          for (i = 0; i < 18; i += 3) {
               var formInputs = [];
               formInputs.push(inputs[i], inputs[i + 1], inputs[i + 2]);
               $.post(
                    "traitement_paliers_C.php",
                    formInputs,
                    function (data) {
                         try {
                              var reponse = JSON.parse(data);
                              if (reponse.error == true) {
                                   alert(reponse.message);
                              }
                         } catch (err) {
                              alert("Erreur lors de l'actualisation de la base de données");
                         }
                    }
               );
          }
     })

})