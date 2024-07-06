<?php
session_start();
header('Content-Type: application/json');
require 'config.php';

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
        exit;
    }

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
        exit;
    }

    if (isset($data['activities']) && is_array($data['activities'])) {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
            exit;
        }

        $db = new Database('localhost', 'centre_loisirs', 'root', '');
        $reservationManager = new ReservationManager($db->getConnection());

        foreach ($data['activities'] as $activity) {
            if (isset($activity['id'])) {
                $reservationManager->reserverActivite($userId, $activity['id']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Données d\'activité invalides']);
                exit;
            }
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Données invalides.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Exception: ' . $e->getMessage()]);
}
?>
