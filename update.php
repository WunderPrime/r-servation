<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require "connexion.php";

// Vérification CSRF
if (!isset($_POST['csrf_token']) || 
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die("Erreur CSRF");
}

// Vérification des champs
if (
    empty($_POST['id']) ||
    empty($_POST['nom']) ||
    empty($_POST['email']) ||
    empty($_POST['nb_personnes']) ||
    empty($_POST['date_reservation']) ||
    empty($_POST['heure_reservation'])
) {
    die("Données manquantes");
}

$id = intval($_POST['id']);
$nom = $_POST['nom'];
$email = $_POST['email'];
$nb_personnes = intval($_POST['nb_personnes']);
$date = $_POST['date_reservation'];
$heure = $_POST['heure_reservation'];

// Requête UPDATE
$sql = "UPDATE reservation
        SET nom = ?, email = ?, nb_personnes = ?, date_reservation = ?, heure_reservation = ?
        WHERE id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$nom, $email, $nb_personnes, $date, $heure, $id]);

header("Location: liste.php?msg=reservation_modifiee");
exit;
?>
