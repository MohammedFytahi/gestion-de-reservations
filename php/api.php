<?php
require_once 'Activity.php';
require_once 'ActivityManager.php';
require_once 'Reservation.php';
require_once 'ReservationManager.php';
require_once '../data/activities.json'; // Chemin absolu ou relatif au fichier JSON

// Chargement des activités depuis le fichier JSON
$activityData = json_decode(file_get_contents('../data/activities.json'), true);

// Initialisation du gestionnaire d'activités
$activityManager = new ActivityManager();

// Ajout des activités depuis le fichier JSON au gestionnaire d'activités
foreach ($activityData as $data) {
    $activity = new Activity($data['id'], $data['name'], $data['type'], $data['description']);
    $activityManager->addActivity($activity);
}

// Initialisation du gestionnaire de réservations
$reservationManager = new ReservationManager();

// Route pour récupérer toutes les activités
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Récupération de toutes les activités
    $activities = $activityManager->getAllActivities();

    // Conversion en tableau simple pour l'envoi en JSON
    $activitiesArray = array_values($activities);

    // Envoi de la réponse JSON
    header('Content-Type: application/json');
    echo json_encode($activitiesArray);
}

// Route pour gérer les réservations (POST pour créer, DELETE pour annuler)
else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Créer une nouvelle réservation
    $requestData = json_decode(file_get_contents('php://input'), true);
    $activityId = $requestData['id']; // Suppose que l'ID est envoyé depuis le formulaire
    $activityName = $requestData['name'];
    $activityType = $requestData['type'];
    $activityDescription = $requestData['description'];
    $newActivity = new Activity($activityId, $activityName, $activityType, $activityDescription);

    // Ajout de la nouvelle activité au gestionnaire d'activités
    $activityManager->addActivity($newActivity);

    // Retourner la réponse JSON
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Activité ajoutée avec succès']);
}

else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Annuler une réservation (à implémenter si nécessaire)
    // $requestData = json_decode(file_get_contents('php://input'), true);
    // $reservationId = $requestData['reservationId'];
    // $reservationManager->cancelReservation($reservationId);
}
?>
