<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Nouvelle Équipe</title>
    <style>
        /* Ton code CSS ici */
    </style>
</head>
<body>

<h1>Ajouter une Nouvelle Équipe</h1>

<?php
// Si une équipe a été ajoutée avec succès, afficher un message de confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($stmt)) {
    echo "<p>Équipe ajoutée avec succès !</p>";
}
?>

<!-- Formulaire d'ajout -->
<form action="add_team.php" method="post">
    <label for="team_name">Nom de l'Équipe :</label>
    <input type="text" id="team_name" name="team_name" required>

    <label for="city_name">Nom de la Ville :</label>
    <input type="text" id="city_name" name="city_name" required>

    <label for="abbreviation">Abréviation :</label>
    <input type="text" id="abbreviation" name="abbreviation" required maxlength="5">

    <button type="submit">Ajouter</button>
</form>

<!-- Bouton de retour -->
<div class="back-button">
    <a href="index.php?action=teams">Retour à la Liste des Équipes</a>
</div>

<script>
    // Vérification côté client avant l'envoi du formulaire
    document.querySelector('form').addEventListener('submit', function (e) {
        const teamName = document.getElementById('team_name').value.trim();
        const cityName = document.getElementById('city_name').value.trim();
        const abbreviation = document.getElementById('abbreviation').value.trim();

        if (!teamName || !cityName || !abbreviation) {
            e.preventDefault();
            alert('Tous les champs sont requis.');
        }
    });
</script>

</body>
</html>
