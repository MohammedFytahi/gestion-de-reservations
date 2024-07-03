<?php
class Admin {
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

    public function ajouterActivite($db, $nom, $description, $type, $placesDisponibles) {
        $stmt = $db->prepare('INSERT INTO activites (nom, description, type, placesDisponibles) VALUES (?, ?, ?, ?)');
        $stmt->execute([$nom, $description, $type, $placesDisponibles]);
    }

    public function consulterActivites($db) {
        $stmt = $db->query('SELECT * FROM activites');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function consulterReservations($db) {
        $stmt = $db->query('SELECT * FROM reservations');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
