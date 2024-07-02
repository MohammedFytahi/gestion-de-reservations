document.addEventListener('DOMContentLoaded', function() {
    const availableActivities = document.getElementById('activités-disponibles');
    const favoritesList = document.getElementById('liste-favoris');
    const cartList = document.getElementById('liste-panier');
    const addActivityForm = document.getElementById('add-activity-form');
    const typeFilter = document.getElementById('type-filter');

    let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Fonction pour afficher les activités par type
    function renderActivities(activities) {
        availableActivities.innerHTML = '';
        activities.forEach(activity => {
            if (!typeFilter.value || activity.type === typeFilter.value) {
                let li = createActivityListItem(activity);
                availableActivities.appendChild(li);
            }
        });
    }

    // Créer un élément de liste pour une activité
    function createActivityListItem(activity) {
        let li = document.createElement('li');
        li.textContent = `${activity.name} - ${activity.type}`;
        
        // Bouton pour ajouter aux favoris
        let favButton = document.createElement('button');
        favButton.textContent = 'Ajouter aux Favoris';
        favButton.addEventListener('click', () => addToFavorites(activity));
        
        // Boutons pour gérer le panier
        let addButton = document.createElement('button');
        addButton.textContent = 'Ajouter au Panier';
        addButton.addEventListener('click', () => addToCart(activity));
        
        let removeButton = document.createElement('button');
        removeButton.textContent = 'Retirer du Panier';
        removeButton.addEventListener('click', () => removeFromCart(activity));

        li.appendChild(favButton);
        li.appendChild(addButton);
        li.appendChild(removeButton);
        return li;
    }

    // Fonction pour ajouter aux favoris
    function addToFavorites(activity) {
        if (!favorites.some(fav => fav.id === activity.id)) {
            favorites.push(activity);
            localStorage.setItem('favorites', JSON.stringify(favorites));
            renderFavorites();
        }
    }

    // Fonction pour retirer des favoris
    function removeFromFavorites(activity) {
        favorites = favorites.filter(fav => fav.id !== activity.id);
        localStorage.setItem('favorites', JSON.stringify(favorites));
        renderFavorites();
    }

    // Fonction pour ajouter au panier
    function addToCart(activity) {
        if (!cart.some(item => item.id === activity.id)) {
            cart.push(activity);
            localStorage.setItem('cart', JSON.stringify(cart));
            renderCart();
        }
    }

    // Fonction pour retirer du panier
    function removeFromCart(activity) {
        cart = cart.filter(item => item.id !== activity.id);
        localStorage.setItem('cart', JSON.stringify(cart));
        renderCart();
    }

    // Fonction pour afficher les favoris
    function renderFavorites() {
        favoritesList.innerHTML = '';
        favorites.forEach(activity => {
            let li = createActivityListItem(activity);
            let removeButton = document.createElement('button');
            removeButton.textContent = 'Retirer des Favoris';
            removeButton.addEventListener('click', () => removeFromFavorites(activity));
            li.appendChild(removeButton);
            favoritesList.appendChild(li);
        });
    }

    // Fonction pour afficher le panier
    function renderCart() {
        cartList.innerHTML = '';
        cart.forEach(activity => {
            let li = createActivityListItem(activity);
            let removeButton = document.createElement('button');
            removeButton.textContent = 'Retirer du Panier';
            removeButton.addEventListener('click', () => removeFromCart(activity));
            li.appendChild(removeButton);
            cartList.appendChild(li);
        });
    }

    // Écouteur d'événement pour le formulaire d'ajout d'activité
    addActivityForm.addEventListener('submit', function(event) {
        event.preventDefault();
        let activityName = this.elements['name'].value;
        let activityType = this.elements['type'].value;
        let activityDescription = this.elements['description'].value;
        let newActivity = { id: generateUniqueId(), name: activityName, type: activityType, description: activityDescription };
        
        // Envoi de la nouvelle activité à l'API (à implémenter)
        fetch('php/api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(newActivity)
        })
        .then(response => response.json())
        .then(data => {
            console.log(data.message); // Afficher un message de confirmation
            // Réactualiser la liste des activités après l'ajout
            fetchActivities();
        })
        .catch(error => console.error('Erreur lors de l\'ajout d\'activité:', error));

        // Réinitialiser le formulaire
        this.reset();
    });

    // Fonction pour récupérer les activités depuis l'API
    function fetchActivities() {
        fetch('php/api.php')
        .then(response => response.json())
        .then(data => {
            renderActivities(data);
        })
        .catch(error => console.error('Erreur lors de la récupération des activités:', error));
    }

    // Initialisation : charger les activités et afficher les favoris et le panier
    fetchActivities();
    renderFavorites();
    renderCart();
});
