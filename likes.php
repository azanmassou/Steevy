<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Inclure le fichier de connexion à la base de données
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['post_id'])) {
        $postId = $_POST['post_id'];
        $userId = $_SESSION['user_id'];

        // Vérifier si l'utilisateur a déjà aimé le post
        $stmt = $conn->prepare("SELECT * FROM likes WHERE post_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $postId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // L'utilisateur a déjà aimé le post, donc supprimer le like (unlike)
            $deleteStmt = $conn->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?");
            $deleteStmt->bind_param("ii", $postId, $userId);
            $deleteStmt->execute();

            // Mettre à jour le nombre de likes du post
            $updateLikesStmt = $conn->prepare("UPDATE posts SET likes = likes - 1 WHERE id = ?");
            $updateLikesStmt->bind_param("i", $postId);
            $updateLikesStmt->execute();

            header("Location: dashboard.php");
            exit();
        } else {
            // L'utilisateur n'a pas encore aimé le post, donc ajouter le like
            $insertStmt = $conn->prepare("INSERT INTO likes (post_id, user_id) VALUES (?, ?)");
            $insertStmt->bind_param("ii", $postId, $userId);
            $insertStmt->execute();

            // Mettre à jour le nombre de likes du post
            $updateLikesStmt = $conn->prepare("UPDATE posts SET likes = likes + 1 WHERE id = ?");
            $updateLikesStmt->bind_param("i", $postId);
            $updateLikesStmt->execute();

            header("Location: dashboard.php");
            exit();
        }
    }
} else {
    // Si la méthode n'est pas POST, rediriger vers le dashboard
    header("Location: dashboard.php");
    exit();
}
