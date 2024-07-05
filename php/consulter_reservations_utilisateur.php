    <?php
    require 'config.php';

    class ReservationManager {
        private $db;

        public function __construct($db) {
            $this->db = $db;
        }

        public function getReservationsUtilisateur($utilisateur_id) {
            $stmt = $this->db->prepare('SELECT r.activite_id, a.nom, a.description, a.type FROM reservations r JOIN activites a ON r.activite_id = a.id WHERE r.utilisateur_id = ?');
            $stmt->execute([$utilisateur_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    header('Content-Type: application/json');

    $db = new Database('localhost', 'centre_loisirs', 'root', '');
    $reservationManager = new ReservationManager($db->getConnection());

    $utilisateur_id = isset($_GET['utilisateur_id']) ? intval($_GET['utilisateur_id']) : 0;

    if ($utilisateur_id === 0) {
        echo json_encode(['error' => 'Utilisateur ID manquant']);
        exit;
    }

    echo json_encode($reservationManager->getReservationsUtilisateur($utilisateur_id));
    ?>
