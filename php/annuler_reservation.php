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

$stmt = $db->prepare('DELETE FROM reservations WHERE utilisateur_id = ? AND activite_id = ?');
$stmt->execute([$utilisateur_id, $activite_id]);

if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Aucune réservation trouvée.']);
}
?>
