<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require "connexion.php";

// Vérifier l'ID dans l'URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID invalide");
}

$id = (int) $_GET['id'];

// Récupérer la réservation
$sql = "SELECT * FROM reservation WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    die("Réservation introuvable");
}

// CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier</title>
</head>
<body>
<h2 align="center">Modifier la réservation</h2>
<form action="update.php" method="post">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <input type="hidden" name="id" value="<?= $reservation['id'] ?>">
    Nom :
    <input type="text" name="nom" value="<?= htmlspecialchars($reservation['nom']) ?>" required><br><br>
    Email :
    <input type="email" name="email" value="<?= htmlspecialchars($reservation['email']) ?>" required><br><br>
    Nombre de personnes :
    <input type="number" name="nb_personnes" min="1" value="<?= $reservation['nb_personnes'] ?>" required><br><br>
    Date :
    <input type="date" name="date_reservation" value="<?= $reservation['date_reservation'] ?>" required><br><br>
    Heure :
    <input type="time" name="heure_reservation" value="<?= $reservation['heure_reservation'] ?>" required><br><br>
    <button type="submit">Enregistrer</button>
</form>
<p><a href="liste.php">Retour à la liste</a></p>
</body>
</html>
