<?php
require 'config.php';

class ReservationManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getReservations() {
        $stmt = $this->db->prepare('SELECT r.activite_id, a.nom AS nom_activite, a.description, a.type, u.nom AS nom_utilisateur
                     FROM reservations r
                     JOIN activites a ON r.activite_id = a.id
                     JOIN utilisateurs u ON r.utilisateur_id = u.id');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

header('Content-Type: application/json');

$db = new Database('localhost', 'centre_loisirs', 'root', '');
$reservationManager = new ReservationManager($db->getConnection());

echo json_encode($reservationManager->getReservations());
?>
