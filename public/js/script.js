document.addEventListener("DOMContentLoaded", function () {
    const btnToggleTheme = document.querySelector(".btn-toggle");

    if (!localStorage.getItem("theme")) {
        document.body.classList.add("dark-theme");
    }
  
    // Vérifier que l'élément .btn-toggle existe avant d'attacher l'événement
    const currentTheme = localStorage.getItem("theme");
    if (currentTheme == "dark") {
        document.body.classList.add("dark-theme");
    } 
    
    if (btnToggleTheme) {
      btnToggleTheme.addEventListener("click", function () {
        document.body.classList.toggle("dark-theme");
  
        let theme = "light";
        if (document.body.classList.contains("dark-theme")) {
          theme = "dark";
        }
        localStorage.setItem("theme", theme);
        })
    }
});

$(document).ready(function() {
    $('.cardModule').click(function() {
        $(this).toggleClass('active'); // Ajoute ou supprime la classe 'active' à la cardModule cliquée
        $(this).next('.description').toggleClass('active'); // Ajoute ou supprime la classe 'active' à la cardModule cliquée
        $(this).find('.description').slideToggle(); // Trouve la description à l'intérieur de la cardModule cliquée et fait le slideToggle
    })
});

$(document).ready(function() {
    $('.dashboard').click(function() {
        $('.dashbord_liste').slideToggle("slow");
    })
});

