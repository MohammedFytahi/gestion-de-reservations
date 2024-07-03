<?php
require 'config.php';

// Set the content type to application/json
header('Content-Type: application/json');

// Fetch activities from the database
$stmt = $db->prepare('SELECT * FROM activites');
$stmt->execute();
$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return activities as JSON
echo json_encode($activities);
?>
