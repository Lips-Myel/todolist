<?php
// Inclure la configuration et les en-têtes CORS
require_once 'config.php';
header('Content-Type: application/json');

// Vérifier si la méthode est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données de la requête
    $data = json_decode(file_get_contents('php://input'), true);

    // Support des données JSON ou application/x-www-form-urlencoded
    $username = isset($data['username']) ? trim($data['username']) : (isset($_POST['username']) ? trim($_POST['username']) : null);
    $password = isset($data['password']) ? $data['password'] : (isset($_POST['password']) ? $_POST['password'] : null);

    // Vérifier que les champs sont fournis
    if (empty($username) || empty($password)) {
        echo json_encode(['message' => 'Nom d\'utilisateur et mot de passe requis.']);
        exit;
    }

    // Vérifier si l'utilisateur existe déjà
    $sql = "SELECT id FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':username' => $username]);

    if ($stmt->fetch()) {
        echo json_encode(['message' => 'Ce nom d\'utilisateur est déjà pris.']);
        exit;
    }

    // Hacher le mot de passe
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insérer le nouvel utilisateur dans la base de données
    $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([
            ':username' => $username,
            ':password' => $hashedPassword
        ]);
        echo json_encode(['message' => 'Inscription réussie !']);
    } catch (PDOException $e) {
        // Gérer les erreurs SQL
        echo json_encode(['message' => 'Erreur lors de l\'inscription : ' . $e->getMessage()]);
    }
} else {
    // Si une méthode autre que POST est utilisée
    echo json_encode(['message' => 'Méthode non autorisée.']);
}
