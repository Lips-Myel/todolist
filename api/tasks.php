<?php
// Démarrer la session si ce n'est pas déjà fait
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inclure la configuration et la connexion à la base de données
require_once 'config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
    header("Location: /login.php");  // Remplacez par l'URL de la page de connexion si nécessaire
    exit();  // Arrête le script après la redirection
}

// Récupérer l'ID de l'utilisateur connecté
$userId = $_SESSION['user_id'];

// Définir le type de contenu en JSON
header('Content-Type: application/json');

// Vérifier la méthode HTTP
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Récupérer les tâches de l'utilisateur
        try {
            $sql = "SELECT id, title, description, completed, created_at FROM tasks WHERE user_id = :user_id ORDER BY created_at DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(["tasks" => $tasks]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["message" => "Erreur lors de la récupération des tâches."]);
        }
        break;

    case 'POST':
        // Ajouter une nouvelle tâche ou mettre à jour une tâche existante
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $action = $_POST['action'] ?? null; // Action pour mise à jour spécifique
        $taskId = $_POST['id'] ?? null;

        if ($action === 'complete' && $taskId) {
            // Marquer une tâche comme terminée
            try {
                $sql = "UPDATE tasks SET completed = 1 WHERE id = :id AND user_id = :user_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $taskId, ':user_id' => $userId]);
                echo json_encode(["message" => "Tâche marquée comme terminée."]);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(["message" => "Erreur lors de la mise à jour de la tâche."]);
            }
        } elseif (!empty($title)) {
            // Ajouter une nouvelle tâche
            try {
                $sql = "INSERT INTO tasks (user_id, title, description) VALUES (:user_id, :title, :description)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([ 
                    ':user_id' => $userId,
                    ':title' => $title,
                    ':description' => $description
                ]);
                echo json_encode(["message" => "Tâche ajoutée avec succès."]);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(["message" => "Erreur lors de l'ajout de la tâche."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Titre de la tâche requis."]);
        }
        break;

    case 'DELETE':
        // Récupérer l'ID à partir de la query string
        $taskId = $_GET['id'] ?? null;

        if ($taskId) {
            try {
                $sql = "DELETE FROM tasks WHERE id = :id AND user_id = :user_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $taskId, ':user_id' => $userId]);
                echo json_encode(["message" => "Tâche supprimée avec succès."]);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(["message" => "Erreur lors de la suppression de la tâche."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "ID de la tâche requis."]);
        }
        break;

    default:
        // Méthode non autorisée
        http_response_code(405);
        echo json_encode(["message" => "Méthode non autorisée."]);
        break;
}
?>
