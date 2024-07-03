-- Création des tables
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    motDePasse VARCHAR(255) NOT NULL
);

CREATE TABLE activites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    type VARCHAR(50),
    placesDisponibles INT NOT NULL
);

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT,
    activite_id INT,
    date DATE,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (activite_id) REFERENCES activites(id)
);

CREATE TABLE favoris (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT,
    activite_id INT,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (activite_id) REFERENCES activites(id)
);

-- Insertion des données initiales
INSERT INTO utilisateurs (nom, email, motDePasse) VALUES
('Alice', 'alice@example.com', 'password123'),
('Bob', 'bob@example.com', 'password456');

INSERT INTO activites (nom, description, type, placesDisponibles) VALUES
('Yoga', 'Session de yoga pour tous les niveaux', 'Relaxation', 20),
('Peinture', 'Atelier de peinture pour débutants', 'Art', 15),
('Basketball', 'Match de basketball amical', 'Sport', 10);
