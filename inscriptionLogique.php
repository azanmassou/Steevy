<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclure le fichier de connexion à la base de données
    include 'db_connection.php';

    // Récupérer les données du formulaire
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Insérer les données dans la base de données
    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
    
    if (mysqli_query($conn, $sql)) {
        // L'utilisateur est inscrit avec succès, rediriger vers une page de connexion
        header("Location: login.php");
        exit();
    } else {
        // Afficher un message d'erreur en cas d'échec de l'inscription
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    
    // Fermer la connexion
    mysqli_close($conn);
}

