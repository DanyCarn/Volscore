<?php
$title = 'Equipes';

ob_start();
?>

<h1>Equipes</h1>
<ul>

<?php
foreach ($teams as $team)
{
    echo "<li>".$team->name."<a href='?action=deleteTeam&teamid=".$team->id."' class='btn')'><img src='../images/delete.png' width='20'></a> </li>";
}
?>
</ul>

<?php
$content = ob_get_clean();
require_once 'gabarit.php';
?>
