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

    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
        exit;
    }

    $activiteId = $data['activite_id'] ?? null;
    if (!$activiteId) {
        echo json_encode(['success' => false, 'message' => 'ID d\'activité manquant']);
        exit;
    }

    $db = new Database('localhost', 'centre_loisirs', 'root', '');
    $favorisManager = new FavorisManager($db->getConnection());

    $success = $favorisManager->retirerFavori($userId, $activiteId);

    echo json_encode(['success' => $success]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Exception: ' . $e->getMessage()]);
}
?>
