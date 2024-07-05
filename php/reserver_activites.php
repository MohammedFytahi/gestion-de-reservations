<?php
session_start();
header('Content-Type: application/json');

require 'config.php';

class ReservationManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function reserveActivities($userId, $activities) {
        try {
            $this->db->beginTransaction();

            foreach ($activities as $activity) {
                if (!isset($activity['id'])) {
                    throw new Exception('Données d\'activité invalides');
                }

                $stmt = $this->db->prepare('INSERT INTO reservations (utilisateur_id, activite_id) VALUES (?, ?)');
                $stmt->execute([$userId, $activity['id']]);
            }

            $this->db->commit();
            return ['success' => true];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => 'Erreur lors de la réservation : ' . $e->getMessage()];
        }
    }
}

$data = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Entrée JSON invalide']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data['activities']) && is_array($data['activities'])) {
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
        exit;
    }

    $db = new Database();
    $reservationManager = new ReservationManager($db->getConnection());

    $result = $reservationManager->reserveActivities($userId, $data['activities']);
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée ou données invalides']);
}
?>
