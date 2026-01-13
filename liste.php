<?php
include 'connexion.php';
$stmt = $pdo->query("SELECT * FROM reservation ORDER BY id ASC");
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste</title>
</head>
<body>
    <h2 align="center">Liste des réservations</h2>
    <?php if ($stmt->rowCount() > 0): ?>
        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Nombre de personnes</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Options</th>
            </tr>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nom']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td align="center"><?= $row['nb_personnes'] ?></td>
                    <td><?= $row['date_reservation'] ?></td>
                    <td><?= $row['heure_reservation'] ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>">Modifier</a> | 
                        <a href="delete.php?id=<?= $row['id'] ?>&csrf_token=<?= $_SESSION['csrf_token'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer cette réservation ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p style="color: red;">Aucune réservation pour le moment.</p>
    <?php endif; ?>
    <p><a href="réservation.php">Ajouter une nouvelle réservation</a></p>
</body>
</html>
