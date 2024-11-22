<?php
// Inclure la configuration et les en-têtes CORS
require_once 'config.php';
header('Content-Type: application/json');

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Vérifier que les champs ne sont pas vides
    if (empty($username) || empty($password)) {
        echo json_encode(['message' => 'Veuillez remplir tous les champs.']);
        exit;
    }

    // Rechercher l'utilisateur dans la base de données
    $sql = "SELECT id, password FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // L'utilisateur n'existe pas
        echo json_encode(['message' => 'Nom d\'utilisateur ou mot de passe incorrect.']);
        exit;
    }

    // Vérifier le mot de passe
    if (!password_verify($password, $user['password'])) {
        // Mot de passe incorrect
        echo json_encode(['message' => 'Nom d\'utilisateur ou mot de passe incorrect.']);
        exit;
    }

    // Connexion réussie
    // Vous pouvez générer un token ou démarrer une session si nécessaire
    session_start();
    $_SESSION['user_id'] = $user['id'];

    echo json_encode(['message' => 'Connexion réussie']);
    exit;
}

// Si une méthode autre que POST est utilisée
echo json_encode(['message' => 'Méthode non autorisée.']);
