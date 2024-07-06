<?php
require 'config.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$utilisateur_id = $data['utilisateur_id'] ?? null;
$activite_id = $data['activite_id'] ?? null;

if (is_null($utilisateur_id) || is_null($activite_id)) {
    echo json_encode(['success' => false, 'error' => 'Paramètres manquants']);
    exit;
}

$db = new Database('localhost', 'centre_loisirs', 'root', '');
$reservationManager = new ReservationManager($db->getConnection());

if ($reservationManager->annulerReservation($utilisateur_id, $activite_id)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Aucune réservation trouvée.']);
}
?>
