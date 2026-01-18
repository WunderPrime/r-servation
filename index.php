<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require "connexion.php";
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Génération du token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification CSRF
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        die("Erreur de sécurité CSRF");
    }

    // Validation des champs
    if (
        empty($_POST['nom']) ||
        empty($_POST['email']) ||
        empty($_POST['nb_personnes']) ||
        empty($_POST['date_reservation']) ||
        empty($_POST['heure_reservation'])
    ) {
        die("Données manquantes");
    }

    $nom = htmlspecialchars($_POST['nom']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $nb_personnes = (int) $_POST['nb_personnes'];
    $date = $_POST['date_reservation'];
    $heure = $_POST['heure_reservation'];

    // INSERTION DANS LA BASE
    $sql = "INSERT INTO reservation 
            (nom, email, nb_personnes, date_reservation, heure_reservation)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $email, $nb_personnes, $date, $heure]);

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = '127.0.0.1';
        $mail->Port = 1025;
        $mail->SMTPAuth = false;

        $mail->setFrom('no-reply@reservation.emsi', 'Restaurant');
        $mail->addAddress($email, $nom);

        $mail->isHTML(true);
        $mail->Subject = 'Confirmation de votre reservation';
        $mail->Body = "
            <h3>Bonjour $nom,</h3>
            <p>Votre reservation a bien ete enregistree !</p>
            <ul>
                <strong>Nombre de personnes :</strong> $nb_personnes<br><br>
                <strong>Date :</strong> $date<br><br>
                <strong>Heure :</strong> $heure<br><br>
            </ul>
            <p>Merci et a bientot !</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        // On peut loguer l'erreur
        error_log("Erreur email : " . $mail->ErrorInfo);
    }

    // Régénérer le token
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    // Redirection vers la page de confirmation avec l'email
    header("Location: confirmation.php?email=" . urlencode($email));
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservation</title>
</head>
<body>
<h2 align="center">Réserver une table</h2>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    Nom: <input type="text" name="nom" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Nombre de personnes: <input type="number" name="nb_personnes" min="1" required><br><br>
    Date: <input type="date" name="date_reservation" required><br><br>
    Heure: <input type="time" name="heure_reservation" required><br><br>
    <button type="submit">Réserver</button>
</form>
<p><a href="liste.php">Voir toutes les réservations</a></p>
</body>
</html>
