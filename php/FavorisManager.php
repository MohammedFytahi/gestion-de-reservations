    <?php
    class FavorisManager {
        private $db;

        public function __construct($db) {
            $this->db = $db;
        }

        public function ajouterFavori($utilisateur_id, $activite_id) {
            $stmt = $this->db->prepare('INSERT INTO favoris (utilisateur_id, activite_id) VALUES (?, ?)');
            $stmt->execute([$utilisateur_id, $activite_id]);
            return $stmt->rowCount() > 0;
        }

        public function retirerFavori($utilisateur_id, $activite_id) {
            $stmt = $this->db->prepare('DELETE FROM favoris WHERE utilisateur_id = ? AND activite_id = ?');
            $stmt->execute([$utilisateur_id, $activite_id]);
            return $stmt->rowCount() > 0;
        }

        public function getFavorisUtilisateur($utilisateur_id) {
            $stmt = $this->db->prepare('SELECT a.id, a.nom, a.description, a.type FROM favoris f JOIN activites a ON f.activite_id = a.id WHERE f.utilisateur_id = ?');
            $stmt->execute([$utilisateur_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    ?>
