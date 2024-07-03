<?php
require 'config.php';
require 'admin.php';

// Lire les données JSON du corps de la requête
$data = json_decode(file_get_contents('php://input'), true);

// Vérifier si toutes les données nécessaires sont présentes
if (isset($data['nom'], $data['description'], $data['type'], $data['placesDisponibles'])) {
    $nom = $data['nom'];
    $description = $data['description'];
    $type = $data['type'];
    $placesDisponibles = $data['placesDisponibles'];

    // Vérifier l'authentification de l'administrateur
    session_start();
    if (isset($_SESSION['admin_id'])) {
        $admin_id = $_SESSION['admin_id'];

        // Récupérer l'administrateur à partir de la base de données
        $stmt = $db->prepare('SELECT * FROM utilisateurs WHERE id = ?');
        $stmt->execute([$admin_id]);
        $adminData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Créer l'objet Admin avec les données récupérées
        $admin = new Admin($adminData['id'], $adminData['nom'], $adminData['email'], $adminData['motDePasse']);

        // Ajouter l'activité en utilisant la méthode de l'objet Admin
        $success = $admin->ajouterActivite($db, $nom, $description, $type, $placesDisponibles);

        // Vérifier si l'ajout a réussi et renvoyer une réponse JSON
        if ($success) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout de l\'activité.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Administrateur non connecté.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Données invalides.']);
}
?>
