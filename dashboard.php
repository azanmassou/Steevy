<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Inclure le fichier de connexion à la base de données
include 'db_connection.php';

// Récupérer l'utilisateur depuis la base de données
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Récupérer tous les posts depuis la base de données
$stmt = $conn->prepare("SELECT * FROM posts");
$stmt->execute();
$posts = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        .sidebar {
            background-color: #f8f9fa;
            padding: 20px;
        }

        .post {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar">
                <h3>Profil</h3>
                <p><strong>Nom d'utilisateur:</strong> <?php echo $user['name']; ?></p>
                <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                <a href="logout.php" class="btn btn-danger">Déconnecter</a>

                <!-- Ajouter un post -->
                <a href="add_post.php" class="btn btn-primary mt-3">Ajouter un Post</a>
            </div>

            <!-- Contenu principal -->
            <div class="col-md-9">
                <h1>Liste des Posts</h1>
                <?php while ($row = $posts->fetch_assoc()): ?>
                    <div class="post">
                        <h3><?php echo $row['title']; ?></h3>
                        <p><?php echo $row['content']; ?></p>
                        <?php if (!empty($row['image'])): ?>
                            <!-- Chemin d'accès à l'image -->
                            <?php $imagePath = "uploads/" . $row['image']; ?>

                            <!-- Vérifier si l'image existe -->
                            <?php if (file_exists($imagePath)): ?>
                                <img src="<?php echo $imagePath; ?>" alt="Image" class="img-fluid">
                            <?php else: ?>
                                <p>Image not found: <?php echo $row['image']; ?></p>
                            <?php endif; ?>
                        <?php endif; ?>

                        <!-- Modifier un post -->
                        <a href="edit_post.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Modifier</a>
                    </div>
                <?php endwhile; ?>

                <!-- Afficher un message si aucun post n'est disponible -->
                <?php if ($posts->num_rows === 0): ?>
                    <p>Aucun post disponible.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
