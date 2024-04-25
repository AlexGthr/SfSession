async function fetchAnything(url) {

    try {

        const response = await fetch(url);

        if (response.ok) {
            const data = await response.json();

            console.log("data = ", data);

            return data;
        }

    } catch(erreur) {
        console.error(erreur);
    }

    return null;
}

document.addEventListener("DOMContentLoaded", function () {

    // Je crée une function async qui utilisera la fonction FetchAnything pour fetch mes données
    async function fetchData(url) {
        const data = await fetchAnything(url);

        if (data) {
            return data;
        } else {
            return null;
        }
    }

    // Div du formulaire pour ajouter des modules à une session
    const formAddModuleSession = document.createElement("div");
    formAddModuleSession.classList.add("formAddModuleSession");

    // Récupérer le chemin de l'URL
    const path = window.location.pathname;

    // Extraire l'ID de l'URL
    const sessionId = path.split('/').pop();

    // Creation du formulaire
    const myForm = document.createElement('FORM');
            myForm.name = 'formAddModuleSession';
            myForm.method = 'POST';
            myForm.action = `/session/${sessionId}/addModule`;

    // Label et input nombre de jour
    const labelFormNbDay = document.createElement("label");
    labelFormNbDay.textContent = "Nombre de jour";

    const inputFormNdDay = document.createElement("input");
    inputFormNdDay.type = "number";
    inputFormNdDay.name = "nbDay";
    inputFormNdDay.classList.add("form-control");

    // Label et input Select module
    const labelFormModule = document.createElement("label");
    labelFormModule.textContent = "Choix du module";

    const selectFormModule = document.createElement("select");
    selectFormModule.name = "module"
    const optionModule = document.createElement("option")
    selectFormModule.classList.add("form-select");

    // Input Submit
    const inputFormSubmit = document.createElement("input");
    inputFormSubmit.type = "submit";
    inputFormSubmit.name = "submit";
    inputFormSubmit.classList.add("buttonFormProgramme");

    let clicked = 0;
    // Je récupère mon formulaire et mon bouton pour ajouté le formulaire
    const formModuleWrapper = document.getElementById("formModule");
    const buttonAddModule = document.getElementById("buttonAddModule");
    
    if (buttonAddModule) {
        // Quand l'utilisateur clique sur le bouton
        buttonAddModule.addEventListener("click", async() => {
            
            if (clicked == 1) {
                clicked = 0;
                formModuleWrapper.removeChild(formAddModuleSession);
                buttonAddModule.textContent = "Ajouter un module";
            } else {
                clicked = 1;
            // Je récupère mon fetch dans une variable module
            const modules = await fetchData(`/api/module/${sessionId}`);
            console.log(modules);

            // Je vide le formulaire avant tout, pour gérer la situation dans laquel l'utilisateur clique plusieurs fois sur le button
            selectFormModule.innerText = '';
            
            // Si modules à bien récupérés mes données 
            if (modules) {
                
                // Je crée une boucles pour crées mes options
                for (i = 0; i < modules.modules.length; i++) {
                    
                    // Je clone l'option
                    const option = optionModule.cloneNode();
                    // J'ajoute mon modules ID en value
                    option.value = modules.modules[i].id;
                    // Je rajoute le nom du module en content
                    option.textContent = modules.modules[i].name;
                    // Et j'ajoute à mon select l'option
                    selectFormModule.add(option);
                }
                
                // J'appendChild tout mes éléments
                formAddModuleSession.appendChild(myForm);
                
                myForm.appendChild(labelFormNbDay);
                myForm.appendChild(inputFormNdDay);
                
                myForm.appendChild(labelFormModule);
                myForm.appendChild(selectFormModule);
                
                myForm.appendChild(inputFormSubmit);
                
                // myForm.appendChild(buttonClose);
                formModuleWrapper.appendChild(formAddModuleSession);
                buttonAddModule.textContent = "Fermer le formulaire";
            } else {
                console.log("erreur");
            }
            }
        })
    }
    
    // Div du formulaire pour ajouter des stagiaires à une session
    const formAddStagiaireSession = document.createElement("div");
    formAddStagiaireSession.classList.add("formAddStagiaireSession");

    // Creation du formulaire
    const myFormStagiaire = document.createElement('FORM');
    myFormStagiaire.name = 'formAddStagiaireSession';
    myFormStagiaire.method = 'POST';
    myFormStagiaire.action = `/session/${sessionId}/addStagiaire`;

    // Label et input choix du stagiaire
    const labelFormName = document.createElement("label");
    labelFormName.textContent = "Choix du stagiaire";

    // Label et input Select stagiaire
    const selectFormStagiaire = document.createElement("select");
    selectFormStagiaire.name = "stagiaire"
    const optionStagiaire = document.createElement("option")
    optionStagiaire.classList.add("form-select");

    // Input Submit
    const inputFormSubmitStagiaire = document.createElement("input");
    inputFormSubmitStagiaire.type = "submit";
    inputFormSubmitStagiaire.name = "submit";
    inputFormSubmitStagiaire.classList.add("buttonFormProgramme");
    

    // Je récupère ma div qui contiendra le formulaire et mon bouton pour ajouté le formulaire
    const formStagiaireWrapper = document.getElementById("formStagiaire");
    const buttonAddStagiaire = document.getElementById("buttonAddStagiaire");

    let stagiaireClicked = 0;

    if (buttonAddStagiaire) {

        buttonAddStagiaire.addEventListener("click", async () => {
        
            if (stagiaireClicked == 1) {
                
                formStagiaireWrapper.removeChild(formAddStagiaireSession);
                buttonAddStagiaire.textContent = "Ajouter un stagiaire";
                
                stagiaireClicked = 0;
                
            } else {
                
                const stagiaires = await fetchData(`/api/stagiaires/${sessionId}`);
    
                selectFormStagiaire.innerText = '';

                stagiaireClicked = 1;

                if (stagiaires) {

                    // Je crée une boucles pour crées mes options
                    for (i = 0; i < stagiaires.stagiaires.length; i++) {

                        // Je clone l'option
                        const option = optionStagiaire.cloneNode();
                        // J'ajoute mon modules ID en value
                        option.value = stagiaires.stagiaires[i].id;
                        // Je rajoute le nom du module en content
                        option.textContent = stagiaires.stagiaires[i].lastName.toUpperCase() + " "  + stagiaires.stagiaires[i].name;
                        // Et j'ajoute à mon select l'option
                        selectFormStagiaire.add(option);
                    }

                    // J'appendChild tout mes éléments
                    formAddStagiaireSession.appendChild(myFormStagiaire);
                    
                    myFormStagiaire.appendChild(labelFormName);
                    myFormStagiaire.appendChild(selectFormStagiaire);
                    
                    myFormStagiaire.appendChild(inputFormSubmitStagiaire);
                    

                    formStagiaireWrapper.appendChild(formAddStagiaireSession);
                    buttonAddStagiaire.textContent = "Fermer le formulaire";
                    }
                }
        })
    };    
});

const btnToggleTheme = document.getElementById("darkmode-toggle");

    if (!localStorage.getItem("theme")) {
        document.body.classList.add("dark-theme");
    }
  
    // Vérifier que l'élément .btn-toggle existe avant d'attacher l'événement
    const currentTheme = localStorage.getItem("theme");
    const body = document.querySelector('body');

    if (currentTheme == "dark") {
        body.classList.add("dark-theme");
        btnToggleTheme.checked = true;
    } 
    
    if (btnToggleTheme) {
      btnToggleTheme.addEventListener("click", function () {
        body.classList.toggle("dark-theme");
  
        let theme = "light";
        if (body.classList.contains("dark-theme")) {
          theme = "dark";
        }
        localStorage.setItem("theme", theme);
        })
    }


$(document).ready(function() {
    $('.cardModule').click(function() {
        $(this).toggleClass('active'); // Ajoute ou supprime la classe 'active' à la cardModule cliquée
        $(this).next('.description').toggleClass('active'); // Ajoute ou supprime la classe 'active' à la cardModule cliquée
        $(this).find('.description').slideToggle(); // Trouve la description à l'intérieur de la cardModule cliquée et fait le slideToggle
    })
});

let button = document.querySelector(".deleteListCateg")

if (button) {
    
    button.addEventListener("click", () => {
        let id = button.dataset.id;
        let entity = button.dataset.entity;
        console.log(id, entity)
        deleteModal(id, entity)
    })

}

function deleteModal(id, entity) {
    let delBtn = document.querySelector(".delBtnTest")
    delBtn.href = `/${entity}/${id}/delete`
}
