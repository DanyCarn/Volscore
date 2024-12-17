<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Centré</title>
    <style>
        /* Centrage global de la page */
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0; /* Optionnel : couleur de fond de la page */
            font-family: Arial, sans-serif;
        }

        /* Style du conteneur du formulaire */
        .form-container {
            background-color: #ffffff; /* Fond blanc pour le formulaire */
            padding: 20px; /* Espacement interne */
            border-radius: 8px; /* Coins arrondis */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Ombre autour du formulaire */
            width: 100%;
            max-width: 400px; /* Largeur maximale du formulaire */
            text-align: center; /* Texte centré */
        }

        /* Style des labels */
        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            text-align: left; /* Alignement du texte des labels */
        }

        /* Style des champs de saisie */
        .form-container input {
            width: 100%; /* Champs prennent toute la largeur */
            padding: 5px; /* Espacement interne */
            margin-bottom: 16px; /* Espacement entre les champs */
            border: 1px solid #ccc; /* Bordure grise claire */
            border-radius: 4px; /* Coins arrondis */
            font-size: 20px; /* Taille de texte */
        }

        /* Style du bouton */
        .form-container button {
            background-color: #007bff; /* Couleur de fond bleu */
            color: #fff; /* Texte blanc */
            border: none; /* Suppression des bordures */
            padding: 10px 20px; /* Espacement interne */
            border-radius: 4px; /* Coins arrondis */
            cursor: pointer; /* Curseur pointeur */
            font-size: 16px; /* Taille de texte */
        }

        /* Effet au survol du bouton */
        .form-container button:hover {
            background-color: #0056b3; /* Couleur plus sombre au survol */
        }
    </style>
</head>
<body>
    <div class="form-container">
        <!-- Formulaire d'ajout -->
        <form action="addTeams.php" method="post">
            <label for="team_name">Nom de l'Équipe :</label>
            <input type="text" id="team_name" name="team_name" required>

            <label for="city_name">Nom de la Ville :</label>
            <input type="text" id="city_name" name="city_name" required>

            <label for="abbreviation">Abréviation :</label>
            <input type="text" id="abbreviation" name="abbreviation" required maxlength="5">

            <button type="submit">Valider</button>
        </form>
        <br>
         <!-- Bouton pour revenir à la page d'accueil -->
        <!-- Bouton de retour -->
<div class="back-button">
    <a href="index.php?action=teams">Retour à la Liste des Équipes</a>
</div>
    </div>
</body>
</html>
