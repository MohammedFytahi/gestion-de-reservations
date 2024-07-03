<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Méthode non autorisée
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

require 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
    exit;
}

if (isset($data['activities']) && is_array($data['activities'])) {
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté.']);
        exit;
    }

    try {
        $db->beginTransaction();

        foreach ($data['activities'] as $activity) {
            if (!isset($activity['id'])) {
                throw new Exception('Invalid activity data');
            }
            $stmt = $db->prepare('INSERT INTO reservations (utilisateur_id, activite_id) VALUES (?, ?)');
            $stmt->execute([$userId, $activity['id']]);
        }

        $db->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la réservation: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Données invalides.']);
}
?>
