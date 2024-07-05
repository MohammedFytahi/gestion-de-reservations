<?php
session_start();
header('Content-Type: application/json');

require 'config.php';

class UserManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare('SELECT * FROM utilisateurs WHERE nom = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $password === $user['motDePasse']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            return ['success' => true, 'user_id' => $user['id'], 'role' => $user['role']];
        } else {
            return ['success' => false, 'message' => 'Invalid username or password'];
        }
    }
}

$data = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
    exit;
}

if (isset($data['username']) && isset($data['password'])) {
    $username = $data['username'];
    $password = $data['password'];

    $db = new Database('localhost', 'centre_loisirs', 'root', '');
    $userManager = new UserManager($db->getConnection());

    $result = $userManager->login($username, $password);
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Missing username or password']);
}
?>
