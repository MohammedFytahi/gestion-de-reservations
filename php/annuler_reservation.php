<?php
require 'config.php';

class ReservationManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function annulerReservation($utilisateur_id, $activite_id) {
        $stmt = $this->db->prepare('DELETE FROM reservations WHERE utilisateur_id = ? AND activite_id = ?');
        $stmt->execute([$utilisateur_id, $activite_id]);

        return $stmt->rowCount() > 0;
    }
}

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
