<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclure le fichier de connexion à la base de données
    include 'db_connection.php';

    // Récupérer les données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérifier les données dans la base de données
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);
    
    // Vérifier si l'utilisateur existe et les données sont correctes
    if (mysqli_num_rows($result) == 1) {
        // L'utilisateur est authentifié avec succès, rediriger vers une page de succès ou un tableau de bord, etc.
        header("Location: dashboard.php");
        exit();
    } else {
        // Afficher un message d'erreur si les données sont incorrectes
        echo "Email or password is incorrect";
    }
}
