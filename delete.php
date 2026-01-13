<?php
session_start();
require "connexion.php";

// Vérification CSRF
if (
    !isset($_GET['id']) ||
    !isset($_GET['csrf_token']) ||
    $_GET['csrf_token'] !== $_SESSION['csrf_token']
) {
    die("Erreur CSRF ou ID manquant");
}

$id = intval($_GET['id']);

// Préparer et exécuter la suppression
$stmt = $pdo->prepare("DELETE FROM reservation WHERE id = ?");
$stmt->execute([$id]);

// Redirection vers la liste après suppression
header("Location: liste.php?msg=reservation_supprimee");
exit;
?>
