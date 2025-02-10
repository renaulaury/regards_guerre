function toggleExhibitionDetails() {
    let option = document.getElementById('exhibitionSelect').value;

    // Masquer toutes les options de détails des expositions
    document.querySelectorAll('[id^="exhibitionDetails-"]').forEach(function(div) {
        div.style.display = 'none';
    });

    // Afficher les détails correspondants
    if (option) {
        let detailsDiv = document.getElementById('exhibitionDetails-' + option);
        if (detailsDiv) {
            detailsDiv.style.display = 'block';
        }
    }

}

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





