<?php
class Utilisateur {
    private $id;
    private $nom;
    private $email;
    private $motDePasse;

    public function __construct($id, $nom, $email, $motDePasse) {
        $this->id = $id;
        $this->nom = $nom;
        $this->email = $email;
        $this->motDePasse = $motDePasse;
    }

    public function getFavoris($db) {
        $stmt = $db->prepare('SELECT * FROM favoris WHERE utilisateur_id = ?');
        $stmt->execute([$this->id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ajouterFavori($db, $activite_id) {
        $stmt = $db->prepare('INSERT INTO favoris (utilisateur_id, activite_id) VALUES (?, ?)');
        $stmt->execute([$this->id, $activite_id]);
    }

    public function supprimerFavori($db, $activite_id) {
        $stmt = $db->prepare('DELETE FROM favoris WHERE utilisateur_id = ? AND activite_id = ?');
        $stmt->execute([$this->id, $activite_id]);
    }

    public function reserverActivite($db, $activite_id) {
        $stmt = $db->prepare('INSERT INTO reservations (utilisateur_id, activite_id, date) VALUES (?, ?, ?)');
        $stmt->execute([$this->id, $activite_id, date('Y-m-d')]);
    }

    public function annulerReservation($db, $reservation_id) {
        $stmt = $db->prepare('DELETE FROM reservations WHERE id = ? AND utilisateur_id = ?');
        $stmt->execute([$reservation_id, $this->id]);
    }
}
?>
