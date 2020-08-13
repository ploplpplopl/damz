// if we are on the page containing the element id linkTabToPrint
if ((document.getElementById('linkTabToPrint') != null)) {
    // clicking on the tab button displays the corresponding tab
    var toPrint = document.getElementById('linkTabToPrint');
    toPrint.addEventListener('click', function () {
        openTab(event, 'tabToPrint')
    }, false);

    // clicking on the tab button displays the corresponding tab
    var histo = document.getElementById('linkTabHisto');
    histo.addEventListener('click', function () {
        openTab(event, 'tabHisto')
    }, false);

    // first tab "À IMPRIMER" selected by default
    document.getElementById("linkTabToPrint").click();

    function openTab(evt, tab) {
        // Declare all variables
        var tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (let i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (let i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(tab).style.display = "block";
        evt.currentTarget.className += " active";
    }
}