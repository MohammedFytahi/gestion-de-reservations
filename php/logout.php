<?php
session_start();
header('Content-Type: application/json');

$_SESSION = array(); // Unset all session variables
session_destroy();   // Destroy the session

echo json_encode(['success' => true]);
?>
