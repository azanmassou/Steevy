<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db_connection.php';

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Veuillez remplir tous les champs";
        $_SESSION['old_values'] = $_POST;
        header("Location: login.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['success'] = "Connexion réussie";
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Mot de passe incorrect";
            $_SESSION['old_values'] = $_POST;
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Utilisateur non trouvé";
        $_SESSION['old_values'] = $_POST;
        header("Location: login.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center">Connexion</h3>
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger"><?php echo $_SESSION['error']; ?></div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>
                        <form action="login.php" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_SESSION['old_values']['email']) ? $_SESSION['old_values']['email'] : ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Se connecter</button>
                        </form>
                    </div>
                    <!-- Bouton d'inscription -->
                    <div class="text-center mt-3">
                            <a href="register.php" class="btn btn-link">S'inscrire</a>
                        </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
unset($_SESSION['old_values']);
?>
