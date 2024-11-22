-- Création de la base de données
CREATE DATABASE IF NOT EXISTS gestion_utilisateurs_todolist;
USE gestion_utilisateurs_todolist;

-- Création de la table `users`
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Identifiant unique pour chaque utilisateur
    username VARCHAR(100) NOT NULL UNIQUE, -- Nom d'utilisateur unique
    password VARCHAR(255) NOT NULL -- Mot de passe haché
);

-- Création de la table `tasks`
CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Identifiant unique pour chaque tâche
    user_id INT NOT NULL, -- Référence à l'utilisateur propriétaire
    title VARCHAR(255) NOT NULL, -- Titre de la tâche
    description TEXT, -- Description optionnelle de la tâche
    completed BOOLEAN DEFAULT 0, -- Statut de la tâche (0 = non terminée, 1 = terminée)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date et heure de création
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE -- Lien avec la table users
);