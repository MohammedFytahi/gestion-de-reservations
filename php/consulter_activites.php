<?php
require 'config.php';

header('Content-Type: application/json');

$db = new Database('localhost', 'centre_loisirs', 'root', '');
$activiteManager = new ActiviteManager($db->getConnection());

echo json_encode($activiteManager->getActivites());
?>
