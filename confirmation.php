<?php
$email = $_GET['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation</title>
</head>
<body>
    <h2>Réservation confirmée !</h2>
    <?php if($email): ?>
        <p class="message">Un email de confirmation a été envoyé à <strong><?= htmlspecialchars($email) ?></strong>.</p>
    <?php else: ?>
        <p class="message">Votre réservation a été enregistrée.</p>
    <?php endif; ?>
    <a href="réservation.php">Retour au formulaire</a>
</body>
</html>
