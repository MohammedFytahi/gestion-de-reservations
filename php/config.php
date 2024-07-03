<?php
// Informations de connexion à la base de données
$host = 'localhost'; // Adresse du serveur MySQL
$dbname = 'centre_loisirs'; // Nom de la base de données
$username = 'root'; // Nom d'utilisateur MySQL
$password = 'password'; // Mot de passe MySQL

try {
    // Création de l'objet PDO pour la connexion à la base de données
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Configuration de PDO pour qu'il lance des exceptions en cas d'erreur
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Désactivation du mode émulation pour les requêtes préparées
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    // En cas d'échec de la connexion, affichage du message d'erreur
    echo 'Connection failed: ' . $e->getMessage();
    exit; // Arrêt de l'exécution du script en cas d'échec
}
?>
