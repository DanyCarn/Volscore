<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Nouvelle Équipe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            width: 300px;
            gap: 15px;
        }
        label {
            font-weight: bold;
        }
        input, button {
            padding: 10px;
            font-size: 1rem;
        }
        button {
            cursor: pointer;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .back-button {
            margin-top: 15px;
            display: inline-block;
        }
        .back-button a {
            text-decoration: none;
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            border-radius: 5px;
            font-size: 1rem;
        }
        .back-button a:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

<h1>Ajouter une Nouvelle Équipe</h1>

<form action="index.php?action=save_team" method="post">
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
