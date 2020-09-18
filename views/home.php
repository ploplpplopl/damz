<?php

$css = '<link rel="stylesheet" href="/public/css/home.css">';

require_once 'views/head.php';
?>

<div class="row">
    <div class="col-12">
        <h1><a href="/impression" class="nolink">Simplifiez-vous la vie,<br>
        imprimez vos documents <strong>sans vous déplacer !</strong></a></h1>

        <div class="slideshow-container">

            <div class="mySlides fade">
                <div class="numbertext">1 / 3</div>
                <a href="/impression"><img src="/public/img/imprimante.jpg" style="width:100%;height:350px;"></a>
                <div class="text">Caption Text</div>
            </div>

            <div class="mySlides fade">
                <div class="numbertext">2 / 3</div>
                <a href="/impression"><img src="/public/img/reliures.jpg" style="width:100%;height:350px;"></a>
                <div class="text">Caption Two</div>
            </div>

            <div class="mySlides fade">
                <div class="numbertext">3 / 3</div>
                <a href="/impression"><img src="/public/img/Meteor-8700-XL.png" style="width:100%;height:350px;"></a>
                <div class="text">Caption Three</div>
            </div>

        </div>
        <br>

        <div style="text-align:center">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <h2>Des impressions de qualité professionnelle</h2>
        <div class="cadre">
            <h3>Qui sommes-nous&nbsp;?</h3>
            <p>Dossier-rapide est une entreprise qui propose un service d'<strong>imprimerie en ligne</strong>
                de qualité professionnelle.<br>
                Pour réaliser vos travaux, elle s'appuie sur la compétence et les <?php echo date("Y") - 2005 ?>
                ans d'expérience de COPIFAC, une imprimerie localisée dans la belle ville de Caen.</p>
        </div>
        <div class="cadre">
            <h3>Nos services</h3>
            <p>Les éditions de nos dossiers, mémoires, rapports et thèses sont réalisées sur des <strong>imprimantes
                    laser professionnelles</strong>, ce qui garantit un résultat parfait de la première à la dernière page. </p>
            <p>Nous effectuons des impressions en <strong>couleur</strong> et/ou en <strong>noir &amp; blanc</strong>, sur un papier 100g
                extra blanc légèrement satiné. <br>
                Ce support est particullièrement adapté aux impressions <strong>recto verso</strong> car il a une forte oppacité. </p>
            <p>Nous apportons une attention particulière à la <strong>reliure</strong> de vos documents pour une présentation
                irréprochable de votre travail. <br>
                Nous proposons des reliures classiques à anneaux plastiques, métalliques ou thermocollées
                pour une plus longue durée de vie. </p>
            <p>Pour la première et quatrième de couverture (première et dernière page), nous proposons des
                feuillets de <strong>protection plastique</strong> et des <strong>feuilles cartonnées</strong> avec un large choix de <strong>couleurs</strong>.
                Ces feuilles cartonnées peuvent avoir un grain cuir pour plus d'élégance (250g), ou être lisses (180g) et
                imprimables comme pour les thèses où le beige reste la référence.<br>
            </p>
        </div>
        <div class="cadre">
            <h3>Livraison</h3>
            <p>Nous avons choisi Chronopost et TNT pour livrer vos documents dans les meilleures conditions. Les délais habituels
                sont compris entre 2 et 5 jours entre la commande et la réception.
                Nos équipes ont à coeur de vous satisfaire et font leur maximum pour minimiser le délai d'impression. </p>
        </div>
    </div>
</div>

<div class="text-center">
    <a href="/impression" class="btn btn-primary btn-lg">Imprimez votre document</a>
</div>

<?php

$javascript = '
<script>
var slideIndex = 0;
showSlides();

function showSlides() {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dot");
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slideIndex++;
    if (slideIndex > slides.length) {
        slideIndex = 1
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].className += " active";
    setTimeout(showSlides, 4000); // Change image every 4 seconds
}
</script>
';

require_once 'views/footer.php';
