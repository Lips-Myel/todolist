<?php

// ====================
// Configuration CORS
// ====================
header("Access-Control-Allow-Origin: ['http://todolist:3003', 'http://localhost:3003'] "); // Origine de votre frontend
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Méthodes autorisées
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // En-têtes autorisés

// Gestion des requêtes préliminaires (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Répondre aux requêtes préliminaires avec les en-têtes CORS
    header("Access-Control-Allow-Origin: ['http://todolist:3003', 'http://localhost:3003'] ");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    exit(0); // Terminer le script après avoir répondu aux OPTIONS
}

// ====================
// Configuration de la base de données
// ====================

// Informations de connexion à la base de données
$host = 'localhost'; // Hôte du serveur MySQL
$db = 'gestion_utilisateurs_todolist'; // Nom de la base de données
$user = 'admin'; // Nom d'utilisateur MySQL
$password = 'admin'; // Mot de passe MySQL (remplacez-le par un mot de passe sécurisé)

// Connexion à la base de données
try {
    // Créer une instance de PDO pour gérer la connexion
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);

    // Configurer PDO pour afficher les erreurs SQL comme exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Configurer PDO pour renvoyer des tableaux associatifs par défaut
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Gérer les erreurs de connexion en renvoyant une réponse JSON
    http_response_code(500); // Code HTTP 500 : Erreur serveur
    echo json_encode([
        "error" => true,
        "message" => "Erreur de connexion à la base de données : " . $e->getMessage()
    ]);
    exit; // Fin du script en cas d'erreur
}

// Ce fichier ne doit produire aucune sortie par défaut.
