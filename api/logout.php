<?php
// Démarrer la session
session_start();

// Vérifier si une session utilisateur existe
if (isset($_SESSION['user_id'])) {
    // Détruire toutes les variables de session
    session_unset();
    // Détruire la session
    session_destroy();

    // Réponse en cas de succès
    echo json_encode([
        "message" => "Déconnexion réussie"
    ]);
} else {
    // Réponse si l'utilisateur n'était pas connecté
    http_response_code(400); // Mauvaise requête
    echo json_encode([
        "message" => "Aucune session active"
    ]);
}
