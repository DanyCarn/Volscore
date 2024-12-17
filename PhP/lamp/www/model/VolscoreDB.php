<?php
require 'IVolscoreDb.php';

class VolscoreDB implements IVolscoreDb {

    public static function connexionDB()
    {
        require 'credentials.php';
        $PDO = new PDO("mysql:host=$hostname; port=$portnumber; dbname=$database;", $username, $password);
        $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $PDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $PDO;
    }
    
    public static function executeInsertQuery($query) : ?int
    {
        try
        {
            $dbh = self::connexionDB();
            $statement = $dbh->prepare($query); // Prepare query
            $statement->execute(); // Executer la query
            $res = $dbh->lastInsertId();
            $dbh = null;
            return $res;
        } catch (PDOException $e) {
            return null;
        }
    }

    public static function executeUpdateQuery($query)
    {
        try
        {
            $dbh = self::connexionDB();
            $statement = $dbh->prepare($query); // Prepare query
            $statement->execute(); // Executer la query
            $dbh = null;
        } catch (PDOException $e) {
        }
    }

    public static function getTeams() : array
    {
        try
        {
            $dbh = self::connexionDB();
            $query = "SELECT * FROM teams";
            $statement = $dbh->prepare($query); // Prepare query
            $statement->execute(); // Executer la query
            $res = [];
            while ($row = $statement->fetch()) {
                $res[] = new Team($row);
            }
            $dbh = null;
            return $res;
        } catch (PDOException $e) {
            print 'Error!:' . $e->getMessage() . '<br/>';
            return null;
        }
    }
    
    public static function getGames() : array
    {
        try
        {
            $dbh = self::connexionDB();
            $query = 
                "SELECT games.id as number, type, level,category,league,receiving_id as receivingTeamId,r.name as receivingTeamName,visiting_id as visitingTeamId,v.name as visitingTeamName,location as place,venue,moment ".
                "FROM games INNER JOIN teams r ON games.receiving_id = r.id INNER JOIN teams v ON games.visiting_id = v.id";
            $statement = $dbh->prepare($query); // Prepare query
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute();
            $res = [];
            while ($rec = $statement->fetch()) {
                $game = new Game($rec);
                $game->scoreReceiving = 0;
                $game->scoreVisiting = 0;
                foreach (self::getSets($game) as $set) {
                    if (VolscoreDB::setIsOver($set)) {
                        if ($set->scoreReceiving > $set->scoreVisiting) {
                            $game->scoreReceiving++;
                        } else {
                            $game->scoreVisiting++;
                        }
                    }
                }
                $res[] = $game;
            }
            return $res;
        } catch (PDOException $e) {
            print 'Error!:' . $e->getMessage() . '<br/>';
            return null;
        }
    }

    public static function getGamesByTime($period) : array
    {
        $query = "SELECT games.id as number, type, level,category,league,receiving_id as receivingTeamId,r.name as receivingTeamName,visiting_id as visitingTeamId,v.name as visitingTeamName,location as place,venue,moment " .
                 "FROM games INNER JOIN teams r ON games.receiving_id = r.id INNER JOIN teams v ON games.visiting_id = v.id ";
    
        switch ($period) {
            case TimeInThe::Past:
                $query .= "WHERE moment < now()";
                break;
            case TimeInThe::Present:
                $query .= "WHERE DATE(moment) = DATE(now())";
                break;
            case TimeInThe::Future:
                $query .= "WHERE moment > now()";
                break;
        }
        $query .= " ORDER BY moment, games.id";
        try {
            $pdo = self::connexionDB();
            $statement = $pdo->prepare($query);
            $statement->setFetchMode(PDO::FETCH_CLASS, 'Game');
            $statement->execute();
            $res = $statement->fetchAll();
            foreach ($res as $i => $game) {
                $res[$i]->scoreReceiving = 0;
                $res[$i]->scoreVisiting = 0;
                foreach (self::getSets($game) as $set) {
                    if ($set->scoreReceiving > $set->scoreVisiting) {
                        $res[$i]->scoreReceiving++;
                    } else {
                        $res[$i]->scoreVisiting++;
                    }
                }
            }
            return $res;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return array(); // empty in case of error
    }
    
    public static function getTeam($number) : Team
    {
        try
        {
            $dbh = self::connexionDB();
            $query = "SELECT * FROM teams WHERE id = $number";
            $statement = $dbh->prepare($query); // Prepare query
            $statement->execute(); // Executer la query
            $queryResult = $statement->fetch(); // Affiche les résultats
            $dbh = null;
            return new Team($queryResult);
        } catch (PDOException $e) {
            print 'Error!:' . $e->getMessage() . '<br/>';
            return null;
        }
    }

    public static function teamHasPlayed($team) : bool
    {
        foreach (self::getGamesByTime(TimeInThe::Past) as $game) {
            // a game is played if it's in the past and one team won 3 sets
            if (($game->receivingTeamId == $team->id || $game->visitingTeamId == $team->id) && ($game->scoreReceiving == 3 || $game->scoreVisiting == 3)) return true;
        }
        return false;
    }

    public static function deleteTeam ($teamid) : bool
    {
        try
        {
            $dbh = self::connexionDB();
            $statement = $dbh->prepare("DELETE FROM teams WHERE id=$teamid"); // Prepare query
            $statement->execute(); // Executer la query
            $dbh = null;
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function renameTeam($number, $newname) : bool
    {
        if (strlen($newname) > 2) { // minimal length = 3
            try
            {
                $dbh = self::connexionDB();
                $statement = $dbh->prepare("UPDATE teams SET name='$newname' WHERE id=$number"); // Prepare query
                $statement->execute(); // Executer la query
                $dbh = null;
                return true;
            } catch (PDOException $e) {
                return false;
            }
        }
        return false;
    }

    public static function getGame($number) : ?Game
    {
        try
        {
            $dbh = self::connexionDB();
            $query =
                "SELECT games.id as number, type, level,category,league,receiving_id as receivingTeamId,r.name as receivingTeamName,visiting_id as visitingTeamId,v.name as visitingTeamName,location as place,venue,moment,toss " .
                "FROM games INNER JOIN teams r ON games.receiving_id = r.id INNER JOIN teams v ON games.visiting_id = v.id " .
                "WHERE games.id=$number";
            $statement = $dbh->prepare($query); // Prepare query
            $statement->execute(); // Executer la query
            if (!($queryResult = $statement->fetch())) throw new Exception("Game not found"); 
            $dbh = null;
            $res = new Game($queryResult);
            // Update score
            $res->scoreReceiving = 0;
            $res->scoreVisiting = 0;
            $sets = self::getSets($res);
            foreach ($sets as $set)
            {
                if (VolscoreDB::setIsOver($set)) {
                    if ($set->scoreReceiving > $set->scoreVisiting)
                    {
                        $res->scoreReceiving++;
                    }
                    else
                    {
                        $res->scoreVisiting++;
                    }
                }
            }
            return $res;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function saveGame($game)
    {
        $query = "UPDATE games SET ". 
            "type = '{$game->type}',".
            "level = '{$game->level}',".
            "category = '{$game->category}',".
            "league = '{$game->league}',".
            "location = '{$game->location}',".
            "venue = '{$game->venue}',".
            "moment = '{$game->moment}',".
            "toss = '{$game->toss}' ". 
            "WHERE id={$game->number}";
        return self::executeUpdateQuery($query);
    }

    
    public static function getMembers($teamid) : array
    {
        try
        {
            $dbh = self::connexionDB();
            $query = "SELECT * FROM members WHERE team_id = $teamid";
            $statement = $dbh->prepare($query); // Prepare query    
            $statement->execute(); // Executer la query
            $res = [];
            while ($row = $statement->fetch()) {
                $res[] = new Member($row);
            }
            $dbh = null;
            return $res;
        } catch (PDOException $e) {
            print 'Error!:' . $e->getMessage() . '<br/>';
            return null;
        }
    }

    public static function getMember($memberid) : ?Member
    {
        try
        {
            $dbh = self::connexionDB();
            $query = "SELECT * FROM members WHERE id = $memberid";
            $statement = $dbh->prepare($query); // Prepare query
            $statement->execute(); // Executer la query
            if (!$queryResult = $statement->fetch()) return null;
            $dbh = null;
            return new Member($queryResult);
        } catch (PDOException $e) {
            print 'Error!:' . $e->getMessage() . '<br/>';
            return null;
        }
    }

    public static function getCaptain($team) : Member
    {
        try
        {
            $dbh = self::connexionDB();
            $query = "SELECT * FROM members WHERE team_id = {$team->id} AND role='C'";
            $statement = $dbh->prepare($query); // Prepare query
            $statement->execute(); // Executer la query
            $queryResult = $statement->fetch(); // Affiche les résultats
            $dbh = null;
            
            return new Member($queryResult);
        } catch (PDOException $e) {
            print 'Error!:' . $e->getMessage() . '<br/>';
            return null;
        }
    }

    public static function getLibero($team) : Member
    {
        try
        {
            $dbh = self::connexionDB();
            $query = "SELECT * FROM members WHERE team_id = {$team->id} AND libero=1";
            $statement = $dbh->prepare($query); // Prepare query
            $statement->execute(); // Executer la query
            $queryResult = $statement->fetch(); // Affiche les résultats
            $dbh = null;
            return new Member($queryResult);
        } catch (PDOException $e) {
            print 'Error!:' . $e->getMessage() . '<br/>';
            return null;
        }
    }

    public static function createGame($game) : ?int
    {
        $query = "INSERT INTO games (type,level,category,league,location,venue,moment,receiving_id,visiting_id) ". 
        "VALUES('{$game->type}','{$game->level}','{$game->category}','{$game->league}','{$game->location}','{$game->venue}','{$game->moment}',{$game->receivingTeamId},{$game->visitingTeamId});";
        return self::executeInsertQuery($query);
    }

    public static function getBenchPlayers($gameid,$setid,$teamid)
    {
        throw new Exception("Not implemented yet");
    }

    public static function getRoster($gameid, $teamid) : array
    {
        try
        {
            $dbh = self::connexionDB();
            $query = "SELECT members.id,members.first_name,members.last_name,members.role,members.license,players.id as playerid, players.number,players.validated ".
                    "FROM players INNER JOIN members ON member_id = members.id ". 
                    "WHERE game_id = $gameid AND members.team_id = $teamid";
            $statement = $dbh->prepare($query); // Prepare query    
            $statement->execute(); // Executer la query
            $res = [];
            while ($row = $statement->fetch()) {
                $member = new Member($row);
                // WARNING: Trick: add some contextual player info to the Member object
                $member->playerInfo = ['playerid' => $row['playerid'], 'number' => $row['number'], 'validated' => $row['validated']];
                $res[] = $member;
            }
            $dbh = null;
            return $res;
        } catch (PDOException $e) {
            print 'Error!:' . $e->getMessage() . '<br/>';
            return null;
        }
    }

    public static function validatePlayer($gameid,$memberid)
    {
        $query = "UPDATE players SET validated = 1 WHERE game_id=$gameid AND member_id=$memberid";
        return self::executeUpdateQuery($query);
    }

    public static function getSets($game) : array
    {
        $dbh = self::connexionDB();
        $res = array();
        
        $query = "SELECT sets.id, number, start, end, game_id, ".
        "(SELECT COUNT(points.id) FROM points WHERE team_id = receiving_id and set_id = sets.id) as recscore, ".
        "(SELECT COUNT(points.id) FROM points WHERE team_id = visiting_id and set_id = sets.id) as visscore ".
        "FROM games INNER JOIN sets ON games.id = sets.game_id ".
        "WHERE game_id = $game->number ".
        "ORDER BY sets.number";
        
        $statement = $dbh->prepare($query); // Prepare query
        $statement->execute(); // Executer la query
        while ($row = $statement->fetch()) {
            $newset = array(
                "game" => $row['game_id'],
                "number" => $row['number']
            );
            $newset['id'] = $row['id'];
            if (!is_null($row['start'])) $newset['start'] = $row['start'];
            if (!is_null($row['end'])) $newset['end'] = $row['end'];
            if (!is_null($row['recscore'])) $newset['scoreReceiving'] = intval($row['recscore']);
            if (!is_null($row['visscore'])) $newset['scoreVisiting'] = intval($row['visscore']);
        
            array_push($res, new Set($newset));
        }
        $dbh = null;
        return $res;
      }
      
    public static function gameIsOver($game) : bool
    {
        $sets = VolscoreDB::getSets($game);
        $recwin = 0;
        $viswin = 0;
        foreach ($sets as $set) {
            if ($set->scoreReceiving > $set->scoreVisiting) $recwin++;
            if ($set->scoreReceiving < $set->scoreVisiting) $viswin++;
        }
        return ($recwin == 3 || $viswin == 3);
    }
      
    public static function getSet($setid) : Set
    {
        try
        {
            $dbh = self::connexionDB();
            $query = "SELECT *,". 
                "(SELECT COUNT(points.id) FROM points WHERE team_id = receiving_id and set_id = sets.id) as scoreReceiving, ".
                "(SELECT COUNT(points.id) FROM points WHERE team_id = visiting_id and set_id = sets.id) as scoreVisiting ".
                "FROM games INNER JOIN sets ON games.id = sets.game_id ". 
                "WHERE sets.id=$setid";
            $statement = $dbh->prepare($query); // Prepare query
            $statement->execute(); // Executer la query
            $queryResult = $statement->fetch(); // Affiche les résultats
            $dbh = null;
            return new Set($queryResult);
        } catch (PDOException $e) {
            print 'Error!:' . $e->getMessage() . '<br/>';
            return null;
        }
    }

    public static function getSetScoringSequence($set) : array
    {
        try
        {
            $dbh = self::connexionDB();
            $query = "SELECT * FROM points WHERE set_id={$set->id}";
            $statement = $dbh->prepare($query); // Prepare query
            $statement->execute(); // Executer la query
            $receivingScores = [];
            $visitingScores = [];
            $servingTeamId = 0;
            $points = 0;
            while ($point = $statement->fetch()) {
                $points++;
                if ($point['team_id'] != $servingTeamId) {
                    $lastTotal = isset($pointsOnServe[$servingTeamId]) ? $pointsOnServe[$servingTeamId][count($pointsOnServe[$servingTeamId])-1] : 0;
                    $pointsOnServe[$servingTeamId][] = $lastTotal+$points;
                    $points = 0;
                    $servingTeamId = $point['team_id'];
                }
            }
            // score the last points
            $points++;
            $lastTotal = isset($pointsOnServe[$servingTeamId]) ? $pointsOnServe[$servingTeamId][count($pointsOnServe[$servingTeamId])-1] : 0;
            $pointsOnServe[$servingTeamId][] = $lastTotal+$points;
            $dbh = null;
            return $pointsOnServe;
        } catch (PDOException $e) {
            print 'Error!:' . $e->getMessage() . '<br/>';
            return null;
        }
    }

    public static function setIsOver($set) : bool
    {
        $score1 = 0;
        $score2 = 0;
        $limit = $set->number == 5 ? 15 : 25;
        $pdo = self::connexionDB();
        $stmt = $pdo->prepare("SELECT COUNT(id) as points, team_id 
                            FROM points 
                            WHERE set_id = :set_id 
                            GROUP BY team_id");
        $stmt->bindValue(':set_id', $set->id);
        $stmt->execute();

        $result = $stmt->fetchAll();
        if(count($result) >= 1) $score1 = $result[0]['points'];
        if(count($result) >= 2) $score2 = $result[1]['points'];

        // Assess
        if($score1 < $limit && $score2 < $limit) return false; // no one has enough points
        if(abs($score2-$score1) < 2) return false; // one team has enough points but a 1-point lead only
        return true; // if we get there, we have a winner

    }

    public static function addSet($game) : Set
    {
        $sets = VolscoreDB::getSets($game);
        if (count($sets) >= 5) throw new Exception('Ajout de set à un match qui en a déjà 5');
        $newset = new Set();
        $newset->game_id = $game->number;
        $newset->number = count($sets)+1;
        $query = "INSERT INTO sets (number,game_id) VALUES(". $newset->number .",". $newset->game_id .");";
        $newset->id = self::executeInsertQuery($query);
        return $newset;
    }
      
    private static function getLastPoint ($set) : ?Point
    {
        $pdo = self::connexionDB();
        $stmt = $pdo->prepare("SELECT * FROM points WHERE set_id = :set_id ORDER BY id DESC LIMIT 1");
        $stmt->bindValue(':set_id', $set->id);
        $stmt->execute();
        if (!$record=$stmt->fetch()) return null;
        return (new Point($record));
    }

    private static function getPointBeforeLast ($set)
    {
        $pdo = self::connexionDB();
        $stmt = $pdo->prepare("SELECT * FROM points WHERE set_id = :set_id ORDER BY id DESC LIMIT 2");
        $stmt->bindValue(':set_id', $set->id);
        $stmt->execute();
        $stmt->fetch(); // "burn" one
        if (!$record=$stmt->fetch()) return null;
        return (new Point($record));
    }

    private static function getLastPointOfTeam ($set, $teamid) : ?Point
    {
        $pdo = self::connexionDB();
        $stmt = $pdo->prepare("SELECT * FROM points WHERE set_id = :set_id AND team_id = $teamid ORDER BY id DESC LIMIT 1");
        $stmt->bindValue(':set_id', $set->id);
        $stmt->execute();
        if (!$record=$stmt->fetch()) return null;
        return (new Point($record));
    }

    public static function addPoint($set, $receiving)
    {
        $game = self::getGame($set->game_id);
        $scoringTeamId = ($receiving ? $game->receivingTeamId : $game->visitingTeamId);

        // get last point of the set
        $lastPoint = self::getLastPoint($set);
        // get last point of the set score by that team
        $lastPointOfTeam = self::getLastPointOfTeam($set, $scoringTeamId);

        // use info for rotation        
        if (!$lastPoint) {
            $position = 1; // at the beginning of the game, serve is on position 1 on both sides
            // save set start time
            $query = "UPDATE `sets` SET start = CURRENT_TIMESTAMP WHERE `sets`.id = ".$set->id;
            self::executeUpdateQuery($query);
        } elseif (!$lastPointOfTeam) {
            $position = 2; // first point of the serve-receiving team
        } elseif ($lastPoint->team_id != $scoringTeamId){
            $position = $lastPointOfTeam->position_of_server % 6 + 1; // change of serve -> rotation
        } else {
            $position = $lastPoint->position_of_server; // no change
        }
        
        $query =
             "INSERT INTO points (team_id, set_id, position_of_server) " .
             "VALUES(". ($receiving ? $game->receivingTeamId : $game->visitingTeamId) . ",".$set->id.",$position);";
        self::executeInsertQuery($query);
    }

    public static function addTimeOut($teamid, $setid)
    {
        $lastPoint = self::getLastPoint(self::getSet($setid));
        $query = "INSERT INTO timeouts (team_id, set_id, point_id) VALUES($teamid,$setid,".$lastPoint->id.");";
        self::executeInsertQuery($query);
    }

    public static function getTimeouts($teamid,$setid) : array
    {
        $query = "SELECT pts.`timestamp` ,".
                    "(SELECT COUNT(pts2.id) FROM points pts2 WHERE team_id = g.receiving_id and set_id = s.id and pts2.id <= pts.id) as scoreReceiving, ".
                    "(SELECT COUNT(pts3.id) FROM points pts3 WHERE team_id = g.visiting_id and set_id = s.id and pts3.id <= pts.id) as scoreVisiting ".
                "FROM timeouts ".
                    "INNER JOIN teams t ON team_id = t.id ".
                    "INNER JOIN points pts ON point_id = pts.id ".
                    "INNER JOIN `sets` s ON timeouts.set_id = s.id ".
                    "INNER JOIN games g ON game_id = g.id ".
                "WHERE s.id= :setid and t.id= :teamid ;";
        
        $dbh = self::connexionDB();
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(':setid',$setid);
        $stmt->bindValue(':teamid',$teamid);
        $stmt->execute();
        return $stmt->fetchall();
    }

    public static function registerSetEndTimestamp($setid)
    {
        $query = "UPDATE `sets` SET end = CURRENT_TIMESTAMP WHERE `sets`.id = ".$setid;
        self::executeUpdateQuery($query);
    }

    public static function nextServer($set) : Member
    {
        $game = self::getGame($set->game_id);
        // get last point of the set
        $lastPoint = self::getLastPoint($set);

        // use info
        if (!$lastPoint) {
            $servingTeamId = $game->receivingTeamId; // by default for now (ignore toss)
            $position = 1;
        } else {            
            $servingTeamId = $lastPoint->team_id; 
            $position = $lastPoint->position_of_server; 
        }

        // find the player
        $positions = self::getPositions($set->id,$servingTeamId);
        return $positions[$position-1];
    }

    public static function numberOfSets($game) : int
    {
        return count(self::getSets($game));
    }

    public static function makePlayer($memberid,$gameid) : bool
    {
        $game = self::getGame($gameid);
        if ($game == null) return false;
        $member = self::getMember($memberid);
        if ($member == null) return false;
        if ($member->team_id != $game->receivingTeamId && $member->team_id != $game->visitingTeamId) return false;

        $query =
             "INSERT INTO players (game_id, member_id, number) " .
             "VALUES($gameid,$memberid,".$member->number.");";
        self::executeInsertQuery($query);
        return true;
    }

    public static function getPlayer($memberid,$gameid) : ?Member
    {
        $pdo = self::connexionDB();
        $query = "SELECT members.id,members.first_name,members.last_name,members.role,members.license,members.team_id,players.id as playerid, players.number ".
        "FROM players INNER JOIN members ON member_id = members.id ". 
        "WHERE members.id = $memberid AND players.game_id = $gameid";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        if ($row = $stmt->fetch()) {
            $player = new Member($row);
            // WARNING: Trick: add some contextual player info to the Member object
            $player->playerInfo = ['playerid' => $row['playerid'], 'number' => $row['number']];
            return $player;
        } else {
            return null;
        }
    }

    public static function setPositions($setid, $teamid, $pos1, $pos2, $pos3, $pos4, $pos5, $pos6, $final=0)
    {
        $query =
             "INSERT INTO positions (set_id, team_id, player_position_1_id, player_position_2_id, player_position_3_id, player_position_4_id, player_position_5_id, player_position_6_id, final) " .
             "VALUES($setid, $teamid, $pos1, $pos2, $pos3, $pos4, $pos5, $pos6, $final);";
        self::executeInsertQuery($query);
    }

    public static function updatePositions($setid, $teamid, $pos1, $pos2, $pos3, $pos4, $pos5, $pos6, $final=0)
    {
        $query =
             "UPDATE positions SET player_position_1_id=$pos1, player_position_2_id=$pos2, player_position_3_id=$pos3, player_position_4_id=$pos4, player_position_5_id=$pos5, player_position_6_id=$pos6,final=$final " .
             "WHERE set_id = $setid AND team_id = $teamid;";
        self::executeUpdateQuery($query);
    }

    public static function getPositions($setid, $teamid, &$isFinal = NULL) : array
    {
        try
        {
            $res = [];
            $dbh = self::connexionDB();
            if ($setid > 0) { 
                $query = "SELECT * FROM positions WHERE set_id=$setid AND team_id=$teamid;";
            } else { // get last used positions
                $query = "SELECT * FROM positions WHERE team_id=$teamid ORDER BY id DESC LIMIT 1;";
            }
            $statement = $dbh->prepare($query); // Prepare query    
            $statement->execute(); // Executer la query
            $positions = $statement->fetch();
            if (!$positions) return $res;
            if ($setid == 0) { // get it from the position sheet
                $setid = $positions['set_id'];
            }
            // build the list
            for ($pos = 1; $pos <= 6; $pos++) {
                $query = "SELECT members.id,members.first_name,members.last_name,members.role,members.license,members.team_id,players.id as playerid, players.number ".
                "FROM players INNER JOIN members ON member_id = members.id INNER JOIN positions ON player_position_".$pos."_id = players.id ". 
                "WHERE set_id = $setid AND positions.team_id = $teamid";
                $statement = $dbh->prepare($query); // Prepare query    
                $statement->execute(); // Executer la query
                $row = $statement->fetch();
                $member = new Member($row);
                // WARNING: Trick: add some contextual player info to the Member object
                $member->playerInfo = ['playerid' => $row['playerid'], 'number' => $row['number']];
                $res[] = $member;
            }
            $isFinal = $positions['final'];
            return $res;
        } catch (PDOException $e) {
            print 'Error!:' . $e->getMessage() . '<br/>';
            return null;
        }
    }

    public static function giveBooking ($playerid, $setid, $severity)
    {
        $lastPoint = self::getLastPoint(self::getSet($setid));
        $pdo = self::connexionDB();
        $query = "INSERT INTO bookings (player_id, point_id, severity) VALUES($playerid, ".$lastPoint->id.", $severity);";
        self::executeInsertQuery($query);
    }
    public static function getAllTeams() {
        $pdo = self::getPDO();
        $query = 'SELECT id, name, city, abbreviation FROM teams';
        $stmt = $pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Renvoie un tableau associatif
    }
    
    public static function getBookings($team,$set) : array
    {
        $query = "SELECT pts.`timestamp` , p.`number`, m.last_name, severity ,  ".
                    "(SELECT COUNT(pts2.id) FROM points pts2 WHERE team_id = g.receiving_id and set_id = s.id and pts2.id <= pts.id) as scoreReceiving, ".
                    "(SELECT COUNT(pts3.id) FROM points pts3 WHERE team_id = g.visiting_id and set_id = s.id and pts3.id <= pts.id) as scoreVisiting ".
                "FROM bookings ".
                    "INNER JOIN players p ON player_id = p.id ".
                    "INNER JOIN members m ON member_id = m.id ".
                    "INNER JOIN points pts ON point_id = pts.id ".
                    "INNER JOIN `sets` s ON set_id = s.id ".
                    "INNER JOIN games g ON s.game_id = g.id ".
                "WHERE s.id= :setid and m.team_id= :teamid ;";
        
        $dbh = self::connexionDB();
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(':setid',$set->id);
        $stmt->bindValue(':teamid',$team->id);
        $stmt->execute();
        return $stmt->fetchall();
    }

}



?>
