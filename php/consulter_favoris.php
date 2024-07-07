<?php
session_start();
header('Content-Type: application/json');
require 'config.php';

try {
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
        exit;
    }

    $db = new Database('localhost', 'centre_loisirs', 'root', '');
    $favorisManager = new FavorisManager($db->getConnection());

    $favoris = $favorisManager->getFavorisUtilisateur($userId);

    echo json_encode($favoris);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Exception: ' . $e->getMessage()]);
}
?>
