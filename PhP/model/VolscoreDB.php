<?php
require 'IVolscoreDb.php';

class VolscoreDB implements IVolscoreDb {

    private static function connexionDB()
    {
        require '.credentials.php';
        $PDO = new PDO('mysql:host=localhost;dbname=volscore', 'root', 'root');
        return $PDO;
    }
    

    public static function getTeams()
    {
        try
        {
            $dbh = self::connexionDB();
            $query = "SELECT * FROM teams";
            $statement = $dbh->prepare($query); // Prepare query
            $statement->execute(); // Executer la query
            $queryResult = $statement->fetchAll(); // Affiche les résultats
            $dbh = null;
            return $queryResult;
        } catch (PDOException $e) {
            print 'Error!:' . $e->getMessage() . '<br/>';
            return null;
        }
    }
    
    public static function getGames()
    {
        try
        {
            $dbh = self::connexionDB();
            $query = 
                "SELECT games.id, type, level,category,league,location,venue,moment,receiving_id,r.name as receiving,visiting_id,v.name as visiting ".
                "FROM games INNER JOIN teams r ON games.receiving_id = r.id INNER JOIN teams v ON games.visiting_id = v.id";
            $statement = $dbh->prepare($query); // Prepare query
            $statement->execute(); // Executer la query
            $queryResult = $statement->fetchAll(); // Affiche les résultats
            $dbh = null;
            return $queryResult;
        } catch (PDOException $e) {
            print 'Error!:' . $e->getMessage() . '<br/>';
            return null;
        }
    }
    
    public static function getTeam($number)
    {
        throw new Exception("Not implemented yet");
    }
    public static function getGame($number)
    {
        throw new Exception("Not implemented yet");
    }
    public static function getPlayers($teamid)
    {
        throw new Exception("Not implemented yet");
    }
    public static function getCaptain($teamid)
    {
        throw new Exception("Not implemented yet");
    }
    public static function getBenchPlayers($gameid,$setid,$teamid)
    {
        throw new Exception("Not implemented yet");
    }

}


?>
