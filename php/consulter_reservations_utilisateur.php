<?php
require 'config.php';

header('Content-Type: application/json');

$utilisateur_id = isset($_GET['utilisateur_id']) ? intval($_GET['utilisateur_id']) : 0;

if ($utilisateur_id === 0) {
    echo json_encode(['error' => 'Utilisateur ID manquant']);
    exit;
}

$stmt = $db->prepare('SELECT r.activite_id, a.nom, a.description, a.type FROM reservations r JOIN activites a ON r.activite_id = a.id WHERE r.utilisateur_id = ?');
$stmt->execute([$utilisateur_id]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($reservations);
?>
