<?php
$title = 'Equipes';

ob_start();
?>

<h1>Equipes</h1>
<ul>

<?php

$games = VolscoreDB::getGames();

foreach ($teams as $team)
{
    $isDeletable = true;
    //création du bouton si l'équipe n'a aucun match planifié
    foreach($games as $game){
        if(!($game->receivingTeamId == $team->id) && !($game->visitingTeamId == $team->id)){
            $isDeletable = true;
        } else {
            $isDeletable = false;
            break;
        }
    }
    if($isDeletable){
        echo "<li>".$team->name."<a href='?action=deleteTeam&teamid=".$team->id."' class='btn')'><img src='../images/delete.png' width='20'></a> </li>";
    } else {
        echo "<li>".$team->name."</li>";
    }
}
?>
 <!-- Bouton "Ajouter une Équipe" en bas du cadre -->
 <a href="?action=newTeam">
        <button style="
            padding: 12px 24px; 
            background-color: #007bff; 
            color: white; 
            border: none; 
            border-radius: 8px; 
            font-size: 16px; 
            cursor: pointer; 
            margin: 20px auto; 
            display: block; 
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
            transition: background-color 0.3s ease, transform 0.2s ease;">
            Ajouter une Équipe
        </button>
    </a>
</ul>

<?php
$content = ob_get_clean();
require_once 'gabarit.php';
?>
