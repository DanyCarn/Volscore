<?php
error_reporting(E_ERROR);

$action = isset($_GET['action']) ? $_GET['action'] : 'home';
require_once 'controller/controller.php';
require_once 'model/VolscoreDB.php';
require_once 'vendor/autoload.php';
require_once 'helpers/helpers.php';

switch ($action)
{
    case 'mark':
        markGame($_GET['id']);
        break;
    case 'validate':
        validateTeamForGame($_GET['team'],$_GET['game']);
        break;
    case 'prepareSet':
        prepareSet($_GET['id']);
        break;
    case 'registerToss':
        registerToss($_POST['gameid'], $_POST['cmdTossWinner']);
        break;
    case 'keepScore':
        keepScore($_GET['setid']);
        break;
    case 'timeout':
        timeout($_GET['teamid'],$_GET['setid']);
        break;
    case 'selectBooking':
        showBookings($_GET['teamid'],$_GET['setid']);
        break;
    case 'registerBooking':
        registerBooking($_POST['dpdPlayer'],$_POST['setid'],$_POST['severity']);
        break;
    case 'continueGame':
        continueGame($_GET['gameid']);
        break;
    case 'resumeScoring':
        resumeScoring($_GET['gameid']);
        break;
    case 'scorePoint':
        scorePoint($_POST['setid'],$_POST['receiving']);
        break;
    case 'setPositions':
        setPositions($_POST['gameid'],$_POST['setid'],$_POST['teamid'],$_POST['position1'],$_POST['position2'],$_POST['position3'],$_POST['position4'],$_POST['position5'],$_POST['position6'],isset($_POST['final']) ? 1 : 0);
        break;
    case 'teams':
        showTeams();
        break;
    case 'games':
        showGames();
        break;
    case 'sheet':
        showGame($_GET['gameid']);
        break;
    case 'unittests':
        executeUnitTests();
        break;
    case 'newTeam':
        addTeam();
        break;
        // Action pour enregistrer une nouvelle équipe
    case 'save_team':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize inputs to prevent SQL injection
            $team_name = htmlspecialchars($_POST['team_name']) ?? '';

            // Vérifier si tous les champs sont remplis
            if (!empty($team_name)) {
                // Appel de la méthode pour ajouter l'équipe
                VolscoreDB::saveTeam($team_name);
                // Redirection vers la page de la liste des équipes
                header('Location: ?action=teams');
                exit;  // Arrêter l'exécution après la redirection
            } else {
                // Afficher un message d'erreur si un champ est manquant
                $message = "Tous les champs sont requis !";
                require_once 'view/error.php';
            }
        }
    case 'deleteTeam':
        deleteteam($_GET['teamid']);
        break;
    default:
        require_once 'view/home.php';
}
?>
