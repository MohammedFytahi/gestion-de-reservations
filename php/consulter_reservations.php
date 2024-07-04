<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "centre_loisirs";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT reservations.id, reservations.utilisateur_id, activites.nom, activites.type, activites.description 
        FROM reservations 
        JOIN activites ON reservations.activite_id = activites.id";
$result = $conn->query($sql);

$reservations = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }
}

echo json_encode($reservations);

$conn->close();
?>
