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

if (isset($data['nom'], $data['description'], $data['type'], $data['placesDisponibles'])) {
    $nom = $data['nom'];
    $description = $data['description'];
    $type = $data['type'];
    $placesDisponibles = $data['placesDisponibles'];

    if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
        $stmt = $db->prepare('INSERT INTO activites (nom, description, type, placesDisponibles) VALUES (?, ?, ?, ?)');
        $success = $stmt->execute([$nom, $description, $type, $placesDisponibles]);

        if ($success) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout de l\'activité.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Administrateur non connecté.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Données invalides.']);
}
?>
