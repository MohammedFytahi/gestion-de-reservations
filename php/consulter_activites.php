<?php
require 'config.php';

class ActiviteManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getActivites() {
        $stmt = $this->db->prepare('SELECT * FROM activites');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

header('Content-Type: application/json');

$db = new Database('localhost', 'centre_loisirs', 'root', '');
$activiteManager = new ActiviteManager($db->getConnection());

echo json_encode($activiteManager->getActivites());
?>
