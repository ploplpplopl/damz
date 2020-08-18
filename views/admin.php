<?php

if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header('location: index.php?action=login');
}
if (isset($_SESSION['pseudo']) && ($_SESSION['pseudo'] == 'admin')) {

    require('views/head.php');
?>

    <div class="row">
        <div class="col-md-4 offset-md-4 home-wrapper">
			<?php echo displayMessage($errors); ?>
        </div>

        <!-- Paliers -->
        <img id="btn_add" src="btn_add1.jpg" onclick="addRow('table0');" onmouseover="btn_light('btn_add',-1,0);" onmouseout="btn_light('btn_add',-1,1);">

        <input id="cnt" type="hidden" value="4">

        <!-- création du tableau avec 4 lignes au départ -->
        <table id="table0" border="0">
            <tbody>
                <tr id="tr1">
                    <!-- la première cellule contient du texte affichant le numéro de la ligne
             ainsi que deux input text -->
                    <td id="td1">ligne n° 1<input type="text" id="iA1"><input type="text" id="iB1"></td>
                    <!-- la seconde cellule contient un bouton qui permet de supprimer la ligne en cours 
             un bouton qui permet de monter la ligne 
             et un bouton qui permet de descendre la ligne -->
                    <td id="tdL1">
                        <img id="btn_del1" src="btn_del1.jpg" onclick="delRow('table0',1);" onmouseover="btn_light('btn_del',1,0);" onmouseout="btn_light('btn_del',1,1);">
                        <img id="btn_up1" src="btn_up1.jpg" onclick="move('table0',1,-1);" onmouseover="btn_light('btn_up',1,0);" onmouseout="btn_light('btn_up',1,1);">
                        <img id="btn_down1" src="btn_down1.jpg" onclick="move('table0',1,1);" onmouseover="btn_light('btn_down',1,0);" onmouseout="btn_light('btn_down',1,1);">
                    </td>
                </tr>
                <tr id="tr2">
                    <td id="td2">ligne n° 2<input type="text" id="iA2"><input type="text" id="iB2"></td>
                    <td id="tdL2">
                        <img id="btn_del2" src="btn_del1.jpg" onclick="delRow('table0',2);" onmouseover="btn_light('btn_del',2,0);" onmouseout="btn_light('btn_del',2,1);">
                        <img id="btn_up2" src="btn_up1.jpg" onclick="move('table0',2,-1);" onmouseover="btn_light('btn_up',2,0);" onmouseout="btn_light('btn_up',2,1);">
                        <img id="btn_down2" src="btn_down1.jpg" onclick="move('table0',2,1);" onmouseover="btn_light('btn_down',2,0);" onmouseout="btn_light('btn_down',2,1);">
                    </td>
                </tr>
                <tr id="tr3">
                    <td id="td3">ligne n° 3<input type="text" id="iA3"><input type="text" id="iB3"></td>
                    <td id="tdL3">
                        <img id="btn_del3" src="btn_del1.jpg" onclick="delRow('table0',3);" onmouseover="btn_light('btn_del',3,0);" onmouseout="btn_light('btn_del',3,1);">
                        <img id="btn_up3" src="btn_up1.jpg" onclick="move('table0',3,-1);" onmouseover="btn_light('btn_up',3,0);" onmouseout="btn_light('btn_up',3,1);">
                        <img id="btn_down3" src="btn_down1.jpg" onclick="move('table0',3,1);" onmouseover="btn_light('btn_down',3,0);" onmouseout="btn_light('btn_down',3,1);">
                    </td>
                </tr>
                <tr id="tr4">
                    <td id="td4">ligne n° 4<input type="text" id="iA4"><input type="text" id="iB4"></td>
                    <td id="tdL4">
                        <img id="btn_del4" src="btn_del1.jpg" onclick="delRow('table0',4);" onmouseover="btn_light('btn_del',4,0);" onmouseout="btn_light('btn_del',4,1);">
                        <img id="btn_up4" src="btn_up1.jpg" onclick="move('table0',4,-1);" onmouseover="btn_light('btn_up',4,0);" onmouseout="btn_light('btn_up',4,1);">
                        <img id="btn_down4" src="btn_down1.jpg" onclick="move('table0',4,1);" onmouseover="btn_light('btn_down',4,0);" onmouseout="btn_light('btn_down',4,1);">
                    </td>
                </tr>
            </tbody>
        </table>
        <script>
            // fonction getelement
            function getel(elm) {
                return document.getElementById(elm);
            }
            // fonction bidon pour faire flasher les boutons
            function btn_light(btn, idx, i) {
                if (idx != -1) {
                    getel(btn + idx).src = btn + i + '.jpg';
                } else {
                    getel(btn).src = btn + i + '.jpg';
                }
            }
            // fonction d'ajout d'une ligne 
            function addRow(nTable) {
                var ta = getel(nTable);
                // insertion de la ligne en fin de tableau
                var myRow = ta.insertRow(-1);
                // récupération de l'index de la ligne insérée, et on ajoute 1 car on souhaite un tableau allant de la ligne 1 à n
                var idx = myRow.rowIndex + 1;
                // insertion d'une cellule sur la ligne
                var myCell = myRow.insertCell(-1);
                // remplissage de la première cellule avec le texte et les input
                // les identifiants des objets générés contiennent le rowIndex(+1) de la ligne insérée
                myCell.innerHTML = 'ligne n° ' + idx + '<input type="text" id="iA' + idx + '"><input type="text" id="iB' + idx + '">';
                // insertion d'une seconde cellule sur la ligne
                myCell = myRow.insertCell(-1);
                // remplissage de la seconde cellule avec les boutons
                // de la même manière, les identifiants des objets img générés contiennent le rowIndex(+1) de la ligne insérée
                myCell.innerHTML = '<img id="btn_del' + idx + '" src="btn_del1.jpg" onclick="delRow(\'table0\',' + idx + ');" onmouseover="btn_light(\'btn_del\',' + idx + ',0);" onmouseout="btn_light(\'btn_del\',' + idx + ',1);"> <img id="btn_up' + idx + '" src="btn_up1.jpg" onclick="move(\'table0\',' + idx + ',-1);" onmouseover="btn_light(\'btn_up\',' + idx + ',0);" onmouseout="btn_light(\'btn_up\',' + idx + ',1);"> <img id="btn_down' + idx + '" src="btn_down1.jpg" onclick="move(\'table0\',' + idx + ',1);" onmouseover="btn_light(\'btn_down\',' + idx + ',0);" onmouseout="btn_light(\'btn_down\',' + idx + ',1);">';
                getel('cnt').value++;
            }
            // fonction de supression d'une ligne
            function delRow(nTable, idx) {
                var ta = getel(nTable);
                // suppression de la ligne
                // on souhaite supprimer la ligne dont l'index (partant de zéro) correspond à la ligne sur laquelle on a cliqué
                // comme au niveau affichage on a nos lignes numérotées de 1 à n, on retranche 1
                ta.deleteRow(idx - 1);
                // boucle sur toutes les lignes du tableau en partant de 1
                for (i = 0; i < ta.tBodies[0].rows.length; i++) {
                    var j = i + 1;
                    // on remet à niveau l'identifiant de la ligne et des cellules avec l'index de la boucle
                    ta.tBodies[0].rows[i].id = 'tr' + j;
                    ta.tBodies[0].rows[i].cells[0].id = 'td' + j;
                    ta.tBodies[0].rows[i].cells[1].id = 'tdL' + j;
                    // dans la première cellule on remet à niveau le texte affiché et les identifiants des objets input
                    // sans oublier de récupérer les informations qui auraient pu être saisies dans les input
                    var ins = ta.tBodies[0].rows[i].cells[0].getElementsByTagName('input');
                    ta.tBodies[0].rows[i].cells[0].innerHTML = 'ligne n° ' + j + '<input type="text" id="iA' + j + '" value="' + ins[0].value + '"><input type="text" id="iB' + j + '" value="' + ins[1].value + '">';
                    // dans la seconde cellule, on remet à niveau les identifiants des objets img
                    ta.tBodies[0].rows[i].cells[1].innerHTML = '<img id="btn_del' + j + '" src="btn_del1.jpg" onclick="delRow(\'table0\',' + j + ');" onmouseover="btn_light(\'btn_del\',' + j + ',0);" onmouseout="btn_light(\'btn_del\',' + j + ',1);"> <img id="btn_up' + j + '" src="btn_up1.jpg" onclick="move(\'table0\',' + j + ',-1);" onmouseover="btn_light(\'btn_up\',' + j + ',0);" onmouseout="btn_light(\'btn_up\',' + j + ',1);"> <img id="btn_down' + j + '" src="btn_down1.jpg" onclick="move(\'table0\',' + j + ',1);" onmouseover="btn_light(\'btn_down\',' + j + ',0);" onmouseout="btn_light(\'btn_down\',' + j + ',1);">';
                }
                getel('cnt').value--;
            }
            // fonction pour déplacer une ligne le paramètre idx contient le numéro de la ligne sur laquelle on a cliqué (numérotation en partant de 1)
            function move(nTable, idx, sens) {
                var ta = getel(nTable);
                // récupération de toutes les lignes du tableau dans un array
                var trs = ta.tBodies[0].getElementsByTagName('tr');
                // positionnement de l'index de la ligne sur laquelle on a cliqué
                var idxA = idx - 1;
                // récupération dans une variable du contenu de la ligne qui va être "déplacée"
                var tr = trs[idxA].innerHTML;
                // récupération des valeurs saisies dans les input
                var ins = trs[idxA].getElementsByTagName('input');
                // positionnement de l'index de la ligne cible pour le déplacement
                var idxB = idxA + sens;
                // on détermine si on se trouve sur la première ou dernière ligne, de manière à ne pas permettre le déplacement en dehors du tableau
                var dontmove = 0;
                if (idx == 1) {
                    if (sens == -1) dontmove++;
                }
                if (idx == getel('cnt').value) {
                    if (sens == 1) dontmove++;
                }
                if (dontmove == 0) {
                    // suppression de la ligne
                    ta.deleteRow(idxA);
                    // insertion d'une nouvelle ligne à l'index
                    ta.insertRow(idxB);
                    // remplissage de la ligne
                    trs[idxB].innerHTML = tr;
                    // on repositionne les valeurs éventuellement saisies dans les input
                    trs[idxB].getElementsByTagName('input')[0].value = ins[0].value;
                    trs[idxB].getElementsByTagName('input')[1].value = ins[1].value;
                    // boucle sur toutes les lignes du tableau avec petite transposition pour la numérotation affichée des lignes
                    for (i = 0; i < ta.tBodies[0].rows.length; i++) {
                        var j = i + 1;
                        // on remet à niveau l'identifiant de la ligne et des cellules avec l'index de la boucle +1
                        ta.tBodies[0].rows[i].id = 'tr' + j;
                        ta.tBodies[0].rows[i].cells[0].id = 'td' + j;
                        ta.tBodies[0].rows[i].cells[1].id = 'tdL' + j;
                        // dans la première cellule on remet à niveau le texte affiché et les identifiants des objets input
                        // sans oublier de récupérer les informations qui auraient pu être saisies dans les input
                        var ins = ta.tBodies[0].rows[i].cells[0].getElementsByTagName('input');
                        ta.tBodies[0].rows[i].cells[0].innerHTML = 'ligne n° ' + j + '<input type="text" id="iA' + j + '" value="' + ins[0].value + '"><input type="text" id="iB' + j + '" value="' + ins[1].value + '">';
                        // dans la seconde cellule, on remet à niveau les identifiants des objets img
                        ta.tBodies[0].rows[i].cells[1].innerHTML = '<img id="btn_del' + j + '" src="btn_del1.jpg" onclick="delRow(\'table0\',' + j + ');" onmouseover="btn_light(\'btn_del\',' + j + ',0);" onmouseout="btn_light(\'btn_del\',' + j + ',1);"> <img id="btn_up' + j + '" src="btn_up1.jpg" onclick="move(\'table0\',' + j + ',-1);" onmouseover="btn_light(\'btn_up\',' + j + ',0);" onmouseout="btn_light(\'btn_up\',' + j + ',1);"> <img id="btn_down' + j + '" src="btn_down1.jpg" onclick="move(\'table0\',' + j + ',1);" onmouseover="btn_light(\'btn_down\',' + j + ',0);" onmouseout="btn_light(\'btn_down\',' + j + ',1);">';
                    }
                }
            }
        </script>

    </div>
<?php
    require('views/footer.htm');
} else {
    header('location: /index.php?action=logout'); // protège accès direct à http://localhost/views/admin.php (views devra etre interdit avec htaccess)
    // TODO /views devra etre interdit avec htaccess
}
?>