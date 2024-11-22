<?php

session_start();

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    echo json_encode(['message' => 'Utilisateur connecté']);
} else {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['message' => 'Utilisateur non connecté']);
}


?>

