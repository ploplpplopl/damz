$(function () {
    // closing notifications
    $(".alert-success, .alert-warning, .alert-danger").css({
        "cursor": "pointer",
        "title": "Fermer"
    }).click(function (e) {
        e.preventDefault();
        $(this).slideUp(1000);
    });

    
});