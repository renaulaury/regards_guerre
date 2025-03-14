document.addEventListener('DOMContentLoaded', function() {
    // Sélectionne tous les éléments avec la classe 'toggle-form' (flèche).
    const toggleButtons = document.querySelectorAll('.toggle-form');

    // Parcourt chaque élément sélectionné.
    toggleButtons.forEach(button => {
        // Ajoute un écouteur d'événements 'click' à chaque élément.
        button.addEventListener('click', function() {
            console.log('Flèche cliquée !'); 
            // Récupère l'ID de l'artiste stocké dans l'attribut 'data-artist-id' de l'élément cliqué.
            const artistId = this.dataset.artistId;

            // Sélectionne le formulaire correspondant à l'ID de l'artiste.
            const form = document.getElementById(`form-${artistId}`);

            // Vérifie si le formulaire est actuellement caché.
            if (form.style.display === 'none') {
                // Si le formulaire est caché, affiche-le.
                form.style.display = 'block';

                // Remplace la classe 'fa-caret-down' par 'fa-caret-up' pour faire tourner la flèche.
                this.classList.remove('fa-caret-down');
                this.classList.add('fa-caret-up');
            } else {
                // Si le formulaire est affiché, cache-le.
                form.style.display = 'none';

                // Remplace la classe 'fa-caret-up' par 'fa-caret-down' pour faire tourner la flèche.
                this.classList.remove('fa-caret-up');
                this.classList.add('fa-caret-down');
            }
        });
    });
});