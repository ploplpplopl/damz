<?php
require('views/head.php');
?>

<div class="row">

    <!-- à modifier -->
    <div class="col-md-4 offset-md-4 home-wrapper">
        <!-- Display messages -->
        <?php
        if (!empty($_SESSION['id'])) {
            if (isset($_SESSION['message'])) : ?>
                <div class="alert <?php echo $_SESSION['type'] ?>">
                    <?php
                    echo $_SESSION['message'] . '<br>';
                    unset($_SESSION['message']);
                    unset($_SESSION['type']);
                    // echo "SESSION: <br>";
                    // print_r($_SESSION);
                    ?>
                </div>
            <?php endif; ?>

            <h4>pseudo : <?php echo $_SESSION['pseudo']; ?></h4>
            <a href="index.php?action=logout" style="color: red">Logout</a>

            <?php if (!$_SESSION['verified']) : ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    You need to verify your email address!
                    Sign into your email account and click
                    on the verification link we just emailed you
                    at
                    <strong><?php echo $_SESSION['email']; ?></strong>
                </div>
            <?php else : ?>
                <button class="btn btn-lg btn-primary btn-block">I'm verified!!!</button>
        <?php endif;
        } ?>
    </div>
    <!-- fin à modifier -->

    <form method="post" action="traitement.php">
        <fieldset id="upload">
            <!-- upload du document et détection du nombre de pages couleurs/N&B via le script compte_pages.js-->
            <legend class="titre1">Téléchargez le fichier PDF à imprimer</legend>
            <input type="file" id="uploadPDF" name="uploadPDF" accept=".pdf,application/pdf"><br>
            <img src="public/img/spinner.gif" id="loading">
        </fieldset>

        <fieldset id="infoDoc">
            <!-- les caractéristiques (nombre de pages en couleur, N&B...) s'affichent quand le traitement se termine -->
            <legend class="titre1">Caractéristiques du PDF</legend>
            <p id="succes"></p>
            <ul id="detailPages">
                <li>Nom du fichier : <b><span id="nomFichier"></span></b></li>
                <li>Pages en Noir & Blanc : <b><span id="nbPagesNB"></span></b></li>
                <li>Pages en Couleur : <b><span id="nbPagesC"></span></b></li>
                <li>Total des pages : <b><span id="nbPages"></span></b></li>
            </ul>
        </fieldset>

        <fieldset>
            <!-- Type de document à imprimer -->
            <legend class="titre1">Type de document à imprimer</legend>
            <div class="conteneurDocType">
                <!-- Dossier -->
                <div class="docType" id="docTypeDossier">
                    <div class="topBarDocType">
                        <input type="radio" name="docType" value="dossier" id="dossier" />
                        <label for="dossier"><strong>Dossier</strong></label>
                    </div>
                    <div class="bottomDocType">
                        <p>
                            du texte qui ne donne aucune information mais qui est bien long bien long bien long.<br>
                            encore du texteeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee <br>
                            et encore du texte <br>et encore du texte <br>et encore du texte <br>et encore du texte <br>
                        </p>
                    </div>
                </div>
                <!-- Mémoire -->
                <div class="docType" id="docTypeMemoire">
                    <div class="topBarDocType">
                        <input type="radio" name="docType" value="memoire" id="memoire" />
                        <label for="memoire"><strong>Mémoire</strong></label>
                    </div>
                    <div class="bottomDocType">
                        <p>du texte <br>encore du texte <br>et encore du texte <br></p>
                    </div>
                </div>
                <!-- Thèse -->
                <div class="docType" id="docTypeThese">
                    <div class="topBarDocType">
                        <input type="radio" name="docType" value="these" id="these" />
                        <label for="these"><strong>Thèse</strong></label>
                    </div>
                    <div class="bottomDocType">
                        <p>du texte <br>encore du texte <br>et encore du texte <br></p>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <!-- Reliure -->
            <legend class="titre1">Reliure</legend>
            <div class="conteneurReliure">
                <!-- Type de reliure -->
                <p>Type de reliure :</p>
                <div class="boutonsReliure">
                    <div class="boutonReliure">
                        <label>
                            <input type="radio" name="btnReliure" id="thermo" hidden><span>Thermocollée</span>
                        </label></div>
                    <div class="boutonReliure">
                        <label>
                            <input type="radio" name="btnReliure" id="spiplast" hidden><span>Spirales Plastique</span>
                        </label></div>
                    <div class="boutonReliure">
                        <label>
                            <input type="radio" name="btnReliure" id="spimetal" hidden><span>Spirales Métalliques</span>
                        </label></div>
                </div>
                <!-- Couleur reliure -->
                <div class="couleurReliure" id="radios">
                    <p>Couleur de la reliure :<br /></p>
                    <!-- <div class="radio-couleur" id="btnReliureNoire"> -->
                    <label for="reliureNoire">Noire</label>
                    <input type="radio" id="reliureNoire" name="btnCoulReliure" value="reliureNoire">
                    <!-- </div> -->
                    <!-- <div class="radio-couleur" id="btnReliureBlanche"> -->
                    <label for="reliureBlanche">Blanche</label>
                    <input type="radio" id="reliureBlanche" name="btnCoulReliure" value="reliureBlanche">
                    <!-- </div> -->
                    <span id="slider"></span>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <!-- couverture -->
            <legend class="titre1">Couverture</legend>
            <div class="conteneurCouverture">
                <!-- Feuillet transparent btn checkbox -->
                <div class="boutonFT">
                    <label>
                        <input type="checkbox" name="btnFTCouv" id="btnFTCouv" hidden><span>Feuillet Transparent</span>
                    </label>
                </div>
                <div class="FCCouv">
                    <div class="boutonFC">
                        <label>
                            <input type="checkbox" name="btnFCCouv" id="btnFCCouv" hidden><span>Feuille Cartonnée</span>
                        </label>
                    </div>
                    <div class="btnCouleurFCCouv">
                        <p>Couleur de la Feuille Cartonnée</p>
                        <input type="radio" id="FCCouvBlanche" name="btnCoulFCCouv" value="FCCouvBlanche">
                        <label for="FCCouvBlanche">Blanche</label>
                        <input type="radio" id="FCCouvNoire" name="btnCoulFCCouv" value="FCCouvNoire">
                        <label for="FCCouvNoire">Noire</label>
                        <input type="radio" id="FCCouvVerte" name="btnCoulFCCouv" value="FCCouvVerte">
                        <label for="FCCouvVerte">Verte</label>
                        <input type="radio" id="FCCouvJaune" name="btnCoulFCCouv" value="FCCouvJaune">
                        <label for="FCCouvJaune">Jaune</label>
                        <input type="radio" id="FCCouvRouge" name="btnCoulFCCouv" value="FCCouvRouge">
                        <label for="FCCouvRouge">Rouge</label>
                    </div>
                </div>


            </div>
        </fieldset>

        <fieldset>
            <!-- dos -->
            <legend class="titre1">Dos</legend>
            <div class="boutonFT">
                <label>
                    <input type="checkbox" name="btnFTDos" id="btnFTDos" hidden><span>Feuillet Transparent</span>
                </label>
            </div>
            <div class="FCDos">
                <!-- <div class="boutonFC">
                        <label>
                            <input type="checkbox" name="btnFCDos" id="btnFCDos" hidden><span>Feuille Cartonnée</span>
                        </label>
                    </div> -->
                <div class="btnCouleurFCDos">
                    <p>Couleur de la Feuille Cartonnée</p>
                    <input type="radio" id="FCDosBlanche" name="btnCoulFCDos" value="FCDosBlanche">
                    <label for="FCDosBlanche">Blanche</label>
                    <input type="radio" id="FCDosNoire" name="btnCoulFCDos" value="FCDosNoire">
                    <label for="FCDosNoire">Noire</label>
                    <input type="radio" id="FCDosVerte" name="btnCoulFCDos" value="FCDosVerte">
                    <label for="FCDosVerte">Verte</label>
                    <input type="radio" id="FCDosJaune" name="btnCoulFCDos" value="FCDosJaune">
                    <label for="FCDosJaune">Jaune</label>
                    <input type="radio" id="FCDosRouge" name="btnCoulFCDos" value="FCDosRouge">
                    <label for="FCDosRouge">Rouge</label>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <!-- options -->
            <legend class="titre1">Options supplémentaires</legend>
            <div class="options">
                <div class="nbExmplaires">
                    <label for="quantity">Nombre d'exemplaires:</label>
                    <input type="number" id="quantity" name="quantity" min="1" value="1">
                </div>
                <div>
                    <label for="rectoverso">Recto-Verso</label>
                    <input type="checkbox" name="btnFTDos" id="rectoverso">
                </div>
            </div>
        </fieldset>

        <fieldset>
            <!-- devis -->
            <p>devis</p>
<!-- TODO créer des div automatiquement en fonction des variables $zone existantes -->
            <div id="zone1"></div>
            <div id="zone2"></div>
            <div id="zone3"></div>
            <div id="zone4"></div>
            <div id="zone5"></div>
            <div id="zone6"></div>
            <div id="zone7"></div>
            <div id="zone8"></div>
            <div id="zone9"></div>
            <div id="zone10"></div>
            <div id="zone11"></div>
            <div id="zone12"></div>
        </fieldset>

        <!-- bouton valider -->
        <input type="submit" id="submit" value="Valider">

    </form>
</div>

<?php
require('views/footer.htm');
?>