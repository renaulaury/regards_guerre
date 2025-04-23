//Attente que le DOM soit chargé avt affichage de la fonction
document.addEventListener("DOMContentLoaded", function () {

    // Fonction pour afficher les détails de l'exposition
    function toggleExhibitionDetails() {
        let option = document.getElementById('exhibitionSelect').value;
        console.log(option);

        // Masquer toutes les options de détails des expositions
        document.querySelectorAll('[id^="exhibitionDetails-"]'). // ^ -> commence par / [] -> sélectionne l'id dynamique
        forEach(div => { // Parcours les expos afin de les masquer
            div.style.display = 'none';
        });

        // Afficher les détails correspondants
        if (option) {
            let selectedExhibition = document.getElementById(`exhibitionDetails-${option}`); //Sélectionne l'id de l'expo 
            if (selectedExhibition) { // pour afficher les détails correspondants
                selectedExhibition.style.display = 'block';
            }
        }
    }

    // Sélectionne le menu déroulant
    let selectElement = document.getElementById('exhibitionSelect');
    if (selectElement) { //A chaque changement d'expo on 'change' le détail de l'expo
        selectElement.addEventListener('change', toggleExhibitionDetails);
    }

    // Appeler la fonction au chargement pour afficher la première exposition par défaut
    toggleExhibitionDetails();
});







