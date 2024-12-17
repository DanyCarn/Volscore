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
</ul>

<?php
$content = ob_get_clean();
require_once 'gabarit.php';
?>
