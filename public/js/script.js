$(document).ready(function() {
    $('.cardModule').click(function() {
        $(this).toggleClass('active'); // Ajoute ou supprime la classe 'active' à la cardModule cliquée
        $(this).next('.description').toggleClass('active'); // Ajoute ou supprime la classe 'active' à la cardModule cliquée
        $(this).find('.description').slideToggle(); // Trouve la description à l'intérieur de la cardModule cliquée et fait le slideToggle
    })
});
