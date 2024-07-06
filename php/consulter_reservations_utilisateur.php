<?php
require 'config.php';
header('Content-Type: application/json');

$db = new Database('localhost', 'centre_loisirs', 'root', '');
$reservationManager = new ReservationManager($db->getConnection());

$utilisateur_id = isset($_GET['utilisateur_id']) ? intval($_GET['utilisateur_id']) : 0;

if ($utilisateur_id === 0) {
    echo json_encode(['error' => 'Utilisateur ID manquant']);
    exit;
}

echo json_encode($reservationManager->getReservationsUtilisateur($utilisateur_id));
?>
