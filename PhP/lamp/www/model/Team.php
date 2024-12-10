<?php

/**
 * Each object of the Team class represents one row of the teams table
 */
class Team extends Model {
    public $id;     // team number
    public $name;   // team name
    public $city;   // city
    public $abbreviation; // abbreviation (if applicable)
}

?>

<h3>Équipes</h3>

<!-- Conteneur principal pour afficher la liste des équipes et le bouton -->
<div style="border: 1px solid #ccc; padding: 20px; border-radius: 10px; width: 300px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); text-align: center;">

    <!-- Affichage des équipes dans une liste -->
    <div style="margin-bottom: 20px;">
        <?php foreach ($teams as $team): ?>
            <div style="border-bottom: 1px solid #eee; padding: 10px;">
                <h4><?= htmlspecialchars($team['name']) ?></h4>
                <p>Ville : <?= htmlspecialchars($team['city']) ?></p>
                <p>Abréviation : <?= htmlspecialchars($team['abbreviation']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Bouton "Ajouter une Équipe" en bas du cadre -->
    <a href="?action=add_team">
        <button style="padding: 10px 20px; margin-top: 10px;">Ajouter une Équipe</button>
    </a>
</div>

