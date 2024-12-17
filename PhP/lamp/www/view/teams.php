<?php
$title = 'Equipes';

ob_start();
?>

<h1>Equipes</h1>
<ul>

<?php
foreach ($teams as $team)
{
    echo "<li>".$team->name."</li>";
}
?>
 <!-- Bouton "Ajouter une Équipe" en bas du cadre -->
 <a href="?action=addTeam.php">
        <button style="padding: 10px 20px; margin-top: 10px;">Ajouter une Équipe</button>
    </a>
</ul>

<?php
$content = ob_get_clean();
require_once 'gabarit.php';
?>
