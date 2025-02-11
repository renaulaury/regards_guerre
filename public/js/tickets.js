// Définir la fonction de manière globale
function toggleExhibitionDetails() {
    let option = document.getElementById('exhibitionSelect').value;

    // Masquer toutes les options de détails des expositions
    document.querySelectorAll('[id^="exhibitionDetails-"]').forEach(div => {
        div.style.display = 'none';
    });

    // Afficher les détails correspondants
    if (option) {
        let selectedExhibition = document.getElementById(`exhibitionDetails-${option}`);
        if (selectedExhibition) {
            selectedExhibition.style.display = 'block';
        }
    }
}

document.addEventListener("DOMContentLoaded", function () {
    // Attacher l'événement onchange après le chargement du DOM
    let selectElement = document.getElementById('exhibitionSelect');
    if (selectElement) {
        selectElement.addEventListener('change', toggleExhibitionDetails);
    }
});




// function toggleBanOptions() {
//     let option = document.getElementById('options').value;

//     // Masquer toutes les options de bannissement
//     document.getElementById('banTempDiv').style.display = 'none';
//     document.getElementById('banDefDiv').style.display = 'none';

//     // Afficher les options correspondantes
//     if (option == 'Banni Temporairement') {
//         document.getElementById('banTempDiv').style.display = 'block';
//     } else if (option == 'Banni Définitivement') {
//         document.getElementById('banDefDiv').style.display = 'block';
//     }
// } 





