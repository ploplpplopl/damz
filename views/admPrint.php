<?php

if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header('location: index.php?action=login');
    // header("refresh:2;url=index.php?action=login");
}
if (isset($_SESSION['pseudo']) && ($_SESSION['pseudo'] == 'printer' || $_SESSION['pseudo'] == 'admin')) {

    require('views/head.php');
?>

    <div class="row">
        <div class="col-md-4 offset-md-4 home-wrapper">
            <!-- Display messages -->
            <?php if (isset($_SESSION['message'])) : ?>
                <div class="alert <?php echo $_SESSION['type'] ?>">
                    <?php
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                    unset($_SESSION['type']);
                    ?>
                </div>
            <?php endif; ?>

            <!-- <h4>Welcome, <?php echo $_SESSION['pseudo']; ?></h4> -->
            <!-- <a href="index.php?action=logout" style="color: red">Logout</a> -->
            <?php if (!$_SESSION['user']['subsc_confirmed']) : ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    You need to verify your email address!
                    Sign into your email account and click
                    on the verification link we just emailed you
                    at
                    <strong><?php echo $_SESSION['email']; ?></strong>
                </div>
            <?php endif; ?>
        </div>


        <!-- Tab links -->
        <div class="tab">
            <button id="linkTabToPrint" class="tablinks">À IMPRIMER</button>
            <button id="linkTabHisto" class="tablinks">HISTORIQUE</button>
        </div>

        <!-- Tab content -->
        <div id="tabToPrint" class="tabcontent">
            <!-- 2 onglets : "à imprimer" et "historique". 
            Une fois la ligne traitée (supprimée de "à imprimer"), elle est envoyée dans l'"historique", 
            TODO dont le nombre de lignes est réglé dans l'interface d'admin -->
            <div class="responsiveTable">
                <table id="tableToPrint">
                    <tr>
                        <th>Suppr</th>
                        <th>Facture</th>
                        <th>__PDF__</th>
                        <th>Étiquette <br> Transport</th>
                        <th>Nom / Prénom</th>
                        <th>Adresse</th>
                        <th>Téléphone</th>
                    </tr>
                    <tr>
                        <td class="center">btn</td>
                        <td class="center">pdf</td>
                        <td class="center">PDF</td>
                        <td class="center">pdf</td>
                        <td>Nom / Prénom</td>
                        <td>Adresse</td>
                        <td>06 06 06 06 06</td>
                    </tr>
                    <tr>
                        <td class="center">btn</td>
                        <td class="center">pdf</td>
                        <td class="center">PDF</td>
                        <td class="center">pdf</td>
                        <td>Nom / Prénom</td>
                        <td>5 rue de la prévenderie, 14000 caen</td>
                        <td>Téléphone</td>
                    </tr>
                    <tr>
                        <td class="center">btn</td>
                        <td class="center">pdf</td>
                        <td class="center">PDF</td>
                        <td class="center">pdf</td>
                        <td>Nom / Prénom</td>
                        <td>Adresse</td>
                        <td>Téléphone</td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="tabHisto" class="tabcontent">
            <div class="responsiveTable">
                <table id="tableHisto">
                    <tr>
                        <th>Suppr</th>
                        <th>Facture</th>
                        <th>__PDF__</th>
                        <th>Étiquette <br> Transport</th>
                        <th>Nom / Prénom</th>
                        <th>Adresse</th>
                        <th>Téléphone</th>
                    </tr>
                    <tr>
                        <td class="center">btn</td>
                        <td class="center">pdf</td>
                        <td class="center">PDF</td>
                        <td class="center">pdf</td>
                        <td>Nom / Prénom</td>
                        <td>Adresse</td>
                        <td>06 06 06 06 06</td>
                    </tr>
                    <tr>
                        <td class="center">btn</td>
                        <td class="center">pdf</td>
                        <td class="center">PDF</td>
                        <td class="center">pdf</td>
                        <td>Nom / Prénom</td>
                        <td>5 rue de la puterie, 14000 caen</td>
                        <td>Téléphone</td>
                    </tr>
                    <tr>
                        <td class="center">btn</td>
                        <td class="center">pdf</td>
                        <td class="center">PDF</td>
                        <td class="center">pdf</td>
                        <td>Nom / Prénom</td>
                        <td>Adresse</td>
                        <td>Téléphone</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
<?php
    require('views/footer.htm');
} else {
    header('location: /index.php?action=logout'); // protège accès direct à http://localhost/views/admPrint.php (views devra etre interdit avec htaccess)
}
?>