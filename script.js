document.addEventListener('DOMContentLoaded', () => {
    const activitiesList = document.getElementById('activités-disponibles');
    const favoritesList = document.getElementById('liste-favoris');
    const cartList = document.getElementById('liste-panier');
    const finalizeButton = document.getElementById('finaliser-reservation');
    const ajoutActiviteForm = document.getElementById('ajout-activite-form');
    const adminSection = document.getElementById('admin');
    const userRole = localStorage.getItem('user_role');
    const userId = localStorage.getItem('user_id');

    if (userRole === 'admin') {
        adminSection.style.display = 'block';
    }

    function afficherActivités() {
        fetch('php/consulter_activites.php')
            .then(response => response.json())
            .then(data => {
                activitiesList.innerHTML = '';
                data.forEach(activity => {
                    const li = document.createElement('li');
                    li.textContent = `${activity.nom} (${activity.type}) - ${activity.description}`;
                    
                    // Ajouter des boutons uniquement si le rôle est 'user'
                    if (userRole === 'user') {
                        const addButton = document.createElement('button');
                        addButton.textContent = 'Ajouter au Panier';
                        addButton.addEventListener('click', () => {
                            ajouterAuPanier(activity);
                        });
                        li.appendChild(addButton);

                        const favoriteButton = document.createElement('button');
                        favoriteButton.textContent = 'Ajouter aux Favoris';
                        favoriteButton.addEventListener('click', () => {
                            ajouterAuxFavoris(activity);
                        });
                        li.appendChild(favoriteButton);
                    }
                    
                    activitiesList.appendChild(li);
                });
            })
            .catch(error => console.error('Erreur:', error));
    }

    function afficherFavoris() {
        const favoris = JSON.parse(localStorage.getItem(`favoris_${userId}`)) || [];
        favoritesList.innerHTML = '';
        favoris.forEach(activity => {
            const li = document.createElement('li');
            li.textContent = `${activity.nom} (${activity.type}) - ${activity.description}`;
            const removeButton = document.createElement('button');
            removeButton.textContent = 'Retirer des Favoris';
            removeButton.addEventListener('click', () => {
                retirerDesFavoris(activity.nom);
            });
            li.appendChild(removeButton);
            favoritesList.appendChild(li);
        });
    }

    function afficherPanier() {
        const panier = JSON.parse(localStorage.getItem(`panier_${userId}`)) || [];
        cartList.innerHTML = '';
        panier.forEach(activity => {
            const li = document.createElement('li');
            li.textContent = `${activity.nom} (${activity.type}) - ${activity.description}`;
            const removeButton = document.createElement('button');
            removeButton.textContent = 'Retirer du Panier';
            removeButton.addEventListener('click', () => {
                retirerDuPanier(activity.nom);
            });
            li.appendChild(removeButton);
            cartList.appendChild(li);
        });
    }

    function ajouterAuxFavoris(activity) {
        let favoris = JSON.parse(localStorage.getItem(`favoris_${userId}`)) || [];
        if (!favoris.some(fav => fav.nom === activity.nom)) {
            favoris.push(activity);
            localStorage.setItem(`favoris_${userId}`, JSON.stringify(favoris));
            afficherFavoris();
        }
    }

    function retirerDesFavoris(activityNom) {
        let favoris = JSON.parse(localStorage.getItem(`favoris_${userId}`)) || [];
        favoris = favoris.filter(activity => activity.nom !== activityNom);
        localStorage.setItem(`favoris_${userId}`, JSON.stringify(favoris));
        afficherFavoris();
    }

    function ajouterAuPanier(activity) {
        let panier = JSON.parse(localStorage.getItem(`panier_${userId}`)) || [];
        if (!panier.some(act => act.nom === activity.nom)) {
            panier.push(activity);
            localStorage.setItem(`panier_${userId}`, JSON.stringify(panier));
            afficherPanier();
        }
    }

    function retirerDuPanier(activityNom) {
        let panier = JSON.parse(localStorage.getItem(`panier_${userId}`)) || [];
        panier = panier.filter(activity => activity.nom !== activityNom);
        localStorage.setItem(`panier_${userId}`, JSON.stringify(panier));
        afficherPanier();
    }

    function finaliserReservation() {
        const panier = JSON.parse(localStorage.getItem(`panier_${userId}`)) || [];
        if (panier.length === 0) {
            alert('Votre panier est vide.');
            return;
        }

        fetch('php/reserver_activites.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ activities: panier })
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text) });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Réservation réussie!');
                localStorage.removeItem(`panier_${userId}`);
                afficherPanier();
            } else {
                alert(`Erreur lors de la réservation: ${data.message}`);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert(`Erreur lors de la réservation: ${error.message}`);
        });
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
