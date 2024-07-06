<?php
class ActiviteManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function ajouterActivite($nom, $description, $type, $placesDisponibles) {
        $stmt = $this->db->prepare('INSERT INTO activites (nom, description, type, placesDisponibles) VALUES (?, ?, ?, ?)');
        $success = $stmt->execute([$nom, $description, $type, $placesDisponibles]);

        if (!$success) {
            $errorInfo = $stmt->errorInfo();
            throw new Exception("Erreur d'insertion: " . $errorInfo[2]);
        }

        return $success;
    }

    public function getActivites() {
        $stmt = $this->db->prepare('SELECT * FROM activites');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
