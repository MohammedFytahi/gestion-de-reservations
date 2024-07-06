document.addEventListener('DOMContentLoaded', () => {
    const activitiesList = document.getElementById('activités-disponibles');
    const favoritesList = document.getElementById('liste-favoris');
    const cartList = document.getElementById('liste-panier');
    const finalizeButton = document.getElementById('finaliser-reservation');
    const ajoutActiviteForm = document.getElementById('ajout-activite-form');
    const adminSection = document.getElementById('admin');
    const reservationsSection = document.getElementById('reservations');
    const reservationsList = document.getElementById('liste-reservations');
    const userReservationsList = document.getElementById('liste-reservations-utilisateur');
    const userRole = localStorage.getItem('user_role');
    const userId = localStorage.getItem('user_id');

    if (userRole === 'admin') {
        adminSection.style.display = 'block';
        reservationsSection.style.display = 'block';
        afficherReservations();
    } else {
        adminSection.style.display = 'none';
        reservationsSection.style.display = 'none';
    }

    function afficherActivités() {
        fetch('php/consulter_activites.php')
            .then(response => response.json())
            .then(data => {
                activitiesList.innerHTML = '';
                data.forEach(activity => {
                    const li = document.createElement('li');
                    li.textContent = `${activity.nom} (${activity.type}) - ${activity.description}`;

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
            body: JSON.stringify({ activities: panier, utilisateur_id: userId })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Réservation réussie!');
                    localStorage.removeItem(`panier_${userId}`);
                    afficherPanier();
                    afficherReservationsUtilisateur();
                } else {
                    alert('Erreur lors de la réservation.');
                }
            })
            .catch(error => console.error('Erreur:', error));
    }

    function afficherReservations() {
        fetch('php/consulter_reservations.php')
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Erreur:', data.error);
                    alert('Erreur lors de la récupération des réservations.');
                } else if (data.length === 0) {
                    reservationsList.innerHTML = '<li>Aucune réservation trouvée.</li>';
                } else {
                    reservationsList.innerHTML = '';
                    data.forEach(reservation => {
                        const li = document.createElement('li');
                        li.textContent = `Utilisateur: ${reservation.nom_utilisateur} - Activité: ${reservation.nom_activite} (${reservation.type}) - ${reservation.description}`;
                        reservationsList.appendChild(li);
                    });
                }
            })
            .catch(error => console.error('Erreur:', error));
    }
    

    function afficherReservationsUtilisateur() {
        fetch(`php/consulter_reservations_utilisateur.php?utilisateur_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (!Array.isArray(data)) {
                console.error('La réponse n\'est pas un tableau valide:', data);
                alert('Erreur lors de la récupération des réservations utilisateur.');
                return;
            }

            userReservationsList.innerHTML = '';
            if (data.length === 0) {
                userReservationsList.innerHTML = '<li>Aucune réservation trouvée.</li>';
            } else {
                data.forEach(reservation => {
                    const li = document.createElement('li');
                    li.textContent = `${reservation.nom} (${reservation.type}) - ${reservation.description}`;
                    const cancelButton = document.createElement('button');
                    cancelButton.textContent = 'Annuler la Réservation';
                    cancelButton.addEventListener('click', () => {
                        annulerReservation(reservation.activite_id);
                    });
                    li.appendChild(cancelButton);
                    userReservationsList.appendChild(li);
                });
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des réservations utilisateur:', error);
            userReservationsList.innerHTML = '<li>Erreur lors de la récupération des réservations utilisateur.</li>';
        });
    }

    function annulerReservation(activiteId) {
        fetch('php/annuler_reservation.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ utilisateur_id: userId, activite_id: activiteId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Réservation annulée avec succès!');
                afficherReservationsUtilisateur();
            } else {
                alert('Erreur lors de l\'annulation de la réservation.');
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

    afficherActivités();
    afficherFavoris();
    afficherPanier();
    afficherReservationsUtilisateur();
});
