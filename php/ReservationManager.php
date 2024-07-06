<?php
class ReservationManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function annulerReservation($utilisateur_id, $activite_id) {
        $stmt = $this->db->prepare('DELETE FROM reservations WHERE utilisateur_id = ? AND activite_id = ?');
        $stmt->execute([$utilisateur_id, $activite_id]);

        return $stmt->rowCount() > 0;
    }

    public function getReservationsUtilisateur($utilisateur_id) {
        $stmt = $this->db->prepare('SELECT r.activite_id, a.nom, a.description, a.type FROM reservations r JOIN activites a ON r.activite_id = a.id WHERE r.utilisateur_id = ?');
        $stmt->execute([$utilisateur_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReservations() {
        $stmt = $this->db->prepare('SELECT r.activite_id, a.nom AS nom_activite, a.description, a.type, u.nom AS nom_utilisateur
                     FROM reservations r
                     JOIN activites a ON r.activite_id = a.id
                     JOIN utilisateurs u ON r.utilisateur_id = u.id');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function reserverActivite($userId, $activityId) {
        $stmt = $this->db->prepare('INSERT INTO reservations (utilisateur_id, activite_id) VALUES (?, ?)');
        $success = $stmt->execute([$userId, $activityId]);

        if (!$success) {
            $errorInfo = $stmt->errorInfo();
            throw new Exception("Erreur d'insertion: " . $errorInfo[2]);
        }

        return $success;
    }
}
?>
