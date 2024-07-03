document.addEventListener('DOMContentLoaded', () => {
    const activitiesList = document.getElementById('activités-disponibles');
    const favoritesList = document.getElementById('liste-favoris');
    const cartList = document.getElementById('liste-panier');
    const finalizeButton = document.getElementById('finaliser-reservation');
    const ajoutActiviteForm = document.getElementById('ajout-activite-form');

    function afficherActivités() {
        fetch('php/consulter_activites.php')
            .then(response => response.json())
            .then(data => {
                activitiesList.innerHTML = '';
                data.forEach(activity => {
                    const li = document.createElement('li');
                    li.textContent = `${activity.nom} (${activity.type}) - ${activity.description}`;
                    const addButton = document.createElement('button');
                    addButton.textContent = 'Ajouter au Panier';
                    addButton.addEventListener('click', () => {
                        ajouterAuPanier(activity.id);
                    });
                    li.appendChild(addButton);

                    const favoriteButton = document.createElement('button');
                    favoriteButton.textContent = 'Ajouter aux Favoris';
                    favoriteButton.addEventListener('click', () => {
                        ajouterAuxFavoris(activity.id);
                    });
                    li.appendChild(favoriteButton);
                    
                    activitiesList.appendChild(li);
                });
            })
            .catch(error => console.error('Erreur:', error));
    }

    function afficherFavoris() {
        const favoris = JSON.parse(localStorage.getItem('favoris')) || [];
        favoritesList.innerHTML = '';
        favoris.forEach(activityId => {
            const li = document.createElement('li');
            li.textContent = `Activité ID: ${activityId}`;
            const removeButton = document.createElement('button');
            removeButton.textContent = 'Retirer des Favoris';
            removeButton.addEventListener('click', () => {
                retirerDesFavoris(activityId);
            });
            li.appendChild(removeButton);
            favoritesList.appendChild(li);
        });
    }

    function afficherPanier() {
        const panier = JSON.parse(localStorage.getItem('panier')) || [];
        cartList.innerHTML = '';
        panier.forEach(activityId => {
            const li = document.createElement('li');
            li.textContent = `Activité ID: ${activityId}`;
            const removeButton = document.createElement('button');
            removeButton.textContent = 'Retirer du Panier';
            removeButton.addEventListener('click', () => {
                retirerDuPanier(activityId);
            });
            li.appendChild(removeButton);
            cartList.appendChild(li);
        });
    }

    function ajouterAuxFavoris(activityId) {
        let favoris = JSON.parse(localStorage.getItem('favoris')) || [];
        if (!favoris.includes(activityId)) {
            favoris.push(activityId);
            localStorage.setItem('favoris', JSON.stringify(favoris));
            afficherFavoris();
        }
    }

    function retirerDesFavoris(activityId) {
        let favoris = JSON.parse(localStorage.getItem('favoris')) || [];
        favoris = favoris.filter(id => id !== activityId);
        localStorage.setItem('favoris', JSON.stringify(favoris));
        afficherFavoris();
    }

    function ajouterAuPanier(activityId) {
        let panier = JSON.parse(localStorage.getItem('panier')) || [];
        if (!panier.includes(activityId)) {
            panier.push(activityId);
            localStorage.setItem('panier', JSON.stringify(panier));
            afficherPanier();
        }
    }

    function retirerDuPanier(activityId) {
        let panier = JSON.parse(localStorage.getItem('panier')) || [];
        panier = panier.filter(id => id !== activityId);
        localStorage.setItem('panier', JSON.stringify(panier));
        afficherPanier();
    }

    function finaliserReservation() {
        const panier = JSON.parse(localStorage.getItem('panier')) || [];
        if (panier.length === 0) {
            alert('Votre panier est vide.');
            return;
        }

        fetch('reserver_activites.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ activities: panier })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Réservation réussie!');
                    localStorage.removeItem('panier');
                    afficherPanier();
                } else {
                    alert('Erreur lors de la réservation.');
                }
            })
            .catch(error => console.error('Erreur:', error));
    }

    ajoutActiviteForm.addEventListener('submit', (event) => {
        event.preventDefault();
        
        const nom = document.getElementById('nom').value;
        const description = document.getElementById('description').value;
        const type = document.getElementById('type').value;
        const placesDisponibles = document.getElementById('placesDisponibles').value;
        
        fetch('php/ajouter_activite.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                nom: nom,
                description: description,
                type: type,
                placesDisponibles: placesDisponibles
            })
        })
        
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Activité ajoutée avec succès!');
                afficherActivités();
            } else {
                alert('Erreur lors de l\'ajout de l\'activité.');
            }
        })
        .catch(error => console.error('Erreur:', error));
    });

    finalizeButton.addEventListener('click', finaliserReservation);

    // Afficher les activités, favoris et panier au chargement de la page
    afficherActivités();
    afficherFavoris();
    afficherPanier();
});
