<?php
require 'config.php';
header('Content-Type: application/json');

$db = new Database('localhost', 'centre_loisirs', 'root', '');
$reservationManager = new ReservationManager($db->getConnection());

echo json_encode($reservationManager->getReservations());
?>
