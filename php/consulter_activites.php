<?php
require 'config.php';

$stmt = $db->query('SELECT * FROM activites');
$activites = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($activites);
?>
