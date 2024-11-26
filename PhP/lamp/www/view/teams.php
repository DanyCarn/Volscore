<?php
$title = 'Equipes';

ob_start();
?>

<h1>Equipes</h1>
<ul>

<?php
foreach ($teams as $team)
{
    echo "<li>".$team->name."<a href='?action=&id=".$game->number."' class='btn btn-sm btn-primary m-1'><img src='../images/suppress.png' width='20'></a> </li>";
}
?>
</ul>

<?php
$content = ob_get_clean();
require_once 'gabarit.php';
?>
