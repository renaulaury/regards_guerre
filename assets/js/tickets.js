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

    // Sélectionne tous les éléments qui permettent d'ajouter des tickets
    const addTicketButtons = document.querySelectorAll('.add-ticket');
    addTicketButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Empêche le comportement par défaut du clic 
            const exhibitionId = this.dataset.exhibitionId; // Récupère l'ID de l'exposition depuis l'attribut data-
            const ticketId = this.dataset.ticketId; // Récupère l'ID du ticket depuis l'attribut data-

            // Trouve le span de quantité associé à ce bouton
            const quantitySpan = this.parentNode.querySelector('.qtyProduct');
            let currentQuantity = parseInt(quantitySpan.textContent);
            quantitySpan.textContent = currentQuantity + 1; // Incrémente la quantité affichée

            // Envoi d'une requête AJAX pour ajouter le ticket au panier
            fetch(`/panier/ajouter/${exhibitionId}/${ticketId}`, { // Remplacez '/panier/ajouter/' par l'URL de votre route d'ajout au panier
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json', // Indique que le corps de la requête est en JSON
                    'X-Requested-With': 'XMLHttpRequest' // Indique que c'est une requête AJAX (utile pour le serveur)
                },
            })
            .then(response => response.json()) // Transforme la réponse en JSON
            .then(data => {
                console.log('Ticket ajouté au panier:', data);
                // Ici, vous pouvez éventuellement mettre à jour d'autres éléments de l'interface utilisateur
                // en fonction de la réponse du serveur (par exemple, afficher un message de succès)
            })
            .catch(error => {
                console.error('Erreur lors de l\'ajout au panier:', error);
                // Ici, vous pouvez gérer les erreurs (par exemple, afficher un message d'erreur à l'utilisateur)
            });
        });
    });

    // Sélectionne tous les éléments qui permettent de retirer des tickets
    const removeTicketButtons = document.querySelectorAll('.remove-ticket');
    removeTicketButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Empêche le comportement par défaut du clic (si c'était un lien)
            const exhibitionId = this.dataset.exhibitionId; // Récupère l'ID de l'exposition depuis l'attribut data-
            const ticketId = this.dataset.ticketId; // Récupère l'ID du ticket depuis l'attribut data-

            // Trouve le span de quantité associé à ce bouton
            const quantitySpan = this.parentNode.querySelector('.qtyProduct');
            let currentQuantity = parseInt(quantitySpan.textContent);
            if (currentQuantity > 0) {
                quantitySpan.textContent = currentQuantity - 1; // Décrémente la quantité affichée

                // Envoi d'une requête AJAX pour retirer le ticket du panier
                fetch(`/panier/retirer/${exhibitionId}/${ticketId}`, { // Remplacez '/panier/retirer/' par l'URL de votre route de retrait du panier
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json', // Indique que le corps de la requête est en JSON
                        'X-Requested-With': 'XMLHttpRequest' // Indique que c'est une requête AJAX (utile pour le serveur)
                    },
                    // Vous pouvez éventuellement envoyer un corps de requête JSON avec la quantité
                    // body: JSON.stringify({ quantity: 1 })
                })
                .then(response => response.json()) // Transforme la réponse en JSON
                .then(data => {
                    console.log('Ticket retiré du panier:', data);
                    // Ici, vous pouvez éventuellement mettre à jour d'autres éléments de l'interface utilisateur
                    // en fonction de la réponse du serveur
                })
                .catch(error => {
                    console.error('Erreur lors du retrait du panier:', error);
                    // Ici, vous pouvez gérer les erreurs
                });
            }
        });
    });

    // Appeler la fonction au chargement pour afficher la première exposition par défaut
    toggleExhibitionDetails();
});






