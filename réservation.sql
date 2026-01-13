-- ==========================================
-- Base de données : restaurant
-- Projet : Système de réservation
-- ==========================================

-- 1️⃣ Création de la base de données
CREATE DATABASE IF NOT EXISTS restaurant CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE restaurant;

-- 2️⃣ Table des réservations
CREATE TABLE IF NOT EXISTS reservation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    nb_personnes INT NOT NULL,
    date_reservation DATE NOT NULL,
    heure_reservation TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3️⃣ Optionnel : Insérer quelques réservations de test
INSERT INTO reservation (nom, email, nb_personnes, date_reservation, heure_reservation)
VALUES 
('Alice Dupont', 'alice@example.com', 2, '2026-01-15', '19:00'),
('Bob Martin', 'bob@example.com', 4, '2026-01-16', '20:30'),
('Charlie Durand', 'charlie@example.com', 3, '2026-01-17', '18:45');

-- 4️⃣ Vérifier les données
SELECT * FROM reservation;
