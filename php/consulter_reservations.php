<?php
require 'config.php';

header('Content-Type: application/json');

$stmt = $db->prepare('SELECT r.activite_id, a.nom AS nom_activite, a.description, a.type, u.nom AS nom_utilisateur
                     FROM reservations r
                     JOIN activites a ON r.activite_id = a.id
                     JOIN utilisateurs u ON r.utilisateur_id = u.id');
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($reservations);
?>
